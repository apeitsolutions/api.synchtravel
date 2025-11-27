<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CustomerSubcription\CustomerSubcription;
use DB;

class WebsiteController extends Controller
{
    //
    public function get_website_index_data(Request $request){
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','webiste_Address','phone','email')->first();
        if($userData){
        // if($request->currentURL == $userData->webiste_Address){
        
            $visa_types = DB::table('visa_types')->where('customer_id',$userData->id)->get();
            if(isset($request->type) && $request->type == 'activity'){ 
                $customer_id        = $userData->id;
                $transfer_detail    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
                $activities         = DB::table('new_activites')->where('customer_id',$customer_id)->select('id','title','currency_symbol','duration','location','activity_content','tour_attributes','min_people','max_people','Availibilty','sale_price','child_sale_price','featured_image','banner_image','activity_date','starts_rating','payment_getway')->orderBy('created_at', 'desc')->limit(6)->get();
                return response()->json(['message'=>'success','activity'=>$activities,'transfer_detail'=>$transfer_detail]);
            }
            
            $userData           = CustomerSubcription::where('Auth_key',$request->token)->first();
            $today_date         = date('Y-m-d');
            $customer_id        = $userData->id;
            $transfer_detail    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->select('ziyarat_City_details')->get();
            $tours              = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                                    ->select('tours.id','tours.title','tours_2.quad_grand_total_amount','tours_2.flights_details','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.tour_location','tours.starts_rating','tours.whats_included','tours.start_date','tours.end_date','tours.gallery_images')
                                    ->where('tour_feature',0)
                                    ->where('tours.customer_id',$customer_id)
                                    ->where('tours.start_date','>=',$today_date)
                                    ->orwhereJsonContains('tours_2.flights_details', $request->departure_from)
                                    ->orderBy('tours.created_at', 'desc')->get();

            $hotels = DB::table('hotels')
                        ->select('hotels.id', 'hotels.property_name','hotels.property_img','hotels.currency_symbol','hotels.star_type','hotels.property_city', DB::raw('MIN(rooms.price_all_days) AS min_price'))
                        ->join('rooms', 'hotels.id', '=', 'rooms.hotel_id')
                        ->groupBy('hotels.id', 'hotels.property_name')
                        ->where('hotels.owner_id',$userData->id)
                        ->get();
                        
                        
                        
            // Get All Top Categories
                $final_data = [];
                $categories = DB::table('categories')->where('customer_id',$userData->id)->select('id','image','title','description')->orderBy('placement', 'asc')->limit(5)->get();
                
                
               $category_count_arr=[];
                foreach($categories as $cat_res){
                    $result = DB::table('tours')
                                    ->join('tours_2','tours.id','tours_2.tour_id')
                                    ->where('tours.customer_id',$userData->id)
                                    ->where('tours.categories',$cat_res->id)->count();
                    $category_count_arr[] = $result;
                   
                }
                
                
                $final_data = [$categories,$category_count_arr];
                
                
                
            // Get All Categories
                $categories = DB::table('categories')->where('customer_id',$userData->id)->select('id','image','title','description')->orderBy('placement', 'asc')->get();


            // Get Meta Data Page
                $meta_data       = DB::table('pages_meta_info')->where('page_url',$request->currentURL)->first();
                
            
            // Category Tours
                $category_tours = [];
        
                if(isset($final_data[0])){
                    foreach($final_data[0] as $cat_res){
                        $today_date = date('Y-m-d');
                        $customer_id = $userData->id;
                 
                        
                        $tours=     DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                                ->where('tours.start_date','>=',$today_date)->where('tours.customer_id',$customer_id)
                                ->where('tours.categories',$cat_res->id)
                                ->where('tours.tour_feature',0)
                                ->select('tours.id','tours.start_date','tours.end_date','tours.title','tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image',
                                'tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.categories','tours.created_at')
                                
                                ->orderBy('tours.created_at', 'desc')->limit($request->limit)->get();
                        
                        $tours_enquire=     DB::table('tours_enquire')->join('tours_2_enquire','tours_enquire.id','tours_2_enquire.tour_id')
                                ->where('tours_enquire.start_date','>=',$today_date)->where('tours_enquire.customer_id',$customer_id)
                                ->where('tours_enquire.categories',$cat_res->id)
                                ->where('tours_enquire.tour_feature',0)
                                ->select('tours_enquire.id','tours_enquire.title','tours_2_enquire.quad_grand_total_amount','tours_2_enquire.triple_grand_total_amount','tours_2_enquire.double_grand_total_amount','tours_enquire.tour_featured_image','tours_enquire.tour_banner_image',
                                'tours_enquire.time_duration','tours_enquire.content','tours_enquire.no_of_pax_days','tours_enquire.currency_symbol','tours_enquire.categories','tours_enquire.created_at')
                                
                                ->orderBy('tours_enquire.created_at', 'desc')->limit($request->limit)->get();
                                
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
            
                        array_push($category_tours,$tours);
                    }
                }
            return response()->json(['message'=>'success',
                                        'tours'=>$tours,
                                        'transfer_detail'=>$transfer_detail,
                                        'hotels'=>$hotels,
                                        'visa_types'=>$visa_types,
                                        'userData' => $userData,
                                        'top_cateogries' => $final_data,
                                        'all_cateogries' => $categories,
                                        'meta_data' => $meta_data,
                                        'category_tours' => $category_tours
                                        ]);
        }else{
            return response()->json([
                'message'   => 'error',
                'userData'  => $userData,
            ]);
        }
    }
    
