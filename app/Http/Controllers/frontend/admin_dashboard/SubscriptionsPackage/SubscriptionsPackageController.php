<?php
namespace App\Http\Controllers\frontend\admin_dashboard\SubscriptionsPackage;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Session;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
Use Illuminate\Support\Facades\Input as input;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\country;
use App\Models\b2b_Agents_detail;

class SubscriptionsPackageController extends Controller
{
    public function add_Subscriptions_Package(Request $request){
        DB::beginTransaction(); 
        try {
            
            DB::table('subscriptions_Packages')->insert([
                'token'                     => $request->token,
                'customer_id'               => $request->customer_id,
                'name_Of_Package'           => $request->name_Of_Package,
                'number_Of_Credits'         => $request->number_Of_Credits,
                'number_Of_Hasanat_Coins'   => $request->number_Of_Hasanat_Coins,
                'price_Of_Package'          => $request->price_Of_Package,
            ]);
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Subscriptions Package Added Successfully!']);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function subscriptions_Package_Details(Request $request){
        DB::beginTransaction(); 
        try {
            $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$request->token)->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','subscriptions_Packages'=>$subscriptions_Packages]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
}
