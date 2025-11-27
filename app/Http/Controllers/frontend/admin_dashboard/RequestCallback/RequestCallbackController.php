<?php

namespace App\Http\Controllers\frontend\admin_dashboard\RequestCallback;

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

class RequestCallbackController extends Controller
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
    
    public function addRequestCallback(Request $request){
        $request->validate([
            'token'     => 'required|string',
            'name'      => 'required|string',
            'contact'   => 'required|string',
            'date'      => 'required|date_format:Y-m-d\TH:i',
            'noofpax'   => 'required|integer',
        ]);
        
        DB::beginTransaction();          
        try {
            $userData = DB::table('customer_subcriptions')->where('Auth_key', $request->token)->first();
            if ($userData == null) {
                DB::rollback();
                return response()->json(['status' => 'error', 'message' => 'Invalid Token']);
            }
            
            $result             = DB::table('requestCallback')->insert([
                'token'         => $request->token,
                'customer_id'   => $userData->id,
                'name'          => $request->name,
                'contact'       => $request->contact,
                'date'          => \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->date),
                'noofpax'       => $request->noofpax,
            ]);
    
            if ($result) {
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Your Request has been Successfully Reserved! Our Team will get back to you soon for confirmation.!']);
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