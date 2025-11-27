<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\view_booking_payment_recieve;
use DB;
use Session;
use Illuminate\Support\Facades\Input;

class UmrahPackageControllerAdmin extends Controller
{
    // Hotel & Locations
    
        public function occupancy_tour(Request $request){
            $token = config('token');
            $customer_id = Session::get('id');
            $curl = curl_init();
            $id  = $request->id;
            
            $data2 = array('token' => $token,'customer_id' => $customer_id,'id' => $id);
            // dd($data2);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/occupancy_tour/{id}',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
    
            $response = curl_exec($curl);
            // print_r($response);die();
            curl_close($curl);
            $data = $response;
            // $data  = json_decode($data1);
            return response()->json(['data'=>$data]);
       }
        
        public function get_other_visa_type(Request $request){
          $token = config('token');
          $customer_id = Session::get('id');
          $curl = curl_init();
    
          $data2 = array('token'=>$token,'customer_id'=>$customer_id);
          curl_setopt_array($curl, array(
             CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_other_visa_type',
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS =>  $data2,
             CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
             ),
          ));
    
          $response = curl_exec($curl);
          curl_close($curl);
          $visa_type = $response;
          
            return response()->json(['visa_type'=>$visa_type]);
          
       }
        
        public function submit_other_visa_type(Request $request){
          $token = config('token');
          $customer_id = Session::get('id');
          $curl = curl_init();
    
          $data2 = array('token'=>$token,'customer_id'=>$customer_id,'other_visa_type'=>$request->other_visa_type);
          curl_setopt_array($curl, array(
             CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_other_visa_type',
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS =>  $data2,
             CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
             ),
          ));
    
          $response = curl_exec($curl);
          echo $response;die();
          curl_close($curl);
          $data2 = json_decode($response);
          $visa_type_get = $data2->visa_type_get;
          return response()->json(['message'=>'Visa Other Type Added SuccessFUl!','visa_type_get'=>$visa_type_get]);
    
       }
       
        public function get_other_Hotel_Name(Request $request){
            $token = config('token');
            $customer_id = Session::get('id');
            $curl = curl_init();
    
            $data2 = array('token'=>$token,'customer_id'=>$customer_id);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_other_Hotel_Name',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
    
            $response = curl_exec($curl);
            // echo $response;die();
            curl_close($curl);
            $hotel_Name = $response;
            // $hotel_Type = $response->hotel_Type;
            print_r($hotel_Name);die();
            return response()->json(['hotel_Name'=>$hotel_Name]);
        }
    
        public function submit_other_Hotel_Name(Request $request){
            $token = config('token');
            $customer_id = Session::get('id');
            $curl = curl_init();
    
            $data2 = array('token'=>$token,'customer_id'=>$customer_id,'other_Hotel_Name'=>$request->other_Hotel_Name);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_other_Hotel_Name',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
    
            $response = curl_exec($curl);
            // echo $response;die();
            curl_close($curl);
            $data2 = json_decode($response);
            $hotel_Name_get = $data2->hotel_Name_get;
            return response()->json(['message'=>'Other Hotel Type Added SuccessFUl!','hotel_Name_get'=>$hotel_Name_get]);
    
        }
        
        public function get_pickup_dropof_location(Request $request){
            $token = config('token');
            $customer_id = Session::get('id');
            $curl = curl_init();
    
            $data2 = array('token'=>$token,'customer_id'=>$customer_id);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_pickup_dropof_location',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
    
            $response = curl_exec($curl);
            // echo $response;die();
            curl_close($curl);
            $data = json_decode($response);
            $pickup_location_get= $data->pickup_location_get;
            $dropof_location_get = $data->dropof_location_get;
            return response()->json(['pickup_location_get'=>$pickup_location_get,'dropof_location_get'=>$dropof_location_get]);
        }
        
        public function submit_pickup_location(Request $request){
            $token = config('token');
            $customer_id = Session::get('id');
            $curl = curl_init();
    
            $data2 = array('token'=>$token,'customer_id'=>$customer_id,'pickup_location'=>$request->pickup_location);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_pickup_location',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
    
            $response = curl_exec($curl);
            echo $response;die();
            curl_close($curl);
            $data2 = json_decode($response);
            $pickup_location = $data2->pickup_location;
            return response()->json(['message'=>'Pickup Location Added SuccessFUl!','pickup_location'=>$pickup_location]);
    
        }
        
        public function submit_dropof_location(Request $request){
            $token = config('token');
            $customer_id = Session::get('id');
            $curl = curl_init();
    
            $data2 = array('token'=>$token,'customer_id'=>$customer_id,'dropof_location'=>$request->dropof_location);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_dropof_location',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
    
            $response = curl_exec($curl);
            echo $response;die();
            curl_close($curl);
            $data2 = json_decode($response);
            $dropof_location = $data2->dropof_location;
            return response()->json(['message'=>'Dropof Location Added SuccessFUl!','dropof_location'=>$dropof_location]);
    
        }
        
        public function submitForm_Airline_Name(Request $request){
            $token = '5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
            $customer_id = Session::get('id');
            $curl = curl_init();
    
            $data2 = array('token'=>$token,'customer_id'=>$customer_id,'other_Airline_Name'=>$request->other_Airline_Name);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submitForm_Airline_Name',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
    
            $response = curl_exec($curl);
            // echo $response;die();
            curl_close($curl);
            $data2 = json_decode($response);
            $other_Airline_Name_get = $data2->other_Airline_Name_get;
            return response()->json(['message'=>'Airline Name Added SuccessFUl!','other_Airline_Name_get'=>$other_Airline_Name_get]);
    
        }
        
        public function get_other_Airline_Name(Request $request){
            $token = '5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
            $customer_id = Session::get('id');
            $curl = curl_init();
    
            $data2 = array('token'=>$token,'customer_id'=>$customer_id);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_other_Airline_Name',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
    
            $response = curl_exec($curl);
            curl_close($curl);
            $airline_Name = $response;
            return response()->json(['airline_Name'=>$airline_Name]);
        }
        
        // public function get_other_Hotel_Type(Request $request){
        //     $token = config('token');
        //     $customer_id = Session::get('id');
        //     $curl = curl_init();
    
        //     $data2 = array('token'=>$token,'customer_id'=>$customer_id);
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_other_Hotel_Type',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS =>  $data2,
        //         CURLOPT_HTTPHEADER => array(
        //          'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
        //         ),
        //     ));
    
        //     $response = curl_exec($curl);
        //     echo $response;die();
        //     curl_close($curl);
        //     $hotel_Type = $response;
        //     return response()->json(['hotel_Type'=>$hotel_Type]);
        // }
    
        public function submit_other_Hotel_Type(Request $request){
            $token = config('token');
            $customer_id = Session::get('id');
            $curl = curl_init();
    
            $data2 = array('token'=>$token,'customer_id'=>$customer_id,'other_Hotel_Type'=>$request->other_Hotel_Type);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_other_Hotel_Type',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
    
            $response = curl_exec($curl);
            echo $response;die();
            curl_close($curl);
            $data2 = json_decode($response);
            $hotel_Type_get = $data2->hotel_Type_get;
            return response()->json(['message'=>'Other Hotel Type Added SuccessFUl!','hotel_Type_get'=>$hotel_Type_get]);
    
        }
    // End Hotel & Locations
    
    // Tour Bookings
        public function view_ternary_bookingsAll(){
            $data  =   DB::table('tours_bookings')
                            ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                            ->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('pakage_type','tour')
                            ->get();
            return response()->json([
                'data'  => $data,
            ]);
        }
        
        public function client_wise_data($id){
            $data   =   DB::table('tours_bookings')
                            ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                            ->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('pakage_type','tour')
                            ->where('customer_id',$id)
                            ->get();
            // print_r($customer_subcriptions);die();
            return response()->json([
                'data'  => $data,
            ]);
        }
        
        public function view_ternary_bookings(){
            $data  =   DB::table('tours_bookings')
                            ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                            ->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('pakage_type','tour')
                            ->get();
            
            $customer_subcriptions  =   DB::table('customer_subcriptions')->get();
            // print_r($customer_subcriptions);die();
            return view('template/frontend/userdashboard/pages/tour/view_ternary_bookings',compact(['data','customer_subcriptions']));
        }
    
        public function view_ternary_bookings_tourId($P_Id){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
        
        $data2 = array('token'=>$token,'customer_id'=>$customer_id,'P_Id'=>$P_Id);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_ternary_bookings_tourId/{P_Id}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $data2    = json_decode($response);
        $a        = $data2->a;
        $recieved = $data2->recieved;
        $price    = $data2->price;
        return response()->json([
            'a'        => $a,
            'recieved' => $recieved,
            'price'    => $price,
         ]);
        }
    
        public function view_confirmed_bookings(){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array('token'=>$token,'customer_id'=>$customer_id);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_confirmed_bookings',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
        $data2 = json_decode($response);
        $data1 = $data2->data1;
        $data  = $data2->data;
        
        return view('template/frontend/userdashboard/pages/tour/view_confirmed_bookings',compact(['data','data1']));
      }
    
        public function view_booking_payment(Request $request){
            $id     = $request->id;
            $P_Id   = $request->P_Id;
            
            $customer_id = auth()->guard('web')->user()->id; 
            // dd($id);
            function view_booking_payment_Response($customer_id,$id,$P_Id) {
                $url ="https://client1.synchronousdigital.com/client1_api.php";
                $data = array('case' => 'view_booking_payment','customer_id'=>$customer_id,'id'=>$id,'P_Id'=>$P_Id);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $responseData = curl_exec($ch);
                if(curl_errno($ch)) {
                    return curl_error($ch);
                }
                curl_close($ch);
                return $responseData;
            }
    
            $responseData2 = view_booking_payment_Response($customer_id,$id,$P_Id);
            // echo $responseData2;die();
            $data2 = json_decode($responseData2);
            $data = $data2->data1;
            // print_r($data2);die();
            $cart_details = $data2->cart_details1;
            $decode_customer_data = $data2->decode_customer_data1;
            $amount_paid = $data2->amount_paid;
            
            
            return view('template/frontend/userdashboard/pages/tour/view_booking_payment',compact(['data','cart_details','decode_customer_data','amount_paid']));
        }
    
        public function view_booking_payment_recieve(Request $request,$tourId){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array(
          'token'=>$token,
          'customer_id'=>$customer_id,
          'tourId'=>$tourId,
          // Data
          'package_id'       => $request->package_id,
          'date'             => $request->date,
          'customer_name'    => $request->customer_name,
          'package_title'    => $request->package_title,
          'recieved_amount'  => $request->recieved_amount,
          'total_amount'     => $request->total_amount,
          'remaining_amount' => $request->remaining_amount,
          'amount_paid'      => $request->amount_paid,
        );
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_booking_payment_recieve/{tourId}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
        $data2 = json_decode($response);
    
        return redirect()->route('view_ternary_bookings');
      }
    
        public function view_booking_detail($id,$P_Id){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id,'P_Id'=>$P_Id);
        // dd($data2);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_booking_detail/{id}/{P_Id}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
        $data2 = json_decode($response);
        $data = $data2->data1;
        $cart_details = $data2->cart_details1;
        $decode_customer_data = $data2->decode_customer_data1;
        // print_r($cart_details);die();
    
        return view('template/frontend/userdashboard/pages/tour/view_booking_detail',compact(['data','cart_details','decode_customer_data']));
      }
      
        public function view_booking_customer_details($id){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id);
        // dd($data2);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_booking_customer_details/{id}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
        $data2 = json_decode($response);
        $data = $data2->data1;
        
        $passenger_detail = json_decode($data->passenger_detail);
        // print_r($passenger_detail);die();
    
        return view('template/frontend/userdashboard/pages/tour/view_booking_customer_details',compact(['data','passenger_detail']));
      }
    
        public function confirmed_tour_booking($id){
        $token = config('token');
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id);
        // dd($data2);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/confirmed_tour_booking/{id}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $data2 = json_decode($response);
        $data  = $data2->data;
    
        // print_r($data);die();
    
        return redirect()->route('view_confirmed_bookings',compact('data'));;
      }
    // End Tour Bookings
    
    // Activity Bookings
        
        public function view_ternary_bookings_ActivityAll(){
            $data  =   DB::table('tours_bookings')
                            ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                            ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                            ->where('pakage_type','activity')
                            ->get();
            return response()->json([
                'data'  => $data,
            ]);
        }
        
        public function client_wise_data_Activity($id){
            $data   =   DB::table('tours_bookings')
                            ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                            ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                            ->where('pakage_type','activity')
                            ->where('customer_id',$id)
                            ->get();
            // print_r($customer_subcriptions);die();
            return response()->json([
                'data'  => $data,
            ]);
        }
        
        public function view_ternary_bookings_Activity(){
            $data   = DB::table('tours_bookings')
                            ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                            ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                            ->where('pakage_type','activity')
                            ->get();
                            
            $customer_subcriptions  =   DB::table('customer_subcriptions')->get();
            return view('template/frontend/userdashboard/pages/tour/view_ternary_bookings_Activity',compact(['data','customer_subcriptions']));
        }
      
        public function view_ternary_bookings_tourId_Activity($P_Id){
            $token = '5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
            $customer_id = auth()->guard('web')->user()->id;
            $curl = curl_init();
        
            $data2 = array('token'=>$token,'customer_id'=>$customer_id,'P_Id'=>$P_Id);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_ternary_bookings_tourId_Activity/{P_Id}',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $data2,
                CURLOPT_HTTPHEADER => array(
                    'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                ),
            ));
        
            $response = curl_exec($curl);
            curl_close($curl);
            $data2    = json_decode($response);
            $a        = $data2->a;
            $recieved = $data2->recieved;
            $price    = $data2->price;
            return response()->json([
                'a'        => $a,
                'recieved' => $recieved,
                'price'    => $price,
            ]);
        }
    
        public function view_confirmed_bookings_Activity(){
        $token = '5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array('token'=>$token,'customer_id'=>$customer_id);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_confirmed_bookings_Activity',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
        $data2 = json_decode($response);
        $data1 = $data2->data1;
        $data  = $data2->data;
    
        return view('template/frontend/userdashboard/pages/tour/view_confirmed_bookings_Activity',compact(['data','data1']));
      }
    
        public function view_booking_payment_Activity($id,$P_Id){
        $token = '5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id,'P_Id'=>$P_Id);
        // dd($data2);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_booking_payment_Activity/{id}/{P_Id}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $data2 = json_decode($response);
        $data = $data2->data1;
        $cart_details = $data2->cart_details1;
        $decode_customer_data = $data2->decode_customer_data1;
        $amount_paid = $data2->amount_paid;
    
        // print_r($decode_customer_data);die();
    
        return view('template/frontend/userdashboard/pages/tour/view_booking_payment_Activity',compact(['data','cart_details','decode_customer_data','amount_paid']));
      }
    
        public function view_booking_payment_recieve_Activity(Request $request,$tourId){
        $token = '5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array(
          'token'=>$token,
          'customer_id'=>$customer_id,
          'tourId'=>$tourId,
          // Data
          'package_id'       => $request->package_id,
          'date'             => $request->date,
          'customer_name'    => $request->customer_name,
          'package_title'    => $request->package_title,
          'recieved_amount'  => $request->recieved_amount,
          'total_amount'     => $request->total_amount,
          'remaining_amount' => $request->remaining_amount,
          'amount_paid'      => $request->amount_paid,
        );
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_booking_payment_recieve_Activity/{tourId}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
        $data2 = json_decode($response);
    
        return redirect()->route('view_ternary_bookings_Activity');
      }
    
        public function view_booking_detail_Activity($id,$P_Id){
        $token = '5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id,'P_Id'=>$P_Id);
        // dd($data2);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_booking_detail_Activity/{id}/{P_Id}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
        $data2 = json_decode($response);
        $data = $data2->data1;
        $cart_details = $data2->cart_details1;
        $decode_customer_data = $data2->decode_customer_data1;
        // print_r($cart_details);die();
    
        return view('template/frontend/userdashboard/pages/tour/view_booking_detail_Activity',compact(['data','cart_details','decode_customer_data']));
      }
      
        public function view_booking_customer_details_Activity($id){
        $token = '5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id);
        // dd($data2);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_booking_customer_details_Activity/{id}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
        $data2 = json_decode($response);
        $data = $data2->data1;
        // print_r($data);die();
        
        $passenger_detail = json_decode($data->passenger_detail);
        // print_r($passenger_detail);die();
    
        return view('template/frontend/userdashboard/pages/tour/view_booking_customer_details_Activity',compact(['data','passenger_detail']));
      }
    
        public function confirmed_tour_booking_Activity($id){
        $token = '5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
        $customer_id = auth()->guard('web')->user()->id;
        $curl = curl_init();
    
        $data2 = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id);
        // dd($data2);
        curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://admin.synchronousdigital.com/api/confirmed_tour_booking_Activity/{id}',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>  $data2,
           CURLOPT_HTTPHEADER => array(
               'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
           ),
        ));
    
        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $data2 = json_decode($response);
        $data  = $data2->data;
    
        // return view('template/frontend/userdashboard/pages/tour/view_confirmed_bookings_Activity',compact('data'));
        return redirect()->route('view_confirmed_bookings_Activity',compact('data'));
      }
    // End Activity Bookings
    
  public function create()
  {
     
      return view('template/frontend/userdashboard/pages/umrah_packages/create_umrah_packages');
  }
  public function store(Request $request)
    {
     
    $package_name=$request->package_name;
    
    
    
     $no_of_pax_days=$request->no_of_pax_days;
      $content=$request->content;
       
    
    
    
    
    $check_in=$request->check_in;
    $check_out=$request->check_out;
    $status=$request->status;
    
    $makkah_board_basis=$request->makkah_board_basis;
    

    if($request->hasFile('makkah_images')){

      $file = $request->file('makkah_images');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('public/uploads/package_imgs/', $filename);
      $makkah_images = $filename;
  }
  else{
      $makkah_images = '';
  }
    $makkah_images=$makkah_images;
    
    
$acc_hotel_name=$request->acc_hotel_name;
$acc_check_in=$request->acc_check_in;
$acc_check_out=$request->acc_check_out;
$acc_type=$request->acc_type;
$acc_qty=$request->acc_qty;
$acc_pax=$request->acc_pax;
$acc_price=$request->acc_price;
$acc_currency=$request->acc_currency;
$acc_commision=$request->acc_commision;
$acc_sale_Porice=$request->acc_sale_Porice;
$acc_total_amount=$request->acc_total_amount;
$acc_no_of_nightst=$request->acc_no_of_nightst;

if(isset($acc_type))
{
    $arrLength = count($acc_type);
        for($i = 0; $i < $arrLength; $i++) {
            $accomodation_details[]=(object)[
    
    
    'acc_type'=>$acc_type[$i],
     'acc_qty'=>$acc_qty[$i],
    'acc_pax'=>$acc_pax[$i],
    'acc_price'=>$acc_price[$i],
    'acc_currency'=>$acc_currency[$i],
     'acc_commision'=>$acc_commision[$i],
    'acc_sale_Porice'=>$acc_sale_Porice[$i],
    'acc_total_amount'=>$acc_total_amount[$i],
   
    ];
        }
    $accomodation_details=$accomodation_details;
 
    $makkah_details=[
        'acc_hotel_name'=>$acc_hotel_name,
        'acc_check_in'=>$acc_check_in,
        'acc_check_out'=>$acc_check_out,
        'acc_no_of_nightst'=>$acc_no_of_nightst,    
        'makkah_board_basis'=>$makkah_board_basis,
        'makkah_images'=>$makkah_images,
        'makkh_more_details'=>$accomodation_details,
    ];
     
    $makkah_details=json_encode($makkah_details);
}
else
{
   $makkah_details = ''; 
}

$madina_board_basis=$request->madina_board_basis;
    

    if($request->hasFile('madina_images')){

      $file = $request->file('madina_images');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('public/uploads/package_imgs/', $filename);
      $madina_images = $filename;
  }
    else{
        $madina_images = '';
    }
    $madina_images=$madina_images;



$madina_hotel_name=$request->madina_hotel_name;
$madina_check_in=$request->madina_check_in;
$madina_check_out=$request->madina_check_out;

$madina_type=$request->madina_type;
$madina_qty=$request->madina_qty;
$madina_pax=$request->madina_pax;
$madina_price=$request->madina_price;
$madina_currency=$request->madina_currency;
$madina_commision=$request->madina_commision;
$madina_sale_Porice=$request->madina_sale_Porice;
$madina_total_amount=$request->madina_total_amount;

$madina_no_of_nightst=$request->madina_no_of_nightst;

if(isset($madina_type))
{
   $arrLength = count($madina_type);
      for($i = 0; $i < $arrLength; $i++) {

$madina_accomodation_details[]=(object)[
    
    
    'madina_type'=>$madina_type[$i],
     'madina_qty'=>$madina_qty[$i],
    'madina_pax'=>$madina_pax[$i],
    'madina_price'=>$madina_price[$i],
    'madina_currency'=>$madina_currency[$i],
     'madina_commision'=>$madina_commision[$i],
    'madina_sale_Porice'=>$madina_sale_Porice[$i],
    'madina_total_amount'=>$madina_total_amount[$i],
   
    ];

}

 $madina_accomodation_details=$madina_accomodation_details;
 
 
 $madina_details=[
     'madina_hotel_name'=>$madina_hotel_name,
      'madina_check_in'=>$madina_check_in,
       'madina_check_out'=>$madina_check_out,
        'madina_no_of_nightst'=>$madina_no_of_nightst,
        
        'madina_board_basis'=>$madina_board_basis,
        'madina_images'=>$madina_images,
        
         'madina_more_details'=>$madina_accomodation_details,
     ];
     
     $madina_details=json_encode($madina_details);






}
else
{
   $madina_details = ''; 
}



if($request->hasFile('transfer_images')){

      $file = $request->file('transfer_images');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('public/uploads/package_imgs/', $filename);
      $transfer_images = $filename;
  }
  else{
      $transfer_images = '';
  }
    $transfer_images=$transfer_images;



$transportation_vehicle_type=$request->transportation_vehicle_type;
$transportation_no_of_vehicle=$request->transportation_no_of_vehicle;
$transportation_root_type=$request->transportation_root_type;
$transportation_total_availAble_seat=$request->transportation_total_availAble_seat;
$transportation_price_per_person=$request->transportation_price_per_person;

$Makkah_Mazarat_Included=$request->Makkah_Mazarat_Included;
        $Makkah_and_Madina_Mazarat_Included=$request->Makkah_and_Madina_Mazarat_Included;
         $Madina_Mazarat_Included=$request->Madina_Mazarat_Included;


$transportation_details=[
    'transportation_vehicle_type'=>$transportation_vehicle_type,
    'transportation_no_of_vehicle'=>$transportation_no_of_vehicle,
    'transportation_root_type'=>$transportation_root_type,
    'transportation_total_availAble_seat'=>$transportation_total_availAble_seat,
    'transportation_price_per_person'=>$transportation_price_per_person,
    
    'Makkah_Mazarat_Included'=>$Makkah_Mazarat_Included,
    'Makkah_and_Madina_Mazarat_Included'=>$Makkah_and_Madina_Mazarat_Included,
    'Madina_Mazarat_Included'=>$Madina_Mazarat_Included,
    'transfer_images'=>$transfer_images,
    ];

 $transfer_details=json_encode($transportation_details);



$more_transportation_vehicle_type=$request->more_transportation_vehicle_type;
$more_transportation_no_of_vehicle=$request->more_transportation_no_of_vehicle;
$more_transportation_root_type=$request->more_transportation_root_type;
$more_transportation_total_availAble_seat=$request->more_transportation_total_availAble_seat;
$more_transportation_price_per_person=$request->more_transportation_price_per_person;

if(isset($more_transportation_vehicle_type))
{
   $arrLength = count($more_transportation_vehicle_type);
      for($i = 0; $i < $arrLength; $i++) {

$transportation_details_more[]=(object)[
    
    'more_transportation_vehicle_type'=>$more_transportation_vehicle_type[$i],
    'more_transportation_no_of_vehicle'=>$more_transportation_no_of_vehicle[$i],
    'more_transportation_root_type'=>$more_transportation_root_type[$i],
    'more_transportation_total_availAble_seat'=>$more_transportation_total_availAble_seat[$i],
     'more_transportation_price_per_person'=>$more_transportation_price_per_person[$i],
    
    ];

}

 $transfer_details_more=json_encode($transportation_details_more);
}
else
{
   $transfer_details_more = ''; 
}
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    // $makkah_hotel_name=$request->makkah_hotel_name;
    // $makkah_location=$request->makkah_location;
    // $makkah_check_in=$request->makkah_check_in;
    // $makkah_check_out=$request->makkah_check_out;
    // $makkah_no_of_nights=$request->makkah_no_of_nights;
    // $makkah_sharing_1=$request->makkah_sharing_1;
    // $makkah_sharing_2=$request->makkah_sharing_2;
    // $makkah_sharing_3=$request->makkah_sharing_3;
    // $makkah_sharing_4=$request->makkah_sharing_4;
    // $makkah_price=$request->makkah_price;
//     $makkah_board_basis=$request->makkah_board_basis;
//     $makkah_room_views=$request->makkah_room_views;

//     if($request->hasFile('makkah_images')){

//       $file = $request->file('makkah_images');
//       $extension = $file->getClientOriginalExtension();
//       $filename = time() . '.' . $extension;
//       $file->move('public/uploads/package_imgs/', $filename);
//       $makkah_images = $filename;
//   }
//   else{
//       $makkah_images = '';
//   }
//     $makkah_images=$makkah_images;

    // $madina_hotel_name=$request->madina_hotel_name;
    // $madina_location=$request->madina_location;
    // $madina_check_in=$request->madina_check_in;
    // $madina_check_out=$request->madina_check_out;
    // $madina_no_of_nights=$request->madina_no_of_nights;
    // $madina_sharing_1=$request->madina_sharing_1;
    // $madina_sharing_2=$request->madina_sharing_2;
    // $madina_sharing_3=$request->madina_sharing_3;
    // $madina_sharing_4=$request->madina_sharing_4;
    // $madina_price=$request->madina_price;
    
//     $madina_board_basis=$request->madina_board_basis;
//     $madina_room_views=$request->madina_room_views;

//     if($request->hasFile('madina_images')){

//       $file = $request->file('madina_images');
//       $extension = $file->getClientOriginalExtension();
//       $filename = time() . '.' . $extension;
//       $file->move('public/uploads/package_imgs/', $filename);
//       $madina_images = $filename;
//   }
//   else{
//       $madina_images = '';
//   }
//     $madina_images=$madina_images;

    // $transfer_pickup_location=$request->transfer_pickup_location;
    // $transfer_drop_location=$request->transfer_drop_location;
    // $transfer_pickup_date_time=$request->transfer_pickup_date_time;
    // $transfer_vehicle=$request->transfer_vehicle;


//     if($request->hasFile('transfer_images')){

//       $file = $request->file('transfer_images');
//       $extension = $file->getClientOriginalExtension();
//       $filename = time() . '.' . $extension;
//       $file->move('public/uploads/package_imgs/', $filename);
//       $transfer_images = $filename;
//   }
//   else{
//       $transfer_images = '';
//   }
//     $transfer_images=$transfer_images;

    $flights_airline=$request->flights_airline;
    $flights_departure_airport=$request->flights_departure_airport;
    $flights_arrival_airport=$request->flights_arrival_airport;
    $flights_departure__return_airport=$request->flights_departure__return_airport;
    $flights_arrival_return_airport=$request->flights_arrival_return_airport;
    $flights_departure__return_date=$request->flights_departure__return_date;
    $flights_arrival_return_date=$request->flights_arrival_return_date;
    $flights_price=$request->flights_price;

    $visa_fee=$request->visa_fee;
    $visit_visa_fee=$request->visit_visa_fee;
    // $details_visa=$request->details_visa;

    $administration_charges=$request->administration_charges;
    
     $quad_grand_total_amount=$request->quad_grand_total_amount;
    $triple_grand_total_amount=$request->triple_grand_total_amount;
    $double_grand_total_amount=$request->double_grand_total_amount;
    
    
    
    
    
    // $administration_charges=$request->administration_charges;
    $customer_id=Session::get('id');
    $token=config('token');
    $curl = curl_init();
 
			$data = array(
        'token'=>$token,
        'customer_id'=>$customer_id,
        'package_name'=>$package_name,
        'check_in'=>$check_in,
        'check_out'=>$check_out,
        'status'=>$status,
        
        'no_of_pax_days'=>$no_of_pax_days,
        'content'=>$content,
        
        'makkah_details'=>$makkah_details,
        'madina_details'=>$madina_details,
        'transfer_details'=>$transfer_details,
        'transfer_details_more'=>$transfer_details_more,
        
        // 'makkah_hotel_name'=>$makkah_hotel_name,
        // 'makkah_location'=>$makkah_location,
        // 'makkah_check_in'=>$makkah_check_in,
        // 'makkah_check_out'=>$makkah_check_out,
        // 'makkah_no_of_nights'=>$makkah_no_of_nights,
        // 'makkah_sharing_1'=>$makkah_sharing_1,
        // 'makkah_sharing_2'=>$makkah_sharing_2,
        // 'makkah_sharing_3'=>$makkah_sharing_3,
        // 'makkah_sharing_4'=>$makkah_sharing_4,
        // 'makkah_price'=>$makkah_price,
        // 'makkah_board_basis'=>$makkah_board_basis,
        // 'makkah_room_views'=>$makkah_room_views,
        // 'makkah_images'=>$makkah_images,

        // 'madina_hotel_name'=>$madina_hotel_name,
        // 'madina_location'=>$madina_location,
        // 'madina_check_in'=>$madina_check_in,
        // 'madina_check_out'=>$madina_check_out,
        // 'madina_no_of_nights'=>$madina_no_of_nights,
        // 'madina_sharing_1'=>$madina_sharing_1,
        // 'madina_sharing_2'=>$madina_sharing_2,
        // 'madina_sharing_3'=>$madina_sharing_3,
        // 'madina_sharing_4'=>$madina_sharing_4,
        // 'madina_price'=>$madina_price,
        // 'madina_board_basis'=>$madina_board_basis,
        // 'madina_room_views'=>$madina_room_views,
        // 'madina_images'=>$madina_images,
        
    //     'transfer_pickup_location'=>$transfer_pickup_location,
    //   'transfer_drop_location'=>$transfer_drop_location,
    //   'transfer_pickup_date_time'=>$transfer_pickup_date_time,
    //   'transfer_vehicle'=>$transfer_vehicle,
    //   'transfer_images'=>$transfer_images,
      

      'flights_airline'=>$flights_airline,
      'flights_departure_airport'=>$flights_departure_airport,
      'flights_arrival_airport'=>$flights_arrival_airport,
      'flights_departure__return_airport'=>$flights_departure__return_airport,
      'flights_arrival_return_airport'=>$flights_arrival_return_airport,
      'flights_departure__return_date'=>$flights_departure__return_date,
      'flights_arrival_return_date'=>$flights_arrival_return_date,
      'flights_price'=>$flights_price,
      'visa_fee'=>$visa_fee,
      'visit_visa_fee'=>$visit_visa_fee,
    //   'details_visa'=>$details_visa,
      'administration_charges'=>$administration_charges,
      'quad_grand_total_amount'=>$quad_grand_total_amount,
    'triple_grand_total_amount'=>$triple_grand_total_amount,
    'double_grand_total_amount'=>$double_grand_total_amount,
      
    );
	

// print_r($data);die();


			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_umrah_packages_api',
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
			$attributes = json_decode($response);
      	return redirect()->back()->with('message','created data');
  }

  public function edit_umrah_packages($id)
  {
    // print_r($data);die();

    $customer_id=Session::get('id');
    $token=config('token');
    $curl = curl_init();
 
			$data = array(
        'token'=>$token,
        'id'=>$id,
      );
      // print_r($data);die();
			curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_umrah_packages_api/{$id}',
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
        $umrah_package = json_decode($response);
        $umrah_package=$umrah_package->umrah_package;
        // print_r($umrah_package);die();
        return view('template/frontend/userdashboard/pages/umrah_packages/edit_umrah_packages',compact('umrah_package'));
  }


  public function submit_edit_umrah_packages(Request $request,$id)
  {
     
    $package_name=$request->package_name;
    $check_in=$request->check_in;
    $check_out=$request->check_out;
    $status=$request->status;

    $makkah_hotel_name=$request->makkah_hotel_name;
    $makkah_location=$request->makkah_location;
    $makkah_check_in=$request->makkah_check_in;
    $makkah_check_out=$request->makkah_check_out;
    $makkah_no_of_nights=$request->makkah_no_of_nights;
    $makkah_sharing_1=$request->makkah_sharing_1;
    $makkah_sharing_2=$request->makkah_sharing_2;
    $makkah_sharing_3=$request->makkah_sharing_3;
    $makkah_sharing_4=$request->makkah_sharing_4;
    $makkah_price=$request->makkah_price;
    $makkah_board_basis=$request->makkah_board_basis;
    $makkah_room_views=$request->makkah_room_views;

    if($request->hasFile('makkah_images')){

      $file = $request->file('makkah_images');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('public/uploads/package_imgs/', $filename);
      $makkah_images = $filename;
  }
  else{
      $makkah_images = '';
  }
    $makkah_images=$makkah_images;

    $madina_hotel_name=$request->madina_hotel_name;
    $madina_location=$request->madina_location;
    $madina_check_in=$request->madina_check_in;
    $madina_check_out=$request->madina_check_out;
    $madina_no_of_nights=$request->madina_no_of_nights;
    $madina_sharing_1=$request->madina_sharing_1;
    $madina_sharing_2=$request->madina_sharing_2;
    $madina_sharing_3=$request->madina_sharing_3;
    $madina_sharing_4=$request->madina_sharing_4;
    $madina_price=$request->madina_price;
    $madina_board_basis=$request->madina_board_basis;
    $madina_room_views=$request->madina_room_views;

    if($request->hasFile('madina_images')){

      $file = $request->file('madina_images');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('public/uploads/package_imgs/', $filename);
      $madina_images = $filename;
  }
  else{
      $madina_images = '';
  }
    $madina_images=$madina_images;

    $transfer_pickup_location=$request->transfer_pickup_location;
    $transfer_drop_location=$request->transfer_drop_location;
    $transfer_pickup_date_time=$request->transfer_pickup_date_time;
    $transfer_vehicle=$request->transfer_vehicle;


    if($request->hasFile('transfer_images')){

      $file = $request->file('transfer_images');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('public/uploads/package_imgs/', $filename);
      $transfer_images = $filename;
  }
  else{
      $transfer_images = '';
  }
    $transfer_images=$transfer_images;

    $flights_airline=$request->flights_airline;
    $flights_departure_airport=$request->flights_departure_airport;
    $flights_arrival_airport=$request->flights_arrival_airport;
    $flights_departure__return_airport=$request->flights_departure__return_airport;
    $flights_arrival_return_airport=$request->flights_arrival_return_airport;
    $flights_departure__return_date=$request->flights_departure__return_date;
    $flights_arrival_return_date=$request->flights_arrival_return_date;
    $flights_price=$request->flights_price;

    $visa_fee=$request->visa_fee;
    $visit_visa_fee=$request->visit_visa_fee;
    $details_visa=$request->details_visa;

    $administration_charges=$request->administration_charges;
    // $administration_charges=$request->administration_charges;
    $customer_id=Session::get('id');
    $token=config('token');
    $curl = curl_init();
 
			$data = array(
        'token'=>$token,
        'id'=>$id,
        'customer_id'=>$customer_id,
        'package_name'=>$package_name,
        'check_in'=>$check_in,
        'check_out'=>$check_out,
        'status'=>$status,
        'makkah_hotel_name'=>$makkah_hotel_name,
        'makkah_location'=>$makkah_location,
        'makkah_check_in'=>$makkah_check_in,
        'makkah_check_out'=>$makkah_check_out,
        'makkah_no_of_nights'=>$makkah_no_of_nights,
        'makkah_sharing_1'=>$makkah_sharing_1,
        'makkah_sharing_2'=>$makkah_sharing_2,
        'makkah_sharing_3'=>$makkah_sharing_3,
        'makkah_sharing_4'=>$makkah_sharing_4,
        'makkah_price'=>$makkah_price,
        'makkah_board_basis'=>$makkah_board_basis,
        'makkah_room_views'=>$makkah_room_views,
        'makkah_images'=>$makkah_images,

        'madina_hotel_name'=>$madina_hotel_name,
        'madina_location'=>$madina_location,
        'madina_check_in'=>$madina_check_in,
        'madina_check_out'=>$madina_check_out,
        'madina_no_of_nights'=>$madina_no_of_nights,
        'madina_sharing_1'=>$madina_sharing_1,
        'madina_sharing_2'=>$madina_sharing_2,
        'madina_sharing_3'=>$madina_sharing_3,
        'madina_sharing_4'=>$madina_sharing_4,
        'madina_price'=>$madina_price,
        'madina_board_basis'=>$madina_board_basis,
        'madina_room_views'=>$madina_room_views,
        'madina_images'=>$madina_images,
        'transfer_pickup_location'=>$transfer_pickup_location,
      'transfer_drop_location'=>$transfer_drop_location,
      'transfer_pickup_date_time'=>$transfer_pickup_date_time,
      'transfer_vehicle'=>$transfer_vehicle,
      'transfer_images'=>$transfer_images,
      

      'flights_airline'=>$flights_airline,
      'flights_departure_airport'=>$flights_departure_airport,
      'flights_arrival_airport'=>$flights_arrival_airport,
      'flights_departure__return_airport'=>$flights_departure__return_airport,
      'flights_arrival_return_airport'=>$flights_arrival_return_airport,
      'flights_departure__return_date'=>$flights_departure__return_date,
      'flights_arrival_return_date'=>$flights_arrival_return_date,
      'flights_price'=>$flights_price,
      'visa_fee'=>$visa_fee,
      'visit_visa_fee'=>$visit_visa_fee,
      'details_visa'=>$details_visa,
      'administration_charges'=>$administration_charges,
      
    );
	

// print_r($data);die();


			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_edit_umrah_packages_api/{$id}',
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
			$umrah_package = json_decode($response);

      // print_r($umrah_package);die();
      	return redirect('super_admin/view_umrah_packages');
  }


  public function enable_umrah_packages($id)
  {
    $token=config('token');
    $curl = curl_init();
 
			$data = array(
        'token'=>$token,
        'id'=>$id
        
    );
	

// print_r($data);die();


			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/enable_umrah_packages_api/{$id}',
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
			$umrah_package = json_decode($response);
      return redirect()->back();

   
  }
  public function disable_umrah_packages($id)
  {
   
    $token=config('token');
    $curl = curl_init();
 
			$data = array(
        'token'=>$token,
        'id'=>$id
        
    );
	

// print_r($data);die();


			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/disable_umrah_package_api/{$id}',
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
			$umrah_package = json_decode($response);
      return redirect()->back();
  }

  public function delete_umrah_packages($id)
  {
    $token=config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'id'=>$id);
    
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_umrah_packages_api/{$id}',
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
			$enable = json_decode($response);
      
      return redirect()->back();
  }


  public function view()
  {

    $customer_id=Session::get('id');
    $token=config('token');
    $curl = curl_init();
 
			$data = array(
        'token'=>$token,
        'customer_id'=>$customer_id
        
    );
	

// print_r($data);die();


			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_umrah_packages_api',
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
			$umrah_package = json_decode($response);
      // print_r($umrah_package);die();
      $umrah_package=$umrah_package->umrah_package;
    return view('template/frontend/userdashboard/pages/umrah_packages/view_umrah_packages',compact('umrah_package'));  
  }


  public function add_categories()
  {

    
    return view('template/frontend/userdashboard/pages/umrah_packages/categories/add_categories');  
  }

    public function submit_categories(Request $request)
    {
        $token=config('token');
        if($request->hasFile('image')){
    
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/uploads/package_imgs/', $filename);
            $image = $filename;
        }
        else{
            $image = '';
        }
    
        $slug=$request->slug;
        $title=$request->title;
        $description=$request->description;
        $placement=$request->placement;
        $customer_id=Session::get('id');
    
		$curl = curl_init();

		$data = array('token'=>$token,'title'=>$title,'customer_id'=>$customer_id,'slug'=>$slug,'description'=>$description,'image'=>$image,'placement'=>$placement);
        // dd($data);
		curl_setopt_array($curl, array(
    		CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_categories',
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
		$response_data = json_decode($response);
	    return redirect()->back()->with('message','Categories Created Successful!');
    }

  public function edit_categories($id)
  {
    $token=config('token');
   
			$curl = curl_init();
 
			$data = array('token'=>$token,'id'=>$id);
	// print_r($data);die();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_categories_api/{$id}',
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
			$response_data = json_decode($response);
      $categories=$response_data->categories;
	// print_r($categories);die();

    
    return view('template/frontend/userdashboard/pages/umrah_packages/categories/edit_categories',compact('categories'));  
  }
  public function submit_edit_categories(Request $request,$id)
  {
    $token=config('token');
    if($request->hasFile('image')){

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $image = $filename;
    }
    else{
        $image = '';
    }
    
    $slug=$request->slug;
    $title=$request->title;
    $description=$request->description;
    
    
    
    
			$curl = curl_init();
 
			$data = array('token'=>$token,'title'=>$title,'id'=>$id,'slug'=>$slug,'description'=>$description,'image'=>$image);
	// print_r($data);die();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_edit_categories_api/{$id}',
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
     //echo $response;
			curl_close($curl);
			$response_data = json_decode($response);
      
      return redirect('super_admin/view_categories');
    
  }
  public function delete_categories(Request $request,$id)
  {
    $token=config('token');
   
			$curl = curl_init();
 
			$data = array(
        'token'=>$token,
       
        'id'=>$id
      );
	// print_r($data);die();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_categories_api/{$id}',
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
     //echo $response;
			curl_close($curl);
			$response_data = json_decode($response);
      
      return redirect('super_admin/view_categories');
    
    
  }

  public function view_categories(Request $request)
  {

    $token=config('token');
   
    $customer_id=Session::get('id');

    // print_r($customer_id);die();
			$curl = curl_init();
 
			$data = array('token'=>$token,'customer_id'=>$customer_id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_categories',
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
			$categories = json_decode($response);
      $categories=$categories->categories;
	// print_r($categories->categories);die();
	
    return view('template/frontend/userdashboard/pages/umrah_packages/categories/view_categories',compact('categories'));  
  }
  
    public function get_facliites_activities(Request $request)
  {
   $token=config('token');
    $title=$request->title;
    $customer_id=Session::get('id');
			$curl = curl_init();
 
			$data = array('token'=>$token,'customer_id'=>$customer_id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_attributes_activites',
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
	       // echo $response;
	        
			curl_close($curl);
			$attributes = json_decode($response);
      	// print_r($attributes);die();
      $attributes=$attributes->facilities;
      
       foreach($attributes as $att_res){
          ?>
          <option value="<?php echo $att_res->id ?>"><?php echo $att_res->title ?></option>
          <?php
      }
  
  
  }
  

    
  public function add_attributes(Request $request)
  {


    return view('template/frontend/userdashboard/pages/umrah_packages/attributes/add_attributes');  
  }
  
    public function submit_attributes(Request $request)
    {
        $token=config('token');
        $title=$request->title;
        $customer_id=Session::get('id');
		$curl = curl_init();
		$data = array('token'=>$token,'title'=>$title,'customer_id'=>$customer_id);
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_attributes',
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
		$response_data = json_decode($response);
        return redirect()->back()->with('message','Attributes Created Successful!');
    }

  public function edit_attributes($id)
  {
    $token=config('token');
   
			$curl = curl_init();
 
			$data = array('token'=>$token,'id'=>$id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_attributes_api/{$id}',
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
			$attributes = json_decode($response);
      // print_r($attributes);die();
	$attributes=$attributes->attributes;
	

    
    return view('template/frontend/userdashboard/pages/umrah_packages/attributes/edit_attributes',compact('attributes'));  
  }
  public function submit_edit_attributes(Request $request,$id)
  {
    $token=config('token');
    $title=$request->title;
    $curl = curl_init();

    $data = array('token'=>$token,'id'=>$id,'title'=>$title);
// print_r($data);die();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_edit_attributes/{$id}',
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
    $attributes = json_decode($response);
    // print_r($attributes);die();
    return redirect()->back();
    
    
  }
  public function delete_attributes($id)
  {
    $token=config('token');
  
    $curl = curl_init();

    $data = array('token'=>$token,'id'=>$id);
// print_r($data);die();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_attributes_api/{$id}',
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
    $attributes = json_decode($response);
    // print_r($attributes);die();
    return redirect()->back();
    
    
  }
  public function view_attributes(Request $request)
  {

    $token=config('token');
    $title=$request->title;
    $customer_id=Session::get('id');
			$curl = curl_init();
 
			$data = array('token'=>$token,'customer_id'=>$customer_id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_attributes',
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
			$attributes = json_decode($response);
      	// print_r($attributes);die();
      $attributes=$attributes->attributes;


    return view('template/frontend/userdashboard/pages/umrah_packages/attributes/view_attributes',compact('attributes'));  
  }

  public function create_excursion(Request $request)
  {
$token=config('token');
    $title=$request->title;
    $customer_id=Session::get('id');
			$curl = curl_init();
 
			$data = array('token'=>$token,'customer_id'=>$customer_id);
	       // dd($data);
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/create_tour',
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
	        echo $response;die();
			curl_close($curl);
			$data = json_decode($response);
    //   	print_r($data);die();
     
   		$customer=$data->customer;
			$attributes=$data->attributes;
			$categories=$data->categories;
			$all_countries=$data->all_countries;
			$all_countries_currency=$data->all_countries_currency;
			$bir_airports=$data->bir_airports;
			$bir_airports1=$data->bir_airports;
      	print_r($bir_airports);die();
     
   
    return view('template/frontend/userdashboard/pages/tour/add_tour',compact('customer','attributes','categories','all_countries','all_countries_currency','bir_airports','bir_airports1'));  
  }

    public function create_excursion1(Request $request)
    {
        $token=config('token');
        $title=$request->title;
        $customer_id=Session::get('id');
    	$curl = curl_init();
    
    	$data = array('token'=>$token,'customer_id'=>$customer_id);
    // 	dd($data);
        curl_setopt_array($curl, array(
        	CURLOPT_URL => 'https://admin.synchronousdigital.com/api/create_tour',
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
         
       	$customer=$data->customer;
    	$attributes=$data->attributes;
    	$categories=$data->categories;
    	$all_countries=$data->all_countries;
    	$all_countries_currency=$data->all_countries_currency;
    	$payment_gateways=$data->payment_gateways;
    	$payment_modes=$data->payment_modes;
    	
    	$transfer_Vehicle = $data->transfer_Vehicle;
    	// print_r($transfer_Vehicle);die();
    	
        return view('template/frontend/userdashboard/pages/tour/add_tour1',compact('transfer_Vehicle','customer','attributes','categories','all_countries','all_countries_currency','payment_gateways','payment_modes'));
    }
    
    public function save_Package(Request $request){
        $token=config('token');
        $customer_id=Session::get('id');
        
        $title=$request->title;
        $no_of_pax_days=$request->no_of_pax_days;
        $starts_rating=$request->starts_rating;
        $currency_symbol=$request->currency_symbol;
        $content=$request->content;
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $time_duration=$request->time_duration;
        $tour_categories=$request->categories;
        $tour_feature=$request->tour_feature;
        $defalut_state=$request->defalut_state;
        $tour_publish=$request->tour_publish;
        $tour_author=$request->tour_author;
        $external_packages=$request->external_packages;
        $external_packages=json_encode($external_packages);
        // dd($request->external_packages);
        if($request->hasFile('tour_featured_image')){
            $file = $request->file('tour_featured_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . 'fea.' . $extension;
            $file->move('public/uploads/package_imgs/', $filename);
            $tour_featured_image = $filename;
        }
        else{
            $tour_featured_image = '';
        }
        $tour_featured_image=$tour_featured_image;
        if($request->hasFile('tour_banner_image')){
            $file = $request->file('tour_banner_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . 'banner.' . $extension;
            $file->move('public/uploads/package_imgs/', $filename);
            $tour_banner_image = $filename;
        }
        else{
            $tour_banner_image = '';
        }
        $tour_banner_image=$tour_banner_image;

        $gallery_images=array();
        if($files=$request->file('gallery_images')){
            foreach($files as $file){
                $name=$file->getClientOriginalName();
                $file->move('public/uploads/package_imgs/',$name);
                $gallery_images[]=$name;
            }
        }
        if(isset($gallery_images))
        {
            $gallery_images=json_encode($gallery_images);  
        }
        else
        {
            $gallery_images ='';  
        }

        $curl = curl_init();
		$data = array('token'=>$token,
            'customer_id'=>$customer_id,
            'title'=>$title,
            'no_of_pax_days'=>$no_of_pax_days,
            'starts_rating'=>$starts_rating,
            'currency_symbol'=>$currency_symbol,
            'content'=>$content,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'time_duration'=>$time_duration,
            'tour_categories'=>$tour_categories,
            'tour_feature'=>$tour_feature,
            'defalut_state'=>$defalut_state,
            'tour_featured_image'=>$tour_featured_image,
            'tour_banner_image'=>$tour_banner_image,
            'tour_publish'=>$tour_publish,
            'tour_author'=>$tour_author,
            'external_packages'=>$external_packages,
            'gallery_images'=>$gallery_images,
        );
	   // print_r($data);die();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/save_Package',
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
		$data = json_decode($response);
		$id = $data->id;
		curl_close($curl);  
  	    return response()->json(['message'=>'OK','id'=>$id ?? '']);
    }
    
    public function save_Accomodation(Request $request,$id){
        
        $token=config('token');
        $customer_id=Session::get('id');
        $id = $request->id;
        
        // Accomodation_Details
        $hotel_city_name=$request->hotel_city_name;
        $acc_hotel_name=$request->acc_hotel_name;
        $acc_check_in=$request->acc_check_in;
        $acc_check_out=$request->acc_check_out;
        $acc_no_of_nights=$request->acc_no_of_nights;
        $acc_type=$request->acc_type;
        $acc_qty=$request->acc_qty;
        $acc_pax=$request->acc_pax;
        $acc_price=$request->acc_price;
        $acc_total_amount=$request->acc_total_amount;
        
        $accomodation_image=array();
        if($files=$request->file('accomodation_image')){
            foreach($files as $file){
                $name=$file->getClientOriginalName();
                $file->move('public/uploads/package_imgs/',$name);
                $accomodation_image[]=$name;
            }
        }
        $accomodation_image=$accomodation_image;
        // print_r($acc_hotel_name);die();
        if(isset($acc_hotel_name))
        {
            $arrLength = count($acc_hotel_name);
            // print_r($arrLength);die();
            for($i = 0; $i < $arrLength; $i++) {
                
                $accomodation_details[]=(object)[
                    'hotel_city_name'=>$hotel_city_name[$i] ?? '',
                    'acc_hotel_name'=>$acc_hotel_name[$i] ?? '',
                    'acc_check_in'=>$acc_check_in[$i] ?? '',
                    'acc_check_out'=>$acc_check_out[$i] ?? '',
                    'acc_no_of_nights'=>$acc_no_of_nights[$i] ?? '',
                    'acc_type'=>$acc_type[$i] ?? '',
                    'acc_qty'=>$acc_qty[$i] ?? '',
                    'acc_pax'=>$acc_pax[$i] ?? '',
                    'acc_price'=>$acc_price[$i] ?? '',
                    'acc_total_amount'=>$acc_total_amount[$i] ?? '',
                    'accomodation_image'=>$accomodation_image[$i] ?? '',
                ]; 
            }
            $accomodation_details=json_encode($accomodation_details);
        }
        else
        {
           $accomodation_details = ''; 
        }
        
        // More Acoomodation Details
        $more_hotel_city=$request->more_hotel_city;
        $more_acc_type=$request->more_acc_type;
        $more_acc_qty=$request->more_acc_qty;
        $more_acc_pax=$request->more_acc_pax;
        $more_acc_price=$request->more_acc_price;
        $more_acc_total_amount=$request->more_acc_total_amount;

        if(isset($more_acc_type))
        {
            $arrLength = count($more_acc_type);
            for($i = 0; $i < $arrLength; $i++) {
                $more_accomodation_details[]=(object)[
                    'more_hotel_city'=>$more_hotel_city[$i],
                    'more_acc_type'=>$more_acc_type[$i],
                    'more_acc_qty'=>$more_acc_qty[$i],
                    'more_acc_pax'=>$more_acc_pax[$i],
                    'more_acc_price'=>$more_acc_price[$i],
                    'more_acc_total_amount'=>$more_acc_total_amount[$i],
                ];
            }
        
        $more_accomodation_details=json_encode($more_accomodation_details);
        }
        else
        {
           $more_accomodation_details = ''; 
        }
        
        // End
        
        $curl = curl_init();
		$data = array(
		    'id'                        => $id,
		    'token'                     => $token,
            'customer_id'               => $customer_id,
            'accomodation_details'      => $accomodation_details,
            'more_accomodation_details' => $more_accomodation_details,
        );
        
	    print_r($data);die();
	    
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/save_Accomodation/{id}',
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
		echo $response;die();
		curl_close($curl);  
  	    return response()->json(['message'=>'OK']);
    }

    public function submit_tour(Request $request){
        $token=config('token');
        $customer_id=Session::get('id');
        
        $checkout_message       =   $request->checkout_message;
        $cancellation_policy    =   $request->cancellation_policy;
        $title=$request->title;
        $no_of_pax_days=$request->no_of_pax_days;
        // $external_packages=$request->external_packages;
        // $external_packages=json_encode($external_packages);
        $content=$request->content;
        $tour_categories=$request->categories;
        $tour_attributes=$request->tour_attributes;
        $tour_attributes=json_encode($tour_attributes);
        $destination_country=$request->destination_country;
        $destination_city=$request->destination_city;
        $tour_location=$request->tour_location_city;
        $destination_details=[
        'destination_country'=>$destination_country,
        'destination_city'=>$destination_city,
        ];
        
        $destination_details=json_encode($destination_details);
        
        
        
        
        
        $more_destination_country=$request->more_destination_country;
        
        $more_destination_city=$request->more_destination_city;
        
        
        
        $destination_details_more=[
        'more_destination_country'=>$more_destination_country,
        'more_destination_city'=>$more_destination_city,
        ];
        
        $destination_details_more=json_encode($destination_details_more);
        
        
        
        
        $hotel_name_markup=$request->hotel_name_markup;
        $room_type=$request->room_type;
        $without_markup_price=$request->without_markup_price;
        $markup_type=$request->markup_type;
        
        $markup=$request->markup;
        $markup_price=$request->markup_price;
 
 
 
 
  $gallery_images=array();
    if($files=$request->file('gallery_images')){
        foreach($files as $file){
            $name=$file->getClientOriginalName();
            $file->move('public/uploads/package_imgs/',$name);
            $gallery_images[]=$name;
        }
    }
    if(isset($gallery_images))
    {
      $gallery_images=json_encode($gallery_images);  
    }
    else
    {
      $gallery_images ='';  
    }
    
    
 
//  print_r();die();
 
 

 if(isset($hotel_name_markup))
{
   $arrLength = count($hotel_name_markup);
  
      for($i = 0; $i < $arrLength; $i++) {

$markup_details[]=(object)[
    
    'hotel_name_markup'=>$hotel_name_markup[$i],
    'room_type'=>$room_type[$i] ?? '',
    'without_markup_price'=>$without_markup_price[$i],
    'markup_type'=>$markup_type[$i],
    'markup'=>$markup[$i],
    'markup_price'=>$markup_price[$i],
    ];

}

 $markup_details=json_encode($markup_details);
 
}
else
{
   $markup_details = '';
   
}
  
  
  
 $more_hotel_name_markup=$request->more_hotel_name_markup;
$more_room_type=$request->more_room_type;
$more_without_markup_price=$request->more_without_markup_price;
$more_markup_type=$request->more_markup_type;

$more_markup=$request->more_markup;
$more_markup_price=$request->more_markup_price;

if(isset($more_hotel_name_markup))
{
   $arrLength = count($more_hotel_name_markup);
      for($i = 0; $i < $arrLength; $i++) {

$more_markup_details[]=(object)[
    
    'more_hotel_name_markup'=>$more_hotel_name_markup[$i],
    'more_room_type'=>$more_room_type[$i],
    'more_without_markup_price'=>$more_without_markup_price[$i],
    'more_markup_type'=>$more_markup_type[$i],
    'more_markup'=>$more_markup[$i],
    'more_markup_price'=>$more_markup_price[$i],
    ];

}

 $more_markup_details=json_encode($more_markup_details);
}
else
{
   $more_markup_details = ''; 
}



// $departure_airport_code=$request->departure_airport_code;
// $departure_flight_number=$request->departure_flight_number;
// $departure_date=$request->departure_date;
// $departure_time=$request->departure_time;

// $arrival_airport_code=$request->arrival_airport_code;
// $arrival_flight_number=$request->arrival_flight_number;
// $arrival_date=$request->arrival_date;
// $arrival_time=$request->arrival_time;

// $flight_type=$request->flight_type;
// $flights_per_person_price=$request->flights_per_person_price;
// $connected_flights_duration_details=$request->connected_flights_duration_details;
// $terms_and_conditions=$request->terms_and_conditions;

// if($request->hasFile('flights_image')){

//         $file = $request->file('flights_image');
//         $extension = $file->getClientOriginalExtension();
//         $filename = time() . '.' . $extension;
//         $file->move('public/uploads/package_imgs/', $filename);
//         $tour_flights_image = $filename;
//     }
// else{
//         $tour_flights_image = '';
//     }

// $flights_image=$tour_flights_image;

// $flights_details=[
//   'departure_airport_code'=>$departure_airport_code,
// 'departure_flight_number'=>$departure_flight_number,
// 'departure_date'=>$departure_date,
// 'departure_time'=>$departure_time,

// 'arrival_airport_code'=>$arrival_airport_code,
// 'arrival_flight_number'=>$arrival_flight_number,
// 'arrival_date'=>$arrival_date,
// 'arrival_time'=>$arrival_time,

// 'flight_type'=>$flight_type,
// 'flights_per_person_price'=>$flights_per_person_price,
// 'connected_flights_duration_details'=>$connected_flights_duration_details,
// 'terms_and_conditions'=>$terms_and_conditions,
// 'flights_image'=>$flights_image,
//     ];

// $flights_details=json_encode($flights_details);

// $more_flights_departure=$request->more_flights_departure;
// $more_flights_arrival=$request->more_flights_arrival;
// $more_flights_airline=$request->more_flights_airline;
// $more_flights_per_person_price=$request->more_flights_per_person_price;

// if(isset($more_flights_departure))
// {
//   $arrLength = count($more_flights_departure);
//       for($i = 0; $i < $arrLength; $i++) {

// $more_flights_details[]=(object)[
    
//     'more_flights_departure'=>$more_flights_departure[$i],
//     'more_flights_arrival'=>$more_flights_arrival[$i],
//     'more_flights_airline'=>$more_flights_airline[$i],
//     'more_flights_per_person_price'=>$more_flights_per_person_price[$i],
//     ];

// }

//  $more_flights_details=json_encode($more_flights_details);
// }
// else
// {
//   $more_flights_details = ''; 
// }

            // New Fields
            $departure_airport_code=$request->departure_airport_code;
            $arrival_airport_code=$request->arrival_airport_code;
            $other_Airline_Name2=$request->other_Airline_Name2;
            $departure_Flight_Type=$request->departure_Flight_Type;
            $departure_flight_number=$request->departure_flight_number;
            $departure_time=$request->departure_time;
            $arrival_time=$request->arrival_time;
            $total_Time=$request->total_Time;
            $flight_type=$request->flight_type;
            $flights_per_person_price=$request->flights_per_person_price;
            $connected_flights_duration_details=$request->connected_flights_duration_details;
            $terms_and_conditions=$request->terms_and_conditions;
            // Return Details
            $return_departure_airport_code=$request->return_departure_airport_code;
            $return_arrival_airport_code=$request->return_arrival_airport_code;
            $return_other_Airline_Name2=$request->return_other_Airline_Name2;
            $return_departure_Flight_Type=$request->return_departure_Flight_Type;
            $return_departure_flight_number=$request->return_departure_flight_number;
            $return_departure_time=$request->return_departure_time;
            $return_arrival_time=$request->return_arrival_time;
            $return_total_Time=$request->return_total_Time;
            
            if($request->hasFile('flights_image')){
                $file = $request->file('flights_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . 'fl.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_flights_image = $filename;
                // echo "The file name is $filename";
            }
            else{
                $tour_flights_image = '';
            }
            
            
            $flights_image=$tour_flights_image;
            $flights_details=[
                'departure_airport_code'=>$departure_airport_code,
                'arrival_airport_code'=>$arrival_airport_code,
                'other_Airline_Name2'=>$other_Airline_Name2,
                'departure_Flight_Type'=>$departure_Flight_Type,
                'departure_flight_number'=>$departure_flight_number,
                'departure_time'=>$departure_time,
                'arrival_time'=>$arrival_time,
                'total_Time'=>$total_Time,
                // 'departure_date'=>$departure_date,
                // 'arrival_flight_number'=>$arrival_flight_number,
                // 'arrival_date'=>$arrival_date,
                'flight_type'=>$flight_type,
                'flights_per_person_price'=>$flights_per_person_price,
                'connected_flights_duration_details'=>$connected_flights_duration_details,
                'terms_and_conditions'=>$terms_and_conditions,
                'flights_image'=>$tour_flights_image,
                // return_details
                'return_departure_airport_code'=>$return_departure_airport_code,
                'return_arrival_airport_code'=>$return_arrival_airport_code,
                'return_other_Airline_Name2'=>$return_other_Airline_Name2,
                'return_departure_Flight_Type'=>$return_departure_Flight_Type,
                'return_departure_flight_number'=>$return_departure_flight_number,
                'return_departure_time'=>$return_departure_time,
                'return_arrival_time'=>$return_arrival_time,
                'return_total_Time'=>$return_total_Time,
            ];
            $flights_details=json_encode($flights_details);
            // More Flight Data
            $more_departure_airport_code=$request->more_departure_airport_code;
            $more_arrival_airport_code=$request->more_arrival_airport_code;
            $more_other_Airline_Name2=$request->more_other_Airline_Name2;
            $more_departure_Flight_Type=$request->more_departure_Flight_Type;
            $more_departure_flight_number=$request->more_departure_flight_number;
            $more_departure_time=$request->more_departure_time;
            $more_arrival_time=$request->more_arrival_time;
            $more_total_Time=$request->more_total_Time;
            // Return Details
            $return_more_departure_airport_code=$request->return_more_departure_airport_code;
            $return_more_arrival_airport_code=$request->return_more_arrival_airport_code;
            $return_more_other_Airline_Name2=$request->return_more_other_Airline_Name2;
            $return_more_departure_Flight_Type=$request->return_more_departure_Flight_Type;
            $return_more_departure_flight_number=$request->return_more_departure_flight_number;
            $return_more_departure_time=$request->return_more_departure_time;
            $return_more_arrival_time=$request->return_more_arrival_time;
            $return_more_total_Time=$request->return_more_total_Time;
            
            if(isset($more_departure_airport_code))
            {
                $arrLength = count($more_departure_airport_code);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_flights_details[]=(object)[
                        'more_departure_airport_code'=>$more_departure_airport_code[$i],
                        'more_arrival_airport_code'=>$more_arrival_airport_code[$i],
                        'more_other_Airline_Name2'=>$more_other_Airline_Name2[$i],
                        'more_departure_Flight_Type'=>$more_departure_Flight_Type[$i],
                        'more_departure_flight_number'=>$more_departure_flight_number[$i],
                        'more_departure_time'=>$more_departure_time[$i],
                        'more_arrival_time'=>$more_arrival_time[$i],
                        'more_total_Time'=>$more_total_Time[$i],
                        // Return Details
                        'return_more_departure_airport_code'=>$return_more_departure_airport_code[$i],
                        'return_more_arrival_airport_code'=>$return_more_arrival_airport_code[$i],
                        'return_more_other_Airline_Name2'=>$return_more_other_Airline_Name2[$i],
                        'return_more_departure_Flight_Type'=>$return_more_departure_Flight_Type[$i],
                        'return_more_departure_flight_number'=>$return_more_departure_flight_number[$i],
                        'return_more_departure_time'=>$return_more_departure_time[$i],
                        'return_more_arrival_time'=>$return_more_arrival_time[$i],
                        'return_more_total_Time'=>$return_more_total_Time[$i],
                    ];
                }
                $more_flights_details=json_encode($more_flights_details);
            }
            else
            {
               $more_flights_details = ''; 
            }
            // End New Field
            


        // Accomodation_Details
        $hotel_city_name=$request->hotel_city_name;
        $acc_hotel_name=$request->acc_hotel_name;
        $acc_check_in=$request->acc_check_in;
        $acc_check_out=$request->acc_check_out;
        $acc_type=$request->acc_type;
        $acc_qty=$request->acc_qty;
        $acc_pax=$request->acc_pax;
        $acc_price=$request->acc_price;
        $acc_currency=$request->acc_currency;
        $acc_total_amount=$request->acc_total_amount;
        $acc_no_of_nightst=$request->acc_no_of_nightst;
        $hotel_whats_included=$request->hotel_whats_included;
        $hotel_whats_excluded=$request->hotel_whats_excluded;

        $accomodation_image=array();
        if($files=$request->file('accomodation_image')){
            foreach($files as $file){
                $name=$file->getClientOriginalName();
                $file->move('public/uploads/package_imgs/',$name);
                $accomodation_image[]=$name;
            }
        }
        $accomodation_image=$accomodation_image;
        // print_r($acc_hotel_name);die();
        if(isset($acc_hotel_name))
        {
            $arrLength = count($acc_hotel_name);
            // print_r($arrLength);die();
            for($i = 0; $i < $arrLength; $i++) {
                
                $accomodation_details[]=(object)[
                    'hotel_city_name'=>$hotel_city_name[$i] ?? '',
                    'acc_hotel_name'=>$acc_hotel_name[$i] ?? '',
                    'acc_check_in'=>$acc_check_in[$i] ?? '',
                    'acc_check_out'=>$acc_check_out[$i] ?? '',
                    'acc_type'=>$acc_type[$i] ?? '',
                    'acc_qty'=>$acc_qty[$i] ?? '',
                    'acc_pax'=>$acc_pax[$i] ?? '',
                    'acc_price'=>$acc_price[$i] ?? '',
                    'acc_currency'=>$acc_currency[$i] ?? '',
                    'acc_total_amount'=>$acc_total_amount[$i] ?? '',
                    'acc_no_of_nightst'=>$acc_no_of_nightst[$i] ?? '',
                    'hotel_whats_included'=>$hotel_whats_included[$i] ?? '',
                    'hotel_whats_excluded'=>$hotel_whats_excluded[$i] ?? '',
                    'accomodation_image'=>$accomodation_image[$i] ?? '',
                ]; 
            }
            $accomodation_details=json_encode($accomodation_details);
        }
        else
        {
           $accomodation_details = ''; 
        }
        
        // More Acoomodation Details
        $more_hotel_city=$request->more_hotel_city;
        $more_acc_type=$request->more_acc_type;
        $more_acc_qty=$request->more_acc_qty;
        $more_acc_pax=$request->more_acc_pax;
        $more_acc_price=$request->more_acc_price;
        $more_acc_total_amount=$request->more_acc_total_amount;

        if(isset($more_acc_type))
        {
            $arrLength = count($more_acc_type);
            for($i = 0; $i < $arrLength; $i++) {
                $more_accomodation_details[]=(object)[
                    'more_hotel_city'=>$more_hotel_city[$i],
                    'more_acc_type'=>$more_acc_type[$i],
                    'more_acc_qty'=>$more_acc_qty[$i],
                    'more_acc_pax'=>$more_acc_pax[$i],
                    'more_acc_price'=>$more_acc_price[$i],
                    'more_acc_total_amount'=>$more_acc_total_amount[$i],
                ];
            }
        
        $more_accomodation_details=json_encode($more_accomodation_details);
        }
        else
        {
           $more_accomodation_details = ''; 
        }
        
        // End

    // Transportation Details
    $transportation_pick_up_location=$request->transportation_pick_up_location;
    $transportation_drop_off_location=$request->transportation_drop_off_location;
    $transportation_pick_up_date=$request->transportation_pick_up_date;
    $transportation_drop_of_date=$request->transportation_drop_of_date;
    $transportation_total_Time=$request->transportation_total_Time;
    $transportation_trip_type=$request->transportation_trip_type;
    $transportation_vehicle_type=$request->transportation_vehicle_type;
    $transportation_no_of_vehicle=$request->transportation_no_of_vehicle;
    $transportation_price_per_vehicle=$request->transportation_price_per_vehicle;
    $transportation_vehicle_total_price=$request->transportation_vehicle_total_price;
    $transportation_price_per_person=$request->transportation_price_per_person;
    // Return Transportation Details
    $return_transportation_pick_up_location=$request->return_transportation_pick_up_location;
    $return_transportation_drop_off_location=$request->return_transportation_drop_off_location;
    $return_transportation_pick_up_date=$request->return_transportation_pick_up_date;
    $return_transportation_drop_of_date=$request->return_transportation_drop_of_date;
    $return_transportation_total_Time=$request->return_transportation_total_Time;
    $return_transportation_vehicle_type=$request->return_transportation_vehicle_type;
    $return_transportation_no_of_vehicle=$request->return_transportation_no_of_vehicle;
    $return_transportation_price_per_vehicle=$request->return_transportation_price_per_vehicle;
    $return_transportation_vehicle_total_price=$request->return_transportation_vehicle_total_price;
    $return_transportation_price_per_person=$request->return_transportation_price_per_person;
    

    if($request->hasFile('transportation_image')){

        $file = $request->file('transportation_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . 'trans.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $transportation_image = $filename;
    }
    else{
        $transportation_image = '';
    }

    $transportation_details=[
        'transportation_pick_up_location'=>$transportation_pick_up_location,
        'transportation_drop_off_location'=>$transportation_drop_off_location,
        'transportation_pick_up_date'=>$transportation_pick_up_date,
        'transportation_drop_of_date'=>$transportation_drop_of_date,
        'transportation_total_Time'=>$transportation_total_Time,
        'transportation_trip_type'=>$transportation_trip_type,
        'transportation_vehicle_type'=>$transportation_vehicle_type,
        'transportation_no_of_vehicle'=>$transportation_no_of_vehicle,
        'transportation_price_per_vehicle'=>$transportation_price_per_vehicle,
        'transportation_vehicle_total_price'=>$transportation_vehicle_total_price,
        'transportation_price_per_person'=>$transportation_price_per_person,
        // Transportatioin Return Details
        'return_transportation_pick_up_location'=>$return_transportation_pick_up_location,
        'return_transportation_drop_off_location'=>$return_transportation_drop_off_location,
        'return_transportation_drop_of_date'=>$return_transportation_drop_of_date,
        'return_transportation_pick_up_date'=>$return_transportation_pick_up_date,
        'return_transportation_total_Time'=>$return_transportation_total_Time,
        'return_transportation_vehicle_type'=>$return_transportation_vehicle_type,
        'return_transportation_no_of_vehicle'=>$return_transportation_no_of_vehicle,
        'return_transportation_price_per_vehicle'=>$return_transportation_price_per_vehicle,
        'return_transportation_vehicle_total_price'=>$return_transportation_vehicle_total_price,
        'return_transportation_price_per_person'=>$return_transportation_price_per_person,
        
        'transportation_image'=>$transportation_image,
    ];
    
    $transportation_details=json_encode($transportation_details);
    $more_transportation_pick_up_location=$request->more_transportation_pick_up_location;
    $more_transportation_drop_off_location=$request->more_transportation_drop_off_location;
    $more_transportation_pick_up_date=$request->more_transportation_pick_up_date;


    if(isset($more_transportation_pick_up_location))
        {
            $arrLength = count($more_transportation_pick_up_location);
            for($i = 0; $i < $arrLength; $i++) {
                $transportation_details_more[]=(object)[
                    'more_transportation_pick_up_location'=>$more_transportation_pick_up_location[$i],
                    'more_transportation_drop_off_location'=>$more_transportation_drop_off_location[$i],
                    'more_transportation_pick_up_date'=>$more_transportation_pick_up_date[$i],
                ];
            }
            $transportation_details_more=json_encode($transportation_details_more);
        }
    else
    {
       $transportation_details_more = ''; 
    }
    // End Transportation Details



    $start_date=$request->start_date;
    $end_date=$request->end_date;
    $time_duration=$request->time_duration;
    // $tour_min_people=$request->tour_min_people;
    // $tour_max_people=$request->tour_max_people;
    // $tour_location=$request->tour_location;
    $whats_included=$request->whats_included;
     $whats_excluded=$request->whats_excluded;
    // $tour_pricing=$request->tour_pricing;
    // $tour_sale_price=$request->tour_sale_price;
    // $tour_publish=$request->tour_publish;
    $tour_author=$request->tour_author;
    $tour_feature=$request->tour_feature;
    $defalut_state=$request->defalut_state;

    
    // $tour_featured_image=$request->tour_featured_image;
    // $tour_banner_image=$request->tour_banner_image;

if($request->hasFile('tour_featured_image')){

        $file = $request->file('tour_featured_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . 'fea.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_featured_image = $filename;
    }
    else{
        $tour_featured_image = '';
    }


      $tour_featured_image=$tour_featured_image;
      if($request->hasFile('tour_banner_image')){

        $file = $request->file('tour_banner_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . 'banner.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_banner_image = $filename;
        // echo "Banner image is add now $tour_banner_image ";
    }
    else{
        // echo "bannger imag is not add ";
        $tour_banner_image = '';
    }
      $tour_banner_image=$tour_banner_image;



    $Itinerary_title=$request->Itinerary_title;
      $Itinerary_city=$request->Itinerary_city;
      $Itinerary_content=$request->Itinerary_content;

      if($request->hasFile('Itinerary_image')){

        $file = $request->file('Itinerary_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . 'iter.' . $extension;
       $file->move('public/uploads/package_imgs/', $filename);
       
        $Itinerary_image = $filename;
    }
    else{
        $Itinerary_image = '';
    }
      $Itinerary_image=$Itinerary_image;
      $Itinerary_details=array([
                        'Itinerary_title'=>$Itinerary_title,
                          'Itinerary_city'=>$Itinerary_city,
                          'Itinerary_content'=>$Itinerary_content,
                          'Itinerary_image'=>$Itinerary_image,

                          ]);

      $Itinerary_details=json_encode($Itinerary_details);



      $more_Itinerary_title=$request->more_Itinerary_title;
      $more_Itinerary_city=$request->more_Itinerary_city;
      $more_Itinerary_content=$request->more_Itinerary_content;
     
      $more_Itinerary_image=array();
    if($files=$request->file('more_Itinerary_image')){
        foreach($files as $file){
            $name=$file->getClientOriginalName();
            $file->move('public/uploads/package_imgs/',$name);
            $more_Itinerary_image[]=$name;
        }
    }

      $more_Itinerary_image=$more_Itinerary_image;
      
      if(isset($more_Itinerary_title))
      {
          
          $arrLength = count($more_Itinerary_title);
      for($i = 0; $i < $arrLength; $i++) {

$more_itinery_details[]=(object)[
    
    'more_Itinerary_title'=>$more_Itinerary_title[$i],
    'more_Itinerary_city'=>$more_Itinerary_city[$i],
    'more_Itinerary_content'=>$more_Itinerary_content[$i],
    'more_Itinerary_image'=>$more_Itinerary_image[$i] ?? '',
    ];

}
// print_r($more_itinery); 
     

 
      $tour_itinerary_details_1=json_encode($more_itinery_details);
    //      print_r($tour_itinerary_details_1);
    //   die();

// print_r(json_decode($tour_itinerary_details_1));die();
          
      }
      else
{
   $tour_itinerary_details_1= ''; 
}
      
      

      $extra_price_name=$request->extra_price_name;
      $extra_price_price=$request->extra_price_price;
      $extra_price_type=$request->extra_price_type;
      $extra_price_person=$request->extra_price_person;
      $tour_extra_price=array([
        'extra_price_name'=>$extra_price_name,
          'extra_price_price'=>$extra_price_price,
          'extra_price_type'=>$extra_price_type,
          'extra_price_person'=>$extra_price_person,

          ]);

$tour_extra_price=json_encode($tour_extra_price);



// $tour->tour_extra_price=$tour_extra_price;





  
$more_extra_price_title=$request->more_extra_price_title;
$more_extra_price_price=$request->more_extra_price_price;
$more_extra_price_type=$request->more_extra_price_type;
$more_extra_price_person=$request->more_extra_price_person;


if(isset($more_extra_price_title))
{
        $arrLength1 = count($more_extra_price_title);
      for($i = 0; $i < $arrLength1; $i++) {

$more_extra_price_details[]=(object)[
    
    'more_extra_price_title'=>$more_extra_price_title[$i],
    'more_extra_price_price'=>$more_extra_price_price[$i],
    'more_extra_price_type'=>$more_extra_price_type[$i],
    'more_extra_price_person'=>$more_extra_price_person[$i],
    ];

}
// print_r($more_extra_price_details); 
// die();
$tour_extra_price_1=json_encode($more_extra_price_details);
    
}
else
{
   $tour_extra_price_1= ''; 
}



  


    
    
    




$faq_title=$request->faq_title;
$faq_content=$request->faq_content;
$tour_faq=array([
  'faq_title'=>json_encode($faq_title),
    'faq_content'=>json_encode($faq_content),
    

    ]);

$tour_faq=json_encode($tour_faq);


$more_faq_title=$request->more_faq_title;
$more_faq_content=$request->more_faq_content;

if(isset($more_faq_title))
{
     $arrLength2 = count($more_faq_title);
      for($i = 0; $i < $arrLength2; $i++) {

$more_faq_details[]=(object)[
    
    'more_faq_title'=>$more_faq_title[$i],
    'more_faq_content'=>$more_faq_content[$i],
   
    ];

}
// print_r($more_faq_details); 
// die();




$tour_faq_1=json_encode($more_faq_details);
    
}
else
{
   $tour_faq_1= ''; 
}


    


 //usama changes
 
    //   $property_country=$request->property_country;
    //   $property_city=$request->property_city;
    //   $hotel_name=$request->hotel_name;
    //   $hotel_rooms_type=$request->hotel_rooms_type;
    //   $price_per_night=$request->price_per_night;
    //   $total_price_per_night=$request->total_price_per_night;
    
      $visa_type=$request->visa_type;
       $visa_fee=$request->visa_fee;
        $visa_rules_regulations=$request->visa_rules_regulations;
        
        
        if($request->hasFile('visa_image')){

        $file = $request->file('visa_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . 'visa.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_visa_image = $filename;
    }
    else{
        $tour_visa_image = '';
    }


$visa_image=$tour_visa_image;
        
        
        
    //   $t_commission=$request->t_commission;
    
    
    if($request->markupSwitch == 'single_markup_switch'){
        $quad_grand_total_amount=$request->quad_grand_total_amount_single;
        $triple_grand_total_amount=$request->triple_grand_total_amount_single;
        $double_grand_total_amount=$request->double_grand_total_amount_single;
    }else{
        $quad_grand_total_amount=$request->quad_grand_total_amount;
        $triple_grand_total_amount=$request->triple_grand_total_amount;
        $double_grand_total_amount=$request->double_grand_total_amount;
    }
    
   
    
    
     $currency_symbol=$request->currency_symbol;
     
     $quad_cost_price=$request->quad_cost_price;
    $triple_cost_price=$request->triple_cost_price;
    $double_cost_price=$request->double_cost_price;
     $all_markup_type=$request->all_markup_type;
     $all_markup_add=$request->all_markup_add;
     
     $payment_gateways=$request->payment_gateways;
     $payment_modes=$request->payment_modes;
     $payment_modes=json_encode($payment_modes);
     
     $starts_rating=$request->starts_rating;
    // $all_quad_markup=$request->all_quad_markup;
    // $all_triple_markup=$request->all_triple_markup;
    //  $all_double_markup=$request->all_double_markup;

            // print_r($flights_details);
            // print_r($more_flights_details);
            // die();

    
			$curl = curl_init();
 
			$data = array('token'=>$token,
			'customer_id'=>$customer_id,
			'title'=>$title,
			'content'=>$content,
			'tour_categories'=>$tour_categories,
			'tour_attributes'=>$tour_attributes,
			'start_date'=>$start_date,
			'end_date'=>$end_date,
			'time_duration'=>$time_duration,
// 			'tour_min_people'=>$tour_min_people,
// 			'tour_max_people'=>$tour_max_people,
			'tour_location'=>$tour_location,
			'whats_included'=>$whats_included,
			'whats_excluded'=>$whats_excluded,
			'currency_symbol'=>$currency_symbol,
// 			'tour_pricing'=>$tour_pricing,
// 			'tour_sale_price'=>$tour_sale_price,
// 			'tour_publish'=>$tour_publish,
			'tour_author'=>$tour_author,
			'tour_feature'=>$tour_feature,
			'defalut_state'=>$defalut_state,
			'tour_featured_image'=>$tour_featured_image,
      'tour_banner_image'=>$tour_banner_image,
      'Itinerary_details'=>$Itinerary_details,
      'tour_itinerary_details_1'=>$tour_itinerary_details_1,
      'tour_extra_price'=>$tour_extra_price,
      'tour_extra_price_1'=>$tour_extra_price_1,
      'tour_faq'=>$tour_faq,
      'tour_faq_1'=>$tour_faq_1,
    //   'external_packages'=>$external_packages,
    //   'property_country'=>$request->property_country,
    //   'property_city'=>$request->property_city,
    //   'hotel_name'=>$request->hotel_name,
    //   'hotel_rooms_type'=>$request->hotel_rooms_type,
    //   'price_per_night'=>$request->price_per_night,
    //   'total_price_per_night'=>$request->total_price_per_night,
      
      
      
      'markup_details'=>$markup_details,
      'more_markup_details'=>$more_markup_details,
      
      'no_of_pax_days'=>$no_of_pax_days,
      'destination_details'=>$destination_details,
      'destination_details_more'=>$destination_details_more,
      'flights_details'=>$flights_details,
      'more_flights_details'=>$more_flights_details,
      'accomodation_details'=>$accomodation_details,
      'more_accomodation_details'=>$more_accomodation_details,
      'transportation_details'=>$transportation_details,
      'transportation_details_more'=>$transportation_details_more,
      'visa_fee'=>$visa_fee,
      'visa_type'=>$visa_type,
      'visa_image'=>$visa_image,
      'visa_rules_regulations'=>$visa_rules_regulations,
        
        
        'quad_grand_total_amount'=>$quad_grand_total_amount,
        'triple_grand_total_amount'=>$triple_grand_total_amount,
        'double_grand_total_amount'=>$double_grand_total_amount,
    
    
     'quad_cost_price'=>$quad_cost_price,
    'triple_cost_price'=>$triple_cost_price,
    'double_cost_price'=>$double_cost_price,
     'all_markup_type'=>$all_markup_type,
     'all_markup_add'=>$all_markup_add,
     'gallery_images'=>$gallery_images,
     
     'payment_gateways'=>$payment_gateways,
     'payment_modes'=>$payment_modes,
      'starts_rating'=>$starts_rating,
     
    // 'all_quad_markup'=>$all_quad_markup,
    // 'all_triple_markup'=>$all_triple_markup,
    //  'all_double_markup'=>$all_double_markup,
    
        'checkout_message'      => $checkout_message,
        'cancellation_policy'   => $cancellation_policy,
    
      );
	        
	       // print_r($data);die();
	        
			curl_setopt_array($curl, array(
    			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/add_tour',
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
// 			echo $response;die();
			curl_close($curl);
			$attributes = json_decode($response);  
      	    return redirect()->route('view_tour');
       }
       
    public function view_tour(Request $request){
        $token=config('token');
        $customer_id=Session::get('id');
      	$curl = curl_init();
    
		$data = array('token'=>$token,'customer_id'=>$customer_id);
        curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_tour_list',
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
		$tours1 = json_decode($response);
        // print_r($tours1);die();
        $tours      = $tours1->tours;
        $final_data = $tours1->data1;
        $booking_Id = $tours1->booking_Id;
        // print_r($tours);die();
        $data = [];
        foreach($tours as $tours_res){
            $tour_book = false;
            foreach($final_data as $book_res){
                if($tours_res->id == $book_res->tour_id){
                    $tour_book = true;
                }
            }

            $single_tour = [
                'id'                 => $tours_res->id,
                'title'              => $tours_res->title,
                'no_of_pax_days'     => $tours_res->no_of_pax_days,
                'start_date'         => $tours_res->start_date,
                'end_date'           => $tours_res->end_date,
                'tour_location'      => $tours_res->tour_location,
                'tour_author'        => $tours_res->tour_author,
                'tour_publish'       => $tours_res->tour_publish,
                'no_of_pax_days'     => $tours_res->no_of_pax_days,
                'book_status'        => $tour_book,
            ];
            array_push($data,$single_tour);      
        }
        
        // print_r($tours);die();
        // print_r($data);die();
        // print_r($final_data);die();
        // print_r($booking_Id);die();
        return view('template/frontend/userdashboard/pages/tour/view_tour',compact(['tours','data','final_data']));  
    } 

  // More Tour Details
    public function more_Tour_Details($id) {
    $token = config('token');
    $customer_id = Session::get('id');
    $curl = curl_init();

    $data = array('token'=>$token,'customer_id'=>$customer_id,'id' => $id);
    // dd($data);
    curl_setopt_array($curl, array(
       CURLOPT_URL => 'https://admin.synchronousdigital.com/api/more_Tour_Details/{id}',
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
    $tours1          = json_decode($response);
    $packges_tour    = $tours1->packges_tour;
    $no_of_pax_days  = $tours1->no_of_pax_days;
    $booked_tour     = $tours1->booked_tour;
    $outStandings    = $tours1->outStandings;
    $recieved        = $tours1->recieved;

    // print_r($recieve_Amount);die();
    return response()->json([
      'packges_tour'      => $packges_tour,
      'no_of_pax_days'    => $no_of_pax_days,
      'booked_tour'       => $booked_tour,
      'outStandings'      => $outStandings,
      'recieved'          => $recieved,
    ]);
  }

    public function edit_tour($id,$type = 'tour')
    {
        $token=config('token');
      	$curl = curl_init();
      	$customer_id=Session::get('id');
      	$package_type = $type;
      	
		$data = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id,'type' => $type);
        curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_tour_api/{$id}',
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
        $tours=$data->tours;
        // print_r($tours);die();
        
        $gallery_Img = json_decode($tours->gallery_images);
        
        $markup_details = json_decode($tours->markup_details);
        $more_markup_details = json_decode($tours->more_markup_details);
        // print_r($markup_details);die();
        
        $tour_faq=json_decode($tours->tour_faq);
        $iternery=json_decode($tours->Itinerary_details);
        
        $tour_faq_1=json_decode($tours->tour_faq_1);
        $iternery1=json_decode($tours->tour_itinerary_details_1);
        
        $destination_details = json_decode($tours->destination_details);
        $destination_details_more = json_decode($tours->destination_details_more);
        
        $flights_details = json_decode($tours->flights_details);
        $flights_details_more = json_decode($tours->flights_details_more);
        
        $accomodation_details = json_decode($tours->accomodation_details);
        $accomodation_details_more = json_decode($tours->accomodation_details_more);
        // print_r($flights_details);die();
        
        $transportation_details = json_decode($tours->transportation_details);
        $transportation_details_more = json_decode($tours->transportation_details_more);
        
        $additional_service = json_decode($tours->tour_extra_price);
        $additional_service_more = json_decode($tours->tour_extra_price_1);
        
        
		$categories=$data->categories;
		$all_countries=$data->all_countries;
    	$payment_gateways=$data->payment_gateways;
    	$payment_modes=$data->payment_modes;
    	
        return view('template/frontend/userdashboard/pages/tour/edit_tour',compact('more_markup_details','markup_details','gallery_Img','tour_faq_1','tour_faq','additional_service_more','additional_service','iternery1','iternery','transportation_details','flights_details_more','flights_details','accomodation_details_more','accomodation_details','destination_details','tours','categories','all_countries','payment_gateways','payment_modes'));
    }

    public function submit_edit_tour(Request $request,$id)
    {
        $token=config('token');
        $customer_id=Session::get('id');
        // Data
            $title              =   $request->title;
            $no_of_pax_days     =   $request->no_of_pax_days;
            $starts_rating      =   $request->starts_rating;
            $currency_symbol    =   $request->currency_symbol;
            $content            =   $request->content;
            $start_date         =   $request->start_date;
            $end_date           =   $request->end_date;
            $time_duration      =   $request->time_duration;
            $tour_categories    =   $request->categories;
            // $tour_categories    =   json_encode($tour_categories);
            $tour_feature       =   $request->tour_feature;
            $defalut_state      =   $request->defalut_state;
            $tour_author        =   $request->tour_author;
            
            if($request->hasFile('tour_featured_image')){
                $file = $request->file('tour_featured_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_featured_image = $filename;
            }
            else{
                $tour_featured_image = $request->tour_featured_image_else;
            }
            
            $tour_featured_image=$tour_featured_image;
            // print_r($tour_featured_image);die();
            
            if($request->hasFile('tour_banner_image')){
                $file = $request->file('tour_banner_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_banner_image = $filename;
            }
            else{
                $tour_banner_image = $request->tour_banner_image_else;
            }
            $tour_banner_image=$tour_banner_image;
            // print_r($tour_banner_image);die();
            
            $gallery_images=array();
            if($files=$request->file('gallery_images')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    $file->move('public/uploads/package_imgs/',$name);
                    $gallery_images[]=$name;
                }
            }
            else
            {
                $gallery_images = $request->gallery_images_else;
                
            }
            if(isset($gallery_images))
            {
                $gallery_images=json_encode($gallery_images);  
            }
            else
            {
                $gallery_images = '';
            }
            // print_r($gallery_images);die();
            
            $destination_country=$request->destination_country;
            $destination_city=$request->destination_city;
            $destination_details=[
                'destination_country'=>$destination_country,
                'destination_city'=>$destination_city,
            ];
            $destination_details=json_encode($destination_details);
            
            $more_destination_country=$request->more_destination_country;
            $more_destination_city=$request->more_destination_city;
            $destination_details_more=[
                'more_destination_country'=>$more_destination_country,
                'more_destination_city'=>$more_destination_city,
            ];
            $destination_details_more=json_encode($destination_details_more);
            
            // Accomodation_Details
            $hotel_city_name=$request->hotel_city_name;
            $acc_hotel_name=$request->acc_hotel_name;
            $acc_check_in=$request->acc_check_in;
            $acc_check_out=$request->acc_check_out;
            $acc_type=$request->acc_type;
            $acc_qty=$request->acc_qty;
            $acc_pax=$request->acc_pax;
            $acc_price=$request->acc_price;
            $acc_currency=$request->acc_currency;
            $acc_total_amount=$request->acc_total_amount;
            $acc_no_of_nightst=$request->acc_no_of_nightst;
            $hotel_whats_included=$request->hotel_whats_included;
            
            // if($request->hasFile('accomodation_image')){
            //     $file = $request->file('accomodation_image');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() . '.' . $extension;
            //     $file->move('public/uploads/package_imgs/', $filename);
            //     $tour_accomodation_image = $filename;
            // }
            $accomodation_image=array();
            if($files=$request->file('accomodation_image')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    $file->move('public/uploads/package_imgs/',$name);
                    $accomodation_image[]=$name;
                }
            }
            else{
                $accomodation_image = $request->accomodation_image_else;
            }
            $accomodation_image=$accomodation_image;
            // print_r($accomodation_image);die();
    
            if(isset($acc_hotel_name))
            {   
                $arrLength = count($acc_hotel_name);
                for($i = 0; $i < $arrLength; $i++) {
                    $accomodation_details[]=(object)[
                        'hotel_city_name'=>$hotel_city_name[$i] ?? '',
                        'acc_hotel_name'=>$acc_hotel_name[$i] ?? '',
                        'acc_check_in'=>$acc_check_in[$i] ?? '',
                        'acc_check_out'=>$acc_check_out[$i] ?? '',
                        'acc_type'=>$acc_type[$i] ?? '',
                        'acc_qty'=>$acc_qty[$i] ?? '',
                        'acc_pax'=>$acc_pax[$i] ?? '',
                        'acc_price'=>$acc_price[$i],
                        'acc_currency'=>$acc_currency[$i] ?? '',
                        'acc_total_amount'=>$acc_total_amount[$i] ?? '',
                        'acc_no_of_nightst'=>$acc_no_of_nightst[$i] ?? '',
                        'accomodation_image'=>$accomodation_image[$i] ?? '',
                        'hotel_whats_included'=>$hotel_whats_included[$i] ?? '',
                    ];
                }
                $accomodation_details=json_encode($accomodation_details);
            }
            else
            {
               $accomodation_details = ''; 
            }
            
            // print_r($accomodation_details);die();
            
            // More Acoomodation Details
            $more_hotel_city=$request->more_hotel_city;
            $more_acc_type=$request->more_acc_type;
            $more_acc_qty=$request->more_acc_qty;
            $more_acc_pax=$request->more_acc_pax;
            $more_acc_price=$request->more_acc_price;
            $more_acc_total_amount=$request->more_acc_total_amount;
    
            if(isset($more_acc_type))
            {
                $arrLength = count($more_acc_type);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_accomodation_details[]=(object)[
                        'more_hotel_city'=>$more_hotel_city[$i] ?? '',
                        'more_acc_type'=>$more_acc_type[$i] ?? '',
                        'more_acc_qty'=>$more_acc_qty[$i] ?? '',
                        'more_acc_pax'=>$more_acc_pax[$i] ?? '',
                        'more_acc_price'=>$more_acc_price[$i] ?? '',
                        'more_acc_total_amount'=>$more_acc_total_amount[$i] ?? '',
                    ];
                }
            
            $more_accomodation_details=json_encode($more_accomodation_details);
            }
            else
            {
               $more_accomodation_details = ''; 
            }
            
            // End_Accomodation_Details
            
            // Flight_Details
            $departure_airport_code=$request->departure_airport_code;
            $arrival_airport_code=$request->arrival_airport_code;
            $other_Airline_Name2=$request->other_Airline_Name2;
            $departure_Flight_Type=$request->departure_Flight_Type;
            $departure_flight_number=$request->departure_flight_number;
            $departure_time=$request->departure_time;
            $arrival_time=$request->arrival_time;
            $total_Time=$request->total_Time;
            $flight_type=$request->flight_type;
            $flights_per_person_price=$request->flights_per_person_price;
            $connected_flights_duration_details=$request->connected_flights_duration_details;
            $terms_and_conditions=$request->terms_and_conditions;
            // Return Details
            $return_departure_airport_code=$request->return_departure_airport_code;
            $return_arrival_airport_code=$request->return_arrival_airport_code;
            $return_other_Airline_Name2=$request->return_other_Airline_Name2;
            $return_departure_Flight_Type=$request->return_departure_Flight_Type;
            $return_departure_flight_number=$request->return_departure_flight_number;
            $return_departure_time=$request->return_departure_time;
            $return_arrival_time=$request->return_arrival_time;
            $return_total_Time=$request->return_total_Time;
            
            if($request->hasFile('flights_image')){
                $file = $request->file('flights_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . 'fl.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_flights_image = $filename;
            }
            else{
                $tour_flights_image = $request->flights_image_else;
            }
            
            
            $flights_image=$tour_flights_image;
            $flights_details=[
                'departure_airport_code'=>$departure_airport_code,
                'arrival_airport_code'=>$arrival_airport_code,
                'other_Airline_Name2'=>$other_Airline_Name2,
                'departure_Flight_Type'=>$departure_Flight_Type,
                'departure_flight_number'=>$departure_flight_number,
                'departure_time'=>$departure_time,
                'arrival_time'=>$arrival_time,
                'total_Time'=>$total_Time,
                'flight_type'=>$flight_type,
                'flights_per_person_price'=>$flights_per_person_price,
                'connected_flights_duration_details'=>$connected_flights_duration_details,
                'terms_and_conditions'=>$terms_and_conditions,
                'flights_image'=>$tour_flights_image,
                // return_details
                'return_departure_airport_code'=>$return_departure_airport_code,
                'return_arrival_airport_code'=>$return_arrival_airport_code,
                'return_other_Airline_Name2'=>$return_other_Airline_Name2,
                'return_departure_Flight_Type'=>$return_departure_Flight_Type,
                'return_departure_flight_number'=>$return_departure_flight_number,
                'return_departure_time'=>$return_departure_time,
                'return_arrival_time'=>$return_arrival_time,
                'return_total_Time'=>$return_total_Time,
            ];
            $flights_details=json_encode($flights_details);
            
            // More Flight Data
            $more_departure_airport_code=$request->more_departure_airport_code;
            $more_arrival_airport_code=$request->more_arrival_airport_code;
            $more_other_Airline_Name2=$request->more_other_Airline_Name2;
            $more_departure_Flight_Type=$request->more_departure_Flight_Type;
            $more_departure_flight_number=$request->more_departure_flight_number;
            $more_departure_time=$request->more_departure_time;
            $more_arrival_time=$request->more_arrival_time;
            $more_total_Time=$request->more_total_Time;
            // Return Details
            $return_more_departure_airport_code=$request->return_more_departure_airport_code;
            $return_more_arrival_airport_code=$request->return_more_arrival_airport_code;
            $return_more_other_Airline_Name2=$request->return_more_other_Airline_Name2;
            $return_more_departure_Flight_Type=$request->return_more_departure_Flight_Type;
            $return_more_departure_flight_number=$request->return_more_departure_flight_number;
            $return_more_departure_time=$request->return_more_departure_time;
            $return_more_arrival_time=$request->return_more_arrival_time;
            $return_more_total_Time=$request->return_more_total_Time;
            
            if(isset($more_departure_airport_code))
            {
                $arrLength = count($more_departure_airport_code);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_flights_details[]=(object)[
                        'more_departure_airport_code'=>$more_departure_airport_code[$i],
                        'more_arrival_airport_code'=>$more_arrival_airport_code[$i],
                        'more_other_Airline_Name2'=>$more_other_Airline_Name2[$i],
                        'more_departure_Flight_Type'=>$more_departure_Flight_Type[$i],
                        'more_departure_flight_number'=>$more_departure_flight_number[$i],
                        'more_departure_time'=>$more_departure_time[$i],
                        'more_arrival_time'=>$more_arrival_time[$i],
                        'more_total_Time'=>$more_total_Time[$i],
                        // Return Details
                        'return_more_departure_airport_code'=>$return_more_departure_airport_code[$i],
                        'return_more_arrival_airport_code'=>$return_more_arrival_airport_code[$i],
                        'return_more_other_Airline_Name2'=>$return_more_other_Airline_Name2[$i],
                        'return_more_departure_Flight_Type'=>$return_more_departure_Flight_Type[$i],
                        'return_more_departure_flight_number'=>$return_more_departure_flight_number[$i],
                        'return_more_departure_time'=>$return_more_departure_time[$i],
                        'return_more_arrival_time'=>$return_more_arrival_time[$i],
                        'return_more_total_Time'=>$return_more_total_Time[$i],
                    ];
                }
                $more_flights_details=json_encode($more_flights_details);
            }
            else
            {
               $more_flights_details = ''; 
            }
            // End_Flight_Details
            
            // Visa_Details
            $visa_type=$request->visa_type;
            $visa_fee=$request->visa_fee;
            $visa_rules_regulations=$request->visa_rules_regulations;
            if($request->hasFile('visa_image')){
                $file = $request->file('visa_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_visa_image = $filename;
            }
            else{
                $tour_visa_image = $request->visa_image_else;
            }
            $visa_image=$tour_visa_image;
            // End_Visa_Details
            
            // Transportation Details
            $transportation_pick_up_location=$request->transportation_pick_up_location;
            $transportation_drop_off_location=$request->transportation_drop_off_location;
            $transportation_pick_up_date=$request->transportation_pick_up_date;
            $transportation_drop_of_date=$request->transportation_drop_of_date;
            $transportation_total_Time=$request->transportation_total_Time;
            $transportation_trip_type=$request->transportation_trip_type;
            $transportation_vehicle_type=$request->transportation_vehicle_type;
            $transportation_no_of_vehicle=$request->transportation_no_of_vehicle;
            $transportation_price_per_vehicle=$request->transportation_price_per_vehicle;
            $transportation_vehicle_total_price=$request->transportation_vehicle_total_price;
            $transportation_price_per_person=$request->transportation_price_per_person;
            // Return Transportation Details
            $return_transportation_pick_up_location=$request->return_transportation_pick_up_location;
            $return_transportation_drop_off_location=$request->return_transportation_drop_off_location;
            $return_transportation_pick_up_date=$request->return_transportation_pick_up_date;
            $return_transportation_drop_of_date=$request->return_transportation_drop_of_date;
            $return_transportation_total_Time=$request->return_transportation_total_Time;
            $return_transportation_vehicle_type=$request->return_transportation_vehicle_type;
            $return_transportation_no_of_vehicle=$request->return_transportation_no_of_vehicle;
            $return_transportation_price_per_vehicle=$request->return_transportation_price_per_vehicle;
            $return_transportation_vehicle_total_price=$request->return_transportation_vehicle_total_price;
            $return_transportation_price_per_person=$request->return_transportation_price_per_person;
            
        
            if($request->hasFile('transportation_image')){
        
                $file = $request->file('transportation_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . 'trans.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $transportation_image = $filename;
            }
            else{
                $transportation_image = $request->transportation_image_else;
            }
        
            $transportation_details=[
                'transportation_pick_up_location'=>$transportation_pick_up_location ?? '',
                'transportation_drop_off_location'=>$transportation_drop_off_location ?? '',
                'transportation_pick_up_date'=>$transportation_pick_up_date ?? '',
                'transportation_drop_of_date'=>$transportation_drop_of_date ?? '',
                'transportation_total_Time'=>$transportation_total_Time ?? '',
                'transportation_trip_type'=>$transportation_trip_type ?? '',
                'transportation_vehicle_type'=>$transportation_vehicle_type ?? '',
                'transportation_no_of_vehicle'=>$transportation_no_of_vehicle ?? '',
                'transportation_price_per_vehicle'=>$transportation_price_per_vehicle ?? '',
                'transportation_vehicle_total_price'=>$transportation_vehicle_total_price ?? '',
                'transportation_price_per_person'=>$transportation_price_per_person ?? '',
                // Transportatioin Return Details
                'return_transportation_pick_up_location'=>$return_transportation_pick_up_location ?? '',
                'return_transportation_drop_off_location'=>$return_transportation_drop_off_location ?? '',
                'return_transportation_drop_of_date'=>$return_transportation_drop_of_date ?? '',
                'return_transportation_pick_up_date'=>$return_transportation_pick_up_date ?? '',
                'return_transportation_total_Time'=>$return_transportation_total_Time ?? '',
                'return_transportation_vehicle_type'=>$return_transportation_vehicle_type ?? '',
                'return_transportation_no_of_vehicle'=>$return_transportation_no_of_vehicle ?? '',
                'return_transportation_price_per_vehicle'=>$return_transportation_price_per_vehicle ?? '',
                'return_transportation_vehicle_total_price'=>$return_transportation_vehicle_total_price ?? '',
                'return_transportation_price_per_person'=>$return_transportation_price_per_person ?? '',
                
                'transportation_image'=>$transportation_image ?? '',
            ];
            
            $transportation_details=json_encode($transportation_details);
            $more_transportation_pick_up_location=$request->more_transportation_pick_up_location;
            $more_transportation_drop_off_location=$request->more_transportation_drop_off_location;
            $more_transportation_pick_up_date=$request->more_transportation_pick_up_date;
        
        
            if(isset($more_transportation_pick_up_location))
            {
                $arrLength = count($more_transportation_pick_up_location);
                for($i = 0; $i < $arrLength; $i++) {
                    $transportation_details_more[]=(object)[
                        'more_transportation_pick_up_location'=>$more_transportation_pick_up_location[$i] ?? '',
                        'more_transportation_drop_off_location'=>$more_transportation_drop_off_location[$i] ?? '',
                        'more_transportation_pick_up_date'=>$more_transportation_pick_up_date[$i] ?? '',
                    ];
                }
                $transportation_details_more=json_encode($transportation_details_more);
            }
            else
            {
               $transportation_details_more = ''; 
            }
            // End Transportation Details
            
            // Itinerary_Deatils
            $whats_included=$request->whats_included;
            $whats_excluded=$request->whats_excluded;
            $cancellation_policy    =   $request->cancellation_policy;
            
            $Itinerary_title=$request->Itinerary_title;
            $Itinerary_city=$request->Itinerary_city;
            $Itinerary_content=$request->Itinerary_content;
    
            if($request->hasFile('Itinerary_image')){
                $file = $request->file('Itinerary_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $Itinerary_image = $filename;
            }
            else{
                $Itinerary_image = $request->Itinerary_image_else;
            }
            
            $Itinerary_image=$Itinerary_image;
            
            $Itinerary_details=array([
                'Itinerary_title'=>$Itinerary_title,
                'Itinerary_city'=>$Itinerary_city,
                'Itinerary_content'=>$Itinerary_content,
                'Itinerary_image'=>$Itinerary_image,
                
            ]);
            // print_r($Itinerary_details);die();
            if(!isset($Itinerary_title)){
                $Itinerary_details = NULL;
                // print_r($Itinerary_details);die();
            }
            else{
                $Itinerary_details=json_encode($Itinerary_details);   
            }
            // print_r($Itinerary_details);die();
            
            $more_Itinerary_title=$request->more_Itinerary_title;
            $more_Itinerary_city=$request->more_Itinerary_city;
            $more_Itinerary_content=$request->more_Itinerary_content;
            $more_Itinerary_image=array();
            
            $more_Itinerary_image=array();
            if($files=$request->file('more_Itinerary_image')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    $file->move('public/uploads/package_imgs/',$name);
                    $more_Itinerary_image[]=$name;
                }
            }
            else{
                $more_Itinerary_image = $request->more_Itinerary_image_else;
            }
    
            $more_Itinerary_image=$more_Itinerary_image;
          
            if(isset($more_Itinerary_title))
            {  
                $arrLength = count($more_Itinerary_title);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_itinery_details[]=(object)[
                        'more_Itinerary_title'=>$more_Itinerary_title[$i] ?? '',
                        'more_Itinerary_city'=>$more_Itinerary_city[$i] ?? '',
                        'more_Itinerary_content'=>$more_Itinerary_content[$i] ?? '',
                        'more_Itinerary_image'=>$more_Itinerary_image[$i] ?? '',
                    ];
                }
                $tour_itinerary_details_1=json_encode($more_itinery_details);  
            }
            else
            {
                $tour_itinerary_details_1= ''; 
            }
            // End_Itinerary_Deatils
            
            // Additional_Services_Details
            $extra_price_name=$request->extra_price_name;
            $extra_price_price=$request->extra_price_price;
            $extra_price_type=$request->extra_price_type;
            $extra_price_person=$request->extra_price_person;
            $tour_extra_price=array([
                'extra_price_name'=>$extra_price_name ?? '',
                'extra_price_price'=>$extra_price_price ?? '',
                'extra_price_type'=>$extra_price_type ?? '',
                'extra_price_person'=>$extra_price_person ?? '',
            ]);
            if(!isset($extra_price_name)){
                $tour_extra_price = NULL;
                // print_r($Itinerary_details);die();
            }
            else{
                $tour_extra_price=json_encode($tour_extra_price);
            }
            
            
            $more_extra_price_title=$request->more_extra_price_title;
            $more_extra_price_price=$request->more_extra_price_price;
            $more_extra_price_type=$request->more_extra_price_type;
            $more_extra_price_person=$request->more_extra_price_person;
    
            if(isset($more_extra_price_title))
            {
                $arrLength1 = count($more_extra_price_title);
                for($i = 0; $i < $arrLength1; $i++) {
                    $more_extra_price_details[]=(object)[
                        'more_extra_price_title'=>$more_extra_price_title[$i] ?? '',
                        'more_extra_price_price'=>$more_extra_price_price[$i] ?? '',
                        'more_extra_price_type'=>$more_extra_price_type[$i] ?? '',
                        'more_extra_price_person'=>$more_extra_price_person[$i] ?? '',
                    ];
                }
                $tour_extra_price_1=json_encode($more_extra_price_details);
            }
            else
            {
               $tour_extra_price_1= ''; 
            }
            // END_Additional_Services_Details
            
            // FAQ_Deatils
            $faq_title=$request->faq_title;
            $faq_content=$request->faq_content;
            $tour_faq=array([
                'faq_title'=>$faq_title ?? '',
                'faq_content'=>$faq_content ?? '',
            ]);
            
            if(!isset($faq_title)){
                $tour_faq = NULL;
                // print_r($Itinerary_details);die();
            }
            else{
                $tour_faq=json_encode($tour_faq);
            }
            
            $more_faq_title=$request->more_faq_title;
            $more_faq_content=$request->more_faq_content;
        
            if(isset($more_faq_title))
            {
                $arrLength2 = count($more_faq_title);
                for($i = 0; $i < $arrLength2; $i++) {
                $more_faq_details[]=(object)[
                        'more_faq_title'=>$more_faq_title[$i] ?? '',
                        'more_faq_content'=>$more_faq_content[$i] ?? '',
                    ];
                }
                $tour_faq_1=json_encode($more_faq_details);
            }
            else
            {
               $tour_faq_1= ''; 
            }
            // END_FAQ_Deatils
            
            // Costing_Details
            $quad_cost_price=$request->quad_cost_price;
            $triple_cost_price=$request->triple_cost_price;
            $double_cost_price=$request->double_cost_price;
            if($request->markupSwitch == 'single_markup_switch'){
                $quad_grand_total_amount=$request->quad_grand_total_amount_single;
                $triple_grand_total_amount=$request->triple_grand_total_amount_single;
                $double_grand_total_amount=$request->double_grand_total_amount_single;
            }else{
                $quad_grand_total_amount=$request->quad_grand_total_amount;
                $triple_grand_total_amount=$request->triple_grand_total_amount;
                $double_grand_total_amount=$request->double_grand_total_amount;
            }
            // $quad_grand_total_amount=$request->quad_grand_total_amount;
            // $triple_grand_total_amount=$request->triple_grand_total_amount;
            // $double_grand_total_amount=$request->double_grand_total_amount;
            $payment_gateways=$request->payment_gateways;
            $payment_modes=$request->payment_modes;
            $payment_modes=json_encode($payment_modes);
            $checkout_message=$request->checkout_message;
            
            $all_markup_type=$request->all_markup_type;
            $all_markup_add=$request->all_markup_add;
            // END_Costing_Details
            
            $tour_publish=$request->tour_publish;
            $tour_location=$request->tour_location_city;
            $tour_location=json_encode($tour_location);
            // Without_markup_FVT
            $hotel_name_markup=$request->hotel_name_markup;
            $room_type=$request->room_type;
            $without_markup_price=$request->without_markup_price;
            $markup_type=$request->markup_type;
            $markup=$request->markup;
            $markup_price=$request->markup_price;
            
            if(isset($hotel_name_markup))
            {
                $arrLength = count($hotel_name_markup);
                for($i = 0; $i < $arrLength; $i++) {
                    $markup_details[]=(object)[
                        'hotel_name_markup'=>$hotel_name_markup[$i] ?? '',
                        'room_type'=>$room_type[$i] ?? '',
                        'without_markup_price'=>$without_markup_price[$i] ?? '',
                        'markup_type'=>$markup_type[$i] ?? '',
                        'markup'=>$markup[$i] ?? '',
                        'markup_price'=>$markup_price[$i] ?? '',
                    ];
                }
                $markup_details=json_encode($markup_details);
            }
            else
            {
               $markup_details = '';
               
            }
            
            $more_hotel_name_markup=$request->more_hotel_name_markup;
            $more_room_type=$request->more_room_type;
            $more_without_markup_price=$request->more_without_markup_price;
            $more_markup_type=$request->more_markup_type;
            $more_markup=$request->more_markup;
            $more_markup_price=$request->more_markup_price;
            
            if(isset($more_hotel_name_markup))
            {
                $arrLength = count($more_hotel_name_markup);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_markup_details[]=(object)[
                        'more_hotel_name_markup'=>$more_hotel_name_markup[$i],
                        'more_room_type'=>$more_room_type[$i],
                        'more_without_markup_price'=>$more_without_markup_price[$i],
                        'more_markup_type'=>$more_markup_type[$i],
                        'more_markup'=>$more_markup[$i],
                        'more_markup_price'=>$more_markup_price[$i],
                    ];
                }
                $more_markup_details=json_encode($more_markup_details);
            }
            else
            {
               $more_markup_details = ''; 
            }
            // END_without_markup_FVT
            
    		$curl = curl_init();
    		$data = array(
    		    'id'=>$id,
    		    'token'=>$token,
    			'customer_id'=>$customer_id,
                // Package_Details
    			'title'             =>  $title,
    			'no_of_pax_days'    =>  $no_of_pax_days,
    			'starts_rating'     =>  $starts_rating,
    			'currency_symbol'   =>  $currency_symbol,
    			'content'           =>  $content,
    			'start_date'        =>  $start_date,
    			'end_date'          =>  $end_date,
    			'time_duration'     =>  $time_duration,
    			'tour_categories'   =>  $tour_categories,
    			'tour_feature'      =>  $tour_feature,
    			'defalut_state'     =>  $defalut_state,
    			'tour_author'       =>  $tour_author,
    			'tour_featured_image'=>$tour_featured_image,
                'tour_banner_image'=>$tour_banner_image,
    			'gallery_images'=>$gallery_images,
                // Accomodation_Details
                'destination_details'=>$destination_details,
                'destination_details_more'=>$destination_details_more,
                'accomodation_details'=>$accomodation_details,
                'more_accomodation_details'=>$more_accomodation_details,
                // Flight_Details
                'flights_details'=>$flights_details,
                'more_flights_details'=>$more_flights_details,
                // Visa_Details
                'visa_fee'=>$visa_fee,
                'visa_type'=>$visa_type,
                'visa_image'=>$visa_image,
                'visa_rules_regulations'=>$visa_rules_regulations,
                // Transportation_Details
                'transportation_details'=>$transportation_details,
                'transportation_details_more'=>$transportation_details_more,
                // Itinerary_Deatils
                'whats_included'=>$whats_included,
    			'whats_excluded'=>$whats_excluded,
                'cancellation_policy'=> $cancellation_policy,
                'Itinerary_details'=>$Itinerary_details,
                'tour_itinerary_details_1'=>$tour_itinerary_details_1,
                'tour_extra_price'=>$tour_extra_price,
                'tour_extra_price_1'=>$tour_extra_price_1,
                'tour_faq'=>$tour_faq,
                'tour_faq_1'=>$tour_faq_1,
                // Costing_Details
                'quad_cost_price'=>$quad_cost_price,
                'triple_cost_price'=>$triple_cost_price,
                'double_cost_price'=>$double_cost_price,
                'quad_grand_total_amount'=>$quad_grand_total_amount,
                'triple_grand_total_amount'=>$triple_grand_total_amount,
                'double_grand_total_amount'=>$double_grand_total_amount,
                'payment_gateways'=>$payment_gateways,
                'payment_modes'=>$payment_modes,
                'checkout_message' => $checkout_message,
                
    			'tour_location'=>$tour_location,
    			'markup_details'=>$markup_details,
                'more_markup_details'=>$more_markup_details,
                'all_markup_type'=>$all_markup_type,
                'all_markup_add'=>$all_markup_add,
                
            );
            // print_r($data);die();
        // End Data

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_tours_api/{$id}',
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
		$attributes = json_decode($response);
      	return redirect()->route('view_tour');
   
  }
  
    public function delete_tour($id)
    {
    $token=config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'id'=>$id);
    
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_tour/{$id}',
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
			$enable = json_decode($response);
      
      return redirect()->back();
  }
    
    // submit_Activities_New
    public function submit_Activities_New(Request $request)
    {
        $token=config('token');
            
        // Data
            $title=$request->title;
            $no_of_pax_days=$request->no_of_pax_days;
            $external_packages=$request->external_packages;
            $external_packages=json_encode($external_packages);
            $customer_id=Session::get('id');
            $content=$request->content;
            $tour_categories=$request->categories;
            $tour_categories=$request->categories;
            $tour_attributes=$request->tour_attributes;
            $tour_attributes=json_encode($tour_attributes);
            $destination_country=$request->destination_country;
            $destination_city=$request->destination_city;
            $tour_location=$request->tour_location_city;
            $destination_details=[
                'destination_country'=>$destination_country,
                'destination_city'=>$destination_city,
            ];
            $destination_details=json_encode($destination_details);
            $more_destination_country=$request->more_destination_country;
            $more_destination_city=$request->more_destination_city;
            $destination_details_more=[
                'more_destination_country'=>$more_destination_country,
                'more_destination_city'=>$more_destination_city,
            ];
            $destination_details_more=json_encode($destination_details_more);
            $hotel_name_markup=$request->hotel_name_markup;
            $room_type=$request->room_type;
            $without_markup_price=$request->without_markup_price;
            $markup_type=$request->markup_type;
            $markup=$request->markup;
            $markup_price=$request->markup_price;
            $gallery_images=array();
            if($files=$request->file('gallery_images')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    $file->move('public/uploads/package_imgs/',$name);
                    $gallery_images[]=$name;
                }
            }
            if(isset($gallery_images))
            {
              $gallery_images=json_encode($gallery_images);  
            }
            else
            {
              $gallery_images ='';  
            }
            if(isset($hotel_name_markup))
            {
                $arrLength = count($hotel_name_markup);
                for($i = 0; $i < $arrLength; $i++) {
                    $markup_details[]=(object)[
                        'hotel_name_markup'=>$hotel_name_markup[$i],
                        'room_type'=>$room_type[$i],
                        'without_markup_price'=>$without_markup_price[$i],
                        'markup_type'=>$markup_type[$i],
                        'markup'=>$markup[$i],
                        'markup_price'=>$markup_price[$i],
                    ];
                }
                $markup_details=json_encode($markup_details);
            }
            else
            {
               $markup_details = '';
               
            }
            
            $more_hotel_name_markup=$request->more_hotel_name_markup;
            $more_room_type=$request->more_room_type;
            $more_without_markup_price=$request->more_without_markup_price;
            $more_markup_type=$request->more_markup_type;
            $more_markup=$request->more_markup;
            $more_markup_price=$request->more_markup_price;
            
            if(isset($more_hotel_name_markup))
            {
                $arrLength = count($more_hotel_name_markup);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_markup_details[]=(object)[
                        'more_hotel_name_markup'=>$more_hotel_name_markup[$i],
                        'more_room_type'=>$more_room_type[$i],
                        'more_without_markup_price'=>$more_without_markup_price[$i],
                        'more_markup_type'=>$more_markup_type[$i],
                        'more_markup'=>$more_markup[$i],
                        'more_markup_price'=>$more_markup_price[$i],
                    ];
                }
                $more_markup_details=json_encode($more_markup_details);
            }
            else
            {
               $more_markup_details = ''; 
            }
            
            $departure_airport_code=$request->departure_airport_code;
            $departure_flight_number=$request->departure_flight_number;
            $departure_date=$request->departure_date;
            $departure_time=$request->departure_time;
            $arrival_airport_code=$request->arrival_airport_code;
            $arrival_flight_number=$request->arrival_flight_number;
            $arrival_date=$request->arrival_date;
            $arrival_time=$request->arrival_time;
            $flight_type=$request->flight_type;
            $flights_per_person_price=$request->flights_per_person_price;
            $connected_flights_duration_details=$request->connected_flights_duration_details;
            $terms_and_conditions=$request->terms_and_conditions;
            
            if($request->hasFile('flights_image')){
                $file = $request->file('flights_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_flights_image = $filename;
            }
            else{
                $tour_flights_image = '';
            }
            $flights_image=$tour_flights_image;
            $flights_details=[
                'departure_airport_code'=>$departure_airport_code,
                'departure_flight_number'=>$departure_flight_number,
                'departure_date'=>$departure_date,
                'departure_time'=>$departure_time,
                
                'arrival_airport_code'=>$arrival_airport_code,
                'arrival_flight_number'=>$arrival_flight_number,
                'arrival_date'=>$arrival_date,
                'arrival_time'=>$arrival_time,
                
                'flight_type'=>$flight_type,
                'flights_per_person_price'=>$flights_per_person_price,
                'connected_flights_duration_details'=>$connected_flights_duration_details,
                'terms_and_conditions'=>$terms_and_conditions,
                'flights_image'=>$flights_image,
            ];
            $flights_details=json_encode($flights_details);
            $more_flights_departure=$request->more_flights_departure;
            $more_flights_arrival=$request->more_flights_arrival;
            $more_flights_airline=$request->more_flights_airline;
            $more_flights_per_person_price=$request->more_flights_per_person_price;
            
            if(isset($more_flights_departure))
            {
                $arrLength = count($more_flights_departure);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_flights_details[]=(object)[
                        'more_flights_departure'=>$more_flights_departure[$i],
                        'more_flights_arrival'=>$more_flights_arrival[$i],
                        'more_flights_airline'=>$more_flights_airline[$i],
                        'more_flights_per_person_price'=>$more_flights_per_person_price[$i],
                    ];
                }
                $more_flights_details=json_encode($more_flights_details);
            }
            else
            {
               $more_flights_details = ''; 
            }
    
            $acc_hotel_name=$request->acc_hotel_name;
            $acc_check_in=$request->acc_check_in;
            $acc_check_out=$request->acc_check_out;
            $acc_type=$request->acc_type;
            $acc_qty=$request->acc_qty;
            $acc_pax=$request->acc_pax;
            $acc_price=$request->acc_price;
            $acc_currency=$request->acc_currency;
            $acc_total_amount=$request->acc_total_amount;
            $acc_no_of_nightst=$request->acc_no_of_nightst;
            
            if($request->hasFile('accomodation_image')){
                $file = $request->file('accomodation_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_accomodation_image = $filename;
            }
            else{
                $tour_accomodation_image = '';
            }
            
            $accomodation_image=$tour_accomodation_image;
    
            if(isset($acc_hotel_name))
            {   
                $arrLength = count($acc_hotel_name);
                for($i = 0; $i < $arrLength; $i++) {
                    $accomodation_details[]=(object)[
                        'acc_hotel_name'=>$acc_hotel_name[$i],
                        'acc_check_in'=>$acc_check_in[$i],
                        'acc_check_out'=>$acc_check_out[$i],
                        'acc_type'=>$acc_type[$i],
                         'acc_qty'=>$acc_qty[$i],
                        'acc_pax'=>$acc_pax[$i],
                        'acc_price'=>$acc_price[$i],
                        'acc_currency'=>$acc_currency[$i],
                        'acc_total_amount'=>$acc_total_amount[$i],
                        'acc_no_of_nightst'=>$acc_no_of_nightst[$i],
                        'accomodation_image'=>$accomodation_image[$i],
                    ];
                }
                $accomodation_details=json_encode($accomodation_details);
            }
            else
            {
               $accomodation_details = ''; 
            }
    
            $transportation_pick_up_location=$request->transportation_pick_up_location;
            $transportation_drop_off_location=$request->transportation_drop_off_location;
            $transportation_pick_up_date=$request->transportation_pick_up_date;
            $transportation_trip_type=$request->transportation_trip_type;
            $transportation_vehicle_type=$request->transportation_vehicle_type;
            $transportation_no_of_vehicle=$request->transportation_no_of_vehicle;
            $transportation_price_per_vehicle=$request->transportation_price_per_vehicle;
            $transportation_vehicle_total_price=$request->transportation_vehicle_total_price;
            $return_transportation_pick_up_location=$request->return_transportation_pick_up_location;
            $return_transportation_drop_off_location=$request->return_transportation_drop_off_location;
            $return_transportation_pick_up_date=$request->return_transportation_pick_up_date;
            $transportation_price_per_person=$request->transportation_price_per_person;
    
            if($request->hasFile('transportation_image')){
                $file = $request->file('transportation_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_transportation_image = $filename;
            }
            else{
                $tour_transportation_image = '';
            }
    
            $transportation_image=$tour_transportation_image;
            $transportation_details=[
                'transportation_pick_up_location'=>$transportation_pick_up_location,
                'transportation_drop_off_location'=>$transportation_drop_off_location,
                'transportation_pick_up_date'=>$transportation_pick_up_date,
                'transportation_trip_type'=>$transportation_trip_type,
                'transportation_vehicle_type'=>$transportation_vehicle_type,
                'transportation_no_of_vehicle'=>$transportation_no_of_vehicle,
                
                'transportation_price_per_vehicle'=>$transportation_price_per_vehicle,
                'transportation_vehicle_total_price'=>$transportation_vehicle_total_price,
                
                'return_transportation_pick_up_location'=>$return_transportation_pick_up_location,
                'return_transportation_drop_off_location'=>$return_transportation_drop_off_location,
                'return_transportation_pick_up_date'=>$return_transportation_pick_up_date,
                
                'transportation_price_per_person'=>$transportation_price_per_person,
                'transportation_image'=>$transportation_image,
            ];
            $transportation_details=json_encode($transportation_details);
            $more_transportation_pick_up_location=$request->more_transportation_pick_up_location;
            $more_transportation_drop_off_location=$request->more_transportation_drop_off_location;
            $more_transportation_pick_up_date=$request->more_transportation_pick_up_date;
    
            if(isset($more_transportation_pick_up_location))
            {
                $arrLength = count($more_transportation_pick_up_location);
                for($i = 0; $i < $arrLength; $i++) {
                    $transportation_details_more[]=(object)[
                        'more_transportation_pick_up_location'=>$more_transportation_pick_up_location[$i],
                        'more_transportation_drop_off_location'=>$more_transportation_drop_off_location[$i],
                        'more_transportation_pick_up_date'=>$more_transportation_pick_up_date[$i],
                    ];
                }
                $transportation_details_more=json_encode($transportation_details_more);
            }
            else
            {
               $transportation_details_more = ''; 
            }
    
            $start_date=$request->start_date;
            $end_date=$request->end_date;
            $time_duration=$request->time_duration;
            $whats_included=$request->whats_included;
            $whats_excluded=$request->whats_excluded;
            $tour_publish=$request->tour_publish;
            $tour_author=$request->tour_author;
            $tour_feature=$request->tour_feature;
            $defalut_state=$request->defalut_state;
            
            if($request->hasFile('tour_featured_image')){
                $file = $request->file('tour_featured_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_featured_image = $filename;
            }
            else{
                $tour_featured_image = '';
            }
            
            $tour_featured_image=$tour_featured_image;
            
            if($request->hasFile('tour_banner_image')){
                $file = $request->file('tour_banner_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_banner_image = $filename;
            }
            else{
                $tour_banner_image = '';
            }
            $tour_banner_image=$tour_banner_image;
            $Itinerary_title=$request->Itinerary_title;
            $Itinerary_city=$request->Itinerary_city;
            $Itinerary_content=$request->Itinerary_content;
    
            if($request->hasFile('Itinerary_image')){
                $file = $request->file('Itinerary_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $Itinerary_image = $filename;
            }
            else{
                $Itinerary_image = '';
            }
            
            $Itinerary_image=$Itinerary_image;
            $Itinerary_details=array([
                'Itinerary_title'=>$Itinerary_title,
                'Itinerary_city'=>$Itinerary_city,
                'Itinerary_content'=>$Itinerary_content,
                'Itinerary_image'=>$Itinerary_image,
                
            ]);
            $Itinerary_details=json_encode($Itinerary_details);
            $more_Itinerary_title=$request->more_Itinerary_title;
            $more_Itinerary_city=$request->more_Itinerary_city;
            $more_Itinerary_content=$request->more_Itinerary_content;
            $more_Itinerary_image=array();
            
            if($files=$request->file('more_Itinerary_image')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    $file->move('public/uploads/package_imgs/',$name);
                    $more_Itinerary_image[]=$name;
                }
            }
    
            $more_Itinerary_image=$more_Itinerary_image;
          
            if(isset($more_Itinerary_title))
            {  
                $arrLength = count($more_Itinerary_title);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_itinery_details[]=(object)[
                        'more_Itinerary_title'=>$more_Itinerary_title[$i],
                        'more_Itinerary_city'=>$more_Itinerary_city[$i],
                        'more_Itinerary_content'=>$more_Itinerary_content[$i],
                        'more_Itinerary_image'=>$more_Itinerary_image[$i],
                    ];
                }
                $tour_itinerary_details_1=json_encode($more_itinery_details);  
            }
            else
            {
                $tour_itinerary_details_1= ''; 
            }
            
            $extra_price_name=$request->extra_price_name;
            $extra_price_price=$request->extra_price_price;
            $extra_price_type=$request->extra_price_type;
            $extra_price_person=$request->extra_price_person;
            $tour_extra_price=array([
                'extra_price_name'=>$extra_price_name,
                'extra_price_price'=>$extra_price_price,
                'extra_price_type'=>$extra_price_type,
                'extra_price_person'=>$extra_price_person,
            ]);
            $tour_extra_price=json_encode($tour_extra_price);
            $more_extra_price_title=$request->more_extra_price_title;
            $more_extra_price_price=$request->more_extra_price_price;
            $more_extra_price_type=$request->more_extra_price_type;
            $more_extra_price_person=$request->more_extra_price_person;
    
            if(isset($more_extra_price_title))
            {
                $arrLength1 = count($more_extra_price_title);
                for($i = 0; $i < $arrLength1; $i++) {
                    $more_extra_price_details[]=(object)[
                        'more_extra_price_title'=>$more_extra_price_title[$i],
                        'more_extra_price_price'=>$more_extra_price_price[$i],
                        'more_extra_price_type'=>$more_extra_price_type[$i],
                        'more_extra_price_person'=>$more_extra_price_person[$i],
                    ];
                }
                $tour_extra_price_1=json_encode($more_extra_price_details);
            }
            else
            {
               $tour_extra_price_1= ''; 
            }
    
            $faq_title=$request->faq_title;
            $faq_content=$request->faq_content;
            $tour_faq=array([
                'faq_title'=>json_encode($faq_title),
                'faq_content'=>json_encode($faq_content),
            ]);
            $tour_faq=json_encode($tour_faq);
            $more_faq_title=$request->more_faq_title;
            $more_faq_content=$request->more_faq_content;
        
            if(isset($more_faq_title))
            {
                $arrLength2 = count($more_faq_title);
                for($i = 0; $i < $arrLength2; $i++) {
                $more_faq_details[]=(object)[
                        'more_faq_title'=>$more_faq_title[$i],
                        'more_faq_content'=>$more_faq_content[$i],
                    ];
                }
                $tour_faq_1=json_encode($more_faq_details);
            }
            else
            {
               $tour_faq_1= ''; 
            }
            
            $visa_type=$request->visa_type;
            $visa_fee=$request->visa_fee;
            $visa_rules_regulations=$request->visa_rules_regulations;
            
            if($request->hasFile('visa_image')){
                $file = $request->file('visa_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_visa_image = $filename;
            }
            else{
                $tour_visa_image = '';
            }
    
            $visa_image=$tour_visa_image;
            $quad_grand_total_amount=$request->quad_grand_total_amount;
            $triple_grand_total_amount=$request->triple_grand_total_amount;
            $double_grand_total_amount=$request->double_grand_total_amount;
            $currency_symbol=$request->currency_symbol;
            $quad_cost_price=$request->quad_cost_price;
            $triple_cost_price=$request->triple_cost_price;
            $double_cost_price=$request->double_cost_price;
            $all_markup_type=$request->all_markup_type;
            $all_markup_add=$request->all_markup_add;
            $payment_gateways=$request->payment_gateways;
            $payment_modes=$request->payment_modes;
    		$curl = curl_init();
    		$data = array('token'=>$token,
    			'customer_id'=>$customer_id,
    			'title'=>$title,
    			'content'=>$content,
    			'tour_categories'=>$tour_categories,
    			'tour_attributes'=>$tour_attributes,
    			'start_date'=>$start_date,
    			'end_date'=>$end_date,
    			'time_duration'=>$time_duration,
    			'tour_location'=>$tour_location,
    			'whats_included'=>$whats_included,
    			'whats_excluded'=>$whats_excluded,
    			'currency_symbol'=>$currency_symbol,
    			'tour_publish'=>$tour_publish,
    			'tour_author'=>$tour_author,
    			'tour_feature'=>$tour_feature,
    			'defalut_state'=>$defalut_state,
    			'tour_featured_image'=>$tour_featured_image,
                'tour_banner_image'=>$tour_banner_image,
                'Itinerary_details'=>$Itinerary_details,
                'tour_itinerary_details_1'=>$tour_itinerary_details_1,
                'tour_extra_price'=>$tour_extra_price,
                'tour_extra_price_1'=>$tour_extra_price_1,
                'tour_faq'=>$tour_faq,
                'tour_faq_1'=>$tour_faq_1,
                'external_packages'=>$external_packages,
                'more_markup_details'=>$more_markup_details,
                'no_of_pax_days'=>$no_of_pax_days,
                'destination_details'=>$destination_details,
                'destination_details_more'=>$destination_details_more,
                'flights_details'=>$flights_details,
                'more_flights_details'=>$more_flights_details,
                'accomodation_details'=>$accomodation_details,
                'transportation_details'=>$transportation_details,
                'transportation_details_more'=>$transportation_details_more,
                'visa_fee'=>$visa_fee,
                'visa_type'=>$visa_type,
                'visa_image'=>$visa_image,
                'visa_rules_regulations'=>$visa_rules_regulations,
                'quad_grand_total_amount'=>$quad_grand_total_amount,
                'triple_grand_total_amount'=>$triple_grand_total_amount,
                'double_grand_total_amount'=>$double_grand_total_amount,
                'quad_cost_price'=>$quad_cost_price,
                'triple_cost_price'=>$triple_cost_price,
                'double_cost_price'=>$double_cost_price,
                'all_markup_type'=>$all_markup_type,
                'all_markup_add'=>$all_markup_add,
                'gallery_images'=>$gallery_images,
                'payment_gateways'=>$payment_gateways,
                'payment_modes'=>$payment_modes,
            );
            // dd($data);
        // End Data

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_Activities_New',
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
		$attributes = json_decode($response);
  	    return redirect()->back()->with('message','created data');
    }
    // Activities Edit Delete
    // 1st
    public function edit_activities($id)
    {
        $token=config('token');
      	$curl = curl_init();
      	$customer_id=Session::get('id');
		$data = array('token'=>$token,'customer_id'=>$customer_id,'id'=>$id);
        curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_activities/{$id}',
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
        // dd($data);
        $tours=$data->tours;
        
        $customer=$data->customer;
		$attributes=$data->attributes;
		$categories=$data->categories;
		$all_countries=$data->all_countries;
		$all_countries_currency=$data->all_countries_currency;
		$bir_airports=$data->bir_airports;
		$bir_airports1=$data->bir_airports;
        
        $bir_airports_A=$data->bir_airports;
    	$bir_airports1_A=$data->bir_airports;
    	$payment_gateways=$data->payment_gateways;
    	$payment_modes=$data->payment_modes;
    	
    	$currency_Symbol = $data->currency_Symbol;
    	$currency_Symbol1 = $data->currency_Symbol;
    	$currency_Symbol2 = $data->currency_Symbol;
    	$currency_Symbol3 = $data->currency_Symbol;
    	$currency_Symbol4 = $data->currency_Symbol;
    	$currency_Symbol5 = $data->currency_Symbol;
    	$currency_Symbol6 = $data->currency_Symbol;
    	$currency_Symbol7 = $data->currency_Symbol;
    	$currency_Symbol8 = $data->currency_Symbol;
    	$currency_Symbol9 = $data->currency_Symbol;
		$currency_Symbol10 = $data->currency_Symbol;
		$currency_Symbol11 = $data->currency_Symbol;
		$currency_Symbol12 = $data->currency_Symbol;
		$currency_Symbol13 = $data->currency_Symbol;
		$currency_Symbol14 = $data->currency_Symbol;
		
		$currency_Symbol15 = $data->currency_Symbol;
		$currency_Symbol16 = $data->currency_Symbol;
		$currency_Symbol17 = $data->currency_Symbol;
		$currency_Symbol18 = $data->currency_Symbol;
		$currency_Symbol19 = $data->currency_Symbol;
		$currency_Symbol20 = $data->currency_Symbol;
		
		$currency_Symbol21 = $data->currency_Symbol;
		$currency_Symbol22 = $data->currency_Symbol;
		$currency_Symbol23 = $data->currency_Symbol;
		$currency_Symbol24 = $data->currency_Symbol;
		
		$currency_Symbol25 = $data->currency_Symbol;
		$currency_Symbol26 = $data->currency_Symbol;
    	
        return view('template/frontend/userdashboard/pages/activities_packages/edit_activities',compact('tours','currency_Symbol25','currency_Symbol26','currency_Symbol21','currency_Symbol22','currency_Symbol23','currency_Symbol24','currency_Symbol','currency_Symbol1','currency_Symbol2','currency_Symbol3','currency_Symbol4','currency_Symbol5','currency_Symbol6','currency_Symbol7','currency_Symbol8','currency_Symbol9','currency_Symbol10','currency_Symbol11','currency_Symbol12','currency_Symbol13','currency_Symbol14','currency_Symbol15','currency_Symbol16','currency_Symbol17','currency_Symbol18','currency_Symbol19','currency_Symbol20','currency_Symbol21','bir_airports_A','bir_airports1_A','customer','attributes','categories','all_countries','all_countries_currency','bir_airports','bir_airports1','payment_gateways','payment_modes'));
    }
    // 2nd
    public function submit_edit_activities(Request $request,$id)
    {
        $token=config('token');
        // Data
            $title=$request->title;
            $no_of_pax_days=$request->no_of_pax_days;
            $external_packages=$request->external_packages;
            $external_packages=json_encode($external_packages);
            $customer_id=Session::get('id');
            $content=$request->content;
            $tour_categories=$request->categories;
            $tour_categories=json_encode($tour_categories);
            $tour_attributes=$request->tour_attributes;
            $tour_attributes=json_encode($tour_attributes);
            $destination_country=$request->destination_country;
            $destination_city=$request->destination_city;
            $tour_location=$request->tour_location_city;
            $destination_details=[
                'destination_country'=>$destination_country,
                'destination_city'=>$destination_city,
            ];
            $destination_details=json_encode($destination_details);
            $more_destination_country=$request->more_destination_country;
            $more_destination_city=$request->more_destination_city;
            $destination_details_more=[
                'more_destination_country'=>$more_destination_country,
                'more_destination_city'=>$more_destination_city,
            ];
            $destination_details_more=json_encode($destination_details_more);
            $hotel_name_markup=$request->hotel_name_markup;
            $room_type=$request->room_type;
            $without_markup_price=$request->without_markup_price;
            $markup_type=$request->markup_type;
            $markup=$request->markup;
            $markup_price=$request->markup_price;
            $gallery_images=array();
            if($files=$request->file('gallery_images')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    $file->move('public/uploads/package_imgs/',$name);
                    $gallery_images[]=$name;
                }
            }
            if(isset($gallery_images))
            {
              $gallery_images=json_encode($gallery_images);  
            }
            else
            {
              $gallery_images ='';  
            }
            if(isset($hotel_name_markup))
            {
                $arrLength = count($hotel_name_markup);
                for($i = 0; $i < $arrLength; $i++) {
                    $markup_details[]=(object)[
                        'hotel_name_markup'=>$hotel_name_markup[$i],
                        'room_type'=>$room_type[$i],
                        'without_markup_price'=>$without_markup_price[$i],
                        'markup_type'=>$markup_type[$i],
                        'markup'=>$markup[$i],
                        'markup_price'=>$markup_price[$i],
                    ];
                }
                $markup_details=json_encode($markup_details);
            }
            else
            {
               $markup_details = '';
               
            }
            
            $more_hotel_name_markup=$request->more_hotel_name_markup;
            $more_room_type=$request->more_room_type;
            $more_without_markup_price=$request->more_without_markup_price;
            $more_markup_type=$request->more_markup_type;
            $more_markup=$request->more_markup;
            $more_markup_price=$request->more_markup_price;
            
            if(isset($more_hotel_name_markup))
            {
                $arrLength = count($more_hotel_name_markup);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_markup_details[]=(object)[
                        'more_hotel_name_markup'=>$more_hotel_name_markup[$i],
                        'more_room_type'=>$more_room_type[$i],
                        'more_without_markup_price'=>$more_without_markup_price[$i],
                        'more_markup_type'=>$more_markup_type[$i],
                        'more_markup'=>$more_markup[$i],
                        'more_markup_price'=>$more_markup_price[$i],
                    ];
                }
                $more_markup_details=json_encode($more_markup_details);
            }
            else
            {
               $more_markup_details = ''; 
            }
            
            $departure_airport_code=$request->departure_airport_code;
            $departure_flight_number=$request->departure_flight_number;
            $departure_date=$request->departure_date;
            $departure_time=$request->departure_time;
            $arrival_airport_code=$request->arrival_airport_code;
            $arrival_flight_number=$request->arrival_flight_number;
            $arrival_date=$request->arrival_date;
            $arrival_time=$request->arrival_time;
            $flight_type=$request->flight_type;
            $flights_per_person_price=$request->flights_per_person_price;
            $connected_flights_duration_details=$request->connected_flights_duration_details;
            $terms_and_conditions=$request->terms_and_conditions;
            
            if($request->hasFile('flights_image')){
                $file = $request->file('flights_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_flights_image = $filename;
            }
            else{
                $tour_flights_image = '';
            }
            $flights_image=$tour_flights_image;
            $flights_details=[
                'departure_airport_code'=>$departure_airport_code,
                'departure_flight_number'=>$departure_flight_number,
                'departure_date'=>$departure_date,
                'departure_time'=>$departure_time,
                
                'arrival_airport_code'=>$arrival_airport_code,
                'arrival_flight_number'=>$arrival_flight_number,
                'arrival_date'=>$arrival_date,
                'arrival_time'=>$arrival_time,
                
                'flight_type'=>$flight_type,
                'flights_per_person_price'=>$flights_per_person_price,
                'connected_flights_duration_details'=>$connected_flights_duration_details,
                'terms_and_conditions'=>$terms_and_conditions,
                'flights_image'=>$flights_image,
            ];
            $flights_details=json_encode($flights_details);
            $more_flights_departure=$request->more_flights_departure;
            $more_flights_arrival=$request->more_flights_arrival;
            $more_flights_airline=$request->more_flights_airline;
            $more_flights_per_person_price=$request->more_flights_per_person_price;
            
            if(isset($more_flights_departure))
            {
                $arrLength = count($more_flights_departure);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_flights_details[]=(object)[
                        'more_flights_departure'=>$more_flights_departure[$i],
                        'more_flights_arrival'=>$more_flights_arrival[$i],
                        'more_flights_airline'=>$more_flights_airline[$i],
                        'more_flights_per_person_price'=>$more_flights_per_person_price[$i],
                    ];
                }
                $more_flights_details=json_encode($more_flights_details);
            }
            else
            {
               $more_flights_details = ''; 
            }
    
            $acc_hotel_name=$request->acc_hotel_name;
            $acc_check_in=$request->acc_check_in;
            $acc_check_out=$request->acc_check_out;
            $acc_type=$request->acc_type;
            $acc_qty=$request->acc_qty;
            $acc_pax=$request->acc_pax;
            $acc_price=$request->acc_price;
            $acc_currency=$request->acc_currency;
            $acc_total_amount=$request->acc_total_amount;
            $acc_no_of_nightst=$request->acc_no_of_nightst;
            
            if($request->hasFile('accomodation_image')){
                $file = $request->file('accomodation_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_accomodation_image = $filename;
            }
            else{
                $tour_accomodation_image = '';
            }
            
            $accomodation_image=$tour_accomodation_image;
    
            if(isset($acc_hotel_name))
            {   
                $arrLength = count($acc_hotel_name);
                for($i = 0; $i < $arrLength; $i++) {
                    $accomodation_details[]=(object)[
                        'acc_hotel_name'=>$acc_hotel_name[$i],
                        'acc_check_in'=>$acc_check_in[$i],
                        'acc_check_out'=>$acc_check_out[$i],
                        'acc_type'=>$acc_type[$i],
                         'acc_qty'=>$acc_qty[$i],
                        'acc_pax'=>$acc_pax[$i],
                        'acc_price'=>$acc_price[$i],
                        'acc_currency'=>$acc_currency[$i],
                        'acc_total_amount'=>$acc_total_amount[$i],
                        'acc_no_of_nightst'=>$acc_no_of_nightst[$i],
                        'accomodation_image'=>$accomodation_image[$i],
                    ];
                }
                $accomodation_details=json_encode($accomodation_details);
            }
            else
            {
               $accomodation_details = ''; 
            }
    
            $transportation_pick_up_location=$request->transportation_pick_up_location;
            $transportation_drop_off_location=$request->transportation_drop_off_location;
            $transportation_pick_up_date=$request->transportation_pick_up_date;
            $transportation_trip_type=$request->transportation_trip_type;
            $transportation_vehicle_type=$request->transportation_vehicle_type;
            $transportation_no_of_vehicle=$request->transportation_no_of_vehicle;
            $transportation_price_per_vehicle=$request->transportation_price_per_vehicle;
            $transportation_vehicle_total_price=$request->transportation_vehicle_total_price;
            $return_transportation_pick_up_location=$request->return_transportation_pick_up_location;
            $return_transportation_drop_off_location=$request->return_transportation_drop_off_location;
            $return_transportation_pick_up_date=$request->return_transportation_pick_up_date;
            $transportation_price_per_person=$request->transportation_price_per_person;
    
            if($request->hasFile('transportation_image')){
                $file = $request->file('transportation_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_transportation_image = $filename;
            }
            else{
                $tour_transportation_image = '';
            }
    
            $transportation_image=$tour_transportation_image;
            $transportation_details=[
                'transportation_pick_up_location'=>$transportation_pick_up_location,
                'transportation_drop_off_location'=>$transportation_drop_off_location,
                'transportation_pick_up_date'=>$transportation_pick_up_date,
                'transportation_trip_type'=>$transportation_trip_type,
                'transportation_vehicle_type'=>$transportation_vehicle_type,
                'transportation_no_of_vehicle'=>$transportation_no_of_vehicle,
                
                'transportation_price_per_vehicle'=>$transportation_price_per_vehicle,
                'transportation_vehicle_total_price'=>$transportation_vehicle_total_price,
                
                'return_transportation_pick_up_location'=>$return_transportation_pick_up_location,
                'return_transportation_drop_off_location'=>$return_transportation_drop_off_location,
                'return_transportation_pick_up_date'=>$return_transportation_pick_up_date,
                
                'transportation_price_per_person'=>$transportation_price_per_person,
                'transportation_image'=>$transportation_image,
            ];
            $transportation_details=json_encode($transportation_details);
            $more_transportation_pick_up_location=$request->more_transportation_pick_up_location;
            $more_transportation_drop_off_location=$request->more_transportation_drop_off_location;
            $more_transportation_pick_up_date=$request->more_transportation_pick_up_date;
    
            if(isset($more_transportation_pick_up_location))
            {
                $arrLength = count($more_transportation_pick_up_location);
                for($i = 0; $i < $arrLength; $i++) {
                    $transportation_details_more[]=(object)[
                        'more_transportation_pick_up_location'=>$more_transportation_pick_up_location[$i],
                        'more_transportation_drop_off_location'=>$more_transportation_drop_off_location[$i],
                        'more_transportation_pick_up_date'=>$more_transportation_pick_up_date[$i],
                    ];
                }
                $transportation_details_more=json_encode($transportation_details_more);
            }
            else
            {
               $transportation_details_more = ''; 
            }
    
            $start_date=$request->start_date;
            $end_date=$request->end_date;
            $time_duration=$request->time_duration;
            $whats_included=$request->whats_included;
            $whats_excluded=$request->whats_excluded;
            $tour_publish=$request->tour_publish;
            $tour_author=$request->tour_author;
            $tour_feature=$request->tour_feature;
            $defalut_state=$request->defalut_state;
            
            if($request->hasFile('tour_featured_image')){
                $file = $request->file('tour_featured_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_featured_image = $filename;
            }
            else{
                $tour_featured_image = '';
            }
            
            $tour_featured_image=$tour_featured_image;
            
            if($request->hasFile('tour_banner_image')){
                $file = $request->file('tour_banner_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_banner_image = $filename;
            }
            else{
                $tour_banner_image = '';
            }
            $tour_banner_image=$tour_banner_image;
            $Itinerary_title=$request->Itinerary_title;
            $Itinerary_city=$request->Itinerary_city;
            $Itinerary_content=$request->Itinerary_content;
    
            if($request->hasFile('Itinerary_image')){
                $file = $request->file('Itinerary_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $Itinerary_image = $filename;
            }
            else{
                $Itinerary_image = '';
            }
            
            $Itinerary_image=$Itinerary_image;
            $Itinerary_details=array([
                'Itinerary_title'=>$Itinerary_title,
                'Itinerary_city'=>$Itinerary_city,
                'Itinerary_content'=>$Itinerary_content,
                'Itinerary_image'=>$Itinerary_image,
                
            ]);
            $Itinerary_details=json_encode($Itinerary_details);
            $more_Itinerary_title=$request->more_Itinerary_title;
            $more_Itinerary_city=$request->more_Itinerary_city;
            $more_Itinerary_content=$request->more_Itinerary_content;
            $more_Itinerary_image=array();
            
            if($files=$request->file('more_Itinerary_image')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    $file->move('public/uploads/package_imgs/',$name);
                    $more_Itinerary_image[]=$name;
                }
            }
    
            $more_Itinerary_image=$more_Itinerary_image;
          
            if(isset($more_Itinerary_title))
            {  
                $arrLength = count($more_Itinerary_title);
                for($i = 0; $i < $arrLength; $i++) {
                    $more_itinery_details[]=(object)[
                        'more_Itinerary_title'=>$more_Itinerary_title[$i],
                        'more_Itinerary_city'=>$more_Itinerary_city[$i],
                        'more_Itinerary_content'=>$more_Itinerary_content[$i],
                        'more_Itinerary_image'=>$more_Itinerary_image[$i],
                    ];
                }
                $tour_itinerary_details_1=json_encode($more_itinery_details);  
            }
            else
            {
                $tour_itinerary_details_1= ''; 
            }
            
            $extra_price_name=$request->extra_price_name;
            $extra_price_price=$request->extra_price_price;
            $extra_price_type=$request->extra_price_type;
            $extra_price_person=$request->extra_price_person;
            $tour_extra_price=array([
                'extra_price_name'=>$extra_price_name,
                'extra_price_price'=>$extra_price_price,
                'extra_price_type'=>$extra_price_type,
                'extra_price_person'=>$extra_price_person,
            ]);
            $tour_extra_price=json_encode($tour_extra_price);
            $more_extra_price_title=$request->more_extra_price_title;
            $more_extra_price_price=$request->more_extra_price_price;
            $more_extra_price_type=$request->more_extra_price_type;
            $more_extra_price_person=$request->more_extra_price_person;
    
            if(isset($more_extra_price_title))
            {
                $arrLength1 = count($more_extra_price_title);
                for($i = 0; $i < $arrLength1; $i++) {
                    $more_extra_price_details[]=(object)[
                        'more_extra_price_title'=>$more_extra_price_title[$i],
                        'more_extra_price_price'=>$more_extra_price_price[$i],
                        'more_extra_price_type'=>$more_extra_price_type[$i],
                        'more_extra_price_person'=>$more_extra_price_person[$i],
                    ];
                }
                $tour_extra_price_1=json_encode($more_extra_price_details);
            }
            else
            {
               $tour_extra_price_1= ''; 
            }
    
            $faq_title=$request->faq_title;
            $faq_content=$request->faq_content;
            $tour_faq=array([
                'faq_title'=>json_encode($faq_title),
                'faq_content'=>json_encode($faq_content),
            ]);
            $tour_faq=json_encode($tour_faq);
            $more_faq_title=$request->more_faq_title;
            $more_faq_content=$request->more_faq_content;
        
            if(isset($more_faq_title))
            {
                $arrLength2 = count($more_faq_title);
                for($i = 0; $i < $arrLength2; $i++) {
                $more_faq_details[]=(object)[
                        'more_faq_title'=>$more_faq_title[$i],
                        'more_faq_content'=>$more_faq_content[$i],
                    ];
                }
                $tour_faq_1=json_encode($more_faq_details);
            }
            else
            {
               $tour_faq_1= ''; 
            }
            
            $visa_type=$request->visa_type;
            $visa_fee=$request->visa_fee;
            $visa_rules_regulations=$request->visa_rules_regulations;
            
            if($request->hasFile('visa_image')){
                $file = $request->file('visa_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/uploads/package_imgs/', $filename);
                $tour_visa_image = $filename;
            }
            else{
                $tour_visa_image = '';
            }
    
            $visa_image=$tour_visa_image;
            $quad_grand_total_amount=$request->quad_grand_total_amount;
            $triple_grand_total_amount=$request->triple_grand_total_amount;
            $double_grand_total_amount=$request->double_grand_total_amount;
            $currency_symbol=$request->currency_symbol;
            $quad_cost_price=$request->quad_cost_price;
            $triple_cost_price=$request->triple_cost_price;
            $double_cost_price=$request->double_cost_price;
            $all_markup_type=$request->all_markup_type;
            $all_markup_add=$request->all_markup_add;
            $payment_gateways=$request->payment_gateways;
            $payment_modes=$request->payment_modes;
    		$curl = curl_init();
    		$data = array(
    		    'id'=>$id,
    		    'token'=>$token,
    			'customer_id'=>$customer_id,
    			'title'=>$title,
    			'content'=>$content,
    			'tour_categories'=>$tour_categories,
    			'tour_attributes'=>$tour_attributes,
    			'start_date'=>$start_date,
    			'end_date'=>$end_date,
    			'time_duration'=>$time_duration,
    			'tour_location'=>$tour_location,
    			'whats_included'=>$whats_included,
    			'whats_excluded'=>$whats_excluded,
    			'currency_symbol'=>$currency_symbol,
    			'tour_publish'=>$tour_publish,
    			'tour_author'=>$tour_author,
    			'tour_feature'=>$tour_feature,
    			'defalut_state'=>$defalut_state,
    			'tour_featured_image'=>$tour_featured_image,
                'tour_banner_image'=>$tour_banner_image,
                'Itinerary_details'=>$Itinerary_details,
                'tour_itinerary_details_1'=>$tour_itinerary_details_1,
                'tour_extra_price'=>$tour_extra_price,
                'tour_extra_price_1'=>$tour_extra_price_1,
                'tour_faq'=>$tour_faq,
                'tour_faq_1'=>$tour_faq_1,
                'external_packages'=>$external_packages,
                'more_markup_details'=>$more_markup_details,
                'no_of_pax_days'=>$no_of_pax_days,
                'destination_details'=>$destination_details,
                'destination_details_more'=>$destination_details_more,
                'flights_details'=>$flights_details,
                'more_flights_details'=>$more_flights_details,
                'accomodation_details'=>$accomodation_details,
                'transportation_details'=>$transportation_details,
                'transportation_details_more'=>$transportation_details_more,
                'visa_fee'=>$visa_fee,
                'visa_type'=>$visa_type,
                'visa_image'=>$visa_image,
                'visa_rules_regulations'=>$visa_rules_regulations,
                'quad_grand_total_amount'=>$quad_grand_total_amount,
                'triple_grand_total_amount'=>$triple_grand_total_amount,
                'double_grand_total_amount'=>$double_grand_total_amount,
                'quad_cost_price'=>$quad_cost_price,
                'triple_cost_price'=>$triple_cost_price,
                'double_cost_price'=>$double_cost_price,
                'all_markup_type'=>$all_markup_type,
                'all_markup_add'=>$all_markup_add,
                'gallery_images'=>$gallery_images,
                'payment_gateways'=>$payment_gateways,
                'payment_modes'=>$payment_modes,
            );
            // dd($data);
        // End Data

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_edit_activities/{$id}',
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
		$attributes = json_decode($response);
  	    return redirect()->back()->with('message','Updated Succesfully');
    }
    // 3rd
    public function delete_activities($id)
    {
    $token=config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'id'=>$id);
    
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_activities/{$id}',
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
			$enable = json_decode($response);
      
      return redirect()->back();
  }

  public function enable_tour($id)
  {
    $token=config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'id'=>$id);
    
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/enable_tour/{$id}',
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
			$enable = json_decode($response);
      
      return redirect()->back();
   
  }

  public function disable_tour($id)
  {

  
    $token=config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'id'=>$id);
   
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/disable_tour/{$id}',
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
			$disable_tour = json_decode($response);
      return redirect()->back();
      // print_r($disable_tour);die();
  }


  public function get_tour_list()
  {
    $tours=Tour::all();
    return response()->json($tours);
  }

  public function add_tour(Request $request)
  {

    $tour = new Tour();
      $tour->title=$request->title;
      $tour->content=$request->content;
      $categories=$request->categories;
      $categories=json_encode($categories);

      $tour->categories=$categories;

      $attributes=$request->attributes;

      // print_r($attributes);die();
      $attributes=json_encode($attributes);

      $tour->attributes=$attributes;


      $tour->start_date=$request->start_date;
      $tour->end_date=$request->end_date;
      $tour->time_duration=$request->time_duration;
      $tour->tour_min_people=$request->tour_min_people;
      $tour->tour_max_people=$request->tour_max_people;
      $tour->tour_location=$request->tour_location;
      $tour->tour_real_address=$request->tour_real_address;
      $tour->tour_pricing=$request->tour_pricing;
      $tour->tour_sale_price=$request->tour_sale_price;
      $tour->tour_publish=$request->tour_publish;
      $tour->tour_author=$request->tour_author;
      $tour->tour_feature=$request->tour_feature;
      $tour->defalut_state=$request->defalut_state;


      if($request->hasFile('tour_featured_image')){

        $file = $request->file('tour_featured_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_featured_image = $filename;
    }
    else{
        $tour_featured_image = '';
    }


      $tour->tour_featured_image=$tour_featured_image;
      if($request->hasFile('tour_banner_image')){

        $file = $request->file('tour_banner_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_banner_image = $filename;
    }
    else{
        $tour_banner_image = '';
    }
      $tour->tour_banner_image=$tour_banner_image;



      $Itinerary_title=$request->Itinerary_title;
      $Itinerary_city=$request->Itinerary_city;
      $Itinerary_content=$request->Itinerary_content;

      if($request->hasFile('Itinerary_image')){

        $file = $request->file('Itinerary_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $Itinerary_image = $filename;
    }
    else{
        $Itinerary_image = '';
    }
      $Itinerary_image=$Itinerary_image;
      $Itinerary_details=array([
                        'Itinerary_title'=>$Itinerary_title,
                          'Itinerary_city'=>$Itinerary_city,
                          'Itinerary_content'=>$Itinerary_content,
                          'Itinerary_image'=>$Itinerary_image,

                          ]);

      $Itinerary_details=json_encode($Itinerary_details);

      $tour->Itinerary_details=$Itinerary_details;






      $more_Itinerary_title=$request->more_Itinerary_title;
      $more_Itinerary_city=$request->more_Itinerary_city;
      $more_Itinerary_content=$request->more_Itinerary_content;

      if($request->hasFile('more_Itinerary_image')){

        $file = $request->file('more_Itinerary_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $more_Itinerary_image = $filename;
    }
    else{
        $more_Itinerary_image = '';
    }
      $more_Itinerary_image=$more_Itinerary_image;
      $tour_itinerary_details_1=array([
                        'more_Itinerary_title'=>$more_Itinerary_title,
                          'more_Itinerary_city'=>$more_Itinerary_city,
                          'more_Itinerary_content'=>$more_Itinerary_content,
                          'more_Itinerary_image'=>$more_Itinerary_image,

                          ]);

      $tour_itinerary_details_1=json_encode($tour_itinerary_details_1);

      $tour->tour_itinerary_details_1=$tour_itinerary_details_1;










      $extra_price_name=$request->extra_price_name;
      $extra_price_price=$request->extra_price_price;
      $extra_price_type=$request->extra_price_type;
      $extra_price_person=$request->extra_price_person;
      $tour_extra_price=array([
        'extra_price_name'=>$extra_price_name,
          'extra_price_price'=>$extra_price_price,
          'extra_price_type'=>$extra_price_type,
          'extra_price_person'=>$extra_price_person,

          ]);

$tour_extra_price=json_encode($tour_extra_price);

$tour->tour_extra_price=$tour_extra_price;

$faq_title=$request->faq_title;
      $faq_content=$request->faq_content;
      $tour_extra_price=array([
        'faq_title'=>$faq_title,
          'faq_content'=>$faq_content,
          

          ]);

$tour_extra_price=json_encode($tour_extra_price);
$tour->tour_faq=$tour_extra_price;






$more_extra_price_title=$request->more_extra_price_title;
      $more_extra_price_price=$request->more_extra_price_price;
      $more_extra_price_type=$request->more_extra_price_type;
      $more_extra_price_person=$request->more_extra_price_person;
      $tour_extra_price_1=array([
        'more_extra_price_title'=>$more_extra_price_title,
          'more_extra_price_price'=>$more_extra_price_price,
          'more_extra_price_type'=>$more_extra_price_type,
          'more_extra_price_person'=>$more_extra_price_person,

          ]);

$tour_extra_price_1=json_encode($tour_extra_price_1);

$tour->tour_extra_price_1=$tour_extra_price_1;

$faq_title=$request->faq_title;
      $tour_faq=$request->faq_content;
      $tour_extra_price=array([
        'faq_title'=>$faq_title,
          'faq_content'=>$faq_content,
          

          ]);

$tour_faq=json_encode($tour_faq);
$tour->tour_extra_price=$tour_faq;





$more_faq_title=$request->more_faq_title;
      $more_faq_content=$request->more_faq_content;
      $tour_faq_1=array([
        'more_faq_content'=>$more_faq_content,
          'more_faq_content'=>$more_faq_content,
          

          ]);

$tour_faq_1=json_encode($tour_faq_1);
$tour->tour_faq_1=$tour_faq_1;







      $tour->save();
      
      return response()->json($tour);
    
  }
  public function edit_tours(Request $request,$id)
  {
    $tour=Tour::find($id);
    if($tour)
    {
      $tour->title=$request->title;
      $tour->content=$request->content;
      $categories=$request->categories;
      $categories=json_encode($categories);

      $tour->categories=$categories;

      $attributes=$request->attributes;

      // print_r($attributes);die();
      $attributes=json_encode($attributes);

      $tour->attributes=$attributes;


      $tour->start_date=$request->start_date;
      $tour->end_date=$request->end_date;
      $tour->time_duration=$request->time_duration;
      $tour->tour_min_people=$request->tour_min_people;
      $tour->tour_max_people=$request->tour_max_people;
      $tour->tour_location=$request->tour_location;
      $tour->tour_real_address=$request->tour_real_address;
      $tour->tour_pricing=$request->tour_pricing;
      $tour->tour_sale_price=$request->tour_sale_price;
      $tour->tour_publish=$request->tour_publish;
      $tour->tour_author=$request->tour_author;
      $tour->tour_feature=$request->tour_feature;
      $tour->defalut_state=$request->defalut_state;


      if($request->hasFile('tour_featured_image')){

        $file = $request->file('tour_featured_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_featured_image = $filename;
    }
    else{
        $tour_featured_image = '';
    }


      $tour->tour_featured_image=$tour_featured_image;
      if($request->hasFile('tour_banner_image')){

        $file = $request->file('tour_banner_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_banner_image = $filename;
    }
    else{
        $tour_banner_image = '';
    }
      $tour->tour_banner_image=$tour_banner_image;



      $Itinerary_title=$request->Itinerary_title;
      $Itinerary_city=$request->Itinerary_city;
      $Itinerary_content=$request->Itinerary_content;

      if($request->hasFile('Itinerary_image')){

        $file = $request->file('Itinerary_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $Itinerary_image = $filename;
    }
    else{
        $Itinerary_image = '';
    }
      $Itinerary_image=$Itinerary_image;
      $Itinerary_details=array([
                        'Itinerary_title'=>$Itinerary_title,
                          'Itinerary_city'=>$Itinerary_city,
                          'Itinerary_content'=>$Itinerary_content,
                          'Itinerary_image'=>$Itinerary_image,

                          ]);

      $Itinerary_details=json_encode($Itinerary_details);

      $tour->Itinerary_details=$Itinerary_details;






      $more_Itinerary_title=$request->more_Itinerary_title;
      $more_Itinerary_city=$request->more_Itinerary_city;
      $more_Itinerary_content=$request->more_Itinerary_content;

      if($request->hasFile('more_Itinerary_image')){

        $file = $request->file('more_Itinerary_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $more_Itinerary_image = $filename;
    }
    else{
        $more_Itinerary_image = '';
    }
      $more_Itinerary_image=$more_Itinerary_image;
      $tour_itinerary_details_1=array([
                        'more_Itinerary_title'=>$more_Itinerary_title,
                          'more_Itinerary_city'=>$more_Itinerary_city,
                          'more_Itinerary_content'=>$more_Itinerary_content,
                          'more_Itinerary_image'=>$more_Itinerary_image,

                          ]);

      $tour_itinerary_details_1=json_encode($tour_itinerary_details_1);

      $tour->tour_itinerary_details_1=$tour_itinerary_details_1;










      $extra_price_name=$request->extra_price_name;
      $extra_price_price=$request->extra_price_price;
      $extra_price_type=$request->extra_price_type;
      $extra_price_person=$request->extra_price_person;
      $tour_extra_price=array([
        'extra_price_name'=>$extra_price_name,
          'extra_price_price'=>$extra_price_price,
          'extra_price_type'=>$extra_price_type,
          'extra_price_person'=>$extra_price_person,

          ]);

$tour_extra_price=json_encode($tour_extra_price);

$tour->tour_extra_price=$tour_extra_price;

$faq_title=$request->faq_title;
      $faq_content=$request->faq_content;
      $tour_extra_price=array([
        'faq_title'=>$faq_title,
          'faq_content'=>$faq_content,
          

          ]);

$tour_extra_price=json_encode($tour_extra_price);
$tour->tour_faq=$tour_extra_price;






$more_extra_price_title=$request->more_extra_price_title;
      $more_extra_price_price=$request->more_extra_price_price;
      $more_extra_price_type=$request->more_extra_price_type;
      $more_extra_price_person=$request->more_extra_price_person;
      $tour_extra_price_1=array([
        'more_extra_price_title'=>$more_extra_price_title,
          'more_extra_price_price'=>$more_extra_price_price,
          'more_extra_price_type'=>$more_extra_price_type,
          'more_extra_price_person'=>$more_extra_price_person,

          ]);

$tour_extra_price_1=json_encode($tour_extra_price_1);

$tour->tour_extra_price_1=$tour_extra_price_1;

$faq_title=$request->faq_title;
      $tour_faq=$request->faq_content;
      $tour_extra_price=array([
        'faq_title'=>$faq_title,
          'faq_content'=>$faq_content,
          

          ]);

$tour_faq=json_encode($tour_faq);
$tour->tour_extra_price=$tour_faq;





$more_faq_title=$request->more_faq_title;
      $more_faq_content=$request->more_faq_content;
      $tour_faq_1=array([
        'more_faq_content'=>$more_faq_content,
          'more_faq_content'=>$more_faq_content,
          

          ]);

$tour_faq_1=json_encode($tour_faq_1);
$tour->tour_faq_1=$tour_faq_1;







      $tour->update();

      return response()->json(['Tour'=>$tour,'Success'=>'Tour Updated Successful!']);
     
    }
    else{
      return response()->json(['Tour'=>$tour,'Error'=>'Tour Not Updated Successful!']);
    }

  }





// activities controller code started

  public function add_categories_activities()
  {

    
    return view('template/frontend/userdashboard/pages/activities_packages/categories/add_categories');  
  }

  public function submit_categories_activities(Request $request)
  {
    
    // print_r($request->all());
    $token=config('token');
  if($request->hasFile('image')){

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $image = $filename;
    }
    else{
        $image = '';
    }
    
    $slug=$request->slug;
    $title=$request->title;
    $placement=$request->placement;
    $description=$request->description;
    $customer_id=Session::get('id');
    
    
    
			$curl = curl_init();
 
			$data = array('token'=>$token,'title'=>$title,'customer_id'=>$customer_id,'slug'=>$slug,'description'=>$description,'image'=>$image,'placement'=>$placement);
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_categories_activites',
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
// 	echo $response;die();
			curl_close($curl);
			$response_data = json_decode($response);
	
	// print_r($response_data);die();
	if(isset($request->ajax)){
	    return true;
	}else{
	    return redirect()->back()->with('message','Categories Created Successful!');
	}
	
    
  }

  public function edit_categories_activities($id)
  {
    $token=config('token');
   
			$curl = curl_init();
 
			$data = array('token'=>$token,'id'=>$id);
	// print_r($data);die();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_categories_activites_api/{$id}',
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
// 	echo $response;die();
			curl_close($curl);
			$response_data = json_decode($response);
      $categories=$response_data->categories;
	// print_r($categories);die();

    
    return view('template/frontend/userdashboard/pages/activities_packages/categories/edit_categories',compact('categories'));  
  }
  public function submit_edit_categories_activities(Request $request,$id)
  {
    $token=config('token');
    if($request->hasFile('image')){

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $image = $filename;
    }
    else{
        $image = '';
    }
    
    $slug=$request->slug;
    $title=$request->title;
    $description=$request->description;
			$curl = curl_init();
 
			$data = array(
        'token'=>$token,
        'title'=>$title,
        'slug'=>$slug,
        'image'=>$image,
        'description'=>$description,
        'id'=>$id
      );
	// print_r($data);die();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_edit_categories_activites_api/{$id}',
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
    //  echo $response;die();
			curl_close($curl);
			$response_data = json_decode($response);
      
      return redirect('super_admin/view_categories_activities');
    
  }
  public function delete_categories_activities(Request $request,$id)
  {
    $token=config('token');
   
			$curl = curl_init();
 
			$data = array(
        'token'=>$token,
       
        'id'=>$id
      );
	// print_r($data);die();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_categories_activites_api/{$id}',
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
     //echo $response;
			curl_close($curl);
			$response_data = json_decode($response);
      
      return redirect()->back();
    
    
  }

  public function view_categories_activities(Request $request)
  {

    $token=config('token');
   
    $customer_id=Session::get('id');

    // print_r($customer_id);die();
			$curl = curl_init();
 
			$data = array('token'=>$token,'customer_id'=>$customer_id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_categories_activites',
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
// 	echo $response();die();
			curl_close($curl);
			$categories = json_decode($response);
      $categories=$categories->categories;
	// print_r($categories->categories);die();
	
    return view('template/frontend/userdashboard/pages/activities_packages/categories/view_categories',compact('categories'));  
  }
  
  public function get_categories_activities(Request $request)
  {

    $token=config('token');
   
    $customer_id=Session::get('id');

    // print_r($customer_id);die();
			$curl = curl_init();
 
			$data = array('token'=>$token,'customer_id'=>$customer_id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_categories_activites',
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
// 	echo $response();die();
			curl_close($curl);
			$categories = json_decode($response);
      $categories=$categories->categories;
      foreach($categories as $cat_res){
          ?>
          <option value="<?php echo $cat_res->id ?>"><?php echo $cat_res->title ?></option>
          <?php
      }
	// print_r($categories->categories);die();
	
    // return view('template/frontend/userdashboard/pages/activities_packages/categories/view_categories',compact('categories'));  
  }
  
    // Hotel Names  
        public function view_Hotel_Names(Request $request)
        {
            $token=config('token');
            $customer_id=Session::get('id');
    		$curl = curl_init();
    		$data = array('token'=>$token,'customer_id'=>$customer_id);
    		curl_setopt_array($curl, array(
    			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_Hotel_Names',
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
    		$other_Hotel_Name = json_decode($response);
            $other_Hotel_Name = $other_Hotel_Name->other_Hotel_Name;
    	
            return view('template/frontend/userdashboard/pages/activities_packages/hotel_Name/other_Hotel_Name',compact('other_Hotel_Name'));  
        }
    
        public function edit_Hotel_Names($id)
        {
            $token=config('token');
            $curl = curl_init();
    		$data = array('token'=>$token,'id'=>$id);
    		curl_setopt_array($curl, array(
    			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_Hotel_Names/{$id}',
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
    		$response_data = json_decode($response);
            $edit_other_Hotel_Name=$response_data->edit_other_Hotel_Name;
            return view('template/frontend/userdashboard/pages/activities_packages/hotel_Name/edit_other_Hotel_Name',compact('edit_other_Hotel_Name'));  
        }
    
        public function submit_edit_Hotel_Names(Request $request,$id)
        {
            $token=config('token');
            $other_Hotel_Name=$request->other_Hotel_Name;
    		$curl = curl_init();
    		$data = array(
                'token'=>$token,
                'other_Hotel_Name'=>$other_Hotel_Name,
                'id'=>$id
            );
    		curl_setopt_array($curl, array(
    			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_edit_Hotel_Names/{$id}',
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
    		$response_data = json_decode($response);
            return redirect('super_admin/view_Hotel_Names');
    
        }
    
        public function delete_Hotel_Names(Request $request,$id)
        {
            $token=config('token');
            $curl = curl_init();
        	$data = array(
                'token'=>$token,
                'id'=>$id
            );
            // dd($data);
        	curl_setopt_array($curl, array(
        		CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_Hotel_Names/{$id}',
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
    		$response_data = json_decode($response);
            return redirect()->back();
        }
    // End Hotel Names
    
    // Locations
        // Pickup Locations
            public function view_pickup_locations(Request $request)
            {
                $token=config('token');
                $customer_id=Session::get('id');
        		$curl = curl_init();
        		$data = array('token'=>$token,'customer_id'=>$customer_id);
        		curl_setopt_array($curl, array(
        			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_pickup_locations',
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
        		$other_pickup_location = json_decode($response);
                $other_pickup_location = $other_pickup_location->other_pickup_location;
        	
                return view('template/frontend/userdashboard/pages/activities_packages/P_D_Locations/other_pickup_location',compact('other_pickup_location'));  
            }
            
            public function edit_pickup_locations($id)
            {
                $token=config('token');
                $curl = curl_init();
        		$data = array('token'=>$token,'id'=>$id);
        		curl_setopt_array($curl, array(
        			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_pickup_locations/{$id}',
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
        		$response_data = json_decode($response);
                $edit_other_pickup_location=$response_data->edit_other_pickup_location;
                return view('template/frontend/userdashboard/pages/activities_packages/P_D_Locations/edit_other_pickup_location',compact('edit_other_pickup_location'));  
            }
    
            public function submit_edit_pickup_locations(Request $request,$id)
            {
                $token=config('token');
                $pickup_location=$request->pickup_location;
        		$curl = curl_init();
        		$data = array(
                    'token'=>$token,
                    'pickup_location'=>$pickup_location,
                    'id'=>$id
                );
        		curl_setopt_array($curl, array(
        			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_edit_pickup_locations/{$id}',
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
        		$response_data = json_decode($response);
                return redirect('super_admin/view_pickup_locations');
    
            }
    
            public function delete_pickup_locations(Request $request,$id)
            {
                $token=config('token');
                $curl = curl_init();
            	$data = array(
                    'token'=>$token,
                    'id'=>$id
                );
            	curl_setopt_array($curl, array(
            		CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_pickup_locations/{$id}',
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
        		$response_data = json_decode($response);
                return redirect()->back();
            }
        // End Pickup Locations
            public function view_dropof_locations(Request $request)
            {
                $token=config('token');
                $customer_id=Session::get('id');
        		$curl = curl_init();
        		$data = array('token'=>$token,'customer_id'=>$customer_id);
        		curl_setopt_array($curl, array(
        			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_dropof_locations',
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
        		$other_dropof_location = json_decode($response);
                $other_dropof_location = $other_dropof_location->other_dropof_location;
        	
                return view('template/frontend/userdashboard/pages/activities_packages/P_D_Locations/other_dropof_location',compact('other_dropof_location'));  
            }
            
            public function edit_dropof_locations($id)
            {
                $token=config('token');
                $curl = curl_init();
        		$data = array('token'=>$token,'id'=>$id);
        		curl_setopt_array($curl, array(
        			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_dropof_locations/{$id}',
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
        		$response_data = json_decode($response);
                $edit_other_dropof_location=$response_data->edit_other_dropof_location;
                return view('template/frontend/userdashboard/pages/activities_packages/P_D_Locations/edit_other_dropof_location',compact('edit_other_dropof_location'));  
            }
    
            public function submit_edit_dropof_locations(Request $request,$id)
            {
                $token=config('token');
                $dropof_location=$request->dropof_location;
        		$curl = curl_init();
        		$data = array(
                    'token'=>$token,
                    'dropof_location'=>$dropof_location,
                    'id'=>$id
                );
        		curl_setopt_array($curl, array(
        			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_edit_dropof_locations/{$id}',
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
        		$response_data = json_decode($response);
                return redirect('super_admin/view_dropof_locations');
    
            }
    
            public function delete_dropof_locations(Request $request,$id)
            {
                $token=config('token');
                $curl = curl_init();
            	$data = array(
                    'token'=>$token,
                    'id'=>$id
                );
            	curl_setopt_array($curl, array(
            		CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_dropof_locations/{$id}',
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
        		$response_data = json_decode($response);
                return redirect()->back();
            }
        // Dropof Locations
        // End Dropof Locations
    // End Locations
  
  public function add_attributes_activities(Request $request)
  {


    return view('template/frontend/userdashboard/pages/activities_packages/attributes/add_attributes');  
  }
  public function submit_attributes_activities(Request $request)
  {

    $token=config('token');
    $title=$request->title;
    $customer_id=Session::get('id');
			$curl = curl_init();
 
			$data = array('token'=>$token,'title'=>$title,'customer_id'=>$customer_id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_attributes_activites',
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
			$response_data = json_decode($response);
	
	// print_r($response_data);die();

    if(isset($request->ajax)){
        return true;
    }else{
        return redirect()->back()->with('message','Attributes Created Successful!');
    }
    
    
  }

  public function edit_attributes_activities($id)
  {
    $token=config('token');
   
			$curl = curl_init();
 
			$data = array('token'=>$token,'id'=>$id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/edit_attributes_activites_api/{$id}',
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
			$attributes = json_decode($response);
      // print_r($attributes);die();
	$attributes=$attributes->attributes;
	

    
    return view('template/frontend/userdashboard/pages/activities_packages/attributes/edit_attributes',compact('attributes'));  
  }
  public function submit_edit_attributes_activities(Request $request,$id)
  {
    $token=config('token');
    $title=$request->title;
    $curl = curl_init();

    $data = array('token'=>$token,'id'=>$id,'title'=>$title);
// print_r($data);die();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://admin.synchronousdigital.com/api/submit_edit_attributes_activites/{$id}',
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
    $attributes = json_decode($response);
    // print_r($attributes);die();
    return redirect()->back();
    
    
  }
  public function delete_attributes_activities($id)
  {
    $token=config('token');
  
    $curl = curl_init();

    $data = array('token'=>$token,'id'=>$id);
// print_r($data);die();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://admin.synchronousdigital.com/api/delete_attributes_activites_api/{$id}',
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
    $attributes = json_decode($response);
    // print_r($attributes);die();
    return redirect()->back();
    
    
  }
  public function view_attributes_activities(Request $request)
  {

    $token=config('token');
    $title=$request->title;
    $customer_id=Session::get('id');
			$curl = curl_init();
 
			$data = array('token'=>$token,'customer_id'=>$customer_id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/view_attributes_activites',
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
			$attributes = json_decode($response);
      	// print_r($attributes);die();
      $attributes=$attributes->attributes;


    return view('template/frontend/userdashboard/pages/activities_packages/attributes/view_attributes',compact('attributes'));  
  }



    public function create_activities(Request $request)
    {
        $token=config('token');
        $title=$request->title;
        $customer_id=Session::get('id');
		$curl = curl_init();

		$data = array('token'=>$token,'customer_id'=>$customer_id);
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://admin.synchronousdigital.com/api/create_activites',
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
 
   	    $customer=$data->customer;
		$attributes=$data->attributes;
		$categories=$data->categories;
		$all_countries=$data->all_countries;
		$all_countries_currency=$data->all_countries_currency;
		$bir_airports=$data->bir_airports;
		$bir_airports1=$data->bir_airports;
        
        $bir_airports_A=$data->bir_airports;
    	$bir_airports1_A=$data->bir_airports;
    	$payment_gateways=$data->payment_gateways;
    	$payment_modes=$data->payment_modes;
    	
    	$currency_Symbol = $data->currency_Symbol;
    	$currency_Symbol1 = $data->currency_Symbol;
    	$currency_Symbol2 = $data->currency_Symbol;
    	$currency_Symbol3 = $data->currency_Symbol;
    	$currency_Symbol4 = $data->currency_Symbol;
    	$currency_Symbol5 = $data->currency_Symbol;
    	$currency_Symbol6 = $data->currency_Symbol;
    	$currency_Symbol7 = $data->currency_Symbol;
    	$currency_Symbol8 = $data->currency_Symbol;
    	$currency_Symbol9 = $data->currency_Symbol;
		$currency_Symbol10 = $data->currency_Symbol;
		$currency_Symbol11 = $data->currency_Symbol;
		$currency_Symbol12 = $data->currency_Symbol;
		$currency_Symbol13 = $data->currency_Symbol;
		$currency_Symbol14 = $data->currency_Symbol;
		
		$currency_Symbol15 = $data->currency_Symbol;
		$currency_Symbol16 = $data->currency_Symbol;
		$currency_Symbol17 = $data->currency_Symbol;
		$currency_Symbol18 = $data->currency_Symbol;
		$currency_Symbol19 = $data->currency_Symbol;
		$currency_Symbol20 = $data->currency_Symbol;
		
		$currency_Symbol21 = $data->currency_Symbol;
		$currency_Symbol22 = $data->currency_Symbol;
		$currency_Symbol23 = $data->currency_Symbol;
		$currency_Symbol24 = $data->currency_Symbol;
		
		$currency_Symbol25 = $data->currency_Symbol;
		$currency_Symbol26 = $data->currency_Symbol;
    	
        return view('template/frontend/userdashboard/pages/activities_packages/add_activities',compact('currency_Symbol25','currency_Symbol26','currency_Symbol21','currency_Symbol22','currency_Symbol23','currency_Symbol24','currency_Symbol','currency_Symbol1','currency_Symbol2','currency_Symbol3','currency_Symbol4','currency_Symbol5','currency_Symbol6','currency_Symbol7','currency_Symbol8','currency_Symbol9','currency_Symbol10','currency_Symbol11','currency_Symbol12','currency_Symbol13','currency_Symbol14','currency_Symbol15','currency_Symbol16','currency_Symbol17','currency_Symbol18','currency_Symbol19','currency_Symbol20','currency_Symbol21','bir_airports_A','bir_airports1_A','customer','attributes','categories','all_countries','all_countries_currency','bir_airports','bir_airports1','payment_gateways','payment_modes'));
         
        // return view('template/frontend/userdashboard/pages/activities_packages/add_activities',compact('customer','attributes','categories','all_countries','all_countries_currency','bir_airports','bir_airports1'));  
    }



  public function create_activities1(Request $request)
  {
$token=config('token');
    $title=$request->title;
    $customer_id=Session::get('id');
			$curl = curl_init();
 
			$data = array('token'=>$token,'customer_id'=>$customer_id);
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/create_activites',
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
    //   	print_r($data);die();
     
   		$customer=$data->customer;
			$attributes=$data->attributes;
			$categories=$data->categories;
			$all_countries=$data->all_countries;
				$all_countries_currency=$data->all_countries_currency;
				$bir_airports=$data->bir_airports;
				$bir_airports1=$data->bir_airports;
    //   	print_r($data);die();
     
   
    return view('template/frontend/userdashboard/pages/tour/add_tour1',compact('customer','attributes','categories','all_countries','all_countries_currency','bir_airports','bir_airports1'));  
  }


  public function submit_activities(Request $request)
  {
    $token=config('token');
    $title=$request->title;
    $no_of_pax_days=$request->no_of_pax_days;
    $external_packages=$request->external_packages;
     $external_packages=json_encode($external_packages);
     
    $customer_id=Session::get('id');
    $content=$request->content;
    $tour_categories=$request->categories;
  
      $tour_categories=json_encode($tour_categories);

    $tour_attributes=$request->tour_attributes;
    $tour_attributes=json_encode($tour_attributes);


$destination_country=$request->destination_country;

$destination_city=$request->destination_city;



 $tour_location=$request->tour_location_city;
//  print_r($tour_location);die();
 
 $destination_details=[
     'destination_country'=>$destination_country,
     'destination_city'=>$destination_city,
     ];
     
     $destination_details=json_encode($destination_details);
     
     
 
 

$more_destination_country=$request->more_destination_country;

$more_destination_city=$request->more_destination_city;



 $destination_details_more=[
     'more_destination_country'=>$more_destination_country,
     'more_destination_city'=>$more_destination_city,
     ];

   $destination_details_more=json_encode($destination_details_more);
   
  
  
  
  $hotel_name_markup=$request->hotel_name_markup;
$room_type=$request->room_type;
$without_markup_price=$request->without_markup_price;
$markup_type=$request->markup_type;

$markup=$request->markup;
$markup_price=$request->markup_price;
 
 
 

 if(isset($hotel_name_markup))
{
   $arrLength = count($hotel_name_markup);
  
      for($i = 0; $i < $arrLength; $i++) {

$markup_details[]=(object)[
    
    'hotel_name_markup'=>$hotel_name_markup[$i],
    'room_type'=>$room_type[$i],
    'without_markup_price'=>$without_markup_price[$i],
    'markup_type'=>$markup_type[$i],
    'markup'=>$markup[$i],
    'markup_price'=>$markup_price[$i],
    ];

}

 $markup_details=json_encode($markup_details);
 
}
else
{
   $markup_details = '';
   
}
  
  
  
 $more_hotel_name_markup=$request->more_hotel_name_markup;
$more_room_type=$request->more_room_type;
$more_without_markup_price=$request->more_without_markup_price;
$more_markup_type=$request->more_markup_type;

$more_markup=$request->more_markup;
$more_markup_price=$request->more_markup_price;

if(isset($more_hotel_name_markup))
{
   $arrLength = count($more_hotel_name_markup);
      for($i = 0; $i < $arrLength; $i++) {

$more_markup_details[]=(object)[
    
    'more_hotel_name_markup'=>$more_hotel_name_markup[$i],
    'more_room_type'=>$more_room_type[$i],
    'more_without_markup_price'=>$more_without_markup_price[$i],
    'more_markup_type'=>$more_markup_type[$i],
    'more_markup'=>$more_markup[$i],
    'more_markup_price'=>$more_markup_price[$i],
    ];

}

 $more_markup_details=json_encode($more_markup_details);
}
else
{
   $more_markup_details = ''; 
}



$departure_airport_code=$request->departure_airport_code;
$departure_flight_number=$request->departure_flight_number;
$departure_date=$request->departure_date;
$departure_time=$request->departure_time;

$arrival_airport_code=$request->arrival_airport_code;
$arrival_flight_number=$request->arrival_flight_number;
$arrival_date=$request->arrival_date;
$arrival_time=$request->arrival_time;

$flight_type=$request->flight_type;
$flights_per_person_price=$request->flights_per_person_price;
$connected_flights_duration_details=$request->connected_flights_duration_details;
$terms_and_conditions=$request->terms_and_conditions;

if($request->hasFile('flights_image')){

        $file = $request->file('flights_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_flights_image = $filename;
    }
    else{
        $tour_flights_image = '';
    }


$flights_image=$tour_flights_image;



$flights_details=[
   'departure_airport_code'=>$departure_airport_code,
'departure_flight_number'=>$departure_flight_number,
'departure_date'=>$departure_date,
'departure_time'=>$departure_time,

'arrival_airport_code'=>$arrival_airport_code,
'arrival_flight_number'=>$arrival_flight_number,
'arrival_date'=>$arrival_date,
'arrival_time'=>$arrival_time,

'flight_type'=>$flight_type,
'flights_per_person_price'=>$flights_per_person_price,
'connected_flights_duration_details'=>$connected_flights_duration_details,
'terms_and_conditions'=>$terms_and_conditions,
'flights_image'=>$flights_image,
    ];

 $flights_details=json_encode($flights_details);


$more_flights_departure=$request->more_flights_departure;
$more_flights_arrival=$request->more_flights_arrival;
$more_flights_airline=$request->more_flights_airline;
$more_flights_per_person_price=$request->more_flights_per_person_price;


// print_r($more_flights_departure);die();

if(isset($more_flights_departure))
{
   $arrLength = count($more_flights_departure);
      for($i = 0; $i < $arrLength; $i++) {

$more_flights_details[]=(object)[
    
    'more_flights_departure'=>$more_flights_departure[$i],
    'more_flights_arrival'=>$more_flights_arrival[$i],
    'more_flights_airline'=>$more_flights_airline[$i],
    'more_flights_per_person_price'=>$more_flights_per_person_price[$i],
    ];

}

 $more_flights_details=json_encode($more_flights_details);
}
else
{
   $more_flights_details = ''; 
}


$acc_hotel_name=$request->acc_hotel_name;
$acc_check_in=$request->acc_check_in;
$acc_check_out=$request->acc_check_out;
$acc_type=$request->acc_type;
$acc_qty=$request->acc_qty;
$acc_pax=$request->acc_pax;
$acc_price=$request->acc_price;
$acc_currency=$request->acc_currency;
// $acc_commision=$request->acc_commision;
// $acc_sale_Porice=$request->acc_sale_Porice;
$acc_total_amount=$request->acc_total_amount;
$acc_no_of_nightst=$request->acc_no_of_nightst;


if($request->hasFile('accomodation_image')){

        $file = $request->file('accomodation_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_accomodation_image = $filename;
    }
    else{
        $tour_accomodation_image = '';
    }


$accomodation_image=$tour_accomodation_image;

if(isset($acc_hotel_name))
{
   $arrLength = count($acc_hotel_name);
      for($i = 0; $i < $arrLength; $i++) {

$accomodation_details[]=(object)[
    
    'acc_hotel_name'=>$acc_hotel_name[$i],
    'acc_check_in'=>$acc_check_in[$i],
    'acc_check_out'=>$acc_check_out[$i],
    'acc_type'=>$acc_type[$i],
     'acc_qty'=>$acc_qty[$i],
    'acc_pax'=>$acc_pax[$i],
    'acc_price'=>$acc_price[$i],
    'acc_currency'=>$acc_currency[$i],
    //  'acc_commision'=>$acc_commision[$i],
    // 'acc_sale_Porice'=>$acc_sale_Porice[$i],
    'acc_total_amount'=>$acc_total_amount[$i],
    'acc_no_of_nightst'=>$acc_no_of_nightst[$i],
    'accomodation_image'=>$accomodation_image[$i],
    ];

}

 $accomodation_details=json_encode($accomodation_details);
}
else
{
   $accomodation_details = ''; 
}



$transportation_pick_up_location=$request->transportation_pick_up_location;
$transportation_drop_off_location=$request->transportation_drop_off_location;
$transportation_pick_up_date=$request->transportation_pick_up_date;
$transportation_trip_type=$request->transportation_trip_type;
$transportation_vehicle_type=$request->transportation_vehicle_type;
$transportation_no_of_vehicle=$request->transportation_no_of_vehicle;

$transportation_price_per_vehicle=$request->transportation_price_per_vehicle;
$transportation_vehicle_total_price=$request->transportation_vehicle_total_price;


$return_transportation_pick_up_location=$request->return_transportation_pick_up_location;
$return_transportation_drop_off_location=$request->return_transportation_drop_off_location;
$return_transportation_pick_up_date=$request->return_transportation_pick_up_date;

$transportation_price_per_person=$request->transportation_price_per_person;

if($request->hasFile('transportation_image')){

        $file = $request->file('transportation_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_transportation_image = $filename;
    }
    else{
        $tour_transportation_image = '';
    }


$transportation_image=$tour_transportation_image;

$transportation_details=[
   'transportation_pick_up_location'=>$transportation_pick_up_location,
'transportation_drop_off_location'=>$transportation_drop_off_location,
'transportation_pick_up_date'=>$transportation_pick_up_date,
'transportation_trip_type'=>$transportation_trip_type,
'transportation_vehicle_type'=>$transportation_vehicle_type,
'transportation_no_of_vehicle'=>$transportation_no_of_vehicle,

'transportation_price_per_vehicle'=>$transportation_price_per_vehicle,
'transportation_vehicle_total_price'=>$transportation_vehicle_total_price,

'return_transportation_pick_up_location'=>$return_transportation_pick_up_location,
'return_transportation_drop_off_location'=>$return_transportation_drop_off_location,
'return_transportation_pick_up_date'=>$return_transportation_pick_up_date,

'transportation_price_per_person'=>$transportation_price_per_person,
'transportation_image'=>$transportation_image,
    ];

 $transportation_details=json_encode($transportation_details);



$more_transportation_pick_up_location=$request->more_transportation_pick_up_location;
$more_transportation_drop_off_location=$request->more_transportation_drop_off_location;
$more_transportation_pick_up_date=$request->more_transportation_pick_up_date;


if(isset($more_transportation_pick_up_location))
{
   $arrLength = count($more_transportation_pick_up_location);
      for($i = 0; $i < $arrLength; $i++) {

$transportation_details_more[]=(object)[
    
    'more_transportation_pick_up_location'=>$more_transportation_pick_up_location[$i],
    'more_transportation_drop_off_location'=>$more_transportation_drop_off_location[$i],
    'more_transportation_pick_up_date'=>$more_transportation_pick_up_date[$i],
    
    
    ];

}

 $transportation_details_more=json_encode($transportation_details_more);
}
else
{
   $transportation_details_more = ''; 
}










    $start_date=$request->start_date;
    $end_date=$request->end_date;
    $time_duration=$request->time_duration;
    // $tour_min_people=$request->tour_min_people;
    // $tour_max_people=$request->tour_max_people;
    // $tour_location=$request->tour_location;
    $whats_included=$request->whats_included;
     $whats_excluded=$request->whats_excluded;
    // $tour_pricing=$request->tour_pricing;
    // $tour_sale_price=$request->tour_sale_price;
    $tour_publish=$request->tour_publish;
    $tour_author=$request->tour_author;
    $tour_feature=$request->tour_feature;
    $defalut_state=$request->defalut_state;

    
    // $tour_featured_image=$request->tour_featured_image;
    // $tour_banner_image=$request->tour_banner_image;

if($request->hasFile('tour_featured_image')){

        $file = $request->file('tour_featured_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_featured_image = $filename;
    }
    else{
        $tour_featured_image = '';
    }


      $tour_featured_image=$tour_featured_image;
      if($request->hasFile('tour_banner_image')){

        $file = $request->file('tour_banner_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_banner_image = $filename;
    }
    else{
        $tour_banner_image = '';
    }
      $tour_banner_image=$tour_banner_image;



    $Itinerary_title=$request->Itinerary_title;
      $Itinerary_city=$request->Itinerary_city;
      $Itinerary_content=$request->Itinerary_content;

      if($request->hasFile('Itinerary_image')){

        $file = $request->file('Itinerary_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
       $file->move('public/uploads/package_imgs/', $filename);
       
        $Itinerary_image = $filename;
    }
    else{
        $Itinerary_image = '';
    }
      $Itinerary_image=$Itinerary_image;
      $Itinerary_details=array([
                        'Itinerary_title'=>$Itinerary_title,
                          'Itinerary_city'=>$Itinerary_city,
                          'Itinerary_content'=>$Itinerary_content,
                          'Itinerary_image'=>$Itinerary_image,

                          ]);

      $Itinerary_details=json_encode($Itinerary_details);



      $more_Itinerary_title=$request->more_Itinerary_title;
      $more_Itinerary_city=$request->more_Itinerary_city;
      $more_Itinerary_content=$request->more_Itinerary_content;
     
      $more_Itinerary_image=array();
    if($files=$request->file('more_Itinerary_image')){
        foreach($files as $file){
            $name=$file->getClientOriginalName();
            $file->move('public/uploads/package_imgs/',$name);
            $more_Itinerary_image[]=$name;
        }
    }

      $more_Itinerary_image=$more_Itinerary_image;
      
      if(isset($more_Itinerary_title))
      {
          
          $arrLength = count($more_Itinerary_title);
      for($i = 0; $i < $arrLength; $i++) {

$more_itinery_details[]=(object)[
    
    'more_Itinerary_title'=>$more_Itinerary_title[$i],
    'more_Itinerary_city'=>$more_Itinerary_city[$i],
    'more_Itinerary_content'=>$more_Itinerary_content[$i],
    'more_Itinerary_image'=>$more_Itinerary_image[$i],
    ];

}
// print_r($more_itinery); 
     

 
      $tour_itinerary_details_1=json_encode($more_itinery_details);
    //      print_r($tour_itinerary_details_1);
    //   die();

// print_r(json_decode($tour_itinerary_details_1));die();
          
      }
      else
{
   $tour_itinerary_details_1= ''; 
}
      
      

      $extra_price_name=$request->extra_price_name;
      $extra_price_price=$request->extra_price_price;
      $extra_price_type=$request->extra_price_type;
      $extra_price_person=$request->extra_price_person;
      $tour_extra_price=array([
        'extra_price_name'=>$extra_price_name,
          'extra_price_price'=>$extra_price_price,
          'extra_price_type'=>$extra_price_type,
          'extra_price_person'=>$extra_price_person,

          ]);

$tour_extra_price=json_encode($tour_extra_price);



// $tour->tour_extra_price=$tour_extra_price;





  
$more_extra_price_title=$request->more_extra_price_title;
$more_extra_price_price=$request->more_extra_price_price;
$more_extra_price_type=$request->more_extra_price_type;
$more_extra_price_person=$request->more_extra_price_person;


if(isset($more_extra_price_title))
{
        $arrLength1 = count($more_extra_price_title);
      for($i = 0; $i < $arrLength1; $i++) {

$more_extra_price_details[]=(object)[
    
    'more_extra_price_title'=>$more_extra_price_title[$i],
    'more_extra_price_price'=>$more_extra_price_price[$i],
    'more_extra_price_type'=>$more_extra_price_type[$i],
    'more_extra_price_person'=>$more_extra_price_person[$i],
    ];

}
// print_r($more_extra_price_details); 
// die();
$tour_extra_price_1=json_encode($more_extra_price_details);
    
}
else
{
   $tour_extra_price_1= ''; 
}



  


    
    
    




$faq_title=$request->faq_title;
$faq_content=$request->faq_content;
$tour_faq=array([
  'faq_title'=>json_encode($faq_title),
    'faq_content'=>json_encode($faq_content),
    

    ]);

$tour_faq=json_encode($tour_faq);


$more_faq_title=$request->more_faq_title;
$more_faq_content=$request->more_faq_content;

if(isset($more_faq_title))
{
     $arrLength2 = count($more_faq_title);
      for($i = 0; $i < $arrLength2; $i++) {

$more_faq_details[]=(object)[
    
    'more_faq_title'=>$more_faq_title[$i],
    'more_faq_content'=>$more_faq_content[$i],
   
    ];

}
// print_r($more_faq_details); 
// die();




$tour_faq_1=json_encode($more_faq_details);
    
}
else
{
   $tour_faq_1= ''; 
}


    


 //usama changes
 
    //   $property_country=$request->property_country;
    //   $property_city=$request->property_city;
    //   $hotel_name=$request->hotel_name;
    //   $hotel_rooms_type=$request->hotel_rooms_type;
    //   $price_per_night=$request->price_per_night;
    //   $total_price_per_night=$request->total_price_per_night;
    
      $visa_type=$request->visa_type;
       $visa_fee=$request->visa_fee;
        $visa_rules_regulations=$request->visa_rules_regulations;
        
        
        if($request->hasFile('visa_image')){

        $file = $request->file('visa_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_visa_image = $filename;
    }
    else{
        $tour_visa_image = '';
    }


$visa_image=$tour_visa_image;
        
        
        
    //   $t_commission=$request->t_commission;
      $quad_grand_total_amount=$request->quad_grand_total_amount;
    $triple_grand_total_amount=$request->triple_grand_total_amount;
    $double_grand_total_amount=$request->double_grand_total_amount;
     $currency_symbol=$request->currency_symbol;
     
     $quad_cost_price=$request->quad_cost_price;
    $triple_cost_price=$request->triple_cost_price;
    $double_cost_price=$request->double_cost_price;
     $all_markup_type=$request->all_markup_type;
     $all_markup_add=$request->all_markup_add;
    // $all_quad_markup=$request->all_quad_markup;
    // $all_triple_markup=$request->all_triple_markup;
    //  $all_double_markup=$request->all_double_markup;



    
			$curl = curl_init();
 
			$data = array('token'=>$token,
			'customer_id'=>$customer_id,
			'title'=>$title,
			'content'=>$content,
			'tour_categories'=>$tour_categories,
			'tour_attributes'=>$tour_attributes,
			'start_date'=>$start_date,
			'end_date'=>$end_date,
			'time_duration'=>$time_duration,
// 			'tour_min_people'=>$tour_min_people,
// 			'tour_max_people'=>$tour_max_people,
			'tour_location'=>$tour_location,
			'whats_included'=>$whats_included,
			'whats_excluded'=>$whats_excluded,
			'currency_symbol'=>$currency_symbol,
// 			'tour_pricing'=>$tour_pricing,
// 			'tour_sale_price'=>$tour_sale_price,
			'tour_publish'=>$tour_publish,
			'tour_author'=>$tour_author,
			'tour_feature'=>$tour_feature,
			'defalut_state'=>$defalut_state,
			'tour_featured_image'=>$tour_featured_image,
      'tour_banner_image'=>$tour_banner_image,
      'Itinerary_details'=>$Itinerary_details,
      'tour_itinerary_details_1'=>$tour_itinerary_details_1,
      'tour_extra_price'=>$tour_extra_price,
      'tour_extra_price_1'=>$tour_extra_price_1,
      'tour_faq'=>$tour_faq,
      'tour_faq_1'=>$tour_faq_1,
      'external_packages'=>$external_packages,
    //   'property_country'=>$request->property_country,
    //   'property_city'=>$request->property_city,
    //   'hotel_name'=>$request->hotel_name,
    //   'hotel_rooms_type'=>$request->hotel_rooms_type,
    //   'price_per_night'=>$request->price_per_night,
    //   'total_price_per_night'=>$request->total_price_per_night,
      
      
      
      'markup_details'=>$markup_details,
      'more_markup_details'=>$more_markup_details,
      
      'no_of_pax_days'=>$no_of_pax_days,
      'destination_details'=>$destination_details,
      'destination_details_more'=>$destination_details_more,
      'flights_details'=>$flights_details,
      'more_flights_details'=>$more_flights_details,
      'accomodation_details'=>$accomodation_details,
      'transportation_details'=>$transportation_details,
      'transportation_details_more'=>$transportation_details_more,
      'visa_fee'=>$visa_fee,
      'visa_type'=>$visa_type,
      'visa_image'=>$visa_image,
      'visa_rules_regulations'=>$visa_rules_regulations,
       'quad_grand_total_amount'=>$quad_grand_total_amount,
    'triple_grand_total_amount'=>$triple_grand_total_amount,
    'double_grand_total_amount'=>$double_grand_total_amount,
    
    
     'quad_cost_price'=>$quad_cost_price,
    'triple_cost_price'=>$triple_cost_price,
    'double_cost_price'=>$double_cost_price,
     'all_markup_type'=>$all_markup_type,
     'all_markup_add'=>$all_markup_add,
    // 'all_quad_markup'=>$all_quad_markup,
    // 'all_triple_markup'=>$all_triple_markup,
    //  'all_double_markup'=>$all_double_markup,
    
    
    
      );
	

// print_r($data);die();


			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/add_activities',
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
// 	echo $response;die();
			curl_close($curl);
			$attributes = json_decode($response);
      	return redirect()->back()->with('message','created data');
       }




    public function view_activities(Request $request)
    {
        $customer_id=Session::get('id');
        $token=config('token');
      	$curl = curl_init();
		$data = array('token'=>$token,'customer_id'=>$customer_id);
    
        curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_activites_list',
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
		$activities = json_decode($response);
        // $tours=$activities->activities;
        // return view('template/frontend/userdashboard/pages/activities_packages/view_activities',compact('tours'));  
        
        $activities = json_decode($response);
        $tours      = $activities->activities;
        $final_data = $activities->data1;

        $data = [];
        foreach($tours as $tours_res){
            $tour_book = false;
            foreach($final_data as $book_res){
                if($tours_res->id == $book_res->tour_id){
                    $tour_book = true;
                }
            }

            $single_tour = [
                'id'                 => $tours_res->id,
                'title'              => $tours_res->title,
                'no_of_pax_days'     => $tours_res->no_of_pax_days,
                'start_date'         => $tours_res->start_date,
                'end_date'           => $tours_res->end_date,
                'tour_location'      => $tours_res->tour_location,
                'tour_author'        => $tours_res->tour_author,
                'tour_publish'       => $tours_res->tour_publish,
                'no_of_pax_days'     => $tours_res->no_of_pax_days,
                'book_status'        => $tour_book,
            ];
            array_push($data,$single_tour);      
        }
        return view('template/frontend/userdashboard/pages/activities_packages/view_activities',compact(['tours','data','final_data']));
    }


  

  public function enable_activities($id)
  {
    $token=config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'id'=>$id);
    
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/enable_activites/{$id}',
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
			$enable = json_decode($response);
      
      return redirect()->back();
   
  }




  public function disable_activities($id)
  {

  
    $token=config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'id'=>$id);
   
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/disable_activites/{$id}',
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
			$disable_tour = json_decode($response);
      return redirect()->back();
      // print_r($disable_tour);die();
  }


//activities controller code ended

public function package_bookings(Request $request)
  {

   $customer_id=Session::get('id');
    $token= config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'customer_id'=>$customer_id);
   
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/package_bookings',
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
// 	echo $response;die();
			curl_close($curl);
			$packages = json_decode($response);
			$packages=$packages->packages;
// 			print_r($packages);die();
       return view('template/frontend/userdashboard/pages/total_booking/package_bookings',compact('packages'));
    //   print_r($disable_tour);die();
  }
  public function activities_bookings(Request $request)
  {

   $customer_id=Session::get('id');
    $token= config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'customer_id'=>$customer_id);
   
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/activities_bookings',
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
//	echo $response;die();
			curl_close($curl);
			$packages = json_decode($response);
			$packages=$packages->packages;
// 			print_r($packages);die();
       return view('template/frontend/userdashboard/pages/total_booking/activities_bookings',compact('packages'));
    //   print_r($disable_tour);die();
  }
  
  public function package_departure_list(Request $request)
  {
$customer_id=Session::get('id');
    $token= config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'customer_id'=>$customer_id);
   
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/package_departure_list',
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
// 	echo $response;die();
			curl_close($curl);
			$package_departure_list = json_decode($response);
// 			print_r($package_departure_list);die();
			$departure_list=$package_departure_list->departure_list;
			
       return view('template/frontend/userdashboard/pages/total_booking/package_departure_list',compact('departure_list'));
    //   print_r($disable_tour);die();
  }
  
  public function package_return_list(Request $request)
  {
$customer_id=Session::get('id');
    $token= config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'customer_id'=>$customer_id);
   
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/package_return_list',
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
// 	echo $response;die();
			curl_close($curl);
			$package_departure_list = json_decode($response);
// 			print_r($package_departure_list);die();
			$departure_list=$package_departure_list->departure_list;
			
       return view('template/frontend/userdashboard/pages/total_booking/package_return_list',compact('departure_list'));
    //   print_r($disable_tour);die();
  }
  
  
   public function activities_departure_list(Request $request)
  {
$customer_id=Session::get('id');
    $token= config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'customer_id'=>$customer_id);
   
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/activities_departure_list',
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
// 	echo $response;die();
			curl_close($curl);
			$package_departure_list = json_decode($response);
// 			print_r($package_departure_list);die();
			$departure_list=$package_departure_list->departure_list;
			
       return view('template/frontend/userdashboard/pages/total_booking/activities_departure_list',compact('departure_list'));
    //   print_r($disable_tour);die();
  }
  
  public function activities_return_list(Request $request)
  {
$customer_id=Session::get('id');
    $token= config('token');
  	$curl = curl_init();
    
			$data = array('token'=>$token,'customer_id'=>$customer_id);
   
    curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://admin.synchronousdigital.com/api/activities_return_list',
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
// 	echo $response;die();
			curl_close($curl);
			$package_departure_list = json_decode($response);
// 			print_r($package_departure_list);die();
			$departure_list=$package_departure_list->departure_list;
			
       return view('template/frontend/userdashboard/pages/total_booking/activities_return_list',compact('departure_list'));
    //   print_r($disable_tour);die();
  }





}
