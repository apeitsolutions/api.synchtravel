<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\ToursBooking;
use App\Models\Cart_details;
use DB;
use App\Models\country;

class ActivityController extends Controller
{
    public function Error_Authenticate(){
        $Error_Message = 'Permissions denied! Please contact Synch Travel Support.';
        return view('Error_Authenticate',compact('Error_Message'));
    }
    
    public function get_activities_list(Request $request){
        $customer_id    = $request->customer_id;
        $userData       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            if($userData->status == 1){
                $activities     = DB::table('new_activites')
                                    ->where('customer_id',$customer_id)
                                    ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','package_author','starts_rating')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                return response()->json([
                    'message'       => 'success',
                    'activities'    => $activities,
                ]);
            }else{
                return response()->json([
                    'message'       => 'error',
                ]);
            }
        }
    }
    
    public function submit_activity_cities(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $customer_id=$userData->id;
        if($userData){
            if($userData->status == 1){
                $categories=DB::table('activities_cities')->insert([
                    'title'=>$request->title,
                    'slug'=>$request->slug,
                    'image'=>$request->image,
                    'placement'=>$request->placement,
                    'description'=>$request->description,
                    'customer_id'=>$request->customer_id,
                ]);
                return response()->json(['message'=>'success']);
            }else{
                return response()->json(['message'=>'error']);
            }
        }
    }
  
    public function get_activities_list_wi_limit(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->where('status','1')->select('id','status')->first();
        $customer_id=$userData->id;
        if($userData){
            $activities=DB::table('new_activites')
                ->where('customer_id',$customer_id)
                ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','package_author','starts_rating')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
            
            $activities_cities=DB::table('activities_cities')
                ->where('customer_id',$customer_id)
                ->limit(8)
                ->get();
        
            return response()->json([
                'message'       => 'success',
                'activities'    => $activities,
                'activities_cities'    => $activities_cities,
            ]);
        }else{
            return response()->json([
                    'message'       => 'error',
                ]);
        }
    }
  
    public function get_activites_lists_wi_cities(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $customer_id=$userData->id;
        if($userData){
            if($userData->status == 1){
                $activities=DB::table('new_activites')
                ->where('customer_id',$customer_id)
                ->where('activities_city',$request->city_id)
                ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','package_author','starts_rating')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
        
            
                return response()->json([
                    'message'       => 'success',
                    'activities'    => $activities,
                ]);
            }else{
                return response()->json([
                    'message'       => 'error',
                ]);
            }
        }else{
            return response()->json([
                'message'       => 'error',
            ]);
        }
    }
  
    public function index_activities(Request $request){
        $userData       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $customer_id    = $userData->id;
        if($userData){
            if($userData->status == 1){
                $activities     = DB::table('new_activites')
                                    ->where('customer_id',$customer_id)
                                    ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','package_author','starts_rating')
                                    ->orderBy('created_at', 'desc')->limit(10)->get();
                return response()->json([
                    'message'       => 'success',
                    'activities'    => $activities,
                ]);
            }
        }else{
            return response()->json([
                'message'       => 'error',
            ]);
        }
    }
  
    public function save_activities(Request $request){
        // echo "print from admin ";
        
        $request_data = json_decode($request->request_data);
        // print_r($request_data);
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            if($userData->status == 1){
                $generate_id=rand(0,9999999);
              
                $complete_date = explode('-',$request_data->activtiy_dates);
    
                $start_date = explode('/',$complete_date[0]);
                $month = $start_date[0];
                $day = $start_date[1];
                $year = trim($start_date[2],' ');
                $end_date = explode('/',$complete_date[1]);
                $month_end = $end_date[0];
                $month_end = trim($month_end,' ');
                $day_end = $end_date[1];
                $year_end = $end_date[2];
    
                $start_date = "$year-$month-$day";
                $end_date = "$year_end-$month_end-$day_end";
                  if(isset($request_data->activities_cities)){
                        $activities_cities = $request_data->activities_cities;
                    }else{
                        $activities_cities = "";
                    }
                    
                     DB::beginTransaction();
                     try {
                         
                        $Id = DB::table('new_activites')->insertGetId([
                          
                             'generate_id' => $generate_id,
                             'title' => $request_data->title,
                             'country' => $request_data->activity_country,
                             'city' => $request_data->activity_city,
                             'activities_city' => $activities_cities,
                             'currency_symbol' => $request_data->currency_symbol,
                             'duration' => $request_data->duration,
                             'location' => $request_data->location,
                             'activity_content' => $request_data->activity_content,
                             'categories' => $request_data->categories,
                             'tour_attributes' => $request_data->tour_attributes,
                             'min_people' => $request_data->min_people,
                             'max_people' => $request_data->max_people,
                             'video_link' => $request_data->video_link,
                             'package_feature' => $request_data->package_feature,
                             'package_author' => $request_data->package_author,
                             'publish_status' => $request_data->publish_status,
                             'Availibilty' => $request_data->Availibilty,
                             'whats_included' => $request_data->whats_included,
                             'whats_excluded' => $request_data->whats_excluded,
                             'cost_price' => $request_data->cost_price,
                             'sale_price' => $request_data->sale_price,
                             'child_cost_price' => $request_data->child_cost_price,
                             'child_sale_price' => $request_data->child_sale_price,
                             'featured_image' => $request_data->featured_image,
                             'banner_image' => $request_data->banner_image,
                             'what_expect' => $request_data->expect_arr,
                             'faqs_arr' => $request_data->faqs_arr,
                             'addtional_service_arr' => $request_data->addtional_service_arr,
                             'gallery_images' => $request_data->gallery_images,
                             'activity_date' => $request_data->activtiy_dates,
                             'checkout_message' => $request_data->checkout_message,
                             'cancellation_policy' => $request_data->cancellation_policy,
                             'meeting_and_pickups' => $request_data->meeting_and_pickups,
                             'payment_getway' => $request_data->payment_getway,
                             'payment_modes' => $request_data->payment_modes,
                             'starts_rating' => $request_data->stars_rating,
                             'start_date' => $start_date,
                             'end_date' => $end_date,
                             'customer_id' => $userData->id,
                          ]);
                          
                        if($Id){
                           
                                if(isset($request_data->activities_cities)){
                                $activities_cities = $request_data->activities_cities;
                                    }else{
                                  $activities_cities = "";
                              }
                               $data = DB::table('new_activites_batches')->insert([
                              
                                 'generate_id' => $generate_id,
                                 'title' => $request_data->title,
                                 'country' => $request_data->activity_country,
                                 'city' => $request_data->activity_city,
                                 'activities_city' => $activities_cities,
                                 'currency_symbol' => $request_data->currency_symbol,
                                 'duration' => $request_data->duration,
                                 'location' => $request_data->location,
                                 'activity_content' => $request_data->activity_content,
                                 'categories' => $request_data->categories,
                                 'tour_attributes' => $request_data->tour_attributes,
                                 'min_people' => $request_data->min_people,
                                 'max_people' => $request_data->max_people,
                                 'video_link' => $request_data->video_link,
                                 'package_feature' => $request_data->package_feature,
                                 'package_author' => $request_data->package_author,
                                 'publish_status' => $request_data->publish_status,
                                 'Availibilty' => $request_data->Availibilty,
                                 'whats_included' => $request_data->whats_included,
                                 'whats_excluded' => $request_data->whats_excluded,
                                 'cost_price' => $request_data->cost_price,
                                 'sale_price' => $request_data->sale_price,
                                 'child_cost_price' => $request_data->child_cost_price,
                                 'child_sale_price' => $request_data->child_sale_price,
                                 'featured_image' => $request_data->featured_image,
                                 'banner_image' => $request_data->banner_image,
                                 'what_expect' => $request_data->expect_arr,
                                 'faqs_arr' => $request_data->faqs_arr,
                                 'addtional_service_arr' => $request_data->addtional_service_arr,
                                 'gallery_images' => $request_data->gallery_images,
                                 'activity_date' => $request_data->activtiy_dates,
                                 'checkout_message' => $request_data->checkout_message,
                                 'meeting_and_pickups' => $request_data->meeting_and_pickups,
                                 'cancellation_policy' => $request_data->cancellation_policy,
                                 'payment_getway' => $request_data->payment_getway,
                                 'payment_modes' => $request_data->payment_modes,
                                 'customer_id' => $userData->id,
                                 'activity_id' => $Id,
                                 
                              ]);
                          
                            $record_found = DB::table('country_activities_count')
                              ->where('country_id', $request_data->activity_country)
                              ->where('customer_id', $userData->id)
                              ->exists();
                          
                            if($record_found){
                                $record_found = DB::table('country_activities_count')
                              ->where('country_id', $request_data->activity_country)
                              ->where('customer_id', $userData->id)
                              ->increment('activity_count', 1);
                            }else{
                                $data = DB::table('country_activities_count')->insert([
                                 'country_id' => $request_data->activity_country,
                                 'customer_id' => $userData->id,
                                 'activity_count' => 1,
                                    'country_img' => '',
                                ]);
                            }
    
                          return response()->json(['message'=>'success']);
                        }
                        DB::commit();  
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['message'=>'error','booking_id'=> '']);
                        }
                    
                   
            }else{
                return response()->json(['message'=>'falid']);
            }
        }else{
            return response()->json(['message'=>'falid']);
        }
    }
    
    public function update_activities(Request $request){
           $request_data = json_decode($request->request_data);
        
            $userData = CustomerSubcription::where('Auth_key',$request->token)->where('status','1')->select('id','status')->first();
            // dd($userData);
            if($userData){
                 
                    $complete_date = explode('-',$request_data->activtiy_dates);
        
                    $start_date = explode('/',$complete_date[0]);
                    $month = $start_date[0];
                    $day = $start_date[1];
                    $year = trim($start_date[2],' ');
                    $end_date = explode('/',$complete_date[1]);
                    $month_end = $end_date[0];
                    $month_end = trim($month_end,' ');
                    $day_end = $end_date[1];
                    $year_end = $end_date[2];
        
                     $start_date = "$year-$month-$day";
                    $end_date = "$year_end-$month_end-$day_end";
                  
                    $generate_id=rand(0,9999999);
                    if($request_data->package_update_type == 'new'){
                        $generate_id = $generate_id;
                    }
                    else{
                        $generate_id = $request_data->prev_generate_code;
                    }
                    
                    if(isset($request_data->activities_cities)){
                        $activities_cities = $request_data->activities_cities;
                    }
                    else{
                      $activities_cities = "";
                  }
                  DB::beginTransaction();
                     try {
                            $result = DB::table('new_activites')
                                      ->where('id', $request_data->id)
                                      ->update([
                                             'title' => $request_data->title,
                                             'generate_id' => $generate_id,
                                             'country' => $request_data->activity_country,
                                             'city' => $request_data->activity_city,
                                             'activities_city' => $activities_cities,
                                             'currency_symbol' => $request_data->currency_symbol,
                                             'duration' => $request_data->duration,
                                             'location' => $request_data->location,
                                             'activity_content' => $request_data->activity_content,
                                             'categories' => $request_data->categories,
                                             'tour_attributes' => $request_data->tour_attributes,
                                             'min_people' => $request_data->min_people,
                                             'max_people' => $request_data->max_people,
                                             'video_link' => $request_data->video_link,
                                             'package_feature' => $request_data->package_feature,
                                             'package_author' => $request_data->package_author,
                                             'publish_status' => $request_data->publish_status,
                                             'Availibilty' => $request_data->Availibilty,
                                             'whats_included' => $request_data->whats_included,
                                             'whats_excluded' => $request_data->whats_excluded,
                                             'cost_price' => $request_data->cost_price,
                                             'sale_price' => $request_data->sale_price,
                                             'child_cost_price' => $request_data->child_cost_price,
                                             'child_sale_price' => $request_data->child_sale_price,
                                             'featured_image' => $request_data->featured_image,
                                             'banner_image' => $request_data->banner_image,
                                             'what_expect' => $request_data->expect_arr,
                                             'faqs_arr' => $request_data->faqs_arr,
                                             'addtional_service_arr' => $request_data->addtional_service_arr,
                                             'gallery_images' => $request_data->gallery_images,
                                             'activity_date' => $request_data->activtiy_dates,
                                             'checkout_message' => $request_data->checkout_message,
                                             'cancellation_policy' => $request_data->cancellation_policy,
                                             'meeting_and_pickups' => $request_data->meeting_and_pickups,
                                             'payment_getway' => $request_data->payment_getway,
                                             'payment_modes' => $request_data->payment_modes,
                                             'starts_rating' => $request_data->stars_rating,
                                             'start_date' => $start_date,
                                             'end_date' => $end_date,
                                             'customer_id' => $userData->id,
                                          ]);
                          
                               
                                if($request_data->package_update_type == 'old'){
                                     if(isset($request_data->activities_cities)){
                                             $activities_cities = $request_data->activities_cities;
                                          }else{
                                              $activities_cities = "";
                                          }
                                    $data = DB::table('new_activites_batches')
                                      ->where('generate_id',$request_data->prev_generate_code)
                                      ->update([
                                     'title' => $request_data->title,
                                     'country' => $request_data->activity_country,
                                      'city' => $request_data->activity_city,
                                      'activities_city' => $activities_cities,
                                     'currency_symbol' => $request_data->currency_symbol,
                                     'duration' => $request_data->duration,
                                     'location' => $request_data->location,
                                     'activity_content' => $request_data->activity_content,
                                     'categories' => $request_data->categories,
                                     'tour_attributes' => $request_data->tour_attributes,
                                     'min_people' => $request_data->min_people,
                                     'max_people' => $request_data->max_people,
                                     'video_link' => $request_data->video_link,
                                     'package_feature' => $request_data->package_feature,
                                     'package_author' => $request_data->package_author,
                                     'publish_status' => $request_data->publish_status,
                                     'Availibilty' => $request_data->Availibilty,
                                     'whats_included' => $request_data->whats_included,
                                     'whats_excluded' => $request_data->whats_excluded,
                                     'cost_price' => $request_data->cost_price,
                                     'sale_price' => $request_data->sale_price,
                                     'child_cost_price' => $request_data->child_cost_price,
                                     'child_sale_price' => $request_data->child_sale_price,
                                     'featured_image' => $request_data->featured_image,
                                     'banner_image' => $request_data->banner_image,
                                     'what_expect' => $request_data->expect_arr,
                                     'faqs_arr' => $request_data->faqs_arr,
                                     'addtional_service_arr' => $request_data->addtional_service_arr,
                                     'gallery_images' => $request_data->gallery_images,
                                     'activity_date' => $request_data->activtiy_dates,
                                     'checkout_message' => $request_data->checkout_message,
                                     'meeting_and_pickups' => $request_data->meeting_and_pickups,
                                     'cancellation_policy' => $request_data->cancellation_policy,
                                     'payment_getway' => $request_data->payment_getway,
                                     'payment_modes' => $request_data->payment_modes,
                                     'customer_id' => $userData->id,
                                  ]);
                                }else{
                                    $data = DB::table('new_activites_batches')
                                      ->insert([
                                     'title' => $request_data->title,
                                     'generate_id' => $generate_id,
                                     'country' => $request_data->activity_country,
                                      'city' => $request_data->activity_city,
                                      'activities_city' => $request_data->activities_cities,
                                     'currency_symbol' => $request_data->currency_symbol,
                                     'duration' => $request_data->duration,
                                     'location' => $request_data->location,
                                     'activity_content' => $request_data->activity_content,
                                     'categories' => $request_data->categories,
                                     'tour_attributes' => $request_data->tour_attributes,
                                     'min_people' => $request_data->min_people,
                                     'max_people' => $request_data->max_people,
                                     'video_link' => $request_data->video_link,
                                     'package_feature' => $request_data->package_feature,
                                     'package_author' => $request_data->package_author,
                                     'publish_status' => $request_data->publish_status,
                                     'Availibilty' => $request_data->Availibilty,
                                     'whats_included' => $request_data->whats_included,
                                     'whats_excluded' => $request_data->whats_excluded,
                                     'cost_price' => $request_data->cost_price,
                                     'sale_price' => $request_data->sale_price,
                                     'child_cost_price' => $request_data->child_cost_price,
                                     'child_sale_price' => $request_data->child_sale_price,
                                     'featured_image' => $request_data->featured_image,
                                     'banner_image' => $request_data->banner_image,
                                     'what_expect' => $request_data->expect_arr,
                                     'faqs_arr' => $request_data->faqs_arr,
                                     'addtional_service_arr' => $request_data->addtional_service_arr,
                                     'gallery_images' => $request_data->gallery_images,
                                     'activity_date' => $request_data->activtiy_dates,
                                     'checkout_message' => $request_data->checkout_message,
                                     'meeting_and_pickups' => $request_data->meeting_and_pickups,
                                     'cancellation_policy' => $request_data->cancellation_policy,
                                     'payment_getway' => $request_data->payment_getway,
                                     'payment_modes' => $request_data->payment_modes,
                                     'customer_id' => $userData->id,
                                     'activity_id' => $request_data->id,
                                      ]);
                                }
                              
                                if($request_data->activity_country !== $request_data->activity_prev_country){
                                    // country::where('id', $request_data->activity_prev_country)->decrement('activites_count', 1);
                                    // country::where('id', $request_data->activity_country)->increment('activites_count', 1);
                                    
                                     $record_found = DB::table('country_activities_count')
                                      ->where('country_id', $request_data->activity_country)
                                      ->where('customer_id', $userData->id)
                                      ->exists();
                                      
                                      if($record_found){
                                            $record_found = DB::table('country_activities_count')
                                          ->where('country_id', $request_data->activity_country)
                                          ->where('customer_id', $userData->id)
                                          ->increment('activity_count', 1);
                                          
                                           $record_found = DB::table('country_activities_count')
                                          ->where('country_id', $request_data->activity_prev_country)
                                          ->where('customer_id', $userData->id)
                                          ->decrement('activity_count', 1);
                                      }else{
                                          $data = DB::table('country_activities_count')->insert([
                                         'country_id' => $request_data->activity_country,
                                         'customer_id' => $userData->id,
                                         'activity_count' => 1,
                                         'country_img' => '',
                                         ]);
                                      }
                              
        
                              }
                               DB::commit();  
                               return response()->json(['message'=>'success']);
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['message'=>'error']);
                        }
                        
                  
            }else{
                return response()->json(['message'=>'error']);
            }
        
        
    }
    
    // Book Activity
    public function book_activity(Request $request){
        DB::beginTransaction();
        try {
            $activities     = DB::table('new_activites')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            return response()->json([
                'message'           => 'success',
                'activities'        => $activities,
            ]);
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error','activities'=>'']);
        }
    }
    
    public function get_tour_for_cart_ActivityAdmin(Request $request){
        DB::beginTransaction();
        try {
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            if($userData){
                if($userData->status == 1){
                    $tours = DB::table('new_activites')->where('id',$request->id)->first();
                    return response()->json(['message'=>'success','tours'=>$tours]);
                }else{
                    return response()->json(['message'=>'failed']);
                }
            }else{
                return response()->json(['message'=>'failed']);
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'failed']);
        }
    }
    
    public function submit_booking_ActivityAdmin(Request $request){
        
        $currency_slc=$request->currency_slc;
            
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
        
        function getBetweenDates($startDate, $endDate){
            $rangArray = [];
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
                 
                 $startDate += (86400); 
            for ($currentDate = $startDate; $currentDate <= $endDate; 
                                            $currentDate += (86400)) {
                                                    
                $date = date('Y-m-d', $currentDate);
                $rangArray[] = $date;
            }
      
            return $rangArray;
        }
            
        $request_data = json_decode($request->request_data);
        
        $cart_data = json_decode($request->cart_data);
        // dd($cart_data);
        
        $request_data = json_decode($request->request_data);

        $name = $request_data[0]->name." ".$request_data[0]->lname;
        $email = $request_data[0]->email;
        
        $cart_data_main = json_decode($request->cart_data);
        
        $cart_data = $cart_data_main[0];
             
        if($cart_data_main[1] == 'tour'){
            foreach($cart_data as $cart_res){
                if(isset($cart_res->provider_id)){
                    $provider_id = $cart_res->provider_id;
                }else{
                    $provider_id = 'test';
                }
            }
        }else{
             $provider_id = 'test';
         }
        
        if(isset($cart_res->provider_id)){
            if($cart_res->provider_id == null){
                $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            }else{
                $userData = CustomerSubcription::where('small_token',$cart_res->provider_id)->select('id','status')->first();
            }
        }else{
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        }
        
        $randomNumber   = random_int(1000000, 9999999);
        $invoiceId      =  "AHT".$randomNumber;
       
        $tourObj = new ToursBooking;

        $tourObj->passenger_name = $name;
        $tourObj->email = $email;
        $tourObj->cart_details = '';
        $tourObj->passenger_detail = $request->request_data;
        $tourObj->adults_detail = $request->adults;
        $tourObj->child_detail = $request->childs;
        $tourObj->infant_details = $request->infants;
        $tourObj->customer_id = $userData->id;
        $tourObj->parent_token = $request->token;
        $tourObj->invoice_no = $invoiceId;
        $tourObj->booking_person = $request->booking_person;
        $tourObj->exchange_currency = $currency_slc;
        
        $tourObj->provider_id = $provider_id;

        DB::beginTransaction();
        
        try {
            $tourData = $tourObj->save();
            
            $booking_id = $tourObj->id;
            $cart_data_main = json_decode($request->cart_data);
            $cart_data = $cart_data_main[0];
             
            if($cart_data_main[1] == 'tour'){
                foreach($cart_data as $cart_res){
                
                    $addtional_services = [];
                    if($cart_res->Additional_services_names != ''){
                        $service = [
                            'service'=>$cart_res->Additional_services_names,
                            'service_price'=>$cart_res->services_price,
                            'service_type'=>'',
                            'person'=>$cart_res->services_persons,
                            'dates'=>$cart_res->service_dates,
                            'Service_Days'=>$cart_res->service_day,
                            'total_price'=>$cart_res->service_day * $cart_res->services_price * $cart_res->services_persons,
                            ];
                            
                            array_push($addtional_services,$service);
                    }
                    
                    if($cart_res->Additional_services_names_more !== 'null' AND $cart_res->Additional_services_names_more !== ''){
                        $services_names = json_decode($cart_res->Additional_services_names_more);
                        $services_persons_more = json_decode($cart_res->services_persons_more);
                        $services_price_more = json_decode($cart_res->services_price_more);
                        $services_days_more = json_decode($cart_res->services_days_more);
                        $services_dates_more = json_decode($cart_res->services_dates_more);
                        
                        $z = 0;
                        foreach($services_names as $service_res){
                             $service = [
                            'service'=>$service_res,
                            'service_price'=> $services_price_more[$z],
                            'service_type'=>'',
                            'person'=>$services_persons_more[$z],
                            'dates'=>$services_dates_more[$z],
                            'Service_Days'=>$services_days_more[$z],
                            'total_price'=>$services_days_more[$z] * $services_price_more[$z] * $services_persons_more[$z],
                            ];
                            
                            array_push($addtional_services,$service);
                        }
                    }
                    
                    if(isset($cart_res->Additional_services_Awaab)){
                        $addtional_services = $cart_res->Additional_services_Awaab;
                    }else{
                        $addtional_services = json_encode($addtional_services);
                    }
                    
                    if($request->cart_visa != "null"){
                        
                    $cart_visa_data = json_decode($request->cart_visa);
                    $cart_visa_data_save = '';
                    foreach($cart_visa_data as $visa_res){
                        $cart_visa_data_save = $visa_res;
                    }
                    }else{
                        $cart_visa_data_save = "";
                    }
                    
                    
                    $prvoider_id = 0;
                    if(isset($cart_res->provider_id)){
                        $prvoider_id = $cart_res->provider_id;
                    }
                    
                    $Cart_details = new Cart_details;
                    
                    $Cart_details->tour_id = $cart_res->tourId;
                    $Cart_details->provider_id = $prvoider_id;
                    $Cart_details->cart_total_data = json_encode($cart_res);
                    $Cart_details->visa_change_data = json_encode($cart_visa_data_save);
                    
                    $Cart_details->generate_id = $cart_res->generate_id;
                    $Cart_details->tour_name = $cart_res->name;
                    $Cart_details->adults = $cart_res->adults;
                    $Cart_details->childs = $cart_res->children;
                    $Cart_details->sigle_price = $cart_res->sigle_price;
                    $Cart_details->tour_total_price = $cart_res->tour_total_price;
                    $Cart_details->total_service_price = $cart_res->total_service_price;
                    $Cart_details->price = $cart_res->price;
                    
                    $Cart_details->sharing2 = $cart_res->sharing2;
                    $Cart_details->sharing3 = $cart_res->sharing3;
                    $Cart_details->sharing4 = $cart_res->sharing4;
                    $Cart_details->sharingSelect = $cart_res->sharingSelect;
                    $Cart_details->image = $cart_res->image;
                    $Cart_details->booking_id = $booking_id;
                    $Cart_details->invoice_no = $invoiceId;
                    $Cart_details->currency = $cart_res->currency;
                    $Cart_details->pakage_type = $cart_res->pakage_type;
                    
                    $Cart_details->Additional_services_names = $addtional_services;
                 
                 if(isset($cart_res->child_price)){
                     $child_price = $cart_res->child_price;
                     $cost_price_child = $cart_res->cost_price_child;
                     $adult_total_price = $cart_res->adult_total_price;
                     $child_total_price = $cart_res->child_total_price;
                     $Cart_details->childs = 0;
                 }else{
                     $child_price = 0;
                     $cost_price_child = 0;
                     $adult_total_price = 0;
                     $child_total_price = 0;
                 }
                 $Cart_details->child_price_tour = $child_price;
                 $Cart_details->child_cost_price = $cost_price_child;
                 $Cart_details->adult_total_price = $adult_total_price;
                 $Cart_details->child_total_price = $child_total_price;
                 
                 
                
                 if(isset($cart_res->double_adults)){
                     $Cart_details->double_adults = $cart_res->double_adults;
                 }
                 
                 if(isset($cart_res->triple_adults)){
                     $Cart_details->triple_adults = $cart_res->triple_adults;
                 }
                 
                 if(isset($cart_res->quad_adults)){
                     $Cart_details->quad_adults = $cart_res->quad_adults;
                 }
                 
                  if(isset($cart_res->without_acc_adults)){
                     $Cart_details->without_acc_adults = $cart_res->without_acc_adults;
                 }
                 
                  if(isset($cart_res->double_rooms)){
                     $Cart_details->double_room = $cart_res->double_rooms;
                 }
                 
                  if(isset($cart_res->triple_rooms)){
                     $Cart_details->triple_room = $cart_res->triple_rooms;
                 }
                 
                  if(isset($cart_res->quad_rooms)){
                     $Cart_details->quad_room = $cart_res->quad_rooms;
                 }
                 
                  if(isset($cart_res->without_acc_sale_price)){
                     $Cart_details->without_acc_sale_price = $cart_res->without_acc_sale_price;
                 }
                 
                 
                 if(isset($cart_res->agent_name)){
                     $Cart_details->agent_name = $cart_res->agent_name;
                 }
                    
                    $Cart_details->client_id = $userData->id;
                    // dd($request->flight_id);
                    if(isset($request->flight_id) && $request->flight_id != null && $request->flight_id != ''){
                         $Cart_details->flight_id = $request->flight_id;
                    }else{
                        $Cart_details->flight_id = '';
                    }
                    
                    $Cart_data = $Cart_details->save();
                    
                    if($cart_res->agent_name != '-1'){
                            $agent_data = DB::table('Agents_detail')->where('id',$cart_res->agent_name)->select('id','balance')->first();
               
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $agent_balance = $agent_data->balance + $cart_res->tour_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$cart_res->tour_total_price,
                                        'balance'=>$agent_balance,
                                        'package_invoice_no'=>$invoiceId,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        ]);
                                        
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                }
                        }
                        
                        if(isset($cart_res->customer_id))
                        {
                        if($cart_res->customer_id != '-1'){
                            $customer_data = DB::table('booking_customers')->where('id',$cart_res->customer_id)->select('id','balance')->first();
               
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $customer_balance = $customer_data->balance + $cart_res->tour_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$cart_res->tour_total_price,
                                        'balance'=>$customer_balance,
                                        'package_invoice_no'=>$invoiceId,
                                        'customer_id'=>$userData->id,
                                        'date'=>date('Y-m-d'),
                                        ]);
                                        
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        }
                        }
                    // print_r($cart_res);
                    
                    
                    $tours_data = DB::table('tours')->where('id',$cart_res->tourId)->select('accomodation_details','accomodation_details_more')->first();
                    $accomodation = json_decode($tours_data->accomodation_details);
                    $more_accomodation_details = json_decode($tours_data->accomodation_details_more);
                    
                    // print_r($accomodation);
                    // print_r($more_accomodation_details);
                    // die;
                    
                     if(isset($accomodation)){
                         foreach($accomodation as $accomodation_res){
                             if(isset($accomodation_res->hotelRoom_type_idM)){
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                            
                               if($room_data){
                                
                                        $rooms_qty = 0;
                                          if(isset($cart_res->double_rooms)){
                                                if($accomodation_res->acc_type == 'Double'){
                                                     $rooms_qty = $cart_res->double_rooms;
                                                }
                                                 
                                         }
                                         
                                          if(isset($cart_res->triple_rooms)){
                                                if($accomodation_res->acc_type == 'Triple'){
                                                     $rooms_qty = $cart_res->triple_rooms;
                                                }
                                         }
                                         
                                          if(isset($cart_res->quad_rooms)){
                                              if($accomodation_res->acc_type == 'Quad'){
                                                     $rooms_qty = $cart_res->quad_rooms;
                                                }
                                         }
                                         
                                         $total_booked = $room_data->booked + $rooms_qty;
                               
                                
                                         
                                         DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $accomodation_res->hotelRoom_type_id,
                                             'booking_from'=>'package',
                                             'quantity'=>$rooms_qty,
                                             'booking_id'=>$invoiceId,
                                             'package_id'=>$cart_res->tourId,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->acc_check_in,
                                             'check_out'=>$accomodation_res->acc_check_out,
                                         ]);
                                         
                               
                                        DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                        
                                        // Update Hotel Supplier Balance
                                
                                         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
               
                                         if(isset($supplier_data)){
                                                // echo "Enter hre ";
                                                
                                                     $week_days_total = 0;
                                                     $week_end_days_totals = 0;
                                                     $total_price = 0;
                                                     $accomodation_res->acc_check_in = date('Y-m-d',strtotime($accomodation_res->acc_check_in));
                                                     $accomodation_res->acc_check_out = date('Y-m-d',strtotime($accomodation_res->acc_check_out));
                                                    if($room_data->price_week_type == 'for_all_days'){
                                                        $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                        $total_price = $room_data->price_all_days * $avaiable_days;
                                                    }else{
                                                         $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                         
                                                         $all_days = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                         
                                                        //  print_r($all_days);
                                                         $week_days = json_decode($room_data->weekdays);
                                                         $week_end_days = json_decode($room_data->weekends);
                                                         
                                                         foreach($all_days as $day_res){
                                                             $day = date('l', strtotime($day_res));
                                                             $day = trim($day);
                                                             $week_day_found = false;
                                                             $week_end_day_found = false;
                                                            
                                                             foreach($week_days as $week_day_res){
                                                                 if($week_day_res == $day){
                                                                     $week_day_found = true;
                                                                 }
                                                             }
                                                      
                                                            // echo "  ".$room_data->weekdays_price;
                                                             if($week_day_found){
                                                                 $week_days_total += $room_data->weekdays_price;
                                                             }else{
                                                                 $week_end_days_totals += $room_data->weekends_price;
                                                             }
                                                             
                                                             
                                                            //  foreach($week_end_days as $week_day_res){
                                                            //      if($week_day_res == $day){
                                                            //          $week_end_day_found = true;
                                                            //      }
                                                            //  }
                                                            //   if($week_end_day_found){
                                                                  
                                                            //  }
                                                         }
                                                         
                                                         
                                                        //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                         
                                                        //  print_r($all_days);
                                                         $total_price = $week_days_total + $week_end_days_totals;
                                                    }
                                                    
                                                    
                                                $all_days_price = $total_price * $rooms_qty;
                                                
                                                // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                                // die;
                                                
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$all_days_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$userData->id,
                                                    'date'=>date('Y-m-d'),
                                                    'package_invoice_no'=>$Cart_details->invoice_no,
                                                    'available_from'=>$accomodation_res->acc_check_in,
                                                    'available_to'=>$accomodation_res->acc_check_out,
                                                    'room_quantity'=>$rooms_qty,
                                                    ]);
                                                    
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                    'balance'=>$supplier_balance,
                                                    'payable'=>$supplier_payable_balance
                                                    ]);
                                                
                                                
                                                  
                                                                            
                                            }
                            
                               }
                             }
                          
                         }
                        
                    }
                    
                    if(isset($more_accomodation_details)){
                        // print_r($more_accomodation_details);
                        
                         foreach($more_accomodation_details as $accomodation_res){
                             if(isset($accomodation_res->more_hotelRoom_type_idM)){
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                                
                                // print_r($room_data);
                                if($room_data){
                                
                                        // $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                        
                                        
                                        
                                        
                                          $rooms_qty = 0;
                                              if(isset($cart_res->double_rooms)){
                                                        if($accomodation_res->more_acc_type == 'Double'){
                                                             $rooms_qty = $cart_res->double_rooms;
                                                        }
                                                         
                                                 }
                                                 
                                                  if(isset($cart_res->triple_rooms)){
                                                        if($accomodation_res->more_acc_type == 'Triple'){
                                                             $rooms_qty = $cart_res->triple_rooms;
                                                        }
                                                 }
                                                 
                                                  if(isset($cart_res->quad_rooms)){
                                                      if($accomodation_res->more_acc_type == 'Quad'){
                                                             $rooms_qty = $cart_res->quad_rooms;
                                                        }
                                                 }
                                                 
                                                 $total_booked = $room_data->booked + $rooms_qty;
                                       
                                        
                                        
                                                 
                                                 DB::table('rooms_bookings_details')->insert([
                                                     'room_id'=> $accomodation_res->more_hotelRoom_type_id,
                                                     'booking_from'=>'package',
                                                     'quantity'=>$rooms_qty,
                                                     'booking_id'=>$invoiceId,
                                                     'package_id'=>$cart_res->tourId,
                                                     'date'=>date('Y-m-d'),
                                                     'check_in'=>$accomodation_res->more_acc_check_in,
                                                     'check_out'=>$accomodation_res->more_acc_check_out,
                                                 ]);
                                                 
                                      
                                        DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                        
                                        // Update Hotel Supplier Balance
                                
                                         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
               
                                         if(isset($supplier_data)){
                                                // echo "Enter hre ";
                                                
                                                     $week_days_total = 0;
                                                     $week_end_days_totals = 0;
                                                     $total_price = 0;
                                                     $accomodation_res->more_acc_check_in = date('Y-m-d',strtotime($accomodation_res->more_acc_check_in));
                                                     $accomodation_res->more_acc_check_out = date('Y-m-d',strtotime($accomodation_res->more_acc_check_out));
                                                    if($room_data->price_week_type == 'for_all_days'){
                                                        $avaiable_days = dateDiffInDays($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                        $total_price = $room_data->price_all_days * $avaiable_days;
                                                    }else{
                                                         $avaiable_days = dateDiffInDays($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                         
                                                         $all_days = getBetweenDates($accomodation_res->more_acc_check_in, $accomodation_res->more_acc_check_out);
                                                         
                                                        //  print_r($all_days);
                                                         $week_days = json_decode($room_data->weekdays);
                                                         $week_end_days = json_decode($room_data->weekends);
                                                         
                                                         foreach($all_days as $day_res){
                                                             $day = date('l', strtotime($day_res));
                                                             $day = trim($day);
                                                             $week_day_found = false;
                                                             $week_end_day_found = false;
                                                            
                                                             foreach($week_days as $week_day_res){
                                                                 if($week_day_res == $day){
                                                                     $week_day_found = true;
                                                                 }
                                                             }
                                                      
                                                            // echo "  ".$room_data->weekdays_price;
                                                             if($week_day_found){
                                                                 $week_days_total += $room_data->weekdays_price;
                                                             }else{
                                                                 $week_end_days_totals += $room_data->weekends_price;
                                                             }
                                                             
                                                             
                                                            //  foreach($week_end_days as $week_day_res){
                                                            //      if($week_day_res == $day){
                                                            //          $week_end_day_found = true;
                                                            //      }
                                                            //  }
                                                            //   if($week_end_day_found){
                                                                  
                                                            //  }
                                                         }
                                                         
                                                         
                                                        //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                         
                                                        //  print_r($all_days);
                                                         $total_price = $week_days_total + $week_end_days_totals;
                                                    }
                                                    
                                                    
                                                $all_days_price = $total_price * $rooms_qty;
                                                
                                                // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                                // die;
                                                
                                                // echo "The supplier Balance is ".$supplier_data->balance;
                                                $supplier_balance = $supplier_data->balance;
                                                $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$all_days_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$userData->id,
                                                    'date'=>date('Y-m-d'),
                                                    'package_invoice_no'=>$Cart_details->invoice_no,
                                                    'available_from'=>$accomodation_res->more_acc_check_in,
                                                    'available_to'=>$accomodation_res->more_acc_check_out,
                                                    'room_quantity'=>$rooms_qty,
                                                    ]);
                                                    
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                    'balance'=>$supplier_balance,
                                                    'payable'=>$supplier_payable_balance
                                                    ]);
                                                
                                                
                                                  
                                                                            
                                            }
                            
                                }
                             }  
                         }
                    
                    }
                    
                    
                    // dd($Cart_data);
                    
                    $tours_data = DB::table('tours_2')->where('tour_id',$cart_res->tourId)->select('flight_supplier','flight_route_id_occupied','flights_per_person_price')->first();
                    
                    $flight_data = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                 
                    if(isset($flight_data)){
                         
                                // Update Flight Supplier Balance
                                
                                $supplier_data = DB::table('supplier')->where('id',$flight_data->dep_supplier)->first();
                                                  
                                //  Calculate Child Price
                                
                                $price_diff = 0;
                                if($cart_res->total_childs > 0){
                                    $child_price_wi_adult_price = $flight_data->flights_per_person_price * $cart_res->total_childs;
                                    $child_price_wi_child_price = $flight_data->flights_per_child_price * $cart_res->total_childs;
                                    $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                                }
                                
                                $infant_price = 0;
                                if($cart_res->total_Infants > 0){
                                    $infant_price = $flight_data->flights_per_infant_price * $cart_res->total_Infants;
                                }
                                
                                
                                
                                if($price_diff != 0 || $infant_price != 0){
                            
                                    $supplier_balance = $supplier_data->balance - $price_diff;
                                    
                                    $supplier_balance = $supplier_balance + $infant_price;
                                    $total_differ = $infant_price - $price_diff;
                                    
                                    DB::table('flight_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$total_differ,
                                                'balance'=>$supplier_balance,
                                                'route_id'=>$flight_data->id,
                                                'date'=>date('Y-m-d'),
                                                'customer_id'=>$userData->id,
                                                'adult_price'=>$flight_data->flights_per_person_price,
                                                'child_price'=>$flight_data->flights_per_child_price,
                                                'infant_price'=>$flight_data->flights_per_infant_price,
                                                'adult_seats_booked'=>$cart_res->total_adults,
                                                'child_seats_booked'=>$cart_res->total_childs,
                                                'infant_seats_booked'=>$cart_res->total_Infants,
                                                'package_invoice_no'=>$Cart_details->invoice_no,
                                                'remarks'=>'Package Booked',
                                              ]);
                                              
                                    DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                }
                                
                                
                            
                        
                        
                    }
                    
                    // Update 3rd Party package Booking Commission
                    
                    if(isset($cart_res->provider_id)){
                        if($cart_res->provider_id != null){
                        //  Book From Other Website
                        //  $thirdPartyData = CustomerSubcription::where('small_token',$cart_res->provider_id)->select('id','small_token','status')->first();
                        //  if(isset($thirdPartyData)){
                        //      $thirdPartyBalData = DB::table('3rd_party_commissions')
                        //                             ->where('customer_id',$userData->id)
                        //                             ->where('third_party_id',$thirdPartyData->id)
                        //                             ->first();
                        //      // 1 Calculate Commission
                             
                        //      // Check Type of Commission
                        //         if($thirdPartyBalData->commission_type == '%'){
                        //             $commisson_amount    = ($cart_res->tour_total_price * $thirdPartyBalData->commission) / 100;
                        //         }else{
                        //             $commisson_amount = $thirdPartyBalData->commission;
                        //         }
                                
                        //         $third_part_bal = $thirdPartyBalData->balance + $commisson_amount;
                                
                        //         DB::table('3rd_party_package_book_ledger')->insert([
                        //                 'package_id'=>$cart_res->tourId,
                        //                 'package_owner'=>$userData->id,
                        //                 'package_request'=>$thirdPartyData->id,
                        //                 'booking_amount'=>$cart_res->tour_total_price,
                        //                 'commisson_amount'=>$commisson_amount,
                        //                 'commisson_perc'=>$thirdPartyBalData->commission,
                        //                 'invoice_id'=>$invoiceId,
                        //                 'received'=>$commisson_amount,
                        //                 'balance'=>$third_part_bal
                        //                 ]);
                                        
                        //      // 
                             
                        //      DB::table('3rd_party_commissions')
                        //                             ->where('id',$thirdPartyBalData->id)
                        //                             ->update(['balance'=>$third_part_bal]);
                        //  }
                        
                         // Book From Same Website
                          $thirdPartyData = CustomerSubcription::where('small_token',$cart_res->provider_id)->select('id','small_token','status')->first();
                         if(isset($thirdPartyData)){
                             $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                             
                             $thirdPartyBalData = DB::table('3rd_party_commissions')
                                                    ->where('customer_id',$thirdPartyData->id)
                                                    ->where('third_party_id',$userData->id)
                                                    ->first();
                             // 1 Calculate Commission
                             
                             // Check Type of Commission
                                if($thirdPartyBalData->commission_type == '%'){
                                    $commisson_amount    = ($cart_res->tour_total_price * $thirdPartyBalData->commission) / 100;
                                }else{
                                    $commisson_amount = $thirdPartyBalData->commission;
                                }
                                
                                $third_part_bal = $thirdPartyBalData->balance + $commisson_amount;
                                
                                DB::table('3rd_party_package_book_ledger')->insert([
                                        'package_id'=>$cart_res->tourId,
                                        'package_owner'=>$thirdPartyData->id,
                                        'package_request'=>$userData->id,
                                        'booking_amount'=>$cart_res->tour_total_price,
                                        'commisson_amount'=>$commisson_amount,
                                        'commisson_perc'=>$thirdPartyBalData->commission,
                                        'invoice_id'=>$invoiceId,
                                        'received'=>$commisson_amount,
                                        'balance'=>$third_part_bal
                                        ]);
                                        
                             // 
                             
                             DB::table('3rd_party_commissions')
                                                    ->where('id',$thirdPartyBalData->id)
                                                    ->update(['balance'=>$third_part_bal]);
                         }
                         
                         
                    }
                    }
                    
                    $notification_insert                            = new alhijaz_Notofication();
                     $notification_insert->type_of_notification_id   = $booking_id ?? ''; 
                     $notification_insert->customer_id               = $userData->id ?? ''; 
                     $notification_insert->type_of_notification      = 'package_booking' ?? ''; 
                     $notification_insert->generate_id               = $invoiceId ?? '';
                     $notification_insert->notification_creator_name = $agent_name ?? '';
                     $notification_insert->total_price               = $cart_res->tour_total_price ?? ''; 
                     $notification_insert->amount_paid               = 0; 
                     $notification_insert->remaining_price           = $cart_res->tour_total_price ?? ''; 
                     $notification_insert->notification_status       = '1' ?? ''; 
                     $notification_insert->save();
                     
                      //Update Packge Pax
                    $package_data = DB::table('tours')->where('id',$cart_res->tourId)->select('id','available_seats','available_single_seats','available_double_seats','available_triple_seats','available_quad_seats')->first();
               
                    $available_seats = (float)$package_data->available_seats - (float)$cart_res->total_pax;
                    $available_double_seats = (float)$package_data->available_double_seats - (float)$cart_res->total_double_pax;
                    $available_triple_seats = (float)$package_data->available_triple_seats - (float)$cart_res->total_triple_pax;
                    $available_quad_seats = (float)$package_data->available_quad_seats - (float)$cart_res->total_quad_pax;
                    
                    
                    // print_r($package_data);
                    $package_data = DB::table('tours')->where('id',$package_data->id)->update([
                        'available_seats'=>$available_seats,
                        'available_double_seats'=>$available_double_seats,
                        'available_triple_seats'=>$available_triple_seats,
                        'available_quad_seats'=>$available_quad_seats,
                        ]);

    
                }}
            else{
                //  print_r($cart_data);die;
                 foreach($cart_data as $cart_res){
                     
                    // dd($cart_res);
                     
                    if($cart_res->agent_name != '-1'){
                        $agent_data = DB::table('Agents_detail')->where('id',$cart_res->agent_name)->select('id','balance')->first();
       
                        if(isset($agent_data)){
                            
                            $agent_balance = $agent_data->balance + $cart_res->activity_total_price;
                            
                            // update Agent Balance
                            DB::table('agents_ledgers_new')->insert([
                                'agent_id'              => $agent_data->id,
                                'received'              => $cart_res->activity_total_price,
                                'balance'               => $agent_balance,
                                'activity_invoice_no'   => $invoiceId,
                                'customer_id'           => $userData->id,
                                'date'                  => date('Y-m-d'),
                            ]);
                            DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                        }
                    }
                    
                    if(isset($cart_res->customer_id)){
                        if($cart_res->customer_id != '-1'){
                            $customer_data = DB::table('booking_customers')->where('id',$cart_res->customer_id)->select('id','balance')->first();
                            if(isset($customer_data)){
                                $customer_balance = $customer_data->balance + $cart_res->activity_total_price;
                                // update Agent Balance
                                DB::table('customer_ledger')->insert([
                                    'booking_customer'      => $customer_data->id,
                                    'received'              => $cart_res->activity_total_price,
                                    'balance'               => $customer_balance,
                                    'activity_invoice_no'   => $invoiceId,
                                    'customer_id'           => $userData->id,
                                    'date'                  => date('Y-m-d'),
                                ]);
                                DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                            }
                        }
                    }
                
                    $addtional_services = [];

                    if($cart_res->Additional_services_names_more !== 'null' AND $cart_res->Additional_services_names_more !== ''){
                        $services_names = json_decode($cart_res->Additional_services_names_more);
                        $services_persons_more = json_decode($cart_res->services_persons_more);
                        $services_price_more = json_decode($cart_res->services_price_more);
                        $services_days_more = json_decode($cart_res->services_days_more);
                        $services_dates_more = json_decode($cart_res->services_dates_more);
                        
                        $z = 0;
                        foreach($services_names as $service_res){
                             $service = [
                            'service'=>$service_res,
                            'service_price'=> $services_price_more[$z],
                            'service_type'=>'',
                            'person'=>$services_persons_more[$z],
                            'dates'=>$services_dates_more[$z],
                            'Service_Days'=>$services_days_more[$z],
                            'total_price'=>$services_days_more[$z] * $services_price_more[$z] * $services_persons_more[$z],
                            ];
                            
                            array_push($addtional_services,$service);
                        }
                    }
                    
                    if(isset($cart_res->Additional_services_Awaab)){
                        // dd($cart_res->Additional_services_Awaab);
                        $addtional_services = $cart_res->Additional_services_Awaab;
                    }else{
                        $addtional_services = json_encode($addtional_services);
                    }
                    
                    $start_date     = date("Y-m-d", strtotime($cart_res->activity_select_date));
                    
                    $Cart_details   = new Cart_details;
                    $Cart_details->tour_id = $cart_res->activtyId;
                    $Cart_details->generate_id = $cart_res->generate_id;
                    $Cart_details->tour_name = $cart_res->name;
                    $Cart_details->adults = $cart_res->adults;
                    $Cart_details->childs = $cart_res->children;
                    $Cart_details->adult_price = $cart_res->adult_price;
                    $Cart_details->child_price = $cart_res->child_price;
                    $Cart_details->activity_select_date = $start_date;
                    $Cart_details->tour_total_price = $cart_res->activity_total_price;
                    $Cart_details->total_service_price = $cart_res->total_service_price;
                    $Cart_details->price = $cart_res->price;
                    $Cart_details->cart_total_data = json_encode($cart_res);
                    $Cart_details->image = $cart_res->image;
                    $Cart_details->booking_id = $booking_id;
                    $Cart_details->invoice_no = $invoiceId;
                    $Cart_details->currency = $cart_res->currency;
                    $Cart_details->pakage_type = $cart_res->pakage_type;
                    $Cart_details->Additional_services_names = $addtional_services;
                    $Cart_details->client_id = $userData->id;
                    $Cart_data = $Cart_details->save();
                }
            }
            
            DB::commit();
            return response()->json(['message'=>'success','booking_id'=>$tourObj->id,'invoice_id'=>$invoiceId]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
    }
}
