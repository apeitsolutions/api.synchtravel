<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;
use DB;
use App\Models\travellanda_get_hotel_detail;
use App\Models\travellanda_get_hotel;
class Travellanda_MetaDataController extends Controller
{
    
    public function get_cities()
    {
        
        
// $code= DB::table('tbo_hotel_codes')->select('hotel_code')->where('id' , '>', 221)->get();
$code= DB::table('tbo_hotel_codes')->select('hotel_code')->skip(221)->take(10)->get();
 
foreach($code as $res_tbo_code)
{
  $hotel_codes[]=$res_tbo_code->hotel_code; 
}

// print_r($hotel_codes);die();
$hotel_codes_en=json_encode($hotel_codes); 
$json = preg_replace('/["]/', '' ,$hotel_codes_en);

  $hotel_codes_trim=trim($json, '[]');
  


    $data= '{
      "Hotelcodes":"'.$hotel_codes_trim.'",
        "Language": "EN"
       }';
  

//   echo $data; die();


   $curl = curl_init();
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

$response = curl_exec($curl);
$hotelData= json_decode($response);
 print_r($hotelData); die();



        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    //     $data_request="<Request>
    //             <Head>
    //           <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
    //             <Password>rY8qm76g5dGH</Password>
    //             <RequestType>GetCities</RequestType>
    //             </Head>
    //             <Body>
    //             </Body>
    //         </Request>";
            
            
    //         $url = "http://xmldemo.travellanda.com/xmlv1/GetCitiesRequest.xsd";
    //         $timeout = 20;
    //         $data = array('xml' => $data_request);
    //         $headers = array(
    //         "Content-type: application/x-www-form-urlencoded",
    //     );
    //     $ch = curl_init();
    //     $payload = http_build_query($data);
    //     curl_setopt($ch, CURLOPT_URL,$url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //     $response = curl_exec($ch);
    //     // echo $response;die();
    //     $xml = simplexml_load_string($response);
    //     $json = json_encode($xml);
    //     // print_r($json);die;
    //     $data = json_decode($json);
    //     $get_currency = json_decode($json);
    // //   print_r($data);die;
      
      
    //   foreach($data->Body->Countries->Country as $Country)
    //   {
    //       foreach($Country->Cities->City as $City)
    //   {
         
         
    //      $data=DB::table('travellanda_get_cities')->insert([
             
             
    //              'CountryCode'=>$Country->CountryCode,
    //              'CityName'=>$City->CityName ?? '',
    //              'CityId'=>$City->CityId ?? '',
                 
                 
    //              ]);
         
         
    //   }
    //   }
      
            
    }
    
public function GetHotels()
    {
       

      //10
//               $data_request="
// <Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotels</RequestType>
//                 </Head>
//              <Body>

// <CountryCode>SS</CountryCode>

// </Body>
// </Request>";
            
//             // print_r($data_request);die();
//             $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
//         $get_currency = json_decode($json);
//     //   print_r($data);die;
      
      
    
         
         
//          $data=DB::table('travellanda_get_hotels')->insert([
             
             
//                  'HotelId'=>$data->Body->Hotels->Hotel->HotelId,
//                  'HotelName'=>$data->Body->Hotels->Hotel->HotelName,
//                  'CityId'=>$data->Body->Hotels->Hotel->CityId
                 
                 
//                  ]);
         
         
          
     
      
            
    }
    
 
    
    
    public function GetHotelDetails()
    {
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(369500)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $Latitude=(array)$Hotel->Latitude;
//         $Latitude=json_encode($Latitude);
        
//         $Longitude=(array)$Hotel->Longitude;
//         $Longitude=json_encode($Longitude);
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
        
//         $insert_data->StarRating=$StarRating;
      
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       } 
//       //2
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(370000)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
      
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //3
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(370500)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
        
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //4
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(371000)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
      
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //5
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(371500)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
       
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //6
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(372000)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
       
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //7
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(372500)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
       
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //8
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(373000)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
       
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }

// //9
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(373500)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
        
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //10
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(374000)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
        
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//              $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(374500)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $Latitude=(array)$Hotel->Latitude;
//         $Latitude=json_encode($Latitude);
        
//         $Longitude=(array)$Hotel->Longitude;
//         $Longitude=json_encode($Longitude);
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
        
//         $insert_data->StarRating=$StarRating;
      
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       } 
//       //2
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(375000)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
      
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //3
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(375500)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
        
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //4
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(376000)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
      
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //5
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(376500)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
       
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //6
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(377000)->take(500)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
       
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //7
//       $travellanda_get_hotel=DB::table('travellanda_get_hotels')->skip(377500)->take(673)->get();
//     //   print_r($travellanda_get_hotel);die();
         
  
             
             
//          $data_request="<Request>
//                 <Head>
//               <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
//                 <Password>rY8qm76g5dGH</Password>
//                 <RequestType>GetHotelDetails</RequestType>
//                 </Head>
//                 <Body><HotelIds>";    
//     foreach ($travellanda_get_hotel as $travellanda_get_hotel) 
//     {
       
//       $data_request.="
                   
//                 <HotelId>".$travellanda_get_hotel->HotelId."</HotelId>
                 
//                   ";
        
//     }
    
    
//     $data_request .="</HotelIds></Body>
//             </Request>";
// // print_r($data_request);die();

// $url = "http://xmldemo.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd";
//             $timeout = 20;
//             $data = array('xml' => $data_request);
//             $headers = array(
//             "Content-type: application/x-www-form-urlencoded",
//         );
//         $ch = curl_init();
//         $payload = http_build_query($data);
//         curl_setopt($ch, CURLOPT_URL,$url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         // echo $response;die();
//         $xml = simplexml_load_string($response);
//         $json = json_encode($xml);
//         // print_r($json);die;
//         $data = json_decode($json);
       
//     //   print_r($data);die;

// foreach($data->Body->Hotels->Hotel as $Hotel)
//       {
       
//       $StarRating=(array)$Hotel->StarRating;
//         $StarRating=json_encode($StarRating);
       
//         $Address=(array)$Hotel->Address;
//         $Address=json_encode($Address);
//         $Location=(array)$Hotel->Location;
//         $Location=json_encode($Location);
        
//         $PhoneNumber=(array)$Hotel->PhoneNumber;
//         $PhoneNumber=json_encode($PhoneNumber);
//         $Description=(array)$Hotel->Description;
//         $Description=json_encode($Description);
        
//         $Facilities=(array)$Hotel->Facilities;
//         $Facilities=json_encode($Facilities);
        
//         $Images=(array)$Hotel->Images;
//         $Images=json_encode($Images);
        
        
        
        
//         $insert_data=new travellanda_get_hotel_detail();
//       $insert_data->HotelId=$Hotel->HotelId;
//         $insert_data->CityId=$Hotel->CityId;
//         $insert_data->HotelName=$Hotel->HotelName;
//         $insert_data->StarRating=$StarRating;
       
        
//          $insert_data->Address=$Address;
//         $insert_data->Location=$Location;
        
//          $insert_data->PhoneNumber=$PhoneNumber;
//         $insert_data->Description=$Description;
        