    public function search_tours(Request $request){
        DB::beginTransaction();
        try {
            

            $start_date     = date("Y-m-d", strtotime($request->start_date));
            $userData       = CustomerSubcription::where('Auth_key',$request->token)->select('id','small_token','status')->first();
            $customer_id    = $userData->id;
            $tours          = DB::table('tours')
                                ->join('tours_2','tours.id','tours_2.tour_id')
                                ->where('categories','=',$request->category)
                                ->where('start_date','>=',$start_date)
                                ->where('customer_id','=',$customer_id)
                                ->where('tour_feature',0)
                                ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration',
                                'tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours_2.flights_details','tours_2.flight_route_type','tours_2.return_flights_details','tours.accomodation_details',
                                'tours.accomodation_details_more','tours.destination_details','tours.destination_details_more','tours.created_at')->orderBy('tours.created_at', 'desc')->get();
                                
            $tours_enquire          = DB::table('tours_enquire')
                                ->join('tours_2_enquire','tours_enquire.id','tours_2_enquire.tour_id')
                                ->where('categories','=',$request->category)
                                ->where('start_date','>=',$start_date)
                                ->where('customer_id','=',$customer_id)
                                ->where('tour_feature',0)
                                ->select('tours_enquire.id','tours_enquire.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours_enquire.tour_featured_image','tours_enquire.tour_banner_image','tours_enquire.time_duration',
                                'tours_enquire.content','tours_enquire.tour_location','tours_enquire.start_date','tours_enquire.end_date','tours_enquire.no_of_pax_days','tours_enquire.currency_symbol','tours_enquire.starts_rating','tours_2_enquire.flights_details','tours_2_enquire.flight_route_type','tours_2_enquire.return_flights_details','tours_enquire.accomodation_details',
                                'tours_enquire.accomodation_details_more','tours_enquire.destination_details','tours_enquire.destination_details_more','tours_enquire.created_at')->orderBy('tours_enquire.created_at', 'desc')->get();
            
            
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
            
            // print_r($mergedArray);
            // die;
            $all_active_providers = DB::table('3rd_party_commissions')
                                ->where('third_party_id',$customer_id)
                                ->where('status','approve')
                                ->get();
            //  dd($all_active_providers);
            // Other Providers Packages                   
            $providers_tours = [];
            foreach($all_active_providers as $active_pro_res){
                 $other_providers_tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                 ->where('customer_id',$active_pro_res->customer_id)
                 ->where('tour_feature',0)
                 ->where('others_providers_show', 'true')
                 ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration',
                        'tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours_2.flights_details','tours_2.flight_route_type','tours_2.return_flights_details','tours.accomodation_details',
                        'tours.accomodation_details_more','tours.destination_details','tours.destination_details_more')->orderBy('tours.created_at', 'desc')->get();
                
                
                $provider_data = CustomerSubcription::where('id',$active_pro_res->customer_id)->select('id','name','lname','webiste_Address','small_token')->first();
                $provider_arr = [$other_providers_tours,$provider_data];
                array_push($providers_tours,$provider_arr);
            }
            
            // Get All Categories
                $categories = DB::table('categories')->where('customer_id',$userData->id)->select('id','image','title','description')->orderBy('placement', 'asc')->get();

