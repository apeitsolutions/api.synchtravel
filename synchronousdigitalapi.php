
<?php
if(isset($_POST)){

    $case = $_POST['case'];
   
    if($case == 'searchtbo_hotel'){ 
        searchtbo_hotel();
    }
    
    if($case == 'tboPreBooking'){
        
        tboPreBooking();
    }
    
    if($case == 'confirmBookingTbo'){
        confirmBookingTbo();
        }
    
    if($case == 'search_ratehawk'){
        search_ratehawk();
         }
         
         if($case == 'ratehawk_hotel_details'){
        ratehawk_hotel_details();
         }
    
    if($case == 'ratehawk_getBooking'){
        ratehawk_getBooking();
    }
    
    if($case == 'confirmRatehawkBooking'){
        confirmRatehawkBooking();
       
       
    }
    

    
 if($case =='serach_hotelbeds'){
        
        serach_hotelbeds();
    }
    
   if($case =='gethotelbedsBooking'){
         
        gethotelbedsBooking();
    }
    
    if($case =='getcnfrmhotelbedsBooking'){
        getcnfrmhotelbedsBooking();
    }
    
    
    if($case == 'travelandaSearch'){
        
        travelandaSearch();
    }
    
    if($case =='travelandapreBooking'){
        
        travelandapreBooking();
    }
    
    if($case == 'travelandaCnfrmBooking'){
        
        travelandaCnfrmBooking();
        
    }
    
    
}

   //Ratehawk Apis
function pr($x){
	    $apiKey = '833583586f0fd4fccd3757cd8a57c0a8';
        $secret = 'ae2dc887d0';
        $signature = hash("sha256", $apiKey.$secret.time());
        return $signature;
        // print_r($signature);
	}

function search_ratehawk(){
    
    $CheckIn= $_POST['CheckIn'];
    $CheckOut= $_POST['CheckOut'];
    $residency= $_POST['residency'];
    $guests= $_POST['guests'];
    $rate_hawke_get_hotel_ids= $_POST['rate_hawke_get_hotel_ids'];
    
    $rate_hawke_get_hotel_ids=json_decode($rate_hawke_get_hotel_ids);
    
     $ratehake_search_rq='{
    "checkin": "'.$CheckIn.'",
    "checkout": "'.$CheckOut.'",
    "residency": "'.$residency.'",
    "language": "en",
    "guests": '.$guests.',
    "ids": [
    "test_hotel",
   "test_hotel_do_not_book",
    
    ';
    foreach($rate_hawke_get_hotel_ids as $rate_hawke_get_hotel_ids)
     {
         
   $ratehake_search_rq .=' 
"'.$rate_hawke_get_hotel_ids->id.'",
      '; 
     }
     $ratehake_search_rq= substr_replace($ratehake_search_rq, '', strrpos($ratehake_search_rq, ','), 1);
   $ratehake_search_rq .='
    
    ],
    "currency": "EUR"
}';


//print_r($ratehake_search_rq);die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/search/serp/hotels/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$ratehake_search_rq,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic NDM4MTo3NjJkNWY4MS02Y2YyLTRlYTItOWUyZC0wZTljY2QwODZhYzI=',
    'Content-Type: application/json',
    'Cookie: uid=TfTb5mL04HmDUEWsBqSiAg=='
  ),
));

$response = curl_exec($curl);
curl_close($curl);
echo $response;
    
}

function ratehawk_hotel_details()
{
    
   $CheckIn= $_POST['CheckIn'];
    $CheckOut= $_POST['CheckOut'];
    $residency= $_POST['residency'];
    $guests= $_POST['guests'];
    $id= $_POST['id'];
    
   
  $data_req='{
        "checkin": "'.$CheckIn.'",
        "checkout": "'.$CheckOut.'",
        "residency": "'.$residency.'",
        "language": "en",
        "guests": '.$guests.',
        "id": "'.$id.'",
        "currency": "EUR"
    }';
    
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/search/hp/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$data_req,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic NDM4MTo3NjJkNWY4MS02Y2YyLTRlYTItOWUyZC0wZTljY2QwODZhYzI=',
        'Content-Type: application/json',
        'Cookie: uid=TfTb5mL04HmDUEWsBqSiAg=='
      ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl); 
    echo $response;
}

