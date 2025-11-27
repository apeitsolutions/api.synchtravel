<?php
$token_client='YgocUp360flerVSPCfFraE2eE8iopIsJ1HMwZqv2-MbNGK1vxYARPNTQ5vb13aD0wJaH3swRna8acDpMnf4SFit5ZHRHCsfzEI5oQ-LlrhqeUEkCy5pt2LRcJKO1lzf-POMp5hSY69l4vKHLTTCvLzZi4gJn4HYJDwJD44UEj8JYCmXKLOeartCdbuOsLT0iZNGc6W5L6gP-oKBgV7BclEfXbcdnMDkPky87Xve9QilaifPhijhoeBJcwff2lcg3ZAO9jz0ghP0Dz5Q1cU3vSdA4W5sqDdew4T6W1cPiQqlRBCoI-g6fG4FKsqtRml3wGKenDLCJ6XfHU6L5lpJa01b8TUm2EFTnAEK0Pte73oFyULbS7yKFoJX8E7JP-TOpVO6lt8YNL7XBT0RBUh8l32-vRSy5RlP1Awz4aUKmvDA4FXZpeu7NzFeLABOGQ66OWAX75d7al-gDDRvMGHwbCDgSK18kpzZbWbIVnlNraIb6uWL936WrBIFFsYPe';

if(isset($_POST)){

$case = $_POST['case'];
if($case =='search_flights')
{
search_flights();
}

if($case =='multicity_search_flights')
{
multicity_search_flights();
}
if($case =='return_search_flights')
{
return_search_flights();
}

if($case =='revalidation')
{
revalidation();
}

if($case =='flightfarerules')
{
flightfarerules();
}
if($case =='SeatMap')
{
SeatMap();
}

if($case =='bookflight')
{
bookflight();
}
if($case =='tripdetails')
{
tripdetails();
}

if($case =='CancelBookingflight')
{
CancelBookingflight();
}
if($case =='ViewReservationflight')
{
ViewReservationflight();
}
if($case =='ViewReservationflight_admin')
{
ViewReservationflight_admin();
}
if($case =='ScheduleChangeFlight')
{
ScheduleChangeFlight();
}
if($case =='OrderTicketFlight')
{
    OrderTicketFlight();
}
if($case =='OrderTicketFlight_admin')
{
    OrderTicketFlight_admin();
}
if($case =='ScheduleChangeAcceptRequest')
{
    ScheduleChangeAcceptRequest();
}

if($case =='VoidQuoteRequest')
{
    VoidQuoteRequest();
}
if($case =='flightTypeFillter')
{
    flightTypeFillter();
}
}


// search api

function flightCreateSessionId()
{
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
  CURLOPT_POSTFIELDS =>'{
Password:"Synch@2023",
AccountNumber:"MCN001949",
UserName:"Synch_XML"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$res=json_decode($response);
// print_r($res);die();

$session_id=$res->Data->SessionId; 
return $session_id;
}



function search_flights()
{
    global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
      
    
                $session_id=flightCreateSessionId();
                
                
                $DepartureDateTime= $_POST['DepartureDate'];
                $OriginLocationCode= $_POST['DepartureCode'];
                $DestinationLocationCode= $_POST['ArrivalCode'];
                $VendorPreferenceCodes =$_POST['AirlinesCode'];
                $adult=$_POST['adult'];
                $child=$_POST['child'];
                $infant=$_POST['infant'];
                $AirTripType=$_POST['AirTripType'];
                $ConversationId=$_POST['ConversationId'];
                $CabinPreference=$_POST['CabinType'];
                $MaxStopsQuantity=$_POST['MaxStopsQuantity'];
                
                $data ='{
              "OriginDestinationInformations": [
                {
                  "DepartureDateTime": "'.$DepartureDateTime.'",
                  "OriginLocationCode": "'.$OriginLocationCode.'",
                  "DestinationLocationCode": "'.$DestinationLocationCode.'"
            },
            
              ],
              "TravelPreferences": {
                "MaxStopsQuantity": "'.$MaxStopsQuantity.'",
                ';
                if($CabinPreference != 'no')
                {
                $data .='
                "CabinPreference": "'.$CabinPreference.'",
                "Preferences": {
                  "CabinClassPreference": {
                    "CabinType": "'.$CabinPreference.'",
                    "PreferenceLevel": "Preferred"
                  }
                },
                ';
                }
                $data .='
                "AirTripType": "'.$AirTripType.'"
              },
              "PricingSourceType": "All",
              "IsRefundable": false,
              "PassengerTypeQuantities": [
                {
                  "Code": "ADT",
                  "Quantity": '.$adult.'
                },
                ';
                
                if($child != 0)
                {
                $data .='
                {
                  "Code": "CHD",
                  "Quantity": '.$child.'
                },
                  '; 
                }
                if($infant != 0)
                {
                $data .='
                {
                  "Code": "INF",
                  "Quantity": '.$infant.'
                },
                  '; 
                }
                $data = substr_replace($data, '', strrpos($data, ','), 1);
              $data .=' 
              
              ],
              "RequestOptions": "fifty",
              "NearByAirports": false,
              "Target": "Test",
              "ConversationId": "'.$ConversationId.'",
              
            }';
             
            // print_r($data);die();          
            // $data = json_encode($data); // Encode the data array into a JSON string
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($ch);
            $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
            //print_r($info);
            curl_close($ch);
            echo $response;  
    
    }
    else
    {
        $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response; 
    }
    
   

 
}