//          $insert_data->Facilities=$Facilities;
//         $insert_data->Images=$Images;
//         $insert_data->save();
        
         
         
      
//       }
//       //8
     
      
      
       
    }
    
    
    
    
    
    
    
    public function travel_search(Request $req)
  {
      
     Session()->forget('other_adults');
     Session()->forget('lead_passenger_details');
     Session()->forget('newstart');
     Session()->forget('newend');
      Session::forget('rooms_details');
      Session()->forget('child_searching');
      Session()->forget('hotel_beds_select');
    
    //   $country_code = $req->country_code;
      $city = $req->cityd;
      $room = $req->room;
      
    //   print_r($room);die();
      Session()->put('room_searching',$room);
      
    //   print_r($ch);die();
      
      $adult = $req->adult;
    //   print_r($adult);
    //   die();
      $child = $req->child;
      
       Session()->put('child_searching',$child);
      
      $exploded_city = explode(" ",$req->city);
      $city_name = $exploded_city[0];
      $start = $req->in;
      $end = $req->out;
      $newend = date("Y-m-d", strtotime($end));
      $newstart = date("Y-m-d", strtotime($start));
    
      Session::put('newstart', $newstart);
      Session::put('newend', $newend);
      
      Session::put('room', $room);
      Session::put('adult', $adult);
    //   Session::put('child', $child);
      Session::put('city', $city);





$snAdults = $req->input('snAdults'); 
$snChildren = $req->input('snChildren');

$snAges1 = $req->input('snAges1');
$snAges2 = $req->input('snAges2');
$snAges3 = $req->input('snAges3');
$snAges4 = $req->input('snAges4');

if(!empty($snAges1[count($snAges1)-1])) {
    unset($snAges1[count($snAges1)-1]);
}
if(!empty($snAges2[count($snAges2)-1])) {
    unset($snAges2[count($snAges2)-1]);
}
if(!empty($snAges3[count($snAges3)-1])) {
    unset($snAges3[count($snAges3)-1]);
}
if(!empty($snAges4[count($snAges4)-1])) {
    unset($snAges4[count($snAges4)-1]);
}


if(!empty($snAdults[count($snAdults)-1])) {
    unset($snAdults[count($snAdults)-1]);
}
if(!empty($snChildren[count($snChildren)-1])) {
    unset($snChildren[count($snChildren)-1]);
}

// print_r($snAges1);
// print_r($snAges2);
// print_r($snAges3);

// print_r($snAdults);
// die();

$i = 0;
$no = 1;




// $data = array(
//     "results" => array(),
//   );
  
  
  
  
  
$childrenAge = array(
    "age_results" => array(),
  );

  foreach ($snAdults as $item) { 
    $countchi  = $i++;
    $chi = $snChildren[$countchi]; 
    if($chi == 0){
        
        $data['results'][] = (object)[
            'Room' => $no++,
            'Adults' => $item,
            'Children' => $chi,
            
            
        ];
    }
    if($chi == 1){

      $childage=array();
      $childage[]=$snAges1[$countchi];
        
        $data['results'][] = (object)[
            'Room' => $no++,
            'Adults' => $item,
            'Children' => $chi,
            'ChildrenAge' => $childage,
        ];
        
    }
    if($chi == 2){

       $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];

        $data['results'][] = (object)[
            'Room' => $no++,
            'Adults' => $item,
            'Children' => $chi,
            'ChildrenAge' => $childage,
        ];
    }
    if($chi == 3){

       $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];
      $childage[]=$snAges3[$countchi];
      
        $data['results'][] = (object)[
            'Room' => $no++,
            'Adults' => $item,
            'Children' => $chi,
            'ChildrenAge' => $childage,
        ];
    }
    if($chi == 4){
  
      $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];
      $childage[]=$snAges3[$countchi];
      $childage[]=$snAges4[$countchi];


        $data['results'][] = (object)[
            'Room' => $no++,
            'Adults' => $item,
            'Children' => $chi,
            'ChildrenAge' => $childage,
        ];

    } 
    if($chi == 5){

      $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];
      $childage[]=$snAges3[$countchi];
      $childage[]=$snAges4[$countchi];
      $childage[]=$snAges5[$countchi];

  
        $data['results'][] = (object)[
            'Room' => $no++,
            'Adults' => $item,
            'Children' => $chi,
            'ChildrenAge' => $childage,
        ];

    }
     if($chi == 6){


      $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];
      $childage[]=$snAges3[$countchi];
      $childage[]=$snAges4[$countchi];
      $childage[]=$snAges5[$countchi];
      $childage[]=$snAges6[$countchi];

  
        $data['results'][] = (object)[
            'Room' => $no++,
            'Adults' => $item,
            'Children' => $chi,
            'ChildrenAge' => $childage,
        ];

    }
    
    
  }

// print_r($data['results']);
// die();
$res_data=$data['results'];


Session::put('room_search',$res_data);


$url = "http://xmldemo.travellanda.com/xmlv1/";
$timeout = 20;

$data_request="<Request>
<Head>
<Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
<Password>rY8qm76g5dGH</Password>
<RequestType>HotelSearch</RequestType>
</Head>
<Body>
<CityIds>
<CityId>$city</CityId>
</CityIds>
<CheckInDate>$newstart</CheckInDate>
<CheckOutDate>$newend</CheckOutDate>
<Rooms>";





foreach($res_data as $res_data)
{
$data_request .="
  
 
 <Room>
<NumAdults>". $res_data->Adults ."</NumAdults>
<Children>";
if(isset($res_data->ChildrenAge))
{
foreach ($res_data->ChildrenAge as $item)
{
$data_request .='<ChildAge>'. $item[0] .'</ChildAge>';
}
}
$data_request .= "
</Children>
</Room>

"; 
// echo $data_request;

}

$data_request .="</Rooms><Nationality>FR</Nationality>
<Currency>GBP</Currency>
<AvailableOnly>1</AvailableOnly>
</Body>
</Request>";

// $json_data=json_encode($data_request);
// print_r($json_data);die();
// echo $data_request;

// die;


        $data = array('xml' => $data_request);
        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
        );
        $ch = curl_init();
        $payload = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        // echo $response;die();
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        // print_r($json);die;
        $data = json_decode($json);
        $get_currency = json_decode($json);
    //   print_r($data);die;
        if(isset($hoteltravel->Error)){
            echo $hoteltravel->Error;
          $travellanda_hotels = null;
        }
        if($data->Body->HotelsReturned > 0){
            // print_r($hoteltravel);die;
        if(isset($data->Body->Hotels->Hotel)){
      $travellanda_hotels = $data->Body->Hotels->Hotel;
    //   print_r($travellanda_hotels);die();
        }else{
            dd('hotel not found');
        }
        }else{
            $travellanda_hotels = null;
        }

//travellanda api//end//
///////
//////
/////
////
//hotelbeds api//start//


