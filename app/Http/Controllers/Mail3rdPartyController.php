<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\SubscriptionEmail;

class Mail3rdPartyController extends Controller
{
    public static function mail_Check($from_Address,$to_Address,$reciever_Name,$email_Message,$details){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "mail_template_key": "2d6f.41936cba9708a01.k1.8532aaf0-5ed5-11ef-ab39-525400cbcb5e.1916f16fb1f",
            "from": { "address": "'.$from_Address.'", "name": "noreply"},
            "to": [{"email_address": {"address": "'.$to_Address.'","name": "Tariq Hussain"}}],
            "merge_info": {"address_name":"'.$email_Message.'"}}',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR61w/R6jD6svyT38dLhpmA4GVFinHRl+0Fvzv3OvTfrLp8cznxfIAgGjSKQaEzFvQWMX9egukBpT12Zdht4tzVlUCCiF9mqRe1U4J3x17qnvhDzIXm1fkRKNLIsKxApuk2ZpFMoq+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        
        $response   = curl_exec($curl);
        // return $response;
        $err        = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'Error';
        } else {
            return 'Success';
        }
    }
    
    public static function mail_Check_AR($from_Address,$to_Address,$reciever_Name,$email_Message,$details){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "mail_template_key": "2d6f.41936cba9708a01.k1.e4a42040-6164-11ef-89c4-525400cbcb5e.1917fde0844",
            "from": { "address": "'.$from_Address.'", "name": "noreply"},
            "to": [{"email_address": {"address": "'.$to_Address.'","name": "Tariq Hussain"}}],
            "merge_info": {"address_name":"'.$email_Message.'"}}',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR61w/R6jD6svyT38dLhpmA4GVFinHRl+0Fvzv3OvTfrLp8cznxfIAgGjSKQaEzFvQWMX9egukBpT12Zdht4tzVlUCCiF9mqRe1U4J3x17qnvhDzIXm1fkRKNLIsKxApuk2ZpFMoq+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        
        $response   = curl_exec($curl);
        // return $response;die;
        $err        = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'Error';
        } else {
            return 'Success';
        }
    }
    
    public static function mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "mail_template_key": "'.$mail_Template_Key.'",
            "from": { "address": "'.$from_Address.'", "name": "noreply"},
            "to": [{"email_address": {"address": "'.$to_Address.'","name": "Tariq Hussain"}}],
            "merge_info": {"address_name":"'.$email_Message.'"}}',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR61w/R6jD6svyT38dLhpmA4GVFinHRl+0Fvzv3OvTfrLp8cznxfIAgGjSKQaEzFvQWMX9egukBpT12Zdht4tzVlUCCiF9mqRe1U4J3x17qnvhDzIXm1fkRKNLIsKxApuk2ZpFMoq+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        
        $response   = curl_exec($curl);
        // return $response;die;
        $err        = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'Error';
        } else {
            return 'Success';
        }
    }
    
    public static function mail_Check_Invoice($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key,$pdf_File_Link,$file_Name_Original){
        $pdf_Content        = file_get_contents($pdf_File_Link);
        $base64_attFile     = base64_encode($pdf_Content);
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "mail_template_key": "'.$mail_Template_Key.'",
            "from": { "address": "'.$from_Address.'", "name": "noreply"},
            "to": [{"email_address": {"address": "'.$to_Address.'","name": "Tariq Hussain"}}],
            "htmlbody":"<div><b> Kindly click on Verify Account to confirm your account </b></div>",
            "merge_info": {"address_name":"'.$email_Message.'"},
            "attachments": [{ "content": "'.$base64_attFile.'", "mime_type": "application/pdf", "name": "'.$file_Name_Original.'"}]}',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR61w/R6jD6svyT38dLhpmA4GVFinHRl+0Fvzv3OvTfrLp8cznxfIAgGjSKQaEzFvQWMX9egukBpT12Zdht4tzVlUCCiF9mqRe1U4J3x17qnvhDzIXm1fkRKNLIsKxApuk2ZpFMoq+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        
        $response   = curl_exec($curl);
        // echo $response;die;
        $err        = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'Error';
        } else {
            return 'Success';
        }
    }
    
    public static function mail_Check_Alsubaee($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "mail_template_key": "'.$mail_Template_Key.'",
            "from": { "address": "'.$from_Address.'", "name": "noreply"},
            "to": [{"email_address": {"address": "'.$to_Address.'","name": "Tariq Hussain"}}],
            "merge_info": {"address_name":"'.$email_Message.'"}}',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR61y+hDxXfgrnTSlILg9zF8BUwzxQU0o3VGh7iL9Gf2T9Mc+wk3HUFegHKcaQzNsHDETp+gqyhlUgGBcjdgtzlwCDSiF9mqRe1U4J3x17qnvhDzMWGhflRGLLYsJwQpskmhjG88h+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        
        $response   = curl_exec($curl);
        // return $response;die;
        $err        = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'Error';
        } else {
            return 'Success';
        }
    }
    
    public static function mail_Check_Alsubaee_Contact_Us(Request $request){
        $from_Address 		= config('mail_From_Address_Alsubaee');
        // $to_Address			= 'ua758323@gmail.com';
        $to_Address			= $request->email;
        $reciever_Name		= $request->first_name.' '.$request->last_name;
        $mail_Template_Key 	= config('mail_Template_Key_Alsubaee');
        $mail_Title			= config('mail_Title_Alsubaee');
        $email_Message      = '<div> <h3> Dear '.$reciever_Name.', </h3> </h4> <ul> <li>Name: '.$reciever_Name.' </li> <li>Email: '.$to_Address.' </li> <li>Subject: '.$request->subject.' </li> <li>Contact: '.$request->contact.' </li> <li>Message: '.$request->message.' </li> </ul> <h4> <br> Thank you <br> Team Alsubaee </div>';
        // return $email_Message;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "mail_template_key": "'.$mail_Template_Key.'",
            "from": { "address": "'.$from_Address.'", "name": "noreply"},
            "to": [{"email_address": {"address": "'.$to_Address.'","name": "Tariq Hussain"}}],
            "merge_info": {"address_name":"'.$email_Message.'"}}',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR61y+hDxXfgrnTSlILg9zF8BUwzxQU0o3VGh7iL9Gf2T9Mc+wk3HUFegHKcaQzNsHDETp+gqyhlUgGBcjdgtzlwCDSiF9mqRe1U4J3x17qnvhDzMWGhflRGLLYsJwQpskmhjG88h+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        
        $response   = curl_exec($curl);
        // return $response;die;
        $err        = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'Error';
        } else {
            return 'Success';
        }
    }
    
    public static function add_SubscriptionEmail(Request $request){
        // Validate the input
        $validated  = $request->validate([
            'token' => 'required|string|exists:customer_subcriptions,Auth_key',
            'email' => 'required|email|unique:subscription_emails,email,NULL,id,token,' . $request->token,
        ]);
        
        DB::beginTransaction();
        try {
            // Check if the token exists in the CustomerSubscription table
            $check_Token                    = CustomerSubcription::where('Auth_key', $request->token)->first();
            if ($check_Token) {
                // Insert the subscription email
                $SubscriptionEmail          = new SubscriptionEmail();
                $SubscriptionEmail->token   = $request->token;
                $SubscriptionEmail->email   = $request->email;
                $SubscriptionEmail->save();
                
                DB::commit();
                return response()->json([
                    'status'                => 'success',
                    'message'               => 'Your Email is Subscribed',
                ]);
            } else {
                return response()->json([
                    'status'                => 'error',
                    'message'               => 'Invalid Token',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status'                    => 'error',
                'message'                   => 'Something Went Wrong',
            ]);
        }
    }
    
    public static function get_SubscriptionEmail(Request $request){
        // Validate the input
        $validated  = $request->validate([
            'token' => 'required|string|exists:customer_subcriptions,Auth_key',
        ]);
        
        DB::beginTransaction();
        try {
            // Check if the token exists in the CustomerSubscription table
            $check_Token                    = CustomerSubcription::where('Auth_key', $request->token)->first();
            if ($check_Token) {
                // Get the Subscription emails
                $SubscriptionEmail          = SubscriptionEmail::where('token',$request->token)->get();
                
                DB::commit();
                return response()->json([
                    'status'                => 'success',
                    'message'               => 'Your Email is Subscribed',
                    'data'                  => $SubscriptionEmail,
                ]);
            } else {
                return response()->json([
                    'status'                => 'error',
                    'message'               => 'Invalid Token',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status'                    => 'error',
                'message'                   => 'Something Went Wrong',
            ]);
        }
    }
    
    public static function mail_Check_HR($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "mail_template_key": "'.$mail_Template_Key.'",
            "from": { "address": "'.$from_Address.'", "name": "noreply"},
            "to": [{"email_address": {"address": "'.$to_Address.'","name": "'.$reciever_Name.'"}}],
            "merge_info": {"address_name":"'.$email_Message.'"}}',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR60l+xXxBq0un2b4Ie9rm1sGVFmjHU110QGi43f1F/HLpsc9w0LPAlSlSvIXFTU7HGdDp7sqnBdVhDpfit8pnlBSXCiF9mqRe1U4J3x17qnvhDzDXWxUkRGNLo0IwQptm2JgEcwj+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        
        $response   = curl_exec($curl);
        // return $response;die;
        $err        = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'Error';
        } else {
            return 'Success';
        }
    }
    
    public static function mail_Check_HR_Contact_Us(Request $request){
        $from_Address 		= config('mail_From_Address_HaramaynRooms');
        // $to_Address			= 'ua758323@gmail.com';
        $to_Address			= $request->email;
        $reciever_Name		= $request->first_name.' '.$request->last_name;
        $mail_Template_Key 	= config('mail_Template_Key_HaramaynRooms');
        $mail_Title			= config('mail_Title_HaramaynRooms');
        $email_Message      = '<div> <h3> Dear '.$reciever_Name.', </h3> </h4> <ul> <li>Name: '.$reciever_Name.' </li> <li>Email: '.$to_Address.' </li> <li>Subject: '.$request->subject.' </li> <li>Contact: '.$request->contact.' </li> <li>Message: '.$request->message.' </li> </ul> <h4> <br> Thank you <br> Team Haramayn Rooms </div>';
        // return $email_Message;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "mail_template_key": "'.$mail_Template_Key.'",
            "from": { "address": "'.$from_Address.'", "name": "noreply"},
            "to": [{"email_address": {"address": "'.$to_Address.'","name": "'.$reciever_Name.'"}}],
            "merge_info": {"address_name":"'.$email_Message.'"}}',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR60l+xXxBq0un2b4Ie9rm1sGVFmjHU110QGi43f1F/HLpsc9w0LPAlSlSvIXFTU7HGdDp7sqnBdVhDpfit8pnlBSXCiF9mqRe1U4J3x17qnvhDzDXWxUkRGNLo0IwQptm2JgEcwj+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        
        $response   = curl_exec($curl);
        // return $response;die;
        $err        = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 'Error';
        } else {
            return 'Success';
        }
    }
}
?>