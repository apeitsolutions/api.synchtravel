<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\addManageQuotation;
use App\Models\AccountDetails;
use App\Models\viewBooking;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\country;
use DateTime;
use Carbon\Carbon;
use Session;
use DB;

class AccountDetailsController extends Controller
{
    
/*
|--------------------------------------------------------------------------
| AccountDetailsController Function Started
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Income Statement Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Income Statement list For Our Client.
*/
public function income_statement(){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/income_statement',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data1 = json_decode($response);
        $data = $data1->data1;
        // print_r($data);die();
        
        // foreach($data as $value){
        //     // print_r($value);
        //     $markup_details = json_decode($value->markup_details);
        //     // print_r($markup_details);
        //     foreach($markup_details as $value1){
        //         print_r($value1);
        //     }die();
        // }die();
        
        // $data = Db::table('tours')->join('view_booking_payment_recieve','tours.id','view_booking_payment_recieve.tourId')->get();
        
        return view('template/frontend/userdashboard/pages/account_Details/income_statement',compact(['data']));
    }
/*
|--------------------------------------------------------------------------
| Income Statement Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Income Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Expense Income list For Our Client.
*/ 
public function expenses_IncomeAll(){
        $data1 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->join('customer_subcriptions','tours.customer_id','customer_subcriptions.id')->get();
        return response()->json([
            'data'  => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Expense Income Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Income Client Wise data Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Expense Income Client Wise data list.
*/     
public function expenses_Income_client_wise_data($id){
        $data1 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$id)
                ->join('customer_subcriptions','tours.customer_id','customer_subcriptions.id')->get();
        return response()->json([
            'data'  => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Expense Income Client Wise data Ended
|--------------------------------------------------------------------------
*/  
/*
|--------------------------------------------------------------------------
| Expense Income Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Expense Income List For Our Client.
*/
public function expenses_Income(){
        $data1 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->join('customer_subcriptions','tours.customer_id','customer_subcriptions.id')->get();
        $customer_subcriptions  =   DB::table('customer_subcriptions')->get();
        return view('template/frontend/userdashboard/pages/account_Details/expenses_Income',compact(['data1','customer_subcriptions']));
    }
/*
|--------------------------------------------------------------------------
| Expense Income Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Out Standing Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Out Standing List For Our Client.
*/    
public function out_Standings(){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/out_Standings',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $data = $data->data1;
        return view('template/frontend/userdashboard/pages/account_Details/income_statement',compact('data'));
    }
/*
|--------------------------------------------------------------------------
| Out Standing Or Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Received Payments Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Received Payment List For Our Client.
*/
public function recieved_Payments(){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/recieved_Payments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $data = $data->data1;
        return view('template/frontend/userdashboard/pages/account_Details/income_statement',compact('data'));
    }
/*
|--------------------------------------------------------------------------
| Received Payments Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Stats Tours Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All stats Tour List For Our Client.
*/
public function stats_Tours(){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/stats_Tours',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $data1 = $data->data1;
        // $data2 = $data->data;
        // print_r($data1);die();
        // print_r($data2);die();
        $total_Amount = $data->total_Amount;
        $recieved_Amount = $data->recieved_Amount;
        $total_Amount_Activity = $data->total_Amount_Activity;
        $recieved_Amount_Activity = $data->recieved_Amount_Activity;
        $currency_SymbolU = $data->currency_Symbol;
        
        
        // foreach($data1 as $value){
        //     // print_r($value);
        //     $markup_details = json_decode($value->markup_details);
        //     // print_r($markup_details);
        //     if(isset($markup_details)){
        //         foreach($markup_details as $value1){
        //             print_r($value1->markup_price);
        //         }
        //     }
        // }die();
        
        return view('template/frontend/userdashboard/pages/account_Details/stats_Tours',compact(['data1','currency_SymbolU','total_Amount','recieved_Amount','total_Amount_Activity','recieved_Amount_Activity']));
    }
/*
|--------------------------------------------------------------------------
| Stats Tours Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Hotel Detail ID Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display all tour list.
*/
public function hotel_detail_ID($id) {
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
        // dd($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/hotel_detail_ID/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $data = json_decode($response);
        $data1 = $data->data1;
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Hotel Detail ID Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Flight Detail ID Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Flight Details.
*/    
public function flight_detail_ID($id) {
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
        // dd($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/flight_detail_ID/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $data = json_decode($response);
        $data1 = $data->data1;
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Flight Detail ID Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Transportation Detail ID Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Transportation Details.
*/    
public function transportation_detail_ID($id) {
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
        // dd($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/transportation_detail_ID/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $data = json_decode($response);
        $data1 = $data->data1;
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Transportation Detail ID Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Visa Detail ID Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Visa Details.
*/    
public function visa_detail_ID($id) {
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
        // dd($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/visa_detail_ID/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $data = json_decode($response);
        $data1 = $data->data1;
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Visa Detail ID Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Cancelled Tours Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Manage Quotation list.
*/    
public function cancelled_Tours(){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/cancelled_Tours',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $data = $data->data1;
        return view('template/frontend/userdashboard/pages/account_Details/income_statement',compact('data'));
    }
/*
|--------------------------------------------------------------------------
| Cancelled Tours Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| View Total Amount Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to count The Total Amount.
*/    
public function view_total_Amount($id) {
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
        // dd($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_total_Amount/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $tour_activity_check = $data->tour_activity_check;
        $total_Amount = $data->total_Amount;
        $total_Amount_Activity = $data->total_Amount_Activity;
        return response()->json([
            'tour_activity_check' => $tour_activity_check,
            'total_Amount' => $total_Amount,
            'total_Amount_Activity' => $total_Amount_Activity,
        ]);
      // echo $a ;
    }
/*
|--------------------------------------------------------------------------
| View Total Amount Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| View Receive Amount Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display active tour list and received amount.
*/    
public function view_recieve_Amount($id) {
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_recieve_Amount/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $tour_activity_check = $data->tour_activity_check;
        $recieved_Amount = $data->recieved_Amount;
        $recieved_Amount_Activity = $data->recieved_Amount_Activity;
        return response()->json([
            'tour_activity_check' => $tour_activity_check,
            'recieved_Amount' => $recieved_Amount,
            'recieved_Amount_Activity' => $recieved_Amount_Activity,
        ]);
    }
/*
|--------------------------------------------------------------------------
| View Receive Amount Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| View Out Standings Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display active tour list and view out standing amount.
*/    
public function view_Outstandings($id) {
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();

        $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_Outstandings/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        
        $tour_activity_check = $data->tour_activity_check;
        $total_Amount = $data->total_Amount;
        $total_Amount_Activity = $data->total_Amount_Activity;
        $recieved_Amount = $data->recieved_Amount;
        $recieved_Amount_Activity = $data->recieved_Amount_Activity;
        return response()->json([
            'tour_activity_check' => $tour_activity_check,
            'total_Amount' => $total_Amount,
            'total_Amount_Activity' => $total_Amount_Activity,
            'recieved_Amount' => $recieved_Amount,
            'recieved_Amount_Activity' => $recieved_Amount_Activity,
        ]);
   }
/*
|--------------------------------------------------------------------------
| View Out Standings Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| View Details Accomodation Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Accomodations list.
*/   
public function view_Details_Accomodation($id){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token, 'customer_id'=>$customer_id, 'id'=>$id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_Details_Accomodation/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data1 = json_decode($response);
        $data = $data1->data;
        
        $accomodation_details       = $data->accomodation_details;
        $accomodation_details_more  = $data->accomodation_details_more;
        
        $flights_details        = $data->flights_details;
        $flights_details_more   = $data->flights_details_more;
        
        $transportation_details        = $data->transportation_details;
        $transportation_details_more   = $data->transportation_details_more;
        
        return response()->json([
            'data'                          => $data,
            'accomodation_details'          => $accomodation_details,
            'accomodation_details_more'     => $accomodation_details_more,
            'flights_details'               => $flights_details,
            'flights_details_more'          => $flights_details_more,
            'transportation_details'        => $transportation_details,
            'transportation_details_more'   => $transportation_details_more,
        ]);
    }
/*
|--------------------------------------------------------------------------
| View Details Accomodation Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Accomodation Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Accomodation pay list.
*/    
public function acc_Pay($selected_city,$t_Id){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token' => $token, 'customer_id' => $customer_id, 'selected_city' => $selected_city, 't_Id' => $t_Id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/acc_Pay/{selected_city}/{t_Id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        // print_r($response);die();
        curl_close($curl);
        $data1 = json_decode($response);
        
        $data                    = $data1->data;
        $paid_accomodation       = $data1->paid_accomodation;
        
        return response()->json([
            'data'                  => $data,
            'paid_accomodation'     => $paid_accomodation, 
        ]);
    }
/*
|--------------------------------------------------------------------------
| Accomodation Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Submit Accomodation Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to insert the detail Accomodation pay.
*/    
public function sumbit_Accomodation_Pay(Request $request){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array(
                'token'=>$token, 
                'customer_id'=>$customer_id,
                // Transportation Data
                'tourId'                            => $request->tourId,
                'paying_date'                       => $request->paying_date,
                'customer_name'                     => $request->customer_name,
                'package_title'                     => $request->package_title,
                'selected_city'                     => $request->selected_city,
                'total_accomodation_amount'         => $request->total_accomodation_amount,
                'recieved_accomodation_amount'      => $request->recieved_accomodation_amount,
                'remaining_accomodation_amount'     => $request->remaining_accomodation_amount,
                'amount_accomodation_paid'          => $request->amount_accomodation_paid,
                
            );
            
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/sumbit_Accomodation_Pay',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        return redirect()->route('expenses_Income');
    }
/*
|--------------------------------------------------------------------------
|  Submit Accomodation Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Submit Flight Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to insert the detail flight pay.
*/    
public function sumbit_Flight_Pay(Request $request){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array(
                'token'=>$token, 
                'customer_id'=>$customer_id,
                // Transportation Data
                'tourId'                    => $request->tourId,
                'paying_date'               => $request->paying_date,
                'customer_name'             => $request->customer_name,
                'package_title'             => $request->package_title,
                'total_flight_amount'       => $request->total_flight_amount,
                'recieved_flight_amount'    => $request->recieved_flight_amount,
                'remaining_flight_amount'   => $request->remaining_flight_amount,
                'amount_flight_paid'        => $request->amount_flight_paid,
                
            );
            
            // print_r($data);die();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/sumbit_Flight_Pay',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        return redirect()->route('expenses_Income');
    }
/*
|--------------------------------------------------------------------------
|  Submit Flight Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Transportation Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Transportation pay.
*/    
public function transportation_Pay($id){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token, 'customer_id'=>$customer_id, 'id'=>$id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/transportation_Pay/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data1 = json_decode($response);
        
        $data                   = $data1->data;
        $customer_Data          = $data1->customer_Data;
        $paid_transportation    = $data1->paid_transportation;
        $paid_flights           = $data1->paid_flights;
        
        return response()->json([
            'data'                          => $data,
            'customer_Data'                 => $customer_Data,
            'paid_transportation'           => $paid_transportation,
            'paid_flights'                  => $paid_flights,
        ]);
    }
/*
|--------------------------------------------------------------------------
|  Transportation Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Submit Transportation Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to insert the detail Transportation pay.
*/    
public function sumbit_Transportation_Pay(Request $request){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array(
                'token'=>$token, 
                'customer_id'=>$customer_id,
                // Transportation Data
                'tourId'                            => $request->tourId,
                'paying_date'                       => $request->paying_date,
                'customer_name'                     => $request->customer_name,
                'package_title'                     => $request->package_title,
                'total_transportation_amount'       => $request->total_transportation_amount,
                'recieved_transportation_amount'    => $request->recieved_transportation_amount,
                'remaining_transportation_amount'   => $request->remaining_transportation_amount,
                'amount_transportation_paid'        => $request->amount_transportation_paid,
                
            );
            
            // print_r($data);die();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/sumbit_Transportation_Pay',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        return redirect()->route('expenses_Income');
    }
/*
|--------------------------------------------------------------------------
|  Submit Transportation Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  View Transportation Total Amount Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to calculate the total amount in transportation.
*/    
public function view_transportation_total_Amount($id) {
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
        // dd($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_transportation_total_Amount/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $view_transportation_total_Amount     = $data->view_transportation_total_Amount;
        $view_transportation_recieve_Amount   = $data->view_transportation_recieve_Amount;
        $view_flight_recieve_Amount           = $data->view_flight_recieve_Amount;
        return response()->json([
            'view_transportation_total_Amount'      => $view_transportation_total_Amount,
            'view_transportation_recieve_Amount'    => $view_transportation_recieve_Amount,
            'view_flight_recieve_Amount'            => $view_flight_recieve_Amount, 
        ]);
    }
/*
|--------------------------------------------------------------------------
|  View Transportation Total Amount Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Visa Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Get The Pay Visa Amount.
*/    
public function visa_Pay($id){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token, 'customer_id'=>$customer_id, 'id'=>$id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/visa_Pay/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        // print_r($response);die();
        curl_close($curl);
        $data1 = json_decode($response);
        
        $data           = $data1->data;
        $customer_Data  = $data1->customer_Data;
        $paid_visa  = $data1->paid_visa;
        
        return response()->json([
            'data'                          => $data,
            'customer_Data'                 => $customer_Data,
            'paid_visa'                     => $paid_visa,
        ]);
    }
/*
|--------------------------------------------------------------------------
|  Visa Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Submit Visa Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Insert The Visa Pay.
*/    
public function sumbit_Visa_Pay(Request $request){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array(
                'token'=>$token, 
                'customer_id'=>$customer_id,
                // Visa Data
                'tourId'                => $request->tourId,
                'paying_date'           => $request->paying_date,
                'customer_name'         => $request->customer_name,
                'package_title'         => $request->package_title,
                'total_visa_amount'     => $request->total_visa_amount,
                'recieved_visa_amount'  => $request->recieved_visa_amount,
                'remaining_visa_amount' => $request->remaining_visa_amount,
                'amount_visa_paid'      => $request->amount_visa_paid,
                
            );
            
            // print_r($data);die();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/sumbit_Visa_Pay',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        return redirect()->route('expenses_Income');
    }
/*
|--------------------------------------------------------------------------
|  Submit Visa Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  View Visa Total Amount Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Calculate the total visa amount.
*/    
public function view_visa_total_Amount($id) {
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
        // dd($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_visa_total_Amount/{id}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
             'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $view_visa_total_Amount     = $data->view_visa_total_Amount;
        $view_visa_recieve_Amount   = $data->view_visa_recieve_Amount;
        return response()->json([
            'view_visa_total_Amount'    => $view_visa_total_Amount,
            'view_visa_recieve_Amount'  => $view_visa_recieve_Amount,
        ]);
    }
/*
|--------------------------------------------------------------------------
|  View Visa Total Amount Ended
|--------------------------------------------------------------------------
*/






/*
|--------------------------------------------------------------------------
| AccountDetailsController Function Ended
|--------------------------------------------------------------------------
*/     
    
}