<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;
use DB;
use App\Models\travellanda_get_hotel_detail;
use App\Models\travellanda_get_hotel;
class Hotelbeds_MetaDataController extends Controller
{
    
    public function get_countries()
    {


// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/locations/countries?fields=all&language=ENG&from=1&to=203',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
//     'X-Signature: 440503bc01c55ba911b5c201c134896ee57896216810de92f8039d6a64eafb10',
//     'Accept: application/json',
//     'Accept-Encoding: gzip'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// print_r(json_decode($response));

            
    }
    
    
    
    public function get_hotel()
    {
      $apiKey = '833583586f0fd4fccd3757cd8a57c0a8';
        $secret = 'ae2dc887d0';
        $signature = hash("sha256", $apiKey.$secret.time());


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=UY&language=ENG&from=1&to=303',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$res_data=json_decode($response);
// print_r($res_data);
// die();

    foreach($res_data->hotels as $hotels)
      {
          
         
         
         $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
                 'code'=>$hotels->code,
                 'name'=>$hotels->name->content,
                 'description'=>$hotels->description->content ?? '',
                 'countryCode'=>$hotels->countryCode ?? '',
                 'stateCode'=>$hotels->stateCode ?? '',
                 'destinationCode'=>$hotels->destinationCode ?? '',
                 'zoneCode'=>$hotels->zoneCode ?? '',
                 'longitude'=>$hotels->coordinates->longitude,
                 'latitude'=>$hotels->coordinates->latitude,
                 'categoryCode'=>$hotels->categoryCode ?? '',
                 'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
                 'chainCode'=>$hotels->chainCode ?? '',
                 'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
                 'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
                 'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
                 'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
                 'address'=>$hotels->address->content ?? '',
                 'postalCode'=>$hotels->postalCode ?? '',
                 'city'=>$hotels->city->content ?? '',
                 'email'=>$hotels->email ?? '',
                 'phones'=>json_encode($hotels->phones ?? ''),
                 'rooms'=>json_encode($hotels->rooms ?? ''),
                 'facilities'=>json_encode($hotels->facilities ?? ''),
                 'issues'=>json_encode($hotels->issues ?? ''),
                 'images'=>json_encode($hotels->images ?? ''),
                 'web'=>$hotels->web ?? '',
                 'lastUpdate'=>$hotels->lastUpdate ?? '',
                 'S2C'=>$hotels->S2C ?? '',
                 'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
                 ]);
         
         
     
      }
      
      //2
      $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=UZ&language=ENG&from=1&to=303',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$res_data=json_decode($response);
// print_r($res_data);
// die();

    foreach($res_data->hotels as $hotels)
      {
          
         
         
         $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
                 'code'=>$hotels->code,
                 'name'=>$hotels->name->content,
                 'description'=>$hotels->description->content ?? '',
                 'countryCode'=>$hotels->countryCode ?? '',
                 'stateCode'=>$hotels->stateCode ?? '',
                 'destinationCode'=>$hotels->destinationCode ?? '',
                 'zoneCode'=>$hotels->zoneCode ?? '',
                 'longitude'=>$hotels->coordinates->longitude,
                 'latitude'=>$hotels->coordinates->latitude,
                 'categoryCode'=>$hotels->categoryCode ?? '',
                 'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
                 'chainCode'=>$hotels->chainCode ?? '',
                 'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
                 'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
                 'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
                 'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
                 'address'=>$hotels->address->content ?? '',
                 'postalCode'=>$hotels->postalCode ?? '',
                 'city'=>$hotels->city->content ?? '',
                 'email'=>$hotels->email ?? '',
                 'phones'=>json_encode($hotels->phones ?? ''),
                 'rooms'=>json_encode($hotels->rooms ?? ''),
                 'facilities'=>json_encode($hotels->facilities ?? ''),
                 'issues'=>json_encode($hotels->issues ?? ''),
                 'images'=>json_encode($hotels->images ?? ''),
                 'web'=>$hotels->web ?? '',
                 'lastUpdate'=>$hotels->lastUpdate ?? '',
                 'S2C'=>$hotels->S2C ?? '',
                 'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
                 ]);
         
         
     
      }
      //3
      $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VC&language=ENG&from=1&to=303',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$res_data=json_decode($response);
