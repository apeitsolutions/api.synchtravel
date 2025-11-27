<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class CheckLoginController extends Controller
{
    public function check_Login(Request $request){
        // dd('STOP');
        DB::beginTransaction(); 
        try {
            if(isset($request->customer_id)){
                $token          = $request->token;
                $customer_id    = $request->customer_id;
                return response()->json(['status'=>'success','message'=>'User is login!','token'=>$token,'customer_id'=>$customer_id]);
            }else{
                return response()->json(['status'=>'error','message'=>'User is not login!']);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'User is not login!']);
        }
    }
}