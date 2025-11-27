<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use DB;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\booking_customers;
use Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Transfer_3rdPartyBooking_Controller;

class TransfersReactController extends Controller
{
    public function transfers_All_Destinations(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $transfer_Locations = [];
            if(isset($request->name_pickup_location_plc) && $request->name_pickup_location_plc != null && $request->name_pickup_location_plc != ''){
                $transfer_Locations  = DB::table('tranfer_destination')
                                        ->where('pickup_City','LIKE','%'.''.$request->name_pickup_location_plc.'%')
                                        ->where('customer_id','=',$userData->id)
                                        ->select('pickup_City')
                                        ->limit(20)
                                        ->get();
            }
            
            if(isset($request->name_drop_off_location_plc) && $request->name_drop_off_location_plc != null && $request->name_drop_off_location_plc != ''){
                $transfer_Locations  = DB::table('tranfer_destination')->where('dropof_City','LIKE','%'.''.$request->name_drop_off_location_plc.'%')
                                        ->where('customer_id','=',$userData->id)
                                        ->select('dropof_City')
                                        ->limit(20)
                                        ->get();
            }
            return response()->json(['message'=>'Success','transfer_Locations'=>$transfer_Locations]);    
        }
        return response()->json(['message'=>'error','transfer_Locations'=>[]]);    
    }
    
    public function transfers_search_new(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            // $request->trip_type = 'Mazaraat';
            if($request->trip_type == 'Mazaraat'){
                $transfer_data      = DB::table('mazaraatDetails')
                                        ->where('city','LIKE','%'.$request->city.'%')
                                        ->where('customer_id',$userData->id)
                                        ->where('availableFrom','<=',$request->pick_up_date)
                                        ->where('availableTo','>=',$request->pick_up_date)
                                        ->get();
            }else if($request->trip_type == 'All_Round' && $request->token == config('token_AlmnhajHotel')){
                $transfer_data      = DB::table('tranfer_destination')
                                        ->where('pickup_City','LIKE','%'.$request->name_pickup_location_plc.'%')
                                        ->where('transfer_type','=',$request->trip_type)
                                        ->where('customer_id',$userData->id)
                                        ->where('available_from','<=',$request->pick_up_date)
                                        ->where('available_to','>=',$request->pick_up_date)
                                        ->get();
            }else{
                $transfer_data      = DB::table('tranfer_destination')
                                        // ->where('pickup_api_City','LIKE','%'.$request->name_pickup_location_plc.'%')
                                        ->where('pickup_City','LIKE','%'.$request->name_pickup_location_plc.'%')
                                        // ->where('dropof_api_City','LIKE','%'.$request->name_drop_off_location_plc.'%')
                                        ->where('dropof_City','LIKE','%'.$request->name_drop_off_location_plc.'%')
                                        ->where('transfer_type','=',$request->trip_type)
                                        ->where('customer_id',$userData->id)
                                        ->where('available_from','<=',$request->pick_up_date)
                                        ->where('available_to','>=',$request->pick_up_date)
                                        ->get();
            }
            // return $transfer_data;
            
            $startName_Array        = explode(',', $request->startName);
            $departure_Latitude     = $startName_Array[0];
            $destinationName_Array  = explode(',', $request->destinationName);
            $return_Latitude        = $destinationName_Array[0];
            
            $all_transfer_list = [];
            if(isset($transfer_data)){
                foreach($transfer_data as $transfer_res){
                    $supplier_details = json_decode($transfer_res->vehicle_details);
                    if(isset($supplier_details)){
                        foreach($supplier_details as $supplier_res){
                            if($supplier_res->display_on_website == 'true'){
                                $vehicle_data = DB::table('tranfer_vehicle')->where('id',$supplier_res->vehicle_id)->first();
                                if(isset($vehicle_data->vehicle_Passenger)){
                                    if($vehicle_data->vehicle_Passenger >= $request->passenger){
                                        $conversoin_data    = DB::table('mange_currencies')->where('id',$transfer_res->conversion_type_Id)->first();
                                        $currencyConverion  = $conversoin_data->purchase_currency.' TO '.$conversoin_data->sale_currency;
                                        
                                        if(isset($request->site_URL)){
                                            $img_URL = $request->site_URL.'/'.$vehicle_data->vehicle_image;
                                        }else{
                                            $img_URL = $vehicle_data->vehicle_image;
                                        }
                                        
                                        
                                        $pick_up_datetime   = new DateTime($request->pick_up_date . ' ' . $request->arrtime);
                                        $ret_datetime       = new DateTime($request->retdate . ' ' . $request->rettime);
                                        $duration           = $pick_up_datetime->diff($ret_datetime);
                                        // Total minutes if needed:
                                        $total_minutes      = ($duration->days * 24 * 60) + ($duration->h * 60) + $duration->i;
                                        // Human-readable string:
                                        $total_duration     = '';
                                        if ($duration->d > 0) {
                                            $total_duration .= $duration->d . ' days ';
                                        }
                                        $total_duration     .= $duration->h . ' hours ' . $duration->i . ' minutes';
                                        
                                        // Quantity Working
                                        $pax_Booked             = 0;
                                        $vehicleId              = $vehicle_data->id;
                                        $transfer_booking_data  = DB::table('transfers_new_booking')
                                                                    ->where('customer_id', $userData->id)
                                                                    ->where('departure_date', $request->pick_up_date)
                                                                    // ->where('booking_status', 'Confirmed')
                                                                    ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(transfer_data, '$.vehicle_Id')) = ?", [$vehicleId])
                                                                    ->get();
                                        if ($transfer_booking_data->isNotEmpty()) {
                                            foreach($transfer_booking_data as $valTB){
                                                $pax_Booked += $valTB->no_of_paxs;
                                            }
                                        }
                                        
                                        $pax_Remaining = 0;
                                        if($pax_Booked > 0){
                                            $pax_Remaining = $vehicle_data->vehicle_Passenger - $pax_Booked;
                                        }else{
                                            $pax_Remaining = $vehicle_data->vehicle_Passenger;
                                        }
                                        // Quantity Working
                                        
                                        
                                        // $pax_Remaining = 0;
                                        // if(isset($vehicle_data->pax_Booked) && $vehicle_data->pax_Booked > 0){
                                        //     $pax_Remaining = $vehicle_data->vehicle_Passenger - $vehicle_data->pax_Booked;
                                        // }else{
                                        //     $pax_Remaining = $vehicle_data->vehicle_Passenger;
                                        // }
                                        
                                        $transfer_list_item = (Object)[
                                            'booking_From'              => '',
                                            'destination_id'            => $transfer_res->id,
                                            'customer_id'               => $transfer_res->customer_id,
                                            'search_passenger'          => $request->passenger,
                                            'no_of_vehicles'            => $request->no_of_vehicles,
                                            'country'                   => $request->pick_up_location_country,
                                            'pickup_date'               => $request->pick_up_date,
                                            'pickup_City'               => $transfer_res->pickup_City ?? NULL,
                                            'dropof_City'               => $transfer_res->dropof_City ?? NULL,
                                            'return_pickup_City'        => $transfer_res->return_pickup_City ?? NULL,
                                            'return_dropof_City'        => $transfer_res->return_dropof_City ?? NULL,
                                            'pickup_api_City'           => $transfer_res->pickup_api_City ?? NULL,
                                            'dropof_api_City'           => $transfer_res->dropof_api_City ?? NULL,
                                            'more_destination_details'  => $transfer_res->more_destination_details ?? NULL,
                                            'ziyarat_City_details'      => $transfer_res->ziyarat_City_details,
                                            'transfer_type'             => $transfer_res->transfer_type ?? 'Mazaraat',
                                            'currency_conversion'       => $transfer_res->currency_conversion ?? $currencyConverion,
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
                                            'OccupancyTo'               => $vehicle_data->vehicle_Passenger ?? '',
                                            'SmallBagAllowance'         => 1,
                                            'BigBagAllowance'           => $vehicle_data->vehicle_Baggage ?? '',
                                            'TransferType'              => $transfer_res->transfer_type ?? '',
                                            'VehicleClass'              => $vehicle_data->vehicle_Type ?? '',
                                            'VehicleMake'               => $vehicle_data->vehicle_Type ?? '',
                                            'duration'                  => $total_duration ?? '',
                                            'departure_Latitude'        => $departure_Latitude ?? '',
                                            'return_Latitude'           => $return_Latitude ?? '',
                                            'deppoint'                  => $request->deppoint ?? '',
                                            'RetPoint'                  => $request->RetPoint ?? '',
                                            'pax_To_Book'               => $request->passenger ?? '0',
                                            'pax_Total'                 => $vehicle_data->vehicle_Passenger ?? '0',
                                            'pax_Booked'                => (string)$pax_Booked ?? $vehicle_data->pax_Booked ?? '0',
                                            'pax_Remaining'             => (string)$pax_Remaining,
                                            'sharing_Transfer'          => $request->sharing_Transfer ?? '',
                                            'vehicle_Id'                => $vehicle_data->id,
                                        ];
                                        
                                        if($pax_Remaining >= $request->passenger){
                                            $all_transfer_list[] = $transfer_list_item;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // return $all_transfer_list;
            
            $sessionID = '';
            if(isset($request->sharing_Transfer) || $request->sharing_Transfer != 1){
                // 3rd Party Api's
                $request_data           = $request;
                $trasnfer_Details_Api   = Transfer_3rdPartyBooking_Controller::search_Transfer_Api($request_data);
                // return $trasnfer_Details_Api;
                
                if(isset($trasnfer_Details_Api['trans']) && $trasnfer_Details_Api['trans'] != ''){
                    $details                = $trasnfer_Details_Api['details'];
                    // return $details;
                    
                    if (!empty($details->PlaceFrom)) {
                        if (preg_match('/u[0-9a-fA-F]{4}/', $details->PlaceFrom)) {
                            $details->PlaceFrom = preg_replace_callback('/u([0-9a-fA-F]{4})/', function ($matches) {
                                return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
                            }, $details->PlaceFrom);
                        }
                    }
                    
                    if (!empty($details->PlaceTo)) {
                        if (preg_match('/u[0-9a-fA-F]{4}/', $details->PlaceTo)) {
                            $details->PlaceTo = preg_replace_callback('/u([0-9a-fA-F]{4})/', function ($matches) {
                                return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
                            }, $details->PlaceTo);
                        }
                    }
                    
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
                    
                    // if($request->token == config('token_AlmnhajHotel')){
                    //     // Request Date/Time
                    //     $pick_up_datetime   = new DateTime($request->pick_up_date . ' ' . $request->arrtime);
                    //     $ret_datetime       = new DateTime($request->retdate . ' ' . $request->rettime);
                    //     $duration           = $pick_up_datetime->diff($ret_datetime);
                    //     // Total minutes if needed:
                    //     $total_minutes      = ($duration->days * 24 * 60) + ($duration->h * 60) + $duration->i;
                    //     // Human-readable string:
                    //     $total_duration     = $duration->h . " hours " . $duration->i . " minutes";
                    //     // Request Date/Time
                    // }
                    
                    // return $trasnfer_Details_Api;
                    if(is_array($trasnfer_Details_Api['trans'])){
                        foreach($trasnfer_Details_Api['trans'] as $transfer_res){
                            $extras_Avline  = [];
                            $VehicleClass   = $transfer_res->VehicleClass ?? '';
                            $VehicleMake    = $transfer_res->VehicleMake ?? '';
                            
                            if(empty((array)$VehicleClass)) {
                                $VehicleClass = '';
                            }
                            
                            if(empty((array)$VehicleMake)) {
                                $VehicleMake = '';
                            }
                            
                            $search_Extras  = Transfer_3rdPartyBooking_Controller::search_Extras_Transfer_Api($transfer_res->BookingID,$trasnfer_Details_Api['sessionID']);
                            $data_EX        = $search_Extras->getData()->data;
                            
                            if(isset($data_EX->TransferOnly->P2PResults->errors->error)){
                                $request_Extras     = '';
                                $response_Extras    = '';
                            }else{
                                $request_Extras     = $data_EX->TransferOnly->P2PResults->Extras->Request;
                                $response_Extras    = $data_EX->TransferOnly->P2PResults->Extras->Response;
                                // return count($response_Extras->Avline);
                                foreach($response_Extras->Avline as $val_Avline){
                                    $data_extras_Avline = [
                                        'ExtrasID'              => $val_Avline->ExtrasID ?? '',
                                        'ExtrasCode'            => $val_Avline->ExtrasCode ?? '',
                                        'MaxNumberOfExtras'     => $val_Avline->MaxNumberOfExtras ?? '',
                                        'Price'                 => $val_Avline->Price ?? '',
                                        'Extras_Description'    => $val_Avline->Extras_Description ?? '',
                                    ];
                                    array_push($extras_Avline,$data_extras_Avline);
                                }
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
                                // 'duration'                  => $total_duration ?? $total_duration_message ?? '',
                                'duration'                  => $total_duration_message ?? '',
                                'request_Extras'            => json_encode($request_Extras) ?? '',
                                'response_Extras'           => json_encode($response_Extras) ?? '',
                                'extras_Avline'             => json_encode($extras_Avline) ?? '',
                                'departure_Latitude'        => $departure_Latitude ?? '',
                                'return_Latitude'           => $return_Latitude ?? '',
                                'deppoint'                  => $request->deppoint ?? '',
                                'RetPoint'                  => $request->RetPoint ?? ''
                            ];
                            $all_transfer_list[] = $transfer_list_item;
                        }
                    }else{
                        $transfer_res   = $trasnfer_Details_Api['trans'];
                        $extras_Avline  = [];
                        $VehicleClass   = $transfer_res->VehicleClass ?? '';
                        $VehicleMake    = $transfer_res->VehicleMake ?? '';
                        
                        if(empty((array)$VehicleClass)) {
                            $VehicleClass = '';
                        }
                        
                        if(empty((array)$VehicleMake)) {
                            $VehicleMake = '';
                        }
                        
                        $search_Extras  = Transfer_3rdPartyBooking_Controller::search_Extras_Transfer_Api($transfer_res->BookingID,$trasnfer_Details_Api['sessionID']);
                        $data_EX        = $search_Extras->getData()->data;
                        
                        if(isset($data_EX->TransferOnly->P2PResults->errors->error)){
                            $request_Extras     = '';
                            $response_Extras    = '';
                        }else{
                            $request_Extras     = $data_EX->TransferOnly->P2PResults->Extras->Request;
                            $response_Extras    = $data_EX->TransferOnly->P2PResults->Extras->Response;
                            // return count($response_Extras->Avline);
                            foreach($response_Extras->Avline as $val_Avline){
                                $data_extras_Avline = [
                                    'ExtrasID'              => $val_Avline->ExtrasID,
                                    'ExtrasCode'            => $val_Avline->ExtrasCode,
                                    'MaxNumberOfExtras'     => $val_Avline->MaxNumberOfExtras,
                                    'Price'                 => $val_Avline->Price,
                                    'Extras_Description'    => $val_Avline->Extras_Description,
                                ];
                                array_push($extras_Avline,$data_extras_Avline);
                            }
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
                            'request_Extras'            => json_encode($request_Extras) ?? '',
                            'response_Extras'           => json_encode($response_Extras) ?? '',
                            'extras_Avline'             => json_encode($extras_Avline) ?? '',
                            'departure_Latitude'        => $departure_Latitude ?? '',
                            'return_Latitude'           => $return_Latitude ?? '',
                            'deppoint'                  => $request->deppoint ?? '',
                            'RetPoint'                  => $request->RetPoint ?? ''
                        ];
                        $all_transfer_list[] = $transfer_list_item;
                    }
                }else{
                    // return response()->json(['message'=>'error','transfers'=>'']);
                }
                
                if(isset($trasnfer_Details_Api['sessionID']) && $trasnfer_Details_Api['sessionID'] != ''){
                    $sessionID = $trasnfer_Details_Api['sessionID'];
                }else{
                    $sessionID = '';
                }
                // 3rd Party Api's
            }
            
            return response()->json(['message'=>'Success','transfers_list'=>$all_transfer_list,'sessionID'=>$sessionID]);
        }
        return response()->json(['message'=>'error','transfers'=>'']);    
    }
    
    public static function MailSend($request,$invoiceId){
        $mail_Send_Status   = false;
        
        // Umrah Shop
        if($request->token == config('token_UmrahShop')){
            $from_Address       = config('mail_From_Address_UmrahShop');
            $website_Title      = config('mail_Title_UmrahShop');
            $mail_Template_Key  = config('mail_Template_Key_UmrahShop');
            $website_Url        = config('website_Url_UmrahShop').'transfer_invoice';
            $mail_Send_Status   = true;
        }
        
        // Hashim Travel
        if($request->token == config('token_HashimTravel')){
            $from_Address       = config('mail_From_Address_HashimTravel');
            $website_Title      = config('mail_Title_HashimTravel');
            $mail_Template_Key  = config('mail_Template_Key_HashimTravel');
            $website_Url        = config('website_Url_HashimTravel').'transfer_invoice';
            $mail_Send_Status   = true;
        }
        
        if($request->token == config('token_HaramaynRooms')){
            $from_Address       = config('mail_From_Address_HaramaynRooms');
            $website_Title      = config('mail_Title_HaramaynRooms');
            $mail_Template_Key  = config('mail_Template_Key_HaramaynRooms');
            $website_Url        = config('website_Url_HaramaynRooms').'transfer-invoice';
            $mail_Send_Status   = true;
            $mail_Check_HR      = true;
        }
        
        if($request->token == config('token_AlmnhajHotel')){
            $from_Address       = config('mail_From_Address_AlmnhajHotel');
            $website_Title      = config('mail_Title_AlmnhajHotel');
            $mail_Template_Key  = config('mail_Template_Key_AlmnhajHotel');
            $website_Url        = config('website_Url_AlmnhajHotel').'transfer-invoice';
            $mail_Send_Status   = true;
            $mail_Check_HR      = true;
        }
        
        if($mail_Send_Status != false){
            $booking_Date               = Carbon::now();
            $formatted_Booking_Date     = $booking_Date->format('d-m-Y H:i:s');
            
            $transfer_data              = json_decode($request->transfer_data);
            // return $transfer_data;
            
            // Lead
            $lead_passenger_details     = $transfer_data->lead_passenger_details;
            $lead_title                 = $lead_passenger_details->lead_title;
            $lead_first_name            = $lead_passenger_details->lead_first_name;
            $lead_last_name             = $lead_passenger_details->lead_last_name;
            $lead_email                 = $lead_passenger_details->lead_email;
            $lead_phone                 = $lead_passenger_details->lead_phone;
            
            // Price
            $transfer_price_details     = $transfer_data->transfer_price_details;
            $currency                   = $transfer_price_details->exchange_curreny_transfer ?? $transfer_price_details->original_curreny_transfer ?? '';
            $total_Price                = $transfer_price_details->exchange_price_total_transfer ?? $transfer_price_details->exchange_price_transfer ?? '' ;
            
            
            $transfer_destination_data  = json_decode($request->transfer_destination_data);
            // return $transfer_destination_data;
            $pickup_City                = $transfer_destination_data->pickup_City;
            $dropof_City                = $transfer_destination_data->dropof_City;
            // $pickup_date                = date('d-m-Y', $transfer_destination_data->pickup_date);
            $pickup_date                = date('Y-m-d',strtotime($transfer_destination_data->pickup_date));
            $no_of_vehicles             = $transfer_destination_data->no_of_vehicles ?? '0';
            
            $website_Invoice            = $website_Url.'/'.$invoiceId;
            
            $details                    = [
                'invoice_no'            => $invoiceId,
                'lead_title'            => $lead_title,
                'lead_Name'             => $lead_first_name,
                'email'                 => $lead_email,
                'contact'               => $lead_phone,
                'booking_Date'          => $formatted_Booking_Date,
                'pickup_City'           => $pickup_City,
                'dropof_City'           => $dropof_City,
                'pickup_date'           => $pickup_date,
                'no_of_vehicles'        => $no_of_vehicles,
                'price'                 => $currency.' '.$total_Price,
            ];
            // <li>Number of Vehicle: '.$details['no_of_vehicles'].' </li>
            // return $details;
            
            $email_Message      = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> <h4> Your booking has been Confirmed! Thank you for choosing our service.Below are the details of your booking:</h4> <ul><li>Invoice No: '.$details['invoice_no'].' </li> <li>Booking Date: '.$details['booking_Date'].'</li> <li>Pickup City: '.$details['pickup_City'].' </li><li>Dropoff City: '.$details['dropof_City'].' </li> <li>Pickup Date: '.$details['pickup_date'].' </li> </ul> <h3>Customer Details:</h3><ul><li>Name: '.$details['lead_title'].' '.$details['lead_Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <h4>We look forward to providing you with a comfortable and enjoyable journey. Should you have any questions or require further assistance, please do not hesitate to contact us. <br>Thank you for choosing '.$website_Title.' </h4> <br> Invoice Link : '.$website_Invoice.' </div>';
            // return $email_Message;
            $to_Address         = $lead_email;
            $reciever_Name      = $lead_first_name;
            
            if($mail_Check_HR != false){
                $mail_Check         = Mail3rdPartyController::mail_Check_HR($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
            }else{
                $mail_Check         = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
            }
            // return $mail_Check;
            
            if($mail_Check == 'Success'){
                return 'Success';
            }else{
                return 'Error';
            }
        }else{
            return 'Error';
        }
    }
    
    public function transfer_checkout_submit(Request $request){
        // return $request;
        
        $transfer_data          = json_decode($request->transfer_data);
        $lead_passenger         = $transfer_data->lead_passenger_details;
        $transfer_destination   = json_decode($request->transfer_destination_data);
        $transfer_price_data    = $transfer_data->transfer_price_details;
        $hotel_booked           = false;
        if(isset($request->hotel_booked)){
            $hotel_booked       = true;
        }
        // return $lead_passenger;
        DB::beginTransaction();
        try {   
                if(isset($request->booking_From) && $request->booking_From != ''){
                    $booking_From           = '3rd Party Apis';
                }else{
                    $booking_From           = NULL;
                }
                
                $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','hotel_Booking_Tag')->first();
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
                    $customer_detail->password          = $password;
                    $customer_detail->phone             = $lead_passenger->lead_phone;
                    $customer_detail->gender            = $gender;
                    $customer_detail->country           = $lead_passenger->lead_country;
                    $customer_detail->customer_id       = $userData->id;
                    $result                             = $customer_detail->save();
                    $booking_customer_id                = $customer_detail->id;
                }
                
                $randomNumber   = random_int(1000000, 9999999);
                // $invoiceId      = "HH".$randomNumber;
                if(isset($userData->hotel_Booking_Tag) && $userData->hotel_Booking_Tag != null && $userData->hotel_Booking_Tag != ''){
                    $invoiceId  = $userData->hotel_Booking_Tag.$randomNumber;
                }else{
                    $invoiceId  = $randomNumber;
                }
                
                if(isset($transfer_destination->vehicle_Id) && $transfer_destination->vehicle_Id > 0){
                    if($transfer_destination->pax_Remaining > 0){
                        $pax_Remaining  = $transfer_destination->pax_Remaining - $transfer_destination->pax_To_Book;
                    }else{
                        $pax_Remaining  = $transfer_destination->pax_Total - $transfer_destination->pax_To_Book;
                    }
                    $pax_To_Book        = $transfer_destination->pax_Total - $pax_Remaining;
                    
                    DB::table('tranfer_vehicle')->where('id',$transfer_destination->vehicle_Id)->update([
                        'pax_Booked'    => $pax_To_Book,
                        'pax_Remaining' => $pax_Remaining,
                    ]);
                }
                
                $booking_status = 'Confirmed';
                if($request->payment_Type){
                    if($request->payment_Type == 'Bank_Payment'){
                        $booking_status = 'Tentative';
                    }
                }
                
                DB::table('transfers_new_booking')->insert([
                    'invoice_no'                    => $invoiceId,
                    'booking_status'                => $booking_status,
                    'b2b_agent_id'                  => $request->b2b_agent_id ?? NULL,
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
                    'exchange_currency'             => $transfer_price_data->exchange_curreny_transfer ?? '',
                    'transfer_price'                => $transfer_destination->total_fare_markup,
                    'transfer_total_price'          => $transfer_price_data->original_price_total_transfer,
                    'currency'                      => $transfer_price_data->original_curreny_transfer,
                    'booking_customer_id'           => $booking_customer_id,
                    'lead_passenger'                => $lead_passenger->lead_first_name." ".$lead_passenger->lead_last_name,
                    'customer_id'                   => $userData->id,
                    'booking_From'                  => $booking_From,
                    'payment_Type'                  => $request->payment_Type ?? NULL,
                    'response_confirm_booking'      => $request->response_confirm_booking ?? '',
                ]);
                
                // if($request->token == config('token_UmrahShop') || $request->token == config('token_HashimTravel') || $request->token == config('token_AlmnhajHotel') || $request->token == config('token_HaramaynRooms')){
                    // return $request;
                    // $invoiceId          = 'AL111111';
                    // $status_RB          = 'Confirmed';
                    $check_Mail         = self::MailSend($request,$invoiceId);
                    // return $check_Mail;
                // }
                
                DB::commit();
                return response()->json(['status'=>'success','Invoice_no'=>$invoiceId]);
        } catch (Throwable $e) {
             DB::rollback();
            echo $e;
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
    }
    
    public function transfer_invoice(Request $request){
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $transfer_booking_data = DB::table('transfers_new_booking')->where('invoice_no',$request->invoice_no)->first();
            if($transfer_booking_data){
                return response()->json([
                    'status' => 'success',
                    'transfer_booking_data' => $transfer_booking_data
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => "Wrong Invoice Number",
                ]);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "Validation error",
            ]);
        }
    }
    
    public function transfer_Make_Payemnt(Request $request){
        DB::beginTransaction();
        try {
            $request->validate([
                'payment_Image' => 'required|string',
            ]);
            
            $base64String = trim(stripslashes($request->input('payment_Image')), '"');
            if (preg_match('/^data:image\/(jpeg|png|gif);base64,/', $base64String, $type)) {
                $current_time           = date('d-m-Y H:i:s');
                $extension              = $type[1];
                $base64                 = substr($base64String, strpos($base64String, ',') + 1);
                $imageData              = base64_decode($base64);
                $fileName               = $current_time.'-'.$extension;
                $b2b_agent_id           = $request->b2b_agent_id;
                $invoice_No             = $request->invoice_No;
                $payment_Date           = $request->payment_Date;
                $transaction_No         = $request->transaction_No;
                $booking_data           = DB::table('transfers_new_booking')->where('invoice_no',$invoice_No)->first();
                
                if($booking_data->b2b_agent_id == $b2b_agent_id){
                    $payment_Type = 'Bank Transfer';
                    
                    if($booking_data->booking_status != 'Confirmed'){
                        
                        $customer_Data = DB::table('customer_subcriptions')->where('id',$booking_data->customer_id)->first();
                        
                        $payment_Details = [
                            'status'            => 'Tentative',
                            'b2b_agent_id'      => $b2b_agent_id,
                            'payment_Type'      => $payment_Type,
                            'total_Price'       => $booking_data->transfer_total_price ?? $booking_data->transfer_price ?? $booking_data->transfer_price_exchange ?? $booking_data->transfer_total_price_exchange ?? '',
                            'invoice_No'        => $invoice_No,
                            'payment_Date'      => $payment_Date,
                            'transaction_No'    => $transaction_No,
                            'payment_Image'     => $fileName,
                        ];
                        
                        DB::table('transfers_new_booking')->where('invoice_no',$invoice_No)->update(['booking_status' => 'Tentative','payment_method' => $payment_Details]);
                        
                        $path       = public_path('images' . $fileName);
                        Storage::disk('public')->put($fileName, $imageData);
                        file_put_contents($path, $imageData);
                        
                        DB::commit();
                        
                        $booking_data       = DB::table('transfers_new_booking')->where('invoice_no',$invoice_No)->first();
                        return response()->json([
                            'status'            => 'success',
                            'message'           => 'Your payment details have been submitted for review. Once the admin approves your payment, your booking will be confirmed.',
                            'payment_Details'   => $payment_Details,
                            'booking_Data'      => $booking_data,
                        ]);
                    }else{
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'Status Already Confirmed!',
                            'payment_Details'   => '',
                            'booking_Data'      => '',
                        ]);
                    }
                    
                }else{
                    return response()->json([
                        'status'        => 'error',
                        'message'       => 'Token not Matched!',
                        'booking_Data'  => ''
                    ]);
                }
            }else{
                return response()->json([
                    'status'        => 'error',
                    'message'       => 'Upload Payment Voucher!',
                    'booking_Data'  => ''
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['message'=>'error','message'=>'Something Went Wrong','Invoice_data'=> '']);
        }
        
        return $request;
    }
    
    public function make_Cancel_Request_Transfer(Request $request){
        try {
            $booking_data = DB::table('transfers_new_booking')->where('invoice_no',$request->invoice_no)->first();
            if($booking_data){
                $customer_Data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                
                if($customer_Data->id == $booking_data->customer_id && $booking_data->b2b_agent_id == $request->b2b_agent_id){
                    if($booking_data->booking_status == 'Confirmed' && $booking_data->after_Confirm_Status != 'Apply For Rejection'){
                        // Alsubaee Hotels
                        // $customer_Data = DB::table('customer_subcriptions')->where('id',$booking_data->customer_id)->first();
                        // if($customer_Data->Auth_key == config('token_Alsubaee')){
                        //     $from_Address               = config('mail_From_Address_Alsubaee');
                        //     $website_Title              = config('mail_Title_Alsubaee');
                        //     $mail_Template_Key          = config('mail_Template_Key_Alsubaee');
                        //     $website_Url                = config('website_Url_Alsubaee');
                        //     $mail_Address_Register_BPC  = config('mail_Address_Register_BPC');
                        //     // $mail_Address_Register_BPC  = 'ua758323@gmail.com';
                        //     $mail_Send_Status           = true;
                        //     $b2b_agents                 = DB::table('b2b_agents')->where('id',$request->b2b_agent_id)->first();
                        //     $b2b_Agent_First_Name       = $b2b_agents->first_name ?? '';
                        //     $b2b_Agent_Last_Name        = $b2b_agents->last_name ?? '';
                        //     $b2b_Agent_Name             = $b2b_Agent_First_Name.' '.$b2b_Agent_Last_Name;
                            
                        //     // return $booking_data;
                            
                        //     $currency                   = $booking_data->exchange_currency ?? 'SAR';
                        //     $total_Price                = $booking_data->exchange_price ?? '0';
                        //     $booking_Date               = Carbon::now();
                        //     $formatted_Booking_Date     = $booking_Date->format('d-m-Y H:i:s');
                            
                        //     $reservation_response       = json_decode($booking_data->reservation_response);
                        //     // return $reservation_response;
                        //     $hotel_details              = $reservation_response->hotel_details;
                        //     $room_Details               = $hotel_details->rooms;
                        //     $room_Message_Mail          = '';
                        //     foreach($room_Details as $val_RD){
                        //         $room_Data              = DB::table('rooms')->where('id',$val_RD->room_code)->first();
                                
                        //         $room_name              = $room_Data->room_type_name;
                        //         $meal_Type              = $room_Data->room_meal_type;
                        //         $room_Message_Mail      .= '<li>Room Type: '.$room_name.'</li><li>Meal Type: '.$meal_Type.'</li>';
                        //     }
                            
                        //     $check_in                   = Carbon::createFromFormat('Y-m-d', $hotel_details->checkIn);
                        //     $formatted_Check_In         = $check_in->format('d-m-Y');
                        //     $check_out                  = Carbon::createFromFormat('Y-m-d', $hotel_details->checkOut);
                        //     $formatted_Check_Out        = $check_out->format('d-m-Y');
                            
                        //     $details                    = [
                        //         'status'                => $booking_data->status,
                        //         'invoice_no'            => $booking_data->invoice_no,
                        //         'Check_In'              => $formatted_Check_In,
                        //         'Check_Out'             => $formatted_Check_Out,
                        //         'price'                 => $currency.' '.$total_Price,
                        //         'hotel_Name'            => $hotel_details->hotel_name ?? '',
                        //         'room_Message_Mail'     => $room_Message_Mail,
                        //     ];
                            
                        //     $email_Message_Register     = '<div> <h3> Dear Admin,</h3> <br> A cancellation request has been submitted by '.$website_Title.' , for Booking: '.$details['invoice_no'].'<br> Please login to dashboard and take action accordingly. <br><br> Thank you. </div>';
                        //     $reciever_Name              = $website_Title;
                        //     $mail_Check_Alsubaee        = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$mail_Address_Register_BPC,$reciever_Name,$email_Message_Register,$mail_Template_Key);
                        //     // return $mail_Check_Alsubaee;
                        // }
                        
                        DB::table('transfers_new_booking')->where('invoice_no',$request->invoice_no)->update([
                            'after_Confirm_Status'           => 'Apply For Rejection',
                            'cancellation_Request_Message'   => $request->cancellation_Request_Message,
                        ]);
                        
                        return response()->json([
                            'status'    => 'success',
                            'message'   => 'Cancellation Request Submitted',
                        ]);
                    }else{
                        if($booking_data->after_Confirm_Status == 'Apply For Rejection'){
                            return response()->json([
                                'status'    => 'error',
                                'message'   => 'Request Already Submitted',
                            ]);
                        }
                        
                        return response()->json([
                            'status'    => 'error',
                            'message'   => 'Confirm Booking First!',
                        ]);
                    }
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => "Token Not Matched",
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => "Invoice Number Not Matched",
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong',
            ]);
        }
    }
    
    public function add_CR_Proceed_Transfer(Request $request){
        DB::beginTransaction();
        try {
            $transfers_new_booking = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
            if($transfers_new_booking != null){
                DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->update([
                    'booking_status'        => 'Cancelled',
                    'after_Confirm_Status'  => 'Cancelled',
                    'cancellation_Price_CR' => $request->cancellation_Price_CR,
                ]);
                $transfers_new_booking = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
                DB::commit();
                return response()->json(['message'=>'success','Invoice_data'=>$transfers_new_booking,'Invoice_id'=>$request->invoice_Id]);
            }else{
                DB::commit();
                return response()->json(['message'=>'error']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function reject_Cncellation_Request_Transfer(Request $request){
        DB::beginTransaction();
        try {
            $transfers_new_booking = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
            if($transfers_new_booking != null){
                DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->update([
                    'after_Confirm_Status'  => 'Rejected',
                ]);
                $transfers_new_booking = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
                DB::commit();
                return response()->json(['message'=>'success','Invoice_data'=>$transfers_new_booking,'Invoice_id'=>$request->invoice_Id]);
            }else{
                DB::commit();
                return response()->json(['message'=>'error']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function reject_Pyament_Booking_Transfer(Request $request){
        try {
            $booking_data = DB::table('transfers_new_booking')->where('invoice_no',$request->invoice_Id)->first();
            if($booking_data){
                $customer_Data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                // return $customer_Data;
                
                DB::table('transfers_new_booking')->where('invoice_no',$request->invoice_Id)->update([
                    'payment_Reject_Status' => 'Rejected',
                ]);
                
                $Invoice_data = DB::table('transfers_new_booking')->where('invoice_no',$request->invoice_Id)->first();
                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Payment Rejected Successfully',
                    'Invoice_data'  => $Invoice_data,
                ]);
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => "Invoice Number Not Matched",
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong',
            ]);
        }
    }
    
    public function confirm_Booking_Transfer(Request $request){
        DB::beginTransaction();
        try {
            DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->where('id',$request->id)->update(['booking_status'=>'Confirmed']);
            $Invoice_data = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            DB::commit();
            return response()->json(['message'=>'success','Invoice_data'=>$Invoice_data]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function add_Transfer_Markup(Request $request){
        try {
            $userData                   = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($userData){
                $transferBooking        = DB::table('transfers_new_booking')->where('customer_id',$userData->id)->where('invoice_no',$request->invoice_no)->first();
                if($transferBooking){
                    DB::table('transfers_new_booking')->where('customer_id',$userData->id)->where('invoice_no',$request->invoice_no)->update(['agentMarkup' => $request->agentMarkup]);
                    return response()->json([
                        'status'        => 'success',
                        'message'       => 'Markup Added Successfully',
                    ]);
                }else{
                    return response()->json([
                        'status'        => 'error',
                        'message'       => 'Booking Not Found',
                    ]);
                }
            }else{
                return response()->json([
                    'status'            => 'error',
                    'message'           => 'Invalid Token',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
}