$i = 0;
$no = 1;
$data = array(
    "hotel_beds" => array(),
  );


  foreach ($snAdults as $item) { 
    $countchi  = $i++;
    $chi = $snChildren[$countchi]; 
    if($chi == 0){
        
        $data['hotel_beds'][] = (object)[
             'rooms' => $no++,
            'adults' => $item,
            'children' => $chi,
            
            
            
        ];
    }
    if($chi == 1){

      $childage=array();
      $childage[]=$snAges1[$countchi];
        
        $data['hotel_beds'][] = (object)[
             'rooms' => $no++,
            'adults' => $item,
            'children' => $chi,
            // 'ChildrenAge' => $childage,
        ];
        
    }
    if($chi == 2){

       $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];

        $data['hotel_beds'][] = (object)[
            'rooms' => $no++,
            'adults' => $item,
            'children' => $chi,
            // 'ChildrenAge' => $childage,
        ];
    }
    if($chi == 3){

       $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];
      $childage[]=$snAges3[$countchi];
      
        $data['hotel_beds'][] = (object)[
             'rooms' => $no++,
            'adults' => $item,
            'children' => $chi,
            // 'ChildrenAge' => $childage,
        ];
    }
    if($chi == 4){
  
      $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];
      $childage[]=$snAges3[$countchi];
      $childage[]=$snAges4[$countchi];


        $data['hotel_beds'][] = (object)[
             'rooms' => $no++,
            'adults' => $item,
            'children' => $chi,
            // 'ChildrenAge' => $childage,
        ];

    } 
    if($chi == 5){

      $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];
      $childage[]=$snAges3[$countchi];
      $childage[]=$snAges4[$countchi];
      $childage[]=$snAges5[$countchi];

  
        $data['hotel_beds'][] = (object)[
             'rooms' => $no++,
            'adults' => $item,
            'children' => $chi,
            // 'ChildrenAge' => $childage,
           
        ];

    }
     if($chi == 6){


      $childage=array();
      $childage[]=$snAges1[$countchi];
      $childage[]=$snAges2[$countchi];
      $childage[]=$snAges3[$countchi];
      $childage[]=$snAges4[$countchi];
      $childage[]=$snAges5[$countchi];
      $childage[]=$snAges6[$countchi];

  
        $data['hotel_beds'][] = (object)[
            'rooms' => $no++,
            'adults' => $item,
            'children' => $chi,
            // 'ChildrenAge' => $childage,
        ];

    }
    
    
  }
  $res_hotel_beds=$data['hotel_beds'];
  
  Session::put('hotel_beds_rooms',$res_hotel_beds);
  
  
// print_r($data['hotel_beds']);
// die();


// print_r($res_hotel_beds);
// die();



$curl = curl_init();

$geolocation=array(
                        "latitude" => 51.507359,
                        "longitude" => -0.136439,
                        "radius" => 20,
                        "unit" => "km",
                  );
              
$data = array(
                'stay'=>array(
                    'checkIn' =>$newstart,
                    'checkOut' => $newend,
                ),
                'occupancies' => $res_hotel_beds,
                    'geolocation' =>  $geolocation,
                );
                
                

               
                
$signature = pr($data);   
$data=json_encode($data);

// print_r($data);die();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/hotels',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data,
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
// echo $response;die();
curl_close($curl);
$hotelbeds = json_decode($response);
// dd($hotelbeds);
// print_r($hotelbeds);die();
 
if(isset($hotelbeds->error)){
     $hotelbed_hotels = null;
}
if(isset($hotelbeds->hotels)){
$hotelbed_hotels = $hotelbeds->hotels->hotels;

// dd($hotelbed_hotels);
}else{
    $hotelbed_hotels = null;
}
// $hotelbed_hotels = null;
//hotelbeds api//end//
/////////
////////
//////
////
//webbeds api//start//

// $username='umrahtech';
// $password='b0ed02034d6196366d60c731312d6233';
// $id='1840835';
// $source='1';
// $rateBasis='-1';
// $rooms=1;

// $fromDate=$newstart;
// $toDate=$newend;
// $currency='492';
// $adultsCode=1;
// $passengerNationality=7;
// $passengerCountryOfResidence=7;
// $hotel=164;



// $data='<customer>  
// <username>'.$username.'</username>  
// <password>'.$password.'</password>  
// <id>'.$id.'</id>  
// <source>'.$source.'</source>  
// <product>hotel</product> 
//     <request command="searchhotels">  
//         <bookingDetails>  
//         <fromDate>'.$fromDate.'</fromDate>  
//         <toDate>'.$toDate.'</toDate>  
//         <currency>'.$currency.'</currency> 
//         <rooms no="1">  
//         <room runno="0">  
//             <adultsCode>'.$adultsCode.'</adultsCode>  
//             <children no="0"></children>  
//             <rateBasis>'.$rateBasis.'</rateBasis>  
//             <passengerNationality>'.$passengerNationality.'</passengerNationality>  
//             <passengerCountryOfResidence>'.$passengerCountryOfResidence.'</passengerCountryOfResidence>  
//         </room>  
        
//     </rooms>   
//         </bookingDetails>  
//         <return>  
//             <filters xmlns:a="http://us.dotwconnect.com/xsd/atomicCondition" >  
//                 <city>'.$hotel.'</city>
//                  <noPrice>true</noPrice>
//             </filters> 
            
//             <fields>  
//                 <field>hotelName</field>  
//                 <field>address</field>  
//                 <field>location</field>  
//                 <field>cityName</field>  
//                 <field>cityCode</field>  
//                 <field>stateName</field>  
//                 <field>stateCode</field>  
//                 <field>countryName</field>  
//                 <field>countryCode</field>  
//                 <field>rating</field>
//                 <field>images</field>
//                 <field>priority</field>  
//             </fields>  
//         </return>  
//     </request>  
// </customer>  ';

// // print_r($data);die();
// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'http://xmldev.dotwconnect.com/gatewayV4.dotw',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS =>$data,
//   CURLOPT_HTTPHEADER => array(
//     'Content-Type: application/xml'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// // echo $response;
// $ob= simplexml_load_string($response);
// $json  = json_encode($ob);
// $result = json_decode($json);
// // print_r($result);die();
// if(isset($result->hotels->hotel)){
// $webbed_hotels = $result->hotels->hotel;

// // dd($hotelbed_hotels);
// }else{
//     $webbed_hotels = null;
// }
    
//webbeds api//end//
///////
/////
////
///
// dd($travellanda_hotels);
////////saving in DB
// print_r($reqdata);die();
// $travellanda = array(
// 'travellandasearchRS' => json_encode($travellanda_hotels),
// 'travellandasearchRQ' => json_encode($reqdata),
// );

// $hotelbeds = array(
// 'hotelbedsRS' => $hotelbed_hotels,
// 'hotelbedsRQ' => $data
// );

// $webbeds = array(
// 'webbedsRS' => $webbed_hotels,
// 'webbedsRQ' => $data,
// );



  $voucher_id = random_int(1000000, 9999999);


Session::put('search_id',$voucher_id);









  $token= config('token');

  $searchdata = array(
    'search_id' => $voucher_id,
    'token'=>$token,
    'travellandasearchRS' => json_encode($travellanda_hotels),
    'travellandasearchRQ' => json_encode($data_request),
    'hotelbedsearchRS' => json_encode($hotelbed_hotels),
    'hotelbedsearchRQ' => json_encode($data),
    'webbedsearchRS' => '',
    'webbedsearchRQ' => '',
    );
//   $searchdata=json_encode($searchdata);
  $curl = curl_init();
        
  $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/save_hotel_booking',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $searchdata,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
    //  echo $response;
    //  dd($response);
     Session::put('inserted_id', $response);
     curl_close($curl);
     

    return view('template/frontend/pages/hotel_search',compact('travellanda_hotels','hotelbed_hotels','get_currency'));

  }
  













  
  
  
  
  
 
