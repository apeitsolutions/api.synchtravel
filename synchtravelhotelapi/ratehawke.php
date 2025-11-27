
<?php
if(isset($_POST)){

    $case = $_POST['case'];
   
    if($case == 'search_ratehawk_1'){
        search_ratehawk_1();
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
    
   if($case == 'ratehawk_get_id_data'){
        ratehawk_get_id_data();
       
       
    }
    
    
}

   //Ratehawk Apis

function search_ratehawk_1()
{
       $country_code_ratehawk= $_POST['country_code_ratehawk'];
 $data_hotel_get='{
    "query": "'.$country_code_ratehawk.'",
    "language": "en"
}';

//print_r($data_hotel_get);die();
   $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/search/multicomplete/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 1,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data_hotel_get,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic NDM4MTo3NjJkNWY4MS02Y2YyLTRlYTItOWUyZC0wZTljY2QwODZhYzI=',
    'Content-Type: application/json',
    'Cookie: uid=TfTb5mL04HmDUEWsBqSiAg=='
  ),
));

$response = curl_exec($curl);
//echo $response;die();
curl_close($curl); 
echo $response;
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
    "currency": "GBP"
}';


//print_r($ratehake_search_rq);die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/search/serp/hotels/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 1,
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
        "currency": "GBP"
    }';
    
    //print_r($data_req);die();
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/search/hp/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 1,
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

function ratehawk_get_id_data()
{
    
    $id= $_POST['id'];
    
    $data_req1='{
        "id": "'.$id.'",
        "language": "en"
    }';
    
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.worldota.net/api/b2b/v3/hotel/info/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 1,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$data_req1,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic NDM4MTo3NjJkNWY4MS02Y2YyLTRlYTItOWUyZC0wZTljY2QwODZhYzI=',
        'Content-Type: application/json',
        'Cookie: uid=TfTb5mL04HmDUEWsBqSiAg=='
      ),
    ));
    
    $response = curl_exec($curl);
    // echo $response;
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
  CURLOPT_MAXREDIRS => 1,
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
      CURLOPT_MAXREDIRS => 1,
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



?>






