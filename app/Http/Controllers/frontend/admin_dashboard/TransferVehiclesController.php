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

class TransferVehiclesController extends Controller
{
    // Add Vehicles
    public function add_Vehicles(){
        $token = config('token');
        $customer_id = Session::get('id');
        $curl = curl_init();
        
        $data = array('token' => $token, 'customer_id' => $customer_id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/add_Vehicles',
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
        return view('template/frontend/userdashboard/pages/Transfer_Details/add_Vehicles',compact(['data']));
    }
    
    // Add New Vehicle
    public function add_new_vehicle(Request $request){
        $token = config('token');
        $customer_id = Session::get('id');
        $curl = curl_init();
        
        if($request->hasFile('vehicle_image')){
        
            $file = $request->file('vehicle_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/uploads/package_imgs/', $filename);
            $vehicle_image = $filename;
        }
        else{
            $vehicle_image = '';
        }
        $vehicle_image=$vehicle_image;
        
        $data = array(
            'token' => $token, 
            'customer_id' => $customer_id,
            // Data
            'vehicle_Name'          =>  $request->vehicle_Name,
            'vehicle_Description'   =>  $request->vehicle_Description,
            'vehicle_image'         =>  $vehicle_image,
            'vehicle_Status'        =>  $request->vehicle_Status,
            'vehicle_Type'          =>  $request->vehicle_Type,
            'vehicle_Passenger'     =>  $request->vehicle_Passenger,
            'vehicle_Door'          =>  $request->vehicle_Door,
            'vehicle_Transmission'  =>  $request->vehicle_Transmission,
            'vehicle_Baggage'       =>  $request->vehicle_Baggage,
            'vehicle_Picking'       =>  $request->vehicle_Picking,
            'meta_title'            =>  $request->meta_title,
            'meta_keywords'         =>  $request->meta_keywords, 
            'meta_desc'             =>  $request->meta_desc,
            'payment_option'        =>  $request->payment_option,
            'policy_and_terms'      =>  $request->policy_and_terms,
        );
        // print_r($data);die();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/add_new_vehicle',
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
        return redirect()->route('view_Vehicles');
    }
    
    // View Vehicles
    public function view_Vehicles(){
        $token = config('token');
        $customer_id = Session::get('id');
        $curl = curl_init();

        $data = array('token'=>$token,'customer_id'=>$customer_id);
        // print_r($data);die();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_Vehicles',
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
        $data = json_decode($response);
        // print_r($data);die();
        
        // foreach($data as $value){
        //     // print_r($value);
        //     foreach($value as $value1){
        //         print_r($value1->vehicle_Name);
        //     }die();
        // }die();
        
        return view('template/frontend/userdashboard/pages/Transfer_Details/view_Vehicles',compact('data'));
    }
    
    // Edit Vehicle Details
    public function edit_vehicle_details($id){
        $token = config('token');
        $customer_id = Session::get('id');
        $curl = curl_init();

        $data = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id);
        // print_r($data);die();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_vehicle_details/{id}',
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
        // print_r($data);die();
        
        // foreach($data as $value){
        //     print_r($value);
        // }die();
        
        return view('template/frontend/userdashboard/pages/Transfer_Details/edit_vehicle_details',compact('data'));
    }
    
