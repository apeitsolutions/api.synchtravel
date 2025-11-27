<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use Session;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\booking_customers;
use App\Http\Controllers\Transfer_3rdPartyBooking_Controller;

class TransfersController extends Controller
{
    public function transfers_search(Request $request){
        
        $token=$request->token;
        
       $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$token)->select('id')->first();
        //  print_r($customer_subcriptions);
       $pick_up_location_country=$request->pick_up_location_country; 
       $pick_up_location_city=$request->pick_up_location_city; 
       $drop_of_location_country=$request->drop_of_location_country; 
       $drop_of_location_city=$request->drop_of_location_city; 
       $pick_up_date=$request->pick_up_date; 
       $trip_type=$request->trip_type; 
       $passenger=$request->passenger;
       
       $transfer=DB::table('tranfer_destination')
       
       ->where('pickup_City','LIKE','%'.$pick_up_location_city.'%')
       ->where('dropof_City','LIKE','%'.$drop_of_location_city.'%')
      
       ->where('transfer_type','=',$trip_type)
       ->where('customer_id',$customer_subcriptions->id)
       ->where('available_from','<=',$pick_up_date)
       ->Where('available_to','>=',$pick_up_date)
       ->get();
        //print_r($transfer);die();
    //   $vehicle_data=array();
      $all_data=array();
      $vihecle_array=array();

    //   $vehicle_json_data=array();
                            foreach($transfer as $transfer_data)
                            {
                                $vehicle_details=json_decode($transfer_data->vehicle_details);
                                //print_r($vehicle_details);die();
                               $currency = DB::table('mange_currencies')->where('id',$transfer_data->conversion_type_Id)->select('sale_currency')->first();
                                 foreach($vehicle_details as $vehicle)
                                 {
                                      if(isset($vehicle->vehicle_id))
                                    {
                                
                                     
                                     if($vehicle->display_on_website == 'true')
                                     {
                                         $vehicle_json_data=$vehicle;
                                       $vehicle_data = DB::table('tranfer_vehicle')->where('id',$vehicle->vehicle_id)->Where('vehicle_Passenger','>=',$passenger)->first();
                                       
                                       
                                    //   $vihecle_array[]=$vehicle_json_data;
                                    
                                    $all_data[]=(object)[
                                        'transfer'=>$transfer_data,
                                        'currency'=>$currency,
                                        'vehicles'=>$vehicle_json_data,
                                        'vehicle'=>$vehicle_data,
                                        ];
                                         
                                      
                                     
                                     
                                     }
                                 }
                                 
                                 
                                 
                              }
                            //   $all_data[]=(object)[
                            //         'transfer'=>$transfer_data,
                            //         'currency'=>$currency,
                            //         'vihecles'=>$vihecle_array,
                                  
                            //          ];
                                
                            }
                        
                            
                            
