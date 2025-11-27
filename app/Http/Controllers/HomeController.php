<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Leave;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function all_currency_get(){
        $currency = DB::table('countries')->get();
        return response()->json(['message'=>'success','currency'=>$currency]);
    }
    
    public static function MailSend($request){
        $mail_Send_Status       = false;
        
        // Alhijaz Rooms Mail
        if($request->token == config('token_AlhijazRooms')){
            $from_Address       = config('mail_From_Address_AlhijazRooms');
            $website_Title      = config('mail_Title_AlhijazRooms');
            $mail_Template_Key  = config('mail_Template_Key_AlhijazRooms');
            $website_Url        = config('website_Url_AlhijazRooms').'hotel_voucher';
            $mail_Send_Status   = true;
        }
        
        // Alsubaee Hotels
        if($request->token == config('token_Alsubaee')){
            $from_Address           = config('mail_From_Address_Alsubaee');
            $website_Title          = config('mail_Title_Alsubaee');
            $mail_Template_Key      = config('mail_Template_Key_Alsubaee');
            $website_Url            = config('website_Url_Alsubaee');
            $mail_Address_Register  = config('mail_Address_Register_Alsubaee');
            // $mail_Address_Register  = 'ua7583232@gmail.com';
            $mail_Send_Status       = true;
        }
        
        // Alif Hotels
        if($request->token == config('token_Alif')){
            $from_Address                   = config('mail_From_Address_Alif');
            $website_Title                  = config('mail_Title_Alif');
            $mail_Template_Key              = config('mail_Template_Key_Alif');
            $mail_Template_Key_Alif_Login   = config('mail_Template_Key_Alif_Login');
            $mail_Template_Key_Alif_Package = config('mail_Template_Key_Alif_Package');
            $website_Url                    = config('website_Url_Alif');
            // $mail_Address_Register  = 'ua7583232@gmail.com';
            $mail_Send_Status               = true;
        }
        
        // Umrah Shop
        if($request->token == config('token_UmrahShop')){
            $from_Address           = config('mail_From_Address_UmrahShop');
            $website_Title          = config('mail_Title_UmrahShop');
            $mail_Template_Key      = config('mail_Template_Key_UmrahShop');
            $website_Url            = config('website_Url_UmrahShop');
            // $mail_Address_Register  = 'ua7583232@gmail.com';
            $mail_Send_Status       = true;
        }
        
        // Haramayn Rooms
        if($request->token == config('token_HaramaynRooms')){
            $from_Address       = config('mail_From_Address_HaramaynRooms');
            $website_Title      = config('mail_Title_HaramaynRooms');
            $mail_Template_Key  = config('mail_Template_Key_HaramaynRooms');
            $website_Url        = config('website_Url_HaramaynRooms');
            $mail_Send_Status   = true;
        }
        
        // Almnhaj Hotels
        if($request->token == config('token_AlmnhajHotel')){
            $from_Address       = config('mail_From_Address_AlmnhajHotel');
            $website_Title      = config('mail_Title_AlmnhajHotel');
            $mail_Template_Key  = config('mail_Template_Key_AlmnhajHotel');
            $website_Url        = config('website_Url_AlmnhajHotel');
            $mail_Send_Status   = true;
        }
        
        if($mail_Send_Status != false){
            $booking_Date               = Carbon::now();
            $formatted_Booking_Date     = $booking_Date->format('d-m-Y H:i:s');
            $lead_title                 = $request->lead_title ?? '';
            $lead_email                 = $request->email ?? '';
            $lead_first_name            = $request->first_name ?? '';
            $lead_last_name             = $request->last_name ?? '';
            $lead_phone                 = $request->phone_no ?? '';
            $details                    = [
                'lead_title'            => $lead_title,
                'lead_Name'             => $lead_first_name,
                'email'                 => $lead_email,
                'contact'               => $lead_phone,
                'booking_Date'          => $formatted_Booking_Date,
            ];
            // $email_Message              = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> <h4> Your Account has been Registered! Thank you for choosing our service.Below are the details of your Account:</h4> <h3>Customer Details:</h3><ul><li>Name: '.$details['lead_title'].' '.$details['lead_Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <h4>Do you have any questions or require further assistance, please do not hesitate to contact us. <br>Thank you for choosing '.$website_Title.' </h4> </div>';
            $email_Message              = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3>  Your Account has been Registered! Thank you for choosing our service. Our team will review your details and approve within 24 to 48 hours. <br> <br> Do you have any questions or require further assistance, please do not hesitate to contact us. <br> <br> Regards, <br> '.$website_Title.' </div>';
            // return $email_Message;
            // $from_Address               = 'noreply@alhijazrooms.com';
            // $to_Address                 = 'ua7583232@gmail.com';
            $to_Address                 = $lead_email;
            $reciever_Name              = $lead_first_name;
            
            if($request->token == config('token_Alsubaee')){
                $mail_Check                 = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                // return $mail_Check;
                $email_Message_Register     = '<div> <h3> Dear '.$website_Title.', </h3> An agent has been Registered at '.$website_Title.' B2B portal. <br> Below are the details of your Account: <br><br> Customer Details: <ul><li>Name: '.$details['lead_title'].' '.$details['lead_Name'].' </li><li>Email: '.$details['email'].' </li><li>Phone Number: '.$details['contact'].' </li></ul> <br> Thank you. </div>';
                $mail_Check_Alsubaee        = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$mail_Address_Register,$reciever_Name,$email_Message_Register,$mail_Template_Key);
                // return $mail_Check_Alsubaee;
            }elseif($request->token == config('token_Alif')){
                $subscriptions_Packages     = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$request->select_Package)->first();
                $package_Expiry_Date        = Carbon::now()->addDays(364)->format('d-m-Y');
                // $portal_Address             = 'https://travel.alifvoyage.com';
                $email_Message              = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> Your Account has been created! Thank you for using our service. <br> Portal address: '.$website_Url.' <br> Please login with below details. <br><br> <ul> <li>Username: '.$details['email'].' </li> <li>Password: '.$request->password.' </li> </ul> <br><br> Do you have any questions or require further assistance, please do not hesitate to contact us. <br> <br> Regards, <br> '.$website_Title.' </div>';
                // dd($email_Message);
                $mail_Check                 = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key_Alif_Login);
                // <li>Price: '.$subscriptions_Packages->price_Of_Package.' </li>
                $detail_Message             = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> Your selected package is '.$subscriptions_Packages->name_Of_Package.'. <br><br> <ul> <li>Total Credits: '.$subscriptions_Packages->number_Of_Credits.' </li> <li>Hasnanat Points: '.$subscriptions_Packages->number_Of_Hasanat_Coins.' </li> <li>Renews On: '.$package_Expiry_Date.' </li> </ul> <br><br> Do you have any questions or require further assistance, please do not hesitate to contact us. <br> <br> Regards, <br> '.$website_Title.' </div>';
                // dd($detail_Message);
                $mail_Check                 = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$detail_Message,$mail_Template_Key_Alif_Package);
            }elseif($request->token == config('token_UmrahShop')){
                $email_Message              = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> Your Account has been created! Thank you for using our service. Please login with below details. <br><br> <ul> <li>Username: '.$details['email'].' </li> <li>Password: '.$request->password.' </li> </ul> <br><br> Do you have any questions or require further assistance, please do not hesitate to contact us. <br> <br> Regards, <br> '.$website_Title.' </div>';
                // $to_Address                 = 'ua758323@gmail.com';
                // dd($from_Address,$to_Address,$email_Message);
                $mail_Check                 = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                // dd($mail_Check);
            }elseif($request->token == config('token_HaramaynRooms') || $request->token == config('token_AlmnhajHotel')){
                $mail_Check             = Mail3rdPartyController::mail_Check_HR($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
            }else{
                $mail_Check             = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
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
    
    public static function register_Verify_Mail(Request $request){
        // return $request;
        
        DB::beginTransaction();
        try {
            if(isset($request->token) && $request->token != null && $request->token != ''){
                if(isset($request->email) && $request->email != null && $request->email != ''){
                    $customer_Data              = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                    $b2b_Agent_Details          = DB::table('b2b_agents')->where('token',$request->token)->where('email', $request->email)->get();
                    if($b2b_Agent_Details->isEmpty()){
                        // return 'ELSE OK';
                        
                        $b2b_Agent_Verify_Mail_Otp  = DB::table('b2b_Agent_Verify_Mail_Otp')->where('token',$request->token)->where('email', $request->email)->get();
                        if(empty($b2b_Agent_Details)){
                        }else{
                            foreach($b2b_Agent_Verify_Mail_Otp as $valFPO){
                                DB::table('b2b_Agent_Verify_Mail_Otp')->where('id', $valFPO->id)->update(['status' => 'Verified']);
                            }
                        }
                        
                        if($request->token == config('token_AlhijazRooms')){
                            $from_Address           = config('mail_From_Address_AlhijazRooms');
                            $mail_Template_Key      = config('mail_Template_Key_AlhijazRooms');
                            $website_Title          = config('mail_Title_AlhijazRooms');
                            $website_Url            = config('website_Url_AlhijazRooms');
                        }
                        
                        if($request->token == config('token_Alsubaee')){
                            $from_Address           = config('mail_From_Address_Alsubaee');
                            $mail_Template_Key      = config('mail_Template_Key_Alsubaee');
                            $website_Title          = config('mail_Title_Alsubaee');
                            $website_Url            = config('website_Url_Alsubaee');
                            $from_Alsubaee          = 'Active';
                        }
                        
                        if($request->token == config('token_HaramaynRooms')){
                            $from_Address           = config('mail_From_Address_HaramaynRooms');
                            $mail_Template_Key      = config('mail_Template_Key_HaramaynRooms');
                            $website_Title          = config('mail_Title_HaramaynRooms');
                            $website_Url            = config('website_Url_HaramaynRooms');
                            $from_HR                = 'Active';
                        }
                        
                        // Almnhaj Hotels
                        if($request->token == config('token_AlmnhajHotel')){
                            $from_Address           = config('mail_From_Address_AlmnhajHotel');
                            $website_Title          = config('mail_Title_AlmnhajHotel');
                            $mail_Template_Key      = config('mail_Template_Key_AlmnhajHotel');
                            $website_Url            = config('website_Url_AlmnhajHotel');
                            $from_HR                = 'Active';
                        }
                        
                        $otp_For_Bookings       = random_int(10000, 99999);
                        $reciever_Name          = $request->first_name ?? '' .' '. $request->last_name ?? '';
                        $to_Address             = $request->email;
                        // $email_Message          = '<h3> Dear '.$reciever_Name.', Enter Your OTP ('.$otp_For_Bookings.') to Register!</h3>';
                        $email_Message          = '<div> <h3> Dear '.$reciever_Name.',</h3> Enter Your OTP ('.$otp_For_Bookings.') to Register! <br><br> Regards <br> '.$website_Title.' </div>';
                        // return $mail_Check;
                        if(isset($from_Alsubaee) && $from_Alsubaee == 'Active'){
                            // $to_Address             = 'ua758323@gmail.com';
                            $mail_Check             = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                            // return $mail_Check;
                        }else{
                            if(isset($from_HR) && $from_HR == 'Active'){
                                $mail_Check         = Mail3rdPartyController::mail_Check_HR($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                                // return $mail_Check;
                            }else{
                                $mail_Check         = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                            }
                        }
                        // return $mail_Check;
                        
                        if($mail_Check != 'Success'){
                            return response()->json(['message'=>'Email Sending Failed!']);
                        }else{
                            DB::table('b2b_Agent_Verify_Mail_Otp')->insert([
                                'token'         => $request->token,
                                'customer_id'   => $customer_Data->id,
                                'email'         => $request->email,
                                'otp'           => $otp_For_Bookings,
                                'status'        => 'Waiting For Response',
                            ]);
                            
                            DB::commit();
                            
                            return response()->json([
                                'status'        => 'success',
                                'message'       => 'Check Your Email!',
                            ]);
                        }
                    }else{
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'B2B Agent Already Register!',
                        ]);
                    }
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'Email is Required!',
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token is Required!',
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public static function register_Verify_Otp(Request $request){
        // return $request;
        
        DB::beginTransaction();
        try {
            if(isset($request->token) && $request->token != null && $request->token != ''){
                if(isset($request->otp) && $request->otp != null && $request->otp != ''){
                    $otp_Details        = DB::table('b2b_Agent_Verify_Mail_Otp')->where('token',$request->token)->where('otp',$request->otp)->get();
                    if($otp_Details->isEmpty()){
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'Invalid OTP!',
                        ]);
                    }
                    
                    $otp_Details        = DB::table('b2b_Agent_Verify_Mail_Otp')->where('token',$request->token)->where('otp',$request->otp)->where('status','Waiting For Response')->get();
                    if($otp_Details->isEmpty()){
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'OTP is Expired!',
                        ]);
                    }else{
                        DB::table('b2b_Agent_Verify_Mail_Otp')->where('token',$request->token)->where('otp',$request->otp)->update(['status'=>'Verified']);
                        DB::commit();
                        return response()->json([
                            'status'        => 'success',
                            'message'       => 'Otp Verified!',
                        ]);
                    }
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'OTP is Required!',
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token is Required!',
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public function register_customer_submit(Request $request){
        // return $request;
        
        DB::beginTransaction();
        try {
            $currentDateTime        = Carbon::now()->format('d-m-Y-H:i:s');
            $token                  = $request->token;
            $title                  = $request->title;
            // Personal Details
            $email                  = $request->email;
            $first_name             = $request->first_name;
            $last_name              = $request->last_name;
            $password               = Hash::make($request->password);
            // Personal Details
            // Company Details
            $company_name           = $request->company_name;
            $company_address        = $request->company_address;
            $phone_no               = $request->phone_no;
            // Company Details
            $country                = $request->country;
            $city                   = $request->city;
            $zip_code               = $request->zip_code;
            $otp_code               = $request->otp_code;
            
            $b2b_agents_get         = DB::table('b2b_agents')->where('email',$email)->where('token',$token)->first();
            $clientData             = DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
            
            if(isset($b2b_agents_get)){
                return response()->json(['status'=>'error','message'=>'Account Already Registered!']);  
            }
            else{
                // Company Details
                if(isset($request->comapany_Details) && $request->comapany_Details == 'Active'){
                    $company_Website        = $request->company_Website;
                    $company_Phone_no       = $request->company_Phone_no;
                    
                    $base64String = trim(stripslashes($request->input('id_Card')), '"');
                    if (preg_match('/^data:image\/(jpeg|png|gif);base64,/', $base64String, $type)) {
                        $extension      = $type[1];
                        $base64         = substr($base64String, strpos($base64String, ',') + 1);
                        $imageData      = base64_decode($base64);
                        $id_Card        = $currentDateTime . '.' . $extension;
                        $path           = 'b2b_Agents_Documents/id_Card/' . $id_Card;
                        Storage::disk('public')->put($path, $imageData);
                    }
                    
                    $business_Document      = [];
                    if(isset($request->business_Document)){
                        foreach ($request->file('business_Document') as $file) {
                            $path_BD            = $currentDateTime . '_' . $file->getClientOriginalName();
                            array_push($business_Document,$path_BD);
                            $file->move(public_path('uploads/b2b_Agents_Documents/business_Document'), $path_BD);
                        }
                    }
                    
                    if(empty($business_Document)){
                        $business_Document = NULL;
                    }else{
                        $business_Document = json_encode($business_Document);
                    }
                }
                // Company Details
                
                // Billing Details
                if(isset($request->billing_Details) && $request->billing_Details == 'Active'){
                    $billing_Details        = $request->billing_Details;
                    $account_Name           = $request->account_Name;
                    $account_Number         = $request->account_Number;
                    $IBAN_Number            = $request->IBAN_Number;
                    $account_Credit         = $request->account_Credit;
                    
                    $account_Document       = [];
                    foreach ($request->file('account_Document') as $file) {
                        $path_AD            = $currentDateTime . '_' . $file->getClientOriginalName();
                        array_push($account_Document,$path_AD);
                        $file->move(public_path('uploads/b2b_Agents_Documents/account_Document'), $path_AD);
                    }
                    
                    // return $account_Document;
                    
                    if(empty($account_Document)){
                        $account_Document = NULL;
                    }else{
                        $account_Document = json_encode($account_Document);
                    }
                }
                // Billing Details
                
                if(isset($request->select_Package) && $request->select_Package != null && $request->select_Package != '' && $request->select_Package > 0){
                    $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$token)->where('id',$request->select_Package)->first();
                }
                
                $package_Expiry_Date    = Carbon::now()->addDays(364)->format('d-m-Y');
                
                $brandingLogo  = NULL;
                if(isset($request->brandingLogo) && $request->brandingLogo != 'null'){
                    $file               = $request->file('brandingLogo');
                    $brandingLogo       = $currentDateTime . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/b2b_Agents_Documents/brandingLogo'), $brandingLogo);
                }
                
                $b2b_agents             = DB::table('b2b_agents')->insertGetId([
                    'token'             => $token,
                    'title'             => $title,
                    'first_name'        => $first_name,
                    'last_name'         => $last_name,
                    'email'             => $email,
                    'password'          => $password,
                    'select_Package'    => $request->select_Package ?? NULL,
                    'total_Hasanat_Credits' => $subscriptions_Packages->number_Of_Credits ?? NULL,
                    'total_Hasanat_Points'  => $subscriptions_Packages->number_Of_Hasanat_Coins ?? NULL,
                    'package_Expiry_Date'   => $package_Expiry_Date,
                    'company_name'      => $company_name,
                    'company_address'   => $company_address,
                    'country'           => $country,
                    'city'              => $city,
                    'phone_no'          => $phone_no,
                    'zip_code'          => $zip_code,
                    'otp_code'          => $otp_code,
                    'position'          => $request->position ?? NULL,
                    'varified_at'       => '1',
                    
                    // Personal Details
                    'personal_Details'  => $request->personal_Details ?? NULL,
                    'user_Name'         => $request->user_Name ?? NULL,
                    
                    // Company Details
                    'comapany_Details'  => $request->comapany_Details ?? NULL,
                    'company_Website'   => $request->company_Website ?? NULL,
                    'id_Card'           => $id_Card ?? NULL,
                    'business_Document' => $business_Document ?? NULL,
                    'company_Phone_no'  => $company_Phone_no ?? NULL,
                    
                    // Billing Details
                    'billing_Details'   => $request->billing_Details ?? NULL,
                    'account_Name'      => $request->account_Name ?? NULL,
                    'account_Number'    => $request->account_Number ?? NULL,
                    'IBAN_Number'       => $request->IBAN_Number ?? NULL,
                    'account_Credit'    => $request->account_Credit ?? NULL,
                    'account_Document'  => $account_Document ?? NULL,
                    
                    'brandingLogo'      => $brandingLogo ?? NULL,
                ]);
                
                // return $b2b_agents;
                
                DB::table('alhijaz_Notofication')->insert([
                    'customer_id'               => $clientData->id,
                    'type_of_notification_id'   => $b2b_agents,
                    'type_of_notification'      => 'Register_B2B_Agent',
                    'generate_id'               => $b2b_agents,
                    'notification_creator_name' => $first_name,
                    'total_price'               => NULL,
                    'amount_paid'               => NULL,
                    'remaining_price'           => NULL,
                    'notification_status'       => '1',
                    
                ]);
                
                $mail_Send              = $this->MailSend($request);
                // return $mail_Send;
                
                DB::commit();
                
                if($mail_Send == 'Success'){
                    return response()->json(['status'=>'success','message'=>$b2b_agents,'mail_Send'=>'Success','message1']);
                }else{
                    return response()->json(['status'=>'success','message'=>$b2b_agents,'mail_Send'=>'Error','message1']);
                }
                
                // return response()->json(['status'=>'success','message'=>$b2b_agents,'message1']);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public function check_login_customer_submit(Request $request){
        $customer_Data  = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
        $b2b_agents     = DB::table('b2b_agents')->where('id',$request->id)->first();
        $all_Seasons    = DB::table('add_Seasons')->where('token',$request->token)->get();
        
        if(!empty($b2b_agents)){
            
            if($b2b_agents->reject_Status == 1){
                return response()->json(['status'=>'error','message'=>'Your account has been rejected!','error_code'=>5]);
            }
            
            if($b2b_agents->varified_at == 1){
                if($b2b_agents->approve_Status == 1){
                    if(isset($b2b_agents->select_Package) && $b2b_agents->select_Package > 0){
                        // Credits
                        $package_Expiry_Date    = Carbon::createFromFormat('d-m-Y', $b2b_agents->package_Expiry_Date);
                        $todayDate              = Carbon::now()->startOfDay();
                        if ($package_Expiry_Date->lessThan($todayDate)) {
                            $selected_package   = '';
                            $total_Credits      = '';
                            $booked_Credits     = '';
                        } else {
                            $selected_package   = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$b2b_agents->select_Package)->first();
                            $hasanat_CreditsD   = DB::table('hasanat_Credits')->where('token',$request->token)->where('selected_Client',$b2b_agents->id)->get();
                            $hasanat_Credits    = 0;
                            if($hasanat_CreditsD->isEmpty()){
                                $hasanat_Credits = 0;
                            }else{
                                foreach($hasanat_CreditsD as $val_HC){
                                    $credit_Expiry_Date    = Carbon::createFromFormat('d-m-Y', $val_HC->credit_Expiry_Date);
                                    if ($credit_Expiry_Date->lessThan($todayDate)) {
                                        $hasanat_Credits += 0;
                                    }else{
                                        $hasanat_Credits += $val_HC->add_Credits;
                                    }
                                }
                            }
                            // return $hasanat_Credits;
                            $total_Credits      = (float)$selected_package->number_Of_Credits + (float)$hasanat_Credits;
                            $booked_Credits     = DB::table('custom_Booking_Hasanat_Credits')->where('token',$request->token)->where('b2b_Agent_Id',$b2b_agents->id)->where('package_Id',$b2b_agents->select_Package)->sum('booked_Credits');
                            // $total_Credits      = (float)$b2b_agents->total_Hasanat_Credits + (float)$booked_Credits;
                        }
                        // Points
                        $points_Details         = DB::table('purchase_Coins')->where('token',$request->token)->where('package_Id',$b2b_agents->select_Package)->get();
                        $discount_Details       = DB::table('discount_Coins')->where('token',$request->token)->where('package_Id',$b2b_agents->select_Package)->get();
                        $total_Points           = (float)$b2b_agents->total_Hasanat_Points;
                        $booked_Points          = DB::table('3rd_Party_Booking_Hasanat_Coins')->where('token',$request->token)->where('b2b_Agent_Id',$b2b_agents->id)->where('package_Id',$b2b_agents->select_Package)->sum('booked_Hasanat_Coins');
                    }else{
                        $selected_package       = '';
                        $total_Credits          = '';
                        $booked_Credits         = '';
                        $points_Details         = [];
                        $discount_Details       = [];
                        $total_Points           = '';
                        $booked_Points          = '';
                    }
                    return response()->json([
                        'status'                => 'success',
                        'customer_Data'         => $customer_Data,
                        'b2b_agent'             => $b2b_agents,
                        'selected_package'      => $selected_package,
                        'total_Credits'         => $total_Credits,
                        'booked_Credits'        => $booked_Credits,
                        'points_Details'        => $points_Details,
                        'discount_Details'      => $discount_Details,
                        'total_Points'          => $total_Points,
                        'booked_Points'         => $booked_Points,
                        'all_Seasons'           => $all_Seasons,
                    ]);
                }else{
                    return response()->json(['status'=>'error','message'=>'Please Aprrove Your Account','error_code'=>1]);
                }
            }
            else{
                if($b2b_agents->varified_at != 1){
                    return response()->json(['status'=>'error','message'=>'Please Varified The Account','error_code'=>3]);
                }
                
                if($request->token != $b2b_agents->token){
                    return response()->json(['status'=>'error','message'=>'Please Register Your Account','error_code'=>4]);
                }
            }
        }
        else{
            return response()->json(['status'=>'error','message'=>'Please Register Your Account','error_code'=>4]);   
        }
    }
    
    public function login_customer_submit(Request $request){
        // return $request;
        $customer_Data  = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
        $b2b_agents     = DB::table('b2b_agents')->where('email',$request->email)->where('token',$request->token)->first();
        $all_Seasons    = DB::table('add_Seasons')->where('token',$request->token)->get();
        // return $b2b_agents;
        if(!empty($b2b_agents)){
            
            if($b2b_agents->reject_Status == 1){
                return response()->json(['status'=>'error','message'=>'Your account has been rejected!','error_code'=>5]);
            }
            
            if($request->email == $b2b_agents->email && Hash::check($request->password, $b2b_agents->password) && $b2b_agents->varified_at == 1 && $request->token == $b2b_agents->token){
                if($b2b_agents->approve_Status == 1){
                    if(isset($b2b_agents->select_Package) && $b2b_agents->select_Package > 0){
                        // Credits
                        $package_Expiry_Date    = Carbon::createFromFormat('d-m-Y', $b2b_agents->package_Expiry_Date);
                        $todayDate              = Carbon::now()->startOfDay();
                        if ($package_Expiry_Date->lessThan($todayDate)) {
                            $selected_package   = '';
                            $total_Credits      = '';
                            $booked_Credits     = '';
                        } else {
                            $selected_package   = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$b2b_agents->select_Package)->first();
                            $hasanat_CreditsD   = DB::table('hasanat_Credits')->where('token',$request->token)->where('selected_Client',$b2b_agents->id)->get();
                            $hasanat_Credits    = 0;
                            if($hasanat_CreditsD->isEmpty()){
                                $hasanat_Credits = 0;
                            }else{
                                foreach($hasanat_CreditsD as $val_HC){
                                    $credit_Expiry_Date    = Carbon::createFromFormat('d-m-Y', $val_HC->credit_Expiry_Date);
                                    if ($credit_Expiry_Date->lessThan($todayDate)) {
                                        $hasanat_Credits += 0;
                                    }else{
                                        $hasanat_Credits += $val_HC->add_Credits;
                                    }
                                }
                            }
                            // return $hasanat_Credits;
                            $total_Credits      = (float)$selected_package->number_Of_Credits + (float)$hasanat_Credits;
                            $booked_Credits     = DB::table('custom_Booking_Hasanat_Credits')->where('token',$request->token)->where('b2b_Agent_Id',$b2b_agents->id)->where('package_Id',$b2b_agents->select_Package)->sum('booked_Credits');
                            // $total_Credits      = (float)$b2b_agents->total_Hasanat_Credits + (float)$booked_Credits;
                        }
                        // Points
                        $points_Details         = DB::table('purchase_Coins')->where('token',$request->token)->where('package_Id',$b2b_agents->select_Package)->get();
                        $discount_Details       = DB::table('discount_Coins')->where('token',$request->token)->where('package_Id',$b2b_agents->select_Package)->get();
                        $total_Points           = (float)$b2b_agents->total_Hasanat_Points;
                        $booked_Points          = DB::table('3rd_Party_Booking_Hasanat_Coins')->where('token',$request->token)->where('b2b_Agent_Id',$b2b_agents->id)->where('package_Id',$b2b_agents->select_Package)->sum('booked_Hasanat_Coins');
                    }else{
                        $selected_package       = '';
                        $total_Credits          = '';
                        $booked_Credits         = '';
                        $points_Details         = [];
                        $discount_Details       = [];
                        $total_Points           = '';
                        $booked_Points          = '';
                    }
                    return response()->json([
                        'status'                => 'success',
                        'customer_Data'         => $customer_Data,
                        'b2b_agent'             => $b2b_agents,
                        'selected_package'      => $selected_package,
                        'total_Credits'         => $total_Credits,
                        'booked_Credits'        => $booked_Credits,
                        'points_Details'        => $points_Details,
                        'discount_Details'      => $discount_Details,
                        'total_Points'          => $total_Points,
                        'booked_Points'         => $booked_Points,
                        'all_Seasons'           => $all_Seasons,
                    ]);
                }else{
                    return response()->json(['status'=>'error','message'=>'Please Aprrove Your Account','error_code'=>1]);
                }
            }
            else{
                if(!Hash::check($request->password, $b2b_agents->password)){
                    return response()->json(['status'=>'error','message'=>'Please Correct Your Password','error_code'=>2]);
                }
                
                if($b2b_agents->varified_at != 1){
                    return response()->json(['status'=>'error','message'=>'Please Varified The Account','error_code'=>3]);
                }
                
                if($request->token != $b2b_agents->token){
                    return response()->json(['status'=>'error','message'=>'Please Register Your Account','error_code'=>4]);
                }
            }
        }
        else{
            return response()->json(['status'=>'error','message'=>'Please Register Your Account','error_code'=>4]);   
        }
    }
    
    public function update_customer_submit(Request $request){
        // return $request->id;
        
        DB::beginTransaction();
        try {
            $currentDateTime        = Carbon::now()->format('d-m-Y-H:i:s');
            $token                  = $request->token;
            $first_name             = $request->first_name;
            $last_name              = $request->last_name;
            $company_name           = $request->company_name;
            $company_address        = $request->company_address;
            $phone_no               = $request->phone_no;
            $b2b_agents_get         = DB::table('b2b_agents')->where('id',$request->id)->where('token',$token)->first();
            
            // Company Details
            if(isset($request->comapany_Details) && $request->comapany_Details == 'Active'){
                $company_Website        = $request->company_Website;
                $company_Phone_no       = $request->company_Phone_no;
                
                if(isset($request->id_Card)){
                    $base64String   = trim(stripslashes($request->input('id_Card')), '"');
                    if (preg_match('/^data:image\/(jpeg|png|gif);base64,/', $base64String, $type)) {
                        $extension      = $type[1];
                        $base64         = substr($base64String, strpos($base64String, ',') + 1);
                        $imageData      = base64_decode($base64);
                        $id_Card        = $currentDateTime . '.' . $extension;
                        $path           = 'b2b_Agents_Documents/id_Card/' . $id_Card;
                        Storage::disk('public')->put($path, $imageData);
                    }
                }else{
                    $id_Card        = $b2b_agents_get->id_Card;
                }
                
                if(isset($request->business_Document)){
                    $business_Document      = [];
                    if($b2b_agents_get->business_Document != null && $b2b_agents_get->business_Document != ''){
                        $business_Document_F = json_decode($b2b_agents_get->business_Document);
                        foreach ($business_Document_F as $val_BD) {
                            array_push($business_Document,$val_BD);
                        }
                    }
                    
                    foreach ($request->file('business_Document') as $file) {
                        $path_BD            = $currentDateTime . '_' . $file->getClientOriginalName();
                        array_push($business_Document,$path_BD);
                        $file->move(public_path('uploads/b2b_Agents_Documents/business_Document'), $path_BD);
                    }
                    
                    if(empty($business_Document)){
                        $business_Document  = NULL;
                    }else{
                        $business_Document  = json_encode($business_Document);
                    }
                }else{
                    $business_Document      = $b2b_agents_get->business_Document;
                }
            }
            // Company Details
            
            // Billing Details
            if(isset($request->billing_Details) && $request->billing_Details == 'Active'){
                $billing_Details        = $request->billing_Details;
                $account_Name           = $request->account_Name;
                $account_Number         = $request->account_Number;
                $IBAN_Number            = $request->IBAN_Number;
                $account_Credit         = $request->account_Credit;
                
                if(isset($request->account_Document)){
                    $account_Document       = [];
                    if($b2b_agents_get->account_Document != null && $b2b_agents_get->account_Document != ''){
                        $account_Document_F = json_decode($b2b_agents_get->account_Document);
                        foreach ($account_Document_F as $val_AD) {
                            array_push($account_Document,$val_AD);
                        }
                    }
                    foreach ($request->file('account_Document') as $file) {
                        $path_AD            = $currentDateTime . '_' . $file->getClientOriginalName();
                        array_push($account_Document,$path_AD);
                        $file->move(public_path('uploads/b2b_Agents_Documents/account_Document'), $path_AD);
                    }
                    
                    if(empty($account_Document)){
                        $account_Document   = NULL;
                    }else{
                        $account_Document   = json_encode($account_Document);
                    }
                }else{
                    $account_Document       = $b2b_agents_get->account_Document;
                }
            }
            // Billing Details
            
            $brandingLogo  = NULL;
            if(isset($request->brandingLogo) && $request->brandingLogo != 'null'){
                $file               = $request->file('brandingLogo');
                $brandingLogo       = $currentDateTime . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/b2b_Agents_Documents/brandingLogo'), $brandingLogo);
            }else{
                if($request->brandingLogo == 'null'){
                    $brandingLogo       = NULL;
                }else{
                    $brandingLogo       = $b2b_agents_get->brandingLogo;
                }
            }
            
            $b2b_agents             = DB::table('b2b_agents')->where('id',$request->id)->update([
                'first_name'        => $first_name,
                'last_name'         => $last_name,
                'company_name'      => $company_name,
                'company_address'   => $company_address,
                'phone_no'          => $phone_no,
                'country'           => $request->country ?? NULL,
                'city'              => $request->city ?? NULL,#
                
                // Personal Details
                'personal_Details'  => $request->personal_Details ?? NULL,
                'user_Name'         => $request->user_Name ?? NULL,
                'position'          => $request->position ?? NULL,
                'zip_code'          => $request->zip_code ?? NULL,
                
                // Company Details
                'comapany_Details'  => $request->comapany_Details ?? NULL,
                'company_Website'   => $request->company_Website ?? NULL,
                'id_Card'           => $id_Card ?? NULL,
                'business_Document' => $business_Document ?? NULL,
                'company_Phone_no'  => $company_Phone_no ?? NULL,
                
                // Billing Details
                'billing_Details'   => $request->billing_Details ?? NULL,
                'account_Name'      => $request->account_Name ?? NULL,
                'account_Number'    => $request->account_Number ?? NULL,
                'IBAN_Number'       => $request->IBAN_Number ?? NULL,
                'account_Credit'    => $request->account_Credit ?? NULL,
                'account_Document'  => $account_Document ?? NULL,
                
                'brandingLogo'      => $brandingLogo ?? NULL
            ]);
            // $mail_Send              = $this->MailSend($request);
            // return $mail_Send;
            
            $b2b_agent  = DB::table('b2b_agents')->where('id',$request->id)->where('token',$token)->first();
            
            DB::commit();
            return response()->json(['status'=>'success','b2b_agent'=>$b2b_agent]);
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public static function forgot_Password(Request $request){
        // return $request;
        
        DB::beginTransaction();
        try {
            if(isset($request->token) && $request->token != null && $request->token != ''){
                if(isset($request->email) && $request->email != null && $request->email != ''){
                    $customer_Data              = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                    // return $customer_Data;
                    $b2b_Agent_Details          = DB::table('b2b_agents')->where('token',$request->token)->where('email', $request->email)->get();
                    // return $b2b_Agent_Details;
                    if($b2b_Agent_Details->isEmpty()){
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'B2B Agent Not Found!',
                        ]);
                    }else{
                        
                        $b2b_Agent_Forgot_Password_Otp  = DB::table('b2b_Agent_Forgot_Password_Otp')->where('token',$request->token)->where('email', $request->email)->get();
                        if($b2b_Agent_Details->isEmpty()){
                        }else{
                            foreach($b2b_Agent_Forgot_Password_Otp as $valFPO){
                                DB::table('b2b_Agent_Forgot_Password_Otp')->where('id', $valFPO->id)->update(['status' => 'Expired']);
                            }
                        }
                        
                        // return 'ELSE OK';
                        
                        if($request->token == config('token_AlhijazRooms')){
                            $from_Address           = config('mail_From_Address_AlhijazRooms');
                            $mail_Template_Key      = config('mail_Template_Key_AlhijazRooms');
                            $website_Url            = config('website_Url_AlhijazRooms');
                        }
                        
                        if($request->token == config('token_Alsubaee')){
                            $from_Address           = config('mail_From_Address_Alsubaee');
                            $mail_Template_Key      = config('mail_Template_Key_Alsubaee');
                            $website_Url            = config('website_Url_Alsubaee');
                            $from_Alsubaee          = 'Active';
                        }
                        
                        if($request->token == config('token_Alif')){
                            $from_Address           = config('mail_From_Address_Alif');
                            $mail_Template_Key      = config('mail_Template_Key_Alif');
                            $website_Url            = config('website_Url_Alif');
                        }
                        
                        if($request->token == config('token_HaramaynRooms')){
                            $from_Address           = config('mail_From_Address_HaramaynRooms');
                            $mail_Template_Key      = config('mail_Template_Key_HaramaynRooms');
                            $website_Title          = config('mail_Title_HaramaynRooms');
                            $website_Url            = config('website_Url_HaramaynRooms');
                            $from_HR                = 'Active';
                        }
                        
                        // Almnhaj Hotels
                        if($request->token == config('token_AlmnhajHotel')){
                            $from_Address           = config('mail_From_Address_AlmnhajHotel');
                            $website_Title          = config('mail_Title_AlmnhajHotel');
                            $mail_Template_Key      = config('mail_Template_Key_AlmnhajHotel');
                            $website_Url            = config('website_Url_AlmnhajHotel');
                            $from_HR                = 'Active';
                        }
                        
                        $otp_For_Bookings       = random_int(10000, 99999);
                        $reciever_Name          = $b2b_Agent_Details[0]->first_name ?? '' .' '. $b2b_Agent_Details[0]->last_name ?? '';
                        $to_Address             = $request->email;
                        // $to_Address             = 'ua758323@gmail.com';
                        $reset_Password_Link    = $website_Url.'reset_Password/'.$b2b_Agent_Details[0]->id.'/'.$to_Address.'/'.$otp_For_Bookings;
                        $email_Message          = '<h3> Dear '.$reciever_Name.', Enter Your OTP ('.$otp_For_Bookings.') to Reset Password!</h3>';
                        // $email_Message          = '<h3> Dear '.$reciever_Name.', Enter Your OTP ('.$otp_For_Bookings.') to Reset Password! <br> Reset Password Link: '.$reset_Password_Link.' </h3>';
                        // return $email_Message;
                        if(isset($from_Alsubaee) && $from_Alsubaee == 'Active'){
                            $mail_Check             = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                        }else{
                            if(isset($from_HR) && $from_HR == 'Active'){
                                $mail_Check         = Mail3rdPartyController::mail_Check_HR($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                                // return $mail_Check;
                            }else{
                                $mail_Check         = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                            }
                            // return 'ELSE';
                        }
                        // return $mail_Check;
                        
                        if($mail_Check != 'Success'){
                            return response()->json(['message'=>'Email Sending Failed!']);
                        }else{
                            DB::table('b2b_Agent_Forgot_Password_Otp')->insert([
                                'token'         => $request->token,
                                'customer_id'   => $customer_Data->id,
                                'b2b_Agent_Id'  => $b2b_Agent_Details[0]->id,
                                'email'         => $request->email,
                                'otp'           => $otp_For_Bookings,
                                'status'        => 'Waiting For Response',
                            ]);
                            
                            DB::commit();
                            
                            return response()->json([
                                'status'        => 'success',
                                'message'       => 'Check Your Email!',
                            ]);
                        }
                    }
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'Email is Required!',
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token is Required!',
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public static function verify_Otp_B2B_Agent(Request $request){
        // return $request;
        
        DB::beginTransaction();
        try {
            if(isset($request->token) && $request->token != null && $request->token != ''){
                if(isset($request->otp) && $request->otp != null && $request->otp != ''){
                    $otp_Details        = DB::table('b2b_Agent_Forgot_Password_Otp')->where('token',$request->token)->where('otp',$request->otp)->get();
                    if($otp_Details->isEmpty()){
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'Invalid OTP!',
                        ]);
                    }
                    
                    $otp_Details        = DB::table('b2b_Agent_Forgot_Password_Otp')->where('token',$request->token)->where('otp',$request->otp)->where('status','Waiting For Response')->get();
                    if($otp_Details->isEmpty()){
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'OTP is Expired!',
                        ]);
                    }else{
                        DB::commit();
                        return response()->json([
                            'status'        => 'success',
                            'message'       => 'Otp Verified!',
                        ]);
                    }
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'OTP is Required!',
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token is Required!',
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public static function forgot_Password_mail($request,$b2b_Agent_Details){
        $mail_Send_Status       = false;
        
        // Alsubaee Hotels
        if($request->token == config('token_Alif')){
            $from_Address           = config('mail_From_Address_Alif');
            $website_Title          = config('mail_Title_Alif');
            $mail_Template_Key      = config('mail_Template_Key_Alif');
            $website_Url            = config('website_Url_Alif');
            // $mail_Address_Register  = 'ua7583232@gmail.com';
            $mail_Send_Status       = true;
        }
        
        if($mail_Send_Status != false){
            $b2b_Agents                 = DB::table('b2b_agents')->where('token',$request->token)->where('id',$b2b_Agent_Details->id)->first();
            $lead_title                 = $b2b_Agents->title ?? '';
            $lead_email                 = $b2b_Agents->email ?? '';
            $lead_first_name            = $b2b_Agents->first_name ?? '';
            $lead_last_name             = $b2b_Agents->last_name ?? '';
            $lead_phone                 = $b2b_Agents->phone_no ?? '';
            $details                    = [
                'lead_title'            => $lead_title,
                'lead_Name'             => $lead_first_name,
                'email'                 => $lead_email,
                'contact'               => $lead_phone,
            ];
            // dd($details);
            $to_Address                 = $lead_email;
            $reciever_Name              = $lead_first_name;
            
            if($request->token == config('token_Alif')){
                $email_Message          = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> Your Password has been Changed! Thank you for using our service. Please login with below details. <br><br> <ul> <li>Username: '.$details['email'].' </li> <li>Password: '.$request->password.' </li> </ul> <br><br> Do you have any questions or require further assistance, please do not hesitate to contact us. <br> <br> Regards, <br> '.$website_Title.' </div>';
                // return $email_Message;
                $mail_Check             = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
            }
            
            if($mail_Check == 'Success'){
                return 'Success';
            }else{
                return 'Error';
            }
        }else{
            return 'Error';
        }
    }
    
    public static function reset_Password(Request $request){
        // return $request;
        
        DB::beginTransaction();
        try {
            if(isset($request->token) && $request->token != null && $request->token != ''){
                if(isset($request->otp) && $request->otp != null && $request->otp != ''){
                    $otp_Details        = DB::table('b2b_Agent_Forgot_Password_Otp')->where('token',$request->token)->where('otp',$request->otp)->where('status','Waiting For Response')->get();
                    if($otp_Details->isEmpty()){
                        return response()->json([
                            'status'            => 'error',
                            'message'           => 'OTP is Expired!',
                        ]);
                    }else{
                        // return $otp_Details;
                        $b2b_Agent_Details  = DB::table('b2b_agents')->where('token',$request->token)->where('email', $otp_Details[0]->email)->get();
                        // return $b2b_Agent_Details[0];
                        if($b2b_Agent_Details->isEmpty()){
                            return response()->json([
                                'status'            => 'error',
                                'message'           => 'B2B Agent Not Found!',
                            ]);
                        }else{
                            // return 'ELSE OK';
                            
                            DB::table('b2b_agents')->where('id',$b2b_Agent_Details[0]->id)->where('token',$request->token)->update(['password' => Hash::make($request->password)]);
                            DB::table('b2b_Agent_Forgot_Password_Otp')->where('id',$otp_Details[0]->id)->where('token',$request->token)->where('otp',$request->otp)->update(['status' => 'Expired']);
                            
                            $mail_Send              = HomeController::forgot_Password_mail($request,$b2b_Agent_Details[0]);
                            // return $mail_Send;
                            
                            DB::commit();
                            return response()->json([
                                'status'        => 'success',
                                'message'       => 'Password Changed Successfully!',
                            ]);
                        }
                    }
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'OTP is Required!',
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token is Required!',
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong ('.$e.')',
            ]);
        }
    }
    
    public function sendVerificationEmail(Request $request){
        $token=$request->token;
            $email=$request->email;
          $otp_code=$request->otp_code;
          $b2b_agents=DB::table('b2b_agents')->where('email',$email)->update([
              'otp_code'=>$otp_code,
              ]);
              return response()->json(['status'=>'success','message'=>$b2b_agents]); 
    }
    
    public function sendVerificationOtpCode(Request $request){
       $token=$request->token;
          $otp_code=$request->otp_code;
          $b2b_agents=DB::table('b2b_agents')->where('otp_code',$otp_code)->update([
              'varified_at'=>1,
              ]);
              return response()->json(['status'=>'success','message'=>$b2b_agents]); 
    }
    
    public function submitForgetPasswordForm(Request $request){
       $token=$request->token;
          $email=$request->email;
          $reset_password_token=$request->reset_password_token;
          $b2b_agents=DB::table('b2b_agents')->where('email',$email)->update([
              'reset_password_token'=>$reset_password_token,
              ]);
          
          
            return response()->json(['status'=>'success','message'=>$b2b_agents]); 
    }
    
    public function submitResetPasswordForm(Request $request){
       $token=$request->token;
          $email=$request->email;
          $password_reset_token=$request->password_reset_token;
          $password=$request->password;
          $confirm_password=$request->confirm_password;
          if($password == $confirm_password)
          {
           $b2b_agents=DB::table('b2b_agents')->where('email',$email)->where('reset_password_token',$password_reset_token)->update([
              'password'=>Hash::make($password),
              ]);
          
         return response()->json(['status'=>'success','message'=>$b2b_agents]);   
          }
          else
          {
           return response()->json(['status'=>'error','message'=>'password Are Not Match']);   
          }
           
    }
}
