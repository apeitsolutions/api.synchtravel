<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use DB;
use App\User;

class TBO_MataDataController extends Controller
{




	public function tbo_hotel_details(){

//$tbo_hotel_codes=\DB::table('synchron_adminpanel.tbo_hotel_codes')->get();
$tbo_hotel_codes=\DB::table('synchron_mata.tbo_hotel_codes1')->skip(3500)->take('500')->get();
$tbo_hotel_details=\DB::table('synchron_mata.tbo_hotel_details')->get();

 //print_r($tbo_hotel_codes);
if(isset($tbo_hotel_codes))
   {
    $hotel_codes=array();
    foreach($tbo_hotel_codes as $res_tbo_code)
    {
    $hotel_codes[]=$res_tbo_code->hotel_code; 
    //$hotel_codes[]=$res_tbo_code; 
    }
   }
    $hotel_codes_en=json_encode($hotel_codes); 
  $json = preg_replace('/["]/', '' ,$hotel_codes_en);

    $hotel_codes_trim=trim($json, '[]');
    //print_r($hotel_codes);die();
		$data = '{
      "Hotelcodes": "'.$hotel_codes_trim.'",
      "Language": "EN"
    }'; 
 //print_r($data);die();                
    $curl = curl_init();
    curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/HotelDetails',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json',
              'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
          ));
      
    $response = curl_exec($curl);
    curl_close($curl);
    $tbo_response = json_decode($response);
    $tbo_response=$tbo_response->HotelDetails;
//print_r($tbo_response);


foreach($tbo_response as $hotels)
      {
          
         
         
         $data=DB::table('synchron_mata.tbo_hotel_details')->insert([
             
             
                 'hotel_code'=>$hotels->HotelCode ?? '',
                 'hotel_name'=>$hotels->HotelName ?? '',
                 'hotel_description'=>$hotels->Description ?? '',
                 'hotel_facilities'=>json_encode($hotels->HotelFacilities ?? ''),
                 'attractions'=>json_encode($hotels->Attractions  ?? ''),
                 'images'=>json_encode($hotels->Images ?? ''),
                 'address'=>$hotels->Address ?? '',
                 'pin_code'=>$hotels->PinCode ?? '',
                 'city_id'=>$hotels->CityId ?? '',
                 'country_name'=>$hotels->CountryName ?? '',
                 'phone_no'=>$hotels->PhoneNumber ?? '',
                 'fax_no'=>$hotels->FaxNumber ?? '',
                 'map'=>$hotels->Map ?? '',
                 'hotel_rating'=>$hotels->HotelRating ?? '',
                 'city_name'=>$hotels->CityName ?? '',
                 'country_code'=>$hotels->CountryCode ?? '',
                 'checkin_time'=>$hotels->CheckInTime ?? '',
                 'checkout_time'=>$hotels->CheckOutTime ?? '',
                 
                 
                 
                 
                 ]);
         
         
     
      }




	}

	

}
