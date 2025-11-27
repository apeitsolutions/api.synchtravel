<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\WebsiteController;

use App\Models\ToursBooking;
use App\Models\booking_users;
use App\Models\Cart_details;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\view_booking_payment_recieve;
use App\Models\pay_Invoice_Agent;
use App\Models\alhijaz_Notofication;
use App\Models\booking_customers;

use DB;

class bookingController extends Controller
{
    //
    
     function submit_invoice(Request $request){
        //  print_r($request->all());
         return redirect('invoice/'.$request->invoice_no.'');
     }
     
      function save_payments(Request $request){
        //  dd($request->all());
         
          if($request->file('voucher')){
                $img_file = $request->file('voucher');
                $name_gen = hexdec(uniqid());
                $img_ext = strtolower($img_file->getClientOriginalExtension());
                $img_name = $name_gen.".".$img_ext;
                $upload = 'public/images/payment_vouchers/';
                $file_upload = $img_file->move($upload,$img_name);
                if($file_upload){
                      $token= config('token');
                      $data = array('token'=>$token,
                              'transcation_id' => $request->transcation_id,
                              'invoice_id' => $request->invoice_id,
                              'payment_am' => $request->payment_am,
                              'account_no' => $request->account_no,
                              'email' => $request->email,
                              'voucher' => $img_name,
                              'domain' => 'client1.synchronousdigital.com',
                              );

                    // dd($request->all());
                    $curl = curl_init();
                    
                    $endpoint_url = config('endpoint_project');
                        curl_setopt_array($curl, array(
            			CURLOPT_URL => $endpoint_url.'/api/save_booking_payment',
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
               
                        	$data = json_decode($response);
            // 			print_r($data);
            			curl_close($curl);
            		
            			if($data->message == 'success'){
            			    return redirect('invoice/'.$request->invoice_id.'')->with('success','Payment Detail Submited Successfully');
            			}else{
            			    return redirect('invoice/'.$request->invoice_id.'')->with('error','Somthing Went Wrong Try Again');
            			}
                }else{
                    return redirect('invoice/'.$request->invoice_id.'')->with('error','Somthing Went Wrong Try Again');
                }
            
            // echo "<pre>";
            // print_r($imagesNames);
            // echo "</pre>";
        }
         
    
     }
     
    
     function view_booking_with_token(){

        $token= config('token');
        $data = array('token'=>$token);

        // dd($request->all());
        $curl = curl_init();
        
        $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
			CURLOPT_URL => $endpoint_url.'/api/get_customer_booking',
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
            $booking = json_decode($response);
            
            $data = $booking->bookings;
            // $passenger_Data = json_decode($data->passenger_detail);
            
            // print_r($passenger_Data);
      
        return view('template/frontend/userdashboard/pages/tour/view_all_bookings',compact('data'));
              
		
        //     print_r($Booking_details)
    }
    
    function submit_booking(){

        if(session('Childs')){
            $childs = session('Childs');
            // print_r(session('Childs'));
        }else{
            $childs = '';
        }
        
        if(session('booking_person')){
            if(session()->get('booking_person') == 'admin'){
                $booking_person = 'admin';
            }else{
                $booking_person = 'user';
            }
        }else{
            $booking_person = 'user';
        }
        
        $token= config('token');
        $data = array('token'=>$token,
        'request_data'=>json_encode(session('passengerDetail')),
        'adults'=>json_encode(session('adults')),
        'childs'=>json_encode($childs),
        'cart_data'=>json_encode(session('cart')),
        'booking_person'=>$booking_person
        );

        // dd($data);
        $curl = curl_init();
       
        $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
			CURLOPT_URL => $endpoint_url.'/api/save_booking',
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
            // die();
			curl_close($curl);
            $tours = json_decode($response);
            
            if($tours->message == 'error'){
                echo "Someth went wrong ";
                return redirect()->back()->with('error','Something Went Wrong Try Again');
            }else{
                    session()->forget('cart');
                    session()->forget('adults');
                    session()->forget('Childs');
                    session()->forget('passengerDetail');
                     if(session('booking_person')){
                     session()->forget('booking_person');
                     }
        
                  return redirect('invoice/'.$tours->invoice_id.'');
            }
            // echo "This si tour ";
            // print_r($tours);
           
		
        //     print_r($Booking_details)
    }
    
    function get_payment_modes(Request $request){
        // print_r($request->all());
          $token= config('token');
        $data = array('token'=>$token,'tour_id'=>$request->tour_id,'type'=>$request->Tourtype);
        $curl = curl_init();
    
        $endpoint_url = config('endpoint_project');
        curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint_url.'/api/get_tour_payment_mode',
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
        $payment_modes = json_decode($response);
        return $payment_modes;
        // print_r($countries);
        // return view('template/frontend/pages/checkout',compact('countries'));
    }

	public function cart()
    {
        // print_r(session()->get('cart'));
        return view('template/frontend/pages/cart');
    }

    public function checkout()
    {
        $token= config('token');
        $data = array('token'=>$token);
        $curl = curl_init();
    
        $endpoint_url = config('endpoint_project');
        curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint_url.'/api/countries',
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
        $countries = json_decode($response);

        // print_r($countries);
        return view('template/frontend/pages/checkout',compact('countries'));
    }
  
    public function invoice($id)
    {
            $token= config('token');
            $data = array('token'=>$token,'booking_id'=>$id);
            $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
			CURLOPT_URL => $endpoint_url.'/api/invoice_data',
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
            $inv_details = json_decode($response);
            if($inv_details->message !== 'failed'){
                $cart_data=$inv_details->cart_data;
                $inv_data=$inv_details->inv_details;
    
                $passenger_Det = json_decode($inv_data->passenger_detail);
                
                $iternery_array = [];
                $visa_type_arr = [];
                foreach($cart_data as $cart_res){
                    
                    $token= config('token');
                    $data = array('token'=>$token,'generate_id'=>$cart_res->generate_id,'type'=>$cart_res->pakage_type);
                    $curl = curl_init();
                
                    $endpoint_url = config('endpoint_project');
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $endpoint_url.'/api/get_tour_itenery',
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
                    // die();
                    curl_close($curl);
                    $internery_dt = json_decode($response);
                    $internery_dt=$internery_dt->tours;
                    // print_r($internery_dt);
                    array_push($iternery_array,$internery_dt);
                    // $iternery1=json_decode($tour_details->tour_itinerary_details_1);
    
                        if($cart_res->pakage_type == 'tour'){
                            if(isset($internery_dt[0]->visa_type)){
                                 $visa_type = WebsiteController::get_visa_type($internery_dt[0]->visa_type);
                                    array_push($visa_type_arr,$visa_type);
                            }
                           
                        }
                      
                      
                }
                
                // print_r($iternery_array);
                // die();
                   
                if($cart_res->pakage_type == 'tour'){
                     if(isset($iternery_array[0][0]->tour_id)){
                         $Tour_send_id = $iternery_array[0][0]->tour_id;
                     }else{
                         $Tour_send_id = '';
                     }
                }else{
                    if(isset($iternery_array[0][0]->activity_id)){
                        $Tour_send_id = $iternery_array[0][0]->activity_id;
                    }else{
                        $Tour_send_id = '';
                    }
                    
                }
               $booking_id = $inv_data->id;
                // die();
                
                $package_type = $cart_res->pakage_type;
                return view('template/frontend/pages/invoice',compact('inv_data','cart_data','id','iternery_array','passenger_Det','visa_type_arr','Tour_send_id','booking_id','package_type'));
            }else{
                 return redirect('/')->with('error', 'Inovice No. not found please Enter Correct Invoice No.!');
            }
             
    }
    
    public function invoice_admin($id)
    {
        $inv_details        = ToursBooking::where('invoice_no',$id)->first();
        $cart_data          = cart_details::where('invoice_no',$id)->get();
        $cart_data_count    = count($cart_data);
        
        $inv_data           = $inv_details;
        
        $passenger_Det      = json_decode($inv_details->passenger_detail);
        
        $iternery_array     = [];
        $visa_type_arr      = [];
        foreach($cart_data as $cart_res){
        
            $tours          = DB::table('tours')
                                ->join('tours_2','tours.id','tours_2.tour_id')
                                ->where('tours.generate_id',$cart_res->generate_id)
                                ->select('tours.*','tours_2.*')
                                ->get();
            array_push($iternery_array,$tours);
            if($cart_res->pakage_type == 'tour'){
                if(isset($tours[0]->visa_type)){
                    $visa_type = WebsiteController::get_visa_type($tours[0]->visa_type);
                    array_push($visa_type_arr,$visa_type);
                }
            }
            
            if($cart_res->pakage_type == 'tour'){
                if(isset($iternery_array[0][0]->tour_id)){
                    $Tour_send_id = $iternery_array[0][0]->tour_id;
                }else{
                    $Tour_send_id = '';
                }
            }else{
                if(isset($iternery_array[0][0]->activity_id)){
                    $Tour_send_id = $iternery_array[0][0]->activity_id;
                }else{
                    $Tour_send_id = '';
                }
            }
            
            
        }
        $booking_id     = $inv_details->id;
        $package_type   = $cart_res->pakage_type;
        
        // dd($inv_details);
        return view('template/frontend/pages/invoice',compact('inv_data','cart_data','id','iternery_array','passenger_Det','visa_type_arr','Tour_send_id','booking_id','package_type'));
    }
        
        // Invoice Package
        public function invoice_package($id,$booking_id,$T_ID){
            $token = config('token');
            $data  = array('token'=>$token,'booking_id'=>$id,'booking_id1'=>$booking_id,'T_ID'=>$T_ID);
            // dd($data);
            $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
    			CURLOPT_URL => $endpoint_url.'/api/invoice_package_data',
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
            $inv_details = json_decode($response);
            // print_r($inv_details);die();
            if($inv_details->message !== 'failed'){
            
                $package_Type               = $inv_details->package_Type;
                // Package Details
                $package_Payments           = $inv_details->package_Payments;
                $package_Payments_count     = $inv_details->package_Payments_count;
                $currency_Symbol            = $inv_details->currency_Symbol;
                $total_package_Payments     = $inv_details->total_package_Payments;
                $recieved_package_Payments  = $inv_details->recieved_package_Payments;
                $remainig_package_Payments  = $inv_details->remainig_package_Payments;
                
                // Activity Details
                $activity_Payments           = $inv_details->activity_Payments;
                $activity_Payments_count     = $inv_details->activity_Payments_count;
                $total_activity_Payments     = $inv_details->total_activity_Payments;
                $recieved_activity_Payments  = $inv_details->recieved_activity_Payments;
                $remainig_activity_Payments  = $inv_details->remainig_activity_Payments;
                
                // Status
                $payment_Status              = $inv_details->payment_Status;
                $tour_ID1                    = $inv_details->tour_ID;
                $flights_details             = json_decode($tour_ID1->flights_details ?? '');
                $flights_detailsI            = json_decode($tour_ID1->flights_details_more ?? ''); 
                $booking_ID                  = $inv_details->booking_ID;
                // $flights_details          = json_decode($flights_details1);
                
                // Invoice Data
                $cart_data=$inv_details->cart_data;
                $inv_data=$inv_details->inv_details;
                $contact_details=$inv_details->contact_details;
                $tour_batchs=$inv_details->tour_batchs;
                $passenger_Det = json_decode($inv_data->passenger_detail);
                $iternery_array = [];
    
                foreach($cart_data as $cart_res){
                    $token= config('token');
                    $data = array('token'=>$token,'generate_id'=>$cart_res->generate_id,'type'=>$cart_res->pakage_type);
                    $curl = curl_init();
                    $endpoint_url = config('endpoint_project');
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $endpoint_url.'/api/get_tour_iternery_invoice_package_data',
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
                    $internery_dt = json_decode($response);
                    $internery_dt = $internery_dt->tours ?? '';
                    array_push($iternery_array,$internery_dt);
                }
                
                $tour_data = $tour_ID1;
                return view('template/frontend/pages/invoice_package',compact('flights_detailsI','flights_details','payment_Status','package_Type','remainig_activity_Payments','recieved_activity_Payments','total_activity_Payments','activity_Payments_count','activity_Payments','total_package_Payments','recieved_package_Payments','remainig_package_Payments','currency_Symbol','package_Payments_count','package_Payments','inv_data','cart_data','id','iternery_array','passenger_Det','contact_details','tour_batchs','tour_data'));
            }else{
                return redirect('/')->with('error', 'Inovice No. not found please Enter Correct Invoice No.!');
            }
        }
        
        
        public function invoice_package_admin($id,$booking_id,$T_ID){
            $T_ID                       = $T_ID;
            $booking_id1                = $booking_id;
            
            $package_Type               = DB::table('cart_details')->where('invoice_no',$id)->select('pakage_type')->first();
            // dd($package_Type);
            
            $package_Payments           = DB::table('view_booking_payment_recieve')->where('package_id',$booking_id1)->get();
            
            $package_Payments_count     = DB::table('view_booking_payment_recieve')->where('package_id',$booking_id1)->count();
            
            $total_package_Payments     = DB::table('view_booking_payment_recieve')->where('package_id',$booking_id1)->first();
            
            $recieved_package_Payments  = DB::table('view_booking_payment_recieve')->where('package_id',$booking_id1)->sum('amount_paid');
            
            $remainig_package_Payments  = DB::table('view_booking_payment_recieve')->where('package_id',$booking_id1)->sum('remaining_amount');
            
            // Activity Details
            $activity_Payments           = DB::table('cart_details')->where('invoice_no',$id)
                                            ->Join('view_booking_payment_recieve_Activity','cart_details.id','view_booking_payment_recieve_Activity.package_id')->get();
                                            
            $activity_Payments_count     = DB::table('cart_details')->where('invoice_no',$id)
                                            ->Join('view_booking_payment_recieve_Activity','cart_details.id','view_booking_payment_recieve_Activity.package_id')->count();
                                            
            $total_activity_Payments     = DB::table('cart_details')->where('invoice_no',$id)->first();
            
            $recieved_activity_Payments  = DB::table('cart_details')->where('invoice_no',$id)
                                            ->Join('view_booking_payment_recieve_Activity','cart_details.id','view_booking_payment_recieve_Activity.package_id')->sum('amount_paid');
                                            
            $remainig_activity_Payments  = DB::table('cart_details')->where('invoice_no',$id)
                                            ->Join('view_booking_payment_recieve_Activity','cart_details.id','view_booking_payment_recieve_Activity.package_id')->sum('remaining_amount');
            
            // status
            $booking_ID     =  DB::table('tours_bookings')->where('invoice_no',$id)->first();
            $payment_Status = DB::table('cart_details')->where('booking_id',$booking_id1)->first();
            $tour_data_id   = DB::table('tours')->where('id',$T_ID)->first();
            $inv_details    = ToursBooking::where('invoice_no',$id)->first();
            $cart_data      = cart_details::where('invoice_no',$id)->get();
            $cart_data1     = cart_details::where('invoice_no',$id)->first();
            $tour_batchs    = DB::table('tour_batchs')->where('generate_id',$cart_data1->generate_id)->first();
            
            if($package_Type == 'tour'){
                $tour_ID = DB::table('tours')->where('id',$T_ID)->first();
            }else{
                $tour_ID = DB::table('new_activites')->where('id',$T_ID)->first();
            }
            
            $response = [
                'message'                   => 'success',
                
                'booking_ID'                => $booking_ID,
                'tour_ID'                   => $tour_ID,
                'package_Type'              => $package_Type,
                'tour_data_id'              => $tour_data_id,
                'package_Payments_count'    => $package_Payments_count,
                'package_Payments'          => $package_Payments,
                // 'currency_Symbol'           => $currency_Symbol,
                'total_package_Payments'    => $total_package_Payments,
                'recieved_package_Payments' => $recieved_package_Payments,
                'remainig_package_Payments' => $remainig_package_Payments,
                
                'activity_Payments'          => $activity_Payments,
                'activity_Payments_count'    => $activity_Payments_count,
                'total_activity_Payments'    => $total_activity_Payments,
                'recieved_activity_Payments' => $recieved_activity_Payments,
                'remainig_activity_Payments' => $remainig_activity_Payments,
                
                'payment_Status'             => $payment_Status,
                
                'inv_details'               => $inv_details,
                'cart_data'                 => $cart_data,
                // 'contact_details'           => $contact_details,
                'tour_batchs'               => $tour_batchs
            ];
            
            $inv_details = $response;
            
            if($inv_details['message'] !== 'failed'){
            
                $package_Type               = $inv_details['package_Type'];
                // Package Details
                $package_Payments           = $inv_details['package_Payments'];
                $package_Payments_count     = $inv_details['package_Payments_count'];
                $currency_Symbol            = '';
                $total_package_Payments     = $inv_details['total_package_Payments'];
                $recieved_package_Payments  = $inv_details['recieved_package_Payments'];
                $remainig_package_Payments  = $inv_details['remainig_package_Payments'];
                
                // Activity Details
                $activity_Payments           = $inv_details['activity_Payments'];
                $activity_Payments_count     = $inv_details['activity_Payments_count'];
                $total_activity_Payments     = $inv_details['total_activity_Payments'];
                $recieved_activity_Payments  = $inv_details['recieved_activity_Payments'];
                $remainig_activity_Payments  = $inv_details['remainig_activity_Payments'];
                
                // Status
                $payment_Status              = $inv_details['payment_Status'];
                $tour_ID1                    = $inv_details['tour_ID'];
                $flights_details             = json_decode($tour_ID1->flights_details ?? '');
                $flights_detailsI            = json_decode($tour_ID1->flights_details_more ?? ''); 
                $booking_ID                  = $inv_details['booking_ID'];
                // $flights_details          = json_decode($flights_details1);
                
                // Invoice Data
                $cart_data          = $inv_details['cart_data'];
                $inv_data           = $inv_details['inv_details'];
                $contact_details    = '';
                $tour_batchs        = $inv_details['tour_batchs'];
                $passenger_Det      = json_decode($inv_data->passenger_detail);
                $iternery_array     = [];
    
                foreach($cart_data as $cart_res){
                    $internery_dt = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.generate_id',$cart_res->generate_id)->select('tours.*','tours_2.*')->get();
                    array_push($iternery_array,$internery_dt);
                }
                
                $tour_data = $tour_ID1;
                return view('template/frontend/pages/invoice_package',compact('flights_detailsI','flights_details','payment_Status','package_Type','remainig_activity_Payments','recieved_activity_Payments','total_activity_Payments','activity_Payments_count','activity_Payments','total_package_Payments','recieved_package_Payments','remainig_package_Payments','currency_Symbol','package_Payments_count','package_Payments','inv_data','cart_data','id','iternery_array','passenger_Det','contact_details','tour_batchs','tour_data'));
            }else{
                return redirect('/')->with('error', 'Inovice No. not found please Enter Correct Invoice No.!');
            }
        }
  
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart(Request $request)
    {
        
        // 	print_r($request->all());
        // session()->forget('cart');
		
		
		if($request->pakage_type == 'activity'){
		    	$token= config('token');
                $curl = curl_init();
                $id = $request->toure_id;
                        $data = array('token'=>$token,'id'=>$request->toure_id,'pakage_type' =>$request->pakage_type);
                
                        $endpoint_url = config('endpoint_project');
                curl_setopt_array($curl, array(
        			CURLOPT_URL => $endpoint_url.'/api/get_tour_for_cart',
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
        // 			echo $response;
        		
        			curl_close($curl);
        			$tours = json_decode($response);
                    $tour_details=$tours->tours;
                    
                    // print_r($tour_details);
                    // 	die();
        
        		// session()->forget('cart');
                //   die();
                $cart = session()->get('cart', []);
                
                if(isset($cart[1])){
                     if($cart[1] == 'activity'){
                        $cart = $cart[0];
                    }else{
                        $cart = [];
                    }
                }
               
                
                // print_r($cart);
                // die();
                
            
                
                $adult_price = $tour_details->sale_price;
        		if($tour_details->child_sale_price > 0){
        			$child_price = $tour_details->child_sale_price;
        		}else{
        			$child_price = $tour_details->sale_price;
        		}
        		
        		$adults_activty_price = $request->adults * $adult_price;
        		$child_activty_price = $request->childs * $child_price;
        		
        		 $total_activty_price = $adults_activty_price + $child_activty_price;
        
                $total_person = $request->adults + $request->childs;
               
                
        		 $additional_service_more = json_decode($tour_details->addtional_service_arr);
        		 
        		 
        		 $additional_service_total = 0;
        		
        		 
        		 
        		 $services_price_more = [];
        		 $services_days_more = [];
        		 $service_dates_more = [];
        		 
        		 
        		 if(isset($request->additonal_service)){
        		     $y = 0;
        		     foreach($request->additonal_service as $service_res){
        		         foreach($additional_service_more as $all_service){
        		             if($service_res == $all_service->service_name){
        		                 if($all_service->service_type == 0){
                    		         $days = 1;
                    		     }else{
                    		         $days = $request->service_days[$y];
                    		     }
                    		     
                    		     
                    		     
                    		     
        		                 
        		                 $serivce_total = $request->service_adults[$y] * ($all_service->service_price * $days);
        		                 $additional_service_total = $additional_service_total + $serivce_total;
        		              //   echo "the days $days are   ".$request->service_adults[$y]." price ".$all_service->more_extra_price_price."  are  total is $serivce_total <br>";
        		                 array_push($services_price_more,$all_service->service_price);
        		                 array_push($services_days_more,$days);
        		                 array_push($service_dates_more,$request->service_dates[$y]);
        		                 
        		                 
        		             }
        		         }
        		         $y++;
        		     }
        		     
        		     
        
        		    
        		 }
        		 
        // 		 echo " service is $additional_service_total ";
        
                if(isset($cart[$id])) {
        
        
                    
                    
                     $total_person = $request->adults + $request->childs;
                   
                    
                      $cart[$id] = [
                        "activtyId" => $request->toure_id,
                        "generate_id" => $tour_details->generate_id,
                        "name" => $tour_details->title,
                        "adults" => $request->adults,
                        "children" => $request->childs,
                        "activity_select_date" => $request->activity_date,
                        "adult_price" => $adult_price,
                        "child_price" => $child_price,
                        "activity_total_price" => $total_activty_price,
                        "price" => $total_activty_price + $additional_service_total,
                        "total_service_price" => $additional_service_total,
                        "image" => $tour_details->featured_image,
                        "currency" => $tour_details->currency_symbol,
                        "pakage_type" => $request->pakage_type,
                        "Additional_services_names_more" =>json_encode($request->additonal_service),
                        "services_persons_more" =>json_encode($request->service_adults),
                        "services_price_more" =>json_encode($services_price_more),
                        "services_days_more" =>json_encode($services_days_more),
                        "services_dates_more" =>json_encode($service_dates_more),
                        "cancellation_policy" => $tour_details->cancellation_policy,
                        "checkout_message" => $tour_details->checkout_message,
                        
                    ];
                    //  session()->put('cart', $cart);
                } else {
                  
        
                    $cart[$id] = [
                        "activtyId" => $request->toure_id,
                        "generate_id" => $tour_details->generate_id,
                        "name" => $tour_details->title,
                        "adults" => $request->adults,
                        "children" => $request->childs,
                        "activity_select_date" => $request->activity_date,
                        "adult_price" => $adult_price,
                        "child_price" => $child_price,
                        "activity_total_price" => $total_activty_price,
                        "price" => $total_activty_price + $additional_service_total,
                        "total_service_price" => $additional_service_total,
                        "image" => $tour_details->featured_image,
                        "currency" => $tour_details->currency_symbol,
                        "pakage_type" => $request->pakage_type,
                        "Additional_services_names_more" =>json_encode($request->additonal_service),
                        "services_persons_more" =>json_encode($request->service_adults),
                        "services_price_more" =>json_encode($services_price_more),
                        "services_days_more" =>json_encode($services_days_more),
                        "services_dates_more" =>json_encode($service_dates_more),
                        "cancellation_policy" => $tour_details->cancellation_policy,
                        "checkout_message" => $tour_details->checkout_message,
                        
                    ];
        
                }
                 
                $cart_cover = [$cart,'activity'];  
                session()->put('cart', $cart_cover);
                
                
		}else{
		    	$token= config('token');
                $curl = curl_init();
                $id = $request->toure_id;
                        $data = array('token'=>$token,'id'=>$request->toure_id,'pakage_type' =>$request->pakage_type);
                
                        $endpoint_url = config('endpoint_project');
                curl_setopt_array($curl, array(
        			CURLOPT_URL => $endpoint_url.'/api/get_tour_for_cart',
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
        // 			echo $response;
        // 			die();
        			curl_close($curl);
        			$tours = json_decode($response);
                    $tour_details=$tours->tours;
        
        		// session()->forget('cart');
                //   die();
                $cart = session()->get('cart', []);
                
        	    if(isset($cart[1])){
                     if($cart[1] == 'tour'){
                        $cart = $cart[0];
                    }else{
                        $cart = [];
                    }
                }
        
                $total_person = $request->adults + $request->childs;
                $sharing_price = 0;
                if($request->sharing == 'sharing2'){
                    $sharing_price = $tour_details->double_grand_total_amount;
                }else if($request->sharing == 'sharing3'){
                    $sharing_price = $tour_details->triple_grand_total_amount;
                }else if($request->sharing == 'sharing4'){
                    $sharing_price = $tour_details->quad_grand_total_amount;
                }
                
                 $additional_service = json_decode($tour_details->tour_extra_price);
        		 $additional_service_more = json_decode($tour_details->tour_extra_price_1);
        		 
        		 
        		 $additional_service_total = 0;
        		 $service_price = 0;
        		 $service_day = 0;
        		 if(isset($request->additonal_service1)){
        		     if($additional_service[0]->extra_price_type == 0){
        		         $days = 1;
        		     }else{
        		         $days = $request->service_days1;
        		     }
        		     
        		    
        		     
        		    $additional_service_total = $request->service_adults1 * ($additional_service[0]->extra_price_price * $days);
        		    
        		    $service_day = $days;
        		    $service_dates = $request->service_dateRang1;
        		    
        		  //  echo "the days $days are   ".$request->service_adults1." are  total is $additional_service_total <br>";
        		    $service_price = $additional_service[0]->extra_price_price;
        		 }else{
        		     $service_dates = '';
        		 }
        		 
        		 
        		 $services_price_more = [];
        		 $services_days_more = [];
        		 $service_dates_more = [];
        		 
        		 
        		 if(isset($request->additonal_service)){
        		     $y = 0;
        		     foreach($request->additonal_service as $service_res){
        		         foreach($additional_service_more as $all_service){
        		             if($service_res == $all_service->more_extra_price_title){
        		                 if($all_service->more_extra_price_type == 0){
                    		         $days = 1;
                    		     }else{
                    		         $days = $request->service_days[$y];
                    		     }
                    		     
                    		     
                    		     
                    		     
        		                 
        		                 $serivce_total = $request->service_adults[$y] * ($all_service->more_extra_price_price * $days);
        		                 $additional_service_total = $additional_service_total + $serivce_total;
        		              //   echo "the days $days are   ".$request->service_adults[$y]." price ".$all_service->more_extra_price_price."  are  total is $serivce_total <br>";
        		                 array_push($services_price_more,$all_service->more_extra_price_price);
        		                 array_push($services_days_more,$days);
        		                 array_push($service_dates_more,$request->service_dates[$y]);
        		                 
        		                 
        		             }
        		         }
        		         $y++;
        		     }
        		     
        		     
        
        		    
        		 }
        		 
        // 		 echo " service is $additional_service_total ";
        
                if(isset($cart[$id])) {
        
        
                    unset($cart[$id]);
                    
                     $total_person = $request->adults + $request->childs;
                    $total_price = $sharing_price * $total_person;
                    
                      $cart[$id] = [
                        "tourId" => $request->toure_id,
                        "generate_id" => $tour_details->generate_id,
                        "name" => $tour_details->title,
                        "adults" => $request->adults,
                        "children" => $request->childs,
                        "sigle_price" => $sharing_price,
                        "tour_total_price" => $total_price,
                        "price" => $total_price + $additional_service_total,
                        "total_service_price" => $additional_service_total,
                        "sharing2" => $tour_details->double_grand_total_amount,
                        "sharing3" => $tour_details->triple_grand_total_amount,
                        "sharing4" => $tour_details->quad_grand_total_amount,
                        "sharingSelect"=>$request->sharing,
                        "image" => $tour_details->tour_banner_image,
                        "currency" => $tour_details->currency_symbol,
                        "pakage_type" => $request->pakage_type,
                        "Additional_services_names" =>$request->additonal_service1,
                        "services_persons" =>$request->service_adults1,
                        "services_price" =>$service_price,
                        "service_day" =>$service_day,
                        "service_dates" =>$service_dates,
                        "Additional_services_names_more" =>json_encode($request->additonal_service),
                        "services_persons_more" =>json_encode($request->service_adults),
                        "services_price_more" =>json_encode($services_price_more),
                        "services_days_more" =>json_encode($services_days_more),
                        "services_dates_more" =>json_encode($service_dates_more),
                        "cancellation_policy" => $tour_details->cancellation_policy,
                        "checkout_message" => $tour_details->checkout_message,
                        
                    ];
                    //  session()->put('cart', $cart);
                } else {
                    // echo "Enter here ".$cart[$id]['adults']." ".$request->adults;
                    $total_person = $request->adults + $request->childs;
                    $total_price = $sharing_price * $total_person;
        			// $total_price = ($request->adults * $price) + ($request->childs * $price) +  $total_sharing_price;
        
                    $cart[$id] = [
                        "tourId" => $request->toure_id,
                        "generate_id" => $tour_details->generate_id,
                        "name" => $tour_details->title,
                        "adults" => $request->adults,
                        "children" => $request->childs,
                        "sigle_price" => $sharing_price,
                        "tour_total_price" => $total_price,
                        "price" => $total_price + $additional_service_total,
                        "total_service_price" => $additional_service_total,
                        "sharing2" => $tour_details->double_grand_total_amount,
                        "sharing3" => $tour_details->triple_grand_total_amount,
                        "sharing4" => $tour_details->quad_grand_total_amount,
                        "sharingSelect"=>$request->sharing,
                        "image" => $tour_details->tour_banner_image,
                        "currency" => $tour_details->currency_symbol,
                        "pakage_type" => $request->pakage_type,
                        "Additional_services_names" =>$request->additonal_service1,
                        "services_persons" =>$request->service_adults1,
                        "services_price" =>$service_price,
                        "service_day" =>$service_day,
                        "service_dates" =>$service_dates,
                        "Additional_services_names_more" =>json_encode($request->additonal_service),
                        "services_persons_more" =>json_encode($request->service_adults),
                        "services_price_more" =>json_encode($services_price_more),
                        "services_days_more" =>json_encode($services_days_more),
                        "services_dates_more" =>json_encode($service_dates_more),
                        "cancellation_policy" => $tour_details->cancellation_policy,
                        "checkout_message" => $tour_details->checkout_message,
                        
                    ];
        
                }
                  
                   $cart_cover = [$cart,'tour'];  
                session()->put('cart', $cart_cover);
                
            
		}
		
// 		$session_cart = session()->get('cart');
        // print_r($session_cart);

        // print_r($request->all());
        
	

        // session()->forget('cart');
        //     session()->forget('adults');
        //     session()->forget('Childs');
        //     session()->forget('passengerDetail');
            
	
        return redirect('cart')->with('success', 'Added to cart successfully!');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request)
    {
        $cart_main = session()->get('cart');
        $cart = $cart_main[0];
        echo "This page is call ";
        print_r($request->all());
        if($request->id){
            $sharing_price = 0;
             if($request->sharing == 'sharing2'){
                $sharing_price = $cart[$request->id]["sharing2"];
            }else if($request->sharing == 'sharing3'){
                $sharing_price = $cart[$request->id]["sharing3"];
            }else if($request->sharing == 'sharing4'){
                $sharing_price = $cart[$request->id]["sharing4"];
            }

            

            
            $cart[$request->id]["adults"] = $request->adults;
            $cart[$request->id]["children"] = $request->childs;

            $total_persons = $request->adults + $request->childs;
            $total_price = $total_persons * $sharing_price;

            $cart[$request->id]["sigle_price"] = $sharing_price;
            // $people_total =  $single_price * $total_persons;
            //  $total = $sharing_price + $people_total;
             $cart[$request->id]["tour_total_price"] = $total_price;
             $cart[$request->id]["price"] = $total_price + $cart[$request->id]["total_service_price"];
             $cart[$request->id]["sharingSelect"] = $request->sharing;
             $cart[$request->id]["sharingPrice"] = $sharing_price;

            //  echo "Total is $total and shar $sharing_price and per $people_total";
            $cart_main[0] = $cart;
            session()->put('cart', $cart_main);
            // session()->flash('success', 'Cart updated successfully');
        }
    }
    
        public function update_activity_data(Request $request)
    {
        print_r($request->all());
        $cart_main = session()->get('cart');
        
        $cart = $cart_main[0];
        
        echo "This page is call ";
        print_r($request->all());
        if($request->id){
           

            

            
            $cart[$request->id]["adults"] = $request->adults;
            $cart[$request->id]["children"] = $request->childs;

            $total_price = ($request->adults * $request->adultsPrice) + ($request->childs * $request->childsPrice);
           

            // $people_total =  $single_price * $total_persons;
            //  $total = $sharing_price + $people_total;
             $cart[$request->id]["activity_total_price"] = $total_price;
             $cart[$request->id]["price"] = $total_price + $cart[$request->id]["total_service_price"];
            

            //  echo "Total is $total and shar $sharing_price and per $people_total";
            $cart_main[0] = $cart;
            session()->put('cart', $cart_main);
            session()->flash('success', 'Cart updated successfully');
        }
    }
    
    public function update_cart_service(Request $request)
    {
        $cart_main = session()->get('cart');
         $cart = $cart_main[0];
        echo "This page is call ";
        print_r($request->all());
        if($request->id){
         
            
            $service_total = $request->singlePrice * ($request->persons * $request->noOfDays);
            
            $difference = $service_total - $request->prev_total;
            
            if($request->index !== '-1'){
                // update Arr 
                echo "enter in more ";
                $personsArr = json_decode($cart[$request->id]["services_persons_more"]);
                $personsArr[$request->index] = $request->persons;
                // print_r($personsArr);
                $cart[$request->id]["services_persons_more"] = json_encode($personsArr);
                //  print_r($cart[$request->id]["services_persons_more"]);
            }else{
                // update Single
                $cart[$request->id]["services_persons"] = $request->persons;
                //   echo "enter in single ";
                
            }
            
            $total_price = $cart[$request->id]["price"];

        
             $cart[$request->id]["price"] = $total_price + $difference;
             $cart[$request->id]["total_service_price"] = $cart[$request->id]["total_service_price"] + $difference;
             
            //  echo "Total is $total and shar $sharing_price and per $people_total";
            
            $cart_main[0] = $cart;
            session()->put('cart', $cart_main);
            $cart = session()->get('cart');
            print_r($cart);
            // session()->flash('success', 'Cart updated successfully');
        }
    }
    
    
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if($request->id) {
            $cart_main = session()->get('cart');
            $cart = $cart_main[0];
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                
                 $cart_main[0] = $cart;
                 session()->put('cart', $cart_main);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }
    
     public function remove_service(Request $request)
    {
        
        
        print_r($request->all());
       $cart_main = session()->get('cart');
       $cart = $cart_main[0];
        echo "This page is call ";
        print_r($request->all());
        if($request->id){
         
            
            $service_total = $request->singlePrice * ($request->persons * $request->noOfDays);
            
            $difference = $service_total - $request->prev_total;
            
            if($request->index !== '-1'){
                // update Arr 
                echo "enter in more index no ".$request->index;
                $Additional_services_names_moreArr = json_decode($cart[$request->id]["Additional_services_names_more"]);
                $personsArr = json_decode($cart[$request->id]["services_persons_more"]);
                $services_price_moreArr = json_decode($cart[$request->id]["services_price_more"]);
                $services_days_moreArr = json_decode($cart[$request->id]["services_days_more"]);
                
                
                // print_r($Additional_services_names_moreArr);
                
                
                
                unset($Additional_services_names_moreArr[$request->index]);
                unset($personsArr[$request->index]);
                unset($services_price_moreArr[$request->index]);
                unset($services_days_moreArr[$request->index]);
                
                print_r($Additional_services_names_moreArr);
                
                $Additional_services_names_moreArr_up = array_values($Additional_services_names_moreArr);
                
                echo json_encode($Additional_services_names_moreArr_up);

                $cart[$request->id]["Additional_services_names_more"] = json_encode(array_values($Additional_services_names_moreArr));
                $cart[$request->id]["services_persons_more"] = json_encode(array_values($personsArr));
                $cart[$request->id]["services_price_more"] = json_encode(array_values($services_price_moreArr));
                $cart[$request->id]["services_days_more"] = json_encode(array_values($services_days_moreArr));
                
                //  print_r($cart[$request->id]["services_persons_more"]);
            }else{
                // update Single
                $cart[$request->id]["services_persons"] = '';
                $cart[$request->id]["Additional_services_names"] = '';
                $cart[$request->id]["services_price"] = '';
                $cart[$request->id]["service_day"] = '';
                
                //   echo "enter in single ";
                
            }
            
            // echo "<pre>";
            // print_r($cart);
            // echo "</pre>";
            
            $total_price = $cart[$request->id]["price"];

            echo "service price is $service_total ";
        
             $cart[$request->id]["price"] = $total_price - $request->prev_total;
             $cart[$request->id]["total_service_price"] = $cart[$request->id]["total_service_price"] - $request->prev_total;
             
            //  echo "Total is $total and shar $sharing_price and per $people_total";
             $cart_main[0] = $cart;
            session()->put('cart', $cart_main);

            $cart = session()->get('cart');
            // echo "<pre>";
            // print_r($cart);
            // echo "</pre>";
            // session()->flash('success', 'Cart updated successfully');
        }
    }
    
    

    public function save_passenger_detail(Request $request){
        // print_r($request->all());
        $passenger_Data = [$request->all()];
        session()->put('passengerDetail',$passenger_Data );

        return redirect('checkout');
        // print_r(session('passengerDetail'));
    }

    public function save_adutls(Request $request){
        // print_r($request->all());

        if($request->passengerType == 'adults'){
            $adults = session()->get('adults', []);
            array_push($adults,$request->all());
            session()->put('adults',$adults);
        }

        if($request->passengerType == 'child'){
            $adults = session()->get('Childs', []);
            array_push($adults,$request->all());
            session()->put('Childs',$adults);
            // print_r(session('Childs'));
        }
       
        return redirect()->back();
        // print_r(session('adults'));
        		// session()->forget('adults');

    }

    


}