function ratehawk_getBooking(){
    
    $partner_order_id= $_POST['partner_order_id'];
    $ip_address= $_POST['ip_address'];
     $roomRates= $_POST['roomRate'];
    $roomRate = json_decode($roomRates);
    
    $data_req='{
            
            ';
            foreach($roomRate as $roomRate)
            {
          $data_req .='
         
            "partner_order_id": "'.$partner_order_id.'",
            "book_hash": "'.$roomRate->book_hash.'",
            "language": "en",
            "user_ip": "'.$ip_address.'"
        
            
            ';
            }
          $data_req .='   
        }';
            
     //print_r($data_req);die();  


  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/hotel/order/booking/form/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data_req,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic NDM4MTo3NjJkNWY4MS02Y2YyLTRlYTItOWUyZC0wZTljY2QwODZhYzI=',
    'Content-Type: application/json',
    'Cookie: uid=TfTb5mL04HmDUEWsBqSiAg=='
  ),
));

$response = curl_exec($curl);
curl_close($curl);
 echo $response; 

}



function confirmRatehawkBooking()
{
    
    $other_passenger_details= $_POST['other_passenger_details'];
     $lead_passenger_details= $_POST['lead_passenger_details'];
    $rate_hawke_getbooking= $_POST['rate_hawke_getbooking'];
     $search_ratehawk= $_POST['search_ratehawk'];
     $lead_array= $_POST['lead_array'];
    
    $lead_passenger_details=json_decode($lead_passenger_details);
    $other_passenger_details=json_decode($other_passenger_details);
    $rate_hawke_getbooking=json_decode($rate_hawke_getbooking);
    $search_ratehawk=json_decode($search_ratehawk);
    $lead_array=json_decode($lead_array);
    
 //print_r($lead_array);die();
    
    
   $data_req_ratehawk='
{
    "user": {
        "email": "'.$lead_passenger_details->lead_email.'",
        "comment": "comment",
        "phone": "'.$lead_passenger_details->lead_phone.'"
    },
    "partner": {
        "partner_order_id": "'.$rate_hawke_getbooking->partner_order_id.'",
        "comment": "partner_comment",
        "amount_sell_b2b2c": "10"
    },
    "language": "en",
    "rooms": [
    ';
                    $count_ad=0;
                  $count_check=0;
                  $count_ad_ch=0;
                   $count_child=0;
    foreach($search_ratehawk as $search_ratehawk)
    {
        
    
    
    $data_req_ratehawk .='
        {
            "guests": [
            
            ';
            $array_merge=array_merge($lead_array,$other_passenger_details);
            
            foreach($array_merge as  $array_merges)
            {
                 $count_length=count($array_merge);
                 if($count_length > $count_ad)
                 {
                if($search_ratehawk->adults > $count_check)
                {
            $data_req_ratehawk .='
            
            
                {
                    "first_name": "'.$array_merge[$count_ad]->other_first_name.'",
                    "last_name": "'.$array_merge[$count_ad]->other_last_name.'"
                },
                
                ';
                
                
                $count_check++;
              $count_ad=$count_ad+1;
            }
            
            }
            }
            $data_req_ratehawk= substr_replace($data_req_ratehawk, '', strrpos($data_req_ratehawk, ','), 1);
              $data_req_ratehawk .='  
            ]
        },
        
        ';
      $count_child = 0;
      $count_check = 0;   
    }
    $data_req_ratehawk= substr_replace($data_req_ratehawk, '', strrpos($data_req_ratehawk, ','), 1);
      $data_req_ratehawk .='   
        
    ],
    "upsell_data": '.json_encode($rate_hawke_getbooking->upsell_data).',
    "payment_type": {
        "type": "'.$rate_hawke_getbooking->payment_types[0]->type.'",
        "amount": "'.$rate_hawke_getbooking->payment_types[0]->amount.'",
        "currency_code": "'.$rate_hawke_getbooking->payment_types[0]->currency_code.'"
    }
}
    
';
    
    
//print_r($data_req_ratehawk);die();
     $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/hotel/order/booking/finish/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$data_req_ratehawk,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic NDM4MTo3NjJkNWY4MS02Y2YyLTRlYTItOWUyZC0wZTljY2QwODZhYzI=',
        'Content-Type: application/json',
        'Cookie: uid=TfTb5mL04HmDUEWsBqSiAg=='
      ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl); 
    echo $response;
}


