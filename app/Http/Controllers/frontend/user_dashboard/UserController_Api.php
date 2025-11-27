<?php

namespace App\Http\Controllers\frontend\user_dashboard;

use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Input as input;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Hash;
use Auth;
use DB;
use App\User;
use App\Index;
use App\About_us;
use App\Models\Lead_Passenger_location;

// use App\Contact_us;

use App\Blog;
use App\Package;
use App\Gallery;
use App\Ourteam;
use App\WhyChoose;
use App\Models\Booking;
use App\Models\Agent;
use App\Models\Conversation;
use App\Models\Ticket;
use App\Mail\SendAgentMail;
use Webpatser\Uuid\Uuid;

use Illuminate\Support\Facades\App;

class UserController_Api extends Controller
{

public function manage_user_roles(Request $request){
        $customer_id    = $request->id;
        $users          = DB::table('role_managers')->where('customer_id',$customer_id)->get();
        return response()->json(['message'=>'success','users'=>$users]);
    }
public function add_user_permission(Request $request){
        $customer_id    = $request->id;
        $add_user       = DB::table('role_managers')->insert([
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'role_type'         => $request->role_type,
            'email'             => $request->email,
            'password'          => $request->password,
            'user_permission'   => $request->permissions,
            'customer_id'       => $customer_id,
        ]);
        return response()->json(['message'=>'success','add_user'=>$add_user]);
    }
public function edit_user_permission(Request $request){
        $customer_id    = $request->id;
        $id             = $request->ids;
        $add_user       = DB::table('role_managers')->where('id',$id)->update([
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'role_type'         => $request->role_type,
            'email'             => $request->email,
            'user_permission'   => $request->permissions,
            'customer_id'       => $customer_id,
        ]);
        return response()->json(['message'=>'success','add_user'=>$add_user]);
    }
    
    
    public function update_user_password(Request $request){
        $userRole   = DB::table('role_managers')->where('id',$request->id)->first();
        if (Hash::check($request->input('current_password'), $userRole->password) == false){
            return response()->json(['status'=>'error','message'=>'invalid current password']);
        }
        else if($request->input('new_password') != $request->input('cnew_password')){
            return response()->json(['status'=>'error','message'=>'confirm password password does not match!!']);
        }
        else{
            DB::table('role_managers')->where('id',$request->id)->update(['password'=>Hash::make($request->input('new_password'))]);
            return response()->json(['status'=>'success','message'=>'password updated successfully']);
        }
	}
    
    
    
public function super_admin_activate_user(Request $request){
        $customer_id    = $request->customer_id;
        $id             = $request->id;
        $find_user      = DB::table('role_managers')->where('id',$id)->where('customer_id',$customer_id)->update(['is_active'=>'0']);
        return response()->json(['message'=>'success']);
    }
public function super_admin_inactivate_user(Request $request){
        $customer_id    = $request->customer_id;
        $id             = $request->id;
        $find_user      = DB::table('role_managers')->where('id',$id)->where('customer_id',$customer_id)->update(['is_active'=>'1']);
        return response()->json(['message'=>'success']);
    }
public function mange_user_role_delete(Request $request){
        $id             = $request->id;
        $customer_id    = $request->customer_id;
        $find_user      = DB::table('role_managers')->where('id',$id)->where('customer_id',$customer_id)->delete();
        return response()->json(['message'=>'success','find_user'=>$find_user]);
    }
public function add_markup_api(Request $request){



$customer=\DB::table('apisynchtravel_db2023.customer_subcriptions')->get();




    }
    
