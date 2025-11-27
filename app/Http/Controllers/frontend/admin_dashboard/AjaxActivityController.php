<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Tour;
use App\Models\Active;
use App\Models\country;
use App\Models\Activities;
use DB;

class AjaxActivityController extends Controller
{
    
    
public function activitySearchajax(Request $request){
        $start_date     = date("Y-m-d", strtotime($request->start_date));
        $userData       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $customer_id    = $userData->id;
        $tours          = DB::table('new_activites')
                            ->where('country','LIKE','%'.''.$request->country_id.'%')
                            ->where('end_date','>=',$start_date)
                            ->where('customer_id','=',$customer_id)
                            ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes','start_date')->orderBy('created_at', 'desc')->get();
        $tomorrowDate   = date('Y-m-d', strtotime('-1 days'));
        $relatedtours   = DB::table('new_activites')
                            ->where('country','LIKE','%'.''.$request->country_id.'%')
                            ->where('start_date','>=',$tomorrowDate)
                            ->where('customer_id','=',$customer_id)
                            ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','start_date')->orderBy('created_at', 'desc')->get();
        return response()->json(['message'=>'success','tours'=>$tours,'relatedTours'=>$relatedtours]);
    }
public function get_activity_detail(Request $request){
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $tours      = DB::table('new_activites')->where('id',$request->id)->first();
        return response()->json(['message'=>'success','tours'=>$tours]);
    }
    
}