// Tbo Apis


function searchtbo_hotel()
{
    
   
    $CheckIn= $_POST['CheckIn'];
    $CheckOut= $_POST['CheckOut'];
    $HotelCodes= $_POST['HotelCodes'];
    $GuestNationality= $_POST['GuestNationality'];
    $PaxRooms= $_POST['PaxRooms'];
    $ResponseTime= $_POST['ResponseTime'];
    $NoOfRooms= $_POST['NoOfRooms'];
    
    
     $data = '{
      "CheckIn" : "'.$CheckIn.'",
      "CheckOut" : "'.$CheckOut.'" ,
     "HotelCodes": '.$HotelCodes.',
     "GuestNationality": "'.$GuestNationality.'" ,
      "PaxRooms": '.$PaxRooms.',
     "ResponseTime": '.$ResponseTime.',
     "IsDetailedResponse": false,
      "Filters": {
      "Refundable": false,
    "NoOfRooms": '.$NoOfRooms.',
     "MealType": "All"
    }
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
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json',
              'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
            ),
          ));
      
          $response = curl_exec($curl);
          curl_close($curl);
        echo $response;
    
}

function tboPreBooking(){
    
    
    $roomRate = $_POST['roomRate'];
   
    
  $roomRate=json_decode($roomRate);
  
$data='{
    
    ';
    foreach($roomRate as $roomRate)
    {
    $data .='
    
    "BookingCode": "'.$roomRate->BookingCode.'",
    
    
        
        '; 
    }
        
     $data .='   
        
        }';

    

 
   
     $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/PreBook',
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
      echo $response; 
      
}

