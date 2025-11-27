<?php

namespace App\Http\Controllers\frontend\user_dashboard;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Hash;
use Auth;
use App\User;
use App\Index;
use App\About_us;
// use App\Contact_us;
use App\Blog;
use App\Package;
use App\Gallery;
use App\Ourteam;
use App\WhyChoose;
use App\Models\Booking;
class UserController extends Controller
{
	
	public function index(){

		if(Auth::guard('web')->check()){


$email=Auth::guard('web')->user()->email;

			$this->bookings= Booking::orderBy('id', 'DESC')->where('lead_passenger_email',$email)->get();

		$data=(array)$this;





			return view('template/frontend/userdashboard/index',compact('data'));die;

		}
		else{

			return redirect('login1');
		}
die;
		// $this->index= Index::all();

		// $data=(array)$this;
		
	     // print_r($data['index']);die;

		

		return view('template/frontend/userdashboard/index',compact('data'));


		
	}
	public function user_profile(){


		$this->user=Auth::guard('web')->user()->get();
		$data=(array)$this;

// foreach ($data['user'] as $data) {
// 	print_r($data->name);die;
// }

		



		return view('template/frontend/userdashboard/pages/user_profile',compact('data'));
	}
	public function bookings(){


$email=Auth::guard('web')->user()->email;
// print_r($email);
// die;

     $this->bookings= Booking::orderBy('id', 'DESC')->where('lead_passenger_email',$email)->get();

		$data=(array)$this;
		
	     // print_r($data['bookings']);die;
	     // print_r(count($data['bookings']));die;


//          echo '<pre>';  print_r($data['bookings']);die;

		return view('template/frontend/userdashboard/pages/bookings',compact('data'));
	}

    public function bookingDetail(Request $request)
    {

        dd('called');
        $case = $request->get('case');
        $brn = $request->get('brn');
        $id = $request->get('id');
//        dd('called');
        $responseData = $this->getBookingReservation($case, $brn);
        $booking_detail = json_decode($responseData);
        $transfer_companies = $this->getTransferCompanies();
        $booking = $this->booking->select('id', 'hotel_makkah_total_amount', 'hotel_madina_total_amount', 'transfer_total_amount', 'booking_currency', 'lead_passenger_details')->find($id);
        if ($case == 'hotel_makkah_view_booking') {
            return view('template/agent/partial/_hotel-makkah-booking-modal', compact('booking_detail', 'booking'));
        } elseif ($case == 'hotel_madina_view_booking') {
            return view('template/agent/partial/_hotel-madina-booking-modal', compact('booking_detail', 'booking'));
        } elseif ($case == 'transfers_view_booking') {
            return view('template/agent/partial/_transfer-booking-modal', compact('booking_detail', 'booking', 'transfer_companies'));
        }
    }


    public function getBookingReservation($case, $brn)
    {
        $url = "https://dow.sa/dow-v2/dowapi.php";
        $data = array();
        $data['case'] = $case;
        if ($case == 'hotel_makkah_view_booking') {
            $data['makkah_brn'] = $brn;
        } elseif ($case == 'hotel_madina_view_booking') {
            $data['madina_brn'] = $brn;
        } elseif ($case == 'transfers_view_booking') {
            $data['transfer_brn'] = $brn;
        }

        /**
         * Call the API to get booking data with Booking Reference No. and case
         */

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

public function settings(){


		return view('template/frontend/userdashboard/pages/settings');
	}

public function logout()
	{  
		Auth::guard('web')->logout();
		return redirect('index'); 


	}






    public function getTransferCompanies()
    {
        function request2()
        {
// $url ="https://dow.sa/classes/apitransmoh.php";
            $url = "https://dow.sa/dow-v2/dowapi.php";
            $data = array('case' => 'transportcompanies');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);// this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            return $responseData;
        }

        $responseData2 = request2();
        $transportcompanies = json_decode($responseData2);

        return $transportcompanies;
//        echo '<pre>'; print_r($transportcompanies); exit();
    }



}