public function hotel_detail($id,$name,$slug){

    $provider = $slug;
$hotel_beds_code=$id;
$hotel_beds_name=$name;
Session::put('hotel_beds_code',$hotel_beds_code);
Session::put('hotel_beds_name',$hotel_beds_name);

        if($provider == "travellanda"){
        
    
    $start = Session::get('newstart');
    $end = Session::get('newend');
    
    $room = Session::get('room');
    // $child = Session::get('child');
    $child = Session::get('child_searching');
    $adult = Session::get('adult');
    $city = Session::get('city');
    
    
    
    
    
     
$search_id=Session::get('search_id');
// $db=DB::table('hotel_booking')->where('search_id',$search_id)->update([
    
//     'check_in'=>$start,
//     'check_out'=>$end,
//     'total_passenger'=>$adult,
//     'child'=>$child,
//     ]);
    
    
    
    $room_search=Session::get('room_search');
    
    
    
    
$url = "http://xmldemo.travellanda.com/xmlv1/";
$timeout = 20;

$data_request="<Request>
<Head>
<Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
<Password>rY8qm76g5dGH</Password>
<RequestType>HotelSearch</RequestType>
</Head>
<Body>
<CityIds>
<CityId>$city</CityId>
</CityIds>
<CheckInDate>$start</CheckInDate>
<CheckOutDate>$end</CheckOutDate>
<Rooms>";





foreach($room_search as $res_data)
{
$data_request .="
  
 
 <Room>
<NumAdults>". $res_data->Adults ."</NumAdults>
<Children>";
if(isset($res_data->ChildrenAge))
{
foreach ($res_data->ChildrenAge as $item)
{
$data_request .='<ChildAge>'. $item[0] .'</ChildAge>';
}
}
$data_request .= "
</Children>
</Room>

"; 
// echo $data_request;

}

$data_request .="</Rooms><Nationality>FR</Nationality>
<Currency>GBP</Currency>
<AvailableOnly>1</AvailableOnly>
</Body>
</Request>";

// $json_data=json_encode($data_request);
// print_r($json_data);die();
// echo $data_request;

// die;


    

        $data = array('xml' => $data_request);
        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
        );
        $ch = curl_init();
        $payload = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $rawdata = curl_exec($ch);
        $xml = simplexml_load_string($rawdata);
        $json = json_encode($xml);
        $data1 = json_decode($json);
        // print_r($data1);die();
        if(isset($data1->Body->Hotels->Hotel)) {
            $hotel = $data1->Body->Hotels->Hotel;
            // dd($hotel);
            $count = count($hotel);
            if($count > 0) {
              foreach($hotel as $key => $rom) {
                 if($id == $rom->HotelId) {
    Session::put('travellandahotelrating', $rom->StarRating);
                     
                     
                      $travellanda_roomh =  $rom;
                      $h_option_id=$travellanda_roomh->Options->Option[0]->OptionId;
                      Session::put('h_option_id',$h_option_id);
                    //   print_r($travellanda_roomh);die();
                      
  $url = "http://xmldemo.travellanda.com/xmlv1/";
    $timeout = 20;
    
    $reqdata = "<Request>
    <Head>
    <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
    <Password>rY8qm76g5dGH</Password>
    <RequestType>GetHotelDetails</RequestType>
    </Head>
    <Body>
    <HotelIds>
    <HotelId>$rom->HotelId</HotelId>
    </HotelIds>
    </Body>
    </Request>";
    $data = array('xml' => $reqdata);
    $headers = array(
        "Content-type: application/x-www-form-urlencoded",
    );
    $ch = curl_init();
    $payload = http_build_query($data);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $rawdata = curl_exec($ch);
    $xml = simplexml_load_string($rawdata);
    $json = json_encode($xml);
    $data1 = json_decode($json);
     Session::put('travellandahotelimg', $data1->Body->Hotels->Hotel->Images->Image[0]);
    // dd();
                
                      
                      
                      break;
                  } else {
                      $travellanda_roomh =  'Not Found'; 
                  }
              }
          } else {
              if($hotel->HotelId == $id){
                  $travellanda_roomh =  $hotel;
              } else {
                  dd('Room Not Founddddddd');
              }
          }
        } else {
            return view('template/frontend/pages/error');
        }
        if($travellanda_roomh == "Not Found") {
            return view('template/frontend/pages/error');
        } else {
            $hotel_detail = $travellanda_roomh;
            // $hotel_detail = json_encode($data1);
            // print_r($hotel_detail);die();
             $tr = $hotel_detail;
            
            
            // $tr = array(
            //     'travel_hotel'=>$hotel_detail,
            //     'room'=>$detail,
            //     );
                // print_r($tr);die;
            $t = json_encode($tr);
            
            $token= config('token');
            
            
            
            $start = Session::get('newstart');
    $end = Session::get('newend');
    $child = Session::get('child_searching');
    $adult = Session::get('adult');
    $room = Session::get('room');
            
            
            $travellanda_detail = array(
                'id'=>Session::get('inserted_id'),
                'token'=>$token,
                'travellandadetailRS' => $t,
                'travellandadetailRQ' => json_encode($reqdata),
                'check_in' => $start,
                'check_out' => $end,
                'child' => $child,
                'adult' => $adult,
                'room' => $room,
                );
            
            $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/save_hotel_booking',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $travellanda_detail,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
//       echo"<pre>";
    //  echo $response;
     curl_close($curl);
     
     
            return view('template/frontend/pages/hotel_detail',compact('travellanda_roomh','data1'));
        }
    
}
          
          
          
        if($provider == "hotelbeds"){
    
           $start = Session::get('newstart');
           $end = Session::get('newend');

    /////////////////////////
    
    
      $hotel_beds_rooms=Session::get('hotel_beds_rooms');
    // print_r($hotel_beds_rooms);die();
    
      $curl = curl_init();
        $geolocation=array(
                         "latitude" => 51.507359,
                        "longitude" => -0.136439,
                        "radius" => 20,
                        "unit" => "km",
                  );
        $data = array(
                'stay'=>array(
                    'checkIn' =>$start,
                    'checkOut' => $end,
                ),
                'occupancies' => $hotel_beds_rooms,
                    'geolocation' =>  $geolocation,
                );
        $data=json_encode($data);
        $signature = pr($data);
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/hotels',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data,
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
// echo $response;die();
curl_close($curl);
$hotelbeds = json_decode($response);
// dd($hotelbeds);
// print_r($hotelbeds);die();
 
if(isset($hotelbeds->error)){
     $hotelbed_hotels = null;
}
if(isset($hotelbeds->hotels)){
$hotelbed_hotels = $hotelbeds->hotels->hotels;
// dd($hotelbed_hotels);
foreach($hotelbed_hotels as $key1=>$getroom){
   
    if($getroom->code = $id && $getroom->name == $hotel_beds_name)
    {
        $get_selected_base_hotel = $getroom;
        Session::put('get_selected_base_hotel',$get_selected_base_hotel);
    //   print_r($get_selected_base_hotel);
    }else{
    $get_selected_base_hotel = null;
}
}
 
// dd($hotelbed_hotels);
}else{
    $hotelbed_hotel = null;
}
$hotelbed_hotel=Session::get('get_selected_base_hotel');
$hotelbed = json_encode($hotelbed_hotel);





$start = Session::get('newstart');
    $end = Session::get('newend');
    $child = Session::get('child_searching');
    $adult = Session::get('adult');
    $room = Session::get('room');
//  print_r($hotelbed);die();
$hotelbed_detail = array(
     'id'=>Session::get('inserted_id'),
    'hotelbeddetailRS' => $hotelbed,
    'hotelbeddetailRQ' => json_encode($data),
    'check_in'=>$start,
    'check_out'=>$end,
    'total_passenger'=>$adult,
    'child'=>$child,
     'room'=>$room,
    
    );
 $curl = curl_init();
        
        $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/save_hotel_booking',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $hotelbed_detail,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
//       echo"<pre>";
     echo $response;
     curl_close($curl);
  return view('template/frontend/pages/hotel_detail',compact('hotelbeds','hotelbed_hotel','signature'));

}




        if($provider == "webbeds"){
    
           $start = Session::get('newstart');
           $end = Session::get('newend');
            Session::put('webbeds_hotelid', $id);
            
         
    //to get hotel rooms only
    /////////////////////////
 $data='<customer>  
    <username>umrahtech</username>  
    <password>b0ed02034d6196366d60c731312d6233</password>  
    <id>1840835</id>  
        <source>1</source>  
        <product>hotel</product>  
        <request command="getrooms">  
            <bookingDetails>
                <fromDate>'.$start.'</fromDate>  
                <toDate>'.$end.'</toDate>  
                <currency>492</currency>  
                <rooms no="1">  
                    <room runno="0">  
                        <adultsCode>1</adultsCode>  
                        <children no="0"></children>  
                        <rateBasis>-1</rateBasis>  
                        <passengerNationality>7</passengerNationality>  
                        <passengerCountryOfResidence>7</passengerCountryOfResidence>
                          
                    </room>  
                    
                </rooms>  
                <productId>'.$id.'</productId>  
            </bookingDetails>  
        </request>  
    </customer>';

    // print_r($data);die;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://xmldev.dotwconnect.com/gatewayV4.dotw',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: text/xml',
    'Cookie: PHPSESSID=31232f6d2be59ecfa33faf4d00f95d2d'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$ob= simplexml_load_string($response);
$json  = json_encode($ob);
$result = json_decode($json);
//  print_r($result);die;
if(isset($result->request->error)) {
    $webbeds_room = null;
} else {
    // $webbeds_rooms = $result->hotel->rooms->room->roomType;
    // dd($webbeds_rooms);
    if(isset($result->hotel->rooms->room->roomType)) {
        $webbeds_room = $result->hotel->rooms->room->roomType;
       $webbed_hotel_name = $result->hotel->{'@attributes'}->name;
       $webbed_hotel_currency = $result->currencyShort;
        Session::put('webbed_hotel_name', $webbed_hotel_name);
        Session::put('$webbed_hotel_currency', $webbed_hotel_currency);
    } else {
        $webbeds_room = null;
    }
}
if ($webbeds_room != null) {
    $webbeds = json_encode($webbeds_room);
    
    $web = array(
        'wedbedroomdetail' => $webbeds_room,
        'webbedhotel' => $webbed_hotel_name,
        );
    $webbed_detail = json_encode($web);
    
    $token= config('token');
    $webbed_detailz = array(
         'id'=>Session::get('inserted_id'),
        'token'=>$token,
        'webbeddetailRS' => $webbed_detail,
        'webbeddetailRQ' => json_encode($data),
        );
     $curl = curl_init();
        
     $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/save_hotel_booking',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $webbed_detailz,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
//       echo"<pre>";
     echo $response;
     curl_close($curl);
        
 $a = view('template/frontend/pages/hotel_detail',compact('webbeds_room'));
} else {
    $a = view('template/frontend/pages/error');
}

return $a;

}
}
  


















  
public function policy(Request $req){
     
      $id = explode(" ",$req->optid);
      $optid = $id[0];
     
     
      $url = "http://xmldemo.travellanda.com/xmlv1/";
        $timeout = 20;
        $reqdata = "<Request><Head><Username>1124fb7683cf22910ea6e3c97473bb9c</Username><Password>rY8qm76g5dGH</Password><RequestType>HotelPolicies</RequestType></Head><Body><OptionId>$optid</OptionId></Body></Request>";
        $data = array('xml' => $reqdata);
        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
        );
        $ch = curl_init();
        $payload = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $rawdata = curl_exec($ch);
        // print_r(rawdata);die;
        $xml = simplexml_load_string($rawdata);
        $json = json_encode($xml);
        // print_r($json);
         $data = json_decode($json);
            // print_r($data);die;
             $policy = $data->Body;
             echo json_encode($policy);
             
             
             $search_id = Session::get('search_id');
             
             
             
             
             $travellandaselection = array(
 
    'id'=>Session::get('inserted_id'),
    'json'=>$json,
    
    );
    $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/travellanda_cancel_policy_data',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $travellandaselection,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