function confirmBookingTbo(){
    
    $room_searching= $_POST['room_searching'];
    $tbo_hotel_pre_booking= $_POST['tbo_hotel_pre_booking'];
    $other_passenger_details= $_POST['other_passenger_details'];
    $lead_passenger_details= $_POST['lead_passenger_details'];
    $other_child= $_POST['other_child'];
    $PaxRooms_tbo= $_POST['PaxRooms_tbo'];
    $search_id= $_POST['search_id'];
   
$tbo_hotel_pre_booking=json_decode($tbo_hotel_pre_booking);
      $other_passenger_details=json_decode($other_passenger_details);
      $lead_passenger_details=json_decode($lead_passenger_details);
      $other_child=json_decode($other_child);
      $PaxRooms_tbo=json_decode($PaxRooms_tbo);



    
      if($room_searching == 1)
               {
                
                  $data= '{
            ';
            foreach($tbo_hotel_pre_booking->HotelResult[0]->Rooms as $rooms)
            {
            $data .=' 
            "BookingCode": "'.$rooms->BookingCode.'",
             "CustomerDetails": [
             {
             
             
             "CustomerNames": [
             
             ';
             if(isset($other_passenger_details))
             {
             $data .='     
                 
                 {
             "Title": "'.$lead_passenger_details->lead_title.'",
             "FirstName": "'.$lead_passenger_details->lead_first_name.'",
             "LastName": "'.$lead_passenger_details->lead_last_name.'",
             "Type": "Adult"
             },
              ';    
                 
             foreach($other_passenger_details as $other_passenger_details)
            {
             $data .=' 
            {
             "Title": "'.$other_passenger_details->title.'",
             "FirstName": "'.$other_passenger_details->other_first_name.'",
             "LastName": "'.$other_passenger_details->other_last_name.'",
             "Type": "Adult"
            },
            
            
             
            ';
            
            }
            }
            else
            {
              $data .='    
             {
             "Title": "'.$lead_passenger_details->lead_title.'",
             "FirstName": "'.$lead_passenger_details->lead_first_name.'",
             "LastName": "'.$lead_passenger_details->lead_last_name.'",
             "Type": "Adult"
             },
             
             ';
            }
            
            if(isset($other_child))
                {
                foreach($other_child as $other_child)
                {
                    
                        
                   
                   $data .=' 
                   {
             "Title": "'.$other_child->title.'",
             "FirstName": "'.$other_child->other_first_name.'",
             "LastName": "'.$other_child->other_last_name.'",
             "Type": "Child"
                    },
                            
            ';
                            
                    
                
               }
               
                    }
            $data= substr_replace($data, '', strrpos($data, ','), 1);
            $data .=' 
            
             ]
             
             
             
             
             
             
             
             }
             ],
             "ClientReferenceId": "1626158614415-92957459",
            "PaymentMode": "Limit",
             "TotalFare": '.$rooms->TotalFare.'
            
            ';
            }
            $data .='
            
            }'; 
               }
               else
               {
                $data= '{
            ';
            foreach($tbo_hotel_pre_booking->HotelResult[0]->Rooms as $rooms)
            {
            $data .=' 
            "BookingCode": "'.$rooms->BookingCode.'",
             "CustomerDetails": [
             
             ';
                  $count_ad=0;
                  $count_check=0;
                  $count_ad_ch=0;
                   $count_child=0;
                 foreach($PaxRooms_tbo as $key => $PaxRooms_tbo)
                 {
                     
                         
                     
                     
             $data .='
             {
            "CustomerNames": [
                 ';
                 $other_child=Session()->get('other_child');
            $array_merge=array_merge($lead_array,$other_passenger_details);
            if(isset($array_merge))
             {
                 foreach($array_merge as $array_merg)
                 {
                     $count_length=count($array_merge);
                     if($count_length > $count_ad)
                     {
                         
                     if($PaxRooms_tbo->adults >  $count_check)
                     {
            $data .='      
                 
                 {
             "Title": "'.$array_merge[$count_ad]->title.'",
             "FirstName": "'.$array_merge[$count_ad]->other_first_name.'",
             "LastName": "'.$array_merge[$count_ad]->other_last_name.'",
             "Type": "Adult"
             },
             
             ';
             
              $count_check++;
              $count_ad=$count_ad+1;
             
            
                 }
                 
                 
            
                 }
                 
                         
                     
                     
                 
             }
             
            
            
              if(isset($other_child))
                {
                foreach($other_child as $other_childs)
                {
                    
                   $count_length=count($other_child);
                  
                     if($count_length > $count_ad_ch)
                     {
             
                     if($PaxRooms_tbo->children >  $count_child)
                     {     
                  
            $data .=' 
            {
             "Title": "'.$other_child[$count_ad_ch]->title.'",
             "FirstName": "'.$other_child[$count_ad_ch]->other_first_name.'",
             "LastName": "'.$other_child[$count_ad_ch]->other_last_name.'",
             "Type": "Child"
            },
                            
            ';
            
                            
                     $count_child++;
              $count_ad_ch=$count_ad_ch+1;
                     }
                     }
               }
               
                    }       
                 $data= substr_replace($data, '', strrpos($data, ','), 1);
             }
             $data .='
             ]
                 
             },
              ';  
                $count_child = 0;
              $count_check = 0;
            
            }
            
            
            
            
            
            $data .=' 
            
             
             ],
             "ClientReferenceId": "1626135861wq4415-5686105",
            "BookingReferenceId": "'.$search_id.'",
            "PaymentMode": "Limit",
             "TotalFare": '.$rooms->TotalFare.',
             "BookingType": "Voucher"
            
            ';
            }
            $data .='
            
            }';   
            }
            
             //print_r($data);die();

$curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/Book',
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
  echo $response;
 curl_close($curl);

}




// Hotelbed apis