// print_r($res_data);
// die();

    foreach($res_data->hotels as $hotels)
      {
          
         
         
         $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
                 'code'=>$hotels->code,
                 'name'=>$hotels->name->content,
                 'description'=>$hotels->description->content ?? '',
                 'countryCode'=>$hotels->countryCode ?? '',
                 'stateCode'=>$hotels->stateCode ?? '',
                 'destinationCode'=>$hotels->destinationCode ?? '',
                 'zoneCode'=>$hotels->zoneCode ?? '',
                 'longitude'=>$hotels->coordinates->longitude,
                 'latitude'=>$hotels->coordinates->latitude,
                 'categoryCode'=>$hotels->categoryCode ?? '',
                 'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
                 'chainCode'=>$hotels->chainCode ?? '',
                 'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
                 'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
                 'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
                 'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
                 'address'=>$hotels->address->content ?? '',
                 'postalCode'=>$hotels->postalCode ?? '',
                 'city'=>$hotels->city->content ?? '',
                 'email'=>$hotels->email ?? '',
                 'phones'=>json_encode($hotels->phones ?? ''),
                 'rooms'=>json_encode($hotels->rooms ?? ''),
                 'facilities'=>json_encode($hotels->facilities ?? ''),
                 'issues'=>json_encode($hotels->issues ?? ''),
                 'images'=>json_encode($hotels->images ?? ''),
                 'web'=>$hotels->web ?? '',
                 'lastUpdate'=>$hotels->lastUpdate ?? '',
                 'S2C'=>$hotels->S2C ?? '',
                 'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
                 ]);
         
         
     
      }
      //4
      $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VE&language=ENG&from=1&to=303',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$res_data=json_decode($response);
// print_r($res_data);
// die();

    foreach($res_data->hotels as $hotels)
      {
          
         
         
         $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
                 'code'=>$hotels->code,
                 'name'=>$hotels->name->content,
                 'description'=>$hotels->description->content ?? '',
                 'countryCode'=>$hotels->countryCode ?? '',
                 'stateCode'=>$hotels->stateCode ?? '',
                 'destinationCode'=>$hotels->destinationCode ?? '',
                 'zoneCode'=>$hotels->zoneCode ?? '',
                 'longitude'=>$hotels->coordinates->longitude,
                 'latitude'=>$hotels->coordinates->latitude,
                 'categoryCode'=>$hotels->categoryCode ?? '',
                 'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
                 'chainCode'=>$hotels->chainCode ?? '',
                 'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
                 'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
                 'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
                 'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
                 'address'=>$hotels->address->content ?? '',
                 'postalCode'=>$hotels->postalCode ?? '',
                 'city'=>$hotels->city->content ?? '',
                 'email'=>$hotels->email ?? '',
                 'phones'=>json_encode($hotels->phones ?? ''),
                 'rooms'=>json_encode($hotels->rooms ?? ''),
                 'facilities'=>json_encode($hotels->facilities ?? ''),
                 'issues'=>json_encode($hotels->issues ?? ''),
                 'images'=>json_encode($hotels->images ?? ''),
                 'web'=>$hotels->web ?? '',
                 'lastUpdate'=>$hotels->lastUpdate ?? '',
                 'S2C'=>$hotels->S2C ?? '',
                 'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
                 ]);
         
         
     
      }
      //5
      $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VG&language=ENG&from=1&to=303',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$res_data=json_decode($response);
// print_r($res_data);
// die();

    foreach($res_data->hotels as $hotels)
      {
          
         
         
         $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
                 'code'=>$hotels->code,
                 'name'=>$hotels->name->content,
                 'description'=>$hotels->description->content ?? '',
                 'countryCode'=>$hotels->countryCode ?? '',
                 'stateCode'=>$hotels->stateCode ?? '',
                 'destinationCode'=>$hotels->destinationCode ?? '',
                 'zoneCode'=>$hotels->zoneCode ?? '',
                 'longitude'=>$hotels->coordinates->longitude,
                 'latitude'=>$hotels->coordinates->latitude,
                 'categoryCode'=>$hotels->categoryCode ?? '',
                 'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
                 'chainCode'=>$hotels->chainCode ?? '',
                 'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
                 'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
                 'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
                 'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
                 'address'=>$hotels->address->content ?? '',
                 'postalCode'=>$hotels->postalCode ?? '',
                 'city'=>$hotels->city->content ?? '',
                 'email'=>$hotels->email ?? '',
                 'phones'=>json_encode($hotels->phones ?? ''),
                 'rooms'=>json_encode($hotels->rooms ?? ''),
                 'facilities'=>json_encode($hotels->facilities ?? ''),
                 'issues'=>json_encode($hotels->issues ?? ''),
                 'images'=>json_encode($hotels->images ?? ''),
                 'web'=>$hotels->web ?? '',
                 'lastUpdate'=>$hotels->lastUpdate ?? '',
                 'S2C'=>$hotels->S2C ?? '',
                 'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
                 ]);
         
         
     
      }
      //6
      $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VI&language=ENG&from=1&to=303',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$res_data=json_decode($response);
