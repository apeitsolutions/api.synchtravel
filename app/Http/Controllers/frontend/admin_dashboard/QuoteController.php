<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\addQuote;

class QuoteController extends Controller
{
    public static function addQuote(Request $request){
        DB::beginTransaction();
        try {
            $userData           = CustomerSubcription::where('Auth_key',$request->token)->first();
            if(!$userData){
                return response()->json(['status'=>'error', 'message'=>'Token Invalid']);
            }
            
            DB::table('addQuote')->insert([
                'token'         => $request->token,
                'name'          => $request->name,
                'contact'       => $request->contact,
                'email'         => $request->email,
                'departure'     => $request->departure,
                'arrival'       => $request->arrival,
                'passengers'    => $request->passengers,
                'airport'       => $request->airport,
            ]);
            
            DB::commit();
            return response()->json(['status'=>'success', 'message'=>'Quote Added Successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['status'=>'error', 'message'=>'Something Went Wrong']);
        }
    }
}