function return_search_flights()
{
     global $token_client;
     
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
             $session_id=flightCreateSessionId();
            $DepartureDateTime= $_POST['DepartureDate'];
            $returnDepartureDateTime=$_POST['ReturnDepartureDate'];
            $OriginLocationCode= $_POST['DepartureCode'];
            $DestinationLocationCode= $_POST['ArrivalCode'];
            $VendorPreferenceCodes =$_POST['AirlinesCode'];
            $adult=$_POST['adult'];
            $child=$_POST['child'];
            $infant=$_POST['infant'];
            $AirTripType=$_POST['AirTripType'];
            $ConversationId=$_POST['ConversationId'];
            $CabinPreference=$_POST['CabinType'];
            
            $data ='{
          "OriginDestinationInformations": [
            {
              "DepartureDateTime": "'.$DepartureDateTime.'",
              "OriginLocationCode": "'.$OriginLocationCode.'",
              "DestinationLocationCode": "'.$DestinationLocationCode.'"
            },
            {
              "DepartureDateTime": "'.$returnDepartureDateTime.'",
              "OriginLocationCode": "'.$DestinationLocationCode.'",
              "DestinationLocationCode": "'.$OriginLocationCode.'"
            },
        
          ],
          "TravelPreferences": {
            "MaxStopsQuantity": "All",
            ';
                if($CabinPreference != 'no')
                {
                $data .='
                "CabinPreference": "'.$CabinPreference.'",
                "Preferences": {
                  "CabinClassPreference": {
                    "CabinType": "'.$CabinPreference.'",
                    "PreferenceLevel": "Preferred"
                  }
                },
                ';
                }
                $data .='
            "AirTripType": "'.$AirTripType.'"
          },
          "PricingSourceType": "Public",
          "IsRefundable": false,
          "PassengerTypeQuantities": [
            {
              "Code": "ADT",
              "Quantity": '.$adult.'
            },
            ';
            
            if($child != 0)
            {
            $data .='
            {
              "Code": "CHD",
              "Quantity": '.$child.'
            },
              '; 
            }
            if($infant != 0)
            {
            $data .='
            {
              "Code": "INF",
              "Quantity": '.$infant.'
            },
              '; 
            }
            $data = substr_replace($data, '', strrpos($data, ','), 1);
          $data .=' 
          
          ],
          "RequestOptions": "fifty",
          "NearByAirports": false,
          "Target": "Test",
          "ConversationId": "'.$ConversationId.'"
        }';
         
        //print_r($data);die();          
        // $data = json_encode($data); // Encode the data array into a JSON string
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;  
    }
    else
    {
        $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response; 
    }   
   

 
}

function revalidation()
{
    global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
              $session_id=flightCreateSessionId();
          $FareSourceCode=$_POST['FareSourceCode'];
            $ConversationId=$_POST['ConversationId'];
        
            
            $data ='{
            "FareSourceCode": "'.$FareSourceCode.'",
            "Target": "Test",
            "ConversationId": "'.$ConversationId.'"
         }';
         
         //print_r($data);die();          
        // $data = json_encode($data); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/Revalidate/Flight");
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
        echo $response;    
    }
     else
    {
        $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response; 
    }  
 
}
    
function flightfarerules()
{
     global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
              $session_id=flightCreateSessionId();
          $FareSourceCode=$_POST['FareSourceCode'];
            $ConversationId=$_POST['ConversationId'];
        
            
            $data ='{
            "FareSourceCode": "'.$FareSourceCode.'",
            "Target": "Test",
            "ConversationId": "'.$ConversationId.'"
         }';
         
        //print_r($data);die();          
        // $data = json_encode($data); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/FlightFareRules");
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
        echo $response;  
    }
    else
    {
        $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response; 
    }  
       
  }
  
function SeatMap()
{
    global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
               $session_id=flightCreateSessionId();
          $FareSourceCode=$_POST['FareSourceCode'];
            $ConversationId=$_POST['ConversationId'];
        
            
            $data ='{
            "FareSourceCode": "'.$FareSourceCode.'",
            "Target": "Test",
            "ConversationId": "'.$ConversationId.'"
         }';
         
         //print_r($data);die();          
        // $data = json_encode($data); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/SeatMap/Flight");
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
        echo $response; 
    }
    else
    {
        $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response; 
    }  
       
  }