    public function admin_markup_api(Request $request){
        
        DB::table('custome_hotel_markup')->insert([
            'customer_id'   => $request->customer_id,
            'markup'        => $request->markup_type,
            'markup_value'  => $request->markup_value,
            'status'        => 1,
        ]);
        
        $status     = $request->status;
        $get_data   = DB::table('admin_markups')->where('customer_id',$request->customer_id)->where('status',1)->first();
        
        if(isset($get_data)){
            $data_update = DB::table('admin_markups')->where('id',$get_data->id)->update(array('status'=>0));
            
            $data_insert = DB::table('admin_markups')->insert(
                array(
                    'provider'          => $request->provider,
                    'customer_id'       => $request->customer_id,
                    'markup_type'       => $request->markup_type,
                    'markup_value'      => $request->markup_value,
                    'status'            => 1,
                    'added_markup'      => $request->added_markup,
                    'services_type'     => $request->services_type,
                )
            );
            return response()->json([
                'data_update' => $data_update,
                'data_insert' => $data_insert
            ]);  
        }else{
            $data = DB::table('admin_markups')->insert(
                array(
                    'provider'      => $request->provider,
                    'customer_id'   => $request->customer_id,
                    'markup_type'   => $request->markup_type,
                    'markup_value'  => $request->markup_value,
                    'status'        => 1,
                    'added_markup'  => $request->added_markup,
                    'services_type' => $request->services_type,
                )
            );
            return response()->json([
                'data' => $data,
            ]);
        }
    }
    
public function view_markup_api(Request $request){


// $token=$request->token;
// $customer_id=DB::table('apisynchtravel_db2023.customer_subcriptions')->where('Auth_key',$token)->select('id','name','lname')->first();
// $name=$customer_id->name ?? '';
// $lname=$customer_id->lname ?? '';
// $user_name=$name . ' '. $lname;
$markup=DB::table('apisynchtravel_db2023.admin_markups')->where('customer_id',$request->customer_id)->where('status',1)->first();

 return response()->json([
            'markup' => $markup,
           
            
        ]);
    }
public function custom_hotel_markup(Request $request){
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$request->customer_id)->where('status',1)->first();
      
        return response()->json([
                        'status' => 'success',
                        'custom_hotel_markup' => $custom_hotel_markup,
                       
                    ]);
        
    }
public function custom_hotel_markup_submit(Request $request){
        $request_data = json_decode($request->request_data);
        $custom_hotel_markup = DB::table('custome_hotel_markup')->where('customer_id',$request->customer_id)->where('status',1)->first();
        if($custom_hotel_markup){
             DB::beginTransaction();
                  
            try {
            
                $result = DB::table('custome_hotel_markup')
                                ->where('customer_id',$request->customer_id)
                                ->where('status',1)
                                ->update(['status' => 0]);
                if($result){  
                        DB::table('custome_hotel_markup')->insert([
                                'customer_id' => $request->customer_id,
                                'markup' => $request_data->markup_type,
                                'markup_value' => $request_data->markup_value,
                                'status' => 1
                            ]);
                                   
                       
                }
                
                DB::commit();
                 return response()->json([
                                'status' => 'success',
                            ]); 
                    // echo $result;
            } catch (Throwable $e) {

                DB::rollback();
                return response()->json(['message'=>'error']);
            }
                     
        }else{
            $result = DB::table('custome_hotel_markup')->insert([
                        'customer_id' => $request->customer_id,
                        'markup' => $request_data->markup_type,
                        'markup_value' => $request_data->markup_value,
                        'status' => 1
                    ]);
            if($result){
                return response()->json([
                                'status' => 'success',
                            ]); 
            }else{
                return response()->json(['message'=>'error']);
            }
        }
    }
public function become_provider_hotel_markup_submit(Request $request){
        $request_data = json_decode($request->request_data);
        // dd($request_data);
        
        $provider_found = DB::table('custom_hotel_provider')->where('customer_id',$request->customer_id)->first();
        if($provider_found){
            $custom_hotel_markup = DB::table('become_provider_markup')->where('customer_id',$request->customer_id)->where('status',1)->first();
            if($custom_hotel_markup){
                 DB::beginTransaction();
                      
                try {
                
                    $result = DB::table('become_provider_markup')
                                    ->where('customer_id',$request->customer_id)
                                    ->where('status',1)
                                    ->update(['status' => 0]);
                    if($result){  
                            DB::table('become_provider_markup')->insert([
                                    'customer_id' => $request->customer_id,
                                    'markup' => $request_data->markup_type,
                                    'markup_value' => $request_data->markup_value,
                                    'status' => 1
                                ]);
                                       
                           
                    }
                    
                    DB::commit();
                     return response()->json([
                                    'status' => 'success',
                                ]); 
                        // echo $result;
                } catch (Throwable $e) {
    
                    DB::rollback();
                    return response()->json(['message'=>'error']);
                }
                         
            }else{
                $result = DB::table('become_provider_markup')->insert([
                            'customer_id' => $request->customer_id,
                            'markup' => $request_data->markup_type,
                            'markup_value' => $request_data->markup_value,
                            'status' => 1
                        ]);
                if($result){
                    return response()->json([
                                    'status' => 'success',
                                ]); 
                }else{
                    return response()->json(['message'=>'error']);
                }
            }
        }else{
              DB::beginTransaction();
                      
                try {
                        $result = DB::table('custom_hotel_provider')->insert([
                                        'customer_id' => $request->customer_id,
                                        'provider_name' => $request->provider_name,
                                    ]);
                                    
                        $result = DB::table('become_provider_markup')->insert([
                                        'customer_id' => $request->customer_id,
                                        'markup' => $request_data->markup_type,
                                        'markup_value' => $request_data->markup_value,
                                        'status' => 1
                                    ]);
                            DB::commit();
                            
                     return response()->json([
                                    'status' => 'success',
                                ]); 
                                
                        // echo $result;
                } catch (Throwable $e) {
    
                    DB::rollback();
                    return response()->json(['message'=>'error']);
                }
        }
        
    }
