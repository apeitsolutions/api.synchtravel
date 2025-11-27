<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\booking_customers;
use Hash;
use App\Http\Controllers\Transfer_3rdPartyBooking_Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TransfersVehicleDestinationsController extends Controller
{
    public function vehicle_Destination_Listing(Request $request){
        try {
            // Validate Token
            $userData = CustomerSubcription::where('Auth_key', $request->token)->select('id', 'status', 'dashboard_Address')->first();
            if (!$userData) {
                return response()->json(['message' => 'error', 'vehicles_list' => '']);
            }
            
            // Fetch all vehicles
            $vehicles_list = DB::table('tranfer_vehicle')->where('customer_id', $userData->id)->get();
            if ($vehicles_list->isEmpty()) {
                return response()->json(['message' => 'error', 'vehicles_list' => []]);
            }
            
            // Fetch all transfer details
            $transfer_Details = DB::table('tranfer_destination')->where('customer_id', $userData->id)->get();
            
            // Process vehicle list
            foreach ($vehicles_list as $vehicle) {
                $available_Pax  = isset($vehicle->pax_Booked) && $vehicle->pax_Booked > 0 ? $vehicle->vehicle_Passenger - $vehicle->pax_Booked : $vehicle->vehicle_Passenger;
                
                // Set Vehicle Image URL
                $vehicle->Vehicel_Image_URL = $userData->dashboard_Address . '/public/uploads/package_imgs/' . $vehicle->vehicle_image;
                
                $all_Transfer_Details = [];
                
                foreach ($transfer_Details as $val_TD) {
                    if (empty($val_TD->vehicle_details)) {
                        continue;
                    }
                    
                    // Decode JSON safely
                    $vehicle_details_Decoded = json_decode($val_TD->vehicle_details);
                    
                    if (!is_array($vehicle_details_Decoded)) {
                        continue; // Skip if JSON decoding fails
                    }
                    
                    foreach ($vehicle_details_Decoded as $vehicle_details) {
                        if (!isset($vehicle_details->display_on_website) || $vehicle_details->display_on_website !== 'true') {
                            continue;
                        }
                        
                        if ($vehicle->id != $vehicle_details->vehicle_id) {
                            continue;
                        }
                        
                        $transfer_List_Item = (object)[
                            // Destination Details
                            'destination_Id'            => $val_TD->id,
                            'pickup_Date'               => $val_TD->available_from,
                            'dropof_Date'               => $val_TD->available_to,
                            'pickup_City'               => $val_TD->pickup_City,
                            'dropof_City'               => $val_TD->dropof_City,
                            'return_Pickup_City'        => $val_TD->return_pickup_City,
                            'return_Dropof_City'        => $val_TD->return_dropof_City,
                            'more_Destination_Details'  => $val_TD->more_destination_details,
                            'ziyarat_City_Details'      => $val_TD->ziyarat_City_details,
                            'transfer_Type'             => $val_TD->transfer_type,
                            // Supplier Details
                            'supplier_Id'               => $vehicle_details->transfer_supplier_Id ?? null,
                            'supplier_Name'             => $vehicle_details->transfer_supplier ?? null,
                            // Vehicle Price Details
                            'vehicle_Price'             => $vehicle_details->vehicle_Fare ?? 0,
                            'vehicle_Exchange_Rate'     => $vehicle_details->exchange_Rate ?? 0,
                            'vehicle_Total_Price'       => $vehicle_details->vehicle_total_Fare ?? 1,
                            'vehicle_Markup_Type'       => $vehicle_details->fare_markup_type ?? null,
                            'vehicle_Markup_Value'      => $vehicle_details->fare_markup ?? 0,
                            'vehicle_Markup_Price'      => $vehicle_details->total_fare_markup ?? 0,
                            'vehicle_Currency_Symbol'   => $vehicle_details->currency_symbol ?? null,
                            // 'vehicle_details_Decoded'   => $vehicle_details_Decoded,
                            // Conversion Details
                            'conversion_Id'             => $val_TD->conversion_type_Id ?? null,
                            'conversion_Name'           => $val_TD->currency_conversion ?? null,
                        ];
                        
                        if ($available_Pax > 0) {
                            $all_Transfer_Details[] = $transfer_List_Item;
                        }
                    }
                }
                
                $vehicle->destinations_Details = $all_Transfer_Details;
            }
            
            return response()->json(['message' => 'success', 'vehicles_list' => $vehicles_list]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'vehicles_list' => [], 'error' => $e->getMessage()]);
        }
    }
    
    public function vehicle_Destination_Detailing(Request $request){
        try {
            // Validate Token
            $userData = CustomerSubcription::where('Auth_key', $request->token)->select('id', 'status', 'dashboard_Address')->first();
            if (!$userData) {
                return response()->json(['message' => 'error', 'vehicles_list' => '']);
            }
            
            // Fetch all vehicles
            $vehicles_list = DB::table('tranfer_vehicle')->where('customer_id', $userData->id)->where('id', $request->vehicle_id)->get();
            if ($vehicles_list->isEmpty()) {
                return response()->json(['message' => 'error', 'vehicles_list' => []]);
            }
            
            // Fetch all transfer details
            $transfer_Details = DB::table('tranfer_destination')->where('customer_id', $userData->id)->get();
            
            // Process vehicle list
            foreach ($vehicles_list as $vehicle) {
                $available_Pax  = isset($vehicle->pax_Booked) && $vehicle->pax_Booked > 0 ? $vehicle->vehicle_Passenger - $vehicle->pax_Booked : $vehicle->vehicle_Passenger;
                
                // Set Vehicle Image URL
                $vehicle->Vehicel_Image_URL = $userData->dashboard_Address . '/public/uploads/package_imgs/' . $vehicle->vehicle_image;
                
                $all_Transfer_Details = [];
                
                foreach ($transfer_Details as $val_TD) {
                    if (empty($val_TD->vehicle_details)) {
                        continue;
                    }
                    
                    // Decode JSON safely
                    $vehicle_details_Decoded = json_decode($val_TD->vehicle_details);
                    
                    if (!is_array($vehicle_details_Decoded)) {
                        continue; // Skip if JSON decoding fails
                    }
                    
                    foreach ($vehicle_details_Decoded as $vehicle_details) {
                        if (!isset($vehicle_details->display_on_website) || $vehicle_details->display_on_website !== 'true') {
                            continue;
                        }
                        
                        if ($vehicle->id != $vehicle_details->vehicle_id) {
                            continue;
                        }
                        
                        $transfer_List_Item = (object)[
                            // Destination Details
                            'destination_Id'            => $val_TD->id,
                            'pickup_Date'               => $val_TD->available_from,
                            'dropof_Date'               => $val_TD->available_to,
                            'pickup_City'               => $val_TD->pickup_City,
                            'dropof_City'               => $val_TD->dropof_City,
                            'return_Pickup_City'        => $val_TD->return_pickup_City,
                            'return_Dropof_City'        => $val_TD->return_dropof_City,
                            'more_Destination_Details'  => $val_TD->more_destination_details,
                            'ziyarat_City_Details'      => $val_TD->ziyarat_City_details,
                            'transfer_Type'             => $val_TD->transfer_type,
                            // Supplier Details
                            'supplier_Id'               => $vehicle_details->transfer_supplier_Id ?? null,
                            'supplier_Name'             => $vehicle_details->transfer_supplier ?? null,
                            // Vehicle Price Details
                            'vehicle_Price'             => $vehicle_details->vehicle_Fare ?? 0,
                            'vehicle_Exchange_Rate'     => $vehicle_details->exchange_Rate ?? 0,
                            'vehicle_Total_Price'       => $vehicle_details->vehicle_total_Fare ?? 1,
                            'vehicle_Markup_Type'       => $vehicle_details->fare_markup_type ?? null,
                            'vehicle_Markup_Value'      => $vehicle_details->fare_markup ?? 0,
                            'vehicle_Markup_Price'      => $vehicle_details->total_fare_markup ?? 0,
                            'vehicle_Currency_Symbol'   => $vehicle_details->currency_symbol ?? null,
                            // Conversion Details
                            'conversion_Id'             => $val_TD->conversion_type_Id ?? null,
                            'conversion_Name'           => $val_TD->currency_conversion ?? null,
                        ];
                        
                        if ($available_Pax > 0) {
                            $all_Transfer_Details[] = $transfer_List_Item;
                        }
                    }
                }
                
                $vehicle->destinations_Details = $all_Transfer_Details;
            }
            
            return response()->json(['message' => 'success', 'vehicles_list' => $vehicles_list]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'vehicles_list' => [], 'error' => $e->getMessage()]);
        }
    }
    
    public function vehicle_Destination_Confirm_Booking(Request $request){
        DB::beginTransaction();
        try {
                $userData                                   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','hotel_Booking_Tag','currency_value')->first();
                $booking_customer_id                        = '';
                if($request->create_account == '1'){
                    $customer_exist                         = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$request->email)->first();
                    if(!empty($customer_exist)){
                        $booking_customer_id                = $customer_exist->id;
                    }else{
                        $customer_detail                    = new booking_customers();
                        if($request->title == "Mrs"){
                            $gender                         = 'female';
                        }else{
                            $gender                         = 'male';
                        }
                        $password                           = Hash::make('admin123');
                        $customer_detail->name              = $request->first_name." ".$request->last_name;
                        $customer_detail->opening_balance   = 0;
                        $customer_detail->balance           = 0;
                        $customer_detail->email             = $request->email;
                        $customer_detail->password          = $password;
                        $customer_detail->phone             = $request->phone;
                        $customer_detail->gender            = $gender;
                        $customer_detail->country           = $request->country;
                        $customer_detail->customer_id       = $userData->id;
                        $result                             = $customer_detail->save();
                        $booking_customer_id                = $customer_detail->id;
                    }
                }
                
                $randomNumber                               = random_int(1000000, 9999999);
                if(isset($userData->hotel_Booking_Tag) && $userData->hotel_Booking_Tag != null && $userData->hotel_Booking_Tag != ''){
                    $invoiceId                              = $userData->hotel_Booking_Tag.$randomNumber;
                }else{
                    $invoiceId                              = $randomNumber;
                }
                
                $destination_Data               = DB::table('tranfer_destination')->where('id',$request->destination_id)->first();
                $currency                       = $userData->currency_value;
                if(!empty($destination_Data->currency_conversion)){
                    preg_match('/\bTO\b\s+(\w+)/i', $destination_Data->currency_conversion, $matches);
                    $currency                   = $matches[1] ?? null;
                }
                $total_Price                    = $request->total_Price ?? null;
                
                DB::table('vehicle_bookings')->insert([
                    'b2b_agent_id'              => $request->b2b_agent_id ?? null,
                    'customer_id'               => $userData->id ?? null,
                    'booking_customer_id'       => $booking_customer_id ?? null,
                    'invoice_id'                => $invoiceId ?? null,
                    'vehicle_id'                => $request->vehicle_id ?? null,
                    'destination_id'            => $request->destination_id ?? null,
                    'first_name'                => $request->first_name ?? null,
                    'last_name'                 => $request->last_name ?? null,
                    'phone'                     => $request->phone ?? null,
                    'saudi_phone'               => $request->saudi_phone ?? null,
                    'country'                   => $request->country ?? null,
                    'email'                     => $request->email ?? null,
                    'destination_details'       => $request->destination_details ?? null,
                    'flight_details'            => $request->flight_details ?? null,
                    'whatsapp_no'               => $request->whatsapp_no ?? null,
                    'arrival_airport_date'      => $request->arrival_airport_date ?? null,
                    'date_of_booking'           => $request->date_of_booking ?? null,
                    'time_of_booking'           => $request->time_of_booking ?? null,
                    'additional_information'    => $request->additional_information ?? null,
                    'currency'                  => $currency,
                    'total_Price'               => $total_Price,
                    'payment_Details'           => $request->payment_Details ?? null,
                ]);
                
                if($request->token == config('token_AlhijazTours')){
                    $check_Mail = self::MailSend($request,$invoiceId,$currency,$total_Price);
                }
                
                DB::commit();
                return response()->json(['status'=>'success','Invoice_no'=>$invoiceId]);
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
    }
    
    public static function MailSend($request,$invoiceId,$currency,$total_Price){
        $mail_Send_Status   = false;
        
        // Alhijaz Tours
        if($request->token == config('token_AlhijazTours')){
            $from_Address           = config('mail_From_Address_AlhijazTours');
            $website_Title          = config('mail_Title_AlhijazTours');
            $mail_Template_Key      = config('mail_Template_Key_AlhijazTours');
            $website_Url            = config('website_Url_AlhijazTours').'transfer_invoice';
            $mail_Send_Status       = true;
        }
        
        if($mail_Send_Status != false){
            $booking_Date           = Carbon::now();
            $formatted_Booking_Date = $booking_Date->format('d-m-Y');
            $tranfer_vehicle        = DB::table('tranfer_vehicle')->where('id',$request->destination_id)->first();
            $destination_Data       = DB::table('tranfer_destination')->where('id',$request->destination_id)->first();
            $website_Invoice        = $website_Url.'/'.$invoiceId;
            $details                = [
                'invoice_no'        => $invoiceId,
                'title'             => $request->title,
                'Name'              => $request->first_name.' '.$request->last_name,
                'email'             => $request->email,
                'contact'           => $request->phone,
                'booking_Date'      => $formatted_Booking_Date,
                'pickup_City'       => $destination_Data->pickup_City,
                'dropof_City'       => $destination_Data->dropof_City,
                'pickup_date'       => date('Y-m-d',strtotime($request->arrival_airport_date)),
                'no_of_vehicles'    => $request->no_of_vehicles ?? '1',
                'price'             => $currency.' '.$total_Price,
            ];
            $email_Message          = '<div> <h3> Dear '.$details['title'].' '.$details['Name'].', </h3> <h4> Your booking has been Confirmed! Thank you for choosing our service.Below are the details of your booking:</h4> <ul><li>Invoice No: '.$details['invoice_no'].' </li> <li>Booking Date: '.$details['booking_Date'].'</li> <li>Pickup City: '.$details['pickup_City'].' </li><li>Dropoff City: '.$details['dropof_City'].' </li> <li>Pickup Date: '.$details['pickup_date'].' </li> <li>Number of Vehicle: '.$details['no_of_vehicles'].' </li></ul> <h3>Customer Details:</h3><ul><li>Name: '.$details['title'].' '.$details['Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <h4>We look forward to providing you with a comfortable and enjoyable stay. Should you have any questions or require further assistance, please do not hesitate to contact us. <br>Thank you for choosing '.$website_Title.' </h4> <br> Invoice Link : '.$website_Invoice.' </div>';
            $to_Address             = $request->email;
            $reciever_Name          = $request->first_name.' '.$request->last_name;
            $mail_Check             = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
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
    
    public function vehicle_Destination_Invoice(Request $request){
        $validated                      = $request->validate([
            'token'                     => 'required|string|exists:customer_subcriptions,Auth_key',
            'invoice_id'                => 'required|string|exists:vehicle_bookings,invoice_id',
        ]);
        
        try {
            $booking_Details            = DB::table('vehicle_bookings')->where('invoice_id',$request->invoice_id)->first();
            $vehicle_Details            = DB::table('tranfer_vehicle')->where('id',$booking_Details->vehicle_id)->first();
            $destination_Details        = DB::table('tranfer_destination')->where('id',$booking_Details->destination_id)->first();
            return response()->json([
                'status'                => 'success',
                'message'               => 'Invoice get successfully!',
                'booking_Details'       => $booking_Details,
                'vehicle_Details'       => $vehicle_Details,
                'destination_Details'   => $destination_Details,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status'                => 'error',
                'message'               => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
}