function bookflight1()
{
 global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
                $session_id=flightCreateSessionId();
          $FareSourceCode=$_POST['FareSourceCode'];
          $PassengerTitle=$_POST['PassengerTitle'];
          $Gender=$_POST['Gender'];
          $PassengerFirstName=$_POST['PassengerFirstName'];
          $PassengerLastName=$_POST['PassengerLastName'];
          $DateOfBirth=$_POST['DateOfBirth'];
          $PassportNumber=$_POST['PassportNumber'];
          $ExpiryDate=$_POST['ExpiryDate'];
          $Country=$_POST['Country'];
          
          $Origin=$_POST['Origin'];
          $Destination=$_POST['Destination'];
          $FlightNumber=$_POST['FlightNumber'];
          $DepartureDateTime=$_POST['DepartureDateTime'];
          $PassengerNationality=$_POST['PassengerNationality'];
          $NationalID=$_POST['NationalID'];
          $CountryCode=$_POST['CountryCode'];
          $AreaCode=$_POST['AreaCode'];
          
          $PhoneNumber=$_POST['PhoneNumber'];
          $Email=$_POST['Email'];
          $PostCode=$_POST['PostCode'];
            
            
            $other_passenger_details=$_POST['other_passenger_details'];
            $other_passenger_details=json_decode($other_passenger_details);
            $child_details=$_POST['child_details'];
            $child_details=json_decode($child_details);
            $infant_details=$_POST['infant_details'];
            $infant_details=json_decode($infant_details);
            
            
            
            $data='{
          "FareSourceCode": "'.$FareSourceCode.'",
           "TravelerInfo": {
            "AirTravelers": [
              {
                "PassengerType": "ADT",
                "Gender": "'.$Gender.'",
                "PassengerName": {
                  "PassengerTitle": "'.$PassengerTitle.'",
                  "PassengerFirstName": "'.$PassengerFirstName.'",
                  "PassengerLastName": "'.$PassengerLastName.'"
                },
                "DateOfBirth": "'.$DateOfBirth.'",
                "Passport": {
                  "PassportNumber": "'.$PassportNumber.'",
                  "ExpiryDate": "'.$ExpiryDate.'",
                  "Country": "'.$Country.'"
                },
                   "SpecialServiceRequest": {
                  "SeatPreference": "Any",
                  "MealPreference": "Any",
                  "RequestedSegments": [
                    {
                      "Origin": "'.$Origin.'",
                      "Destination": "'.$Destination.'",
                      "FlightNumber": "'.$FlightNumber.'",
                      "DepartureDateTime": "'.$DepartureDateTime.'",
                                   "RequestSSRs": [
                        {
                          "SSRCode": "Any",
                          "FreeText": "Meal MOML"
                        }
                      ]
                    }
                  ]
                },
               "PassengerNationality": "'.$PassengerNationality.'",
               "NationalID": "'.$NationalID.'"
              },
              ';
              if(isset($other_passenger_details))
              {
                  foreach($other_passenger_details as $other_details)
                  {
                      
                  }
               $data .='  
                {
                "PassengerType": "ADT",
                "Gender": "'.$other_details->other_gender.'",
                "PassengerName": {
                  "PassengerTitle": "'.$other_details->other_title.'",
                  "PassengerFirstName": "'.$other_details->other_first_name.'",
                  "PassengerLastName": "'.$other_details->other_last_name.'"
                },
                "DateOfBirth": "'.$other_details->other_date_of_birth.'",
                "Passport": {
                  "PassportNumber": "'.$other_details->other_passport_no.'",
                  "ExpiryDate": "'.$other_details->other_passport_expiry_date.'",
                  "Country": "'.$other_details->other_passport_country.'"
                },
                   "SpecialServiceRequest": {
                  "SeatPreference": "Any",
                  "MealPreference": "Any",
                  "RequestedSegments": [
                    {
                      "Origin": "'.$Origin.'",
                      "Destination": "'.$Destination.'",
                      "FlightNumber": "'.$FlightNumber.'",
                      "DepartureDateTime": "'.$DepartureDateTime.'",
                                   "RequestSSRs": [
                        {
                          "SSRCode": "Any",
                          "FreeText": "Meal MOML"
                        }
                      ]
                    }
                  ]
                },
               "PassengerNationality": "'.$other_details->other_passport_country.'",
               "NationalID": "'.$other_details->other_passport_country.'"
              },
                '; 
              }
              if(isset($child_details))
              {
                  foreach($child_details as $child_d)
                  {
                      
                  }
               $data .='  
                {
                "PassengerType": "CHD",
                "Gender": "'.$child_d->child_gender.'",
                "PassengerName": {
                  "PassengerTitle": "'.$child_d->child_title.'",
                  "PassengerFirstName": "'.$child_d->child_first_name.'",
                  "PassengerLastName": "'.$child_d->child_last_name.'"
                },
                "DateOfBirth": "'.$child_d->child_date_of_birth.'",
                "Passport": {
                  "PassportNumber": "'.$child_d->child_passport_no.'",
                  "ExpiryDate": "'.$child_d->child_passport_expiry_date.'",
                  "Country": "'.$child_d->child_passport_country.'"
                },
                   "SpecialServiceRequest": {
                  "SeatPreference": "Any",
                  "MealPreference": "Any",
                  "RequestedSegments": [
                    {
                      "Origin": "'.$Origin.'",
                      "Destination": "'.$Destination.'",
                      "FlightNumber": "'.$FlightNumber.'",
                      "DepartureDateTime": "'.$DepartureDateTime.'",
                                   "RequestSSRs": [
                        {
                          "SSRCode": "Any",
                          "FreeText": "Meal MOML"
                        }
                      ]
                    }
                  ]
                },
               "PassengerNationality": "'.$child_d->child_passport_country.'",
               "NationalID": "'.$child_d->child_passport_country.'"
              },
                '; 
              }
              if(isset($infant_details))
              {
                  foreach($infant_details as $infant_de)
                  {
                      
                  }
               $data .='  
                {
                "PassengerType": "INF",
                "Gender": "'.$infant_de->infant_gender.'",
                "PassengerName": {
                  "PassengerTitle": "'.$infant_de->infant_title.'",
                  "PassengerFirstName": "'.$infant_de->infant_first_name.'",
                  "PassengerLastName": "'.$infant_de->infant_last_name.'"
                },
                "DateOfBirth": "'.$infant_de->infant_date_of_birth.'",
                "Passport": {
                  "PassportNumber": "'.$infant_de->infant_passport_no.'",
                  "ExpiryDate": "'.$infant_de->infant_passport_expiry_date.'",
                  "Country": "'.$infant_de->infant_passport_country.'"
                },
                   "SpecialServiceRequest": {
                  "SeatPreference": "Any",
                  "MealPreference": "Any",
                  "RequestedSegments": [
                    {
                      "Origin": "'.$Origin.'",
                      "Destination": "'.$Destination.'",
                      "FlightNumber": "'.$FlightNumber.'",
                      "DepartureDateTime": "'.$DepartureDateTime.'",
                                   "RequestSSRs": [
                        {
                          "SSRCode": "Any",
                          "FreeText": "Meal MOML"
                        }
                      ]
                    }
                  ]
                },
               "PassengerNationality": "'.$infant_de->infant_passport_country.'",
               "NationalID": "'.$infant_de->infant_passport_country.'"
              },
                '; 
              }
              $data = substr_replace($data, '', strrpos($data, ','), 1);
              $data .='
            ],
            "CountryCode": "'.$CountryCode.'",
            "AreaCode": "'.$AreaCode.'",
            "PhoneNumber": "'.$PhoneNumber.'",
            "Email": "'.$Email.'",
            "PostCode": "'.$PostCode.'"
          },
          "Target": "Test",
          "PaymentCardInfo": {
            "cardScheme": "Visa",
            "cardType": "Credit",
            "cardNumber": "4929527253694568",
            "cvv": 568,
            "cardValidFrom": {
              "month": "Jan",
              "year": 2015
            },
            "cardExpiry": {
              "month": "Feb",
              "year": 2024
            },
            "cardHolderName": "jamshaidhanif",
            "billingAddress": {
              "customerTitle": "mr",
              "customerFirstName": "jamshaid",
              "customerMiddleName": "jamshaid",
              "customerLastName": "hanif",
              "address1": "nullable",
              "address2": "nullable",
              "address3": "nullable",
              "city": "london",
              "state": "nullable",
              "country": "GB",
              "phone": "4438582397583",
              "zip": "nullable",
              "emailId": "jamshaid@gmail.com"
            },
            "securePassword": "35324112",
            "computerIP": "192.168.10.2"
          },
          "PaymentReferences": {
            "PaymentID": "",
            "PaymentParams": [
              {
                "Key": "",
                "Value": "",
                "Type": "ReplyField"
              }
            ]
          },
          "ClientReferenceNo": "383858397",
          "ConversationId": "383858397"
        }';
            //print_r($data);die();          
        // $data = json_encode($data); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/Book/Flight");
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
        echo $response;
    }
    else
    {
       $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;  
    }

}

