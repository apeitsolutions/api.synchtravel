<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;
use DB;
use App\Models\travellanda_get_hotel_detail;
use App\Models\travellanda_get_hotel;
class TBOHolidays_HotelAPI_Controller extends Controller
{
    
    
    
   
    
    
    
    public function tboholidays_get_countries()
    {
       
//       $tboholidays_countries=DB::table('tboholidays_countries')->get(); 
       
        
//      $data='{
// ';
// foreach($tboholidays_countries as $tboholidays_countries)

// {
//   $data.=' 
//     "query": "'.$tboholidays_countries->Code.'",
//     '; 
    
//      }   
//   $data.=' 
//     "language": "en"
// }';
// // print_r($data);die();
// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/search/multicomplete/',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS =>$data,
//   CURLOPT_HTTPHEADER => array(
//     'Content-Type: application/json',
//     'Authorization: Basic NDM4MTo3NjJkNWY4MS02Y2YyLTRlYTItOWUyZC0wZTljY2QwODZhYzI=',
//     'Cookie: uid=TfTb5mL8zLd3KD3ZBK7FAg=='
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;

// print_r(json_decode($response));
// die();


//      $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/hotelcodelist',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Content-Type: application/json',
//     'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
//   ),
// ));

// $response = curl_exec($curl);
// // echo $response;die();
// curl_close($curl);
// $res_data=json_decode($response);
// print_r($res_data);
// die();   
        
        
        
        $data='{
 "CheckIn": "2022-09-05",
 "CheckOut": "2022-09-20",
  "HotelCodes": "1247101,1247102,1247103,124714",
 "GuestNationality": "AE",
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
 "NoOfRooms": 100,
 "MealType": "All"
}
';
                 
                  //print_r($data);die();
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/Search',
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
// echo $response;
// die();
curl_close($curl);
$res_data=json_decode($response);

print_r(json_decode($response));
die();


//     foreach($res_data->CountryList as $CountryList)
//       {
          
         
         
//          $data=DB::table('tboholidays_countries')->insert([
             
//                  'Code'=>$CountryList->Code,
//                  'Name'=>$CountryList->Name,
                
//                  ]);
         
         
     
//       }
            
    }
    
    
    
public function tboholidays_get_cities()
    {
        
$data='{
"CountryCode":"VU"
 }';

// print_r($data);die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
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
// echo $response;
// die();
curl_close($curl);
$res_data=json_decode($response);

// print_r(json_decode($response));
// die();

foreach($res_data->CityList as $CityList)
      {
          
         
         
         $data=DB::table('tboholidays_cities')->insert([
             
                 'Code'=>$CityList->Code,
                 'Name'=>$CityList->Name,
                
                 ]);
         
         
     
      }
      //2
      $data='{
"CountryCode":"VE"
 }';

// print_r($data);die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
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
// echo $response;
// die();
curl_close($curl);
$res_data=json_decode($response);

// print_r(json_decode($response));
// die();

foreach($res_data->CityList as $CityList)
      {
          
         
         
         $data=DB::table('tboholidays_cities')->insert([
             
                 'Code'=>$CityList->Code,
                 'Name'=>$CityList->Name,
                
                 ]);
         
         
     
      }
//3
$data='{
"CountryCode":"VN"
 }';

// print_r($data);die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
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
// echo $response;
// die();
curl_close($curl);
$res_data=json_decode($response);

// print_r(json_decode($response));
// die();

foreach($res_data->CityList as $CityList)
      {
          
         
         
         $data=DB::table('tboholidays_cities')->insert([
             
                 'Code'=>$CityList->Code,
                 'Name'=>$CityList->Name,
                
                 ]);
         
         
     
      }
//4
$data='{
"CountryCode":"VI"
 }';

// print_r($data);die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
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
// echo $response;
// die();
curl_close($curl);
$res_data=json_decode($response);

// print_r(json_decode($response));
// die();

foreach($res_data->CityList as $CityList)
      {
          
         
         
         $data=DB::table('tboholidays_cities')->insert([
             
                 'Code'=>$CityList->Code,
                 'Name'=>$CityList->Name,
                
                 ]);
         
         
     
      }
//5
$data='{
"CountryCode":"YE"
 }';