public function become_provider(Request $request){
        $custom_hotel_markup = DB::table('become_provider_markup')->where('customer_id',$request->customer_id)->where('status',1)->first();
      
        return response()->json([
                        'status' => 'success',
                        'custom_hotel_markup' => $custom_hotel_markup,
                       
                    ]);
        
    }
    
    public function on_request_booking_Season_Working($all_data,$request){
        $today_Date             = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->created_at)) {
                    return false;
                }
                
                if($record->created_at != null && $record->created_at != ''){
                    $created_at     = Carbon::parse($record->created_at);
                    $start_Date     = Carbon::parse($start_Date);
                    $end_Date       = Carbon::parse($end_Date);
                    return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function on_request_booking(Request $request){
        $user_data              = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
        // dd($user_data->id);
        
        // if($user_data->id == 45){
            
        //     $on_request_bookings    = DB::table('hotels_bookings')
        //                                     ->where(function ($query) use ($user_data) {
        //                                         $query->where('customer_id', $user_data->id)
        //                                         ->where('status', 'Room On Request');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('customer_id', $user_data->id)
        //                                         ->where('status', 'In_Progess');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('customer_id', $user_data->id)
        //                                         ->where('status', 'Confimed');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('customer_id', $user_data->id)
        //                                         ->where('status', 'CONFIRMED');
        //                                     })
        //                                     ->orwhere(function ($query) use ($user_data) {
        //                                         $query->where('client_Id', $user_data->id)
        //                                         ->where('status', 'Room On Request');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('client_Id', $user_data->id)
        //                                         ->where('status', 'In_Progess');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('client_Id', $user_data->id)
        //                                         ->where('status', 'Confimed');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('client_Id', $user_data->id)
        //                                         ->where('status', 'CONFIRMED');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('client_Id', $user_data->id)
        //                                         ->where('status', 'Reject');
        //                                     })
        //                                     ->get();
        // }else{
        //         $on_request_bookings    = DB::table('hotels_bookings')
        //                                     ->where(function ($query) use ($user_data) {
        //                                         $query->where('customer_id', $user_data->id)
        //                                         ->where('status', 'Room On Request');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('customer_id', $user_data->id)
        //                                         ->where('status', 'In_Progess');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('customer_id', $user_data->id)
        //                                         ->where('status', 'Confimed');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('customer_id', $user_data->id)
        //                                         ->where('status', 'CONFIRMED');
        //                                     })
        //                                     ->orWhere(function ($query) use ($user_data) {
        //                                         $query->where('client_Id', $user_data->id)
        //                                         ->where('status', 'Reject');
        //                                     })
        //                                     ->get();
        //                                 // ->where('customer_id',$user_data->id)
        //                                 // ->where('status','Room On Request')
        //                                 // ->orWhere('status','In_Progess')
        //                                 // ->get();
        //     }
        
        // return response()->json([
        //     'status'                => 'success',
        //     'on_request_bookings'   => $on_request_bookings,
           
        // ]);
        
        if($user_data->id == 45){
            $on_request_bookings = DB::table('hotels_bookings')
                                        ->leftJoin('b2b_agents', 'b2b_agents.id', '=', 'hotels_bookings.b2b_agent_id')
                                        ->where(function ($query) use ($user_data) {
                                            $query->where('customer_id', $user_data->id)
                                                ->where('status', 'Room On Request');
                                        })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('customer_id', $user_data->id)
                                                ->where('status', 'In_Progess');
                                        })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('customer_id', $user_data->id)
                                                ->where('status', 'Confimed');
                                        })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('customer_id', $user_data->id)
                                                ->where('status', 'CONFIRMED');
                                        })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('client_Id', $user_data->id)
                                                ->where('status', 'Room On Request');
                                        })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('client_Id', $user_data->id)
                                                ->where('status', 'In_Progess');
                                        })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('client_Id', $user_data->id)
                                                ->where('status', 'Confimed');
                                        })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('client_Id', $user_data->id)
                                                ->where('status', 'CONFIRMED');
                                        })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('client_Id', $user_data->id)
                                                ->where('status', 'Reject');
                                        })
                                        ->select('hotels_bookings.*', 'b2b_agents.company_name')
                                        ->get();
        } else {
            $on_request_bookings    = DB::table('hotels_bookings')
                                        ->leftJoin('b2b_agents', 'b2b_agents.id', '=', 'hotels_bookings.b2b_agent_id')
                                        ->where(function ($query) use ($user_data) {
                                            $query->where('customer_id', $user_data->id)
                                                ->where('status', 'Room On Request');
                                        })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('customer_id', $user_data->id)
                                                ->where('status', 'In_Progess');
                                        })
                                        // ->orWhere(function ($query) use ($user_data) {
                                        //     $query->where('customer_id', $user_data->id)
                                        //         ->where('status', 'Confimed');
                                        // })
                                        // ->orWhere(function ($query) use ($user_data) {
                                        //     $query->where('customer_id', $user_data->id)
                                        //         ->where('status', 'CONFIRMED');
                                        // })
                                        ->orWhere(function ($query) use ($user_data) {
                                            $query->where('client_Id', $user_data->id)
                                                ->where('status', 'Reject');
                                        })
                                        ->select('hotels_bookings.*', 'b2b_agents.company_name')
                                        ->get();
                    }
        
        // Season
        $today_Date             = date('Y-m-d');
        $season_Id              = '';
        if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
            $season_Id          = 'all_Seasons';
        }elseif(isset($request->season_Id) && $request->season_Id > 0){
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $user_data->id)->where('id', $request->season_Id)->first();
            $season_Id          = $season_SD->id ?? '';
        }else{
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $user_data->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            $season_Id          = $season_SD->id ?? '';
        }
        
        $season_Details         = DB::table('add_Seasons')->where('customer_id', $user_data->id)->get();
        if($user_data->id == 4){
            if($on_request_bookings->isEmpty()){
            }else{
                // dd($on_request_bookings);
                $on_request_bookings   = $this->on_request_booking_Season_Working($on_request_bookings,$request);
                // dd($on_request_bookings);
            }
        }
        // Season
        
        return response()->json([
            'status'                => 'success',
            'on_request_bookings'   => $on_request_bookings,
            'season_Details'        => $season_Details,
            'season_Id'             => $season_Id,
        ]);
    }
    
    public function custom_hotel_revenue(Request $request){
            $user_data=DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id')->first();
            //return $user_data;
            $bookings = DB::table('hotels_bookings')
            ->leftJoin('manage_customer_markups', 'hotels_bookings.invoice_no', '=', 'manage_customer_markups.invoice_no')
            ->where('provider','Custome_hotel')->where('customer_id',$user_data->id)->where('status','Confirmed')->orwhere('status','Confrimed')->orwhere('customer_id',$user_data->id)->get();
            // ->where('provider','Custome_hotel')->where('customer_id',$user_data->id)->where('status','Confirmed')->get();
      
        return response()->json([
                        
                        'bookings' => $bookings,
                       
                    ]);
        
    }