function bookflight()
{
 global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
              $session_id=flightCreateSessionId();
              
        
          $PassengerTitle=$_POST['PassengerTitle'];
          $Gender=$_POST['Gender'];
          $PassengerFirstName=$_POST['PassengerFirstName'];
          $PassengerLastName=$_POST['PassengerLastName'];
          $DateOfBirth=$_POST['DateOfBirth'];
          $PassportNumber=$_POST['PassportNumber'];
          $ExpiryDate=$_POST['ExpiryDate'];
          $Country=$_POST['Country'];
          
          $Origin=$_POST['Origin'];
          $Destination=$_POST['Destination'];
          $FlightNumber=$_POST['FlightNumber'];
          $DepartureDateTime=$_POST['DepartureDateTime'];
          $PassengerNationality=$_POST['PassengerNationality'];
          $NationalID=$_POST['NationalID'];
          $CountryCode=$_POST['CountryCode'];
          $AreaCode=$_POST['AreaCode'];
          
          $PhoneNumber=$_POST['PhoneNumber'];
          $Email=$_POST['Email'];
          $PostCode=$_POST['PostCode'];
          $ConversationId=$_POST['ConversationId'];
            
            
            $revalidation_res=$_POST['revalidation_res'];
           
            $revalidation_res=json_decode($revalidation_res);
             //print_r($revalidation_res);die();
            $other_passenger_details=$_POST['other_passenger_details'];
            $other_passenger_details=json_decode($other_passenger_details);
            $child_details=$_POST['child_details'];
            $child_details=json_decode($child_details);
            $infant_details=$_POST['infant_details'];
            $infant_details=json_decode($infant_details);
            
            $extra_services_details=$_POST['extra_services_details'];
            $extra_services_details=json_decode($extra_services_details);
            $other_extra_services_details=$_POST['other_extra_services_details'];
            $other_extra_services_details=json_decode($other_extra_services_details);
            $child_extra_services_details=$_POST['child_extra_services_details'];
            //print_r($child_extra_services_details);die();
            $child_extra_services_details=json_decode($child_extra_services_details);
            
            
            $data='
            {
          "FareSourceCode": "'.$revalidation_res->Data->PricedItineraries[0]->AirItineraryPricingInfo->FareSourceCode.'",
           "TravelerInfo": {
            "AirTravelers": [
              {
                "PassengerType": "ADT",
                "Gender": "'.$Gender.'",
                "PassengerName": {
                  "PassengerTitle": "'.$PassengerTitle.'",
                  "PassengerFirstName": "'.$PassengerFirstName.'",
                  "PassengerLastName": "'.$PassengerLastName.'"
                },
                "DateOfBirth": "'.$DateOfBirth.'",
                "Passport": {
                  "PassportNumber": "'.$PassportNumber.'",
                  "ExpiryDate": "'.$ExpiryDate.'",
                  "Country": "'.$Country.'"
                },
                "SpecialServiceRequest": {
                  "SeatPreference": "Any",
                  "MealPreference": "Any",
                  "RequestedSegments": [
                  ';
                  if(isset($revalidation_res->Data->PricedItineraries))
                  {
                    foreach($revalidation_res->Data->PricedItineraries[0]->OriginDestinationOptions as $OriginDestinationOptions)
                    {
                      $data .=' 
                    {
                      "Origin": "'.$OriginDestinationOptions->FlightSegments[0]->DepartureAirportLocationCode.'",
                      "Destination": "'.$OriginDestinationOptions->FlightSegments[0]->ArrivalAirportLocationCode.'",
                      "FlightNumber": "'.$OriginDestinationOptions->FlightSegments[0]->FlightNumber.'",
                      "DepartureDateTime": "'.$OriginDestinationOptions->FlightSegments[0]->DepartureDateTime.'",
                       
                     
                    },  
                   
                  
                  ';
                    }
                  }
                  $data .='
                  ]
                  
                },
                ';
            if(isset($extra_services_details) && $extra_services_details != '' && $extra_services_details[0]->ExtraServiceId != 0)
            {
            $data .='
             "ExtraServices1_1": [
             ';
             foreach($extra_services_details as $services)
                {
                    if($services->ExtraServiceId != 'no')
                    {
             $data .='
              {
                "ExtraServiceId": '.$services->ExtraServiceId.',
                "Quantity": '.$services->Quantity.',
                "Key": "string"
              },
              ';
                    }
                }
                $data = substr_replace($data, '', strrpos($data, ','), 1);
              $data .='
              
              ],
              ';
            }
            
              $data .='
               "PassengerNationality": "'.$PassengerNationality.'",
               "NationalID": "'.$NationalID.'"
               
              },
              ';
              if(isset($other_passenger_details))
              {
                  foreach($other_passenger_details as $other_details)
                  {
        
               $data .='  
                {
                "PassengerType": "ADT",
                "Gender": "'.$other_details->other_gender.'",
                "PassengerName": {
                  "PassengerTitle": "'.$other_details->other_title.'",
                  "PassengerFirstName": "'.$other_details->other_first_name.'",
                  "PassengerLastName": "'.$other_details->other_last_name.'"
                },
                "DateOfBirth": "'.$other_details->other_date_of_birth.'",
                "Passport": {
                  "PassportNumber": "'.$other_details->other_passport_no.'",
                  "ExpiryDate": "'.$other_details->other_passport_expiry_date.'",
                  "Country": "'.$other_details->other_passport_country.'"
                },
                   "SpecialServiceRequest": {
                  "SeatPreference": "Any",
                  "MealPreference": "Any",
                  "RequestedSegments": [
                    {
                      "Origin": "'.$Origin.'",
                      "Destination": "'.$Destination.'",
                      "FlightNumber": "'.$FlightNumber.'",
                      "DepartureDateTime": "'.$DepartureDateTime.'",
                                   
                      
                    }
                  ]
                },
                ';
            if(isset($other_extra_services_details) && $other_extra_services_details != '' && $other_extra_services_details[0]->ExtraServiceId != 0)
            {
            $data .='
             "ExtraServices1_1": [
             ';
             foreach($other_extra_services_details as $services)
                {
                    if($services->ExtraServiceId != 'no')
                    {
             $data .='
              {
                "ExtraServiceId": '.$services->ExtraServiceId.',
                "Quantity": '.$services->Quantity.',
                "Key": "string"
              },
              ';
                    }
                }
                $data = substr_replace($data, '', strrpos($data, ','), 1);
              $data .='
              
              ],
              ';
            }
            
              $data .='
               "PassengerNationality": "'.$other_details->other_passport_country.'",
               "NationalID": "'.$other_details->other_passport_country.'"
              },
                ';
                  }
              }
              if(isset($child_details))
              {
                  foreach($child_details as $child_d)
                  {
                      
                 
               $data .='  
                {
                "PassengerType": "CHD",
                "Gender": "'.$child_d->child_gender.'",
                "PassengerName": {
                  "PassengerTitle": "'.$child_d->child_title.'",
                  "PassengerFirstName": "'.$child_d->child_first_name.'",
                  "PassengerLastName": "'.$child_d->child_last_name.'"
                },
                "DateOfBirth": "'.$child_d->child_date_of_birth.'",
                "Passport": {
                  "PassportNumber": "'.$child_d->child_passport_no.'",
                  "ExpiryDate": "'.$child_d->child_passport_expiry_date.'",
                  "Country": "'.$child_d->child_passport_country.'"
                },
                   "SpecialServiceRequest": {
                  "SeatPreference": "Any",
                  "MealPreference": "Any",
                  "RequestedSegments": [
                    {
                      "Origin": "'.$Origin.'",
                      "Destination": "'.$Destination.'",
                      "FlightNumber": "'.$FlightNumber.'",
                      "DepartureDateTime": "'.$DepartureDateTime.'",
                                   
                      
                    }
                  ]
                },
                ';
            if(isset($child_extra_services_details) && $child_extra_services_details != '' && $child_extra_services_details[0]->ExtraServiceId != 0)
            {
            $data .='
             "ExtraServices1_1": [
             ';
             foreach($child_extra_services_details as $services)
                {
                    if($services->ExtraServiceId != 'no')
                    {
             $data .='
              {
                "ExtraServiceId": '.$services->ExtraServiceId.',
                "Quantity": '.$services->Quantity.',
                "Key": "string"
              },
              ';
                    }
                }
                $data = substr_replace($data, '', strrpos($data, ','), 1);
              $data .='
              
              ],
              ';
            }
            
              $data .='
               "PassengerNationality": "'.$child_d->child_passport_country.'",
               "NationalID": "'.$child_d->child_passport_country.'"
              },
                ';
                  }
              }
              if(isset($infant_details))
              {
                  foreach($infant_details as $infant_de)
                  {
                      
                  
               $data .='  
                {
                "PassengerType": "INF",
                "Gender": "'.$infant_de->infant_gender.'",
                "PassengerName": {
                  "PassengerTitle": "'.$infant_de->infant_title.'",
                  "PassengerFirstName": "'.$infant_de->infant_first_name.'",
                  "PassengerLastName": "'.$infant_de->infant_last_name.'"
                },
                "DateOfBirth": "'.$infant_de->infant_date_of_birth.'",
                "Passport": {
                  "PassportNumber": "'.$infant_de->infant_passport_no.'",
                  "ExpiryDate": "'.$infant_de->infant_passport_expiry_date.'",
                  "Country": "'.$infant_de->infant_passport_country.'"
                },
                   "SpecialServiceRequest": {
                  "SeatPreference": "Any",
                  "MealPreference": "Any",
                  "RequestedSegments": [
                    {
                      "Origin": "'.$Origin.'",
                      "Destination": "'.$Destination.'",
                      "FlightNumber": "'.$FlightNumber.'",
                      "DepartureDateTime": "'.$DepartureDateTime.'",
                                   
                     
                    }
                  ]
                },
               "PassengerNationality": "'.$infant_de->infant_passport_country.'",
               "NationalID": "'.$infant_de->infant_passport_country.'"
              },
                ';
                  }
              }
              $data = substr_replace($data, '', strrpos($data, ','), 1);
              $data .='
             
            ],
             
            "CountryCode": "'.$CountryCode.'",
            "AreaCode": "'.$AreaCode.'",
            "PhoneNumber": "'.$PhoneNumber.'",
            "Email": "'.$Email.'",
            "PostCode": "'.$PostCode.'"
          },
          "Target": "Test",
          "ConversationId": "'.$ConversationId.'",
          "LocHoldBooking": "false"
          }
            ';
           
          //print_r($data);die();          
         //$data = json_encode($data); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/Book/Flight");
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
        echo $response;  
    }
    else
    {
       $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;  
    }

}