//       echo"<pre>";
    //  echo $response;
     curl_close($curl);
             
             
             
             


  }
  
  
  
  














  
  
public function getbooking($slug,Request $req){
  

    $provider = $slug;
    Session::put('provider', $provider);
    if($provider =='travellanda'){
        // dd($req);
      $datein = $req->in;
      $dateout = $req->out;
      
      
      
        $roomsg=$req->input('selectrooms');
        
              foreach ($roomsg as $roomsgroup) {
                if($roomsgroup != 'no'){

                   $obj=json_decode($roomsgroup);

$roomRate[] = $obj;

                   
                }
      }
      
    //   print_r(json_encode($roomRate));         
      
    //  die();
    
     
  Session::put('rooms_details', $roomRate);   
     
     
     
     
     
     
     
     
     
     
    //  Session::put('travellandaoptid', $optid);
    //  Session::put('travellandaroomid', $roomid);
     
     Session::put('travellandapass', $req->passport_no);
     Session::put('travellandaaddress', $req->Address);
     Session::put('travellandaemail', $req->email);
     

        // $url = "http://xmldemo.travellanda.com/xmlv1/";
        // $timeout = 20;
        // $reqdata = "<Request>
        // <Head>
        // <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
        // <Password>rY8qm76g5dGH</Password>
        // <RequestType>HotelPolicies</RequestType>
        // </Head>
        // <Body>";
        // foreach($roomRate as $roomRate)
        // {
        //   $reqdata .="  
        //     <OptionId>".$roomRate->OptionId."</OptionId>
            
        //   "; 
        // }
        
        
        // $reqdata .="</Body>
        // </Request>";
        
        // // print_r($reqdata);die();
        
        // $data = array('xml' => $reqdata);
        
        // print_r($data);die();
        
        // $headers = array(
        //     "Content-type: application/x-www-form-urlencoded",
        // );
        // $ch = curl_init();
        // $payload = http_build_query($data);
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $rawdata = curl_exec($ch);
        // // print_r(rawdata);die;
        // $xml = simplexml_load_string($rawdata);
        // $json = json_encode($xml);
        // // print_r($json);
        //  $data = json_decode($json);
        //  print_r($data);die;
        //  dd($data->Body);
//checkout page


//to store in DB//
// $token= config('token');
// $travellandaselection = array(
//     'token'=>$token,
//     'slug'=>$slug,
//     'selectedoptid'=>$optid,
//     'selectedroomid'=>$roomid
//     );
// $travellanda_selected = json_encode($travellandaselection);
// dd($req);


$travellandaselection = array(
    'slug'=>$slug,
    'id'=>Session::get('inserted_id'),
    'travellandaSelectionRQ'=>json_encode($roomRate),
    'travellandaSelectionRS'=>json_encode($roomRate),
    );
    $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/save_hotel_booking',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $travellandaselection,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
//       echo"<pre>";
    //  echo $response;
     curl_close($curl);
//end DB store//


 return redirect('hotel_cart');

    
      }
  
    if($provider =='hotelbeds'){
        
        
        
        
        $hotelbeds_select_room=$req->input('hotelbeds_select_room');
        
    //      print_r($hotelbeds_select_room);         
      
    //  die();
        
        
        
              foreach ($hotelbeds_select_room as $roomsgroup) {
                if($roomsgroup != 'no'){

                   $obj=json_decode($roomsgroup);

$roomRate[] = $obj;

                   
                }
      }
      
      
      
      
      
      
    //   print_r($roomRate);         
      
    //  die();
    
   
  

    $data_res = '{
    
    "rooms":[
    ';
    foreach($roomRate as $roomRate)
    {
        
  $data_res .='
    
        {
            "rateKey": "'.$roomRate->rate_rateKey.'"
        },
        
        
      ';
    }
    
    $data_res .='
        
    ]
   
    
     ';
    
   $data_res .='
    }';

$data_res = substr_replace($data_res, '', strrpos($data_res, ','), 1);

// print_r($data_res);die();
// $data=$data;
//   $data=json_encode($data);
//   print_r($data);die;
$signature = pr($data_res);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/checkrates',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data_res,
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
// echo $response;die();
curl_close($curl);



Session::put('hotel_beds_select',$response);


$result = json_decode($response);

    $hotelbedcselection = $response;
    $hotelbedzselection = array(
        'id'=>Session::get('inserted_id'),
        'hotelbedselectionRS' => $hotelbedcselection,
        'hotelbedselectionRQ' => $data_res,
        );
        $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/save_hotel_booking',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $hotelbedzselection,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
//       echo"<pre>";
     echo $response;
     curl_close($curl);
     
     
     return redirect('hotel_cart');
     
     
    //  return view('template/frontend/pages/bookingdetail',compact('hotelbeds_room_rate','slug'));
     
 
  }
  
     if($provider =='webbeds'){
        $data = json_decode($req->selected_webbeds_romz);

        $productid = Session::get('webbeds_hotelid');
        $opt = $req->selected_webbeds_romz;
        //dd($opt);
        $exploded = explode("$$",$opt);
        $explode = $exploded;
   
       $ratebasis_id = $data->selectedRateBasis;
     $allocation = $data->allocationDetails;
     $room_code = $data->roomtypecode;
//     dd($allocation);
   $datein = $req->in;
   $dateout = $req->out;
    
$fromDate=$datein;
$toDate=$dateout;

$product=$productid;
$adultsCode=1;
$rooms=1;
$passengerNationality=7;
$passengerCountryOfResidence=7;
$selectedRateBasis=$ratebasis_id;
$allocationDetails=$allocation;
$code=$room_code;


    $data='<customer>  
    <username>umrahtech</username>  
    <password>b0ed02034d6196366d60c731312d6233</password>  
    <id>1840835</id>  
        <source>1</source>  
        <product>hotel</product>  
        <request command="getrooms">  
            <bookingDetails>  
                <fromDate>'.$fromDate.'</fromDate>  
                <toDate>'.$toDate.'</toDate>  
                <currency>492</currency>  
                <rooms no="'.$rooms.'">  
                    <room runno="0">  
                        <adultsCode>'.$adultsCode.'</adultsCode>  
                        <children no="0"></children>  
                        <rateBasis>-1</rateBasis>  
                        <passengerNationality>'.$passengerNationality.'</passengerNationality>  
                        <passengerCountryOfResidence>'.$passengerCountryOfResidence.'</passengerCountryOfResidence>
                        <roomTypeSelected>  
                            <code>'.$code.'</code>  
                            <selectedRateBasis>'.$selectedRateBasis.'</selectedRateBasis>  
                            <allocationDetails>'.$allocationDetails.'</allocationDetails>  
                        </roomTypeSelected>
                    </room>  
                    
                </rooms>  
                <productId>'.$product.'</productId>
                <roomModified>'.$rooms.'</roomModified>   
            </bookingDetails>  
        </request>  
    </customer>  ';

    // print_r($data);die();
    $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://xmldev.dotwconnect.com/gatewayV4.dotw',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: text/xml',
    'Cookie: PHPSESSID=31232f6d2be59ecfa33faf4d00f95d2d'
  ),
));