    // Update Vehicle
    public function update_vehicle(Request $request,$id){
        $token = config('token');
        $customer_id = Session::get('id');
        $curl = curl_init();
        
        if($request->hasFile('vehicle_image')){
        
            $file = $request->file('vehicle_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/uploads/package_imgs/', $filename);
            $vehicle_image = $filename;
        }
        else{
            $vehicle_image = $request->vehicle_image_old;
        }
        $vehicle_image=$vehicle_image;
        
        $data = array(
            'token'         => $token, 
            'customer_id'   => $customer_id,
            'id'            => $id,
            // Data
            'vehicle_Name'          =>  $request->vehicle_Name,
            'vehicle_Description'   =>  $request->vehicle_Description,
            'vehicle_image'         =>  $vehicle_image,
            'vehicle_Status'        =>  $request->vehicle_Status,
            'vehicle_Type'          =>  $request->vehicle_Type,
            'vehicle_Passenger'     =>  $request->vehicle_Passenger,
            'vehicle_Door'          =>  $request->vehicle_Door,
            'vehicle_Transmission'  =>  $request->vehicle_Transmission,
            'vehicle_Baggage'       =>  $request->vehicle_Baggage,
            'vehicle_Picking'       =>  $request->vehicle_Picking,
            'meta_title'            =>  $request->meta_title,
            'meta_keywords'         =>  $request->meta_keywords, 
            'meta_desc'             =>  $request->meta_desc,
            'payment_option'        =>  $request->payment_option,
            'policy_and_terms'      =>  $request->policy_and_terms,
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/update_vehicle/{id}',
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
        return redirect()->route('view_Vehicles');
    }
    
    // View Destination
    public function view_Destination(){
        $token = config('token');
        $customer_id = Session::get('id');
        $curl = curl_init();

        $data = array('token'=>$token,'customer_id'=>$customer_id);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_Destination',
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
        $data                = $data1->data;
        $tranfer_destination = $data1->tranfer_destination;
        
        return view('template/frontend/userdashboard/pages/Transfer_Details/view_Destination',compact(['data','tranfer_destination']));
    }
    
    // Add New Destination
    public function add_new_destination(Request $request){
        $token = config('token');
        $customer_id = Session::get('id');
        $curl = curl_init();
        
        $vehicle_Name       = $request->vehicle_Name;
        $vehicle_Fare       = $request->vehicle_Fare;
        $fare_markup_type   = $request->fare_markup_type;
        $fare_markup        = $request->fare_markup;
        $total_fare_markup  = $request->total_fare_markup;
        
        if(isset($vehicle_Name))
        {
            $arrLength = count($vehicle_Name);
            for($i = 0; $i < $arrLength; $i++) {
                $vehicle_details[]=(object)[
                    'vehicle_Name'      => $vehicle_Name[$i] ?? '',
                    'vehicle_Fare'      => $vehicle_Fare[$i] ?? '',
                    'fare_markup_type'  => $fare_markup_type[$i] ?? '',
                    'fare_markup'       => $fare_markup[$i] ?? '',
                    'total_fare_markup' => $total_fare_markup[$i] ?? '',
                ];
            }
            $vehicle_details=json_encode($vehicle_details);
        }
        else
        {
           $vehicle_details = ''; 
        }
        
        $data = array(
            'token' => $token, 
            'customer_id' => $customer_id,
            // Data
            'pickup_City'       =>  $request->pickup_City,
            'dropof_City'       =>  $request->dropof_City,
            'vehicle_details'   =>  $vehicle_details,
        );
        
        // print_r($data);die();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/add_new_destination',
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
        return redirect()->route('view_Destination');
    }
    
    // Edit Destination Details
    public function edit_destination_details($id){
        $token = config('token');
        $customer_id = Session::get('id');
        $curl = curl_init();

        $data = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id);
        // print_r($data);die();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_destination_details/{id}',
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
        $tranfer_destination = $data1->tranfer_destination;
        $data = $data1->data;
        $vehicle_details = json_decode($tranfer_destination->vehicle_details);
        
        return view('template/frontend/userdashboard/pages/Transfer_Details/edit_destination_details',compact(['data','tranfer_destination','vehicle_details']));
    }
    
    // Update Destination
    public function update_destination(Request $request,$id){
        $token = config('token');
        $customer_id = Session::get('id');
        $curl = curl_init();
        
        $vehicle_Name       = $request->vehicle_Name;
        $vehicle_Fare       = $request->vehicle_Fare;
        $fare_markup_type   = $request->fare_markup_type;
        $fare_markup        = $request->fare_markup;
        $total_fare_markup  = $request->total_fare_markup;
        
        if(isset($vehicle_Name))
        {
            $arrLength = count($vehicle_Name);
            for($i = 0; $i < $arrLength; $i++) {
                $vehicle_details[]=(object)[
                    'vehicle_Name' => $vehicle_Name[$i] ?? '',
                    'vehicle_Fare' => $vehicle_Fare[$i] ?? '',
                    'fare_markup_type'  => $fare_markup_type[$i] ?? '',
                    'fare_markup'       => $fare_markup[$i] ?? '',
                    'total_fare_markup' => $total_fare_markup[$i] ?? '',
                ];
            }
            $vehicle_details=json_encode($vehicle_details);
        }
        else
        {
           $vehicle_details = ''; 
        }
        
        $data = array(
            'token'         => $token, 
            'customer_id'   => $customer_id,
            'id'            => $id,
            /// Data
            'pickup_City'       =>  $request->pickup_City,
            'dropof_City'       =>  $request->dropof_City,
            'vehicle_details'   =>  $vehicle_details,
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.synchronousdigital.com/api/update_destination/{id}',
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
        return redirect()->route('view_Destination');
    }
}