function tripdetails()
{
    global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
              $session_id=flightCreateSessionId();
          $UniqueID=$_POST['UniqueID'];
              $data='
               {
                "UniqueID": "'.$UniqueID.'"
                }
              ';
         
          // $data = json_encode($data); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/TripDetails/".$UniqueID);
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;   
    }
    else
    {
      $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;   
    }

}

function multicity_search_flights()
{
     global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
             $session_id=flightCreateSessionId();
            
            
            $destination_details= $_POST['destination_details'];
            $destination_details=json_decode($destination_details);
            
            $VendorPreferenceCodes =$_POST['AirlinesCode'];
            $adult=$_POST['adult'];
            $child=$_POST['child'];
            $infant=$_POST['infant'];
            $AirTripType=$_POST['AirTripType'];
            $ConversationId=$_POST['ConversationId'];
            $CabinPreference=$_POST['CabinType'];
            
            
            
            
            
            $data ='{
          "OriginDestinationInformations": [
          ';
          foreach($destination_details as $destination)
          {
          $data .='    
            {
              "DepartureDateTime": "'.$destination->departure_date.'T00:00:00",
              "OriginLocationCode": "'.$destination->departurecode.'",
              "DestinationLocationCode": "'.$destination->arrivalcode.'"
            },
          '; 
            }
        $data .=' 
          ],
          "TravelPreferences": {
            "MaxStopsQuantity": "All",
            "CabinPreference": "Y",
            "Preferences": {
              "CabinClassPreference": {
                "CabinType": "'.$CabinPreference.'",
                "PreferenceLevel": "Restricted"
              }
            },
            "AirTripType": "OneWay"
          },
          "PricingSourceType": "All",
          "IsRefundable": false,
          "PassengerTypeQuantities": [
            {
              "Code": "ADT",
              "Quantity": '.$adult.'
            },
            ';
            
            if($child != 0)
            {
            $data .='
            {
              "Code": "CHD",
              "Quantity": '.$child.'
            },
              '; 
            }
            if($infant != 0)
            {
            $data .='
            {
              "Code": "INF",
              "Quantity": '.$infant.'
            },
              '; 
            }
            $data = substr_replace($data, '', strrpos($data, ','), 1);
          $data .=' 
          
          ],
          "RequestOptions": "Hundred",
          "NearByAirports": true,
          "IsResidentFare": false,
          
          "Target": "Test",
          "ConversationId": "'.$ConversationId.'",
          "Provider": "Mystifly"
          
          
        }';
         
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;   
    }
    else
    {
      $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;   
    }
    
}
                
