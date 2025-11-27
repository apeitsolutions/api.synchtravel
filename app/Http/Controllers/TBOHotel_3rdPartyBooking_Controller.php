<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;
use DB;
use App\Models\travellanda_get_hotel_detail;
use App\Models\travellanda_get_hotel;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;


class TBOHotel_3rdPartyBooking_Controller extends Controller
{
    // 3rd Party Api
    public function tboholidays_Country_List(){
        $data = '';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.tbotechnology.in/TBOHolidays_HotelAPI/CountryList',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
        ));
        
        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $CountryList        = '';
        $decode_Response    = json_decode($response);
        if($decode_Response->Status){
            if($decode_Response->Status->Description == 'Success'){
                $CountryList = $decode_Response->CountryList;
            }
        }
        return $CountryList;
    }
    
    public function tboholidays_City_List_OLD(){
        $country_Codes  = DB::table('tboHoliday_Country_List')->get();
        $decode_CC      = json_decode($country_Codes);
        if(count($decode_CC) > 0){
            for($c=0; $c<=$decode_CC; $c++){
                $country_Code   = $decode_CC[$c]->Code;
                $data           = '{"CountryCode":"'.$country_Code.'"}';
                $curl           = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>$data,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
                    ),
                ));
                $response = curl_exec($curl);
                // echo $response;die();
                curl_close($curl);
                $decode_Response    = json_decode($response);
                $CityList           = '';
                if($decode_Response->Status){
                    if($decode_Response->Status->Description == 'Success'){
                        $CityList   = $decode_Response->CityList;
                        $total_List = count($CityList);
                        for($x=0; $x<$total_List; $x++){
                            $code_Exist = DB::table('tboHoliday_City_List')->where('Code',$CityList[$x]->Code)->first();
                            if($code_Exist == null){
                                DB::table('tboHoliday_City_List')->insert([
                                    'Code'          => $CityList[$x]->Code,
                                    'Name'          => $CityList[$x]->Name,
                                    'CountryCode'   => $country_Code,
                                ]);
                            }
                        }
                    }
                }
            }
        }
        return 'STOP';
    }
    
    public function tboholidays_City_List(Request $req){
        // $data = '{"CountryCode":"'.$country_Code.'"}';
        $data = '{"CountryCode":"'.$req->CountryCode.'"}';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
        ));
        
        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $decode_Response = json_decode($response);
        $CityList        = '';
        $decode_Response    = json_decode($response);
        if($decode_Response->Status){
            if($decode_Response->Status->Description == 'Success'){
                $CityList = $decode_Response->CityList;
            }
        }
        return $CityList;
    }
    
    public function tboholidays_Hotel_Codes(){
        $data = '';
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.tbotechnology.in/TBOHolidays_HotelAPI/hotelcodelist',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_POSTFIELDS =>$data,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
          ),
        ));
        
        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $decode_Response    = json_decode($response);
        $HotelCodes         = $decode_Response->HotelCodes;
        // return $HotelCodes;
        $total_codes        = count($HotelCodes);
        return $total_codes;
        $all_Codes          = DB::table('tboHoliday_Hotel_Codes')->get();
        if(count($all_Codes) > 0){
            $x_new = count($all_Codes);
            $x_new++;
        }else{
            $x_new = 0;
        }
        
        // 321142
        // 307640
        // 321311
        
        // dd($total_codes,$x_new,$HotelCodes);
        for($x=$x_new; $x<=$total_codes; $x++){
            $code_Exist = DB::table('tboHoliday_Hotel_Codes')->where('HotelCodes',$HotelCodes[$x])->first();
            if($code_Exist == null){
                DB::table('tboHoliday_Hotel_Codes')->insert([
                    'HotelCodes' => $HotelCodes[$x],
                ]);
            }
        }
        $all_Codes = DB::table('tboHoliday_Hotel_Codes')->get();
        return $all_Codes;
    }
    
    public function tboholidays_Hotel_Codes_OLD(){
        $data = '';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.tbotechnology.in/TBOHolidays_HotelAPI/hotelcodelist',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
        ));
        
        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $decode_Response    = json_decode($response);
        return $decode_Response->HotelCodes;
    }
    
    public function tboholidays_Hotel_Details_req(Request $hotel_Code){
        $data = '{"Hotelcodes": '.$hotel_Code->hotel_Code.',"Language": "EN"}';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.tbotechnology.in/TBOHolidays_HotelAPI/HotelDetails',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
        ));
        
        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $HotelDetails       = '';
        $decode_Response    = json_decode($response);
        if($decode_Response->Status){
            if($decode_Response->Status->Description == 'Successful'){
                $HotelDetails = $decode_Response->HotelDetails;
            }
        }
        return $HotelDetails;
    }
    
    public function tboholidays_Hotel_Details($hotel_Code){
        $data = '{"Hotelcodes": '.$hotel_Code.',"Language": "EN"}';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.tbotechnology.in/TBOHolidays_HotelAPI/HotelDetails',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
        ));
        
        $response = curl_exec($curl);
        // echo $response;die();
        curl_close($curl);
        $HotelDetails       = '';
        $decode_Response    = json_decode($response);
        if($decode_Response->Status){
            if($decode_Response->Status->Description == 'Successful'){
                $HotelDetails = $decode_Response->HotelDetails;
            }
        }
        return $HotelDetails;
    }
    
    public function tboholidays_Search_Hotels_old(Request $req){
        $HotelResult        = [];
        $GuestNationality   = DB::table('tboHoliday_Country_List')->where('Name',$req->Country_Name)->first();
        $city_list          = DB::table('tboHoliday_City_List')->where('Name',$req->City_Name)->get();
        // return $city_list;
        // 126632
        // $encoded_hotel_Code = DB::table('tboHoliday_Hotel_Codes')->limit(1000)->get();
        $encoded_hotel_Code = DB::table('tboHoliday_Hotel_Codes')->where('CityId',$city_list[0]->Code)->get();
        $hotel_Codes        = json_decode($encoded_hotel_Code);
        // $hotel_Codes = $this->tboholidays_Hotel_Codes_OLD();
        // return $hotel_Codes;
        // London Hotel Code 1001425
        $count_HC           = count($hotel_Codes);
        // return $count_HC;
        // 307639
        if($count_HC > 0){
            for($x=0; $x<$count_HC; $x++){
                $hotel_Details = $this->tboholidays_Hotel_Details($hotel_Codes[$x]->HotelCodes);
                // $hotel_Details = $this->tboholidays_Hotel_Details('1001425');
                // return $hotel_Details[0]->CityId;
                if(isset($hotel_Details[0]->CityId) && $city_list[0]->Code == $hotel_Details[0]->CityId && $hotel_Details != ''){
                    $data='{
                        "CheckIn": "2024-03-10",
                        "CheckOut": "2024-03-15",
                        "HotelCodes": "'.$hotel_Details[0]->HotelCode.'",
                        "GuestNationality": "'.$GuestNationality->Code.'",
                        "PaxRooms": [
                            {
                                "Adults": 1,
                                "Children": 1,
                                "ChildrenAges": [ 1 ]
                            }
                        ],
                        "ResponseTime": 23.0,
                        "IsDetailedResponse": false,
                        "Filters": {
                            "Refundable": false,
                            "NoOfRooms": 0,
                            "MealType": "All"
                        }
                    }';
                    // return $data;
                    $curl       = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.tbotechnology.in/TBOHolidays_HotelAPI/Search',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>$data,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
                        ),
                    ));
                    $response   = curl_exec($curl);
                    // echo $response;die();
                    curl_close($curl);
                    $decode_Response = json_decode($response);
                    if(isset($decode_Response->Status)){
                        if($decode_Response->Status->Description == 'Successful'){
                            // return $decode_Response->HotelResult;
                            if(isset($decode_Response->HotelResult)){
                                $details = $decode_Response->HotelResult;
                                // return $details[0];
                                array_push($HotelResult,$details[0]);
                            }
                        }
                        // else{
                        //     echo $response;die();
                        //     return 'Something Went Wrong';
                        // }
                    }
                    // else{
                    //     return 'Something Went Wrong';
                    // }
                }
            }
        }
        // return count($HotelResult);
        return $HotelResult;
    }
    
    public function tboholidays_Search_Hotels_old_1(Request $req){
        $HotelResult        = [];
        $GuestNationality   = DB::table('tboHoliday_Country_List')->where('Name',$req->Country_Name)->first();
        $city_list          = DB::table('tboHoliday_City_List')->where('Name',$req->City_Name)->get();
        // return $city_list;
        foreach($city_list as $city){
            $encoded_hotel_Code = DB::table('tboHoliday_Hotel_Codes')->where('CityId', $city->Code)->limit(200)->get();
            // return count($encoded_hotel_Code);
            foreach($encoded_hotel_Code as $hotel){
                // return $hotel;
                try {
                    $hotel_Details = $this->tboholidays_Hotel_Details($hotel->HotelCodes);
                    if ($hotel_Details && isset($hotel_Details[0]->CityId) && $city->Code == $hotel_Details[0]->CityId) {
                        $data = [
                            "CheckIn" => "2024-03-10",
                            "CheckOut" => "2024-03-15",
                            "HotelCodes" => $hotel_Details[0]->HotelCode,
                            "GuestNationality" => $GuestNationality->Code,
                            "PaxRooms" => [
                                [
                                    "Adults" => 1,
                                    "Children" => 1,
                                    "ChildrenAges" => [1]
                                ]
                            ],
                            "ResponseTime" => 23.0,
                            "IsDetailedResponse" => false,
                            "Filters" => [
                                "Refundable" => false,
                                "NoOfRooms" => 0,
                                "MealType" => "All"
                            ]
                        ];
                        $response = Http::withHeaders([
                            'Content-Type'  => 'application/json',
                            'Authorization' => 'Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
                        ])->retry(3, 10000)->post('https://api.tbotechnology.in/TBOHolidays_HotelAPI/Search', $data);
                        // return $response;
                        $decode_Response = $response->json();
                        if (isset($decode_Response['Status']) && $decode_Response['Status']['Description'] == 'Successful') {
                            if (isset($decode_Response['HotelResult'])) {
                                $details = $decode_Response['HotelResult'];
                                array_push($HotelResult, $details[0]);
                            }
                        }
                    }
                } catch (Exception $e) {
                    // Handle the exception, you may log it for debugging purposes
                    Log::error('Error occurred while searching hotels: ' . $e->getMessage());
                }
            }
        }
        return $HotelResult;
    }

    public function tboholidays_Search_Hotels(Request $req){
        $HotelResult            = [];
        $GuestNationality       = DB::table('tboHoliday_Country_List')->where('Name', $req->Country_Name)->first();
        $city_list              = DB::table('tboHoliday_City_List')->where('Name', $req->City_Name)->get();
        $encoded_hotel_Codes    = DB::table('tboHoliday_Hotel_Codes')->where('CityId',$city_list[0]->Code)->pluck('HotelCodes')->toArray();
        // return $encoded_hotel_Codes;
        $chunked_hotel_Codes    = array_chunk($encoded_hotel_Codes, 10);
        // return count($chunked_hotel_Codes);
        $i = 1;
        foreach($chunked_hotel_Codes as $chunk){
            $i++;
            try {
                $data = [
                    "CheckIn" => "2024-03-10",
                    "CheckOut" => "2024-03-15",
                    "HotelCodes" => implode(',', $chunk), // Convert chunk to comma-separated string
                    "GuestNationality" => $GuestNationality->Code,
                    "PaxRooms" => [
                        [
                            "Adults" => 1,
                            "Children" => 1,
                            "ChildrenAges" => [1]
                        ]
                    ],
                    "ResponseTime" => 23.0,
                    "IsDetailedResponse" => false,
                    "Filters" => [
                        "Refundable" => false,
                        "NoOfRooms" => 0,
                        "MealType" => "All"
                    ]
                ];
                // return $data;
                $response = Http::withHeaders([
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
                ])->post('https://api.tbotechnology.in/TBOHolidays_HotelAPI/Search', $data);
                if ($response->successful()) {
                    $decode_Response = $response->json();
                    if (isset($decode_Response['Status']) && $decode_Response['Status']['Description'] == 'Successful') {
                        if (isset($decode_Response['HotelResult'])) {
                            $details = $decode_Response['HotelResult'];
                            foreach ($details as $detail) {
                                array_push($HotelResult, $detail);
                            }
                        }
                    }
                } else {
                    // Log unsuccessful response
                    Log::error('API request failed: ' . $response->status());
                }
            } catch (Exception $e) {
                // Handle the exception, you may log it for debugging purposes
                Log::error('Error occurred while searching hotels: ' . $e->getMessage());
            }
        }
        // return $i;
        return $HotelResult;
    }

    public function tboholidays_add_cities(Request $req){
        $city_list      = DB::table('tboHoliday_City_List')->get();
        $hotel_Codes    = DB::table('tboHoliday_Hotel_Codes')->where('id','>','55606')->whereNull('CityName')->get();
        // return $hotel_Codes;
        for($b=0; $b<count($hotel_Codes); $b++){
            $hotel_Details = $this->tboholidays_Hotel_Details($hotel_Codes[$b]->HotelCodes);
            if(isset($hotel_Details[0]->CityId)){
                DB::table('tboHoliday_Hotel_Codes')->where('HotelCodes',$hotel_Details[0]->HotelCode)->update([
                    'CityId'    => $hotel_Details[0]->CityId,
                    'CityName'  => $hotel_Details[0]->CityName,
                ]);
            }
        }
        
        // $checkpointID   = '1058384';
        // $batchSize      = 100;
        // DB::table('tboHoliday_Hotel_Codes')->orderBy('id')->where('id', '>', '32754')->whereNull('CityName')->where('HotelCodes', '>', $checkpointID)
        //     ->chunk($batchSize, function ($hotelCodes) use (&$checkpointID) {
        //     foreach ($hotelCodes as $hotelCode) {
        //         $hotelDetails = $this->tboholidays_Hotel_Details($hotelCode->HotelCodes);
        //         if (isset($hotelDetails[0]->CityId)) {
        //             DB::table('tboHoliday_Hotel_Codes')->where('HotelCodes', $hotelDetails[0]->HotelCode)
        //                 ->update([
        //                     'CityId'    => $hotelDetails[0]->CityId,
        //                     'CityName'  => $hotelDetails[0]->CityName,
        //                 ]);
        //             $checkpointID = $hotelDetails[0]->HotelCode;
        //         }
        //     }
        // });
        
        // return 'STOP';
        
        // $hotel_Codes = DB::table('tboHoliday_Hotel_Codes')->where('id','>',23571)->where('CityName',NULL)->get();
        // return $hotel_Codes;
        // DB::table('tboHoliday_Hotel_Codes')->where('id','>',23571)->where('CityName',NULL)->orderBy('id')->chunk(1000, function ($hotel_Codes) {
        //     $updates = [];
        //     foreach ($hotel_Codes as $hotel_Code) {
        //         $hotel_Details = $this->tboholidays_Hotel_Details($hotel_Code->HotelCodes);
        //         if (!empty($hotel_Details) && isset($hotel_Details[0]->CityId)) {
        //             $updates[] = [
        //                 'HotelCodes' => $hotel_Code->HotelCodes,
        //                 'CityId'     => $hotel_Details[0]->CityId,
        //                 'CityName'   => $hotel_Details[0]->CityName,
        //             ];
        //         }
        //     }
            
        //     if (!empty($updates)) {
        //         foreach ($updates as $update) {
        //             // Directly update rows based on HotelCodes
        //             DB::table('tboHoliday_Hotel_Codes')->where('HotelCodes', $update['HotelCodes'])->update([
        //                 'CityId'   => $update['CityId'],
        //                 'CityName' => $update['CityName'],
        //             ]);
        //         }
        //     }
        // });
        
    }
    
    public function tboholidays_PreBook($BookingCode){
        $details        = '';
        // $BookingCode    = $req->BookingCode;
        try {
            $data = [
                        "BookingCode"=> $BookingCode,
                        "PaymentMode"=> "Limit"
                    ];
            // return $data;
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ])->post('https://api.tbotechnology.in/TBOHolidays_HotelAPI/PreBook', $data);
            return $response;
            if ($response->successful()) {
                $decode_Response = $response->json();
                if (isset($decode_Response['Status']) && $decode_Response['Status']['Description'] == 'Successful') {
                    if (isset($decode_Response['HotelResult'])) {
                        $details = $decode_Response['HotelResult'];
                    }
                }
            } else {
                // Log unsuccessful response
                Log::error('API request failed: ' . $response->status());
            }
        } catch (Exception $e) {
            // Handle the exception, you may log it for debugging purposes
            Log::error('Error occurred while searching hotels: ' . $e->getMessage());
        }
        
        return $details;
    }
    
    public function tboholidays_book(Request $req){
        $ClientReferenceId  = 'CRID - '.random_int(1000000, 9999999);
        $BookingReferenceId = 'BID - '.random_int(1000000, 9999999);
        $data = '{
                    "BookingCode": "'.$req->BookingCode.'",
                    "CustomerDetails": [
                        {
                            "CustomerNames": [
                                {
                                    "Title": "Mr",
                                    "FirstName": "Usama",
                                    "LastName": "Ali",
                                    "Type": "Adult"
                                },
                                {
                                    "Title": "Mr",
                                    "FirstName": "Usama1",
                                    "LastName": "Ali1",
                                    "Type": "Adult"
                                }
                            ]
                        }
                    ],
                    "ClientReferenceId": "'.$ClientReferenceId.'",
                    "BookingReferenceId": "'.$BookingReferenceId.'",
                    "TotalFare": '.$req->TotalFare.',
                    "EmailId": "'.$req->EmailId.'",
                    "PhoneNumber": "'.$req->PhoneNumber.'",
                    "BookingType": "Voucher"
                }';
        // return $data;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/Book',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
        ));
        $response = curl_exec($curl);
        return $response;
        curl_close($curl);
    }
    
    public function tboholidays_BookingDetail(Request $req){
        $data = '{
                    "ConfirmationNumber": "'.$req->ConfirmationNumber.'",
                    "PaymentMode": "Limit"
                }';
        // return $data;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/BookingDetail',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
        ));
        $response = curl_exec($curl);
        return $response;
        curl_close($curl);
    }
    
    public function tboholidays_Cancel(Request $req){
        $data = '{
                    "ConfirmationNumber": "'.$req->ConfirmationNumber.'"
                }';
        // return $data;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/Cancel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
        ));
        $response = curl_exec($curl);
        return $response;
        curl_close($curl);
    }
    // 3rd Party Api
}
