<?php

namespace App\Http\Controllers\HotelMangers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\CustomerSubcription\CustomerSubcription;

class HotelbookingController extends Controller
{
    
      function save_hotel_booking_details_tbo(Request $request)
    {
       
            // print_r($request->id);
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'tbo_BookingDetail_rq' => $request->tbo_BookingDetail_rq,
            'tbo_BookingDetail_rs' => $request->tbo_BookingDetail_rs,
           
            
        ]); 
        
    }
    
    function SaveHotelBooking(Request $request){
        
      
        
        //   print_r($request->search_id);die;
        if($request->search_id){
           
           
          
                
        $result = DB::table('hotel_booking')->insert([
            
            'hotel_search_rq' => $request->hotel_search_rq,
             'hotel_search_rs' => $request->hotel_search_rs,
            
            'travellandasearchRQ' => json_encode($request->travellandasearchRQ),
            'travellandasearchRS' => $request->travellandasearchRS,
            'hotelbedsearchRQ' => $request->hotelbedsearchRQ,
            'hotelbedsearchRS' => $request->hotelbedsearchRS,
            'tboSearchRS' => $request->tboSearchRS,
             'tboSearchRQ' => $request->tboSearchRQ,
             
              'ratehawk_search_rq' => $request->ratehawk_search_rq,
             'ratehawk_search_rs' => $request->ratehawk_search_rs,
              
              
            'webbedsearchRQ' => $request->webbedsearchRQ,
            'webbedsearchRS' => $request->webbedsearchRS,
            'search_id' => $request->search_id,
            'token' => $request->token,
        ]);
        // print_r($request->tboDetailRS); die();
       $insertted_id = $request->search_id;
      return $insertted_id;
        }
         
        
       
        
        //detail start////
        
        
        
        if($request->travellandadetailRS){
            // print_r($request->id);
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'travellandadetailRS' => $request->travellandadetailRS,
            'travellandadetailRQ' => $request->travellandadetailRQ,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'rooms' => $request->room,
            'total_passenger' => $request->adult,
            'child' => $request->child,
            
            'provider' => "travellanda"
            
        ]); 
        }
        
        if($request->hotel_checkavailability){
            // print_r($request->id);
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'hotel_checkavailability' => $request->hotel_checkavailability,
            'rooms_checkavail' => $request->rooms_checkavail,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'rooms' => $request->room,
            'total_passenger' => $request->adult,
            'child' => $request->child,
            
            'provider' => "hotels"
            
        ]); 
        }
        
        if($request->ratehawk_details_rq){
            // print_r($request->id);
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'ratehawk_details_rq' => $request->ratehawk_details_rq,
            'ratehawk_details_rs' => $request->ratehawk_details_rs,
            'ratehawk_details_rq1' => $request->ratehawk_details_rq1,
            'ratehawk_details_rs1' => $request->ratehawk_details_rs1,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'rooms' => $request->room,
            'total_passenger' => $request->total_passenger,
            'child' => $request->child,
            'provider' => "ratehawk"
            
        ]); 
        }
        
        
        
        
        if($request->travellanda_cancellation_api_response){
            // print_r($request->id);
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'travellanda_cancellation_api_response' => $request->travellanda_cancellation_api_response,
            'booking_status' => $request->booking_status,
                ]); 
                 return response()->json(['result'=>$result]); 
        }
        
        if($request->tboCancellationRQ){
            // print_r($request->id);
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'tboCancellationRQ' => $request->tboCancellationRQ,
            'tboCancellationRS' => $request->tboCancellationRS,
            'booking_status' => 'Cancelled',
                ]); 
                 return response()->json(['result'=>$result]); 
        }
        
        if($request->ratehawk_cencellation_rq){
            // print_r($request->id);
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'ratehawk_cencellation_rq' => $request->ratehawk_cencellation_rq,
            'ratehawk_cancellation_rs' => $request->ratehawk_cancellation_rs,
            'booking_status' => 'Cancelled',
                ]); 
                 return response()->json(['result'=>$result]); 
        }
        
        
        
        
        
        if($request->hotelbeddetailRQ){
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'hotelbeddetailRS' => $request->hotelbeddetailRS,
            'hotelbeddetailRQ' => $request->hotelbeddetailRQ,
            
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_passenger' => $request->total_passenger,
            'child' => $request->child,
            'rooms' => $request->room,
            'provider' => "hotelbeds"
        ]); 
        }
        if($request->tboDetailRS){
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            
            'tboDetailRS' => $request->tboDetailRS,
            
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_passenger' => $request->total_passenger,
            'child' => $request->child,
            'rooms' => $request->room,
            'provider' => "tbo"
        ]); 
        
       
        }
        
        
        
        if($request->webbeddetailRS){
             
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'webbeddetailRS' => $request->webbeddetailRS,
            'webbeddetailRQ' => $request->webbeddetailRQ,
            'provider' => "webbed"
            
        ]); 
        }
        //detail end////
        //selection starts////
        if($request->travellandaSelectionRQ){
             
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'travellandaSelectionRQ' => $request->travellandaSelectionRQ,
            'travellandaSelectionRS' => $request->travellandaSelectionRS,
            'travellanda_cancellation_response' => $request->travellanda_cancellation_response,
        ]); 
        }
        
        
        if($request->tboSelectionRQ){
             
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'tboSelectionRQ' => $request->tboSelectionRQ,
            'tboSelectionRS' => $request->tboSelectionRS,
        ]);
        
         print_r($result);
        }
        
        
        
        
        
        
        
        
        if($request->webbedselectionRQ){
            // print_r($insertted_id);
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'webbedSelectionRQ' => $request->webbedselectionRQ,
            'webbedSelectionRS' => $request->webbedselectionRS,
        ]); 
        }
        if($request->hotelbedselectionRQ){
           
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'hotelbedSelectionRQ' => $request->hotelbedselectionRQ,
            'hotelbedSelectionRS' => $request->hotelbedselectionRS,
        ]); 
        }
        if($request->rooms_checkavailability){
           
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'rooms_checkavailability' => $request->rooms_checkavailability,
           
        ]); 
        }
        if($request->ratehawk_selection_rq){
           
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'ratehawk_selection_rq' => $request->ratehawk_selection_rq,
            'ratehawk_selection_rq_arr' => $request->ratehawk_selection_rq_arr,
            'ratehawk_selection_rs' => $request->ratehawk_selection_rs,
        ]); 
        }
        //selection end///
        //cnfrm start///
        if($request->travellandacnfrmRQ){
          $non_refundable=$request->non_refundable;
          if($non_refundable == 'non_refundable')
          {
              $booking_status='Non Refundable';
          }
          else
          {
            $booking_status='Confirmed';  
          }
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'booking_status'=> $booking_status,
            'travellandacnfrmRQ' => $request->travellandacnfrmRQ,
            'travellandacnfrmRS' => $request->travellandacnfrmRS,
             'auth_token' => $request->auth_token,
        ]); 
        }
        
         if($request->tboBookingRQ){
          $non_refundable=$request->non_refundable;
          if($non_refundable == 'non_refundable')
          {
              $booking_status='Non Refundable';
          }
          else if($non_refundable == 'failed')
          {
              $booking_status='Failed';
          }
          else
          {
            $booking_status='Confirmed';  
          }
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'booking_status'=> $booking_status,
            'tboBookingRQ' => $request->tboBookingRQ,
            'tboBookingRS' => $request->tboBookingRS,
            'auth_token' => $request->auth_token,
            
        ]); 
        }
        if($request->ratehawk_booking_rq){
           $non_refundable=$request->non_refundable;
          if($non_refundable == 'non_refundable')
          {
              $booking_status='Non Refundable';
            // $booking_status='Confirmed'; 
          }
          else
          {
            $booking_status='Confirmed';  
          }
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'ratehawk_booking_rq' => $request->ratehawk_booking_rq,
            'ratehawk_booking_rs' => $request->ratehawk_booking_rs,
            'booking_status'=> $booking_status,
            'auth_token' => $request->auth_token,
           
            
        ]); 
        }
        if($request->hotel_reservation){
           $non_refundable=$request->non_refundable;
          if($non_refundable == 'non_refundable')
          {
              $booking_status='Non Refundable';
            // $booking_status='Confirmed'; 
          }
          else
          {
            $booking_status='Confirmed';  
            // print_r($request->all());
            
            $hotel_data = DB::table('hotel_booking')->where('search_id',$request->id)->select('check_in','check_out')->first();
            // die;
            if($request->generate_id != null){
                $room_data = DB::table('rooms')->where('id',$request->rooms_id)->first();
                
                $rooms_more_data = json_decode($room_data->more_room_type_details);
                // print_r($rooms_more_data);
                
                $total_booked = 0;
                $booking_main_data = [];
                foreach($rooms_more_data as $key => $room_more_res){
                    // echo "The room gen ".$room_more_res->room_gen_id." and request is ".$request->generate_id;
                    if($room_more_res->room_gen_id == $request->generate_id){
                        // print_r($room_more_res);
                        $total_booked = $room_more_res->more_quantity_booked + $request->room_qunatity;
                        
                        
                        if(!empty($room_more_res->more_booking_details) && $room_more_res->more_booking_details !== null){
                            $booking_main_data = json_decode($room_more_res->more_booking_details);
                        }
                        
                         $booking_details = ['booking_from'=>'website',
                                 'quantity'=>$request->room_qunatity,
                                 'booking_id'=>$request->id,
                                 'date'=>date('Y-m-d'),
                                 'check_in'=>$hotel_data->check_in,
                                 'check_out'=>$hotel_data->check_out,
                             ];
                        array_push($booking_main_data,$booking_details);
                        $booking_main_data = json_encode($booking_main_data);
                        
                        $rooms_more_data[$key]->more_quantity_booked = $total_booked;
                        $rooms_more_data[$key]->more_booking_details = $booking_main_data;
                        // echo "total booked rooms is $total_booked";
                    }
                }
                
                // print_r($rooms_more_data);
                
                
                // die;
         
                DB::table('rooms')->where('id',$request->rooms_id)->update(['more_room_type_details'=>$rooms_more_data]);
                
                
                
            }else{
                // die;
                $room_data = DB::table('rooms')->where('id',$request->rooms_id)->first();
                
                $total_booked = $room_data->booked + $request->room_qunatity;
                
                $booking_main_data = [];
                if(!empty($room_data->booking_details) && $room_data->booking_details !== null){
                    $booking_main_data = json_decode($room_data->booking_details);
                }
                
                $booking_details = ['booking_from'=>'website',
                        'quantity'=>$request->room_qunatity,
                        'booking_id'=>$request->id,
                        'date'=>date('Y-m-d'),
                        'check_in'=>$hotel_data->check_in,
                        'check_out'=>$hotel_data->check_out,
                    ];
                array_push($booking_main_data,$booking_details);
                $booking_main_data = json_encode($booking_main_data);
                DB::table('rooms')->where('id',$request->rooms_id)->update(['booked'=>$total_booked,'booking_details'=>$booking_main_data]);
            }
            
          }
            
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'hotel_reservation' => $request->hotel_reservation,
           
            'booking_status'=> $booking_status,
            'auth_token' => $request->auth_token,
            
           
            
        ]);
        
        
        return response()->json(['message'=>'success','result'=>$result]);
        }
      
        if($request->webbedcnfrmRQ){
        //   print_r($insertted_id);
           $result = DB::table('hotel_booking')->where('search_id',$request->id)->update([
            'webbedcnfrmRQ' => $request->webbedcnfrmRQ,
            'webbedcnfrmRS' => $request->webbedcnfrmRS,
        ]); 
        }
        
        
        
        if($request->hotelbedcnfrmRQ){
           
            
            $is_refundable=$request->is_refundable;
        // print_r($is_refundable);die();
            if($is_refundable == 'non_refundable')
            {
                $booking_status='Non Refundable';
            }
            if($is_refundable == 'refundable')
            {
                $booking_status='Confirmed';
            }
           $result = DB::table('hotel_booking')->where('search_id',$request->search_id)->update([
            'hotelbedcnfrmRQ' => $request->hotelbedcnfrmRQ,
            'hotelbedcnfrmRS' => $request->hotelbedcnfrmRS,
            'booking_status' => $booking_status
        ]); 
   
        }
        //cnfrm end////
    }
    
    
    
    
    public function getVoucherData(Request $request){
        
        $data_tbo=DB::table('hotel_booking')->where('search_id', $request->id)->first();
        return json()->response(['data_tbo','data_tbo']);
        
    }
    
    
    public function customer_subcriptions_authentication(Request $request){
        // dd($request->token);
        
        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
        $hotel_payment_details=DB::table('hotel_payment_details')->where('hotel_search_id',$request->search_id)->sum('amount_paid');
        $name=$customer_subcriptions->name;
        $lname=$customer_subcriptions->lname;
        $client_name=$name . $lname;
        return response()->json(['hotel_payment_details'=>$hotel_payment_details,'client_name'=>$client_name]);
        
    }
}
			
