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
use App\Models\booking_customers;
use App\Models\visa_supplier_Slots;
use DB;
use Hash;
use Carbon\Carbon;

class visaSupplierController extends Controller
{
    public function visa_supplier_ledger(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $customer_id        = $request->customer_id;
            $suppliers_data     = DB::table('visa_Sup')->where('id',$request->supplier_id)->first();
            $suppliers_ledger   = DB::table('visa_supplier_ledger')->where('supplier_id',$request->supplier_id)->get();
            $countries          = DB::table('countries')->get();
            
            return response()->json(['message'=>'success','countries'=>$countries,'supplier_data'=>$suppliers_data,'ledger_data'=>$suppliers_ledger]);
        }else{
            return response()->json(['data'=>'']);
        }
    }
    
    public static function visa_supplier_statement_Season_Working($all_data,$request){
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
    
    public function visa_supplier_statement(Request $request){
        
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            if($userData){
                
                if(isset($request->start_date)){
                    $startDate              = $request->start_date;
                    $endDate                = $request->end_date;
                    $supplier_invoices      = DB::table('add_manage_invoices')
                                               ->where(function($query) use($request){
                                                    $query->whereJsonContains('all_visa_price_details',['visa_supplier_id'=>"".$request->supplier_id.""])
                                                        ->OrWhereJsonContains('all_visa_price_details',['visa_supplier_id'=>$request->supplier_id]);
                                                    })
                                                    ->whereDate('created_at','>=', $startDate)
                                                    ->whereDate('created_at','<=', $endDate)
                                                    ->select('id as invoice_no','all_visa_price_details','created_at')
                                                    ->get();
                                                    // dd($supplier_invoices);
                    $supplier_invoices_arr  = [];
                    if(isset($supplier_invoices)){
                        foreach($supplier_invoices as $supplier_res){
                            $all_visa_price_details = json_decode($supplier_res->all_visa_price_details);
                            if(isset($all_visa_price_details)){
                                foreach($all_visa_price_details as $visa_detial_res){
                                    if($visa_detial_res->visa_supplier_id == $request->supplier_id){
                                        $supplier_invoices_arr[] = (Object)[
                                                'supplier_id'=> $request->supplier_id,
                                                'booking_type'=> "Invoice",
                                                'invoice_id'=> $supplier_res->invoice_no,
                                                'visa_type_name'=> $visa_detial_res->visa_type_name,
                                                'visa_fee_purchase'=> $visa_detial_res->visa_fee_purchase,
                                                'visa_occupied'=> $visa_detial_res->visa_occupied,
                                                'total_price'=> $visa_detial_res->visa_purchase_total,
                                                'created_at' => $supplier_res->created_at
                                            ];
                                    }
                                } 
                            }
                        }
                    }
                    
                    $supplier_invoices   = DB::table('add_manage_invoices')->where('transfer_supplier_id',$request->supplier_id)
                                            ->whereDate('created_at','>=', $startDate)
                                            ->whereDate('created_at','<=', $endDate)
                                            ->select('id as invoice_no','transportation_details','transportation_details_more','created_at')
                                            ->get();
                    $supplier_packages_arr = [];
                    if(isset($supplier_invoices)){
                        foreach($supplier_invoices as $supplier_res){
                            $transportation_details = json_decode($supplier_res->transportation_details);
                            $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                            
                            if(isset($transportation_details)){
                                $total_price = 0;
                                $no_of_vehicle = 0;
                                $price_per_vehicle = 0;
                                $vehcile_type = 0;
                                $pick_up_location = '';
                                $drop_off_location = '';
                                $transportation_type = '';

                                foreach($transportation_details as $trans_res){
                                    if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                                        $total_price += $trans_res->transportation_vehicle_total_price;
                                        
                                        $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                                        $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                                        $vehcile_type = $trans_res->transportation_vehicle_type;
                                        $pick_up_location = $trans_res->transportation_pick_up_location;
                                        $drop_off_location = $trans_res->transportation_drop_off_location;
                                        $transportation_type = $trans_res->transportation_trip_type;
                                    }
                                }
                                
                            if(isset($transportation_details_more)){
                                foreach($transportation_details_more as $trans_res){
                                    $drop_off_location = $trans_res->more_transportation_drop_off_location;
                                }
                            }
                                
                                
                                $supplier_packages_arr[] = (Object)[
                                    'supplier_id'=> $request->supplier_id,
                                    'booking_type'=> "Invoice",
                                    'invoice_id'=> $supplier_res->invoice_no,
                                    'pick_up_location'=> $pick_up_location,
                                    'drop_off_location'=> $drop_off_location,
                                    'transportation_type'=> $transportation_type,
                                    'vehcile_type'=> $vehcile_type,
                                    'price_per_vehicle'=> $price_per_vehicle,
                                    'number_of_vehicles'=> $no_of_vehicle,
                                    'total_price'=> $total_price,
                                    'created_at' => $supplier_res->created_at
                                ];
                            }
                             
                            // print_r($transportation_details);
                            // print_r($transportation_details_more);
                           
                        }
                    }
                    // $supplier_packages = DB::table('tours_2')->where('transfer_supplier_id',$request->supplier_id)
                    //                                     ->whereDate('created_at','>=', $startDate)
                    //                                     ->whereDate('created_at','<=', $endDate)
                    //                                      ->select('tour_id as package_id','transportation_details','transportation_details_more','created_at')
                    //                                      ->get();
                    
                    
                    $supplier_packages_arr = [];
                    // if(isset($supplier_packages)){
                    //     foreach($supplier_packages as $supplier_res){
                    //         $transportation_details = json_decode($supplier_res->transportation_details);
                    //         $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                            
                    //             if(isset($transportation_details)){
                                 
                    //                 $total_price = 0;
                    //                 $no_of_vehicle = 0;
                    //                 $price_per_vehicle = 0;
                    //                 $vehcile_type = 0;
                    //                 $pick_up_location = '';
                    //                 $drop_off_location = '';
                    //                 $transportation_type = '';
    
                    //                 foreach($transportation_details as $trans_res){
                    //                     if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                    //                         $total_price += $trans_res->transportation_vehicle_total_price;
                                            
                    //                         $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                    //                         $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                    //                         $vehcile_type = $trans_res->transportation_vehicle_type;
                    //                         $pick_up_location = $trans_res->transportation_pick_up_location;
                    //                         $drop_off_location = $trans_res->transportation_drop_off_location;
                    //                         $transportation_type = $trans_res->transportation_trip_type;
                    //                     }
                    //                 }
                                    
                    //             if(isset($transportation_details_more)){
                    //                 foreach($transportation_details_more as $trans_res){
                    //                     $drop_off_location = $trans_res->more_transportation_drop_off_location;
                    //                 }
                    //             }
                                    
                    //                  $supplier_packages_arr[] = (Object)[
                    //                                     'supplier_id'=> $request->supplier_id,
                    //                                     'booking_type'=> "Package",
                    //                                     'package_id'=> $supplier_res->package_id,
                    //                                     'pick_up_location'=> $pick_up_location,
                    //                                     'drop_off_location'=> $drop_off_location,
                    //                                     'transportation_type'=> $transportation_type,
                    //                                     'vehcile_type'=> $vehcile_type,
                    //                                     'price_per_vehicle'=> $price_per_vehicle,
                    //                                     'number_of_vehicles'=> $no_of_vehicle,
                    //                                     'total_price'=> $total_price,
                    //                                     'created_at' => $supplier_res->created_at
                    //                                 ];
                    //             }
                            
                    //     }
                    // }
                    
                    $payments_data  = DB::table('recevied_payments_details')
                                        ->where('Criteria','Visa Supplier')
                                        ->where('Content_Ids',$request->supplier_id)
                                        ->whereDate('payment_date','>=', $startDate)
                                        ->whereDate('payment_date','<=', $endDate)
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('created_at')
                                        ->get();
                        
                    $make_payments_data = DB::table('make_payments_details')
                                            ->where('Criteria','Visa Supplier')
                                            ->where('Content_Ids',$request->supplier_id)
                                            ->whereDate('payment_date','>=', $startDate)
                                            ->whereDate('payment_date','<=', $endDate)
                                            ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                                            ->orderBy('created_at')
                                            ->get();
                    
                }else{
                     $supplier_invoices = DB::table('add_manage_invoices')
                                                     ->whereJsonContains('all_visa_price_details',['visa_supplier_id'=>"".$request->supplier_id.""])
                                                     ->OrWhereJsonContains('all_visa_price_details',['visa_supplier_id'=>$request->supplier_id])
                                                     ->select('id as invoice_no','all_visa_price_details','created_at')
                                                     ->get();
    
                    
                    $supplier_invoices_arr = [];
                    if(isset($supplier_invoices)){
                        foreach($supplier_invoices as $supplier_res){
                            $all_visa_price_details = json_decode($supplier_res->all_visa_price_details);

                            if(isset($all_visa_price_details)){
                                foreach($all_visa_price_details as $visa_detial_res){
                                    if($visa_detial_res->visa_supplier_id == $request->supplier_id){
                                        $supplier_invoices_arr[] = (Object)[
                                                'supplier_id'=> $request->supplier_id,
                                                'booking_type'=> "Invoice",
                                                'invoice_id'=> $supplier_res->invoice_no,
                                                'visa_type_name'=> $visa_detial_res->visa_type_name,
                                                'visa_fee_purchase'=> $visa_detial_res->visa_fee_purchase,
                                                'visa_occupied'=> $visa_detial_res->visa_occupied,
                                                'total_price'=> $visa_detial_res->visa_purchase_total,
                                                'created_at' => $supplier_res->created_at
                                            ];
                                    }
                                } 
                            }
                        }
                    }
                    
                    // $supplier_packages = DB::table('tours_2')->where('transfer_supplier_id',$request->supplier_id)
                    //                                      ->select('tour_id as package_id','transportation_details','transportation_details_more','created_at')
                    //                                      ->get();
                    
                    
                    $supplier_packages_arr = [];
                    // if($userData->id != 30){
                    //     if(isset($supplier_packages)){
                    //         foreach($supplier_packages as $supplier_res){
                    //             $transportation_details = json_decode($supplier_res->transportation_details);
                    //             $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                                
                    //                 if(isset($transportation_details)){
                                     
                    //                     $total_price = 0;
                    //                     $no_of_vehicle = 0;
                    //                     $price_per_vehicle = 0;
                    //                     $vehcile_type = 0;
                    //                     $pick_up_location = '';
                    //                     $drop_off_location = '';
                    //                     $transportation_type = '';
        
                    //                     foreach($transportation_details as $trans_res){
                    //                         if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                    //                             $total_price += $trans_res->transportation_vehicle_total_price;
                                                
                    //                             $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                    //                             $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                    //                             $vehcile_type = $trans_res->transportation_vehicle_type;
                    //                             $pick_up_location = $trans_res->transportation_pick_up_location;
                    //                             $drop_off_location = $trans_res->transportation_drop_off_location;
                    //                             $transportation_type = $trans_res->transportation_trip_type;
                    //                         }
                    //                     }
                                        
                    //                 if(isset($transportation_details_more)){
                    //                     foreach($transportation_details_more as $trans_res){
                    //                         $drop_off_location = $trans_res->more_transportation_drop_off_location;
                    //                     }
                    //                 }
                                        
                    //                      $supplier_packages_arr[] = (Object)[
                    //                                         'supplier_id'=> $request->supplier_id,
                    //                                         'booking_type'=> "Package",
                    //                                         'package_id'=> $supplier_res->package_id,
                    //                                         'pick_up_location'=> $pick_up_location,
                    //                                         'drop_off_location'=> $drop_off_location,
                    //                                         'transportation_type'=> $transportation_type,
                    //                                         'vehcile_type'=> $vehcile_type,
                    //                                         'price_per_vehicle'=> $price_per_vehicle,
                    //                                         'number_of_vehicles'=> $no_of_vehicle,
                    //                                         'total_price'=> $total_price,
                    //                                         'created_at' => $supplier_res->created_at
                    //                                     ];
                    //                 }
                                
                    //         }
                    //     }
                    // }
                    
                    
                    $payments_data = DB::table('recevied_payments_details')
                        ->where('Criteria','Visa Supplier')
                        ->where('Content_Ids',$request->supplier_id)
                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                        ->orderBy('created_at')
                        ->get();
                        
                    $make_payments_data = DB::table('make_payments_details')
                        ->where('Criteria','Visa Supplier')
                        ->where('Content_Ids',$request->supplier_id)
                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                        ->orderBy('created_at')
                        ->get();
                }
                
                $supplier_invoices_arr  = collect($supplier_invoices_arr);
                $supplier_packages_arr  = collect($supplier_packages_arr);
                $all_data               = $supplier_invoices_arr->concat($supplier_packages_arr)->concat($payments_data)->concat($make_payments_data)->sortBy('created_at');
                $suppliers_data         = DB::table('visa_Sup')->where('id',$request->supplier_id)->first();
                
                // Season
                $today_Date             = date('Y-m-d');
                $season_Id              = '';
                if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
                    $season_Id          = 'all_Seasons';
                }elseif(isset($request->season_Id) && $request->season_Id > 0){
                    $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
                    $season_Id          = $season_SD->id ?? '';
                }else{
                    $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
                    $season_Id          = $season_SD->id ?? '';
                }
                
                // dd($all_data);
                
                $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
                if($request->customer_id == 4 || $request->customer_id == 54){
                    if($all_data->isEmpty()){
                    }else{
                        // dd($all_data);
                        $all_data   = $this->visa_supplier_statement_Season_Working($all_data,$request);
                        // dd($all_data);
                    }
                }
                // Season
                
                $countries = DB::table('countries')->get();
                
                return response()->json(['message'=>'success','countries'=>$countries,'supplier_data'=>$suppliers_data,'statement_data'=>$all_data,'season_Details'=>$season_Details,'season_Id'=>$season_Id]);
            }else{
                return response()->json(['data'=>'']);
            }
    }
    
    
    public function search_visa_list(Request $request){
        // dd($request->all());
        $request_all_data = json_decode($request->request_data);
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $pax = (int)$request_all_data->no_of_paxs;
        $check_visa_availilbilty = DB::table('visa_Availability')->join('visa_types','visa_types.id','=','visa_Availability.visa_type')
                                                                 ->join('countries','countries.id','=','visa_Availability.country')
                                                                 ->where('visa_Availability.country',$request_all_data->visa_country)
                                                                 ->where('visa_Availability.visa_type',$request_all_data->visa_type)
                                                                 ->whereDate('visa_Availability.availability_from','<=',$request_all_data->departure_data)
                                                                 ->whereDate('visa_Availability.availability_to','>=',$request_all_data->departure_data)
                                                                 ->where('visa_Availability.visa_available','>',$pax)
                                                                 ->where('visa_Availability.customer_id',$userData->id)
                                                                 ->select('visa_Availability.*','visa_Availability.id as $avail_id','visa_types.other_visa_type','countries.name')
                                                                 ->get();
        return response()->json(['status'=>'success','data'=>$check_visa_availilbilty]);
    }
    
    public function search_visa_list_combine(Request $request){
        $request_all_data = json_decode($request->request_data);
        
        $pax = (int)$request_all_data->pax;
        $country = DB::table('countries')->where('name',$request_all_data->country)->select('id','name')->first();
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        
        $check_visa_availilbilty = DB::table('visa_Availability')->join('visa_types','visa_types.id','=','visa_Availability.visa_type')
                                                                 ->join('countries','countries.id','=','visa_Availability.country')
                                                                 ->where('visa_Availability.country',$country->id)
                                                                 ->whereDate('visa_Availability.availability_from','<=',$request_all_data->checkin)
                                                                 ->whereDate('visa_Availability.availability_to','>=',$request_all_data->checkin)
                                                                 ->where('visa_Availability.visa_available','>',$pax)
                                                                 ->where('visa_Availability.customer_id',$userData->id)
                                                                 ->select('visa_Availability.*','visa_Availability.id as $avail_id','visa_types.other_visa_type','countries.name')
                                                                 ->get();
        return response()->json(['status'=>'success','data'=>$check_visa_availilbilty]);
    }
    
    public function visa_booking_invoice(Request $request){
       $booking_data = DB::table('visa_bookings')->where('invoice_no',$request->invoice_no)->first();
       
       $visa_avail_data = json_decode($booking_data->visa_avail_data);
       $visa_type = DB::table('visa_types')->where('id',$visa_avail_data->visa_type)->select('other_visa_type')->first();
       $visa_country = DB::table('countries')->where('id',$visa_avail_data->country)->select('name')->first();

        $visa_type = $visa_type->other_visa_type;
        $visa_country = $visa_country->name;
        if($booking_data){
            return response()->json([
                'status' => 'success',
                'booking_data' => $booking_data,
                'visa_type' => $visa_type,
                'visa_country' => $visa_country,
                // 'markups_details' => $markups,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "Wrong Invoice Number",
            ]);
        }
    }
    
    public function visa_checkout_submit(Request $request){
        // print_r($request->all());
        $request_all_data = json_decode($request->request_all_data);
        // dd($request_all_data);
        $lead_passenger = json_decode($request->lead_passenger);
        
        $hotel_booked = false;
        if(isset($request->hotel_booked)){
            $hotel_booked = true;
        }
        // dd($lead_passenger);
         DB::beginTransaction();
        
            try {   
                    $userData   = CustomerSubcription::where('id',$request->customer_id)->select('id','status')->first();
                    $booking_customer_id = "";
                    $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$lead_passenger->lead_email)->first();
                    if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                            $booking_customer_id = $customer_exist->id;
                    }else{
                       
                       if($lead_passenger->lead_title == "Mr"){
                           $gender = 'male';
                       }else{
                            $gender = 'female';
                       }
                        
                        $password = Hash::make('admin123');
                        
                        $customer_detail                    = new booking_customers();
                        $customer_detail->name              = $lead_passenger->lead_first_name." ".$lead_passenger->lead_last_name;
                        $customer_detail->opening_balance   = 0;
                        $customer_detail->balance           = 0;
                        $customer_detail->email             = $lead_passenger->lead_email;
                        $customer_detail->password             = $password;
                        $customer_detail->phone             = $lead_passenger->lead_phone;
                        $customer_detail->gender            = $gender;
                        $customer_detail->country           = $lead_passenger->lead_country;
        
                        $customer_detail->customer_id       = $userData->id;
                        $result = $customer_detail->save();
                        
                        $booking_customer_id = $customer_detail->id;
        
                        
                    }
                    
                    $randomNumber = random_int(1000000, 9999999);
                    $invoiceId =  "HH".$randomNumber;
                    
                    $visa_avail_data = DB::table('visa_Availability')->where('id',$request_all_data->visa_avail_id)->first();
                    
                    DB::table('visa_bookings')->insert([
                            'invoice_no' => $invoiceId,
                            'no_of_paxs' => $request_all_data->no_of_paxs,
                            'hotel_booked' => $hotel_booked,
                            'lead_passenger_data' => $request->lead_passenger,
                            'other_passenger_data' => $request->other_adults,
                            'visa_avail_id' => $request_all_data->visa_avail_id,
                            'visa_avail_data' => json_encode($visa_avail_data),
                            'visa_price_exchange' => $request_all_data->exchange_price,
                            'visa_total_price_exchange' => $request_all_data->exchange_price_total,
                            'exchange_currency' => $request_all_data->exchange_curreny,
                            'visa_price' => $request_all_data->original_price,
                            'visa_total_price' => $request_all_data->original_price_total,
                            'currency' => $request_all_data->original_curreny,
                            'booking_customer_id' => $booking_customer_id,
                            'lead_passenger' => $lead_passenger->lead_first_name,
                            'customer_id' => $request->customer_id,
                            
                        ]);

                DB::commit();
                return response()->json(['status'=>'success',
                                         'Invoice_no'=>$invoiceId
                                            ]);
                
            } catch (Throwable $e) {
                 DB::rollback();
                echo $e;
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
    }
    
    public function visa_confirmed_checkout_submit(Request $request){
        $result = DB::table('visa_bookings')->where('invoice_no',$request->invoice_no)->update([
                        'booking_status' => 'Confirmed',
                        'payment_method' => $request->slc_pyment_method,
                    ]);
            
        if($result){
              return response()->json([
                'status' => 'success',
                'Invoice_no' => $request->invoice_no
            ]);
        }else{
             return response()->json([
                'status' => 'error',
                'Invoice_no' => $request->invoice_no
            ]);
        }
    }
    
    public function reject_visa_booking(Request $request){
        $result = DB::table('visa_bookings')->where('invoice_no',$request->invoice_no)->update([
                        'booking_status' => 'Rejected',
                    ]);
            
        $visa_data = DB::table('visa_bookings')->where('invoice_no',$request->invoice_no)->first();
        
        $lead_passenger_data = json_decode($visa_data->lead_passenger_data);
            
        if($result){
              return response()->json([
                'status' => 'success',
                'Invoice_no' => $request->invoice_no,
                'lead_email' => $lead_passenger_data->lead_email
            ]);
        }else{
             return response()->json([
                'status' => 'error',
                'Invoice_no' => $request->invoice_no,
                'lead_email' => $lead_passenger_data->lead_email
            ]);
        }
    }
    
    public function visa_booking_list(Request $request){
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        
        // $visa_booking_list = DB::table('visa_bookings')->where('customer_id',$userData->id)->orderBy('id','desc')->get();
        $visa_booking_list =   DB::table('visa_bookings')
                                    ->join('countries', function ($join) {
                                        $join->on('countries.id', '=', DB::raw('CAST(JSON_UNQUOTE(JSON_EXTRACT(visa_bookings.visa_avail_data, "$.country")) AS UNSIGNED)'));
                                    })
                                    ->join('visa_types', function ($join) {
                                        $join->on('visa_types.id', '=', DB::raw('CAST(JSON_UNQUOTE(JSON_EXTRACT(visa_bookings.visa_avail_data, "$.visa_type")) AS UNSIGNED)'));
                                    })
                                     ->select('visa_bookings.*', 'countries.name','visa_types.other_visa_type')
                                     ->orderBy('visa_bookings.id','desc')
                                    ->get();
        // dd($visa_booking_list);
        
        return response()->json([
                'status' => 'success',
                'data' => $visa_booking_list
            ]);
    }
    
    public function confrim_visa_booking(Request $request){
        $booking_data = DB::table('visa_bookings')->where('invoice_no',$request->invoice_no)->first();

        
      
        // dd($reservation_data);
        $currentDateTime = Carbon::now();

        // Add 2 hours to the current time
        $updatedDateTime = $currentDateTime->addHours($request->hours);
        
        // Optionally, you can format the updated time as per your requirement
        $formattedDateTime = $updatedDateTime->format('Y-m-d H:i:s');

        $result = DB::table('visa_bookings')->where('invoice_no',$request->invoice_no)->update([
            'expire_at' => $formattedDateTime,              
            'booking_status' => 'In Progess',
        ]);
        
         $booking_data = DB::table('visa_bookings')->where('invoice_no',$request->invoice_no)->first();
        
        //  if($result){
            return response()->json([
                'status' => 'success',
                'Invoice_id' => $request->invoice_no,
                'Invoice_data' => $booking_data
            ]);
        // }
        // dd($booking_data);
    }
  
    public function submit_visa_suppliers(Request $request){
        DB::beginTransaction();
        try {
            $customer_id            = $request->customer_id;
            $visa_supplier_name     = $request->visa_supplier_name;
            $email                  = $request->email;
            $phone_no               = $request->phone_no;
            $address                = $request->address;
            $contact_person_name    = $request->contact_person_name;
            $country                = $request->country;
            $city                   = $request->city ?? '';
            
            if($request->visa_Ref_No != null && $request->visa_Ref_No != ''){
                $check_VRN = DB::table('visa_Sup')->where('customer_id',$customer_id)->where('visa_Ref_No',$request->visa_Ref_No)->first();
                if($check_VRN == null){
                    $visa_Ref_No            = $request->visa_Ref_No;
                }else{
                    return response()->json(['Status'=>'Exist']);
                }
            }else{
                $three_digit_code       = rand(0,444);
                $visa_Ref_No            = 'VSP'.$three_digit_code;
            }
            
            $data = DB::table('visa_Sup')->insert([
                'SU_id'                 => $request->SU_id ?? NULL,
                'opening_balance'       => $request->opening_balance,
                'balance'               => $request->opening_balance,
                'opening_payable'       => $request->opening_payable,
                'payable'               => $request->opening_payable,
                'customer_id'           => $customer_id,
                'visa_Ref_No'           => $visa_Ref_No,
                'visa_supplier_name'    => $visa_supplier_name,
                'email'                 => $email,
                'phone_no'              => $phone_no,
                'address'               => $address,
                'contact_person_name'   => $contact_person_name,
                'country'               => $country,
                'city'                  => $city ?? '',
                'currency'              => $request->currency ?? NULL,
                'more_phone_no'         => $request->more_phone_no ??'',
            ]);
            
            DB::commit();
            return response()->json(['Status'=>'SuccessFull','data'=>$data]);
            
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function get_visa_suppliers(Request $request){
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            // $data           = DB::table('visa_Sup')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            $customer_Id    = $request->customer_id;
            $SU_id          = $request->SU_id;
            $data           = DB::table('visa_Sup')
                                ->where(function ($query) use ($customer_Id) {
                                    $query->where('customer_id', $customer_Id);
                                })
                                ->orWhere(function ($query) use ($SU_id) {
                                    $query->where('SU_id', $SU_id);
                                })
                                ->get();
        }else{
            $data = DB::table('visa_Sup')->where('customer_id',$request->customer_id)->get();
        }
        return response()->json(['Status'=>'SuccessFull','data'=>$data]);
    }
    
    public function get_supplier_visas(Request $request){
        // dd($request->all());
       
        $supplier_visas = DB::table('visa_Availability')
                            ->join('countries','countries.id','visa_Availability.country')
                            ->join('visa_types','visa_types.id','visa_Availability.visa_type')
                            ->where('visa_Availability.visa_supplier',$request->visa_supplier)
                            ->select('visa_Availability.*','countries.name','visa_types.other_visa_type')
                            ->get();
                            
            return response()->json(['Status'=>'SuccessFull','data'=>$supplier_visas]);
        
        // print_r($supplier_visas);
        
    }
    
    public function get_visa_suppliers_for_edit(Request $request){
        $countries  = DB::table('countries')->get();
        $data       = DB::table('visa_Sup')->where('customer_id',$request->customer_id)->where('id',$request->visa_sup_id)->first();
        return response()->json(['Status'=>'SuccessFull','data'=>$data,'countries'=>$countries]);
    }
    
    public function submit_visa_suppliers_for_update(Request $request){
        DB::beginTransaction();
        try {
            $visa_supllier_id = $request->visa_sup_id;
            if($request->visa_Ref_No != null && $request->visa_Ref_No != ''){
                $check_VRN = DB::table('visa_Sup')->where('customer_id',$request->customer_id)->where('visa_Ref_No',$request->visa_Ref_No)->where('id','!=',$request->visa_sup_id)->first();
                if($check_VRN == null){
                    $visa_Ref_No = $request->visa_Ref_No;
                }else{
                    return response()->json(['Status'=>'Exist']);
                }
            }else{
                $visa_Ref_No            = '';
            }
            $data = DB::table('visa_Sup')->where('id',$visa_supllier_id)->update([  
                'customer_id'           => $request->customer_id,
                'visa_supplier_name'    => $request->visa_supplier_name,
                'visa_Ref_No'           => $visa_Ref_No,
                'email'                 => $request->email,
                'phone_no'              => $request->phone_no,
                'address'               => $request->address,
                'contact_person_name'   => $request->contact_person_name,
                'country'               => $request->country,
                'city'                  => $request->city ?? '',
                'currency'              => $request->currency ?? NULL,
                'more_phone_no'         => $request->more_phone_no ??'',
            ]);
            DB::commit();
            return response()->json(['Status'=>'SuccessFull','data'=>$data]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function get_visa_type_for_sup(Request $request){
       
    //   dd($request);
       
       $customer_id =  $request->customer_id;
        
        $data= DB::table('visa_types')->where('customer_id',$customer_id)->get();
        $visa_supplier = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
       $mange_currencies=DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
       return response()->json(['Status'=>'SuccessFull','data'=>$data,'sup'=>$visa_supplier,'mange_currencies'=>$mange_currencies]);
    }
    
    public function view_visa_type(Request $request){
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $data = DB::table('visa_Availability')->where('visa_Availability.SU_id',$request->SU_id)
                    ->where('visa_Availability.customer_id',$request->customer_id)
                    ->join('visa_Sup','visa_Sup.id','visa_Availability.visa_supplier')
                    ->join('visa_types','visa_types.id','visa_Availability.visa_type')
                    ->join('mange_currencies','mange_currencies.id','visa_Availability.currency_conversion')
                    ->select('visa_Availability.id as visa_id','visa_Availability.*','visa_Sup.*','visa_types.*','mange_currencies.purchase_currency','mange_currencies.sale_currency')
                    ->get();
        }else{
            $data = DB::table('visa_Availability')
                    ->where('visa_Availability.customer_id',$request->customer_id)
                    ->join('visa_Sup','visa_Sup.id','visa_Availability.visa_supplier')
                    ->join('visa_types','visa_types.id','visa_Availability.visa_type')
                    ->join('mange_currencies','mange_currencies.id','visa_Availability.currency_conversion')
                    ->select('visa_Availability.id as visa_id','visa_Availability.*','visa_Sup.*','visa_types.*','mange_currencies.purchase_currency','mange_currencies.sale_currency')
                    ->get();
        }
        return response()->json(['Status'=>'SuccessFull','data'=>$data,]);
    }
    
    public function submit_visa_avalability_for_sup(Request $request){
        
        // dd($request->currency_conversion);
        
        $currency_conv_data = json_decode($request->currency_conversion);
        
        // dd($currency_conv_data);
      
        $currency_id = 0;
        
        if(isset($currency_conv_data)){
            $currency_id = $currency_conv_data->id;
        }
      
        DB::beginTransaction();
      
        try{
            $visa_id = DB::table('visa_Availability')->insertGetId([  
                'SU_id'                         => $request->SU_id ?? NULL,
                'customer_id'                   => $request->customer_id,
                'country'                       => $request->country,
                'visa_supplier'                 => $request->visa_supplier,
                'visa_type'                     => $request->visa_type,
                'currency_conversion'           => $currency_id,
                'conversion_type'               => $request->conversion_type,
                'visa_price_conversion_rate'    => $request->visa_price_conversion_rate,
                'visa_converted_price'          => $request->visa_converted_price,
                'visa_qty'                      => $request->visa_qty,
                'visa_available'                => $request->visa_qty,
                'visa_price'                    => $request->visa_price,
                'availability_from'             => $request->availability_from,
                'availability_to'               => $request->availability_to,
                'total_visa_payable'            => $request->total_visa_payable,
            ]);
            
            $supplier_data      = DB::table('visa_Sup')->where('id',$request->visa_supplier)->first();
            $supplier_balance   = $supplier_data->balance + $request->total_visa_payable;
            DB::table('visa_supplier_ledger')->insert([
                'SU_id'         => $request->SU_id ?? NULL,
                'customer_id'   => $request->customer_id,
                'supplier_id'   => $request->visa_supplier,
                'payment'       => $request->total_visa_payable,
                'balance'       => $supplier_balance,
                'payable'       => $supplier_data->payable,
                'visa_qty'      => $request->visa_qty,
                'visa_type'     => $request->visa_type,
                'visa_price'    => $request->visa_price,
                'visa_avl_id'   => $visa_id,
                'date'          => date('Y-m-d'),
                'remarks'       => 'New Visa Purchased',
            ]);
            $data = DB::table('visa_Sup')->where('id',$request->visa_supplier)->update([  
               'balance' => $supplier_balance,
            ]);
            
            DB::commit();
            return response()->json(['status'=>'success']);
        }catch(Throwable $e){
            DB::rollback();
            return response()->json(['status'=>'error']);
        }
    }
  
    public function get_visa_prices(Request $request){
        $visa_Availability = DB::table('visa_Availability')->where('customer_id',$request->customer_id)->where('visa_supplier',$request->visa_supplier)->sum('visa_converted_price');
        return response()->json(['Status'=>'SuccessFull','visa_Availability'=>$visa_Availability]);
    }
    
    public function visa_supplier_new_slot(Request $req){
        $visa_supplier_Details  = DB::table('visa_Sup')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
        $mange_currencies       = DB::table('mange_currencies')->where('customer_id',$req->customer_id)->get();
        $visa_supplier_Slots    = DB::table('visa_supplier_Slots')->where('customer_id',$req->customer_id)->where('visa_supplier_id',$req->id)->get();
        $visa_Availability      = DB::table('visa_Availability')->where('customer_id',$req->customer_id)->where('visa_supplier',$req->id)->sum('visa_converted_price');
        $all_currency           = DB::table('countries')->get();
        return response()->json(['message'=>'success','all_currency'=>$all_currency,'visa_supplier_Details'=>$visa_supplier_Details,'mange_currencies'=>$mange_currencies,'visa_supplier_Slots'=>$visa_supplier_Slots,'visa_Availability'=>$visa_Availability]);
    }
    
    public function add_visa_supplier_new_slot(Request $request){
        
        $visa_supplier_Slots        = DB::table('visa_supplier_Slots')->where('visa_supplier_Id',$request->visa_supplier_Id)->where('customer_id',$request->customer_id)->get();
        if(isset($visa_supplier_Slots) && $visa_supplier_Slots != null && $visa_supplier_Slots != '' && count($visa_supplier_Slots) != 0){
            $total                  = count($visa_supplier_Slots);
            $selectedDate           = $request->visa_supplier_Start_Date;
            $selectedDate           = Carbon::parse($selectedDate);
            $previousDate           = $selectedDate->subDays(1);
            $previousDateFormatted  = $previousDate->toDateString();
            DB::table('visa_supplier_Slots')->where('id',$visa_supplier_Slots[$total - 1]->id)->where('customer_id',$visa_supplier_Slots[$total - 1]->customer_id)->update(['visa_supplier_End_Date' => $previousDateFormatted]);
            
            $visa_supplier_Slots                                    = new visa_supplier_Slots();
            $visa_supplier_Slots->customer_id                       = $request->customer_id;
            $visa_supplier_Slots->visa_supplier_Id                  = $request->visa_supplier_Id;
            $visa_supplier_Slots->selected_VS_details               = $request->selected_VS_details;
            $visa_supplier_Slots->visa_supplier_Start_Date          = $request->visa_supplier_Start_Date;
            $visa_supplier_Slots->visa_supplier_End_Date            = $request->visa_supplier_End_Date;
            $visa_supplier_Slots->visa_supplier_Cost_Price          = $request->visa_supplier_Cost_Price;
            $visa_supplier_Slots->visa_supplier_Conversion          = $request->visa_supplier_Conversion;
            $visa_supplier_Slots->visa_supplier_Exchange_Rate       = $request->visa_supplier_Exchange_Rate;
            $visa_supplier_Slots->visa_supplier_Sale_Price          = $request->visa_supplier_Sale_Price;
            $visa_supplier_Slots->visa_supplier_Currency            = $request->visa_supplier_Currency;
            $visa_supplier_Slots->visa_supplier_price_all           = $request->visa_supplier_price_all;
            $visa_supplier_Slots->visa_supplier_Currency_Select     = $request->visa_supplier_Currency_Select;
            
            if($request->visa_supplier_Currency_Select != ''){
                DB::table('visa_Sup')->where('id',$request->visa_supplier_Id)->where('customer_id',$request->customer_id)->update(['currency' => $request->visa_supplier_Currency_Select]);
            }
            
            $visa_supplier_Slots->save();
            
            return response()->json(['status'=>'success','message1'=>'success','message'=>'Visa Supplier Slot Added Successful!']);
            
        }else{
            $visa_supplier_Slots                                    = new visa_supplier_Slots();
            $visa_supplier_Slots->customer_id                       = $request->customer_id;
            $visa_supplier_Slots->visa_supplier_Id                  = $request->visa_supplier_Id;
            $visa_supplier_Slots->selected_VS_details               = $request->selected_VS_details;
            $visa_supplier_Slots->visa_supplier_Start_Date          = $request->visa_supplier_Start_Date;
            $visa_supplier_Slots->visa_supplier_End_Date            = $request->visa_supplier_End_Date;
            $visa_supplier_Slots->visa_supplier_Cost_Price          = $request->visa_supplier_Cost_Price;
            $visa_supplier_Slots->visa_supplier_Conversion          = $request->visa_supplier_Conversion;
            $visa_supplier_Slots->visa_supplier_Exchange_Rate       = $request->visa_supplier_Exchange_Rate;
            $visa_supplier_Slots->visa_supplier_Sale_Price          = $request->visa_supplier_Sale_Price;
            $visa_supplier_Slots->visa_supplier_Currency            = $request->visa_supplier_Currency;
            $visa_supplier_Slots->visa_supplier_price_all           = $request->visa_supplier_price_all;
            $visa_supplier_Slots->visa_supplier_Currency_Select     = $request->visa_supplier_Currency_Select;
            
            if($request->visa_supplier_Currency_Select != ''){
                DB::table('visa_Sup')->where('id',$request->visa_supplier_Id)->where('customer_id',$request->customer_id)->update(['currency' => $request->visa_supplier_Currency_Select]);
            }
            
            $visa_supplier_Slots->save();
            
            return response()->json(['status'=>'success','message1'=>'success','message'=>'Visa Supplier Slot Added Successful!']);
        }
    }
    
    public function edit_visa_supplier_slot(Request $req){
        $visa_supplier_Slots = DB::table('visa_supplier_Slots')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
        return response()->json(['message'=>'success','visa_supplier_Slots'=>$visa_supplier_Slots]);
    }
    
    public function update_visa_supplier_slot(Request $request){
        DB::beginTransaction();
        try {
            DB::table('visa_supplier_Slots')->where('id',$request->id)->where('customer_id',$request->customer_id)->update(['visa_supplier_Sale_Price'=>$request->visa_supplier_Sale_Price]);
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Visa Supplier Slot Updated Successful!']);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
    }
}