// print_r($res_data);
// die();

    foreach($res_data->hotels as $hotels)
      {
          
         
         
         $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
                 'code'=>$hotels->code,
                 'name'=>$hotels->name->content,
                 'description'=>$hotels->description->content ?? '',
                 'countryCode'=>$hotels->countryCode ?? '',
                 'stateCode'=>$hotels->stateCode ?? '',
                 'destinationCode'=>$hotels->destinationCode ?? '',
                 'zoneCode'=>$hotels->zoneCode ?? '',
                 'longitude'=>$hotels->coordinates->longitude,
                 'latitude'=>$hotels->coordinates->latitude,
                 'categoryCode'=>$hotels->categoryCode ?? '',
                 'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
                 'chainCode'=>$hotels->chainCode ?? '',
                 'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
                 'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
                 'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
                 'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
                 'address'=>$hotels->address->content ?? '',
                 'postalCode'=>$hotels->postalCode ?? '',
                 'city'=>$hotels->city->content ?? '',
                 'email'=>$hotels->email ?? '',
                 'phones'=>json_encode($hotels->phones ?? ''),
                 'rooms'=>json_encode($hotels->rooms ?? ''),
                 'facilities'=>json_encode($hotels->facilities ?? ''),
                 'issues'=>json_encode($hotels->issues ?? ''),
                 'images'=>json_encode($hotels->images ?? ''),
                 'web'=>$hotels->web ?? '',
                 'lastUpdate'=>$hotels->lastUpdate ?? '',
                 'S2C'=>$hotels->S2C ?? '',
                 'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
                 ]);
         
         
     
      }
      //7
      $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VN&language=ENG&from=1&to=303',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$res_data=json_decode($response);
// print_r($res_data);
// die();

    foreach($res_data->hotels as $hotels)
      {
          
         
         
         $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
                 'code'=>$hotels->code,
                 'name'=>$hotels->name->content,
                 'description'=>$hotels->description->content ?? '',
                 'countryCode'=>$hotels->countryCode ?? '',
                 'stateCode'=>$hotels->stateCode ?? '',
                 'destinationCode'=>$hotels->destinationCode ?? '',
                 'zoneCode'=>$hotels->zoneCode ?? '',
                 'longitude'=>$hotels->coordinates->longitude,
                 'latitude'=>$hotels->coordinates->latitude,
                 'categoryCode'=>$hotels->categoryCode ?? '',
                 'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
                 'chainCode'=>$hotels->chainCode ?? '',
                 'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
                 'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
                 'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
                 'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
                 'address'=>$hotels->address->content ?? '',
                 'postalCode'=>$hotels->postalCode ?? '',
                 'city'=>$hotels->city->content ?? '',
                 'email'=>$hotels->email ?? '',
                 'phones'=>json_encode($hotels->phones ?? ''),
                 'rooms'=>json_encode($hotels->rooms ?? ''),
                 'facilities'=>json_encode($hotels->facilities ?? ''),
                 'issues'=>json_encode($hotels->issues ?? ''),
                 'images'=>json_encode($hotels->images ?? ''),
                 'web'=>$hotels->web ?? '',
                 'lastUpdate'=>$hotels->lastUpdate ?? '',
                 'S2C'=>$hotels->S2C ?? '',
                 'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
                 ]);
         
         
     
      }
      //8
      $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&countryCode=VU&language=ENG&from=1&to=303',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$res_data=json_decode($response);
// print_r($res_data);
// die();

    foreach($res_data->hotels as $hotels)
      {
          
         
         
         $data=DB::table('hotel_beds_hotel_details')->insert([
             
             
                 'code'=>$hotels->code,
                 'name'=>$hotels->name->content,
                 'description'=>$hotels->description->content ?? '',
                 'countryCode'=>$hotels->countryCode ?? '',
                 'stateCode'=>$hotels->stateCode ?? '',
                 'destinationCode'=>$hotels->destinationCode ?? '',
                 'zoneCode'=>$hotels->zoneCode ?? '',
                 'longitude'=>$hotels->coordinates->longitude,
                 'latitude'=>$hotels->coordinates->latitude,
                 'categoryCode'=>$hotels->categoryCode ?? '',
                 'categoryGroupCode'=>$hotels->categoryGroupCode ?? '',
                 'chainCode'=>$hotels->chainCode ?? '',
                 'accommodationTypeCode'=>$hotels->accommodationTypeCode ?? '',
                 'boardCodes'=>json_encode($hotels->boardCodes ?? ''),
                 'segmentCodes'=>json_encode($hotels->segmentCodes ?? ''),
                 'amenityCodes'=>json_encode($hotels->amenityCodes ?? ''),
                 'address'=>$hotels->address->content ?? '',
                 'postalCode'=>$hotels->postalCode ?? '',
                 'city'=>$hotels->city->content ?? '',
                 'email'=>$hotels->email ?? '',
                 'phones'=>json_encode($hotels->phones ?? ''),
                 'rooms'=>json_encode($hotels->rooms ?? ''),
                 'facilities'=>json_encode($hotels->facilities ?? ''),
                 'issues'=>json_encode($hotels->issues ?? ''),
                 'images'=>json_encode($hotels->images ?? ''),
                 'web'=>$hotels->web ?? '',
                 'lastUpdate'=>$hotels->lastUpdate ?? '',
                 'S2C'=>$hotels->S2C ?? '',
                 'ranking'=>$hotels->ranking ?? '',
                 
                 
                 
                 ]);
         
         
     
      }
      
      



    }
    


}