public function edit_markup_api(Request $request,$id){


$markup=\DB::table('apisynchtravel_db2023.admin_markups')->where('id',$id)->first();

 return response()->json([
            'markup' => $markup,
            
            
        ]);

    }  
public function update_markup_api(Request $request,$id){



        $markup=\DB::table('apisynchtravel_db2023.admin_markups')->where('id',$id)->update(

        array(

            'provider'   =>   $request->provider,

            'customer_id'   =>  $request->customer_id,

            'markup_type'   =>   $request->markup_type,

            'markup_value'   =>   $request->markup_value,
             'admin_site'   =>   $request->admin_site,
        ));

         return response()->json([
            'markup' => $markup,
            
            
        ]);

    }    
public function delete_markup_api(Request $request,$id){



        $markup=\DB::table('apisynchtravel_db2023.admin_markups')->where('id',$id)->delete();

         return response()->json([
            'markup' => $markup,
            
            
        ]);

    }  
public function get_markup_hotel_api(Request $request){


$token=$request->token;
$customer_id=DB::table('apisynchtravel_db2023.customer_subcriptions')->where('Auth_key',$token)->select('id')->first();

$markup=DB::table('apisynchtravel_db2023.admin_markups')->where('customer_id',$customer_id->id)->where('status',1)->orwhere('added_markup','synchtravel')->orderBy('id','DESC')->get();

 return response()->json([
            'data' => $markup,
            
            
        ]);
    }
public function index(){

       

        
        return view('template/frontend/userdashboard/index');

		//return view('template/frontend/userdashboard/index',compact('data'));

	}

