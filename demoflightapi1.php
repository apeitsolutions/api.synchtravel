<?php

  $session_id=flightCreateSessionId();
 //print_r($session_id);die();
 $start_time = microtime(true); // Record the start time
 $data=array(
    "OriginDestinationInformations"=>[
        array(
            "DepartureDateTime"=>"2023-11-29 T00:00:00",
        "OriginLocationCode"=>"JFK",
        "DestinationLocationCode"=>"DXB",
            ),
       ],
    "TravelPreferences"=>array(
        "MaxStopsQuantity"=> "All",
        "AirTripType"=>"OneWay"
        ),
        "PricingSourceType"=>"All",
        "IsRefundable"=>"false",
        "PassengerTypeQuantities"=>[
            array(
            "Code"=> "ADT",
            "Quantity"=> 1
            )
            ],
       "RequestOptions"=> "fifty",
        "NearByAirports"=> "false",
        "Target"=> "Test",
        "ConversationId"=> "742449771213089",
     
     
     );
 $rqdata=json_encode($data);
//print_r($rqdata);die();
 
  
   $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
            $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/Search/Flight");
            curl_setopt($ch, CURLOPT_PORT , 443);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); // Connection timeout in seconds
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $rqdata);
            $response = curl_exec($ch);
            $info =curl_errno($ch)> 0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
            //print_r($info);
            curl_close($ch);
            $end_time = microtime(true); // Record the end time
    $elapsed_time = $end_time - $start_time; // Calculate the elapsed time in seconds
    echo $response;
            echo $elapsed_time;  die();
            
  
  $data='
  
  {
  "OriginDestinationInformations": [
    {
      "DepartureDateTime": "2023-09-01T00:00:00",
      "OriginLocationCode": "BLR",
      "DestinationLocationCode": "DXB"
    },     
    {       
      "DepartureDateTime": "2023-09-05T00:00:00",
      "OriginLocationCode": "DXB",
      "DestinationLocationCode": "JFK" 
    },
    {
      "DepartureDateTime": "2023-09-10T00:00:00",
      "OriginLocationCode": "JFK",
      "DestinationLocationCode": "DXB"
    }   
  ],   
  "TravelPreferences": {     
    "MaxStopsQuantity": "All",     
    "CabinPreference": "Y",     
    "Preferences": {       
      "CabinClassPreference": {         
        "CabinType": "Y",         
        "PreferenceLevel": "Preferred"       
      }
    },     
    "AirTripType": "OneWay"
  },
  "PricingSourceType": "All",
  "IsRefundable": false,
  "PassengerTypeQuantities": [     
    {       
      "Code": "ADT",
      "Quantity": 1     
    }   
  ],
  "RequestOptions": "Hundred",
  "Target": "Test" 
}
  
  ';
               //print_r($data);die();          
            // $data = json_encode($data); // Encode the data array into a JSON string
            $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
            $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v2/multiCityFaresBETA/Search/Flight");
            curl_setopt($ch, CURLOPT_PORT , 443);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); // Connection timeout in seconds
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($ch);
            $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
            //print_r($info);
            curl_close($ch);
            print_r(json_decode($response));die(); 
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  die();
  
  
  
//   $data ='{
//               "OriginDestinationInformations": [
//                 {
//                   "DepartureDateTime": "2023-08-23 T00:00:00",
//                   "OriginLocationCode": "TRZ",
//                   "DestinationLocationCode": "SIN"
//             },
            
//               ],
//               "TravelPreferences": {
//                 "MaxStopsQuantity": "OneStop",
//                 "AirTripType": "OneWay"
//               },
//               "PricingSourceType": "All",
//               "IsRefundable": false,
//               "PassengerTypeQuantities": [
//                 {
//                   "Code": "ADT",
//                   "Quantity": 1
//                 }
                 
              
//               ],
//               "RequestOptions": "fifty",
//               "NearByAirports": false,
//               "Target": "Test",
//               "ConversationId": "108046543465893",
              