        if($transfer){
            return response()->json(['message'=>'Success','transfers'=>$all_data]);    
        }else{
            return response()->json(['message'=>'Failed']);  
        }
        
       
    }
    
    public function transfers_search_new(Request $request){
        // dd($request->token);
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        
        // dd($userData);
        
        if($userData){
            
            $request_data   = json_decode($request->request_all_data);
            // dd($request_data);
            $transfer_data  = DB::table('tranfer_destination')
                                ->where('pickup_api_City','LIKE','%'.$request_data->name_pickup_location_plc.'%')
                                ->where('dropof_api_City','LIKE','%'.$request_data->name_drop_off_location_plc.'%')
                                ->where('transfer_type','=',$request_data->trip_type)
                                ->where('customer_id',$userData->id)
                                ->where('available_from','<=',$request_data->pick_up_date)
                                ->where('available_to','>=',$request_data->pick_up_date)
                                ->get();
            
            // dd($transfer_data);
            
            $all_transfer_list = [];
            if(isset($transfer_data)){
                foreach($transfer_data as $transfer_res){
                    $supplier_details = json_decode($transfer_res->vehicle_details);
                    if(isset($supplier_details)){
                        foreach($supplier_details as $supplier_res){
                            if($supplier_res->display_on_website == 'true'){
                                $vehicle_data = DB::table('tranfer_vehicle')->where('id',$supplier_res->vehicle_id)->first();
                                if(isset($vehicle_data->vehicle_Passenger)){
                                    if($vehicle_data->vehicle_Passenger >= $request_data->passenger){
                                        
                                        $conversoin_data = DB::table('mange_currencies')->where('id',$transfer_res->conversion_type_Id)->first();
                                        
                                        if(isset($request->site_URL)){
                                            $img_URL = $request->site_URL.'/'.$vehicle_data->vehicle_image;
                                        }else{
                                            $img_URL = $vehicle_data->vehicle_image;
                                        }
                                        
                                        $pick_up_datetime   = new DateTime($request_data->pick_up_date . ' ' . $request_data->arrtime);
                                        $ret_datetime       = new DateTime($request_data->retdate . ' ' . $request_data->rettime);
                                        $duration           = $pick_up_datetime->diff($ret_datetime);
                                        $total_hours        = $duration->days * 24 + $duration->h;
                                        $total_minutes      = $total_hours * 60 + $duration->i;
                                        $total_duration     = $total_hours . " hours " . $duration->i . " minutes";
                                        
                                        $transfer_list_item = (Object)[
                                            'booking_From'              => '',
                                            'destination_id'            => $transfer_res->id,
                                            'customer_id'               => $transfer_res->customer_id,
                                            'search_passenger'          => $request_data->passenger,
                                            'no_of_vehicles'            => $request_data->no_of_vehicles,
                                            'country'                   => $request_data->pick_up_location_country,
                                            'pickup_date'               => $request_data->pick_up_date,
                                            'pickup_City'               => $transfer_res->pickup_City,
                                            'dropof_City'               => $transfer_res->dropof_City,
                                            'return_pickup_City'        => $transfer_res->return_pickup_City,
                                            'return_dropof_City'        => $transfer_res->return_dropof_City,
                                            'pickup_api_City'           => $transfer_res->pickup_api_City,
                                            'dropof_api_City'           => $transfer_res->dropof_api_City,
                                            'more_destination_details'  => $transfer_res->more_destination_details,
                                            'ziyarat_City_details'      => $transfer_res->ziyarat_City_details,
                                            'transfer_type'             => $transfer_res->transfer_type,
                                            'currency_conversion'       => $transfer_res->currency_conversion,
                                            'conversion_type_Id'        => $transfer_res->conversion_type_Id,
                                            'transfer_supplier_Id'      => $supplier_res->transfer_supplier_Id,
                                            'transfer_supplier'         => $supplier_res->transfer_supplier,
                                            'vehicle_Name'              => $supplier_res->vehicle_Name,
                                            'vehicle_image'             => $img_URL,
                                            'vehicle_Fare'              => $supplier_res->vehicle_Fare,
                                            'fare_markup_type'          => $supplier_res->fare_markup_type,
                                            'fare_markup'               => $supplier_res->fare_markup,
                                            'exchange_Rate'             => $supplier_res->exchange_Rate,
                                            'total_fare_markup'         => $supplier_res->total_fare_markup,
                                            'currency_symbol'           => $supplier_res->currency_symbol,
                                            'sale_currency'             => $conversoin_data->sale_currency ?? '',
                                            'OccupancyFrom'             => 1,
                                            'OccupancyTo'               => $vehicle_data->vehicle_Passenger ?? '0',
                                            'SmallBagAllowance'         => 1,
                                            'BigBagAllowance'           => $vehicle_data->vehicle_Baggage ?? '0',
                                            'TransferType'              => $transfer_res->transfer_type ?? '',
                                            'VehicleClass'              => $vehicle_data->vehicle_Type,
                                            'VehicleMake'               => $vehicle_data->vehicle_Type,
                                            'duration'                  => $total_duration ?? '',
                                        ];
                                        
                                        $all_transfer_list[] = $transfer_list_item;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // dd($all_transfer_list);
            
            // 3rd Party Api's
            $trasnfer_Details_Api = Transfer_3rdPartyBooking_Controller::search_Transfer_Api($request_data);
            // return $trasnfer_Details_Api;
            $trans_data = $trasnfer_Details_Api['trans'];
            // dd($trans_data[0]->VehicleClass);
            // if(isset($trans_data[0]->Disclaimer) && $trans_data[0]->Disclaimer != 0){
            //     $disclaimer_details = Transfer_3rdPartyBooking_Controller::search_Disclaimer_Transfer_Api($trans_data[6]->BookingID,$trasnfer_Details_Api['sessionID']);
                // dd($disclaimer_details);
            // }
            // dd('Stop');
            
            if(isset($trasnfer_Details_Api['trans']) && $trasnfer_Details_Api['trans'] != ''){
                $details                = $trasnfer_Details_Api['details'];
                $arrival_time_str       = $details->ArrTime;
                $arrival_time           = DateTime::createFromFormat('Hi', $arrival_time_str);
                $current_time           = new DateTime();
                $time_difference        = $arrival_time->diff($current_time);
                $days                   = $time_difference->format('%a');
                $hours                  = $time_difference->format('%h');
                $minutes                = $time_difference->format('%i');
                $total_duration_message = '';
                
                if ($days > 0) {
                    $total_duration_message .= "$days days ";
                }
                
                if ($hours > 0) {
                    $total_duration_message .= "$hours hours ";
                }
                
                if ($minutes > 0) {
                    $total_duration_message .= "$minutes minutes";
                }
                
                foreach($trasnfer_Details_Api['trans'] as $transfer_res){
                    $VehicleClass   = $transfer_res->VehicleClass;
                    $VehicleMake    = $transfer_res->VehicleMake;
                    
                    if(empty((array)$VehicleClass)) {
                        $VehicleClass = '';
                    }
                    
                    if(empty((array)$VehicleMake)) {
                        $VehicleMake = '';
                    }
                    
                    $transfer_list_item = (Object)[
                        'booking_From'              => '3rd Party API',
                        'destination_id'            => $transfer_res->BookingID,
                        'customer_id'               => $userData->id,
                        'search_passenger'          => $request_data->passenger,
                        'no_of_vehicles'            => $request_data->no_of_vehicles,
                        'country'                   => $request_data->pick_up_location_country,
                        'pickup_date'               => $request_data->pick_up_date,
                        'pickup_City'               => $details->PlaceFrom,
                        'dropof_City'               => $details->PlaceTo,
                        'return_pickup_City'        => $transfer_res->return_pickup_City ?? '',
                        'return_dropof_City'        => $transfer_res->return_dropof_City ?? '',
                        'pickup_api_City'           => $request_data->name_pickup_location_plc ?? '',
                        'dropof_api_City'           => $request_data->name_drop_off_location_plc ?? '',
                        'more_destination_details'  => $transfer_res->more_destination_details ?? '',
                        'ziyarat_City_details'      => $transfer_res->ziyarat_City_details ?? '',
                        'transfer_type'             => $request_data->trip_type,
                        'currency_conversion'       => $details->Currency,
                        'conversion_type_Id'        => $transfer_res->conversion_type_Id ?? '1',
                        'transfer_supplier_Id'      => $transfer_res->SupplierID,
                        'transfer_supplier'         => $supplier_res->transfer_supplier ?? '',
                        'vehicle_Name'              => $transfer_res->Vehicle,
                        'vehicle_image'             => $transfer_res->VehicleImage,
                        'vehicle_Fare'              => $transfer_res->Price,
                        'fare_markup_type'          => $supplier_res->fare_markup_type ?? '',
                        'fare_markup'               => $transfer_res->fare_markup ?? '',
                        'exchange_Rate'             => $supplier_res->exchange_Rate ?? '',
                        'total_fare_markup'         => $transfer_res->Price,
                        'currency_symbol'           => $supplier_res->currency_symbol ?? $details->Currency ?? '',
                        'sale_currency'             => $conversoin_data->sale_currency ?? $details->Currency ?? '',
                        'OccupancyFrom'             => $transfer_res->OccupancyFrom ?? '0' ,
                        'OccupancyTo'               => $transfer_res->OccupancyTo ?? '0',
                        'SmallBagAllowance'         => $transfer_res->SmallBagAllowance ?? '0',
                        'BigBagAllowance'           => $transfer_res->BigBagAllowance ?? '0',
                        'TransferType'              => $transfer_res->TransferType ?? '',
                        'VehicleClass'              => $VehicleClass,
                        'VehicleMake'               => $VehicleMake,
                        'duration'                  => $total_duration_message ?? '',
                    ];
                    $all_transfer_list[] = $transfer_list_item;
                }
            }
            
            if(isset($trasnfer_Details_Api['sessionID']) && $trasnfer_Details_Api['sessionID'] != ''){
                $sessionID = $trasnfer_Details_Api['sessionID'];
            }else{
                $sessionID = '';
            }
            // 3rd Party Api's
            
            // dd($all_transfer_list);
            
            return response()->json(['message'=>'Success','transfers_list'=>$all_transfer_list,'sessionID'=>$sessionID]);    
        }
        return response()->json(['message'=>'error','transfers'=>'']);
    }
    
    public function transfers_search_latest(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        //print_r($userData);die();   
        if($userData){
            $request_data = json_decode($request->request_all_data);
           //print_r($request_data);die();
           
           if($request_data->trip_type == 'One-Way')
           {
            $transfer_data = DB::table('tranfer_destination')
                               ->where('pickup_api_City','LIKE','%'.$request_data->name_pickup_location_plc.'%')
                               ->where('dropof_api_City','LIKE','%'.$request_data->name_drop_off_location_plc.'%')
                               ->where('transfer_type','=',$request_data->trip_type)
                               ->where('customer_id',$userData->id)
                               ->where('available_from','<=',$request->pick_up_date)
                               ->Where('available_to','>=',$request->pick_up_date)
                               ->get();
                 //print_r($transfer_data);die();       
            $all_transfer_list = [];
            if(isset($transfer_data)){
                foreach($transfer_data as $transfer_res){
                    $supplier_details = json_decode($transfer_res->vehicle_details);
                    //print_r($supplier_details);die();   
                    if(isset($supplier_details)){
                        foreach($supplier_details as $supplier_res){
                            if($supplier_res->display_on_website == 'true'){
                                $vehicle_data = DB::table('tranfer_vehicle')->where('id',$supplier_res->vehicle_id)->first();
                                if(isset($vehicle_data->vehicle_Passenger)){
                                    if($vehicle_data->vehicle_Passenger >= $request_data->passenger){
                                        
                                        $conversoin_data = DB::table('mange_currencies')->where('id',$transfer_res->conversion_type_Id)->first();
                                        
                                        
                                        $transfer_list_item = (Object)[
                                                'destination_id' => $transfer_res->id,
                                                'customer_id' => $transfer_res->customer_id,
                                                'search_passenger' => $request_data->passenger,
                                                'no_of_vehicles' => $request_data->no_of_vehicles,
                                                'country' => $request_data->pick_up_location_country,
                                                'pickup_date' => $request->pick_up_date,
                                                'pickup_City' => $transfer_res->pickup_City,
                                                'dropof_City' => $transfer_res->dropof_City,
                                                'return_pickup_City' => $transfer_res->return_pickup_City,
                                                'return_dropof_City' => $transfer_res->return_dropof_City,
                                                'pickup_api_City' => $transfer_res->pickup_api_City,
                                                'dropof_api_City' => $transfer_res->dropof_api_City,
                                                'more_destination_details' => $transfer_res->more_destination_details,
                                                'ziyarat_City_details' => $transfer_res->ziyarat_City_details,
                                                'transfer_type' => $transfer_res->transfer_type,
                                                'currency_conversion' => $transfer_res->currency_conversion,
                                                'conversion_type_Id' => $transfer_res->conversion_type_Id,
                                                'transfer_supplier_Id' => $supplier_res->transfer_supplier_Id,
                                                'transfer_supplier' => $supplier_res->transfer_supplier,
                                                'vehicle_Name' => $supplier_res->vehicle_Name,
                                                'vehicle_image' => $vehicle_data->vehicle_image,
                                                'vehicle_Fare' => $supplier_res->vehicle_Fare,
                                                'fare_markup_type' => $supplier_res->fare_markup_type,
                                                'fare_markup' => $supplier_res->fare_markup,
                                                'exchange_Rate' => $supplier_res->exchange_Rate,
                                                'total_fare_markup' => $supplier_res->total_fare_markup,
                                                'currency_symbol' => $supplier_res->currency_symbol,
                                                'sale_currency' => $conversoin_data->sale_currency ?? '',
                                            ];
                                            
                                        $all_transfer_list[] = $transfer_list_item;
                                    }
                                }
                                // print_r($vehicle_data);
                            }
                            
                            // 
                        }
                    }
                    // print_r($supplier_details);
                }
            }
            // dd($all_transfer_list);
            
            return response()->json(['message'=>'Success','transfers_list'=>$all_transfer_list]);   
           }
           else if($request_data->trip_type == 'Return')
           {
            $transfer_data = DB::table('tranfer_destination')
                               ->where('pickup_api_City','LIKE','%'.$request_data->name_pickup_location_plc.'%')
                               ->where('dropof_api_City','LIKE','%'.$request_data->name_drop_off_location_plc.'%')
                               ->where('transfer_type','=',$request_data->trip_type)
                               ->where('customer_id',$userData->id)
                               ->where('available_from','<=',$request->pick_up_date)
                               ->Where('available_to','>=',$request->return_date)
                               ->get();
                 //print_r($transfer_data);die();       
            $all_transfer_list = [];
            if(isset($transfer_data)){
                foreach($transfer_data as $transfer_res){
                    $supplier_details = json_decode($transfer_res->vehicle_details);
                    //print_r($supplier_details);die();   
                    if(isset($supplier_details)){
                        foreach($supplier_details as $supplier_res){
                            if($supplier_res->display_on_website == 'true'){
                                $vehicle_data = DB::table('tranfer_vehicle')->where('id',$supplier_res->vehicle_id)->first();
                                if(isset($vehicle_data->vehicle_Passenger)){
                                    if($vehicle_data->vehicle_Passenger >= $request_data->passenger){
                                        
                                        $conversoin_data = DB::table('mange_currencies')->where('id',$transfer_res->conversion_type_Id)->first();
                                        
                                        
                                        $transfer_list_item = (Object)[
                                                'destination_id' => $transfer_res->id,
                                                'customer_id' => $transfer_res->customer_id,
                                                'search_passenger' => $request_data->passenger,
                                                'no_of_vehicles' => $request_data->no_of_vehicles,
                                                'country' => $request_data->pick_up_location_country,
                                                'pickup_date' => $request->pick_up_date,
                                                'pickup_City' => $transfer_res->pickup_City,
                                                'dropof_City' => $transfer_res->dropof_City,
                                                'return_pickup_City' => $transfer_res->return_pickup_City,
                                                'return_dropof_City' => $transfer_res->return_dropof_City,
                                                'pickup_api_City' => $transfer_res->pickup_api_City,
                                                'dropof_api_City' => $transfer_res->dropof_api_City,
                                                'more_destination_details' => $transfer_res->more_destination_details,
                                                'ziyarat_City_details' => $transfer_res->ziyarat_City_details,
                                                'transfer_type' => $transfer_res->transfer_type,
                                                'currency_conversion' => $transfer_res->currency_conversion,
                                                'conversion_type_Id' => $transfer_res->conversion_type_Id,
                                                'transfer_supplier_Id' => $supplier_res->transfer_supplier_Id,
                                                'transfer_supplier' => $supplier_res->transfer_supplier,
                                                'vehicle_Name' => $supplier_res->vehicle_Name,
                                                'vehicle_image' => $vehicle_data->vehicle_image,
                                                'vehicle_Fare' => $supplier_res->vehicle_Fare,
                                                'fare_markup_type' => $supplier_res->fare_markup_type,
                                                'fare_markup' => $supplier_res->fare_markup,
                                                'exchange_Rate' => $supplier_res->exchange_Rate,
                                                'total_fare_markup' => $supplier_res->total_fare_markup,
                                                'currency_symbol' => $supplier_res->currency_symbol,
                                                'sale_currency' => $conversoin_data->sale_currency ?? '',
                                            ];
                                            
                                        $all_transfer_list[] = $transfer_list_item;
                                    }
                                }
                                // print_r($vehicle_data);
                            }
                            
                            // 
                        }
                    }
                    // print_r($supplier_details);
                }
            }
            // dd($all_transfer_list);
            
            return response()->json(['message'=>'Success','transfers_list'=>$all_transfer_list]);   
           }
           else
           {
               $array_pickup_date=$request->array_pickup_date;
               $array_pickup_date=json_decode($array_pickup_date);
                $return_date=$request->return_date;
       $all_round_date=explode( '/', end($array_pickup_date));
       $all_round_date_new=$all_round_date[2].'-'.$all_round_date[0].'-'.$all_round_date[1];
               //print_r($all_round_date_new);die();
            $transfer_data = DB::table('tranfer_destination')
                               ->where('pickup_api_City','LIKE','%'.$request_data->name_pickup_location_plc.'%')
                               ->where('dropof_api_City','LIKE','%'.$request_data->name_drop_off_location_plc.'%')
                               ->where('transfer_type','=',$request_data->trip_type)
                               ->where('customer_id',$userData->id)
                               ->where('available_from','<=',$request->pick_up_date)
                               ->Where('available_to','>=',$all_round_date_new)
                               ->get();
                             $all_round_pickup_location=json_decode($request->all_round_pickup_location);
                             $all_round_dropoff_location=json_decode($request->all_round_dropoff_location);
                               //echo '</br>';
        //   print_r($all_round_pickup_location);
        //   echo '</br>';
        //   print_r($all_round_dropoff_location);
            $all_transfer_list = [];
            if(isset($transfer_data)){
                foreach($transfer_data as $transfer_res){
                    $pickup_sub_loc=json_decode($transfer_res->pickup_sub_loc);
                    $drop_sub_loc=json_decode($transfer_res->drop_sub_loc);
                    // print_r($pickup_sub_loc);
                    // echo '</br>';
                    // print_r($drop_sub_loc);
                    if($pickup_sub_loc == $all_round_pickup_location && $drop_sub_loc == $all_round_dropoff_location)
                    {
                    
                    $supplier_details = json_decode($transfer_res->vehicle_details);
                    //print_r($transfer_res->pickup_sub_loc);die();   
                    if(isset($supplier_details)){
                        foreach($supplier_details as $supplier_res){
                            if($supplier_res->display_on_website == 'true'){
                                $vehicle_data = DB::table('tranfer_vehicle')->where('id',$supplier_res->vehicle_id)->first();
                                if(isset($vehicle_data->vehicle_Passenger)){
                                    if($vehicle_data->vehicle_Passenger >= $request_data->passenger){
                                        
                                        $conversoin_data = DB::table('mange_currencies')->where('id',$transfer_res->conversion_type_Id)->first();
                                        
                                        
                                        $transfer_list_item = (Object)[
                                                'destination_id' => $transfer_res->id,
                                                'customer_id' => $transfer_res->customer_id,
                                                'search_passenger' => $request_data->passenger,
                                                'no_of_vehicles' => $request_data->no_of_vehicles,
                                                'country' => $request_data->pick_up_location_country,
                                                'pickup_date' => $request->pick_up_date,
                                                'pickup_City' => $transfer_res->pickup_City,
                                                'dropof_City' => $transfer_res->dropof_City,
                                                'return_pickup_City' => $transfer_res->return_pickup_City,
                                                'return_dropof_City' => $transfer_res->return_dropof_City,
                                                'pickup_api_City' => $transfer_res->pickup_api_City,
                                                'dropof_api_City' => $transfer_res->dropof_api_City,
                                                'more_destination_details' => $transfer_res->more_destination_details,
                                                'ziyarat_City_details' => $transfer_res->ziyarat_City_details,
                                                'transfer_type' => $transfer_res->transfer_type,
                                                'currency_conversion' => $transfer_res->currency_conversion,
                                                'conversion_type_Id' => $transfer_res->conversion_type_Id,
                                                'transfer_supplier_Id' => $supplier_res->transfer_supplier_Id,
                                                'transfer_supplier' => $supplier_res->transfer_supplier,
                                                'vehicle_Name' => $supplier_res->vehicle_Name,
                                                'vehicle_image' => $vehicle_data->vehicle_image,
                                                'vehicle_Fare' => $supplier_res->vehicle_Fare,
                                                'fare_markup_type' => $supplier_res->fare_markup_type,
                                                'fare_markup' => $supplier_res->fare_markup,
                                                'exchange_Rate' => $supplier_res->exchange_Rate,
                                                'total_fare_markup' => $supplier_res->total_fare_markup,
                                                'currency_symbol' => $supplier_res->currency_symbol,
                                                'sale_currency' => $conversoin_data->sale_currency ?? '',
                                            ];
                                            
                                        $all_transfer_list[] = $transfer_list_item;
                                    }
                                }
                                // print_r($vehicle_data);
                            }
                            
                            // 
                        }
                    }
                    // print_r($supplier_details);
                }
                }
            }
            // dd($all_transfer_list);
            
            return response()->json(['message'=>'Success','transfers_list'=>$all_transfer_list]);   
           }
                 
        }
        // dd($request->all());
            return response()->json(['message'=>'error','transfers'=>'']);    
    }
    
    public function transfers_search_combine(Request $request){
        // dd($request->all());
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $request_data = json_decode($request->request_all_data);
            // dd($request_data);
             $transfer_data = DB::table('tranfer_destination')
                               ->where('dropof_api_City','LIKE','%'.$request_data->country.'%')
                               ->where('customer_id',$userData->id)
                               ->where('available_from','<=',$request_data->checkin)
                               ->Where('available_to','>=',$request_data->checkin)
                               ->get();
                        // dd($transfer_data);       
            $all_transfer_list = [];
            if(isset($transfer_data)){
                foreach($transfer_data as $transfer_res){
                    $supplier_details = json_decode($transfer_res->vehicle_details);
                    if(isset($supplier_details)){
                        foreach($supplier_details as $supplier_res){
                            if($supplier_res->display_on_website == 'true'){
                                $vehicle_data = DB::table('tranfer_vehicle')->where('id',$supplier_res->vehicle_id)->first();
                                if(isset($vehicle_data->vehicle_Passenger)){
                                    if($vehicle_data->vehicle_Passenger >= $request_data->passenger){
                                        
                                        $conversoin_data = DB::table('mange_currencies')->where('id',$transfer_res->conversion_type_Id)->first();
                                        
                                        
                                        $transfer_list_item = (Object)[
                                                'destination_id' => $transfer_res->id,
                                                'customer_id' => $transfer_res->customer_id,
                                                'search_passenger' => $request_data->passenger,
                                                'no_of_vehicles' => 1,
                                                'country' => $request_data->pick_up_location_country,
                                                'pickup_date' => $request_data->checkin,
                                                'pickup_City' => $transfer_res->pickup_City,
                                                'dropof_City' => $transfer_res->dropof_City,
                                                'return_pickup_City' => $transfer_res->return_pickup_City,
                                                'return_dropof_City' => $transfer_res->return_dropof_City,
                                                'pickup_api_City' => $transfer_res->pickup_api_City,
                                                'dropof_api_City' => $transfer_res->dropof_api_City,
                                                'more_destination_details' => $transfer_res->more_destination_details,
                                                'ziyarat_City_details' => $transfer_res->ziyarat_City_details,
                                                'transfer_type' => $transfer_res->transfer_type,
                                                'currency_conversion' => $transfer_res->currency_conversion,
                                                'conversion_type_Id' => $transfer_res->conversion_type_Id,
                                                'transfer_supplier_Id' => $supplier_res->transfer_supplier_Id,
                                                'transfer_supplier' => $supplier_res->transfer_supplier,
                                                'vehicle_Name' => $supplier_res->vehicle_Name,
                                                'vehicle_image' => $vehicle_data->vehicle_image,
                                                'vehicle_Fare' => $supplier_res->vehicle_Fare,
                                                'fare_markup_type' => $supplier_res->fare_markup_type,
                                                'fare_markup' => $supplier_res->fare_markup,
                                                'exchange_Rate' => $supplier_res->exchange_Rate,
                                                'total_fare_markup' => $supplier_res->total_fare_markup,
                                                'currency_symbol' => $supplier_res->currency_symbol,
                                                'sale_currency' => $conversoin_data->sale_currency ?? '',
                                            ];
                                            
                                        $all_transfer_list[] = $transfer_list_item;
                                    }
                                }
                                // print_r($vehicle_data);
                            }
                            
                            // 
                        }
                    }
                    // print_r($supplier_details);
                }
            }
            // dd($all_transfer_list);
            
            return response()->json(['message'=>'Success','transfers_list'=>$all_transfer_list]);    
        }
        // dd($request->all());
            return response()->json(['message'=>'error','transfers'=>'']);    
    }
    
    public function confirm_booking_transfer(Request $request){
    
        $auth_key=$request->auth_key;
       $invoice_no=$request->invoice_no; 
       $transfer_availability=$request->transfer_availability; 
       $transfer_checkavailability=$request->transfer_checkavailability; 
       $transfer_checkavailability_again=$request->transfer_checkavailability_again; 
       $lead_passenger_details=$request->lead_passenger_details; 
       $other_passenger_details=$request->other_passenger_details; 
       $booking_status=$request->booking_status;
       $transfer_booking=$request->transfer_booking;
       $price_transfer_total=$request->price_transfer_total;
       
       $lead_passenger = json_decode($lead_passenger_details);
       
       DB::beginTransaction();
        
            try {
                   $userData = CustomerSubcription::where('Auth_key',$request->auth_key)->select('id','status')->first();
                   
                   $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$lead_passenger->lead_email)->first();
                    if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                            $customer_id = $customer_exist->id;
                    }else{
                       
                        
                        $password = Hash::make('admin123');
                        
                        $customer_detail                    = new booking_customers();
                        $customer_detail->name              = $lead_passenger->lead_first_name." ".$lead_passenger->lead_last_name;
                        $customer_detail->opening_balance   = 0;
                        $customer_detail->balance           = 0;
                        $customer_detail->email             = $lead_passenger->lead_email;
                        $customer_detail->password             = $password;
                        $customer_detail->phone             = $lead_passenger->lead_phone;
            
                        $customer_detail->customer_id       = $userData->id;
                        $result = $customer_detail->save();
                        
                        $customer_id = $customer_detail->id;
            
                        
                    }
                   
                   
                   $data = DB::table('transfer_bookings')->insert([
                       'auth_key'=>$auth_key,
                       'invoice_no'=>$invoice_no,
                       'booking_customer_id'=>$customer_id,
                       'transfer_availability'=>$transfer_availability,
                       'transfer_checkavailability'=>$transfer_checkavailability,
                       'transfer_checkavailability_again'=>$transfer_checkavailability_again,
                       'transfer_booking'=>$transfer_booking,
                       'booking_status'=>$booking_status,
                       'lead_passenger_details'=>$lead_passenger_details,
                       'other_passenger_details'=>$other_passenger_details,
                       'exchange_price'=>$price_transfer_total,
                       ]);
           
                    DB::commit();
                
                return response()->json(['status'=>'success']);
                
                   
                    // dd($invoiceId);
                    // return response()->json(['message'=>'success','booking_id'=>$tourObj->id,'invoice_id'=>$invoiceId]);
                } catch (\Exception $e) {
                    DB::rollback();
                    echo $e;die;
                    return response()->json(['status'=>'error']);
                    // something went wrong
                }
    }
    
    public function transfer_voucher_details(Request $request){
        $invoice_no=$request->invoice_no; 
        $transfer_data = DB::table('transfer_bookings')->where('invoice_no',$invoice_no)->first();
        return response()->json(['transfers'=>$transfer_data]);   
    }
    
    public function all_transfers_bookings(Request $request){
        $token=$request->token; 
        $transfer_bookings = DB::table('transfer_bookings')->where('auth_key',$token)
        ->select('id','invoice_no','transfer_checkavailability','transfer_checkavailability_again','booking_status','lead_passenger_details','other_passenger_details')->get();
        return response()->json(['transfer_bookings'=>$transfer_bookings]);   
    }
    
    public function transfer_checkout_submit_only(Request $request){
        $transfer_data          = json_decode($request->transfer_data);
        $lead_passenger         = $transfer_data->lead_passenger_details;
        $transfer_destination   = json_decode($request->transfer_destination_data);
        $transfer_price_data    = $transfer_data->transfer_price_details;
        $hotel_booked           = false;
        
        if(isset($request->booking_From) && $request->booking_From != ''){
            $booking_From           = '3rd Party Apis';
        }else{
            $booking_From           = NULL;
        }
        
        if(isset($request->hotel_booked)){
            $hotel_booked = true;
        }
        
        DB::beginTransaction();
        try {   
            $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            $booking_customer_id    = '';
            
            $customer_exist         = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$lead_passenger->lead_email)->first();
            if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                $booking_customer_id = $customer_exist->id;
            }else{
                if($lead_passenger->lead_title == "Mr"){
                    $gender = 'male';
                }else{
                    $gender = 'female';
                }
                
                $password                           = Hash::make('admin123');
                $customer_detail                    = new booking_customers();
                $customer_detail->name              = $lead_passenger->lead_first_name." ".$lead_passenger->lead_last_name;
                $customer_detail->opening_balance   = 0;
                $customer_detail->balance           = 0;
                $customer_detail->email             = $lead_passenger->lead_email;
                $customer_detail->password          = $password;
                $customer_detail->phone             = $lead_passenger->lead_phone;
                $customer_detail->gender            = $gender;
                $customer_detail->country           = $lead_passenger->lead_country;
                $customer_detail->customer_id       = $userData->id;
                $result                             = $customer_detail->save();
                $booking_customer_id                = $customer_detail->id;
            }
            
            $randomNumber                           = random_int(1000000, 9999999);
            $invoiceId                              =  "HH".$randomNumber;
            
            DB::table('transfers_new_booking')->insert([
                'invoice_no'                    => $invoiceId,
                'booking_status'                => 'Confirmed',
                'payment_method'                => $request->slc_pyment_method,
                'departure_date'                => $transfer_destination->pickup_date,
                'no_of_paxs'                    => $transfer_price_data->no_of_paxs_transfer,
                'hotel_booked'                  => $hotel_booked,
                'lead_passenger_data'           => json_encode($lead_passenger),
                'other_passenger_data'          => json_encode($transfer_data->other_passenger_details),
                'transfer_destination_id'       => $transfer_price_data->destination_avail_id,
                'transfer_data'                 => $request->transfer_destination_data,
                'transfer_price_exchange'       => $transfer_price_data->exchange_price_transfer,
                'transfer_total_price_exchange' => $transfer_price_data->exchange_price_total_transfer,
                'exchange_currency'             => $transfer_price_data->exchange_curreny_transfer,
                'transfer_price'                => $transfer_destination->total_fare_markup,
                'transfer_total_price'          => $transfer_price_data->original_price_total_transfer,
                'currency'                      => $transfer_price_data->original_curreny_transfer,
                'booking_customer_id'           => $booking_customer_id,
                'lead_passenger'                => $lead_passenger->lead_first_name." ".$lead_passenger->lead_last_name,
                'customer_id'                   => $userData->id,
                'booking_From'                  => $booking_From,
                'response_confirm_booking'      => $request->response_confirm_booking,
            ]);
            
            DB::commit();
            return response()->json(['status'=>'success','Invoice_no'=>$invoiceId]);
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
    }
    
    public function transfer_checkout_submit_with_service($request,$invoice_no,$booking_customer_id){
        // dd(json_decode($request));
        $request = json_decode($request);
        $transfer_data = json_decode($request->transfer_data);
        $lead_passenger = $transfer_data->lead_passenger_details;
        $transfer_destination = json_decode($request->transfer_destination_data);
        $transfer_price_data = $transfer_data->transfer_price_details;
        $hotel_booked = true;
       
       
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $booking_customer_id = $booking_customer_id;
      
            
                    

                    $result = DB::table('transfers_new_booking')->insert([
                            'invoice_no' => $invoice_no,
                            'booking_status' => 'Confirmed',
                            'payment_method' => $request->slc_pyment_method,
                            'departure_date' => $transfer_destination->pickup_date,
                            'no_of_paxs' => $transfer_price_data->no_of_paxs_transfer,
                            'hotel_booked' => $hotel_booked,
                            'lead_passenger_data' => json_encode($lead_passenger),
                            'other_passenger_data' => json_encode($transfer_data->other_passenger_details),
                            'transfer_destination_id' => $transfer_price_data->destination_avail_id,
                            'transfer_data' => $request->transfer_destination_data,
                            'transfer_price_exchange' => $transfer_price_data->exchange_price_transfer,
                            'transfer_total_price_exchange' => $transfer_price_data->exchange_price_total_transfer,
                            'exchange_currency' => $transfer_price_data->exchange_curreny_transfer,
                            'transfer_price' => $transfer_destination->total_fare_markup,
                            'transfer_total_price' => $transfer_price_data->original_price_total_transfer,
                            'currency' => $transfer_price_data->original_curreny_transfer,
                            'booking_customer_id' => $booking_customer_id,
                            'lead_passenger' => $lead_passenger->lead_first_name." ".$lead_passenger->lead_last_name,
                            'customer_id' => $userData->id,
                            
                        ]);

                if($result){
                    return true;
                }else{
                    return false;
                }
                
                
            
    }
}
