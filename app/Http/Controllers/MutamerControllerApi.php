<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Hash;
class MutamerControllerApi extends Controller
{
 
    public function getinvoice(Request $request){
        // dd($request);
       $invoice_no = $request->invoice_no;
        
      $invoice_data = \DB::table('tours_bookings')->where('invoice_no',$invoice_no)->first();
      
          return response()->json(['message'=>'success','fetchedinvoice'=>$invoice_data]);
    }
    
    
    public function addlead(Request $request){
        // dd($request);
 
     
          \DB::table('C_Tracker')->insert([
          'invoice'=>$request->invoice_no,
          'leadname'=>$request->leadname,
          'leadmail'=>$request->leadmail,
          'leadpassword'=>hash::make($request->leadpassword),
              ]);

          return response()->json(['message'=>'success']);
    }

    
    public function leadpassengerin(Request $request){
        // dd($request);
      
          $lead_passenger = DB::table('C_Tracker')->get();
      //print_r($lead_passenger);die();
          foreach($lead_passenger as $lead_passengers){
              
        if($lead_passengers->leadmail == $request->leademail){
             
            if (Hash::check($request->leadpassword, $lead_passengers->leadpassword))
                {
                    // dd($lead_passengers);
                    $lead_passengerz = $lead_passengers;
                    \DB::table('C_Tracker')->where('id',$lead_passengerz->id)->update([
              'status'=> 'logedin',
              ]);
                    
                }else{
                    $lead_passengerz = null;
                }
             
        }
          }
            if($lead_passengerz != "" && $lead_passengerz != null){
            return response()->json(['message'=>'success','fetchedlead'=>$lead_passengerz]);
            }else{
                 return response()->json(['message'=>'false']);
            }
          
    }


public function save_lead_location(Request $request){
      
        // dd($request);
        
        $location_routine_array = [];

        $lead_passenger_loc = DB::table('C_Tracker')->where('id',$request->leadId)->first();
        //print_r($lead_passenger_loc);die();
        
        if($lead_passenger_loc->updated_location != null && $lead_passenger_loc->updated_location != ""){
            // dd($lead_passenger_loc);
            $location_data = $lead_passenger_loc->updated_location;
            $location_data = json_decode($location_data);
             array_push($location_data,$request->all());
             $location_routine_array = $location_data;
        }else{
             array_push($location_routine_array,$request->all());
        }
    //   print_r($location_routine_array);die();
          $lead_passenger = \DB::table('C_Tracker')->where('id',$request->leadId)->update([
              'updated_location'=> json_encode($location_routine_array),
              'Clocation'=> $request->cityName.', '.$request->countryName,
              'locationIP'=> $request->ip,
              ]);
              
           //print_r($lead_passenger);die();   
            if($lead_passenger != "" && $lead_passenger != null){
            return response()->json(['message'=>'success']);
            }else{
                 return response()->json(['message'=>'false']);
            }
          
    }
    public function save_lead_location_old(Request $request){
      
        // dd($request);
        
        $location_routine_array = [];

        $lead_passenger_loc = DB::table('C_Tracker')->where('id',$request->leadId)->first();
        
        if($lead_passenger_loc->location != null && $lead_passenger_loc->location != ""){
            // dd($lead_passenger_loc);
            $location_data = $lead_passenger_loc->location;
            $location_data = json_decode($location_data);
             array_push($location_data,$request->all());
             $location_routine_array = $location_data;
        }else{
             array_push($location_routine_array,$request->all());
        }
      
          $lead_passenger = \DB::table('C_Tracker')->where('id',$request->leadId)->update([
              'location'=> json_encode($location_routine_array),
              'locationIP'=> $request->ip,
              ]);
              
           //print_r($lead_passenger);die();   
            if($lead_passenger != "" && $lead_passenger != null){
            return response()->json(['message'=>'success']);
            }else{
                 return response()->json(['message'=>'false']);
            }
          
    }

    
    public function get_lead_location(Request $request){

       
      
          $lead_passenger = \DB::table('C_Tracker')->where('id',$request->leadId)->first();
             
              
            if($lead_passenger != "" && $lead_passenger != null){
             return response()->json(['message'=>'success','fetchedleadlocation'=>$lead_passenger]);
            }else{
                 return response()->json(['message'=>'false']);
            }
          
    }

    
    public function cron_update_lead_location(Request $request){

        $lead = \DB::table('C_Tracker')->where('status','logedin')->get();
        foreach($lead as $leads){
            if($leads->location !=null && $leads->location !=''){
                $currentdateandtime = date("Y/m/d")."-".date("h:i:sa");
                $lead_loc_history_arr = json_decode($leads->location);
                    $loc_ip = $leads->locationIP;
                    $ip = $loc_ip;
                  
                    $location = \Location::get($ip);
                    $array=[
                        'leadId'=>$leads->id ?? '',
                        'ip'=>$location->ip ?? '',
                        'countryCode'=>$location->countryCode ?? "",
                        'regionName'=>$location->regionName ?? "",
                        'cityName'=>$location->cityName ?? "",
                        'latitude'=>$location->latitude ?? "",
                        'longitude'=>$location->longitude ?? "",
                        'dateNtime'=>$currentdateandtime ?? "",
                        ];
                   
                    
                 array_push($lead_loc_history_arr,$array);
                
            }
         
        
        
        
                \DB::table('C_Tracker')->where('id',$leads->id)->update([
                    'location'=>json_encode($lead_loc_history_arr),
                    ]);
            
        }
       
         
          
    }
    
    
    public function lead_logout(Request $request){

     
      if($request->update_status){
       \DB::table('C_Tracker')->where('id',$request->leadId)->update([
              'status'=>$request->update_status,
              ]);
            }
        
          
          
    }
 


}