function CancelBookingflight()
{
    global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
              $session_id=flightCreateSessionId();
            $UniqueID=$_POST['UniqueID'];
            $ConversationId=$_POST['ConversationId'];
          
            
            $data ='{
          "UniqueID": "'.$UniqueID.'",
          "Target": "Test",
          "ConversationId": "'.$ConversationId.'"
        }';
         
         //print_r($data);die();          
        // $data = json_encode($data); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/Booking/Cancel");
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
        echo $response;   
    }
    else
    {
      $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;    
    }
         
}

function ViewReservationflight()
{
     global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
             $session_id=flightCreateSessionId();
          $UniqueID=$_POST['UniqueID'];
        
          // $data = json_encode($data); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1.1/TripDetails/".$UniqueID);
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;    
    }
    else
    {
      $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;    
    }
       
}

function ScheduleChangeFlight()
{
     global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
          $session_id=flightCreateSessionId();
          $ActionType=$_POST['ActionType'];
          $MFRef=$_POST['MFRef'];
          $RejectOption=$_POST['RejectOption'];
          $FlightNumber=$_POST['FlightNumber'];
          $AirlineCode=$_POST['AirlineCode'];
          $TravelDate=$_POST['TravelDate'];
          $DepartureTime='17:50';
          $CityPair=$_POST['CityPair'];
            $Comments=$_POST['Comments'];
          $data='
          {
         "ActionType": "ChooseAlternative",
          "MFRef": "MF16026620",
          "RejectOption": "Earlierflightoptions",
          "FlightOptions": [
            {
              "FlightNumber": 30,
              "AirlineCode": "EK",
              "TravelDate": "23-12-2021",
              "DepartureTime": "17:50",
              "CityPair": "LHR-DXB"
            }
          ],
        "Comments": "Suggest me whether this option is available"
        }';
        //print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/ScheduleChange");
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
        echo $response;  
    }
     else
    {
      $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;    
    }
    
}


