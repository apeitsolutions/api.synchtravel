<?php
namespace App\Http\Controllers\frontend\admin_dashboard\HasanatCoin;

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
use App\Http\Controllers\Mail3rdPartyController;

class HasanatCoinController extends Controller
{
    public function create_Hasanat_Coin_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            // $request->token         = '';
            $hasanat_Coins          = DB::table('hasanat_Coins')->where('token',$request->token)->first();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','hasanat_Coins'=>$hasanat_Coins]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function add_Hasanat_Coin_Limit(Request $request){
        DB::beginTransaction(); 
        try {
            $hasanat_Coins          = DB::table('hasanat_Coins')->where('token',$request->token)->first();
            if(!empty($hasanat_Coins) && $hasanat_Coins != null){
                DB::table('hasanat_Coins')->where('token',$request->token)->where('id',$request->id)->update([
                    'coins_Limit'   => $request->coins_Limit,
                ]);
            }else{
                DB::table('hasanat_Coins')->insert([
                    'token'         => $request->token,
                    'customer_id'   => $request->customer_id,
                    'coins_Limit'   => $request->coins_Limit,
                ]);
            }
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Hasanat Coins Limit Added Successfully!']);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function utilize_Hasanat_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            $hasanat_Coins          = DB::table('hasanat_Coins')->where('token',$request->token)->first();
            $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$request->token)->get();
            $utilize_Hasanat_Coins  = DB::table('utilize_Hasanat_Coins')->where('token',$request->token)->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','hasanat_Coins'=>$hasanat_Coins,'subscriptions_Packages'=>$subscriptions_Packages,'utilize_Hasanat_Coins'=>$utilize_Hasanat_Coins]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function hasanat_Coins_Statament(Request $request){
        DB::beginTransaction(); 
        try {
            $hasanat_Coins_Statament = DB::table('hasanat_Coins_Statament')->where('token',$request->token)->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','hasanat_Coins_Statament'=>$hasanat_Coins_Statament]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function create_Discount_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$request->token)->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','subscriptions_Packages'=>$subscriptions_Packages]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function add_Discount_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            // $discount_Coins          = DB::table('discount_Coins')->where('token',$request->token)->first();
            // if(!empty($discount_Coins) && $discount_Coins != null){
            //     DB::table('discount_Coins')->where('token',$request->token)->where('id',$request->id)->update([
            //         'coins_Limit'   => $request->coins_Limit,
            //     ]);
            // }else{
                DB::table('discount_Coins')->insert([
                    'token'             => $request->token,
                    'customer_id'       => $request->customer_id,
                    'package_Id'        => $request->package_Id,
                    'total_Coins'       => $request->total_Coins,
                    'coins_Percentage'  => $request->coins_Percentage,
                    'number_Of_Coins'   => $request->number_Of_Coins,
                ]);
            // }
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Discount Coins Added Successfully!']);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_Discount_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            $discount_Coins = DB::table('discount_Coins')->where('discount_Coins.token',$request->token)
                                ->join('subscriptions_Packages','discount_Coins.package_Id','subscriptions_Packages.id')
                                ->select('discount_Coins.*','subscriptions_Packages.*','discount_Coins.id as discount_Id')
                                ->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','discount_Coins'=>$discount_Coins]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function check_Discount_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            if($request->id != null){
                $discount_Coins = DB::table('discount_Coins')
                                    ->where('id','!=',$request->id)
                                    ->where('token',$request->token)
                                    ->where('customer_id',$request->customer_id)
                                    ->where('package_Id',$request->package_Id)
                                    ->where('total_Coins',$request->total_Coins)
                                    ->where('coins_Percentage',$request->coins_Percentage)
                                    ->where('number_Of_Coins',$request->number_Of_Coins)
                                    ->first();
            }else{
                $discount_Coins = DB::table('discount_Coins')
                                    ->where('token',$request->token)
                                    ->where('customer_id',$request->customer_id)
                                    ->where('package_Id',$request->package_Id)
                                    ->where('total_Coins',$request->total_Coins)
                                    ->where('coins_Percentage',$request->coins_Percentage)
                                    ->where('number_Of_Coins',$request->number_Of_Coins)
                                    ->first();
            }
            if(!empty($discount_Coins) && $discount_Coins != null){
                return response()->json(['status'=>'error','message'=>'Already Added!','discount_Coins'=>$discount_Coins]);
            }else{
                return response()->json(['status'=>'success','message'=>'Not Added!',]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function edit_Discount_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            $discount_Coins         = DB::table('discount_Coins')->where('token',$request->token)->where('id',$request->id)->first();
            $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$request->token)->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','subscriptions_Packages'=>$subscriptions_Packages,'discount_Coins'=>$discount_Coins]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function update_Discount_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            
            DB::table('discount_Coins')->where('token',$request->token)->where('id',$request->id)->update([
                'coins_Percentage'  => $request->coins_Percentage,
                'number_Of_Coins'   => $request->number_Of_Coins,
            ]);
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Discount Coins Updated Successfully!']);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function create_Purchase_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$request->token)->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','subscriptions_Packages'=>$subscriptions_Packages]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function get_Booked_Coins_Details(Request $request){
        DB::beginTransaction(); 
        try {
            $booked_Coins = DB::table('3rd_Party_Booking_Hasanat_Coins')
                                ->where('3rd_Party_Booking_Hasanat_Coins.token',$request->token)
                                ->where('3rd_Party_Booking_Hasanat_Coins.package_Id',$request->id)
                                ->join('subscriptions_Packages','3rd_Party_Booking_Hasanat_Coins.package_Id','subscriptions_Packages.id')
                                ->first();
            if(!empty($booked_Coins) && $booked_Coins != null){
                return response()->json(['status'=>'success','message'=>'Booking Available','booked_Coins'=>$booked_Coins]);
            }else{
                return response()->json(['status'=>'error','message'=>'Booking Not Available']);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function add_Purchase_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            $subscriptions_Packages         = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$request->package_Id)->first();
            $number_Of_Hasanat_Coins        = $subscriptions_Packages->number_Of_Hasanat_Coins + $request->purchase_Coins;
            
            DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$request->package_Id)->update([
                'number_Of_Hasanat_Coins'   => $number_Of_Hasanat_Coins,
            ]);
            
            DB::table('purchase_Coins')->insert([
                'token'                     => $request->token,
                'customer_id'               => $request->customer_id,
                'package_Id'                => $request->package_Id,
                'total_Coins'               => $request->total_Coins,
                'booked_Coins'              => $request->booked_Coins,
                'remaining_Coins'           => $request->remaining_Coins,
                'purchase_Price'            => $request->purchase_Price,
                'purchase_Coins'            => $request->purchase_Coins,
            ]);
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Purchase Coins Added Successfully!']);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_Purchase_Coins(Request $request){
        DB::beginTransaction(); 
        try {
            $purchase_Coins = DB::table('purchase_Coins')
                                ->join('subscriptions_Packages','purchase_Coins.package_Id','subscriptions_Packages.id')
                                ->where('purchase_Coins.token',$request->token)->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','purchase_Coins'=>$purchase_Coins]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function create_Hasanat_Credits(Request $request){
        DB::beginTransaction(); 
        try {
            $client_Details = [];
            $b2b_agents     = DB::table('b2b_agents')->where('token',$request->token)->get();
            if($b2b_agents->isEmpty()){
            }else{
                foreach($b2b_agents as $val_CD){
                    $subscriptions_Packages             = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$val_CD->select_Package)->first();
                    if($subscriptions_Packages != null){
                        $custom_Booking_Hasanat_Credits = DB::table('custom_Booking_Hasanat_Credits')->where('token',$request->token)->where('b2b_Agent_Id',$val_CD->id)->sum('booked_Credits');
                        // $hasanat_Credits                = DB::table('hasanat_Credits')->where('token',$request->token)->where('selected_Client',$val_CD->id)->sum('add_Credits');
                        $hasanat_CreditsD               = DB::table('hasanat_Credits')->where('token',$request->token)->where('selected_Client',$val_CD->id)->get();
                        // dd($hasanat_CreditsD);
                        $hasanat_Credits                = 0;
                        $todayDate                      = Carbon::now()->startOfDay();
                        if($hasanat_CreditsD->isEmpty()){
                            $hasanat_Credits = 0;
                        }else{
                            foreach($hasanat_CreditsD as $val_HC){
                                $credit_Expiry_Date    = Carbon::createFromFormat('d-m-Y', $val_HC->credit_Expiry_Date);
                                if ($credit_Expiry_Date->lessThan($todayDate)) {
                                    $hasanat_Credits += 0;
                                }else{
                                    $hasanat_Credits += $val_HC->add_Credits;
                                }
                            }
                        }
                        $client_OBJ = [
                            'selected_Client'   => $val_CD,
                            'selected_Package'  => $subscriptions_Packages,
                            'booked_Credits'    => $custom_Booking_Hasanat_Credits,
                            'hasanat_Credits'   => $hasanat_Credits,
                        ];
                        array_push($client_Details,$client_OBJ);
                    }else{
                        $client_OBJ = [
                            'selected_Client'   => $val_CD,
                            'selected_Package'  => NULL,
                            'booked_Credits'    => 0,
                            'hasanat_Credits'   => 0,
                        ];
                        array_push($client_Details,$client_OBJ);
                    }
                }
            }
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','client_Details'=>$client_Details]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public static function MailSend($request){
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
            $b2b_Agents                 = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->selected_Client)->first();
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
            // $to_Address                 = 'ua758323@gmail.com';
            $to_Address                 = $lead_email;
            $reciever_Name              = $lead_first_name;
            
            if($request->token == config('token_Alif')){
                $subscriptions_Packages     = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$b2b_Agents->select_Package)->first();
                $total_Credits              = $subscriptions_Packages->number_Of_Credits ?? '0';
                $hasanat_TC                 = DB::table('hasanat_Credits')->where('token',$request->token)->where('selected_Client',$request->selected_Client)->where('selected_Package',$b2b_Agents->select_Package)->get();
                if($hasanat_TC->isEmpty()){
                }else{
                    foreach($hasanat_TC as $val_TC){
                        $total_Credits      += $val_TC->add_Credits ?? '0';
                    }
                }
                $booked_Credits             = 0;
                $hasanat_BC                 = DB::table('custom_Booking_Hasanat_Credits')->where('token',$request->token)->where('b2b_Agent_Id',$request->selected_Client)->get();
                if($hasanat_BC->isEmpty()){
                }else{
                    foreach($hasanat_BC as $val_BC){
                        $booked_Credits     += $val_BC->booked_Credits ?? '0';
                    }
                }
                $email_Message              = '<div> <h3> Dear '.$details['lead_title'].' '.$details['lead_Name'].', </h3> Credits has been Added. Your selected package is '.$subscriptions_Packages->name_Of_Package.'. <br><br> <ul> <li>Total Credits: '.$total_Credits.' </li> <li>Booked Credits: '.$booked_Credits.' </li> <li>Remaining Credits: '.$b2b_Agents->total_Hasanat_Credits.' </li> </ul> <br><br> Do you have any questions or require further assistance, please do not hesitate to contact us. <br> <br> Regards, <br> '.$website_Title.' </div>';
                // dd($email_Message);
                $mail_Check                 = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
                // dd($mail_Check);
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
    
    public function add_Hasanat_Credits(Request $request){
        DB::beginTransaction(); 
        try {
            $client_Exist = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->selected_Client)->first();
            // dd($client_Exist);
            if($client_Exist != null){
                $total_Hasanat_Credits          = $client_Exist->total_Hasanat_Credits + $request->add_Credits;
                DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->selected_Client)->update(['total_Hasanat_Credits'=>$total_Hasanat_Credits]);
                
                $credit_Expiry_Date            = Carbon::now()->addDays(364)->format('d-m-Y');
                
                DB::table('hasanat_Credits')->insert([
                    'token'                     => $request->token,
                    'customer_id'               => $request->customer_id,
                    'selected_Client'           => $request->selected_Client,
                    'selected_Package'          => $request->selected_Package,
                    'total_Credits'             => $request->total_Credits,
                    'booked_Credits'            => $request->booked_Credits,
                    'add_Credits'               => $request->add_Credits,
                    'credit_Expiry_Date'        => $credit_Expiry_Date,
                ]);
                
                DB::table('custom_Booking_Hasanat_Credits')->insert([
                    'token'                     => $request->token,
                    'customer_id'               => $request->customer_id,
                    'b2b_Agent_Id'              => $request->selected_Client,
                    'package_Id'                => $request->selected_Package,
                    'add_Credits'               => $request->add_Credits,
                    'status'                    => 'add_Credits',
                ]);
                
                $mail_Send                      = $this->MailSend($request);
                // dd($mail_Send);
                
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Credits Added Successfully!']);
            }else{
                DB::commit();
                return response()->json(['status'=>'error','message'=>'Client Not Exist!']);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function renew_Hasanat_Credits(Request $request){
        DB::beginTransaction(); 
        try {
            $client_Exist = DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->client_Id)->first();
            // dd($client_Exist);
            if($client_Exist != null){
                $subscriptions_Packages = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$client_Exist->select_Package)->first();
                $booked_Credits         = DB::table('custom_Booking_Hasanat_Credits')->where('token',$request->token)->where('b2b_Agent_Id',$request->client_Id)->sum('booked_Credits');
                $todayDate              = Carbon::now()->startOfDay();
                $hasanat_CreditsD       = DB::table('hasanat_Credits')->where('token',$request->token)->where('selected_Client',$request->client_Id)->get();
                $hasanat_Credits        = 0;
                if($hasanat_CreditsD->isEmpty()){
                    $hasanat_Credits = 0;
                }else{
                    foreach($hasanat_CreditsD as $val_HC){
                        $credit_Expiry_Date    = Carbon::createFromFormat('d-m-Y', $val_HC->credit_Expiry_Date);
                        if ($credit_Expiry_Date->lessThan($todayDate)) {
                            $hasanat_Credits += 0;
                        }else{
                            $hasanat_Credits += $val_HC->add_Credits;
                        }
                    }
                }
                $total_Hasanat_Credits  = $hasanat_Credits + $subscriptions_Packages->number_Of_Credits;
                $total_Hasanat_Points   = $client_Exist->total_Hasanat_Points + $subscriptions_Packages->number_Of_Hasanat_Coins;
                // $total_Hasanat_Credits  = $client_Exist->total_Hasanat_Credits + $subscriptions_Packages->number_Of_Credits;
                $package_Expiry_Date    = Carbon::createFromFormat('d-m-Y', $client_Exist->package_Expiry_Date)->addDays(364)->format('d-m-Y');
                
                DB::table('b2b_agents')->where('token',$request->token)->where('id',$request->client_Id)->update([
                    'package_Expiry_Date'   => $package_Expiry_Date,
                    'total_Hasanat_Credits' => $total_Hasanat_Credits,
                    'total_Hasanat_Points'  => $total_Hasanat_Points,
                ]);
                
                // DB::table('hasanat_Credits')->insert([
                //     'token'                     => $request->token,
                //     'customer_id'               => $request->customer_id,
                //     'selected_Client'           => $request->client_Id,
                //     'selected_Package'          => $subscriptions_Packages->id,
                //     'total_Credits'             => $subscriptions_Packages->number_Of_Credits,
                //     'booked_Credits'            => $booked_Credits,
                //     'add_Credits'               => $subscriptions_Packages->number_Of_Credits,
                // ]);
                
                // DB::table('custom_Booking_Hasanat_Credits')->insert([
                //     'token'                     => $request->token,
                //     'customer_id'               => $request->customer_id,
                //     'b2b_Agent_Id'              => $request->client_Id,
                //     'package_Id'                => $subscriptions_Packages->id,
                //     'add_Credits'               => $subscriptions_Packages->number_Of_Credits,
                //     'status'                    => 'renew_Credits',
                // ]);
                
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Credits Renew Successfully!']);
            }else{
                DB::commit();
                return response()->json(['status'=>'error','message'=>'Client Not Exist!']);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function hasanat_Credit_Statement(Request $request){
        DB::beginTransaction(); 
        try {
            $hasanat_Credit_Statament               = [];
            $custom_Booking_Hasanat_Credits         = DB::table('custom_Booking_Hasanat_Credits')->where('token',$request->token)->get();
            $todayDate                              = Carbon::now()->startOfDay();
            if($custom_Booking_Hasanat_Credits->isEmpty()){
            }else{
                foreach($custom_Booking_Hasanat_Credits as $val_HCS){
                    $client_Details                 = DB::table('b2b_agents')->where('token',$request->token)->where('id',$val_HCS->b2b_Agent_Id)->first();
                    $subscriptions_Packages         = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$val_HCS->package_Id)->first();
                    $hotels_bookings                = DB::table('hotels_bookings')->where('invoice_no',$val_HCS->booking_Id)->first();
                    $hasanat_Credits_Status         = NULL;
                    $hasanat_Credits_Date           = NULL;
                    $hasanat_Credits                = DB::table('hasanat_Credits')->where('token',$request->token)->where('selected_Client',$val_HCS->b2b_Agent_Id)->where('created_at',$val_HCS->created_at)->first();
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
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','hasanat_Credit_Statament'=>$hasanat_Credit_Statament]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function hasanat_Coin_Statement(Request $request){
        DB::beginTransaction(); 
        try {
            $hasanat_Coin_Statament                 = [];
            $third_Party_Booking_Hasanat_Coins      = DB::table('3rd_Party_Booking_Hasanat_Coins')->where('token',$request->token)->get();
            if($third_Party_Booking_Hasanat_Coins->isEmpty()){
            }else{
                foreach($third_Party_Booking_Hasanat_Coins as $val_HCS){
                    $client_Details                 = DB::table('b2b_agents')->where('token',$request->token)->where('id',$val_HCS->b2b_Agent_Id)->first();
                    $subscriptions_Packages         = DB::table('subscriptions_Packages')->where('token',$request->token)->where('id',$val_HCS->package_Id)->first();
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
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','hasanat_Coin_Statament'=>$hasanat_Coin_Statament]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
}
