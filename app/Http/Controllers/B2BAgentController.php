<?php
namespace App\Http\Controllers;
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
use App\Mail\SendMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class B2BAgentController extends Controller
{
    public function create_B2B_Agents(Request $request){
        DB::beginTransaction(); 
        try {
            $all_countries          = country::all();
            $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$request->token)->orderBy('id', 'ASC')->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','subscriptions_Packages'=>$subscriptions_Packages,'all_countries'=>$all_countries]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_B2B_Agents(Request $request){
        DB::beginTransaction(); 
        try {
            $all_countries  = country::all();
            $b2b_Agents     = DB::table('b2b_agents')->where('token',$request->token)->orderBy('id', 'DESC')->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','b2b_Agents'=>$b2b_Agents,'all_countries'=>$all_countries]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function b2b_Agent_Markup(Request $request){
        DB::beginTransaction(); 
        try {
            $all_countries  = country::all();
            $b2b_Agents     = DB::table('b2b_agents')->where('token',$request->token)->orderBy('id', 'DESC')->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','b2b_Agents'=>$b2b_Agents,'all_countries'=>$all_countries]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function add_b2b_Agent_Markup(Request $request){
        DB::beginTransaction(); 
        try {
            
            $agent_Markup_Details = json_decode($request->agent_Markup_Details);
            
            // dd($agent_Markup_Details);
            
            foreach($agent_Markup_Details as $val_AMD){
                
                $b2b_Agent_Markups = DB::table('b2b_Agent_Markups')->where('token',$request->token)->where('agent_Id',$val_AMD->agent_Id)->first();
                
                // dd($b2b_Agent_Markups);
                
                if(!empty($b2b_Agent_Markups) && $b2b_Agent_Markups !== null){
                    
                    // dd($val_AMD->agent_Id);
                    
                    DB::table('b2b_Agent_Markups')->where('agent_Id',$val_AMD->agent_Id)->update(['markup_status' => 0]);
                    
                    DB::table('b2b_Agent_Markups')->insert([
                        'token'         => $request->token,
                        'customer_id'   => $request->customer_id,
                        'agent_Id'      => $val_AMD->agent_Id,
                        'agent_Details' => $val_AMD->agent_Details,
                        'markup_Type'   => $val_AMD->markup_Type,
                        'markup_Value'  => $val_AMD->markup_Value,
                        'markup_status' => 1,
                    ]);
                    
                }else{
                    DB::table('b2b_Agent_Markups')->insert([
                        'token'         => $request->token,
                        'customer_id'   => $request->customer_id,
                        'agent_Id'      => $val_AMD->agent_Id,
                        'agent_Details' => $val_AMD->agent_Details,
                        'markup_Type'   => $val_AMD->markup_Type,
                        'markup_Value'  => $val_AMD->markup_Value,
                        'markup_status' => 1,
                    ]);
                }
            }
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Agent Markup Added Successfully!']);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_b2b_Agent_Markup(Request $request){
        DB::beginTransaction(); 
        try {
            $b2b_Agent_Markups = DB::table('b2b_Agent_Markups')->where('token',$request->token)->orderBy('id', 'DESC')->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','b2b_Agent_Markups'=>$b2b_Agent_Markups]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function get_B2B_Agents_Details(Request $request){
        DB::beginTransaction(); 
        try {
            $b2b_Agent_Details = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
            DB::commit();
            return response()->json(['status'=>'success','message'=>'B2B Agent Details!','b2b_Agent_Details'=>$b2b_Agent_Details]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function b2b_Agents_Change_Password(Request $request){
        DB::beginTransaction(); 
        try {
            $b2b_Agents = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
            DB::commit();
            return response()->json(['status'=>'success','message'=>'B2B Agent Details!','b2b_Agents'=>$b2b_Agents]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public static function Change_Password_Mail($request){
        $mail_Send_Status       = false;
        
        // Alsubaee Hotels
        if($request->token == config('token_Alif')){
            $from_Address           = config('mail_From_Address_Alif');
            $website_Title          = config('mail_Title_Alif');
            $mail_Template_Key      = config('mail_Template_Key_Alif');
            $website_Url            = config('website_Url_Alif');
            // $mail_Address_Register  = 'ua7583232@gmail.com';
            $mail_Send_Status       = true;
        }
        
        if($mail_Send_Status != false){
            $b2b_Agents                 = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
            $lead_title                 = $b2b_Agents->title ?? '';
            $lead_email                 = $b2b_Agents->email ?? '';
            $lead_first_name            = $b2b_Agents->first_name ?? '';
            $lead_last_name             = $b2b_Agents->last_name ?? '';
            $lead_phone                 = $b2b_Agents->phone_no ?? '';
            $details                    = [
                'lead_title'            => $lead_title,
                'lead_Name'             => $lead_first_name,
                'email'                 => $lead_email,
                'contact'               => $lead_phone,
            ];
            // dd($details);
            $to_Address                 = $lead_email;
            $reciever_Name              = $lead_first_name;
            
            if($request->token == config('token_Alif')){
                $email_Message              = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> Your Password has been Changed! Thank you for using our service. Please login with below details. <br><br> <ul> <li>Username: '.$details['email'].' </li> <li>Password: '.$request->new_Password.' </li> </ul> <br><br> Do you have any questions or require further assistance, please do not hesitate to contact us. <br> <br> Regards, <br> '.$website_Title.' </div>';
                // dd($email_Message);
                $mail_Check                 = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
            }
            
            if($mail_Check == 'Success'){
                return 'Success';
            }else{
                return 'Error';
            }
        }else{
            return 'Error';
        }
    }
    
    public function b2b_Agents_Update_Change_Password(Request $request){
        DB::beginTransaction(); 
        try {
            $b2b_Agents = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
            
            // dd($request->current_Password,$b2b_Agents->password);
            
            // if(Hash::check($request->current_Password, $b2b_Agents->password)){
                DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->update([
                    'password' => Hash::make($request->new_Password),
                ]);
                
                $mail_Send              = $this->Change_Password_Mail($request);
                // dd($mail_Send);
                
                DB::commit();
                return response()->json(['status'=>'success','message'=>'B2B Agent Details!','b2b_Agents'=>$b2b_Agents]);
            // }else{
            //     // dd('ELSE');
            //     DB::commit();
            //     return response()->json(['status'=>'error_ICP','message'=>'The current password is incorrect!']);
            // }
            
            // if (!Hash::check($request->current_Password, $b2b_Agents->password)) {    
            // }
            
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function approve_B2B_Agents(Request $request){
        DB::beginTransaction(); 
        try {
            DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->update(['approve_Status' => 1, 'reject_Status' => NULL]);
            $b2b_Agent_Details = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
            DB::commit();
            return response()->json(['status'=>'success','message'=>'B2B Agent Aprroved Successfully!','b2b_Agent_Details'=>$b2b_Agent_Details]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function disable_B2B_Agents(Request $request){
        DB::beginTransaction(); 
        try {
            DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->update(['approve_Status' => NULL]);
            $b2b_Agent_Details = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
            DB::commit();
            return response()->json(['status'=>'success','message'=>'B2B Agent Disable Successfully!','b2b_Agent_Details'=>$b2b_Agent_Details]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function reject_B2B_Agents(Request $request){
        DB::beginTransaction(); 
        try {
            $b2b_Agent_Details = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
                
            if($request->token == config('token_HaramaynRooms')){
                DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->update(['reject_Status' => 1]);
            }else{
                DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->delete();
            }
            // DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->update(['reject_Status' => 1]);
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'B2B Agent Rejected Successfully!','b2b_Agent_Details'=>$b2b_Agent_Details]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_B2B_Agents_Single(Request $request){
        DB::beginTransaction(); 
        try {
            $b2b_Agent_Details = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
            DB::commit();
            return response()->json(['status'=>'success','message'=>'B2B Agent Details!','b2b_Agent_Details'=>$b2b_Agent_Details]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function b2b_Agents_Profile(Request $req){
        $all_countries                          = country::all();
        $userData                               = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
            $agent_personal_details             = DB::table('b2b_agents')->where('id',$req->agent_id)->first();
            $all_suppliers                      = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->get();
            $currency_data                      = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            $agents_Lead_details                = DB::table('addLead')->where('customer_id',$userData->id)->where('b2b_agent_Id',$agent_personal_details->id)->first();
            
            // Hasanat Working
            $expire_Status   = 'Valid';
            if($agent_personal_details->package_Expiry_Date != null){
                $package_Expiry_Date    = Carbon::createFromFormat('d-m-Y', $agent_personal_details->package_Expiry_Date);
                $todayDate              = Carbon::now()->startOfDay();
                if ($package_Expiry_Date->lessThan($todayDate)) {
                    $expire_Status   = 'Expired';
                }
            }
            
            $selected_package                   = DB::table('subscriptions_Packages')->where('customer_id',$userData->id)
                                                    ->where('id',$agent_personal_details->select_Package)
                                                    ->first();
            // $hasanat_Credits                    = DB::table('hasanat_Credits')->where('customer_id',$userData->id)->where('selected_Client',$agent_personal_details->id)->sum('add_Credits');
            $todayDate                          = Carbon::now()->startOfDay();
            $hasanat_CreditsD                   = DB::table('hasanat_Credits')->where('customer_id',$userData->id)->where('selected_Client',$agent_personal_details->id)->get();
            // dd($hasanat_CreditsD);
            $hasanat_Credits    = 0;
            if($hasanat_CreditsD->isEmpty()){
                $hasanat_Credits = 0;
            }else{
                foreach($hasanat_CreditsD as $val_HC){
                    $credit_Expiry_Date    = Carbon::createFromFormat('d-m-Y', $val_HC->credit_Expiry_Date);
                    // dd($credit_Expiry_Date,$todayDate);
                    if ($credit_Expiry_Date->lessThan($todayDate)) {
                        // dd('IF');
                        $hasanat_Credits += 0;
                    }else{
                        // dd('ELSE');
                        $hasanat_Credits += $val_HC->add_Credits;
                    }
                }
            }
            
            $number_Of_Credits                  = 0;
            if($selected_package != null){
                $number_Of_Credits = $selected_package->number_Of_Credits;
            }
            $total_Credits                      = $number_Of_Credits + $hasanat_Credits;
            $custom_Booking_Hasanat_Credits     = DB::table('custom_Booking_Hasanat_Credits')
                                                    ->join('subscriptions_Packages','custom_Booking_Hasanat_Credits.package_Id','subscriptions_Packages.id')
                                                    ->join('hotels_bookings','custom_Booking_Hasanat_Credits.booking_Id','hotels_bookings.id')
                                                    ->where('custom_Booking_Hasanat_Credits.customer_id',$userData->id)
                                                    ->where('custom_Booking_Hasanat_Credits.b2b_agent_Id',$agent_personal_details->id)
                                                    ->get();
            $booked_Credits                     = DB::table('custom_Booking_Hasanat_Credits')->where('customer_id',$userData->id)->where('b2b_agent_Id',$agent_personal_details->id)
                                                    ->sum('booked_Credits');
            // $total_Credits                      = $agent_personal_details->total_Hasanat_Credits + $booked_Credits;
            
            // Credit Ledger
            $hasanat_Credit_Statament               = [];
            $custom_Booking_Hasanat_Credits         = DB::table('custom_Booking_Hasanat_Credits')->where('token',$req->token)->where('b2b_agent_Id',$agent_personal_details->id)->get();
            if($custom_Booking_Hasanat_Credits->isEmpty()){
            }else{
                foreach($custom_Booking_Hasanat_Credits as $val_HCS){
                    $client_Details                 = DB::table('b2b_agents')->where('token',$req->token)->where('id',$val_HCS->b2b_Agent_Id)->first();
                    $subscriptions_Packages         = DB::table('subscriptions_Packages')->where('token',$req->token)->where('id',$val_HCS->package_Id)->first();
                    $hotels_bookings                = DB::table('hotels_bookings')->where('invoice_no',$val_HCS->booking_Id)->first();
                    $hasanat_Credits_Status         = NULL;
                    $hasanat_Credits_Date           = NULL;
                    $hasanat_Credits                = DB::table('hasanat_Credits')->where('customer_id',$userData->id)->where('selected_Client',$agent_personal_details->id)->where('created_at',$val_HCS->created_at)->first();
                    if($hasanat_Credits != NULL){
                        $credit_Expiry_Date         = Carbon::createFromFormat('d-m-Y', $hasanat_Credits->credit_Expiry_Date);
                        if ($credit_Expiry_Date->lessThan($todayDate)) {
                            $hasanat_Credits_Status = 'Expired';
                            $hasanat_Credits_Date   = $hasanat_Credits->credit_Expiry_Date;
                        }else{
                            $hasanat_Credits_Status = 'Valid';
                            $hasanat_Credits_Date   = $hasanat_Credits->credit_Expiry_Date;
                        }
                    }
                    $hasanat_Credit_Statament_OBJ   = [
                        'credit_Booking'            => $val_HCS,
                        'client_Details'            => $client_Details,
                        'selected_Package'          => $subscriptions_Packages,
                        'hotels_bookings'           => $hotels_bookings,
                        'hasanat_Credits'           => $hasanat_Credits,
                        'hasanat_Credits_Status'    => $hasanat_Credits_Status,
                        'hasanat_Credits_Date'      => $hasanat_Credits_Date,
                    ];
                    array_push($hasanat_Credit_Statament,$hasanat_Credit_Statament_OBJ);
                }
            }
            // Credit Ledger
            
            $hasanat_Coins_3rd_Party_Booking    = DB::table('3rd_Party_Booking_Hasanat_Coins')
                                                    ->join('subscriptions_Packages','3rd_Party_Booking_Hasanat_Coins.package_Id','subscriptions_Packages.id')
                                                    ->join('hotels_bookings','3rd_Party_Booking_Hasanat_Coins.booking_Id','hotels_bookings.id')
                                                    ->join('discount_Coins','3rd_Party_Booking_Hasanat_Coins.discount_Id','discount_Coins.id')
                                                    ->where('3rd_Party_Booking_Hasanat_Coins.customer_id',$userData->id)
                                                    ->where('3rd_Party_Booking_Hasanat_Coins.b2b_agent_Id',$agent_personal_details->id)
                                                    ->get();
            $total_Coins                        = DB::table('3rd_Party_Booking_Hasanat_Coins')->where('customer_id',$userData->id)->where('b2b_agent_Id',$agent_personal_details->id)
                                                    ->sum('booked_Hasanat_Coins');
            // Hasanat Coins Ledger
            $hasanat_Coin_Statament             = [];
            $third_Party_Booking_Hasanat_Coins  = DB::table('3rd_Party_Booking_Hasanat_Coins')->where('customer_id',$userData->id)->where('b2b_agent_Id',$agent_personal_details->id)->get();
            if($third_Party_Booking_Hasanat_Coins->isEmpty()){
            }else{
                foreach($third_Party_Booking_Hasanat_Coins as $val_HCS){
                    $client_Details                 = DB::table('b2b_agents')->where('token',$req->token)->where('id',$val_HCS->b2b_Agent_Id)->first();
                    $subscriptions_Packages         = DB::table('subscriptions_Packages')->where('token',$req->token)->where('id',$val_HCS->package_Id)->first();
                    $hotels_bookings                = DB::table('hotels_bookings')->where('invoice_no',$val_HCS->booking_Id)->first();
                    
                    $hasanat_Coins_Statament_OBJ    = [
                        'coin_Booking'              => $val_HCS,
                        'client_Details'            => $client_Details,
                        'selected_Package'          => $subscriptions_Packages,
                        'hotels_bookings'           => $hotels_bookings,
                    ];
                    array_push($hasanat_Coin_Statament,$hasanat_Coins_Statament_OBJ);
                }
            }
            // Credit Ledger
            // Hasanat Working
            
            return response()->json([
                'status'                            => 'success',
                'agent_personal_details'            => $agent_personal_details,
                "currency_data"                     => $currency_data,
                'all_suppliers'                     => $all_suppliers,
                'all_countries'                     => $all_countries,
                'agents_Lead_details'               => $agents_Lead_details,
                'selected_package'                  => $selected_package,
                'custom_Booking_Hasanat_Credits'    => $custom_Booking_Hasanat_Credits,
                'total_Credits'                     => $total_Credits,
                'booked_Credits'                    => $booked_Credits,
                'expire_Status'                     => $expire_Status,
                'hasanat_Coins_3rd_Party_Booking'   => $hasanat_Coins_3rd_Party_Booking,
                'total_Coins'                       => $total_Coins,
                'hasanat_Credit_Statament'          => $hasanat_Credit_Statament,
                'hasanat_Coin_Statament'            => $hasanat_Coin_Statament,
            ]);
        }
    }
    
    public static function b2b_Agents_Booking_Statement_Season_Working($all_data,$request){
        $today_Date             = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->created_at)) {
                    if(!isset($record->check_in)){
                        return false;
                    }
                }
                
                if(!empty($record->check_in)){
                    $created_at = Carbon::parse($record->check_in);
                }
                
                if(!empty($record->created_at)){
                    $created_at = Carbon::parse($record->created_at);
                }
                
                if(isset($created_at)){
                    $start_Date     = Carbon::parse($start_Date);
                    $end_Date       = Carbon::parse($end_Date);
                    return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                }else{
                    return false;
                }
                
                // if($record->created_at != null && $record->created_at != ''){
                //     $created_at     = Carbon::parse($record->created_at);
                //     $start_Date     = Carbon::parse($start_Date);
                //     $end_Date       = Carbon::parse($end_Date);
                //     return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                // }else{
                //     return false;
                // }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function b2b_Agents_Booking_Statement(Request $request){
        // Invoice Booking
        $agent_invoices_all = DB::table('add_manage_invoices')->where('b2b_Agent_Id',$request->agent_id)
                                ->select('id','services','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC')
                                ->orderBy('created_at')->get();
        $agent_invoices     = [];
        foreach($agent_invoices_all as $inv_res){
            $accomodation_details = json_decode($inv_res->accomodation_details);
            $services = json_decode($inv_res->services);
            
            $check_in = '';
            if(isset($accomodation_details[0]->acc_check_in)){
                $check_in = $accomodation_details[0]->acc_check_in;
            }
            
            if(isset($services[0])){
                if($services[0] == 'visa_tab'){
                     $check_in = $inv_res->created_at;
                }
            }
            
            if(isset($services[0])){
                if($services[0] == 'transportation_tab'){
                     $check_in = $inv_res->created_at;
                }
            }
            
            $inv_res->check_in = $check_in;
            $agent_invoices[] = $inv_res;
        }
        
        // Package Booking
        $packages_booking_all   = DB::table('cart_details')->join('tours','tours.id','=','cart_details.tour_id')->where('b2b_agent_name',$request->agent_id)
                                    ->select('cart_details.id','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                                    ,'tours.accomodation_details','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date')
                                    ->orderBy('created_at')->get();
        
        $packages_booking       = [];
        foreach($packages_booking_all as $inv_res){
            $accomodation_details = json_decode($inv_res->accomodation_details);
            
            $check_in = '';
            if(isset($accomodation_details[0]->acc_check_in)){
                $check_in = $accomodation_details[0]->acc_check_in;
            }
            
            $inv_res->check_in = $check_in;
            $packages_booking[] = $inv_res;
        }
        
        $payments_data      = DB::table('recevied_payments_details')->where('Criteria','b2b_Agent')->orWhere('Criteria','B2B Agent')->where('Content_Ids',$request->agent_id)
                                ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data','exchange_rate')
                                ->orderBy('check_in')->get();
            
        $make_payments_data = DB::table('make_payments_details')->where('Criteria','b2b_Agent')->orWhere('Criteria','B2B Agent')->where('Content_Ids',$request->agent_id)
                                ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data','exchange_rate')
                                ->orderBy('check_in')->get();
                                
        $agent_invoices             = collect($agent_invoices);
        $packages_booking           = collect($packages_booking);
        $all_data                   = $agent_invoices->concat($packages_booking)->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
        $Agents_detail              = DB::table('b2b_agents')->where('id',$request->agent_id)->first();
        
        // Hotel Booking
        if($request->customer_id == 48 || $request->customer_id == 58){
            $hotels_bookings    = [];
            $b2b_Agent_HB       = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('b2b_agent_id',$request->agent_id)
                                    ->select('id','lead_passenger','invoice_no','status','base_currency','exchange_price','exchange_currency','base_exchange_rate','reservation_request','reservation_response','created_at','payment_details')
                                    ->get();
            foreach($b2b_Agent_HB as $val_HB){
                $reservation_response = json_decode($val_HB->reservation_response);
                if(isset($reservation_response->provider) && $reservation_response->provider == 'Custome_hotel'){
                    if(isset($reservation_response->hotel_details)){
                        $hotel_details      = $reservation_response->hotel_details;
                        if(isset($hotel_details->checkIn)){
                            $val_HB->check_in   = $hotel_details->checkIn;
                            $val_HB->hotel_Type = $reservation_response->provider;
                            $hotels_bookings[]  = $val_HB;
                        }
                    }
                }
            }
            
            $hotels_bookings            = collect($hotels_bookings);
            $all_data                   = $agent_invoices->concat($packages_booking)->concat($hotels_bookings)->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
        }
        // Hotel Booking
        
        $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        if($request->customer_id == 54){
            if($all_data->isEmpty()){
            }else{
                // return $all_data;
                $all_data   = $this->b2b_Agents_Booking_Statement_Season_Working($all_data,$request);
                // return $all_data;
            }
        }
        // Season
        
        // dd($all_data);
        return response()->json(['status'=>'success','Agents_detail'=>$all_data,'Agents_Pers_details'=>$Agents_detail,'season_Details'=>$season_Details]);
        
    }
    
    public function b2b_Agent_Booking_Statement_Ajax(Request $request){
        $startDate              = $request->start_date;
        $endDate                = $request->end_date;
        $customer_invoices_all  = DB::table('add_manage_invoices')->where('b2b_Agent_Id',$request->agent_id)
                                    ->select('id','services','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC')
                                    ->orderBy('created_at')->get();
                                    
        $customer_invoices      = [];
        foreach($customer_invoices_all as $inv_res){
            $accomodation_details = json_decode($inv_res->accomodation_details);
            $services = json_decode($inv_res->services);
            
            $check_in = '';
            if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                $check_in = $accomodation_details[0]->acc_check_in;
                $inv_res->check_in = $check_in;
                $customer_invoices[] = $inv_res;
            }
            
            if(isset($services[0])){
                if($services[0] == 'visa_tab' && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                    $inv_res->check_in = $inv_res->created_at;
                    $customer_invoices[] = $inv_res;
                }
            }
            
            if(isset($services[0])){
                if($services[0] == 'transportation_tab'  && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                    $inv_res->check_in = $inv_res->created_at;
                    $customer_invoices[] = $inv_res;
                }
            }
            
        }
        
        // Fetch data from table2
        $packages_booking_all   = DB::table('cart_details')
                                    ->join('tours','tours.id','=','cart_details.tour_id')->where('b2b_agent_name',$request->agent_id)
                                    ->select('cart_details.id','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                                        ,'tours.accomodation_details','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date')
                                    ->orderBy('created_at')->get();
        $packages_booking = [];
        foreach($packages_booking_all as $inv_res){
            $accomodation_details = json_decode($inv_res->accomodation_details);
            
            $check_in = '';
            if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                $check_in = $accomodation_details[0]->acc_check_in;
                $inv_res->check_in = $check_in;
                $packages_booking[] = $inv_res;
            }
            
        }
        
        $payments_data      = DB::table('recevied_payments_details')
                                // ->where('Criteria','b2b_Agent')
                                ->where(function ($query) {
                                    $query->where('Criteria', 'b2b_Agent')
                                          ->orWhere('Criteria', 'B2B Agent');
                                })
                                ->where('Content_Ids',$request->agent_id)
                                ->whereDate('payment_date','>=', $startDate)->whereDate('payment_date','<=', $endDate)
                                ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                ->orderBy('check_in')->get();
            
        $make_payments_data = DB::table('make_payments_details')
                                // ->where('Criteria','b2b_Agent')
                                ->where(function ($query) {
                                    $query->where('Criteria', 'b2b_Agent')
                                          ->orWhere('Criteria', 'B2B Agent');
                                })
                                ->where('Content_Ids',$request->agent_id)
                                ->whereDate('payment_date','>=', $startDate)->whereDate('payment_date','<=', $endDate)
                                ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                ->orderBy('check_in')->get();
        // dd($payments_data);
        $customer_invoices  = collect($customer_invoices);
        $packages_booking   = collect($packages_booking);
        $all_data           = $customer_invoices->concat($packages_booking)->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
        $Agents_detail      = DB::table('b2b_agents')->where('id',$request->agent_id)->first();
        
        // Hotel Booking
        if($request->customer_id == 48 || $request->customer_id == 58 || $request->customer_id == 59 || $request->customer_id == 60){
            $hotels_bookings    = [];
            $b2b_Agent_HB       = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('b2b_agent_id',$request->agent_id)
                                    ->select('id','lead_passenger','invoice_no','status','base_currency','exchange_price','exchange_currency','base_exchange_rate','reservation_request','reservation_response','created_at','payment_details')
                                    ->get();
            foreach($b2b_Agent_HB as $val_HB){
                $reservation_response = json_decode($val_HB->reservation_response);
                if(isset($reservation_response->provider) && $reservation_response->provider == 'Custome_hotel'){
                    if(isset($reservation_response->hotel_details)){
                        $hotel_details      = $reservation_response->hotel_details;
                        if(isset($hotel_details->checkIn)){
                            if(isset($hotel_details->checkIn) && $hotel_details->checkIn >= $startDate && $hotel_details->checkIn <= $endDate){
                                $val_HB->check_in       = $hotel_details->checkIn;
                                $val_HB->hotel_Type     = $reservation_response->provider;
                                $hotels_bookings[]      = $val_HB;
                            }
                        }
                    }
                }
            }
            
            $hotels_bookings            = collect($hotels_bookings);
            $all_data                   = $customer_invoices->concat($packages_booking)->concat($hotels_bookings)->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
        }
        // Hotel Booking
        
        return response()->json(['status'=>'success','booking_statement_data'=>$all_data,'customer_details'=>$Agents_detail]);
    }
    
    public function b2b_Agent_Transfer_Statement(Request $request){
        $startDate              = $request->start_date;
        $endDate                = $request->end_date;
        $Agents_detail          = DB::table('b2b_agents')->where('id',$request->agent_id)->first();
        $all_data               = [];
        // if($request->customer_id == 60){
            $transferBooking    = [];
            $transferBooking    = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->where('b2b_agent_id',$request->agent_id)
                                    ->where('departure_date', '>=', $request->start_date)->where('departure_date', '<=', $request->end_date)
                                    ->get();
            $all_data           = collect($transferBooking);
        // }
        return response()->json(['status'=>'success','booking_statement_data'=>$all_data,'customer_details'=>$Agents_detail]);
    }
    
    public function b2b_Agent_Payments_List_Ajax_Season_Working($payments_list,$request){
        $today_Date     = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $payments_list;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
            }else{
                return $payments_list;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            $filtered_data      = collect($payments_list)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->payment_date)) {
                    return false;
                }
                $payment_date   = Carbon::parse($record->payment_date);
                return $payment_date->between($start_Date, $end_Date) || ($payment_date->lte($start_Date) && $payment_date->gte($end_Date));
            });
            
            $payments_list = $filtered_data;
        }
        return $payments_list;
    }
    
    public function b2b_Agent_Payments_List_Ajax(Request $request){
        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list          = DB::table($request->table_name)->where('Criteria',$request->criteria)->where('Content_Ids',$request->Content_Ids)->orderBy('payment_date','asc')->get();
            $customer_data          = DB::table($request->person_detail_table)->where('id',$request->Content_Ids)->first();
            $currency_data          = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            $hotels_bookings        = '';
            $customer_subcriptions  = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_subcriptions->id == 48 ||$customer_subcriptions->id == 58 ||$customer_subcriptions->id == 59 ||$customer_subcriptions->id == 60){
                $hotels_bookings        = DB::table('hotels_bookings')->where('customer_id',$customer_subcriptions->id)->where('b2b_agent_id',$request->Content_Ids)->where('status','Confirmed')->get();
                // dd($hotels_bookings);
            }
            
            $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->get();
            if($userData->id == 54){
                // dd($payments_list);
                $payments_list      = $this->b2b_Agent_Payments_List_Ajax_Season_Working($payments_list,$request);
                // dd($payments_list);
            }
            
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
                'customer_data'     => $customer_data,
                'currency_data'     => $currency_data,
                'hotels_bookings'   => $hotels_bookings,
                'season_Details'    => $season_Details,
            ]);                         
        }
    }
    
    public function b2b_Agent_Hotel_Supplier_Report(Request $request){
        $request_data =  json_decode($request->request_data);
      
        $start_date = '';
        $end_date   = '';
        
        if($request_data->report_type == 'data_wise'){
            $start_date = $request_data->check_in;
            $end_date   = $request_data->check_out;
        }
        
        if($request_data->report_type == 'data_today_wise'){
            $start_date = date('Y-m-d');
            $end_date   = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_tomorrow_wise'){
            $start_date = date('Y-m-d',strtotime("+1 days"));
            $end_date   = date('Y-m-d',strtotime("+1 days"));
        }
        
        if($request_data->report_type == 'data_week_wise'){
            $startOfWeek    = Carbon::now()->startOfWeek();
            $start_date     = $startOfWeek->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfWeek();
            $end_date       = $endOfWeek->format('Y-m-d');
            // dd($start_date,$end_date,$request_data);
            // $end_date       = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_month_wise'){
            $startOfMonth   = Carbon::now()->startOfMonth();
            $start_date     = $startOfMonth->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfMonth();
            $end_date       = $endOfWeek->format('Y-m-d');
            // $end_date       = date('Y-m-d');
        }
      
        //********************************************
        // Invoice Working
        //********************************************
      
        $invoices_query                 = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id);
        $invoice_select_common_element  = ['all_services_quotation','generate_id','confirm','accomodation_details','accomodation_details_more','f_name','middle_name','created_at','id','lead_fname','lead_lname'];
        
        if($request_data->person_report_type == 'b2b_AgentWise'){
            $invoices_query->select('id','b2b_Agent_Id','b2b_Agent_Name','b2b_Agent_Company_Name',...$invoice_select_common_element);
            if($request_data->agent_Id == 'all_agent'){
                $invoices_query->where('b2b_Agent_Id','>',0);
            }
          
            if($request_data->agent_Id != 'all_agent'){
                $invoices_query->where('b2b_Agent_Id',$request_data->agent_Id);
            }
        }
        
        $invoices_data = $invoices_query->get();
      
        $aray_data = [];
        foreach($invoices_data as $invoice_res){
            
            $invoice_Supplier_Name = '';
            
            if($request_data->report_type == 'all_data'){
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->more_hotel_room_view) && $arr_hotel->more_hotel_room_view != null && $arr_hotel->more_hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->more_hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                    'room_type'=>$arr_hotel->more_acc_type,
                                    'room_type'=>$arr_hotel->more_hotel_type_cat ?? $arr_hotel->more_acc_type ?? '',
                                    'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                    'hotel_room_view'=>$hotel_room_view ?? '',
                                    'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'=>$arr_hotel->more_acc_qty,
                                    'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                    'all_services_quotation'=> $invoice_res->all_services_quotation,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        // 'room_type'=>$arr_hotel->more_acc_type,
                                        'room_type'=> $arr_hotel->more_hotel_type_cat ?? $arr_hotel->more_acc_type ?? '',
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->hotel_room_view) && $arr_hotel->hotel_room_view != null && $arr_hotel->hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'=> $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                    // 'room_type'=> $arr_hotel->acc_type,
                                    'room_type'=> $arr_hotel->hotel_type_cat ?? $arr_hotel->acc_type,
                                    'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                    'hotel_room_view'=>$hotel_room_view ?? '',
                                    'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                    'quantity'=> $arr_hotel->acc_qty,
                                    'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                    'all_services_quotation'=> $invoice_res->all_services_quotation,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        // 'room_type'=> $arr_hotel->acc_type,
                                        'room_type'=> $arr_hotel->hotel_type_cat ?? $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }
                    }
                }
                
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->more_hotel_room_view) && $arr_hotel->more_hotel_room_view != null && $arr_hotel->more_hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->more_hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        // 'room_type'=>$arr_hotel->more_acc_type,
                                        'room_type'=>$arr_hotel->more_hotel_type_cat ?? $arr_hotel->more_acc_type ?? '',
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            // 'room_type'=>$arr_hotel->more_acc_type,
                                            'room_type'=>$arr_hotel->more_hotel_type_cat ?? $arr_hotel->more_acc_type ?? '',
                                            'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                            'hotel_room_view'=>$hotel_room_view ?? '',
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$arr_hotel->more_acc_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                            'all_services_quotation'=> $invoice_res->all_services_quotation,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                      
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->hotel_room_view) && $arr_hotel->hotel_room_view != null && $arr_hotel->hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        // 'room_type'=> $arr_hotel->acc_type,
                                        'room_type'=> $arr_hotel->hotel_type_cat ?? $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            // 'room_type'=> $arr_hotel->acc_type,
                                            'room_type'=> $arr_hotel->hotel_type_cat ?? $arr_hotel->acc_type,
                                            'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                            'hotel_room_view'=>$hotel_room_view ?? '',
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $arr_hotel->acc_qty,
                                            'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                            'all_services_quotation'=> $invoice_res->all_services_quotation,
                                        ];
                                    }
                                }
                             
                            }
                        }
                    }
                }
            }
        }
        
        // dd($aray_data);
      
        //********************************************
        //Package Working
        //********************************************
      
        $invoices_query = DB::table('cart_details')->where('client_id',$request->customer_id)
                            ->join('tours','tours.id','=','cart_details.tour_id')
                            ->join('tours_bookings','tours_bookings.id','=','cart_details.booking_id');
                            
        $invoice_select_common_element = ['cart_details.b2b_Agent_name as agent_Id','cart_details.invoice_no as generate_id','confirm','tours.accomodation_details','tours.accomodation_details_more','tours_bookings.passenger_name as f_name','cart_details.created_at','cart_details.cart_total_data'];
        $invoices_query->select('tours.id as tour_id',...$invoice_select_common_element);
        if($request_data->person_report_type == 'b2b_AgentWise'){
            if($request_data->agent_Id == 'b2b_Agent_Id'){
                $invoices_query->where('cart_details.b2b_Agent_name','>',0);
            }
            
            if($request_data->agent_Id != 'b2b_Agent_Id'){
                $invoices_query->where('cart_details.b2b_Agent_name',$request_data->agent_Id);
            }
        }
      
        $invoices_data = $invoices_query->get();
        
        foreach($invoices_data as $invoice_res){
            $invoice_res->confirm = "confirm";
            if($request_data->report_type == 'all_data'){
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->tour_id,
                                    'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                    'room_type'=>$arr_hotel->more_acc_type,
                                    'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'=>$room_qty,
                                    'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name,
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> '',
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'Package',
                                    'status'=> $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$room_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->tour_id,
                                    'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'=> $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                    'room_type'=> $arr_hotel->acc_type,
                                    'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                    'quantity'=> $room_qty,
                                    'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name,
                                    'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $invoice_res->agent_Company_Name ?? '',
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'Package',
                                    'status'=> $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $room_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$room_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            'room_type'=>$arr_hotel->more_acc_type,
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $room_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                            }
                         }
                     }
                }
            }
        }
        
        //********************************************
        // Hotel Working
        //********************************************
        if($request->customer_id == 48 || $request->customer_id == 58 || $request->customer_id == 59 || $request->customer_id == 60){
            $aray_data          = [];
            
            // if($person_report_type != '' && $person_report_type == 'B2BAgentWise'){
            //     $hotel_Booking_Data = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('b2b_agent_id',$request_data->b2b_agent_id)
            //                             ->select('id','invoice_no','b2b_agent_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
            //                             ->orderBy('hotels_bookings.id','asc')
            //                             ->get();
            // }else{
                $hotel_Booking_Data = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('b2b_agent_id','!=',NULL)
                                        ->select('id','invoice_no','b2b_agent_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
                                        ->orderBy('hotels_bookings.id','asc')
                                        ->get();
            // }
            
            foreach($hotel_Booking_Data as $val_HB){
                $invoice_Supplier_Name = '';
                
                if(isset($val_HB->reservation_response) && $val_HB->reservation_response != 'null' && $val_HB->reservation_response != NULL){
                    $reservation_request    = json_decode($val_HB->reservation_request);
                    $reservation_response   = json_decode($val_HB->reservation_response);
                    if(isset($reservation_response->hotel_details) && $reservation_response->hotel_details != 'null' && $reservation_response->hotel_details != NULL){
                    $hotel_details          = $reservation_response->hotel_details;
                    if(isset($hotel_details->rooms) && $hotel_details->rooms != 'null' && $hotel_details->rooms != NULL){
                        $rooms              = $hotel_details->rooms;
                            foreach($rooms as $arr_hotel){
                                // dd($arr_hotel->room_name);
                                
                                if(isset($request_data->supplier) && $request_data->supplier != null && $request_data->supplier != '' && $request_data->supplier != 'all_Suppliers'){
                                    $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$request_data->supplier)->select('room_supplier_name')->first();
                                }
                                
                                if($reservation_response->provider == 'Custome_hotel'){
                                    $hotel_Data     = DB::table('hotels')->where('id',$hotel_details->hotel_code)->first();
                                    $room_Details   = DB::table('rooms')->where('id',$arr_hotel->room_code)->first();
                                    
                                    if($room_Details != null){
                                        if(isset($room_Details->room_supplier_name) && $room_Details->room_supplier_name != null && $room_Details->room_supplier_name != '' && $room_Details->room_supplier_name != 'Select One'){
                                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$room_Details->room_supplier_name)->select('room_supplier_name')->first();
                                        }else{
                                            if(isset($room_Details->room_supplier_name_AR) && $room_Details->room_supplier_name_AR != null && $room_Details->room_supplier_name_AR != '' && $room_Details->room_supplier_name_AR != 'Select One'){
                                                $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$room_Details->room_supplier_name_AR)->select('room_supplier_name')->first();
                                            }
                                        }
                                    }
                                }
                                
                                $adults     = $arr_hotel->adults ?? 0;
                                $childs     = $arr_hotel->childs ?? 0;
                                $quantity   = $adults + $childs;
                                $b2b_agents = DB::table('b2b_agents')->where('id',$val_HB->b2b_agent_id)->first();
                                
                                $first_name = $b2b_agents->first_name ?? '';
                                $last_name  = $b2b_agents->last_name ?? '';
                                $agent_Name = $first_name .' '. $last_name;
                                
                                if($request_data->supplier == 'all'){
                                    if($start_date != '' && $end_date != ''){
                                        if($hotel_details->checkIn >= $start_date && $hotel_details->checkIn <= $end_date){
                                            $aray_data[]                    = (object)[
                                                'invoice_no'                => $val_HB->invoice_no,
                                                'invoice_id'                => $val_HB->id,
                                                'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                'hotel_name'                => $hotel_details->hotel_name,
                                                'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                'reservation_no'            => $arr_hotel->room_code ?? '',
                                                'quantity'                  => $quantity,
                                                'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                                'agent_id'                  => $b2b_agents->id ?? '',
                                                'agent_name'                => $agent_Name,
                                                'agent_company_name'        => $b2b_agents->company_name ?? $agent_Name,
                                                'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                'booking_type'              => 'Hotel Booking',
                                                'status'                    => $val_HB->status,
                                                'all_services_quotation'    => 'NO',
                                            ];
                                        }
                                    }else{
                                        $aray_data[]                    = (object)[
                                            'invoice_no'                => $val_HB->invoice_no,
                                            'invoice_id'                => $val_HB->id,
                                            'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                            'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                            'hotel_name'                => $hotel_details->hotel_name,
                                            'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                            'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                            'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                            'reservation_no'            => $arr_hotel->room_code ?? '',
                                            'quantity'                  => $quantity,
                                            'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                            'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                            'agent_id'                  => $b2b_agents->id ?? '',
                                            'agent_name'                => $agent_Name,
                                            'agent_company_name'        => $b2b_agents->company_name ?? $agent_Name,
                                            'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                            'booking_type'              => 'Hotel Booking',
                                            'status'                    => $val_HB->status,
                                            'all_services_quotation'    => 'NO',
                                        ];
                                    }
                                }else{
                                    if(isset($room_Details->room_supplier_name)){
                                        if($request_data->supplier == $room_Details->room_supplier_name){
                                            if($start_date != '' && $end_date != ''){
                                                if($hotel_details->checkIn >= $start_date && $hotel_details->checkIn <= $end_date){
                                                    $aray_data[]                    = (object)[
                                                        'invoice_no'                => $val_HB->invoice_no,
                                                        'invoice_id'                => $val_HB->id,
                                                        'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                        'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                        'hotel_name'                => $hotel_details->hotel_name,
                                                        'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                        'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                        'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                        'reservation_no'            => $arr_hotel->room_code ?? '',
                                                        'quantity'                  => $quantity,
                                                        'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                        'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                        'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                                        'agent_id'                  => $b2b_agents->id ?? '',
                                                        'agent_name'                => $agent_Name,
                                                        'agent_company_name'        => $b2b_agents->company_name ?? $agent_Name,
                                                        'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                        'booking_type'              => 'Hotel Booking',
                                                        'status'                    => $val_HB->status,
                                                        'all_services_quotation'    => 'NO',
                                                    ];
                                                }
                                            }else{
                                                $aray_data[]                    = (object)[
                                                    'invoice_no'                => $val_HB->invoice_no,
                                                    'invoice_id'                => $val_HB->id,
                                                    'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                    'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                    'hotel_name'                => $hotel_details->hotel_name,
                                                    'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                    'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                    'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                    'reservation_no'            => $arr_hotel->room_code ?? '',
                                                    'quantity'                  => $quantity,
                                                    'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                    'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                    'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                                    'agent_id'                  => $b2b_agents->id ?? '',
                                                    'agent_name'                => $agent_Name,
                                                    'agent_company_name'        => $b2b_agents->company_name ?? $agent_Name,
                                                    'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                    'booking_type'              => 'Hotel Booking',
                                                    'status'                    => $val_HB->status,
                                                    'all_services_quotation'    => 'NO',
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->get();
        if($request->customer_id == 4 || $request->customer_id == 54){
            // dd($aray_data);
            $aray_data      = $this->b2b_Agent_Hotel_Supplier_Report_Season_Working($aray_data,$request);
            // dd($aray_data);
        }
        
        return response()->json(['hotel_supplier'=>$aray_data]);
    }
    
    public function b2b_Agent_Hotel_Supplier_Report_Season_Working($all_data,$request){
        // dd($request->season_Id);
        
        $today_Date     = date('Y-m-d');
        
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
                // dd('else if');
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        // dd($season_Details);
        
        if($season_Details != null){
            $start_Date = $season_Details->start_Date;
            $end_Date   = $season_Details->end_Date;
            
            $filtered_data = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->check_in)) {
                    return false;
                }
                
                if($record->check_in != null && $record->check_in != ''){
                    $checkIn    = Carbon::parse($record->check_in);
                    $checkOut   = isset($record->check_out) ? Carbon::parse($record->check_out) : $checkIn;
                    return $checkIn->between($start_Date, $end_Date) || $checkOut->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkOut->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            // $all_data = $filtered_data;
            $all_data = array_values($filtered_data->toArray());
        }
        return $all_data;
    }
    
    public function b2b_Agent_Hotel_Supplier_Report_Sub_User(Request $request){
        $request_data =  json_decode($request->request_data);
        
        dd($request_data);
        
        if($request_data->report_type == 'all_data'){
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
                $allgetsuppliers    = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get(); 
                $hotel_supplier     = DB::table('add_manage_invoices')
                                        ->where('customer_id',$request->customer_id)
                                        ->where('SU_id',$request->SU_id)
                                        ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                                        'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                                        'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                                        ->get();
                // dd($allgetsuppliers);
                
                $aray_data=array();
                foreach($hotel_supplier as $hotel){
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL){
                    //   $accomodation_details=json_decode($hotel->accomodation_details);
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                    //   $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if(isset($arr_hotel->more_hotel_supplier_id))
                           {
                        foreach($allgetsuppliers as $suppliers)
                        {
                           if($suppliers->id == $arr_hotel->more_hotel_supplier_id)
                           {
                               
                           
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                            'room_type'=>$arr_hotel->more_acc_type,
                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=>$arr_hotel->more_acc_qty,
                            'supplier'=>$arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                           }
                        }
                      }
                    }
                    }
                    
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL){
                        $accomodation_details=json_decode($hotel->accomodation_details);
                        foreach($accomodation_details as $arr_hotel){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                foreach($allgetsuppliers as $suppliers){
                                    if($suppliers->id == $arr_hotel->hotel_supplier_id){  
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $hotel->generate_id,
                                            'invoice_id'=>$hotel->id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $arr_hotel->acc_qty,
                                            'supplier'=> $arr_hotel->hotel_supplier_id,
                                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                            'agent_id'=> $hotel->agent_Id,
                                            'agent_name'=> $hotel->agent_Name,
                                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $hotel->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                return response()->json(['hotel_supplier'=>$aray_data]);
            }
          
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent'){
               
               
                          $hotel_supplier = DB::table('add_manage_invoices')
                          ->where('customer_id',$request->customer_id)
                          ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL 
                && isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL
                )
                {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                  $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                  foreach($array_merge as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                       'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                       
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                else
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                }
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);   
         
           }
           
            if($request_data->supplier != 'all' && $request_data->agent_Id == 'all_agent'){
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
            else{
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
        }
      
        if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise' || $request_data->report_type == 'data_week_wise'|| $request_data->report_type == 'data_month_wise'){
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
                 
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                                            
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise')
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_in == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise')
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_in == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                             'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
             }
            
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent'){
                 
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                  
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_in == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_in == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
             } 
            else{
                  
                  
                  
                  
                  
                  
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                 
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_in == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_in == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
                  
                  
                 
              }
        }
        
        if($request_data->report_type == 'data_wise'){
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                    if($arr_hotel->acc_check_in >= $request_data->check_in && $arr_hotel->acc_check_in <= $request_data->check_out)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_in >= $request_data->check_in && $arr_hotel->more_acc_check_in <= $request_data->check_out
                     
                      )
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=>  date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
         }
            
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent')
            {
               
        $hotel_supplier = DB::table('add_manage_invoices')
        ->where('customer_id',$request->customer_id)
        ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                     
                    if($arr_hotel->acc_check_in >= $request_data->check_in && $arr_hotel->acc_check_in <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_in >= $request_data->check_in && $arr_hotel->more_acc_check_in <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
         
           }
            else
            {
                $hotel_supplier = DB::table('add_manage_invoices')
                ->where('customer_id',$request->customer_id)
                ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if(isset($arr_hotel->acc_check_in) && isset($arr_hotel->acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->hotel_supplier_id))
                      {
                    if($arr_hotel->acc_check_in >= $request_data->check_in && $arr_hotel->acc_check_in <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }
                  }
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->more_acc_check_in) && isset($arr_hotel->more_acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->more_hotel_supplier_id))
                      {
                      if($arr_hotel->more_acc_check_in >= $request_data->check_in && $arr_hotel->more_acc_check_in <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $arr_hotel->more_hotel_supplier_id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                      }
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]); 
         }
      }
    }
    
    public function b2b_Agent_Profile_Bookings(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $all_customers_data     = [];
        if($userData){
            $all_customers_data = $this->b2b_agent_all_bookings_details($request->agent_id,$request);
        }
        $graph_data             = $this->createAgentYearlyGraph($request->agent_id,$request);
        return response()->json(['message'=>'success',
            'agent_data'        => $all_customers_data,
            'graph_data'        => $graph_data
        ]);
    }
    
    public static function b2b_agent_all_bookings_details_Season_Working($all_data,$request){
        $today_Date             = date('Y-m-d');
        // $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record['created_at'])) {
                    return false;
                }
                
                if($record['created_at'] != null && $record['created_at'] != ''){
                    $created_at     = Carbon::parse($record['created_at']);
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
    
    public static function b2b_agent_all_bookings_details($agent_id,$request){
        $agent_lists = DB::table('b2b_agents')->where('id',$agent_id)->get();
        // dd($agent_lists);
        $all_agent_data = [];
        foreach($agent_lists as $agent_res){
            $agent_tour_booking     = DB::table('cart_details')->where('b2b_agent_name',$agent_id)->get();
            $agent_invoice_booking  = DB::table('add_manage_invoices')->where('b2b_agent_Id',$agent_id)->get();
            
            $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_id)->get();
            $transfer_Booking       = DB::table('transfers_new_booking')->where('b2b_agent_id',$agent_id)->get();
            
            // dd($agent_tour_booking,$agent_invoice_booking);
            // dd($hotel_Booking,$transfer_Booking);
            //  print_r($agent_tour_booking);die;
            
            $booking_all_data = [];
            foreach($agent_tour_booking as $tour_res){
                $tours_costing = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();
                $booking_details    = DB::table('tours_bookings')->where('invoice_no',$tour_res->invoice_no)->first();
                //  print_r($tours_costing);
                
                $cart_all_data  = json_decode($tour_res->cart_total_data);
                $grand_profit   = 0;
                $grand_cost     = 0;
                $grand_sale     = 0;
                // Profit From Double Adults
                 
                    if($cart_all_data->double_adults > 0){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_adult_total_cost;
                        $grand_sale += $cart_all_data->double_adult_total;
                    }
                 
                // Profit From Triple Adults
                 
                    if($cart_all_data->triple_adults > 0){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_adult_total_cost;
                        $grand_sale += $cart_all_data->triple_adult_total;
                    }
                    
    
                 // Profit From Quad Adults
                 
                    if($cart_all_data->quad_adults > 0){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_adult_total_cost;
                        $grand_sale += $cart_all_data->quad_adult_total;
                    }
                 
                 // Profit From Without Acc
                 
                    if($cart_all_data->without_acc_adults > 0){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_adult_total_cost;
                        $grand_sale += $cart_all_data->without_acc_adult_total;
                    }
                 
                 // Profit From Double Childs
                 
                    if($cart_all_data->double_childs > 0){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_child_total_cost;
                        $grand_sale += $cart_all_data->double_childs_total;
                    }
                 
                 // Profit From Triple Childs
                 
                   if($cart_all_data->triple_childs > 0){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_child_total_cost;
                        $grand_sale += $cart_all_data->triple_childs_total;
                    }
                    
                 // Profit From Quad Childs
                 
                    if($cart_all_data->quad_childs > 0){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                        $grand_profit += $quad_profit;
                        $grand_cost += $quad_child_total_cost;
                        $grand_sale += $cart_all_data->quad_child_total;
                    }
                 
                 // Profit From Without Acc Child
    
                    if($cart_all_data->children > 0){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_child_total_cost;
                        $grand_sale += $cart_all_data->without_acc_child_total;
                    }
    
                // Profit From Double Infant
                    if($cart_all_data->double_infant > 0){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                        $grand_profit += $double_profit;
                         $grand_cost += $double_infant_total_cost;
                        $grand_sale += $cart_all_data->double_infant_total;
                    }
                 
                 // Profit From Triple Infant
                 
                    if($cart_all_data->triple_infant > 0){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                        $grand_profit += $triple_profit;
                         $grand_cost += $triple_infant_total_cost;
                        $grand_sale += $cart_all_data->triple_infant_total;
                    }
                 
                 // Profit From Quad Infant
                 
                    if($cart_all_data->quad_infant > 0){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_infant_total_cost;
                        $grand_sale += $cart_all_data->quad_infant_total;
                    }
                 
                 // Profit From Without Acc Infant  
                 
                  if($cart_all_data->infants > 0){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_infant_total_cost;
                        $grand_sale += $cart_all_data->without_acc_infant_total;
                  }
                  
                  $over_all_dis = 0;
                //   echo "Grand Total Profit is $grand_profit "; 
                  if($cart_all_data->discount_type == 'amount'){
                      $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                  }else{
                       $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                       $final_profit = $grand_profit - $discunt_am_over_all;
                  }
                 
                  
                 
                //  echo "Grand Total Profit is $final_profit";
                //  print_r($cart_all_data);
                
                $commission_add = '';
                if(isset($cart_all_data->customer_commsion_add_total)){
                    $commission_add = $cart_all_data->customer_commsion_add_total;
                }
    
                 $booking_data = [
                        'booking_type' => 'Package',
                        'invoice_id'=>$tour_res->invoice_no,
                        'booking_id'=>$tour_res->booking_id,
                        'tour_id'=>$tour_res->tour_id,
                        'price'=>$tour_res->tour_total_price,
                        'paid_amount'=>$tour_res->total_paid_amount,
                        'remaing_amount'=> $tour_res->tour_total_price - $tour_res->total_paid_amount,
                        'over_paid_amount'=> $tour_res->over_paid_amount,
                        'tour_name'=>$cart_all_data->name,
                        'profit'=>$final_profit,
                        'discount_am'=>$cart_all_data->discount_Price,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'created_at'=>$tour_res->created_at,
                        'commission_am'=>'',
                        'customer_commsion_add_total'=>$commission_add,
                        'currency'=>$tour_res->currency,
                        'confirm'=>$tour_res->confirm,
                        'lead_Name'=>$booking_details->passenger_name,
                        'passenger_detail'=>$booking_details->passenger_detail,
                     ];
                     
                  array_push($booking_all_data,$booking_data);
            }
            
            $invoices_all_data = [];
            foreach($agent_invoice_booking as $inv_res){
                
                $accomodation           = json_decode($inv_res->accomodation_details);
                $accomodation_more      = json_decode($inv_res->accomodation_details_more);
                $markup_details         = json_decode($inv_res->markup_details);
                $more_markup_details    = json_decode($inv_res->more_markup_details);
                
                // Caluclate Flight Price 
                $grand_cost             = 0;
                $grand_sale             = 0;
                $flight_cost            = 0;
                $flight_sale            = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                        $flight_cost = $mark_res->without_markup_price; 
                        $flight_sale = $mark_res->markup_price; 
                    }
                }
                
                $flight_total_cost      = (float)$flight_cost * (float)$inv_res->no_of_pax_days;
                $flight_total_sale      = (float)$flight_sale * (float)$inv_res->no_of_pax_days;
                $flight_profit          = $flight_total_sale - $flight_total_cost;
                
                $grand_cost             += $flight_total_cost;
                $grand_sale             += $flight_total_sale;
                
                // Caluclate Visa Price 
                $visa_cost = 0;
                $visa_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                        $visa_cost      = $mark_res->without_markup_price; 
                        $visa_sale      = $mark_res->markup_price; 
                    }
                }
                $visa_total_cost        = (float)$visa_cost * (float)$inv_res->no_of_pax_days;
                $visa_total_sale        = (float)$visa_sale * (float)$inv_res->no_of_pax_days;
                $visa_profit            = $visa_total_sale - $visa_total_cost;
                $grand_cost             += $visa_total_cost;
                $grand_sale             += $visa_total_sale;
                
                // Caluclate Transportation Price
                $trans_cost = 0;
                $trans_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                        $trans_cost     = $mark_res->without_markup_price; 
                        $trans_sale     = $mark_res->markup_price; 
                    }
                }
                $trans_total_cost       = (float)$trans_cost * (float)$inv_res->no_of_pax_days;
                $trans_total_sale       = (float)$trans_sale * (float)$inv_res->no_of_pax_days;
                $trans_profit           = $trans_total_sale - $trans_total_cost;
                $grand_cost             += $trans_total_cost;
                $grand_sale             += $trans_total_sale;
                
                // Caluclate Double Room Price
                $double_total_cost = 0;
                $double_total_sale = 0;
                $double_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Double'){
                            (float)$double_cost = $accmod_res->acc_total_amount; 
                            (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                            
                            $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                            $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                            $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                            $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Double'){
                            $double_cost = $accmod_res->more_acc_total_amount; 
                            $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                            $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                            $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                            $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                $grand_cost             += $double_total_cost;
                $grand_sale             += $double_total_sale;
                
                // Caluclate Triple Room Price
                $triple_total_cost = 0;
                $triple_total_sale = 0;
                $triple_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->acc_total_amount; 
                            $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                            $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                $grand_cost             += $triple_total_cost;
                $grand_sale             += $triple_total_sale;
                
                // Caluclate Quad Room Price
                $quad_total_cost = 0;
                $quad_total_sale = 0;
                $quad_total_profit = 0;
                if(isset($accomodation)){
                            foreach($accomodation as $accmod_res){
                                if($accmod_res->acc_type == 'Quad'){
                                    $quad_cost = $accmod_res->acc_total_amount; 
                                    $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                    
                                     $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                     $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                     $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                     $quad_total_profit = $quad_total_profit + $quad_profit;
                                }
                            }
                         }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Quad'){
                            $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                            $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                            $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                            $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                            $quad_total_profit = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                $grand_cost             += $quad_total_cost;
                $grand_sale             += $quad_total_sale;
                
                // Caluclate Final Price
                $Final_inv_price        = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                $Final_inv_profit       = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                $select_curreny_data    = explode(' ', $inv_res->currency_conversion);
                $invoice_curreny        = "";
                $customer_curreny_data = explode(' ', $inv_res->currency_Type_AC);
                if(isset($select_curreny_data[2])){
                    $invoice_curreny    = $select_curreny_data[2];
                }
                
                $customer_curreny       = $invoice_curreny;
                $customer_curreny_data  = explode(' ', $inv_res->currency_Type_AC);
                if(isset($customer_curreny_data[2])){
                    $customer_curreny   = $customer_curreny_data[2];
                }
                
                $profit                 = $inv_res->total_sale_price_all_Services - $inv_res->total_cost_price_all_Services ?? $grand_cost;
                $inv_single_data        = [
                    'booking_type'      => 'Invoice',
                    'invoice_id'        => $inv_res->id,
                    'price'             => $inv_res->total_sale_price_all_Services,
                    'paid_amount'       => $inv_res->total_paid_amount,
                    'remaing_amount'    => $inv_res->total_sale_price_all_Services - $inv_res->total_paid_amount,
                    'over_paid_amount'  => $inv_res->over_paid_amount,
                    'profit'            => $profit,
                    'total_cost'        => $inv_res->total_cost_price_all_Services ?? $grand_cost,
                    'total_sale'        => $inv_res->total_sale_price_all_Services,
                    'invoice_curreny'   => $invoice_curreny,
                    'customer_curreny'  => $customer_curreny,
                    'customer_total'    => $inv_res->total_sale_price_all_Services ?? $inv_res->total_sale_price_AC,
                    'created_at'        => $inv_res->created_at,
                ];
                
                // dd($inv_single_data);
                $inv_single_data = array_merge($inv_single_data,(array)$inv_res);
                // dd($inv_single_data);
                array_push($invoices_all_data,$inv_single_data);
            }
            
            $hotel_Booking_All_Data = [];
            foreach($hotel_Booking as $val_HB){
                // dd($val_HB);
                // $reservation_response   = json_decode($val_HB->reservation_response);
                // $reservation_request    = json_decode($val_HB->reservation_request);
                // $hotel_checkout_select  = json_decode($reservation_request->hotel_checkout_select);
                // $rooms_list             = $hotel_checkout_select->rooms_list;
                // $hotel_Rooms            = DB::table('rooms')->where('id',$rooms_list[0]->booking_req_id)->first();
                // $room_Cost_Price        = 0;
                
                // if($hotel_Rooms->price_all_days > 0){
                //     $room_Cost_Price += $hotel_Rooms->price_all_days;
                // }
                
                // if($hotel_Rooms->weekdays_price > 0){
                //     $room_Cost_Price += $hotel_Rooms->weekdays_price;
                // }
                
                // if($hotel_Rooms->weekdays_price > 0){
                //     $room_Cost_Price += $hotel_Rooms->weekdays_price;
                // }
                
                // dd($room_Cost_Price,$hotel_Rooms);
                // dd($val_HB,$reservation_response);
                
                $b2b_Agent_CS           = DB::table('customer_subcriptions')->where('Auth_key',$agent_res->token)->first();
                
                $hotel_Single_Data      = [
                    'booking_typeH'     => 'Website Hotel',
                    'invoice_id'        => $val_HB->id,
                    'price'             => $val_HB->exchange_price,
                    'paid_amount'       => $val_HB->exchange_price,
                    'remaing_amount'    => 0,
                    'over_paid_amount'  => $val_HB->exchange_price,
                    'profit'            => 0,
                    'total_cost'        => $val_HB->exchange_price,
                    'total_sale'        => $val_HB->exchange_price,
                    'invoice_curreny'   => $val_HB->GBP_currency ?? $val_HB->exchange_currency ?? '',
                    'customer_curreny'  => $customer_curreny ?? $val_HB->GBP_currency ?? $val_HB->exchange_currency ?? '',
                    'customer_total'    => $val_HB->exchange_price ?? '',
                    'created_at'        => $val_HB->created_at,
                    'website_URL'       => $b2b_Agent_CS->webiste_Address,
                ];
                $hotel_Single_Data = array_merge($hotel_Single_Data,(array)$val_HB);
                array_push($hotel_Booking_All_Data,$hotel_Single_Data);
            }
            // dd($hotel_Booking_All_Data);
            
            $transfer_Booking_All_Data = [];
            foreach($transfer_Booking as $val_TB){
                $b2b_Agent_CS                    = DB::table('customer_subcriptions')->where('Auth_key',$agent_res->token)->first();
                $transfer_total_price_exchange  = $val_TB->transfer_total_price_exchange ?? $val_TB->transfer_total_price ?? '';
                $profit                         = 0;
                $transfer_Single_Data   = [
                    'booking_type'      => 'Website Transfer',
                    'invoice_id'        => $val_TB->id,
                    'price'             => $transfer_total_price_exchange,
                    'paid_amount'       => $transfer_total_price_exchange,
                    'remaing_amount'    => 0,
                    'over_paid_amount'  => 0,
                    'profit'            => $profit,
                    'total_cost'        => $transfer_total_price_exchange,
                    'total_sale'        => $transfer_total_price_exchange,
                    'invoice_curreny'   => $val_TB->exchange_currency ?? $val_TB->currency ?? '',
                    'customer_curreny'  => $val_TB->currency ?? $val_TB->exchange_currency ?? '',
                    'customer_total'    => $transfer_total_price_exchange,
                    'created_at'        => $val_TB->created_at,
                    'website_URL'       => $b2b_Agent_CS->webiste_Address,
                ];
                
                $transfer_Single_Data = array_merge($transfer_Single_Data,(array)$val_TB);
                array_push($transfer_Booking_All_Data,$transfer_Single_Data);
            }
            // dd($transfer_Booking_All_Data);
            
            if($request->customer_id == 54){
                if(empty($booking_all_data)){
                }else{
                    $booking_all_data   = self::b2b_agent_all_bookings_details_Season_Working($booking_all_data,$request);
                    // dd($booking_all_data);
                }
                
                if(empty($invoices_all_data)){
                }else{
                    $invoices_all_data  = self::b2b_agent_all_bookings_details_Season_Working($invoices_all_data,$request);
                    // dd($invoices_all_data);
                }
                
                if(empty($hotel_Booking_All_Data)){
                }else{
                    $hotel_Booking_All_Data  = self::b2b_agent_all_bookings_details_Season_Working($hotel_Booking_All_Data,$request);
                    // dd($hotel_Booking_All_Data);
                }
                
                if(empty($transfer_Booking_All_Data)){
                }else{
                    $transfer_Booking_All_Data  = self::b2b_agent_all_bookings_details_Season_Working($transfer_Booking_All_Data,$request);
                    // dd($transfer_Booking_All_Data);
                }
            }
            
            $total_paid_amount          = DB::table('agents_ledgers_new')->where('b2b_Agent_id',$agent_id)->where('received_id','!=',NULL)->sum('payment');
            
            $agent_quotation_booking    = DB::table('addManageQuotationPackage')->where('b2b_Agent_Id',$agent_id)->where('quotation_status',NULL)->get();
            
            $agent_data = [
                'agent_id'                  => $agent_res->id,
                'agent_name'                => $agent_res->company_name,
                'total_paid_amount'         => $total_paid_amount,
                'agents_tour_booking'       => $booking_all_data,
                'agents_invoices_booking'   => $invoices_all_data,
                'agent_quotation_booking'   => $agent_quotation_booking,
                'hotel_Booking_All_Data'    => $hotel_Booking_All_Data,
                'transfer_Booking_All_Data' => $transfer_Booking_All_Data,
            ];
            array_push($all_agent_data,$agent_data);
            
            // dd($agent_data);
        }
        return $all_agent_data;
    }
    
    public function b2b_Agents_Details_ByType(Request $request){
        // dd($request->token);
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','Auth_key')->first();
        $request_data           = $request;
        $agentallBookingsObject = [];
        $agent_Groups           = [];
        $b2bAgentDetails        = DB::table('b2b_agents')->where('id',$request->id)->first();
        
        if($request_data->date_type == 'data_today_wise'){
            $today_date     = date('Y-m-d');
            $agentsInvoices = DB::table('b2b_agents')
                                ->leftJoin('add_manage_invoices', function ($join) use($today_date) {
                                    $join->on('add_manage_invoices.b2b_Agent_Id', '=', 'b2b_agents.id')->whereDate('add_manage_invoices.created_at', $today_date);
                                })->where('b2b_agents.token',$userData->Auth_key)->where('b2b_agents.id',$request->id)
                                ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('b2b_agents.id')->orderBy('b2b_agents.id','asc')->get();
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings = DB::table('b2b_agents')
                                    ->leftJoin('cart_details', function ($join) use($today_date) {
                                        $join->on('cart_details.b2b_Agent_Name', '=', 'b2b_agents.id')->whereDate('cart_details.created_at', $today_date);
                                    })
                                    ->where('b2b_agents.token',$userData->Auth_key)
                                    ->select('b2b_agents.id', 'b2b_agents.first_name','b2b_agents.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                    ->groupBy('b2b_agents.id')
                                    ->orderBy('b2b_agents.id','asc')
                                    ->get();
            foreach($agentsInvoices as $index => $invoice_res){
                $total_prices   = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                $payment_today  = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)->whereDate('date',$today_date)->where('received_id','!=',NULL)->sum('payment');
                $total_price    = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $agentallBookingsObject[] = (Object)[
                        'agent_id' => $invoice_res->id,
                        'agent_Name' => $invoice_res->first_name,
                        'company_name' => $invoice_res->company_name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
            // Hotel Bookings
            $hotel_Booking                      = DB::table('hotels_bookings')->where('b2b_agent_id',$request->id)->whereDate('created_at', $today_date)->get();
            if($hotel_Booking->isNotEmpty()){
                foreach($hotel_Booking as $valHB){
                    $agentallBookingsObject[]   = (Object)[
                        'total_prices'          => $valHB->exchange_price,
                    ];
                }
            }
        }
        
        if($request_data->date_type == 'data_week_wise'){
            
            $startOfWeek = Carbon::now()->startOfWeek();
            $start_date = $startOfWeek->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            //  dd($today_date);
            $agentsInvoices = DB::table('b2b_agents')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.b2b_Agent_Id', '=', 'b2b_agents.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('b2b_agents.token',$userData->Auth_key)->where('b2b_agents.id',$request->id)
                                
                                // ->select('b2b_agents.id', 'b2b_agents.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('b2b_agents.id')
                                ->orderBy('b2b_agents.id','asc')
                                ->get();
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings = DB::table('b2b_agents')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                $join->on('cart_details.b2b_Agent_Name', '=', 'b2b_agents.id')
                                                    ->whereDate('cart_details.created_at','>=', $start_date)
                                                    ->whereDate('cart_details.created_at','<=', $end_date);
                                            })
                            ->where('b2b_agents.token',$userData->Auth_key)
                            ->select('b2b_agents.id', 'b2b_agents.first_name','b2b_agents.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('b2b_agents.id')
                            ->orderBy('b2b_agents.id','asc')
                            ->get();
            
            foreach($agentsInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('agents_ledgers_new')->where('b2b_agent_id',$invoice_res->id)
                                               ->whereDate('date','>=', $start_date)
                                               ->whereDate('date','<=', $end_date)
                                               ->where('received_id','!=',NULL)
                                               ->sum('payment');
                $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $agentallBookingsObject[] = (Object)[
                        'agent_id' => $invoice_res->id,
                        'agent_Name' => $invoice_res->first_name,
                        'company_name' => $invoice_res->company_name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
            // Hotel Bookings
            $hotel_Booking                      = DB::table('hotels_bookings')->where('b2b_agent_id',$request->id)->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date)->get();
            if($hotel_Booking->isNotEmpty()){
                foreach($hotel_Booking as $valHB){
                    $agentallBookingsObject[]   = (Object)[
                        'total_prices'          => $valHB->exchange_price,
                    ];
                }
            }
        }
        
        if($request_data->date_type == 'data_month_wise'){
            $startOfMonth   = Carbon::now()->startOfMonth();
            $start_date     = $startOfMonth->format('Y-m-d');
            $end_date       = date('Y-m-d');
            
            $agentsInvoices = DB::table('b2b_agents')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.b2b_Agent_Id', '=', 'b2b_agents.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('b2b_agents.token',$userData->Auth_key)->where('b2b_agents.id',$request->id)
                                ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('b2b_agents.id')
                                ->orderBy('b2b_agents.id','asc')
                                ->get();
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings  =   DB::table('b2b_agents')
                                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                            $join->on('cart_details.b2b_Agent_Name', '=', 'b2b_agents.id')
                                                ->whereDate('cart_details.created_at','>=', $start_date)
                                                ->whereDate('cart_details.created_at','<=', $end_date);
                                            })
                                            ->where('b2b_agents.token',$userData->Auth_key)
                                            ->select('b2b_agents.id', 'b2b_agents.first_name','b2b_agents.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                            ->groupBy('b2b_agents.id')
                                            ->orderBy('b2b_agents.id','asc')
                                            ->get();
            foreach($agentsInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('agents_ledgers_new')->where('b2b_agent_id',$invoice_res->id)
                                               ->whereDate('date','>=', $start_date)
                                               ->whereDate('date','<=', $end_date)
                                               ->where('received_id','!=',NULL)
                                               ->sum('payment');
                $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $agentallBookingsObject[] = (Object)[
                    'agent_id' => $invoice_res->id,
                    'agent_Name' => $invoice_res->first_name,
                    'company_name' => $invoice_res->company_name,
                    'currency' =>  $invoice_res->currency,
                    'Invoices_booking' => $invoice_res->Invoices_booking,
                    'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                    'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                    'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                    'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                    'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                    'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                    'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                    'Paid' => number_format($payment_today,2),
                    'remaining' => number_format($total_price - $payment_today,2)
                ];
            }
            
            // Hotel Bookings
            $hotel_Booking                      = DB::table('hotels_bookings')->where('b2b_agent_id',$request->id)->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date)->get();
            if($hotel_Booking->isNotEmpty()){
                foreach($hotel_Booking as $valHB){
                    $agentallBookingsObject[]   = (Object)[
                        'total_prices'          => $valHB->exchange_price,
                    ];
                }
            }
        }
        
        if($request_data->date_type == 'data_year_wise'){
            
            $startOfYear    = Carbon::now()->startOfYear();
            $start_date     = $startOfYear->format('Y-m-d');
            $end_date       = date('Y-m-d');
            
            // dd($start_date,$end_date,$request->id,$userData->Auth_key);
            
            $agentsInvoices     = DB::table('b2b_agents')
                                    ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                        $join->on('add_manage_invoices.b2b_Agent_Id', '=', 'b2b_agents.id')
                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                    })
                                    ->where('b2b_agents.token',$userData->Auth_key)->where('b2b_agents.id',$request->id)
                                    // ->select('b2b_agents.id', 'b2b_agents.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                    ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                    ->groupBy('b2b_agents.id')
                                    ->orderBy('b2b_agents.id','asc')
                                    ->get();
            // dd('Test',$agentsInvoices);
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings  = DB::table('b2b_agents')
                                        ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                            $join->on('cart_details.b2b_Agent_Name', '=', 'b2b_agents.id')
                                                ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date);
                                            })
                                        ->where('b2b_agents.token',$userData->Auth_key)
                                        ->select('b2b_agents.id', 'b2b_agents.first_name','b2b_agents.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                        ->groupBy('b2b_agents.id')->orderBy('b2b_agents.id','asc')->get();
            foreach($agentsInvoices as $index => $invoice_res){
                $total_prices   = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $payment_today  = DB::table('agents_ledgers_new')->where('b2b_agent_id',$invoice_res->id)
                                   ->whereDate('date','>=', $start_date)
                                   ->whereDate('date','<=', $end_date)
                                   ->where('received_id','!=',NULL)
                                   ->sum('payment');
                $total_price    = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $agentallBookingsObject[] = (Object)[
                    'agent_id' => $invoice_res->id,
                    'agent_Name' => $invoice_res->first_name,
                    'company_name' => $invoice_res->company_name,
                    'currency' =>  $invoice_res->currency,
                    'Invoices_booking' => $invoice_res->Invoices_booking,
                    'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                    'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                    'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                    'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                    'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                    'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                    'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                    'Paid' => number_format($payment_today,2),
                    'remaining' => number_format($total_price - $payment_today,2)
                ];
            }
            
            // Hotel Bookings
            $hotel_Booking                      = DB::table('hotels_bookings')->where('b2b_agent_id',$request->id)->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date)->get();
            if($hotel_Booking->isNotEmpty()){
                foreach($hotel_Booking as $valHB){
                    $agentallBookingsObject[]   = (Object)[
                        'total_prices'          => $valHB->exchange_price,
                    ];
                }
            }
        }
        
        if($request_data->date_type == 'data_wise'){
            $start_date = $request_data->start_date;
            $end_date   = $request_data->end_date;
            
            //  dd($today_date);
            $agentsInvoices = DB::table('b2b_agents')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.b2b_Agent_Id', '=', 'b2b_agents.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('b2b_agents.token',$userData->Auth_key)->where('b2b_agents.id',$request->id)
                                
                                // ->select('b2b_agents.id', 'b2b_agents.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                // 'b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.agent_Name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency'
                                ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('b2b_agents.id')
                                ->orderBy('b2b_agents.id','asc')
                                ->get();
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings  = DB::table('b2b_agents')
                                        ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                            $join->on('cart_details.b2b_agent_name', '=', 'b2b_agents.id')
                                            ->whereDate('cart_details.created_at','>=', $start_date)
                                            ->whereDate('cart_details.created_at','<=', $end_date);
                                        })
                                        ->where('b2b_agents.token',$userData->Auth_key)
                                        ->select('b2b_agents.id', 'b2b_agents.first_name','b2b_agents.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                        ->groupBy('b2b_agents.id')
                                        ->orderBy('b2b_agents.id','asc')
                                        ->get();
            foreach($agentsInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('agents_ledgers_new')->where('b2b_agent_id',$invoice_res->id)
                                               ->whereDate('date','>=', $start_date)
                                               ->whereDate('date','<=', $end_date)
                                               ->where('received_id','!=',NULL)
                                               ->sum('payment');
                $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $agentallBookingsObject[] = (Object)[
                        'agent_id' => $invoice_res->id,
                        'agent_Name' => $invoice_res->first_name,
                        'company_name' => $invoice_res->company_name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
            // Hotel Bookings
            $hotel_Booking                      = DB::table('hotels_bookings')->where('b2b_agent_id',$request->id)->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date)->get();
            if($hotel_Booking->isNotEmpty()){
                foreach($hotel_Booking as $valHB){
                    $agentallBookingsObject[]   = (Object)[
                        'total_prices'          => $valHB->exchange_price,
                    ];
                }
            }
        }
        
        if($request_data->filter_type == 'total_revenue'){
            
            $agentallBookingsObject = new Collection($agentallBookingsObject);
            $agentallBookingsObject = $agentallBookingsObject->sortByDesc('total_prices');
            
            // Reindex the collection starting from 0
            $agentallBookingsObject = $agentallBookingsObject->values();
            $agentallBookingsObject = $agentallBookingsObject->toArray();
            
            if(sizeOf($agentallBookingsObject) >= 4){
                $limitedAgentData = array_slice($agentallBookingsObject, 0, 4);
            }else{
                $limitedAgentData = $agentallBookingsObject;
            }
            
            $series_data        = [];
            $categories_data    = [];
            
            // Generate Graph Data Today
            if($request_data->date_type == 'data_today_wise'){
                $date = date('Y-m-d');
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [$agent_res->total_prices];
                    $series_data[] = [
                        'name' => $agent_res->agent_Name,
                        'data' => $agent_booking_data
                    ];
                }
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                
                foreach($limitedAgentData as $agent_res){
                    
                    // Loop On DatesOfWeek
                    
                    $agent_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $agentsInvoices         = DB::table('add_manage_invoices')->where('b2b_agent_Id',$b2bAgentDetails->id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->Sum('total_sale_price_all_Services');

                        $agentsPackageBookings  =  DB::table('cart_details')->where('b2b_agent_name',$b2bAgentDetails->id)
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$b2bAgentDetails->id)
                                                    ->whereDate('created_at', $date_res)
                                                    ->Sum('exchange_price');
                        $total_booking_price    = $agentsInvoices + $agentsPackageBookings + $hotel_Booking;
                                        
                      
                        
                        $agent_booking_data[] = $total_booking_price;
                    }
                    
                    $series_data[] = [
                            'name' => $b2bAgentDetails->first_name,
                            'data' => $agent_booking_data
                        ];
                }
                
                // Get Data For Categories
                
                $dayNamesOfWeek = [];
                for ($day = 0; $day < 7; $day++) {
                    $dayNamesOfWeek[] = $today->startOfWeek()->addDays($day)->format('l'); // 'l' format represents the full day name
                }
                
                $categories_data = $dayNamesOfWeek;
            }
            
            // Generate Graph Data For Month
            if($request_data->date_type == 'data_month_wise'){
                
                foreach($limitedAgentData as $agent_res){
                    $startDate              = Carbon::now()->startOfMonth();
                    $startDateWeek          = $startDate->toDateString();
                    $endDate                = $startDate->copy()->addDays(6);
                    $endDateWeek            = $endDate->toDateString();
                    $agent_booking_data     = [];
                    
                    for($i=1; $i<=5; $i++){
                        $agentsInvoices         = DB::table('add_manage_invoices')->where('b2b_agent_Id',$b2bAgentDetails->id)
                                                    ->whereDate('add_manage_invoices.created_at','>=', $startDate)->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                    ->Sum('total_sale_price_all_Services');
                        $agentsPackageBookings  =  DB::table('cart_details')->where('b2b_agent_name',$b2bAgentDetails->id)
                                                    ->whereDate('cart_details.created_at','>=', $startDate)->whereDate('cart_details.created_at','<=', $endDate)
                                                    ->Sum('tour_total_price');
                        $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$b2bAgentDetails->id)
                                                    ->whereDate('created_at','>=', $startDate)->whereDate('created_at','<=', $endDate)
                                                    ->Sum('exchange_price');
                        $total_booking_price    = $agentsInvoices + $agentsPackageBookings + $hotel_Booking;
                        $agent_booking_data[]   = $total_booking_price;
                        $startDate              = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate            = $startDate->copy()->addDays(2);
                        }else{
                            $endDate            = $startDate->copy()->addDays(6);
                        }
                        $startDateWeek          = $startDate->toDateString();
                        $endDateWeek            = $endDate->toDateString();
                    }
                    $series_data[]              = [
                        'name'                  => $b2bAgentDetails->first_name,
                        'data'                  => $agent_booking_data
                    ];
                }
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            // Generate Graph Data For Year
            if($request_data->date_type == 'data_year_wise'){
                
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    
               
                    
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('b2b_agent_Id',$b2bAgentDetails->id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->Sum('total_sale_price_all_Services');

                        $agentsPackageBookings =  DB::table('cart_details')->where('b2b_agent_name',$b2bAgentDetails->id)
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->Sum('tour_total_price');
                        $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$b2bAgentDetails->id)
                                                    ->whereDate('created_at','>=', $month_res->start_date)->whereDate('created_at','<=', $month_res->end_date)
                                                    ->Sum('exchange_price');
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings + $hotel_Booking;
                                        
                      
                        
                        $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $b2bAgentDetails->first_name,
                            'data' => $agent_booking_data
                        ];
                }
                // print_r($series_data);
                // die;
    
            }
            
            // Generate Graph Data For Custom Date
            if($request_data->date_type == 'data_wise'){
                $series_data        = [];
                $categories_data    = [];
                
                $startDate      = Carbon::parse($request_data->start_date);
                $currentDate    = $startDate->copy();
                $endDate        = Carbon::parse($request_data->end_date);
                $dateRange      = [];
                
                while ($currentDate->lte($endDate)) {
                    $dateRange[] = $currentDate->format('Y-m-d');
                    $currentDate->addDay();
                }
                
                // dd($limitedAgentData);
                
                $agent_res = $limitedAgentData[0] ?? '';
                if($agent_res != ''){
                    foreach ($dateRange as $date) {
                        $series_data            = [];
                        $agentsInvoices         = DB::table('add_manage_invoices')->where('b2b_agent_Id',$b2bAgentDetails->id)
                                                    ->whereDate('add_manage_invoices.created_at', '=', $date)
                                                    ->sum('total_sale_price_all_Services');
                        $agentsPackageBookings  = DB::table('cart_details')->where('b2b_agent_name',$b2bAgentDetails->id)
                                                    ->whereDate('cart_details.created_at', '=', $date)
                                                    ->sum('tour_total_price');
                            $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$b2bAgentDetails->id)
                                                    ->whereDate('created_at','=', $date)
                                                    ->Sum('exchange_price');
                        $total_booking_price    = $agentsInvoices + $agentsPackageBookings + $hotel_Booking; 
                        if($total_booking_price > 0){
                            $agent_booking_data[]   = $total_booking_price;
                            $series_data[]          = [
                                'name'              => $b2bAgentDetails->first_name,
                                'data'              => $agent_booking_data
                            ];
                        }else{
                            $agent_booking_data[]   = 0;
                            $series_data[]          = [
                                'name'              => $b2bAgentDetails->first_name,
                                'data'              => $agent_booking_data
                            ];
                        }
                        $categories_data[]          = $date;
                    }
                }
            }
            
            return response()->json([
                'status'            => 'success',
                'data'              => $limitedAgentData,
                'series_data'       => $series_data,
                // 'series_data1'      => $series_data1,
                'categories_data'   => $categories_data,
                'agent_Groups'      => $agent_Groups,
                // 'categories_data1'  => $categories_data1,
            ]);
        }else{
            $agentallBookingsObject = new Collection($agentallBookingsObject);
            
            $agentallBookingsObject = $agentallBookingsObject->sortByDesc('all_bookings');
            
            // Reindex the collection starting from 0
            $agentallBookingsObject = $agentallBookingsObject->values();
            
            $agentallBookingsObject = $agentallBookingsObject->toArray();
            
            if(sizeOf($agentallBookingsObject) >= 4){
                $limitedAgentData = array_slice($agentallBookingsObject, 0, 4);
            }else{
                $limitedAgentData = $limitedAgentData;
            }
            
            // dd($limitedAgentData);
            $series_data = [];
            $categories_data = [];
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                foreach($limitedAgentData as $agent_res){
                    
                    $agent_booking_data = [$agent_res->all_bookings];
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            return response()->json([
                'status'            => 'success',
                'data'              => $limitedAgentData,
                'series_data'       => $series_data,
                'categories_data'   => $categories_data,
                'agent_Groups'      => $agent_Groups,
            ]);
        }
    }
    
    function b2b_Agent_Prices_Type(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $all_customers_data = [];
        if($userData){
            $all_customers_data = $this->b2b_agent_all_bookings_details_Type($request->agent_id,$request->date_type,$request->start_date,$request->end_date);
        }
        $graph_data = $this->createAgentYearlyGraph($request->agent_id,$request);
        return response()->json(['message'=>'success',
            'agent_data' => $all_customers_data,
            'graph_data' => $graph_data
        ]);
    }
    
    public static function b2b_agent_all_bookings_details_Type($agent_id,$date_type,$start_date,$end_date){
        $agent_lists = DB::table('b2b_agents')->where('id',$agent_id)->get();
        
        $all_agent_data = [];
        foreach($agent_lists as $agent_res){
            if($date_type == 'data_today_wise'){
                $today_date             = date('Y-m-d');
                $agent_tour_booking     = DB::table('cart_details')->where('b2b_agent_name',$agent_id)->whereDate('cart_details.created_at', $today_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('b2b_agent_Id',$agent_id)->whereDate('add_manage_invoices.created_at', $today_date)->get();
                $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_id)->whereDate('created_at', $today_date)->get();
            }
            
            if($date_type == 'data_week_wise'){
                $startOfWeek            = Carbon::now()->startOfWeek();
                $start_date             = $startOfWeek->format('Y-m-d');
                $end_date               = date('Y-m-d');
                $agent_tour_booking     = DB::table('cart_details')->where('b2b_agent_name',$agent_id)
                                            ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('b2b_agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
                $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_id)->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date)->get();
            }
            
            if($date_type == 'data_month_wise'){
                $startOfMonth   = Carbon::now()->startOfMonth();
                $start_date     = $startOfMonth->format('Y-m-d');
                $end_date       = date('Y-m-d');
                $agent_tour_booking     = DB::table('cart_details')->where('b2b_agent_name',$agent_id)
                                            ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('b2b_agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
                $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_id)->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date)->get();
            }
            
            if($date_type == 'data_year_wise'){
                $startOfYear    = Carbon::now()->startOfYear();
                $start_date     = $startOfYear->format('Y-m-d');
                $end_date       = date('Y-m-d');
                $agent_tour_booking     = DB::table('cart_details')->where('b2b_agent_name',$agent_id)
                                            ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('b2b_agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
                $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_id)->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date)->get();
            }
            
            if($date_type == 'data_wise'){
                $start_date             = $start_date;
                $end_date               = $end_date;
                $agent_tour_booking     = DB::table('cart_details')->where('b2b_agent_name',$agent_id)
                                            ->whereDate('cart_details.created_at','>=', $start_date)->whereDate('cart_details.created_at','<=', $end_date)->get();
                $agent_invoice_booking  = DB::table('add_manage_invoices')->where('b2b_agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $start_date)->whereDate('add_manage_invoices.created_at','<=', $end_date)->get();
                $hotel_Booking          = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_id)->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date)->get();
            }
            
            $booking_all_data = [];
            foreach($agent_tour_booking as $tour_res){
                 
                $tours_costing = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();
                $booking_details    = DB::table('tours_bookings')->where('invoice_no',$tour_res->invoice_no)->first();
                //  print_r($tours_costing);
                
                 
                 $cart_all_data = json_decode($tour_res->cart_total_data);
                 
                 $grand_profit = 0;
                 $grand_cost = 0;
                 $grand_sale = 0;
                 // Profit From Double Adults
                 
                    if($cart_all_data->double_adults > 0){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_adult_total_cost;
                        $grand_sale += $cart_all_data->double_adult_total;
                    }
                 
                 // Profit From Triple Adults
                 
                    if($cart_all_data->triple_adults > 0){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_adult_total_cost;
                        $grand_sale += $cart_all_data->triple_adult_total;
                    }
                    
    
                 // Profit From Quad Adults
                 
                    if($cart_all_data->quad_adults > 0){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_adult_total_cost;
                        $grand_sale += $cart_all_data->quad_adult_total;
                    }
                 
                 // Profit From Without Acc
                 
                    if($cart_all_data->without_acc_adults > 0){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_adult_total_cost;
                        $grand_sale += $cart_all_data->without_acc_adult_total;
                    }
                 
                 // Profit From Double Childs
                 
                    if($cart_all_data->double_childs > 0){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_child_total_cost;
                        $grand_sale += $cart_all_data->double_childs_total;
                    }
                 
                 // Profit From Triple Childs
                 
                   if($cart_all_data->triple_childs > 0){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_child_total_cost;
                        $grand_sale += $cart_all_data->triple_childs_total;
                    }
                    
                 // Profit From Quad Childs
                 
                    if($cart_all_data->quad_childs > 0){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                        $grand_profit += $quad_profit;
                        $grand_cost += $quad_child_total_cost;
                        $grand_sale += $cart_all_data->quad_child_total;
                    }
                 
                 // Profit From Without Acc Child
    
                    if($cart_all_data->children > 0){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_child_total_cost;
                        $grand_sale += $cart_all_data->without_acc_child_total;
                    }
    
                // Profit From Double Infant
                    if($cart_all_data->double_infant > 0){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                        $grand_profit += $double_profit;
                         $grand_cost += $double_infant_total_cost;
                        $grand_sale += $cart_all_data->double_infant_total;
                    }
                 
                 // Profit From Triple Infant
                 
                    if($cart_all_data->triple_infant > 0){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                        $grand_profit += $triple_profit;
                         $grand_cost += $triple_infant_total_cost;
                        $grand_sale += $cart_all_data->triple_infant_total;
                    }
                 
                 // Profit From Quad Infant
                 
                    if($cart_all_data->quad_infant > 0){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_infant_total_cost;
                        $grand_sale += $cart_all_data->quad_infant_total;
                    }
                 
                 // Profit From Without Acc Infant  
                 
                  if($cart_all_data->infants > 0){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_infant_total_cost;
                        $grand_sale += $cart_all_data->without_acc_infant_total;
                  }
                  
                  $over_all_dis = 0;
                //   echo "Grand Total Profit is $grand_profit "; 
                  if($cart_all_data->discount_type == 'amount'){
                      $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                  }else{
                       $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                       $final_profit = $grand_profit - $discunt_am_over_all;
                  }
                 
                  
                 
                //  echo "Grand Total Profit is $final_profit";
                //  print_r($cart_all_data);
                
                $commission_add = '';
                if(isset($cart_all_data->customer_commsion_add_total)){
                    $commission_add = $cart_all_data->customer_commsion_add_total;
                }
    
                 $booking_data = [
                        'booking_type' => 'Package',
                        'invoice_id'=>$tour_res->invoice_no,
                        'booking_id'=>$tour_res->booking_id,
                        'tour_id'=>$tour_res->tour_id,
                        'price'=>$tour_res->tour_total_price,
                        'paid_amount'=>$tour_res->total_paid_amount,
                        'remaing_amount'=> $tour_res->tour_total_price - $tour_res->total_paid_amount,
                        'over_paid_amount'=> $tour_res->over_paid_amount,
                        'tour_name'=>$cart_all_data->name,
                        'profit'=>$final_profit,
                        'discount_am'=>$cart_all_data->discount_Price,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'created_at'=>$tour_res->created_at,
                        'commission_am'=>'',
                        'customer_commsion_add_total'=>$commission_add,
                        'currency'=>$tour_res->currency,
                        'confirm'=>$tour_res->confirm,
                        'lead_Name'=>$booking_details->passenger_name,
                        'passenger_detail'=>$booking_details->passenger_detail,
                     ];
                     
                  array_push($booking_all_data,$booking_data);
            }
            
            $invoices_all_data = [];
            foreach($agent_invoice_booking as $inv_res){
                
                $accomodation = json_decode($inv_res->accomodation_details);
                $accomodation_more = json_decode($inv_res->accomodation_details_more);
                $markup_details = json_decode($inv_res->markup_details);
                $more_markup_details = json_decode($inv_res->more_markup_details);
                 
                // Caluclate Flight Price 
                $grand_cost = 0;
                $grand_sale = 0;
                $flight_cost = 0;
                $flight_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                        $flight_cost = $mark_res->without_markup_price; 
                        $flight_sale = $mark_res->markup_price; 
                    }
                }
                
                $flight_total_cost = (float)$flight_cost * (float)$inv_res->no_of_pax_days;
                $flight_total_sale = (float)$flight_sale * (float)$inv_res->no_of_pax_days;
                
                $flight_profit = $flight_total_sale - $flight_total_cost;
                
                $grand_cost += $flight_total_cost;
                $grand_sale += $flight_total_sale;
                
                // Caluclate Visa Price 
                $visa_cost = 0;
                $visa_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                        $visa_cost = $mark_res->without_markup_price; 
                        $visa_sale = $mark_res->markup_price; 
                    }
                }
                $visa_total_cost = (float)$visa_cost * (float)$inv_res->no_of_pax_days;
                $visa_total_sale = (float)$visa_sale * (float)$inv_res->no_of_pax_days;
                $visa_profit = $visa_total_sale - $visa_total_cost;
                $grand_cost += $visa_total_cost;
                $grand_sale += $visa_total_sale;
    
                // Caluclate Transportation Price
                $trans_cost = 0;
                $trans_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                        $trans_cost = $mark_res->without_markup_price; 
                        $trans_sale = $mark_res->markup_price; 
                    }
                }
                $trans_total_cost = (float)$trans_cost * (float)$inv_res->no_of_pax_days;
                $trans_total_sale = (float)$trans_sale * (float)$inv_res->no_of_pax_days;
                $trans_profit = $trans_total_sale - $trans_total_cost;
                $grand_cost += $trans_total_cost;
                $grand_sale += $trans_total_sale;
                
                // Caluclate Double Room Price
                $double_total_cost = 0;
                $double_total_sale = 0;
                $double_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Double'){
                            (float)$double_cost = $accmod_res->acc_total_amount; 
                            (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                            
                             $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                             $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                             $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                             $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Double'){
                            $double_cost = $accmod_res->more_acc_total_amount; 
                            $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                            $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                            $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                            $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                $grand_cost += $double_total_cost;
                $grand_sale += $double_total_sale;
                
                // Caluclate Triple Room Price
                $triple_total_cost = 0;
                $triple_total_sale = 0;
                $triple_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->acc_total_amount; 
                            $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                            $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                $grand_cost += $triple_total_cost;
                $grand_sale += $triple_total_sale;
                 
                // Caluclate Quad Room Price
                $quad_total_cost = 0;
                $quad_total_sale = 0;
                $quad_total_profit = 0;
                if(isset($accomodation)){
                            foreach($accomodation as $accmod_res){
                                if($accmod_res->acc_type == 'Quad'){
                                    $quad_cost = $accmod_res->acc_total_amount; 
                                    $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                    
                                     $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                     $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                     $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                     $quad_total_profit = $quad_total_profit + $quad_profit;
                                }
                            }
                         }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Quad'){
                            $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                            $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                            $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                            $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                            $quad_total_profit = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                $grand_cost += $quad_total_cost;
                $grand_sale += $quad_total_sale;
                
                // Caluclate Final Price
                $Final_inv_price = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                $Final_inv_profit = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                
                $select_curreny_data = explode(' ', $inv_res->currency_conversion);
                
                $invoice_curreny = "";
                $customer_curreny_data = explode(' ', $inv_res->currency_Type_AC);
                if(isset($select_curreny_data[2])){
                    $invoice_curreny = $select_curreny_data[2];
                }
                
                $customer_curreny = $invoice_curreny;
                $customer_curreny_data = explode(' ', $inv_res->currency_Type_AC);
                if(isset($customer_curreny_data[2])){
                    $customer_curreny = $customer_curreny_data[2];
                }
                
                $profit = $inv_res->total_sale_price_all_Services - $inv_res->total_cost_price_all_Services ?? $grand_cost;
                $inv_single_data = [
                    'booking_type' => 'Invoice',
                    'invoice_id'=>$inv_res->id,
                    'price'=>$inv_res->total_sale_price_all_Services,
                    'paid_amount'=>$inv_res->total_paid_amount,
                    'remaing_amount'=> $inv_res->total_sale_price_all_Services - $inv_res->total_paid_amount,
                    'over_paid_amount'=>$inv_res->over_paid_amount,
                    'profit'=>$profit,
                    'total_cost'=> $inv_res->total_cost_price_all_Services ?? $grand_cost,
                    'total_sale'=>$inv_res->total_sale_price_all_Services,
                    'invoice_curreny'=> $invoice_curreny,
                    'customer_curreny'=>$customer_curreny,
                    'customer_total'=>$inv_res->total_sale_price_all_Services ?? $inv_res->total_sale_price_AC,
                    'created_at'=>$inv_res->created_at,
                ];
                
                // print_r($inv_single_data);
                $inv_single_data = array_merge($inv_single_data,(array)$inv_res);
                // dd($inv_single_data);
                array_push($invoices_all_data,$inv_single_data);
                 
            }
            
            $hotel_Booking_All_Data = [];
            foreach($hotel_Booking as $val_HB){
                // dd($val_HB);
                // $reservation_response   = json_decode($val_HB->reservation_response);
                // $reservation_request    = json_decode($val_HB->reservation_request);
                // $hotel_checkout_select  = json_decode($reservation_request->hotel_checkout_select);
                // $rooms_list             = $hotel_checkout_select->rooms_list;
                // $hotel_Rooms            = DB::table('rooms')->where('id',$rooms_list[0]->booking_req_id)->first();
                // $room_Cost_Price        = 0;
                
                // if($hotel_Rooms->price_all_days > 0){
                //     $room_Cost_Price += $hotel_Rooms->price_all_days;
                // }
                
                // if($hotel_Rooms->weekdays_price > 0){
                //     $room_Cost_Price += $hotel_Rooms->weekdays_price;
                // }
                
                // if($hotel_Rooms->weekdays_price > 0){
                //     $room_Cost_Price += $hotel_Rooms->weekdays_price;
                // }
                
                // dd($room_Cost_Price,$hotel_Rooms);
                // dd($val_HB,$reservation_response);
                
                $b2b_Agent_CS           = DB::table('customer_subcriptions')->where('Auth_key',$agent_res->token)->first();
                
                $hotel_Single_Data      = [
                    'booking_typeH'     => 'Website Hotel',
                    'invoice_id'        => $val_HB->id,
                    'price'             => $val_HB->exchange_price,
                    'paid_amount'       => $val_HB->exchange_price,
                    'remaing_amount'    => 0,
                    'over_paid_amount'  => $val_HB->exchange_price,
                    'profit'            => 0,
                    'total_cost'        => $val_HB->exchange_price,
                    'total_sale'        => $val_HB->exchange_price,
                    'invoice_curreny'   => $val_HB->GBP_currency ?? $val_HB->exchange_currency ?? '',
                    'customer_curreny'  => $customer_curreny ?? $val_HB->GBP_currency ?? $val_HB->exchange_currency ?? '',
                    'customer_total'    => $val_HB->exchange_price ?? '',
                    'created_at'        => $val_HB->created_at,
                    'website_URL'       => $b2b_Agent_CS->webiste_Address,
                ];
                $hotel_Single_Data = array_merge($hotel_Single_Data,(array)$val_HB);
                array_push($hotel_Booking_All_Data,$hotel_Single_Data);
            }
            
            $total_paid_amount          = DB::table('agents_ledgers_new')->where('b2b_agent_id',$agent_id)->where('received_id','!=',NULL)->sum('payment');
            $agent_quotation_booking    = DB::table('addManageQuotationPackage')->where('b2b_agent_Id',$agent_id)->where('quotation_status',NULL)->get();
            
            $agent_data = [
                'agent_id'                  => $agent_res->id,
                'agent_name'                => $agent_res->company_name,
                'total_paid_amount'         => $total_paid_amount,
                'agents_tour_booking'       => $booking_all_data,
                'agents_invoices_booking'   => $invoices_all_data,
                'agent_quotation_booking'   => $agent_quotation_booking,
                'hotel_Booking_All_Data'    => $hotel_Booking_All_Data,
            ];
            array_push($all_agent_data,$agent_data);         
        }
        
        return $all_agent_data;
    }
    
    function createAgentYearlyGraph($agent_id,$request){
        $series_data                = [];
        $categories_data            = [];
        if(isset($request->season_Id) && $request->season_Id > 0 && $request->season_Id != 'all_Seasons'){
            $season                 = DB::table('add_Seasons')->where('id', $request->season_Id)->first();
            $startDate              = Carbon::parse($season->start_Date);
            $endDate                = Carbon::parse($season->end_Date);
            $monthsData             = [];
            while ($startDate->lte($endDate)) {
                $startOfMonth       = $startDate->copy()->startOfMonth();
                $endOfMonth         = $startDate->copy()->endOfMonth();
                
                if ($endOfMonth->gt($endDate)) {
                    $endOfMonth     = $endDate;
                }
                
                $categories_data[]  = $startOfMonth->format('F');
                $monthsData[]       = (Object)[
                    'month'         => $startOfMonth->month,
                    'start_date'    => $startOfMonth->format('Y-m-d'),
                    'end_date'      => $endOfMonth->format('Y-m-d'),
                ];
                $startDate->addMonth();
            }
        }else{
            $currentYear            = date('Y');
            $monthsData             = [];
            for ($month = 1; $month <= 12; $month++) {
                $startOfMonth       = Carbon::create($currentYear, $month, 1)->startOfMonth();
                $endOfMonth         = Carbon::create($currentYear, $month, 1)->endOfMonth();
                $categories_data[]  = $startOfMonth->format('F');
                $startOfMonth       = $startOfMonth->format('Y-m-d');
                $endOfMonth         = $endOfMonth->format('Y-m-d');
                $monthsData[]       = (Object)[
                    'month'         => $month,
                    'start_date'    => $startOfMonth,
                    'end_date'      => $endOfMonth,
                ];
            }
        }
        
        $agent_booking_data = [];
        foreach($monthsData as $month_res){
            $agentInvoices          =   DB::table('add_manage_invoices')->where('b2b_agent_Id',$agent_id)
                                            ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                            ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                            ->Sum('total_sale_price_all_Services');
            $agentPackageBookings   =   DB::table('cart_details')->where('b2b_agent_name',"$agent_id")
                                            ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                            ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                            ->Sum('tour_total_price');
            $hotel_Booking          =   0;
            if($request->token == config('token_HaramaynRooms')){
                $hotel_Booking          =   DB::table('hotels_bookings')->where('b2b_agent_id',$agent_id)
                                                ->whereDate('hotels_bookings.created_at','>=', $month_res->start_date)
                                                ->whereDate('hotels_bookings.created_at','<=', $month_res->end_date)
                                                ->sum('selected_exchange_rate');
            }
            $total_booking_price    = $agentInvoices + $agentPackageBookings + $hotel_Booking;
            $agent_booking_data[]   = floor($total_booking_price * 100) / 100;
           
        }
        
        $series_data[]              = [
            'data'                  => $agent_booking_data
        ];
        
        return [
            'series_data'           => $series_data,
            'categories_data'       => $categories_data,
        ];
    }
    
    function createAgentYearlyGraph_Old($agent_id){
        $series_data = [];
        $categories_data = [];
        
        $currentYear = date('Y');
        $monthsData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            
             $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
             $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
             
              $categories_data[] = $startOfMonth->format('F');
             
             $startOfMonth = $startOfMonth->format('Y-m-d');
             $endOfMonth = $endOfMonth->format('Y-m-d');

            $monthsData[] = (Object)[
                'month' => $month,
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
            ];
        }
        
        $agent_booking_data = [];
        foreach($monthsData as $month_res){
            // Add 7 days to the start date
            
            $agentInvoices = DB::table('add_manage_invoices')->where('b2b_agent_Id',$agent_id)
                                                              ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                              ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                              ->Sum('total_sale_price_all_Services');

            $agentPackageBookings =  DB::table('cart_details')->where('b2b_agent_name',"$agent_id")
                                                      ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                      ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                      ->Sum('tour_total_price');
                                                       
            $total_booking_price = $agentInvoices + $agentPackageBookings;
                            
          
            
            $agent_booking_data[] = floor($total_booking_price * 100) / 100;
           
        }
        
        $series_data[] = [
            'data' => $agent_booking_data
        ];
        
        return [
            'series_data' => $series_data,
            'categories_data' => $categories_data,
        ];
    }
    
    public function b2b_edit_Agents(Request $request){
        DB::beginTransaction(); 
        try {
            $edit_Agents    = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
            $countries      = DB::table('countries')->get();
            return response()->json(['status'=>'success','edit_Agents'=>$edit_Agents,'countries'=>$countries]); 
            DB::commit();
            return response()->json(['status'=>'success','message'=>'B2B Agent Disable Successfully!']);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function b2b_update_Agents(Request $request){
        $id                                     = $request->id;
        $Agents_detail                          = b2b_Agents_detail::find($id);
        if($Agents_detail){
            $Agents_detail->title               = $request->title;
            $Agents_detail->first_name          = $request->first_name;
            $Agents_detail->last_name           = $request->last_name;
            $Agents_detail->email               = $request->email;
            $Agents_detail->company_name        = $request->company_name;
            $Agents_detail->company_address     = $request->company_address;
            $Agents_detail->phone_no            = $request->phone_no;
            $Agents_detail->country             = $request->country;
            $Agents_detail->city                = $request->city;
            $Agents_detail->update();
            return response()->json(['status'=>'success','Success'=>'B2B Agent Updated Successful!']);
        }
        else{
            return response()->json(['Agents_detail'=>$Agents_detail,'Error'=>'Agents Not Updated!']);
        }
    }
    
    public function b2b_Agent_Payment_Details(Request $request){
        try {
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','currency_symbol','currency_value')->first();
            if($userData){
                if($userData->status == 1){
                    
                    $b2b_Agent                  = DB::table('b2b_agents')->where('id', $request->b2b_Agent_id)->first();
                    $b2b_Agent_AD               = DB::table('agents_ledgers_new')->where('b2b_Agent_id', $request->b2b_Agent_id)->get();
                    $b2b_Agent_Total_Balance    = DB::table('agents_ledgers_new')->where('b2b_Agent_id', $request->b2b_Agent_id)->sum('balance');
                    $b2b_Agent_Total_Paid       = DB::table('agents_ledgers_new')->where('b2b_Agent_id', $request->b2b_Agent_id)->sum('received');
                    $b2b_Agent_Total_Remaining  = $b2b_Agent_Total_Balance - $b2b_Agent_Total_Paid;
                    
                    // Hotel Booking
                    $hotels_bookings = '';
                    if($userData->id == 48 || $userData->id == 59 || $userData->id == 60){
                        $hotels_bookings    = [];
                        $b2b_Agent_HB       = DB::table('hotels_bookings')->where('customer_id',$userData->id)->where('b2b_agent_id',$request->b2b_Agent_id)
                                                ->select('id','lead_passenger','invoice_no','status','exchange_price','reservation_request','reservation_response','created_at','payment_details')
                                                ->get();
                        foreach($b2b_Agent_HB as $val_HB){
                            $reservation_response = json_decode($val_HB->reservation_response);
                            if(isset($reservation_response->provider) && $reservation_response->provider == 'Custome_hotel'){
                                if(isset($reservation_response->hotel_details)){
                                    $hotel_details      = $reservation_response->hotel_details;
                                    if(isset($hotel_details->checkIn)){
                                        // if(isset($hotel_details->checkIn) && $hotel_details->checkIn >= $startDate && $hotel_details->checkIn <= $endDate){
                                            $val_HB->check_in       = $hotel_details->checkIn;
                                            $val_HB->hotel_Type     = $reservation_response->provider;
                                            $hotels_bookings[]      = $val_HB;
                                        // }
                                    }
                                }
                            }
                        }
                        
                        $hotels_bookings            = collect($hotels_bookings);
                    }
                    // Hotel Booking
                    
                    // Transfer Booking
                    $transfer_bookings = '';
                    if($userData->id == 60){
                        $transfer_bookings  = DB::table('transfers_new_booking')->where('customer_id',$userData->id)->where('b2b_agent_id',$request->b2b_Agent_id)->get();
                    }
                    // Transfer Booking
                    
                    return response()->json([
                        'message'                   => 'success',
                        'currency_value'            => $userData->currency_value,
                        'userData'                  => $userData,
                        'hotels_bookings'           => $hotels_bookings,
                        'transfer_bookings'         => $transfer_bookings,
                        'b2b_Agent'                 => $b2b_Agent,
                        'b2b_Agent_AD'              => $b2b_Agent_AD,
                        'b2b_Agent_Total_Balance'   => $b2b_Agent_Total_Balance,
                        'b2b_Agent_Total_Paid'      => $b2b_Agent_Total_Paid,
                        'b2b_Agent_Total_Remaining' => $b2b_Agent_Total_Remaining,
                    ]);
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    // React
    public function b2b_Add_Credit_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            // dd($request);
            
            $customer_Data                  = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                $b2b_agents                 = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->b2b_Agent_Id)->first();
                $b2b_Agent_Credit_Limits    = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->get();
                if($b2b_Agent_Credit_Limits->isempty()){
                    DB::table('b2b_Agent_Credit_Limits')->insert([
                        'token'                 => $request->token,
                        'customer_id'           => $customer_Data->id,
                        'b2b_Agent_Id'          => $request->b2b_Agent_Id,
                        'transaction_Id'        => $request->transaction_Id,
                        'booking_Amount'        => $request->booking_Amount,
                        'total_Amount'          => $request->booking_Amount,
                        'remaining_Amount'      => $request->booking_Amount,
                        'currency'              => $request->currency,
                        'status'                => $request->status,
                        'status_Type'           => 'Pending',
                        'payment_Method'        => $request->payment_Method,
                        'payment_Remarks'       => $request->payment_Remarks,
                        'services_Type'         => $request->services_Type,
                    ]);
                }else{
                    DB::table('b2b_Agent_Credit_Limits')->insert([
                        'token'             => $request->token,
                        'customer_id'       => $customer_Data->id,
                        'b2b_Agent_Id'      => $request->b2b_Agent_Id,
                        'transaction_Id'    => $request->transaction_Id,
                        'booking_Amount'    => $request->booking_Amount,
                        'total_Amount'      => $request->total_Amount + $request->booking_Amount,
                        'remaining_Amount'  => $request->remaining_Amount + $request->booking_Amount,
                        'currency'          => $request->currency,
                        'status'            => $request->status,
                        'status_Type'       => 'Pending',
                        'payment_Method'    => $request->payment_Method,
                        'payment_Remarks'   => $request->payment_Remarks,
                        'services_Type'     => $request->services_Type,
                    ]);
                }
                
                if(isset($b2b_agents->request_Credit_Limit) && $b2b_agents->request_Credit_Limit == 'Yes'){
                    DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->b2b_Agent_Id)->update(['request_Credit_Limit' => NULL]);
                }
                
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Agent Credit Limit Added Successfully!']);
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token Not Matched',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function b2b_View_Credit_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            $customer_Data                  = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                $b2b_Agent_Credit_Limits    = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Credit_Payment')->get();
                if($b2b_Agent_Credit_Limits->isempty()){
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Credit Limit Not Exist!',
                        'total_Amount'              => 0,
                        'remaining_Amount'          => 0,
                        'customer_Data'             => $customer_Data,
                    ]);
                }else{
                    // $total_Amount               = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Credit_Payment')->max('total_Amount');
                    $total_Amount               = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Credit_Payment')->orderBy('id', 'DESC')->get();
                    if($request->token == config('token_HaramaynRooms')){
                        $total_Amount           = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Credit_Payment')->where('status_Type', 'Approved')->orderBy('id', 'DESC')->get();    
                    }
                    $booking_Amount             = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Booking')->sum('booking_Amount');
                    $remaining_Amount           = $total_Amount[0]->total_Amount - $booking_Amount ;
                    
                    // return $total_Amount[0]->total_Amount;
                    
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Agent Credit Limit!',
                        'total_Amount'              => (float)$total_Amount[0]->total_Amount,
                        'remaining_Amount'          => (float)$remaining_Amount,
                        'customer_Data'             => $customer_Data,
                        'b2b_Agent_Credit_Limits'   => $b2b_Agent_Credit_Limits,
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token Not Matched',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function b2b_Check_Credit_Limit_Approved(Request $request){
        DB::beginTransaction(); 
        try {
            $customer_Data                  = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                $b2b_Agent_Credit_Limits    = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Credit_Payment')->where('status_Type','Approved')->get();
                if($b2b_Agent_Credit_Limits->isempty()){
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Credit Limit Not Exist!',
                        'total_Amount'              => 0,
                        'remaining_Amount'          => 0,
                        'customer_Data'             => $customer_Data,
                    ]);
                }else{
                    // $total_Amount               = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Credit_Payment')->max('total_Amount');
                    $total_Amount               = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Credit_Payment')->orderBy('id', 'DESC')->get();
                    $booking_Amount             = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Booking')->sum('booking_Amount');
                    $remaining_Amount           = $total_Amount[0]->total_Amount - $booking_Amount ;
                    
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Agent Credit Limit!',
                        'total_Amount'              => (float)$total_Amount[0]->total_Amount,
                        'remaining_Amount'          => (float)$remaining_Amount,
                        'customer_Data'             => $customer_Data,
                        'b2b_Agent_Credit_Limits'   => $b2b_Agent_Credit_Limits,
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token Not Matched',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function b2b_View_Credit_Limit_Booking(Request $request){
        DB::beginTransaction(); 
        try {
            $customer_Data                          = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                $b2b_Agent_Credit_Limits            = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->get();
                if($b2b_Agent_Credit_Limits->isempty()){
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Credit Limit Not Exist!',
                        'customer_Data'             => $customer_Data,
                    ]);
                }else{
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Agent Credit Limit!',
                        'customer_Data'             => $customer_Data,
                        'b2b_Agent_Credit_Limits'   => $b2b_Agent_Credit_Limits,
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token Not Matched',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    // React
    
    // Credit Limit
    public function allowed_B2B_Agents_Credit_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            $all_countries                      = country::all();
            $all_B2B_Data                       = [];
            $b2b_Agents                         = DB::table('b2b_agents')->where('token',$request->token)->where('approve_Status','1')->where('reject_Status',NULL)->orderBy('id', 'DESC')->get();
            foreach($b2b_Agents as $val){
                $b2b_Agent_Credit_Limits        = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$request->customer_id)->where('b2b_Agent_Id',$val->id)->get();
                if(count($b2b_Agent_Credit_Limits) > 0){
                    $b2b_Agent_Credit_Limits = $b2b_Agent_Credit_Limits;
                }else{
                    $b2b_Agent_Credit_Limits = NULL;
                }
                
                $b2b_Data                       = [
                    'b2b_Agents'                => $val,
                    'b2b_Agent_Credit_Limits'   => $b2b_Agent_Credit_Limits,
                ];
                array_push($all_B2B_Data,$b2b_Data);
            }
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','all_B2B_Data'=>$all_B2B_Data,'all_countries'=>$all_countries]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function allow_B2B_Agents_Credit_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            $b2b_agents        = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->get();
            if($b2b_agents->isempty()){
                DB::commit();
                return response()->json([
                    'status'                => 'error',
                ]);
            }else{
                DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->update(['allow_Credit_Limit' => 'Yes']);
                
                DB::commit();
                return response()->json([
                    'status'                => 'success',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function allow_Multiple_B2B_Agents_Credit_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            $selected_Agents    = json_decode($request->selected_Agents);
            // dd($selected_Agents);
            foreach($selected_Agents as $val_ID){
                $b2b_agents        = DB::table('b2b_agents')->where('token',$request->token)->where('id',$val_ID)->get();
                if($b2b_agents->isempty()){
                }else{
                    DB::table('b2b_agents')->where('token',$request->token)->where('id',$val_ID)->update(['allow_Credit_Limit' => 'Yes']);
                }
            }
            DB::commit();
            return response()->json([
                'status'                => 'success',
            ]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function stop_B2B_Agents_Credit_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            $b2b_agents        = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->get();
            if($b2b_agents->isempty()){
                DB::commit();
                return response()->json([
                    'status'                => 'error',
                ]);
            }else{
                DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->update(['allow_Credit_Limit' => NULL]);
                
                DB::commit();
                return response()->json([
                    'status'                => 'success',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function request_B2B_Agents_Credit_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            $customer_Data          = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                $b2b_agents        = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->get();
                if($b2b_agents->isempty()){
                    DB::commit();
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'Agent Not Exist',
                    ]);
                }else{
                    DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->update(['request_Credit_Limit' => 'Yes']);
                    
                    DB::commit();
                    return response()->json([
                        'status'    => 'success',
                        'message'   => 'Credit Limit Requested',
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token Not Matched',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_B2B_Agents_Credit_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            $customer_Data                          = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                $b2b_agents                         = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->id)->first();
                $b2b_Agent_Credit_Limits            = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->id)->where('status','Credit_Payment')->get();
                if($b2b_Agent_Credit_Limits->isempty()){
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Credit Limit Not Exist!',
                        'total_Amount'              => 0,
                        'remaining_Amount'          => 0,
                        'customer_Data'             => $customer_Data,
                        'b2b_agents'                => $b2b_agents,
                    ]);
                }else{
                    $total_Amount               = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->id)->where('status','Credit_Payment')->orderBy('id', 'DESC')->get();
                    $booking_Amount             = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->id)->where('status','Booking')->sum('booking_Amount');
                    $remaining_Amount           = $total_Amount[0]->total_Amount - $booking_Amount ;
                    
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Agent Credit Limit!',
                        'total_Amount'              => (float)$total_Amount[0]->total_Amount,
                        'remaining_Amount'          => (float)$remaining_Amount,
                        'customer_Data'             => $customer_Data,
                        'b2b_Agent_Credit_Limits'   => $b2b_Agent_Credit_Limits,
                        'b2b_agents'                => $b2b_agents,
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token Not Matched',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function edit_B2B_Agents_Credit_Limit(Request $request){
        // dd('OK');
        DB::beginTransaction(); 
        try {
            $customer_Data                          = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                $b2b_Agent_Credit_Limits            = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$request->customer_id)->where('id',$request->id)->first();
                if($b2b_Agent_Credit_Limits != NULL){
                    $b2b_agents                         = DB::table('b2b_agents')->where('token',$request->token)->where('id',$b2b_Agent_Credit_Limits->b2b_Agent_Id)->first();
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Agent Credit Limit!',
                        'customer_Data'             => $customer_Data,
                        'b2b_Agent_Credit_Limits'   => $b2b_Agent_Credit_Limits,
                        'b2b_agents'                => $b2b_agents,
                    ]);
                }else{
                    DB::commit();
                    return response()->json([
                        'status'                    => 'error',
                        'message'                   => 'Credit Limit Not Exist!',
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token Not Matched',
                ]);
            }
        } catch (Throwable $e) {
            dd($e);
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function b2b_Update_Credit_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            $customer_Data                          = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                $b2b_Agent_Credit_Limits            = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('id',$request->id)->get();
                if($b2b_Agent_Credit_Limits->isempty()){
                    DB::commit();
                    return response()->json(['status'=>'error','message'=>'Credit Limit Not Exist!']);
                }else{
                    $final_Booking_Amount           = 0;
                    $requested_Booking_Amount       = $request->booking_Amount;
                    $all_Credit_Limits              = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)
                                                        ->where('id','>=',$request->id)->get();
                    // dd($requested_Booking_Amount,$b2b_Agent_Credit_Limits,$all_Credit_Limits);
                    foreach($all_Credit_Limits as $val_ACL){
                        if($request->id == $val_ACL->id){
                            if($requested_Booking_Amount > $val_ACL->booking_Amount){
                                $final_Booking_Amount   = $requested_Booking_Amount - $val_ACL->booking_Amount;
                                // $booking_Amount         = $final_Booking_Amount + $val_ACL->booking_Amount;
                            }
                            
                            if($requested_Booking_Amount < $val_ACL->booking_Amount){
                                $final_Booking_Amount   = $val_ACL->booking_Amount - $requested_Booking_Amount;
                                // $booking_Amount         = $val_ACL->booking_Amount - $final_Booking_Amount;
                            }
                        }
                        
                        // if($final_Booking_Amount > 0){
                            $booking_Amount         = $final_Booking_Amount + $val_ACL->booking_Amount;
                        // }
                        // else{
                        //     $booking_Amount         = $val_ACL->booking_Amount;
                            
                        // }
                        
                        $total_Amount               = $final_Booking_Amount + $val_ACL->total_Amount;
                        $remaining_Amount           = $final_Booking_Amount + $val_ACL->remaining_Amount;
                        
                        DB::table('b2b_Agent_Credit_Limits')->where('id',$val_ACL->id)->update([
                            'booking_Amount'        => $booking_Amount,
                            'total_Amount'          => $total_Amount,
                            'remaining_Amount'      => $remaining_Amount,
                        ]);
                    }
                    DB::commit();
                    return response()->json(['status'=>'success','message'=>'Agent Credit Limit Updated Successfully!']);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token Not Matched',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_B2B_Agents_Credit_Limit_Approve(Request $request){
        DB::beginTransaction(); 
        try {
            DB::table('b2b_Agent_Credit_Limits')->where('id',$request->id)->update(['status_Type' => 'Approved']);
            DB::commit();
            return response()->json([
                'status'                    => 'success',
                'message'                   => 'Status Approved Successfully',
            ]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function b2b_View_Credit_Limit_Ledger(Request $request){
        DB::beginTransaction(); 
        try {
            $customer_Data                          = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($customer_Data != null){
                $b2b_Agent_Credit_Limits            = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->get();
                if($b2b_Agent_Credit_Limits->isempty()){
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Credit Limit Not Exist!',
                        'total_Amount'              => 0,
                        'remaining_Amount'          => 0,
                        'customer_Data'             => $customer_Data,
                    ]);
                }else{
                    $booking_Details            = DB::table('hotels_bookings')->where('customer_id',$customer_Data->id)->where('b2b_agent_id',$request->b2b_Agent_Id)->get();
                    $total_Amount               = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Credit_Payment')->orderBy('id', 'DESC')->get();
                    $booking_Amount             = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$customer_Data->id)->where('b2b_Agent_Id',$request->b2b_Agent_Id)->where('status','Booking')->sum('booking_Amount');
                    $remaining_Amount           = $total_Amount[0]->total_Amount - $booking_Amount ;
                    
                    DB::commit();
                    return response()->json([
                        'status'                    => 'success',
                        'message'                   => 'Agent Credit Limit!',
                        'total_Amount'              => (float)$total_Amount[0]->total_Amount,
                        'remaining_Amount'          => (float)$remaining_Amount,
                        'customer_Data'             => $customer_Data,
                        'b2b_Agent_Credit_Limits'   => $b2b_Agent_Credit_Limits,
                        'booking_Details'           => $booking_Details,
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Token Not Matched',
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    // Credit Limit
    
    public function make_Cancel_Request(Request $request){
        try {
            $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->first();
            if($booking_data){
                $customer_Data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                
                if($customer_Data->id == $booking_data->customer_id && $booking_data->b2b_agent_id == $request->b2b_agent_id){
                    if($booking_data->status == 'Confirmed' && $booking_data->after_Confirm_Status != 'Apply For Rejection'){
                        // Alsubaee Hotels
                        $customer_Data = DB::table('customer_subcriptions')->where('id',$booking_data->customer_id)->first();
                        if($customer_Data->Auth_key == config('token_Alsubaee')){
                            $from_Address               = config('mail_From_Address_Alsubaee');
                            $website_Title              = config('mail_Title_Alsubaee');
                            $mail_Template_Key          = config('mail_Template_Key_Alsubaee');
                            $website_Url                = config('website_Url_Alsubaee');
                            $mail_Address_Register_BPC  = config('mail_Address_Register_BPC');
                            // $mail_Address_Register_BPC  = 'ua758323@gmail.com';
                            $mail_Send_Status           = true;
                            $b2b_agents                 = DB::table('b2b_agents')->where('id',$request->b2b_agent_id)->first();
                            $b2b_Agent_First_Name       = $b2b_agents->first_name ?? '';
                            $b2b_Agent_Last_Name        = $b2b_agents->last_name ?? '';
                            $b2b_Agent_Name             = $b2b_Agent_First_Name.' '.$b2b_Agent_Last_Name;
                            
                            // return $booking_data;
                            
                            $currency                   = $booking_data->exchange_currency ?? 'SAR';
                            $total_Price                = $booking_data->exchange_price ?? '0';
                            $booking_Date               = Carbon::now();
                            $formatted_Booking_Date     = $booking_Date->format('d-m-Y H:i:s');
                            
                            $reservation_response       = json_decode($booking_data->reservation_response);
                            // return $reservation_response;
                            $hotel_details              = $reservation_response->hotel_details;
                            $room_Details               = $hotel_details->rooms;
                            $room_Message_Mail          = '';
                            foreach($room_Details as $val_RD){
                                $room_Data              = DB::table('rooms')->where('id',$val_RD->room_code)->first();
                                
                                $room_name              = $room_Data->room_type_name;
                                $meal_Type              = $room_Data->room_meal_type;
                                $room_Message_Mail      .= '<li>Room Type: '.$room_name.'</li><li>Meal Type: '.$meal_Type.'</li>';
                            }
                            
                            $check_in                   = Carbon::createFromFormat('Y-m-d', $hotel_details->checkIn);
                            $formatted_Check_In         = $check_in->format('d-m-Y');
                            $check_out                  = Carbon::createFromFormat('Y-m-d', $hotel_details->checkOut);
                            $formatted_Check_Out        = $check_out->format('d-m-Y');
                            
                            $details                    = [
                                'status'                => $booking_data->status,
                                'invoice_no'            => $booking_data->invoice_no,
                                'Check_In'              => $formatted_Check_In,
                                'Check_Out'             => $formatted_Check_Out,
                                'price'                 => $currency.' '.$total_Price,
                                'hotel_Name'            => $hotel_details->hotel_name ?? '',
                                'room_Message_Mail'     => $room_Message_Mail,
                            ];
                            
                            $email_Message_Register     = '<div> <h3> Dear Admin,</h3> <br> A cancellation request has been submitted by '.$website_Title.' , for Booking: '.$details['invoice_no'].'<br> Please login to dashboard and take action accordingly. <br><br> Thank you. </div>';
                            $reciever_Name              = $website_Title;
                            $mail_Check_Alsubaee        = Mail3rdPartyController::mail_Check_Alsubaee($from_Address,$mail_Address_Register_BPC,$reciever_Name,$email_Message_Register,$mail_Template_Key);
                            // return $mail_Check_Alsubaee;
                        }
                        
                        DB::table('hotels_bookings')->where('invoice_no',$request->invoice_no)->update([
                            'after_Confirm_Status'           => 'Apply For Rejection',
                            'cancellation_Request_Message'   => $request->cancellation_Request_Message,
                        ]);
                        
                        return response()->json([
                            'status'    => 'success',
                            'message'   => 'Cancellation Requested Successfully',
                        ]);
                    }else{
                        if($booking_data->after_Confirm_Status == 'Apply For Rejection'){
                            return response()->json([
                                'status'    => 'error',
                                'message'   => 'Request Already Submitted',
                            ]);
                        }
                        
                        return response()->json([
                            'status'    => 'error',
                            'message'   => 'Confirm Booking First!',
                        ]);
                    }
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'message'   => "Token Not Matched",
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => "Invoice Number Not Matched",
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong',
            ]);
        }
    }
    
    public function reject_Pyament_Booking(Request $request){
        try {
            $booking_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_Id)->first();
            if($booking_data){
                $customer_Data = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
                // return $customer_Data;
                
                DB::table('hotels_bookings')->where('invoice_no',$request->invoice_Id)->update([
                    'payment_Reject_Status' => 'Rejected',
                ]);
                
                if($request->token == config('token_HaramaynRooms')){
                    DB::table('hotels_bookings')->where('invoice_no',$request->invoice_Id)->update([
                        'payment_details'       => NULL,
                    ]);
                }
                
                $Invoice_data = DB::table('hotels_bookings')->where('invoice_no',$request->invoice_Id)->first();
                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Payment Rejected Successfully',
                    'Invoice_data'  => $Invoice_data,
                ]);
            }else{
                return response()->json([
                    'status'    => 'error',
                    'message'   => "Invoice Number Not Matched",
                ]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Something Went Wrong',
            ]);
        }
    }
}