function ScheduleChangeAcceptRequest()
{
    
    global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
              $session_id=flightCreateSessionId();
          
          $UniqueID=$_POST['UniqueID'];
         
          $data ='
        {
        "ActionType": "ChooseAlternative",
        "MFRef": "'.$UniqueID.'",
        "RejectOption": "ApplyforRefund"
         
        }
        ';
        //print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/ScheduleChange");
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
        echo $response;   
    }
     else
    {
      $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;    
    }
    
}

function OrderTicketFlight()
{
    global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
               $session_id=flightCreateSessionId();
          
          $UniqueID=$_POST['UniqueID'];
         
          $data ='
        {
          "UniqueID": "'.$UniqueID.'",
          "Target": "Test"
        }
        ';
        //print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/OrderTicket");
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
        echo $response;    
    }
    else
    {
      $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;    
    }
     
}

function VoidQuoteRequest()
{
     global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
             $session_id=flightCreateSessionId();
          
          $ptrType=$_POST['ptrType'];
          $mFRef=$_POST['mFRef'];
          $firstName=$_POST['firstName'];
          $lastName=$_POST['lastName'];
          $title=$_POST['title'];
          $eTicket=$_POST['eTicket'];
          $passengerType=$_POST['passengerType'];
         
          $data ='
        {
          "ptrType": "'.$ptrType.'",
          "mFRef": "'.$mFRef.'",
          "AllowChildPassenger": false,
          "passengers": [
            {
              "firstName": "'.$firstName.'",
              "lastName": "'.$lastName.'",
              "title": "'.$title.'",
              "eTicket": "'.$eTicket.'",
              "passengerType": "'.$passengerType.'"
            }
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
        echo $response;    
    }
    else
    {
      $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response;    
    }
    
       
}


function OrderTicketFlight_admin()
{
   
   $session_id=flightCreateSessionId();
     $UniqueID=$_POST['UniqueID'];
         
          $data ='
        {
          "UniqueID": "'.$UniqueID.'",
          "Target": "Test"
        }
        ';
        //print_r($data);die();
          $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1/OrderTicket");
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
        echo $response;    
    
     
}

function ViewReservationflight_admin()
{

             $session_id=flightCreateSessionId();
          $UniqueID=$_POST['UniqueID'];
        
          // $data = json_encode($data); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$session_id; // Prepare the authorisation SessionId
        $headers  = array('Accept: application/json','Content-Type: application/json;' , $authorization);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://restapidemo.myfarebox.com/api/v1.1/TripDetails/".$UniqueID);
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $headers);
        
        $response = curl_exec($ch);
        $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);
        echo $response;    
   
       
}