//             }';
             
//              //print_r($data);die();          
//             // $data = json_encode($data); // Encode the data array into a JSON string
//             $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
//             $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
//             $ch = curl_init();
//             curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/Search/Flight");
//             curl_setopt($ch, CURLOPT_PORT , 443);
//             curl_setopt($ch, CURLOPT_VERBOSE, 0);
//             curl_setopt($ch, CURLOPT_HEADER, 0);
//             curl_setopt($ch, CURLOPT_POST, 1);
//             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
//             curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); // Connection timeout in seconds
//             curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
//             curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//             $response = curl_exec($ch);
//             $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//             //print_r($info);
//             curl_close($ch);
//             print_r(json_decode($response));die();
//   $data =' {
// "UniqueID": "MF23800423"
// }';
             
//              //print_r($data);die();          
//             // $data = json_encode($data); // Encode the data array into a JSON string
//             $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
//             $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
//             $ch = curl_init();
//             curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/TripDetails/MF23800423");
//             curl_setopt($ch, CURLOPT_PORT , 443);
//             curl_setopt($ch, CURLOPT_VERBOSE, 0);
//             curl_setopt($ch, CURLOPT_HEADER, 0);
      
//             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
//             curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); // Connection timeout in seconds
//             curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
            
//             $response = curl_exec($ch);
//             $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//             //print_r($info);
//             curl_close($ch);
//             print_r($response);die();
//  $data='
//           {
//          "ActionType": "ChooseAlternative",
//           "MFRef": "MF23486323",
//           "RejectOption": "Earlierflightoptions",
//           "FlightOptions": [
//             {
//               "FlightNumber": 2,
//               "AirlineCode": "EK",
//               "TravelDate": "23-7-2023",
//               "DepartureTime": "17:50",
//               "CityPair": "LHR-DXB"
//             }
//           ],
//         "Comments": "Suggest me whether this option is available"
//         }';
//         //print_r($data);die();
//           $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
//         $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/ScheduleChange");
//         curl_setopt($ch, CURLOPT_PORT , 443);
//         curl_setopt($ch, CURLOPT_VERBOSE, 0);
//         curl_setopt($ch, CURLOPT_HEADER, 0);
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
//         $response = curl_exec($ch);
//         $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//         //print_r($info);
//         curl_close($ch);
//         echo $response;die();
 
 
 
 
 //
//  $session_id=flightCreateSessionId();
//  $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
//         $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1.1/TripDetails/MF23818823");
//         curl_setopt($ch, CURLOPT_PORT , 443);
//         curl_setopt($ch, CURLOPT_VERBOSE, 0);
//         curl_setopt($ch, CURLOPT_HEADER, 0);
        
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        
//         $response = curl_exec($ch);
//         $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//         //print_r($info);
//         curl_close($ch);
// print_r(json_decode($response));die();
        
 
//  $session_id=flightCreateSessionId();
 
//   $data ='
// {
//   "UniqueID": "MF23818823",
//   "Target": "Test",
// }
// ';
// //print_r($data);die();
//   $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
// $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/OrderTicket");
// curl_setopt($ch, CURLOPT_PORT , 443);
// curl_setopt($ch, CURLOPT_VERBOSE, 0);
// curl_setopt($ch, CURLOPT_HEADER, 0);
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// $response = curl_exec($ch);
// $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
// //print_r($info);
// curl_close($ch);
// echo $response; die(); 
 
 
 
 
 
 
  //  GetExchangeQuote PTR (Post Ticketing Request)