            // Get All Attributes
                $attributes = DB::table('attributes')->where('customer_id',$userData->id)->select('id','title')->get();

                
            return response()->json(['message'=>'success','tours'=>$tours,'other_providers_tours'=>$providers_tours,'small_token'=>$userData->small_token,'all_categories'=>$categories,'attributes' => $attributes]);
        } catch (\Exception $e) {
            echo $e;
            die;
            DB::rollback();
            $result = false;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function filter_tour(Request $request){
        //  echo "this is call now ";
        //  dd($request->all());
          $request_data = json_decode($request->request_data);
        //  echo "The min price is ".$request_data->min." max price is ".$request_data->max;
         
        
          $start_date = date("Y-m-d", strtotime($request_data->start));
          $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            if($userData->status == 1){
                
                if(isset($request_data->request_page)){
                    
                    if($request_data->category){
                       
                        $tours = DB::table('tours')
                        ->join('tours_2','tours.id','tours_2.tour_id')
                        ->where('tours.customer_id',$userData->id)
                        ->where('tours.categories',$request_data->category)
                        ->where('tours.start_date','>=',$start_date)
                        
                        ->Where(function($query) use($request_data) {
                            
                            $query->whereBetween('tours_2.quad_grand_total_amount',[$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.triple_grand_total_amount', [$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.double_grand_total_amount', [$request_data->min,$request_data->max]);
                          
                         })
                        ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours.tour_attributes','tours_2.flights_details','tours_2.flights_details_more')->orderBy('tours.created_at', 'desc')->get();
                        return response()->json(['message'=>'success','data'=>$tours]);
                    }else{
                        $tours = DB::table('tours')
                        ->join('tours_2','tours.id','tours_2.tour_id')
                        ->where('tours.customer_id',$userData->id)
                        ->where('tours.start_date','>=',$start_date)
                        
                        ->Where(function($query) use($request_data) {
                            
                             $query->whereBetween('tours_2.quad_grand_total_amount',[$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.triple_grand_total_amount', [$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.double_grand_total_amount', [$request_data->min,$request_data->max]);
                          
                         })
                        ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours.tour_attributes','tours_2.flights_details','tours_2.flights_details_more')->orderBy('tours.created_at', 'desc')->get();
                        return response()->json(['message'=>'success','data'=>$tours]);
                    }
                   
                }else{
                    
                    $tours = DB::table('tours')
                    ->join('tours_2','tours.id','tours_2.tour_id')
                    ->where('tours.customer_id',$userData->id)
                    ->where('tours.categories',$request_data->category)
                    ->where('tours.start_date','>=',$start_date)
                    
                    ->Where(function($query) use($request_data) {
                        
                        $query->whereBetween('tours_2.quad_grand_total_amount',[$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.triple_grand_total_amount', [$request_data->min,$request_data->max])
                            ->orwhereBetween('tours_2.double_grand_total_amount', [$request_data->min,$request_data->max]);
                      
                     })
                   ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours.tour_attributes','tours_2.flights_details','tours_2.flights_details_more')->orderBy('tours.created_at', 'desc')->get();
                    
                    // print_r($tours);
                    // die;
                    return response()->json(['message'=>'success','data'=>$tours]);
                }
                
                

            }
        }
        
     }
     
     public function get_package_detail(Request $request){
        DB::beginTransaction();
        try {
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','webiste_Address')->first();
            if($request->type == 'tour'){
                    $Agents_detail      = DB::table('Agents_detail')->where('customer_id',$userData->id)->get();
                    $customer_detail    = DB::table('booking_customers')->where('customer_id',$userData->id)->get();
                    $mange_currencies   = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
                    $all_visa_types     = DB::table('visa_types')->where('customer_id',$userData->id)->get();
                    
                if($request->provider_token == NULL){
                    $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$request->id)->select('tours_2.*','tours.*','tours_2.id as tour2id')->first();
                   
                }else{
                    $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                            ->where('tours.id',$request->id)
                            ->where('tours.others_providers_show', 'true')
                            ->select('tours_2.*','tours.*','tours_2.id as tour2id')->first();
                    $userData = CustomerSubcription::where('small_token',$request->provider_token)->select('id','status','webiste_Address')->first();
                }
            }
            if($request->type == 'activity'){
                $tours              =  DB::table('new_activites')->where('customer_id',$userData->id)->where('id',$request->id)->first();
                // dd($tours);
                $customer_detail    = DB::table('booking_customers')->where('customer_id',$userData->id)->get();
                $Agents_detail      = DB::table('Agents_detail')->where('customer_id',$userData->id)->get();
                $all_visa_types     = '';
                $mange_currencies   = '';
            }
            return response()->json(['message'=>'success','tours'=>$tours,'agents'=>$Agents_detail,'visa_types'=>$all_visa_types,'mange_currencies'=>$mange_currencies,'customer_details'=>$customer_detail,'userData'=>$userData]);    
            
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
    }
    
}
