<?php

namespace App\Http\Controllers\frontend\admin_dashboard\emailEnquiry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\RoomsType;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\hotel_manager\RoomGallery;
use App\Models\allowed_Hotels_Rooms;
use Session;
use Auth;
use DB;
use App\Http\Controllers\Mail3rdPartyController;

class emailEnquiryController extends Controller
{
    public function getAllEmailEnquiries(Request $request){
        DB::beginTransaction();          
        try {
            $emailEnquiry       = DB::table('emailEnquiry')->where('token',$request->token)->orderby('id', 'desc')->get();
            if($emailEnquiry){
                return response()->json([
                    'status'    => 'success',
                    'data'      => $emailEnquiry,
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function addEmailEnquies(Request $request){
        DB::beginTransaction();          
        try {
            $userData           = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($userData == null){
                DB::commit();
                return response()->json(['status'=>'error','message'=>'Invalid Token']);
            }
            
            $package            = DB::table('tours')->where('id',$request->packageid)->first();
            if($package == null){
                DB::commit();
                return response()->json(['status'=>'error','message'=>'Package not exist']);
            }
            
            $result             = DB::table('emailEnquiry')->insert([
                'token'         => $request->token,
                'customer_id'   => $userData->id,
                'packageid'     => $request->packageid,
                'firstname'     => $request->firstname,
                'lastname'      => $request->lastname,
                'email'         => $request->email,
                'contact'       => $request->contact,
                'adults'        => $request->adults,
                'childs'        => $request->childs,
                'infants'       => $request->infants,
            ]);
            if($result){
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Your enquiry is submitted, our team will contact you soon!']);
            }else{
                DB::commit();
                return response()->json(['status'=>'error','message'=>'Something went wrong']);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error','message'=>'Something went wrong']);
        }
    }
    
    public function addEmailEnquiriesold(Request $request){
        DB::beginTransaction();          
        try {
            $userData           = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($userData == null){
                DB::commit();
                return response()->json(['status'=>'error','message'=>'Invalid Token']);
            }
            
            $package            = DB::table('tours')->where('id',$request->packageid)->first();
            if($package == null){
                DB::commit();
                return response()->json(['status'=>'error','message'=>'Package not exist']);
            }
            
            if($request->token == config('token_AlhijazTours')){
                $from_Address       = config('mail_From_Address_AlhijazTours');
                $mail_Template_Key  = config('mail_Template_Key_AlhijazTours');
                $website_Title      = config('mail_Title_AlhijazTours');
                $website_Url        = config('website_Url_AlhijazTours');
            }
            $reciever_Name          = $request->firstname.' '.$request->lastname;
            $to_Address             = $request->email;
            $email_Message          = '<div> <h3> Dear '.$reciever_Name.' ,</h3> Thank you for reaching out to us. We have received your enquiry and our team is currently reviewing the details. <br> One of our representatives will get back to you as soon as possible. In the meantime, if you have any additional information or questions, feel free to call us. <br> We appreciate your interest and look forward to assisting you. <br><br> Best regards, <br> <b> Al Hijaz Tours Ltd </b> <br><br> Call: 0121 777 2522 <br> Whatsapp: +44 7309 803307 </div>';
            $mail_Check             = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
            // return $mail_Check;
            
            $result             = DB::table('emailEnquiry')->insert([
                'token'         => $request->token,
                'customer_id'   => $userData->id,
                'packageid'     => $request->packageid,
                'firstname'     => $request->firstname,
                'lastname'      => $request->lastname,
                'email'         => $request->email,
                'contact'       => $request->contact,
                'adults'        => $request->adults,
                'childs'        => $request->childs,
                'infants'       => $request->infants,
            ]);
            if($result){
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Your enquiry is submitted, our team will contact you soon!']);
            }else{
                DB::commit();
                return response()->json(['status'=>'error','message'=>'Something went wrong']);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            echo $e;
            return response()->json(['message'=>'error','message'=>'Something went wrong']);
        }
    }
    
    public function addEmailEnquiries(Request $request){
        $request->validate([
            'token'     => 'required|string',
            'packageid' => 'required|integer|exists:tours,id',
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
            'email'     => 'required|email',
            'contact'   => 'required|string',
            'adults'    => 'required|integer',
            'childs'    => 'nullable|integer',
            'infants'   => 'nullable|integer',
        ]);
        
        DB::beginTransaction();          
        try {
            
            $userData = DB::table('customer_subcriptions')->where('Auth_key', $request->token)->first();
            if ($userData == null) {
                DB::rollback();
                return response()->json(['status' => 'error', 'message' => 'Invalid Token']);
            }
            
            $package = DB::table('tours')->where('id', $request->packageid)->first();
            if ($package == null) {
                DB::rollback();
                return response()->json(['status' => 'error', 'message' => 'Package does not exist']);
            }
            
            if ($request->token == config('token_AlhijazTours')) {
                $from_Address      = config('mail_From_Address_AlhijazTours');
                $mail_Template_Key = config('mail_Template_Key_AlhijazTours');
                $website_Title     = config('mail_Title_AlhijazTours');
                $website_Url       = config('website_Url_AlhijazTours');
            }
            
            $reciever_Name = $request->firstname . ' ' . $request->lastname;
            $to_Address    = $request->email;
            $email_Message = "<div> <h3> Dear $reciever_Name ,</h3> Thank you for reaching out to us. We have received your enquiry and our team is currently reviewing the details. <br> One of our representatives will get back to you as soon as possible. In the meantime, if you have any additional information or questions, feel free to call us. <br> We appreciate your interest and look forward to assisting you. <br><br> Best regards, <br> <b> Al Hijaz Tours Ltd </b> <br><br> Call: 0121 777 2522 <br> Whatsapp: +44 7309 803307 </div>";
            Mail3rdPartyController::mail_Check_All($from_Address, $to_Address, $reciever_Name, $email_Message, $mail_Template_Key);
            
            $result = DB::table('emailEnquiry')->insert([
                'token'       => $request->token,
                'customer_id' => $userData->id,
                'packageid'   => $request->packageid,
                'firstname'   => $request->firstname,
                'lastname'    => $request->lastname,
                'email'       => $request->email,
                'contact'     => $request->contact,
                'adults'      => $request->adults,
                'childs'      => $request->childs,
                'infants'     => $request->infants,
            ]);
    
            if ($result) {
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Your enquiry is submitted, our team will contact you soon!']);
            } else {
                DB::rollback();
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
        } catch (Throwable $e) {
            DB::rollback();    
            Log::error('Email Enquiry Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
        }
    }
}