$response = curl_exec($curl);
$xml = simplexml_load_string($response);
        $json = json_encode($xml);
        // print_r($json);
         $data = json_decode($json);
    // //   dd($data);die;
    // $token= config('token');
    // $webselection = array(
    //         'token'=>$token,
    //         'room_code'=>$room_code,
    //         'allocation'=>$allocation,
    //         'ratebasis_id'=>$ratebasis_id
    //         );
    $webbedselection = array(
       'id'=>Session::get('inserted_id'),
       'webbedselectionRQ' => $data,
       'webbedselectionRS' => $json,
       );
            $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/save_hotel_booking',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $webbedselection,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
//       echo"<pre>";
     echo $response;
     curl_close($curl);
//end DB store//

return view('template/frontend/pages/bookingdetail',compact('allocation','room_code','ratebasis_id','slug'));


    }
}
  
  






public function hotel_checkout()
{
    
    $slug= Session::get('provider');
    $optid= Session::get('travellandaoptid');
    $roomid= Session::get('travellandaroomid');
    
     $search_id=Session::get('search_id');
    // $lead_passengar = DB::table('hotel_booking')->where('search_id',$search_id)->first();
    
    
    
    

    

   
    $data = array(
        'search_id'=>$search_id,
        );
        $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/get_booking_checkout',
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
//       echo"<pre>";
    //  echo $response;die();
     curl_close($curl);
     
     $res=json_decode($response);
    //  print_r($res);die();
    
    $get_data=$res->data;
    $countries=$res->countries;
    $lead_encode=$get_data->lead_passenger_details;
    $lead_passengar_decode=json_decode($lead_encode);
    
    $other_encode=$get_data->other_passenger_details;
    $other_passengar_decode=json_decode($other_encode);
    
    $hotelbedSelectionRS=$get_data->hotelbedSelectionRS;
    $hotel_beds_select_res=json_decode($hotelbedSelectionRS);
    // print_r($hotel_beds_select_res);die();
    
     Session::put('lead_passenger_details',$lead_passengar_decode);
    
    // print_r($lead_passengar_decode);die();
    
   return view('template/frontend/hotel_page/hotel_checkout',compact('optid','roomid','slug','lead_passengar_decode','other_passengar_decode','hotel_beds_select_res','countries')); 
}


public function hotel_cart()
{
    
    $slug= Session::get('provider');
    $optid= Session::get('travellandaoptid');
    $roomid= Session::get('travellandaroomid');
    
     $search_id=Session::get('search_id');
   $data = array(
        'search_id'=>$search_id,
        );
        $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/hotel_cart',
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
//       echo"<pre>";
    //  echo $response;die();
     curl_close($curl);
     
     $res=json_decode($response);
    //  print_r($res);die();
    
    $get_data=$res->data;
    
 
    // print_r(json_decode($get_data->travellandadetailRS));die();
   return view('template/frontend/hotel_page/cart',compact('get_data')); 
}

public function add_lead_passengar(Request $request)
{
    
 $search_id=Session::get('search_id');
//  print_r($search_id);die();
 $lead_first_name=$request->lead_first_name;
 $lead_last_name=$request->lead_last_name;
 $lead_email=$request->lead_email;
 $lead_phone=$request->lead_phone;
 $lead_title=$request->lead_title;
 $lead_passport_no=$request->lead_passport_no;
 $lead_address=$request->lead_address;
 
 $data=array(
     
     'lead_first_name'=>$lead_first_name,
     'lead_last_name'=>$lead_last_name,
     'lead_email'=>$lead_email,
     'lead_phone'=>$lead_phone,
     'lead_title'=>$lead_title,
     'lead_passport_no'=>$lead_passport_no,
      'lead_address'=>$lead_address,
     
     );
     
     $lead_passenger_details=json_encode($data);

            $data = array(
                'search_id'=>$search_id,
                'lead_passenger_details'=>$lead_passenger_details
               
                );
                
                // print_r($travellandacnfrm);die();
                 $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/add_lead_passengar',
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
//       echo"<pre>";
    //  echo $response;
     curl_close($curl);
//end DB store//
  
        
        return redirect()->back()->with('message','Lead Passengar Submit SuccessFul!');
    
    
}

