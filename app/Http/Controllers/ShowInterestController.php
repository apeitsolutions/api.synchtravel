<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Models\Show_Interest;
use App\Models\CustomerSubcription\CustomerSubcription;

class ShowInterestController extends Controller
{
    // Show Interest
    public function send_Whatsapp_HajjInterest($phone_No,$country,$customer_Name){
        $countries = DB::table('countries')->get();
        
        $first_PN   = substr($phone_No, 0, 1);
        if($first_PN == '+' || $first_PN == '0'){
            $phone_No   = substr($phone_No, 1);
        }else{
            $phone_No   = $phone_No;
        }
        
        $new_mobile = '';
        if(isset($country) && $country != null && $country != null){
            foreach($countries as $value_WAC) {
                if($country == $value_WAC->id || $country == $value_WAC->name){
                    $phonecode_Count    = preg_match_all('/[0-9]/', $value_WAC->phonecode);
                    $agent_No_Numbers   = '';
                    if($phonecode_Count == 2){
                        $agent_No_Numbers   = substr($phone_No, 0, 2);
                    }
                    
                    if($phonecode_Count == 3){
                        $agent_No_Numbers   = substr($phone_No, 0, 3);
                    }
                    
                    if($agent_No_Numbers != ''){
                        if($value_WAC->phonecode == $agent_No_Numbers){
                            $new_mobile  = '+'.$phone_No;
                        }else{
                            $new_mobile  = '+'.$value_WAC->phonecode.$phone_No;
                        }
                    }
                }
            }
        }else{
            $new_mobile = '+'.$agent_No;
        }
        
        $body = "Registration Open for Hajj 2024 - Take Action Now! \n\n Dear $customer_Name, \n
We are excited to announce that registrations for Hajj 2024 are now open through the NUSUK portal. To secure your spot, please follow the steps below:
1. Registration Process:
   - Click on the following link to begin your registration:
 [NUSUK Registration] https://hajj.nusuk.sa/registration/signup
   - Register as soon as possible to start the process.
2. Important Notes:
   - Registering on the platform is the first step; it does not guarantee your participation in Hajj 2024.
   - Have a copy of your passport and a selfie ready for upload.
   - Ensure your email address and phone number are accessible for verification codes.
3. Browser Recommendation:
   - Avoid using Chrome to prevent delays. Use Microsoft Edge or Firefox browsers.
   - Watch the video provided for further guidance.
4. Image Requirements:
   - Photos: 200 X 200, around 18 KB.
   - Passport: 800 X 400, up to 1 MB.
5. Troubleshooting Tips:
   - If you encounter issues uploading, use the provided links to minimize image resolution.
6. After Registration:
   - Once registered, email a screenshot of your approval to info@alhijaztours.net.
   - Attach a copy of your passport and photo for offline input until the booking portal launches.
7. Stay Informed:
   - Whether your registration is 100% complete or under review, visit the site every 48 hours for updates.
8. Group Bookings:
   - Consider providing this information to your clients for group bookings.
Thank you for your prompt attention to this matter. We look forward to facilitating a smooth registration process for Hajj 2024";
        // dd($body);
        $params = array(
            'token'     => '94w9dbh8fg4bnggl',
            'to'        => $new_mobile,
            'caption'   => 'https://www.google.com/',
            'body'      => $body,
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.ultramsg.com/instance60437/messages/chat',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        
        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        curl_close($curl);
        $new_response = json_decode($response);
        if (isset($new_response->error)) {
            return 'Something Went Wrong!';
        } else {
            return 'Check Your Whatsapp!';
        }
    }
    
    public function create_ShowInterest(Request $request){
        DB::beginTransaction();
        try {
            $userData                           = CustomerSubcription::where('Auth_key',$request->token)->first();
            
            $check_Email = Show_Interest::where('email_Address',$request->email_Address)->where('customer_id',$userData->id)->get();
            if(count($check_Email) > 0){
                return response()->json(['status'=>'error','message'=>'Email Already Exist!']);
            }else{
                $show_Interest                      = new Show_Interest();
                $show_Interest->customer_id         = $userData->id;
                $show_Interest->token               = $request->token;
                $show_Interest->email_Address       = $request->email_Address;
                $show_Interest->first_Name          = $request->first_Name;
                $show_Interest->last_Name           = $request->last_Name;  
                $show_Interest->no_Of_Passengers    = $request->no_Of_Passengers;
                $show_Interest->phone_No            = $request->phone_No;
                $show_Interest->street_Address      = $request->street_Address;
                $show_Interest->city                = $request->city;
                $show_Interest->post_Code           = $request->post_Code;
                $show_Interest->country             = $request->country;
                
                $show_Interest->save();
                
                if(isset($request->phone_No) && $request->phone_No != null && $request->phone_No != ''){
                    $check_Whatsapp = $this->send_Whatsapp_HajjInterest($request->phone_No,$request->country,$request->first_Name);
                }
                
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Data Added Successfully!']);
            }
            
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_ShowInterest(Request $request){
        DB::beginTransaction(); 
        try {
            // $Show_Interest = Show_Interest::where('token',$request->token)->whereYear('created_at', '>', 2024)->orderBy('id', 'DESC')->get();
            $filterDate     = Carbon::createFromFormat('d-m-Y', '05-12-2024')->startOfDay();
            $Show_Interest  = Show_Interest::where('token', $request->token)->where('created_at', '>', $filterDate)->orderBy('id', 'DESC')->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','Show_Interest'=>$Show_Interest]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_ShowInterest_2024(Request $request){
        DB::beginTransaction(); 
        try {
            $Show_Interest  = Show_Interest::where('token',$request->token)->whereYear('created_at', '<', 2025)->orderBy('id', 'DESC')->get();
            return response()->json(['status'=>'success','message'=>'Data Fetch Successfully!','Show_Interest'=>$Show_Interest]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function edit_ShowInterest(Request $request){
        DB::beginTransaction(); 
        try {
            $Show_Interest = Show_Interest::where('token',$request->token)->where('id',$request->id)->first();
            return response()->json(['status'=>'success','message'=>'Data Edit Successfully!','Show_Interest'=>$Show_Interest]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function update_ShowInterest(Request $request){
        DB::beginTransaction();
        try {
            $show_Interest                      = Show_Interest::find($request->id);
            $show_Interest->email_Address       = $request->email_Address;
            $show_Interest->first_Name          = $request->first_Name;
            $show_Interest->last_Name           = $request->last_Name;
            $show_Interest->no_Of_Passengers    = $request->no_Of_Passengers;
            $show_Interest->phone_No            = $request->phone_No;
            $show_Interest->street_Address      = $request->street_Address;
            $show_Interest->city                = $request->city;
            $show_Interest->post_Code           = $request->post_Code;
            $show_Interest->country             = $request->country;
            
            $show_Interest->update();
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Data Updated Successfully!','show_Interest'=>$show_Interest]);
            
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }

    public function deleteseat_ShowInterest(Request $request){
        $suppliers = Show_Interest::where('token',$request->token)->where('id',$request->id)->delete();
        if($suppliers = 1){
            return response()->json(['status'=>'success','message'=>'Data Deleted Successfully!']);
        }else{
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    // Show Interest
}