function serach_hotelbeds(){
    
    
    // echo 'true';die();
    
     $newstart= $_POST['CheckIn'];
    $newend= $_POST['CheckOut'];
    $res_hotel_beds= $_POST['res_hotel_beds'];
    $lat =$_POST ['lat'];
    $long =$_POST ['long'];


$res_hotel_beds=json_decode($res_hotel_beds);

//print_r();die();
    

    

$geolocation=array(
                        "latitude" => $lat,
                        "longitude" => $long,
                        "radius" => 20,
                        "unit" => "km",
                  );
              
$data = array(
                'platform ID'=>"207",
                'stay'=>array(
                    'checkIn' =>$newstart,
                    'checkOut' => $newend,
                ),
                'occupancies' => $res_hotel_beds,
                    'geolocation' =>  $geolocation,
                );
                
                

               
                
 $signature = pr($data);  
  
$data=json_encode($data);


$curl = curl_init();
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
curl_close($curl);
echo $response;
}


function gethotelbedsBooking(){
    
    $roomRateIn= $_POST['roomRate'];
    $roomRate= json_decode($roomRateIn);
     $data_res = '{
     "platform ID":"207",
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
 echo $response;
curl_close($curl);
    
    
}

function getcnfrmhotelbedsBooking(){
    
    $lead_passenger_details= $_POST['lead_passenger_details'];
    $room_searching= $_POST['room_searching'];
    $other_passenger_details= $_POST['other_passenger_details'];
    $other_child= $_POST['other_child'];
    $remarks_hotelbeds= $_POST['remarks_hotelbeds'];
     $hotel_bed_remarks= $_POST['hotel_bed_remarks'];

    
    
    
    if($room_searching == 1)
   {
    $data_res = '{
    
    "holder": {
        "name": "'. $lead_passenger_details->lead_first_name .'",
        "surname": "'. $lead_passenger_details->lead_last_name .'"
    },
    "platform ID":"207",
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
 
                {
                    "roomId": "'.$room_count.'",
                    "type": "AD",
                    "name": "'.$lead_passenger_details->lead_first_name.'",
                    "surname": "'.$lead_passenger_details->lead_last_name.'"
                },
 
        ';
        
        
        $count=0;
        $count1=1;
      
        $other_adults= Session()->get('other_adults');
        // print_r($other_adults);die();
        if(isset($other_adults))
        {
            
       
        foreach($other_adults as $other_adults)
    {
        if($t_passenger > $count)
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
    
    if(isset($other_child))
    {
    foreach($other_child as $other_child)
    {
        
            
       
       $data_res .='
       
                {
                    "roomId": "'.$room_count.'",
                    "type": "CH",
                    "name": "'.$other_child->other_first_name.'",
                    "surname": "'.$other_child->other_last_name.'"
                },
                ';
                
        
    
   }
   
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
    "remark": "'.$hotel_bed_remarks .'",
    "tolerance": 2
}';   
   }
   else
   {
   $data_res = '{
    
    "holder": {
        "name": "'. $lead_passenger_details->lead_first_name .'",
        "surname": "'. $lead_passenger_details->lead_last_name .'"
    },
    "platform ID":"207",
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
 
 
                {
                    "roomId": "'.$room_count.'",
                    "type": "AD",
                    "name": "'.$lead_passenger_details->lead_first_name.'",
                    "surname": "'.$lead_passenger_details->lead_last_name.'"
                },
        ';
        
        
        $count=0;
        $count1=1;
       $temp=1;
        $counter=1;
        $other_adults= Session()->get('other_adults');
        // print_r($other_adults);die();
        if(isset($other_adults))
        {
            
       
        foreach($other_adults as $other_adults)
    {
        if($t_passenger > $count)
        {
            if($temp <= 1)
            {
       
       $data_res .='
       
                {
                    "roomId": "'.$counter.'",
                    "type": "AD",
                    "name": "'.$other_adults->other_first_name.'",
                    "surname": "'.$other_adults->other_last_name.'"
                },
                ';
                
                $temp=$temp+1;
                
        }
        else
        {
            $counter=$counter+1;
            $temp=0;
           $data_res .='
       
                {
                    "roomId": "'.$counter.'",
                    "type": "AD",
                    "name": "'.$other_adults->other_first_name.'",
                    "surname": "'.$other_adults->other_last_name.'"
                },
                ';
                
                $temp=$temp+1;  
        }
        
        
        }
     $count=$count+1;
     
    $count1=$count1+1;
   }
    
    if(isset($other_child))
    {
    foreach($other_child as $other_child)
    {
        
            
       
       $data_res .='
       
                {
                    "roomId": "'.$count1.'",
                    "type": "CH",
                    "name": "'.$other_child->other_first_name.'",
                    "surname": "'.$other_child->other_last_name.'"
                },
                ';
                
        
    
   }
   
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
    "remark": "'.$hotel_bed_remarks .'",
    "tolerance": 2
}';

}
   

   
   
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
echo $response;
curl_close($curl); 

$res=json_decode($response);
    
}




// Travelanda Apis


function travelandaSearch(){
    $res_codes= $_POST['res_code'];
    // $data= $_POST['data'];
    $CountryCode= $_POST['CountryCode'];
    $res_dataaa=$data['results'];
   
     $res_data = json_decode($res_dataaa);
      $res_code = json_decode($res_codes);
      
    //   print_r($res_code); die();
    


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

$data_request .="</Rooms><Nationality>".$CountryCode."</Nationality>
<Currency>GBP</Currency>
<AvailableOnly>1</AvailableOnly>
</Body>
</Request>";

// $json_data=json_encode($data_request);
 //print_r($data_request);die();
// echo $data_request;
// die;

        $travellenda_request_data = $data_request;
        
        // print_r($travellenda_request_data); die();
        $url = "http://xmldemo.travellanda.com/xmlv1/";
$timeout = 20;
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
          echo $response;
          curl_close($ch);
    }

    
    function travelandapreBooking(){
        $roomRateIn = $_POST['roomRate'];
        $roomRate= json_decode($roomRateIn);
        
         $url = "http://xmldemo.travellanda.com/xmlv1/";
        $timeout = 20;
        $reqdata = "<Request>
        <Head>
        <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
        <Password>rY8qm76g5dGH</Password>
        <RequestType>HotelPolicies</RequestType>
        </Head>
        <Body>";
        foreach($roomRate as $roomRate1)
        {
          $reqdata .="  
            <OptionId>".$roomRate1->OptionId."</OptionId>
            
          "; 
        }
        
        
        $reqdata .="</Body>
        </Request>";
        
        // print_r($reqdata);die();
        
        $data = array('xml' => $reqdata);
        
        // print_r($data);die();
        
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
        
    }
    
    function travelandaCnfrmBooking(){
        
        $rooms_details= $_POST['rooms_details'];
       $h_option_id= $_POST['h_option_id'];
          $room_search= $_POST['room_search'];
        $children_details= $_POST['children_details'];
        $t_passenger= $_POST['t_passenger'];
           $lead_passenger_details= $_POST['lead_passenger_details'];
        $other_passenger_details= $_POST['other_passenger_details'];
        
        
         $rooms_details= json_decode($rooms_details);
        $children_details= json_decode($children_details);
         $lead_passenger_details= json_decode( $lead_passenger_details);
        $other_passenger_details= json_decode($other_passenger_details);
        
         $url = "http://xmldemo.travellanda.com/xmlv1/";
        $timeout = 20; 
   
   if($room_search == 1)
   {
      
      
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


    
$data_request .="
 <Room>
 
<RoomId>". $rooms_details[0]->Rooms->Room->RoomId ."</RoomId>

<PaxNames>";

 

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
    
    if($t_passenger > $count)
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
  
   }
   else
   {
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

foreach($rooms_details[0]->Rooms->Room as $rooms_details)
{
    
$data_request .="
 <Room>
 
<RoomId>". $rooms_details->RoomId ."</RoomId>

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
    
    if($t_passenger > $count)
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
   }
        

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
        
    }
    ?>