public function add_other_passengar(Request $request)
{
    
   $child = Session()->get('child_searching',[]);
    $adults = Session()->get('other_adults',[]);
            
            
            
             $data=(object)[
     
     'title'=>$request->title,
     'other_first_name'=>$request->other_first_name,
     'other_last_name'=>$request->other_last_name,
     'other_nationality'=>$request->other_nationality,
     
     
     ];
            
            
            array_push($adults,$data);
            Session()->put('other_adults',$adults);
            
    
    // print_r($adults);die();
 $search_id=Session::get('search_id');

 
 
$data = array(
                'search_id'=>$search_id,
                'other_passenger_details'=>json_encode($adults)
               
                );
                
                // print_r($travellandacnfrm);die();
                 $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/add_other_passengar',
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
//       echo"<pre>";
    //  echo $response;
     curl_close($curl);
//end DB store//
     
     
 
 
    
        
        
        
        
        
        return redirect()->back()->with('message','Other Passengar Submit SuccessFul!');
    
    
}










  
public function cnfrmbooking($id,$slug,Request $req){
      
     // dd($id);
      $search_id=Session::get('search_id');
       $h_option_id=Session::get('h_option_id');
     $other_passenger_details = Session()->get('other_adults');
    //   $searched_data = DB::table('hotel_booking')->where('search_id',$search_id)->update(
    //     [
    //     'other_passenger_details'=>json_encode($other_passenger_details),
        
    //     ]);
     
     
     
     if($slug == "travellanda"){
         
        
        
         $lead_passenger_details=Session::get('lead_passenger_details');
    
         
        
        
          $rooms_details=Session::get('rooms_details'); 
          
          $children_details=''; 
          
         
//   print_r($rooms_details);die();
          
          
         

   $url = "http://xmldemo.travellanda.com/xmlv1/";
        $timeout = 20; 
$data_request="<Request>
<Head>
<Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
<Password>rY8qm76g5dGH</Password>
<RequestType>HotelBooking</RequestType>
</Head>
<Body>
<OptionId>". $h_option_id ."</OptionId>
<YourReference>XMLTEST</YourReference>
<Rooms>";

foreach($rooms_details as $rooms_details)
{
    
$data_request .="
 <Room>
 
<RoomId>". $rooms_details->Rooms->Room->RoomId ."</RoomId>
<PaxNames>";
 $other_passenger_details = Session()->get('other_adults',[]);
 

 $data_request .="
 <AdultName>
<Title>". $lead_passenger_details->lead_title ."</Title>
<FirstName>". $lead_passenger_details->lead_first_name ."</FirstName>
<LastName>".$lead_passenger_details->lead_last_name."</LastName>
</AdultName>";

if(isset($other_passenger_details))
{
  $count=0;  
foreach($other_passenger_details as $other_passenger_details)
{
    
    if($rooms_details->Rooms->Room->NumAdults > $count)
    {
    
   $data_request .="
 <AdultName>
<Title>". $other_passenger_details->title ."</Title>
<FirstName>". $other_passenger_details->other_first_name ."</FirstName>
<LastName>". $other_passenger_details->other_last_name ."</LastName>
</AdultName>";

}
$count=$count+1;
}
}
// if(isset($children_details))
// {
// foreach ($res_data->ChildrenAge as $item)
// {
  $data_request .="<ChildName>
<FirstName>ather</FirstName>
<LastName>ather</LastName>
</ChildName>";
// }
// }
$data_request .="
</PaxNames>
</Room>

"; 
// echo $data_request;


}

$data_request .="</Rooms>
</Body>
</Request>";

//  echo $data_request;
//  die();
      
      
      
        $data = array('xml' => $data_request);
        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
        );
        $ch = curl_init();
        $payload = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $rawdata = curl_exec($ch);
        // print_r($rawdata);die;
        $xml = simplexml_load_string($rawdata);
        $json = json_encode($xml);
        
        $data = json_decode($json);
        
        
    // print_r($data);
      
    // die();
    
 if(isset($data->Body->Error)){
            $er = $data->Body->Error;
            $fail = $er->ErrorText;
            dd($fail);die();
        }
        
        
    $search_id=Session::get('search_id');
    
    
    
    
    
        
        
            
       
            
        
        if(isset($data->Body->HotelBooking)){
            $bh = $data->Body->HotelBooking;
            $booking_refer = $bh->BookingReference;
           
            // dd($booking_refer);
            $url = "http://xmldemo.travellanda.com/xmlv1/";
        $timeout = 20;
        $reqdata1 = "<Request>
        <Head>
        <Username>1124fb7683cf22910ea6e3c97473bb9c</Username><Password>rY8qm76g5dGH</Password><RequestType>HotelBookingDetails</RequestType></Head><Body>
        <BookingReference>$booking_refer</BookingReference>
        </Body></Request>";
        $data = array('xml' => $reqdata1);
        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
        );
        $ch = curl_init();
        $payload = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $rawdata = curl_exec($ch);
        // print_r(rawdata);die;
        $xml = simplexml_load_string($rawdata);
        $json = json_encode($xml);
        // print_r($json);
         $data = json_decode($json);
         $booked_data = $data->Body->Bookings->HotelBooking;
         $slug = "travellanda";
         
         







            $travellandacnfrm = array(

                'id'=>Session::get('inserted_id'),
                'travellandacnfrmRQ'=>$data_request,
                'travellandacnfrmRS'=>$json,
                );
                
                // print_r($travellandacnfrm);die();
                 $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/save_hotel_booking',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $travellandacnfrm,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
//       echo"<pre>";
    //  echo $response;
     curl_close($curl);
//end DB store//
$id = Session::get('search_id');
return redirect('voucher/'.$id.'/'.$slug.'');die();
$num = 1;   //for travellanda//
// return redirect('hotel_booking/'.$num.'/'.$id.'');die();

//             // return view('template/frontend/pages/booking_voucher',compact('booked_data','slug'));
//         }else{
//           return view('template/frontend/pages/booking_fail');
//         }
        }
        
    
     
    
 
}


if($slug == "hotelbeds")
{
   
   
   $lead_passenger_details=Session::get('lead_passenger_details');
    
   
   $hotel_beds_select=Session::get('hotel_beds_select');
   $hotel_beds_select=json_decode($hotel_beds_select);
   
  
//   print_r($hotel_beds_select);die();
   
   
   $data_res = '{
    
    "holder": {
        "name": "'. $lead_passenger_details->lead_first_name .'",
        "surname": "'. $lead_passenger_details->lead_last_name .'"
    },
    "rooms": [
    ';
    $room_count=1;
    foreach($hotel_beds_select->hotel->rooms as $rooms)
    {
        
    foreach($rooms->rates as $rates)
    {
        
  $data_res .='
    
        {
           "rateKey": "'.$rates->rateKey.'",
 "paxes": [
 
 
        
        ';
        
        
        $count=1;
        $count1=1;
        $other_adults= Session()->get('other_adults');
        if(isset($other_adults))
        {
            
       
        foreach($other_adults as $other_adults)
    {
        if($rates->adults >= $count)
        {
            
       
       $data_res .='
       
                {
                    "roomId": "'.$room_count.'",
                    "type": "AD",
                    "name": "'.$other_adults->other_first_name.'",
                    "surname": "'.$other_adults->other_last_name.'"
                },
                ';
                
        }
     $count=$count+1;
      $count1=$count1+1;
    
   }
    $data_res= substr_replace($data_res, '', strrpos($data_res, ','), 1);
   
    }
    else
    {
       $data_res .='
       
                {
                    "roomId": "'.$room_count.'",
                    "type": "AD",
                    "name": "'.$lead_passenger_details->lead_first_name.'",
                    "surname": "'.$lead_passenger_details->lead_last_name.'"
                },
                '; 
    }
        $data_res .='        
        ]
        }, 
      ';
      
    
    }
    
}
  $data_res= substr_replace($data_res, '', strrpos($data_res, ','), 1);
    $data_res .='
       
   ],
   
    
     ';
    
   $data_res .='
  
    "clientReference": "IntegrationAgency",
    "remark": "Booking remarks are to be written here.",
    "tolerance": 2
}';


   

   
   