//     $session_id=flightCreateSessionId();
    
    $data ='
        {
  "ptrType": "GetExchangeQuote",
  "MFRef": "MF23827823",
  "PTRCategory": "None",
  "PTRStatus": "InProcess",
  "PTRId": 10817,
  "ShowProcessingMethod": "False",
  "Page": 1
}
        ';
    print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/Search/PostTicketingRequest");
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;die();
        
   //  Refund PTR (Post Ticketing Request)


    // $session_id=flightCreateSessionId();
    
    $data ='
        {
          "ptrType": "Refund",
          "mFRef": "MF23827823",
          "passengers": [
            {
              "firstName": "ASIF",
              "lastName": "KAMAL",
              "title": "Mr",
              "eTicket": "TKT367820",
              "passengerType": "ADT"
            },
            {
              "firstName": "SAIM",
              "lastName": "SAEED",
              "title": "MSTR",
              "eTicket": "TKT367821",
              "passengerType": "CHD"
            },
          ],
          "AdditionalNote": "Proceed direct Cancellation as per penalty"
 
        }
        ';
        print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/PostTicketingRequest");
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;die();
        
  //  RefundQuote PTR (Post Ticketing Request)


    // $session_id=flightCreateSessionId();
    
    $data ='
        {
          "ptrType": "RefundQuote",
          "mFRef": "MF23827823",
          "passengers": [
            {
              "firstName": "ASIF",
              "lastName": "KAMAL",
              "title": "Mr",
              "eTicket": "TKT367820",
              "passengerType": "ADT"
            },
            {
              "firstName": "SAIM",
              "lastName": "SAEED",
              "title": "MSTR",
              "eTicket": "TKT367821",
              "passengerType": "CHD"
            },
          ],
          "AdditionalNote": "Pls quote refund"
 
        }
        ';
        print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/PostTicketingRequest");
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;die();
    
    
    
    
    
    
    
    
//  //  ReissueQuote PTR (Post Ticketing Request)


//     $session_id=flightCreateSessionId();
    
    $data ='
        {
          "ptrType": "ReissueQuote",
          "mFRef": "MF23827823",
          "AllowChildPassenger": true,
          "reissueQuoteRequestType": "Segment",
          "passengers": [
          {
              "firstName": "ASIF",
              "lastName": "KAMAL",
              "title": "Mr",
              "eTicket": "TKT367820",
              "passengerType": "ADT"
            },
            {
              "firstName": "SAIM",
              "lastName": "SAEED",
              "title": "MSTR",
              "eTicket": "TKT367821",
              "passengerType": "CHD"
            },
          ],
         "originDestinations": [
        {
          "originLocationCode": "BKK",
          "destinationLocationCode": "USM",
          "cabinPreference": "Y",
          "departureDateTime": "2023-09-02T06:15:00",
          "flightNumber": 101,
          "airlineCode": "PG"
        },
        {
          "originLocationCode": "USM",
          "destinationLocationCode": "CNX",
          "cabinPreference": "Y",
          "departureDateTime": "2023-09-02T10:00:00",
          "flightNumber": 241,
          "airlineCode": "UK"
        }
        
  ], 
        }
        ';
        print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/PostTicketingRequest");
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;die();
    
 //  Void PTR (Post Ticketing Request)


    // $session_id=flightCreateSessionId();
    
    $data ='
        {
          "ptrType": "Void",
          "mFRef": "MF23827823",
          "AllowChildPassenger": false,
          "passengers": [
            {
              "firstName": "ASIF",
              "lastName": "KAMAL",
              "title": "Mr",
              "eTicket": "TKT367820",
              "passengerType": "ADT"
            },
            {
              "firstName": "SAIM",
              "lastName": "SAEED",
              "title": "MSTR",
              "eTicket": "TKT367821",
              "passengerType": "CHD"
            },
          ],
        }
        ';
        print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/PostTicketingRequest");
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;die();
//  VoidQuote PTR (Post Ticketing Request)


    // $session_id=flightCreateSessionId();
    
    $data ='
        {
          "ptrType": "VoidQuote",
          "mFRef": "MF23827823",
          "AllowChildPassenger": false,
          "passengers": [
            {
              "firstName": "ASIF",
              "lastName": "KAMAL",
              "title": "Mr",
              "eTicket": "TKT367820",
              "passengerType": "ADT"
            },
            {
              "firstName": "SAIM",
              "lastName": "SAEED",
              "title": "MSTR",
              "eTicket": "TKT367821",
              "passengerType": "CHD"
            },
            
          ],
        }
        ';
      //print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/PostTicketingRequest");
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;die();   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
       $data='
   {
  "UniqueID": "MF23467023",
  "Target": "Test",
  "ConversationId": "892118112282987"
}
   ';
 //print_r($data);die();          
 //$data = json_encode($data); // Encode the data array into a JSON string
$authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
$headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/AirTicketOrderStatus");
curl_setopt($ch, CURLOPT_PORT , 443);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//print_r($info);
curl_close($ch);
echo $response;die();
print_r(json_decode($response));die();  
        $data='
   {
  "ptrType": "None",
  "MFRef": "MF23463323",
  "PTRCategory": "None",
  "PTRStatus": "None",
  "PTRId": 0,
  "ShowProcessingMethod": "False",
  "Page": 1
}
';
   
   //print_r($data);die();          
 //$data = json_encode($data); // Encode the data array into a JSON string
$authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
$headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/Search/PostTicketingRequest");
curl_setopt($ch, CURLOPT_PORT , 443);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//print_r($info);
curl_close($ch);
echo $response;die();
print_r(json_decode($response));die(); 
 
 
 
 
 
   $session_id=flightCreateSessionId();
        $data='
   {
  "CategoryId": "Urgent",
  "Target": "Test",
  "ConversationId": "984535453754033",
  "ScheduleChangeType": "Accept"
}
';
   
   //print_r($data);die();          
 //$data = json_encode($data); // Encode the data array into a JSON string
$authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
$headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/Queues");
curl_setopt($ch, CURLOPT_PORT , 443);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//print_r($info);
curl_close($ch);
echo $response;die();
print_r(json_decode($response));die(); 




     $data='
   {
  "Page":2
}
';
   
   //print_r($data);die();          
 //$data = json_encode($data); // Encode the data array into a JSON string
$authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
$headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/Search/CreditNote");
curl_setopt($ch, CURLOPT_PORT , 443);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//print_r($info);
curl_close($ch);
echo $response;die();
print_r(json_decode($response));die(); 


   $data='
   {
  "UniqueID": "MF23366223",
  "Notes": [
    "hlw"
  ],
  "Target": "Test",
  "ConversationId": "984535453754033"
}
';
   
   //print_r($data);die();          
 //$data = json_encode($data); // Encode the data array into a JSON string
$authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
$headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/BookingNotes");
curl_setopt($ch, CURLOPT_PORT , 443);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//print_r($info);
curl_close($ch);
echo $response;die();
print_r(json_decode($response));die();  
   
   
   
   $data='
   {
  "UniqueID": "MF23366223",
  "Target": "Test",
  "ConversationId": "984535453754033"
}
   ';
 //print_r($data);die();          
 //$data = json_encode($data); // Encode the data array into a JSON string
$authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
$headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/AirTicketOrderStatus");
curl_setopt($ch, CURLOPT_PORT , 443);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//print_r($info);
curl_close($ch);
echo $response;die();
print_r(json_decode($response));die();   

$data ='{
   "OriginDestinationInformations":[
      {
         "DepartureDateTime":"2023-07-24T00:00:00",
         "OriginLocationCode":"BLR",
         "DestinationLocationCode":"DXB"
      },
      {
         "DepartureDateTime":"2023-07-27T00:00:00",
         "OriginLocationCode":"DXB",
         "DestinationLocationCode":"JFK"
      },
      {
         "DepartureDateTime":"2023-07-29T00:00:00",
         "OriginLocationCode":"JFK",
         "DestinationLocationCode":"BLR"
      }
   ],
   "TravelPreferences":{
      "MaxStopsQuantity":"All",
      "CabinPreference":"Y",
      "Preferences":{
         "CabinClassPreference":{
            "CabinType":"Y",
            "PreferenceLevel":"Preferred"
         }
      },
      "AirTripType":"Circle"
   },
   "PricingSourceType":"All",
   "NearByAirports":true,
   "IsResidentFare":false,
   "PassengerTypeQuantities":[
      {
         "Code":"ADT",
         "Quantity":1
      }
   ],
   "RequestOptions":"Hundred",
   "Target":"Test",
   "ConversationId":"String"
}';
 
 //print_r($data);die();          
 //$data = json_encode($data); // Encode the data array into a JSON string
$authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
$headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/MultiCityFares/Search/Flight");
curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v2/multiCityFaresBETA/Search/Flight");
curl_setopt($ch, CURLOPT_PORT , 443);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//print_r($info);
curl_close($ch);
echo $response;die();
print_r(json_decode($response));die();







$data='

{
  "ListFaresourcecode": [
    {
      "FareSourceCode": "ZE9EUzBMVmsxT3JTcDMyR1RjN0xVL1ZkSzVsZUNWNlRlbCswMS9LY1VxRHE0UXBseXdIQVR5NlZhTnJjaUcwVUUrd3Zsdk41czhMS2lTYjFpbjBHS2Y5N0N5NVljUUtienFKZEpRM3p6R283c2hicGhKekhvRGdoWkZRNlBpYUhwdEZWNDhmMmk4eGdjT2pweEM5UG9nPT0="
    },
     {
      "FareSourceCode": "bmhUZ1RKdEwyeUFtclpPeFNYdGVFNXBUMkxmSmtucGxtUEZSN0NyamFRRzE1RlhSeEZuUS85TEprNWovVUw5WlBTeXBXWVRzL091OXlxQS9CVDFrQ3VVQ0M5MnBXTHMvWUtOUGdSY2gxTjZIeElUQWJqWmZlNlVPU0JoM1ZtVDhLanZZbzViYzVXdi96c09tV0Ivcmx3PT0="
    },
     {
      "FareSourceCode": "ZE9EUzBMVmsxT3JTcDMyR1RjN0xVL1ZkSzVsZUNWNlRlbCswMS9LY1VxRHE0UXBseXdIQVR5NlZhTnJjaUcwVUUrd3Zsdk41czhMS2lTYjFpbjBHS2Y5N0N5NVljUUtienFKZEpRM3p6R283c2hicGhKekhvRGdoWkZRNlBpYUhwdEZWNDhmMmk4eGdjT2pweEM5UG9nPT0="
    },
     {
      "FareSourceCode": "OUlxUjd3SGhtZm15OFEvMW1MUDFSTkJGUHZCN2V0Mk5GRTVtUnVGNWkzeTd5aUFqQlFMQmxJOGQzR25SVjlRNU5Odng5K1JNNmloNVNlZHlVZy9BeHN0cVJvK0ZDNDBQK3ptVHdhZjV4RGo4QjBBVWNBbnV5cFQ3dlowdkpxVEdjc3NIY2RiSmlNaVNmbG1JTVRsdXJRPT0="
    },
 
  ],
  "Target": "Test",
  "ConversationId": "string"
}

';

 //print_r($data);die();          
 //$data = json_encode($data); // Encode the data array into a JSON string
$authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
$headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/MultiCityFares/Revalidation/Flight");
//curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/multiCityFaresBETA/Revalidate/Flight");
curl_setopt($ch, CURLOPT_PORT , 443);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
//print_r($info);
curl_close($ch);
echo $response;die();
print_r(json_decode($response));die();
















// search api

function flightCreateSessionId()
{
    $data='{
Password:"Synch@2023",
AccountNumber:"MCN001949",
UserName:"Synch_XML"
}';
//print_r($data);die();
   $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://restapidemo.myfarebox.com/api/CreateSession',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
//echo $response;die();
curl_close($curl);

$res=json_decode($response);
//print_r($res);die();

$session_id=$res->Data->SessionId; 
return $session_id;
}




?>
