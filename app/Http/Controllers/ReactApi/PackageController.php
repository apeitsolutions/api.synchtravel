<?php

namespace App\Http\Controllers\ReactApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\booking_customers;
use Carbon\Carbon;
use Hash;

class PackageController extends Controller
{
     public function get_all_tour_list_apis_new(Request $request){
        DB::beginTransaction();
        try {
            
            $token=$request->token;
            $cat_id=$request->cat_id;
            
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            $customer_id = $userData->id;
            //print_r($customer_id);die();
            if($request->cat_id == null){
                $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('customer_id',$customer_id)
                ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration',
                'tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours_2.flights_details','tours_2.flight_route_type','tours_2.return_flights_details','tours.accomodation_details',
                'tours.accomodation_details_more','tours.destination_details','tours.destination_details_more','tours.created_at')
                ->where('tour_feature',0)
                -orderBy('tours.created_at', 'asc')->get();
            
                $tours_enquire=DB::table('tours_enquire')->join('tours_2_enquire','tours_enquire.id','tours_2_enquire.tour_id')->where('customer_id',$customer_id)
                ->select('tours_enquire.id','tours_enquire.title','tours_2_enquire.quad_grand_total_amount','tours_2_enquire.triple_grand_total_amount','tours_2_enquire.double_grand_total_amount','tours_enquire.tour_featured_image',
                'tours_enquire.tour_banner_image','tours_enquire.time_duration','tours_enquire.content','tours_enquire.no_of_pax_days','tours_enquire.currency_symbol','tours_enquire.categories','tours_enquire.starts_rating','tours_enquire.start_date',
                'tours_enquire.end_date','tours_enquire.destination_details','tours_enquire.destination_details_more','tours_enquire.tour_location','tours.created_at')
                ->where('tour_feature',0)
                ->orderBy('tours_enquire.created_at', 'asc')->get();
                
                  
                if(isset($tours_enquire)){
                    foreach($tours_enquire as $index => $toure_res){
                        $tours_enquire[$index]->package_type = 'enquire';
                    }
                }
                
                $collection1 = collect($tours);
                $collection2 = collect($tours_enquire);
    
                $mergedCollection = $collection1->merge($collection2);
                $sortedCollection = $mergedCollection->sortByDesc('created_at');
                $tours = $sortedCollection->values()->all();
            }else{
                $categories= DB::table('categories')->where('id',$request->cat_id)->select('title')->first();
                $today_date=date('Y-m-d');
                $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('customer_id',$customer_id)->where('categories',$request->cat_id)
                    ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration',
                    'tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours_2.flights_details','tours_2.flight_route_type','tours_2.return_flights_details','tours.accomodation_details',
                    'tours.accomodation_details_more','tours.destination_details','tours.destination_details_more','tours.created_at')
                      ->where('tour_feature',0)
                                    ->where('tours.customer_id',$customer_id)
                                    ->where('tours.start_date','>=',$today_date)
                                    ->orwhereJsonContains('tours_2.flights_details', $request->departure_from)
                                    ->orderBy('tours.created_at', 'desc')->get();
                
                //print_r($tours);die();
                $tours_enquire=DB::table('tours_enquire')->join('tours_2_enquire','tours_enquire.id','tours_2_enquire.tour_id')->where('customer_id',$customer_id)->where('categories',$request->cat_id)
                    ->select('tours_enquire.id','tours_enquire.title','tours_2_enquire.quad_grand_total_amount','tours_2_enquire.triple_grand_total_amount','tours_2_enquire.flights_details','tours_2_enquire.double_grand_total_amount','tours_enquire.tour_featured_image',
                    'tours_enquire.tour_banner_image','tours_enquire.time_duration','tours_enquire.content','tours_enquire.no_of_pax_days','tours_enquire.currency_symbol','tours_enquire.categories','tours_enquire.starts_rating','tours_enquire.start_date',
                    'tours_enquire.end_date','tours_enquire.destination_details','tours_enquire.destination_details_more','tours_enquire.tour_location','tours_enquire.created_at')
                    ->where('tours_enquire.customer_id',$customer_id)
                                    ->where('tours_enquire.start_date','>=',$today_date)
                                    ->orwhereJsonContains('tours_2_enquire.flights_details', $request->departure_from)
                                    ->orderBy('tours_enquire.created_at', 'desc')->get();
                    
                
                if(isset($tours_enquire)){
                    foreach($tours_enquire as $index => $toure_res){
                        $tours_enquire[$index]->package_type = 'enquire';
                    }
                }
                
                $collection1 = collect($tours);
                $collection2 = collect($tours_enquire);
    
                $mergedCollection = $collection1->merge($collection2);
                $sortedCollection = $mergedCollection->sortByDesc('created_at');
                $tours = $sortedCollection->values()->all();
            }
            
            $all_active_providers   = DB::table('3rd_party_commissions')->where('third_party_id',$customer_id)->where('status','approve')->get();
            $providers_tours        = [];
            foreach($all_active_providers as $active_pro_res){
                 $other_providers_tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                 ->where('customer_id',$active_pro_res->customer_id)
                 ->where('others_providers_show', 'true')
                ->select('tours.id','tours.title','tours_2.quad_grand_total_amount','tours_2.flights_details','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image',
                'tours.tour_banner_image','tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.categories','tours.starts_rating','tours.start_date',
                'tours.end_date','tours.destination_details','tours.destination_details_more','tours.tour_location')
                ->orderBy('tours.created_at', 'asc')->get();
                
                
                $provider_data = CustomerSubcription::where('id',$active_pro_res->customer_id)->select('id','name','lname','webiste_Address','dashboard_Address')->first();
                $provider_arr = [$other_providers_tours,$provider_data];
                array_push($providers_tours,$provider_arr);
            }
            
            return response()->json(['message'=>'success','tours'=>$tours,'providers_tours'=>$providers_tours,'customer_id'=>$customer_id,'categories'=>$categories]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            $result = false;
            return response()->json(['message'=>'error']);
        }
    }
     public function get_all_catigories_list_apis_new(Request $request){
         $token=$request->token;
   $userData = CustomerSubcription::where('Auth_key',$token)->select('id')->first();
    $categories= DB::table('categories')->where('customer_id',$userData->id)->select('id','title')->orderBy('id','DESC')->get();
   return response()->json(['categories'=>$categories]);
 }
}