//  print_r($data_res);die();  
   

// $data=$data;
//   $data=json_encode($data);
//   print_r($data);die;
$signature = pr($data_res);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/bookings',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data_res,
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
// echo $response;
curl_close($curl);  
$res=json_decode($response);
// print_r($res);die();


$search_id=Session::get('search_id');


$data = array(
     'hotelbedcnfrmRQ'=>$data_res,
                'search_id'=>$search_id,
                'hotelbedcnfrmRS'=>$response,
               
                );
                
                // print_r($travellandacnfrm);die();
                 $curl = curl_init();
        
            $endpoint_url = config('endpoint_project');
            curl_setopt_array($curl, array(
     CURLOPT_URL => $endpoint_url.'/api/hotelbed_booking',
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
//       echo"<pre>";
    //  echo $response;
     curl_close($curl);
//end DB store//




 
    



return redirect('voucher/'.$id.'/'.$slug.'');die();

}



}

public function voucher($id,$slug)
{
    
    $data = array(
        'provider'=>$slug,
        'id'=>$id,
       
        );
        
        // print_r($data);die();
    
    $curl = curl_init();
   
     curl_setopt_array($curl, array(
     CURLOPT_URL => 'https://admin.synchronousdigital.com/api/hotel_voucher/{$id}/{$slug}',
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
    $res=json_decode($response);
    $data=$res->data;
    $data1=$res->data->hotelbedcnfrmRS;
   $hotelbeds=json_decode($data1);
   $data2=$res->data->travellandacnfrmRS;
    $travellanda=json_decode($data2);
//   print_r($travellanda);die();
   return view('template/frontend/pages/booking_voucher',compact('data','hotelbeds','travellanda'));
}





public function booking_cancle($id,$slug){
    // dd($slug);
    if($slug =='travellanda'){
        
        $url = "http://xmldemo.travellanda.com/xmlv1/";
        $timeout = 20;
        $reqdata = "<Request>
        <Head>
        <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
        <Password>rY8qm76g5dGH</Password>
        <RequestType>HotelBookingCancel</RequestType>
        </Head><Body>
<BookingReference>$id</BookingReference>
</Body></Request>";
        $data = array('xml' => $reqdata);
        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
        );
        $ch = curl_init();
        $payload = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $rawdata = curl_exec($ch);
        // print_r($rawdata);die;
        $xml = simplexml_load_string($rawdata);
        $json = json_encode($xml);
        // print_r($json);
        $data = json_decode($json);
    //   print_r($json);die();
        if(isset($data->Body->HotelBooking->BookingReference)){
        $booking_ref = $data->Body->HotelBooking->BookingReference;
          
          
           $url = "http://xmldemo.travellanda.com/xmlv1/";
        $timeout = 20;
        $reqdata = "<Request>
        <Head>
        <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
        <Password>rY8qm76g5dGH</Password>
        <RequestType>HotelBookingDetails</RequestType>
        </Head><Body>
        <BookingReference>$booking_ref</BookingReference>
        </Body></Request>";
        $data = array('xml' => $reqdata);
        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
        );
        $ch = curl_init();
        $payload = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $rawdata = curl_exec($ch);
        // print_r(rawdata);die;
        $xml = simplexml_load_string($rawdata);
        $json = json_encode($xml);
        // print_r($json);die();
         $data = json_decode($json);
            //  print_r($data);die;
             $booked_data = $data->Body->Bookings->HotelBooking;
            //  dd($booked_data);
            
            $travellandacancel = json_encode($booked_data);
            
            
            $search_id = Session::get('search_id');
           
                 
                 $cnl_policy=array(
                     'id'=>$search_id,
                     'travellanda_cancellation_api_response'=>$json,
                 'booking_status'=> 'Cancelled'
                     
                     );
                 
                 
                 
                 
                 
                 $curl = curl_init();
    
          $data2 = $cnl_policy;
          curl_setopt_array($curl, array(
             CURLOPT_URL => 'https://admin.synchronousdigital.com/api/save_hotel_booking',
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
        //   echo $response;
          curl_close($curl);
       
                 
             return redirect()->back()->with('msg', 'Your Cancellation Request Has Been Successful');
        }
        else
        {
            return Redirect::back()->withErrors(['msg', 'Your Cancellation Request Has Been Failed']);
        }
             
    }
    
     


}

public function hotel_bed_cancelliation($id){
   
// $signature = pr($id);

$refrence=$id;
// print_r($refrence);die();

$apiKey = '833583586f0fd4fccd3757cd8a57c0a8';
        $secret = 'ae2dc887d0';
        $signature = hash("sha256", $apiKey.$secret.time());


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/bookings/'.$id.'?cancellationFlag=CANCELLATION',

  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'DELETE',
 
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
// echo $response;
curl_close($curl);  
$res=json_decode($response);
// print_r($res);die();

$search_id=Session::get('search_id');
    
    
    $data = array(
        'search_id'=>$search_id,
        'hotelbedcnfrmRS'=>$response,
       
        );
        
        // print_r($data);die();
    
    $curl = curl_init();
   
     curl_setopt_array($curl, array(
     CURLOPT_URL => 'https://admin.synchronousdigital.com/api/hotel_bed_cancelliation',
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
    
    
    
        
        return redirect()->back();

}







// public function voucher($id,$slug){
public function hotel_booking($num,$id){
 
//  dd($id);
 if($num == "1"){
//  $searched_data = DB::table('hotel_booking')->where('search_id',$id)->get();
//  $decode = json_decode($searched_data);
// //  dd($decode);
//  $travellandacnfrm = $decode[0]->travellandacnfrmRS;
//  $book_data = json_decode($travellandacnfrm);
// //   dd($book_data);
//  $booked_data = $book_data->Body->Bookings->HotelBooking;

 return view('template/frontend/pages/booking_voucher',compact('num','id'));
 }
 
 
 if($num == "2"){
//  $searched_data = DB::table('hotel_booking')->where('search_id',$id)->get();
//  $decode = json_decode($searched_data);
// //  dd($decode);
//  $hotelbedscnfrm = $decode[0]->hotelbedcnfrmRS;
//  $book_data = json_decode($hotelbedscnfrm);
//  $hotelbed = $book_data->hotelbedcnfrmRS;
//  $hotelbed_data = json_decode($hotelbed);
// dd($num);
  return view('template/frontend/pages/booking_voucher',compact('num','id'));
 }
 
 
//  if($slug == "webbeds"){
//  $searched_data = DB::table('hotel_booking')->where('search_id',$id)->get();
//  $decode = json_decode($searched_data);
//  dd($decode);
//  $hotelbedscnfrm = $decode[0]->hotelbedcnfrmRS;
//  $book_data = json_decode($hotelbedscnfrm);
//  $hotelbed = $book_data->hotelbedcnfrmRS;
//  $hotelbed_data = json_decode($hotelbed);
//  return view('template/frontend/pages/booking_voucher',compact('data','slug'));
//  }
}


}