// print_r($data);die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
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
// echo $response;
// die();
curl_close($curl);
$res_data=json_decode($response);

// print_r(json_decode($response));
// die();

foreach($res_data->CityList as $CityList)
      {
          
         
         
         $data=DB::table('tboholidays_cities')->insert([
             
                 'Code'=>$CityList->Code,
                 'Name'=>$CityList->Name,
                
                 ]);
         
         
     
      }
//6
$data='{
"CountryCode":"ZM"
 }';

// print_r($data);die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
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
// echo $response;
// die();
curl_close($curl);
$res_data=json_decode($response);

// print_r(json_decode($response));
// die();

foreach($res_data->CityList as $CityList)
      {
          
         
         
         $data=DB::table('tboholidays_cities')->insert([
             
                 'Code'=>$CityList->Code,
                 'Name'=>$CityList->Name,
                
                 ]);
         
         
     
      }
//7
$data='{
"CountryCode":"ZW"
 }';

// print_r($data);die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
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
// echo $response;
// die();
curl_close($curl);
$res_data=json_decode($response);

// print_r(json_decode($response));
// die();

foreach($res_data->CityList as $CityList)
      {
          
         
         
         $data=DB::table('tboholidays_cities')->insert([
             
                 'Code'=>$CityList->Code,
                 'Name'=>$CityList->Name,
                
                 ]);
         
         
     
      }


    }
    
    
    
    
    
    public function get_hotel()
    {
      $apiKey = '833583586f0fd4fccd3757cd8a57c0a8';
        $secret = 'ae2dc887d0';
        $signature = hash("sha256", $apiKey.$secret.time());


// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=UY&language=ENG&from=1&to=303',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
//     "X-Signature: $signature",
//     'Accept: application/json',
//     'Accept-Encoding: gzip'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// $res_data=json_decode($response);
// // print_r($res_data);
// // die();

//     foreach($res_data->hotels as $hotels)
//       {
          
         
         
//          $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
//                  'code'=>$hotels->code,
//                  'name'=>$hotels->name->content,
//                  'description'=>$hotels->description->content ?? '',
//                  'countryCode'=>$hotels->countryCode ?? '',
//                  'stateCode'=>$hotels->stateCode ?? '',
//                  'destinationCode'=>$hotels->destinationCode ?? '',
//                  'zoneCode'=>$hotels->zoneCode ?? '',
//                  'longitude'=>$hotels->coordinates->longitude,
//                  'latitude'=>$hotels->coordinates->latitude,
//                  'categoryCode'=>$hotels->categoryCode ?? '',
//                  'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
//                  'chainCode'=>$hotels->chainCode ?? '',
//                  'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
//                  'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
//                  'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
//                  'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
//                  'address'=>$hotels->address->content ?? '',
//                  'postalCode'=>$hotels->postalCode ?? '',
//                  'city'=>$hotels->city->content ?? '',
//                  'email'=>$hotels->email ?? '',
//                  'phones'=>json_encode($hotels->phones ?? ''),
//                  'rooms'=>json_encode($hotels->rooms ?? ''),
//                  'facilities'=>json_encode($hotels->facilities ?? ''),
//                  'issues'=>json_encode($hotels->issues ?? ''),
//                  'images'=>json_encode($hotels->images ?? ''),
//                  'web'=>$hotels->web ?? '',
//                  'lastUpdate'=>$hotels->lastUpdate ?? '',
//                  'S2C'=>$hotels->S2C ?? '',
//                  'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
//                  ]);
         
         
     
//       }
      
//       //2
//       $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=UZ&language=ENG&from=1&to=303',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
//     "X-Signature: $signature",
//     'Accept: application/json',
//     'Accept-Encoding: gzip'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// $res_data=json_decode($response);
// // print_r($res_data);
// // die();

//     foreach($res_data->hotels as $hotels)
//       {
          
         
         
//          $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
//                  'code'=>$hotels->code,
//                  'name'=>$hotels->name->content,
//                  'description'=>$hotels->description->content ?? '',
//                  'countryCode'=>$hotels->countryCode ?? '',
//                  'stateCode'=>$hotels->stateCode ?? '',
//                  'destinationCode'=>$hotels->destinationCode ?? '',
//                  'zoneCode'=>$hotels->zoneCode ?? '',
//                  'longitude'=>$hotels->coordinates->longitude,
//                  'latitude'=>$hotels->coordinates->latitude,
//                  'categoryCode'=>$hotels->categoryCode ?? '',
//                  'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
//                  'chainCode'=>$hotels->chainCode ?? '',
//                  'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
//                  'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
//                  'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
//                  'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
//                  'address'=>$hotels->address->content ?? '',
//                  'postalCode'=>$hotels->postalCode ?? '',
//                  'city'=>$hotels->city->content ?? '',
//                  'email'=>$hotels->email ?? '',
//                  'phones'=>json_encode($hotels->phones ?? ''),
//                  'rooms'=>json_encode($hotels->rooms ?? ''),
//                  'facilities'=>json_encode($hotels->facilities ?? ''),
//                  'issues'=>json_encode($hotels->issues ?? ''),
//                  'images'=>json_encode($hotels->images ?? ''),
//                  'web'=>$hotels->web ?? '',
//                  'lastUpdate'=>$hotels->lastUpdate ?? '',
//                  'S2C'=>$hotels->S2C ?? '',
//                  'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
//                  ]);
         
         
     
//       }
//       //3
//       $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VC&language=ENG&from=1&to=303',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
//     "X-Signature: $signature",
//     'Accept: application/json',
//     'Accept-Encoding: gzip'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// $res_data=json_decode($response);
// // print_r($res_data);
// // die();

//     foreach($res_data->hotels as $hotels)
//       {
          
         
         
//          $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
//                  'code'=>$hotels->code,
//                  'name'=>$hotels->name->content,
//                  'description'=>$hotels->description->content ?? '',
//                  'countryCode'=>$hotels->countryCode ?? '',
//                  'stateCode'=>$hotels->stateCode ?? '',
//                  'destinationCode'=>$hotels->destinationCode ?? '',
//                  'zoneCode'=>$hotels->zoneCode ?? '',
//                  'longitude'=>$hotels->coordinates->longitude,
//                  'latitude'=>$hotels->coordinates->latitude,
//                  'categoryCode'=>$hotels->categoryCode ?? '',
//                  'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
//                  'chainCode'=>$hotels->chainCode ?? '',
//                  'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
//                  'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
//                  'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
//                  'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
//                  'address'=>$hotels->address->content ?? '',
//                  'postalCode'=>$hotels->postalCode ?? '',
//                  'city'=>$hotels->city->content ?? '',
//                  'email'=>$hotels->email ?? '',
//                  'phones'=>json_encode($hotels->phones ?? ''),
//                  'rooms'=>json_encode($hotels->rooms ?? ''),
//                  'facilities'=>json_encode($hotels->facilities ?? ''),
//                  'issues'=>json_encode($hotels->issues ?? ''),
//                  'images'=>json_encode($hotels->images ?? ''),
//                  'web'=>$hotels->web ?? '',
//                  'lastUpdate'=>$hotels->lastUpdate ?? '',
//                  'S2C'=>$hotels->S2C ?? '',
//                  'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
//                  ]);
         
         
     
//       }
//       //4
//       $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VE&language=ENG&from=1&to=303',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
//     "X-Signature: $signature",
//     'Accept: application/json',
//     'Accept-Encoding: gzip'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// $res_data=json_decode($response);
// // print_r($res_data);
// // die();

//     foreach($res_data->hotels as $hotels)
//       {
          
         
         
//          $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
//                  'code'=>$hotels->code,
//                  'name'=>$hotels->name->content,
//                  'description'=>$hotels->description->content ?? '',
//                  'countryCode'=>$hotels->countryCode ?? '',
//                  'stateCode'=>$hotels->stateCode ?? '',
//                  'destinationCode'=>$hotels->destinationCode ?? '',
//                  'zoneCode'=>$hotels->zoneCode ?? '',
//                  'longitude'=>$hotels->coordinates->longitude,
//                  'latitude'=>$hotels->coordinates->latitude,
//                  'categoryCode'=>$hotels->categoryCode ?? '',
//                  'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
//                  'chainCode'=>$hotels->chainCode ?? '',
//                  'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
//                  'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
//                  'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
//                  'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
//                  'address'=>$hotels->address->content ?? '',
//                  'postalCode'=>$hotels->postalCode ?? '',
//                  'city'=>$hotels->city->content ?? '',
//                  'email'=>$hotels->email ?? '',
//                  'phones'=>json_encode($hotels->phones ?? ''),
//                  'rooms'=>json_encode($hotels->rooms ?? ''),
//                  'facilities'=>json_encode($hotels->facilities ?? ''),
//                  'issues'=>json_encode($hotels->issues ?? ''),
//                  'images'=>json_encode($hotels->images ?? ''),
//                  'web'=>$hotels->web ?? '',
//                  'lastUpdate'=>$hotels->lastUpdate ?? '',
//                  'S2C'=>$hotels->S2C ?? '',
//                  'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
//                  ]);
         
         
     
//       }
//       //5
//       $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VG&language=ENG&from=1&to=303',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
//     "X-Signature: $signature",
//     'Accept: application/json',
//     'Accept-Encoding: gzip'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// $res_data=json_decode($response);
// // print_r($res_data);
// // die();

//     foreach($res_data->hotels as $hotels)
//       {
          
         
         
//          $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
//                  'code'=>$hotels->code,
//                  'name'=>$hotels->name->content,
//                  'description'=>$hotels->description->content ?? '',
//                  'countryCode'=>$hotels->countryCode ?? '',
//                  'stateCode'=>$hotels->stateCode ?? '',
//                  'destinationCode'=>$hotels->destinationCode ?? '',
//                  'zoneCode'=>$hotels->zoneCode ?? '',
//                  'longitude'=>$hotels->coordinates->longitude,
//                  'latitude'=>$hotels->coordinates->latitude,
//                  'categoryCode'=>$hotels->categoryCode ?? '',
//                  'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
//                  'chainCode'=>$hotels->chainCode ?? '',
//                  'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
//                  'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
//                  'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
//                  'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
//                  'address'=>$hotels->address->content ?? '',
//                  'postalCode'=>$hotels->postalCode ?? '',
//                  'city'=>$hotels->city->content ?? '',
//                  'email'=>$hotels->email ?? '',
//                  'phones'=>json_encode($hotels->phones ?? ''),
//                  'rooms'=>json_encode($hotels->rooms ?? ''),
//                  'facilities'=>json_encode($hotels->facilities ?? ''),
//                  'issues'=>json_encode($hotels->issues ?? ''),
//                  'images'=>json_encode($hotels->images ?? ''),
//                  'web'=>$hotels->web ?? '',
//                  'lastUpdate'=>$hotels->lastUpdate ?? '',
//                  'S2C'=>$hotels->S2C ?? '',
//                  'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
//                  ]);
         
         
     
//       }
//       //6
//       $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VI&language=ENG&from=1&to=303',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
//     "X-Signature: $signature",
//     'Accept: application/json',
//     'Accept-Encoding: gzip'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// $res_data=json_decode($response);
// // print_r($res_data);
// // die();

//     foreach($res_data->hotels as $hotels)
//       {
          
         
         
//          $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
//                  'code'=>$hotels->code,
//                  'name'=>$hotels->name->content,
//                  'description'=>$hotels->description->content ?? '',
//                  'countryCode'=>$hotels->countryCode ?? '',
//                  'stateCode'=>$hotels->stateCode ?? '',
//                  'destinationCode'=>$hotels->destinationCode ?? '',
//                  'zoneCode'=>$hotels->zoneCode ?? '',
//                  'longitude'=>$hotels->coordinates->longitude,
//                  'latitude'=>$hotels->coordinates->latitude,
//                  'categoryCode'=>$hotels->categoryCode ?? '',
//                  'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
//                  'chainCode'=>$hotels->chainCode ?? '',
//                  'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
//                  'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
//                  'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
//                  'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
//                  'address'=>$hotels->address->content ?? '',
//                  'postalCode'=>$hotels->postalCode ?? '',
//                  'city'=>$hotels->city->content ?? '',
//                  'email'=>$hotels->email ?? '',
//                  'phones'=>json_encode($hotels->phones ?? ''),
//                  'rooms'=>json_encode($hotels->rooms ?? ''),
//                  'facilities'=>json_encode($hotels->facilities ?? ''),
//                  'issues'=>json_encode($hotels->issues ?? ''),
//                  'images'=>json_encode($hotels->images ?? ''),
//                  'web'=>$hotels->web ?? '',
//                  'lastUpdate'=>$hotels->lastUpdate ?? '',
//                  'S2C'=>$hotels->S2C ?? '',
//                  'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
//                  ]);
         
         
     
//       }
//       //7
//       $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VN&language=ENG&from=1&to=303',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
//     "X-Signature: $signature",
//     'Accept: application/json',
//     'Accept-Encoding: gzip'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// $res_data=json_decode($response);
// // print_r($res_data);
// // die();

//     foreach($res_data->hotels as $hotels)
//       {
          
         
         
//          $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
//                  'code'=>$hotels->code,
//                  'name'=>$hotels->name->content,
//                  'description'=>$hotels->description->content ?? '',
//                  'countryCode'=>$hotels->countryCode ?? '',
//                  'stateCode'=>$hotels->stateCode ?? '',
//                  'destinationCode'=>$hotels->destinationCode ?? '',
//                  'zoneCode'=>$hotels->zoneCode ?? '',
//                  'longitude'=>$hotels->coordinates->longitude,
//                  'latitude'=>$hotels->coordinates->latitude,
//                  'categoryCode'=>$hotels->categoryCode ?? '',
//                  'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
//                  'chainCode'=>$hotels->chainCode ?? '',
//                  'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
//                  'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
//                  'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
//                  'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
//                  'address'=>$hotels->address->content ?? '',
//                  'postalCode'=>$hotels->postalCode ?? '',
//                  'city'=>$hotels->city->content ?? '',
//                  'email'=>$hotels->email ?? '',
//                  'phones'=>json_encode($hotels->phones ?? ''),
//                  'rooms'=>json_encode($hotels->rooms ?? ''),
//                  'facilities'=>json_encode($hotels->facilities ?? ''),
//                  'issues'=>json_encode($hotels->issues ?? ''),
//                  'images'=>json_encode($hotels->images ?? ''),
//                  'web'=>$hotels->web ?? '',
//                  'lastUpdate'=>$hotels->lastUpdate ?? '',
//                  'S2C'=>$hotels->S2C ?? '',
//                  'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
//                  ]);
         
         
     
//       }
//       //8
//       $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VU&language=ENG&from=1&to=303',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
//     "X-Signature: $signature",
//     'Accept: application/json',
//     'Accept-Encoding: gzip'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// $res_data=json_decode($response);
// // print_r($res_data);
// // die();

//     foreach($res_data->hotels as $hotels)
//       {
          
         
         
//          $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
//                  'code'=>$hotels->code,
//                  'name'=>$hotels->name->content,
//                  'description'=>$hotels->description->content ?? '',
//                  'countryCode'=>$hotels->countryCode ?? '',
//                  'stateCode'=>$hotels->stateCode ?? '',
//                  'destinationCode'=>$hotels->destinationCode ?? '',
//                  'zoneCode'=>$hotels->zoneCode ?? '',
//                  'longitude'=>$hotels->coordinates->longitude,
//                  'latitude'=>$hotels->coordinates->latitude,
//                  'categoryCode'=>$hotels->categoryCode ?? '',
//                  'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
//                  'chainCode'=>$hotels->chainCode ?? '',
//                  'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
//                  'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
//                  'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
//                  'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
//                  'address'=>$hotels->address->content ?? '',
//                  'postalCode'=>$hotels->postalCode ?? '',
//                  'city'=>$hotels->city->content ?? '',
//                  'email'=>$hotels->email ?? '',
//                  'phones'=>json_encode($hotels->phones ?? ''),
//                  'rooms'=>json_encode($hotels->rooms ?? ''),
//                  'facilities'=>json_encode($hotels->facilities ?? ''),
//                  'issues'=>json_encode($hotels->issues ?? ''),
//                  'images'=>json_encode($hotels->images ?? ''),
//                  'web'=>$hotels->web ?? '',
//                  'lastUpdate'=>$hotels->lastUpdate ?? '',
//                  'S2C'=>$hotels->S2C ?? '',
//                  'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
//                  ]);
         
         
     
//       }
      
      



    }
    


}