public function downloadFile(Request $request){
       
      $uuid=$request->uuid;
    $book = Conversation::where('uuid', $uuid)->firstOrFail();
    //print_r($book->message);die();
    $myFile = public_path("uploads/file/".$book->message);
    
    $pathToFile = $myFile;
     return response()->json(['pathToFile' => $pathToFile]);
   
} 


public function downloadFileclient(Request $request){
       
      $uuid=$request->uuid;
    $book = Conversation::where('uuid', $uuid)->firstOrFail();
    //print_r($book->message);die();
   
     return response()->json(['pathToFile' => $book->message]);
   
}


    public function view_ticket(Request $request){
        $ticket = Ticket::with('conversation')->where('customer_id',$request->id)->orderBy('id', 'DESC')->get();
        // $ticket = DB::table('tickets')->where('customer_id',$request->id)->orderBy('id', 'DESC')->get();
        
        // $ticket = Ticket::with('conversation')->where('customer_id', $request->id)->orderBy('id', 'DESC')->get();
        // App::setLocale('ar');
        // $currentLocale = App::getLocale();
        // $ticketCompanyNames = DB::table('translations')
        //                         ->where('key_name', 'company_name')
        //                         ->whereIn('id', $ticket->pluck('id'))
        //                         ->where('language_code', $currentLocale)
        //                         ->pluck('value', 'id');
        // foreach ($ticket as $t) {
        //     $t->company_name = $ticketCompanyNames[$t->id] ?? $t->company_name;
        // }
        
        // dd($ticket);
        
        return response()->json(['ticket' => $ticket]);
    }
    
public function updated_status_ticket_client(Request $request){
    $customer_id=$request->customer_id;
    
    $clientData=DB::table('customer_subcriptions')->where('id',$customer_id)->first();
    
    $ticket_id=$request->ticket_id;
    $status_type=$request->status_type;
  $ticket=Ticket::find($ticket_id);
  DB::beginTransaction();
        try {   
       
       if($status_type == 'Accecpted')
       {
          $ticket_status='Resoloved';
          $ticket->status=$ticket_status;
          $ticket->update();
          
          $conversation=new Conversation();
            $conversation->message='ticket has been resolved and confirmed by'.$clientData->company_name ?? '';
            $conversation->ticket_id =$ticket_id;
            $conversation->message_sent='client';
            $conversation->save();
       }
       else{
          $ticket_status=$status_type;
          $ticket->status=$ticket_status;
            $ticket->update();
        
        $conversation=new Conversation();
            $conversation->message='Ticket status has been rejected by the '. $clientData->company_name ?? ''.', seems like his issue is not resolved.';
            $conversation->ticket_id =$ticket_id;
            $conversation->message_sent='client';
            $conversation->save();
       }
        
        
        DB::commit();
         return response()->json(['message' => 'success']);
            
           
        } catch (Throwable $e) {
                 DB::rollback();
                  return response()->json(['message' => 'error']);
        }
}    
    
    
public function view_offers(Request $request){
        $offers=DB::table('offers')->where('customer_id',$request->id)->get();
        $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
        ->where('tours.customer_id',$request->id)
        ->get();
        $umrah_packages=DB::table('umrah_packages')->where('customer_id',$request->id)->get();
        $data = $tours->union($umrah_packages);
       
      return response()->json(['offers' => $offers,'data'=>$data]);
    }

public function conversation_admin(Request $request){
    
    $customer_name=$request->customer_name;
    $ticket_id=$request->ticket_id;
         $ticket=Ticket::find($ticket_id);
     $conversation=$ticket->conversation;
     $client=DB::table('customer_subcriptions')->where('id',$ticket->customer_id)->first();
    return response()->json(['client'=>$client,'conversation'=>$conversation,'ticket' => $ticket]);
         
    }
    public function conversation_admin_submit(Request $request){
      $message_type=$request->message_type; 
      $ticket_id=$request->ticket_id;
      $getFilesupload=$request->getFilesupload;
      //print_r($getFilesupload);
      
       if ($getFilesupload) {
    $uuid = (string) Uuid::generate();   
}
else{
     
    $uuid = NULL;
    $message_type = $request->message_type;
}
      
    
      
     DB::beginTransaction();
        try {   
        $conversation=new Conversation();
        $conversation->message = $message_type;
        $conversation->uuid = $uuid;
        $conversation->ticket_id =$ticket_id;
        $conversation->message_sent='client';
        $conversation->save();
        
        DB::commit();
        return response()->json(['message'=>'success']);
          
           
        } catch (Throwable $e) {
                 DB::rollback();
               return response()->json(['message'=>'error']);
        }
    }
    
    

}

