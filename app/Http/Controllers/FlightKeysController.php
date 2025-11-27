<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class FlightKeysController extends Controller
{
    public function flight_keys(Request $request){
        // 05-08-2023
        // $appId  = '95295218';
        // $appKey = 'cb7fb705fcf1f1ad5923fd8a1ad2f3a7';
        
        // 09-08-2023
        // $appId  = '2da238a1';
        // $appKey = '64304afee82694e0a1a989745eddc102';
        
        // 13-09-2023
        $appId      = '9e1a7af1';
        $appKey     = 'c057b9bcf7ac0f480354bc7009ddebf1';
        
        // 26-10-23
        // $new_key    = '653a56fd34d03694fc8208bd';
        
        // 14-11-23
        // $new_key    = '65535de502fda99a9acc310b';
        
        // 16-11-23
        $new_key    = '65562e34ca7c079a8c334ee3';
        
        return response()->json(['message'=>'success','appId'=>$appId,'appKey'=>$appKey,'new_key'=>$new_key]);
    }
    
    public function currency_keys(Request $request){
        // $data = 'ce6c67167238c69859a81f6e';
        
        // 13-07-2023
        $data = '31f97dbadbc16cad43b8ac97';
        
        return response()->json(['message'=>'success','data'=>$data]);
    }
    
    public function get_customer_id(Request $req){
        $customer_id = DB::table('customer_subcriptions')->where('Auth_key',$req->token)->select('id')->first();
        return response()->json(['customer_id'=>$customer_id]);
    }
}