function flightTypeFillter()
{
    global $token_client;
    $token_authorization=$_POST['token_authorization'];
    if($token_authorization == $token_client)
    {
      
    
                $session_id=flightCreateSessionId();
                
                
                $DepartureDateTime= $_POST['DepartureDate'];
                $OriginLocationCode= $_POST['DepartureCode'];
                $DestinationLocationCode= $_POST['ArrivalCode'];
                $VendorPreferenceCodes =$_POST['AirlinesCode'];
                $adult=$_POST['adult'];
                $child=$_POST['child'];
                $infant=$_POST['infant'];
                $AirTripType=$_POST['AirTripType'];
                $ConversationId=$_POST['ConversationId'];
                $CabinPreference=$_POST['CabinType'];
                $MaxStopsQuantity=$_POST['MaxStopsQuantity'];
                
                $data ='{
              "OriginDestinationInformations": [
                {
                  "DepartureDateTime": "'.$DepartureDateTime.'",
                  "OriginLocationCode": "'.$OriginLocationCode.'",
                  "DestinationLocationCode": "'.$DestinationLocationCode.'"
            },
            
              ],
              "TravelPreferences": {
                "MaxStopsQuantity": "'.$MaxStopsQuantity.'",
                "CabinPreference": "'.$CabinPreference.'",
                "Preferences": {
                  "CabinClassPreference": {
                    "CabinType": "'.$CabinPreference.'",
                    "PreferenceLevel": "Preferred"
                  }
                },
                "AirTripType": "'.$AirTripType.'"
              },
              "PricingSourceType": "All",
              "IsRefundable": false,
              "PassengerTypeQuantities": [
                {
                  "Code": "ADT",
                  "Quantity": '.$adult.'
                },
                ';
                
                if($child != 0)
                {
                $data .='
                {
                  "Code": "CHD",
                  "Quantity": '.$child.'
                },
                  '; 
                }
                if($infant != 0)
                {
                $data .='
                {
                  "Code": "INF",
                  "Quantity": '.$infant.'
                },
                  '; 
                }
                $data = substr_replace($data, '', strrpos($data, ','), 1);
              $data .=' 
              
              ],
              "RequestOptions": "fifty",
              "NearByAirports": false,
              "Target": "Test",
              "ConversationId": "'.$ConversationId.'",
              
            }';
             
            // print_r($data);die();          
            // $data = json_encode($data); // Encode the data array into a JSON string
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($ch);
            $info =curl_errno($ch)>0 ? array("curl_error_".curl_errno($ch)=>curl_error($ch)) : curl_getinfo($ch);
            //print_r($info);
            curl_close($ch);
            echo $response;  
    
    }
    else
    {
        $response=array(
            'message'=>'Invalid Token',
            );
         $response=json_encode($response);
          echo $response; 
    }
    
   

 
}
?>
