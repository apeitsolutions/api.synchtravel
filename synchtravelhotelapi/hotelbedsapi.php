
<?php

// hotelbeds Apis
if(isset($_POST)){

$case = $_POST['case'];
if($case =='serach_hotelbeds')
{
serach_hotelbeds();
}
if($case =='hotel_details')
{
hotel_details();
}
if($case =='hotel_facilities')
{
hotel_facilities();
}
  if($case =='getBooking'){
         
        getBooking();
}

 if($case =='getBookingMultipleRooms'){
         
        getBookingMultipleRooms();
}
if($case =='confirmbooking'){
         
        confirmbooking();
}
if($case =='cancellation_booking'){
         
        cancellation_booking();
}
if($case =='new_confirmbooking'){
         
        new_confirmbooking();
}

if($case =='multi_rooms_confirmbooking'){
         
        multi_rooms_confirmbooking();
}
    
}
function pr($x){
	    $apiKey = '833583586f0fd4fccd3757cd8a57c0a8';
        $secret = 'ae2dc887d0';
        $signature = hash("sha256", $apiKey.$secret.time());
        return $signature;
        // print_r($signature);
	}
function serach_hotelbeds(){
    
   
    
$newstart= $_POST['CheckIn'];
    $newend= $_POST['CheckOut'];
    $res_hotel_beds= $_POST['res_hotel_beds'];
    $lat =$_POST['lat'];
    $long =$_POST['long'];


$res_hotel_beds=json_decode($res_hotel_beds);

//print_r();die();
    

    

$curl = curl_init();
    
    $geolocation=array(
                        "latitude" => $lat,
                        "longitude" => $long,
                        "radius" => 20,
                        "unit" => "km",
                  );
              
    $data = array(
                'platform'=>"207",
                'stay'=>array(
                    'checkIn' =>$newstart,
                    'checkOut' => $newend,
                ),
                'occupancies' => $res_hotel_beds,
                    'geolocation' =>  $geolocation,
                );
                
                
    
               $hotel_bed_request_data = $data;
                
    $signature = pr($data);  
    //print_r($data);die();
    $data=json_encode($data);
    
    // echo $data;
    // die;
   
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/hotels',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 1,
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

function hotel_details(){
    
   $hotel_beds_code= $_POST['hotel_beds_code'];
    $curl = curl_init();
    $signature = pr($hotel_beds_code);
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels/'.$hotel_beds_code.'/details?language=ENG&useSecondaryLanguage=False',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 1,
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
    //   echo $response;
    curl_close($curl);
    echo $response;
    
    
}

function hotel_facilities(){
    
    $hotel_beds_code= $_POST['hotel_beds_code'];
    $curl = curl_init();
    $signature = pr($hotel_beds_code);
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels/'.$hotel_beds_code.'/details?language=ENG&useSecondaryLanguage=False',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 1,
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
    //   echo $response;
    curl_close($curl);
    echo $response;
    
    
}

function getBooking(){
    
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
  CURLOPT_MAXREDIRS => 1,
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
//  echo $response;
curl_close($curl);
 echo $response;
    
    
}

function getBookingMultipleRooms(){
    
    
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
    // echo $data_res;
    // die;
$signature = pr($data_res);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/checkrates',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 1,
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
//  echo $response;
curl_close($curl);
 echo $response;
    
    
    
}

function confirmbooking(){
    
    $lead_passenger_details= $_POST['lead_passenger_details'];
    $lead_passenger_details=json_decode($lead_passenger_details);
    $room_searching= $_POST['room_searching'];
    $t_passenger= $_POST['t_passenger'];
    $other_passenger_details= $_POST['other_passenger_details'];
    $other_adults=json_decode($other_passenger_details);
    $other_child= $_POST['other_child'];
    $other_child=json_decode($other_child);
//  $hotel_bed_remarks= $_POST['hotel_bed_remarks'];
$hotel_bed_remarks= 'smoking';
 $hotel_beds_select= $_POST['hotel_beds_select'];
 $hotel_beds_select=json_decode($hotel_beds_select);
    
    $lead_array[]=(object)[
        
        'title'=>$lead_passenger_details->lead_title,
        'other_first_name'=>$lead_passenger_details->lead_first_name,
        'other_last_name'=>$lead_passenger_details->lead_last_name,
        ];
 

  $merge_data=array_merge($lead_array,$other_adults);
       //print_r($merge_data);die();
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
      
       
      //print_r($other_adults);die();
        if(isset($other_adults))
        {
            
       
        foreach($other_adults as $other_adults_data)
    {
        if($t_passenger > $count)
        {
           
       
       $data_res .='
       
                {
                    "roomId": "'.$room_count.'",
                    "type": "AD",
                    "name": "'.$other_adults_data->other_first_name.'",
                    "surname": "'.$other_adults_data->other_last_name.'"
                },
                ';
                
              
                
       
        
        
        
        }
     $count=$count+1;
     
    $count1=$count1+1;
   }
    
    if(isset($other_child[0]))
    {
    foreach($other_child as $other_child_data)
    {
        
            
       
       $data_res .='
       
                {
                    "roomId": "'.$room_count.'",
                    "type": "CH",
                    "name": "'.$other_child_data->other_first_name.'",
                    "surname": "'.$other_child_data->other_last_name.'"
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
 
                
 
        ';
        
$temp_count=0; 
        if(isset($merge_data))
        {
          
       
        foreach($merge_data as $other_adults)
    {
        
      if($temp_count >= $rates->adults)      
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
     $temp_count=$temp_count+1;      
   }
    
    if(isset($other_child[0]))
    {
    foreach($other_child as $other_child_data)
    {
        
            
       
       $data_res .='
       
                {
                    "roomId": "'.$room_count.'",
                    "type": "CH",
                    "name": "'.$other_child_data->other_first_name.'",
                    "surname": "'.$other_child_data->other_last_name.'"
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
   

   
//   $data_res = json_decode($data_res);
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
  CURLOPT_MAXREDIRS => 1,
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

curl_close($curl); 
echo $response;

    
}
function new_confirmbooking(){
    // print_r($_POST);
    
    $request_data = [];
    if(isset($_POST['hotel_request_data'])){
        $request_data = json_decode($_POST['hotel_request_data']);
    }
    
    $hotel_data = [];
    if(isset($_POST['hotel_checkout_select'])){
        $hotel_data = json_decode($_POST['hotel_checkout_select']);
    }


    $rooms_arr = [];
    if(isset($hotel_data->rooms_list)){
        $counter = 1;
        $index_counter = 0;
        $child_counter = 0;
        foreach($hotel_data->rooms_list as $room_res){
            
            $paxes_arr = [];
            
            for($adult = 0; $adult < $room_res->adults; $adult++){
                
                if($index_counter == 0){
                    $name = $request_data->lead_first_name;
                    $surname = $request_data->lead_last_name;
                }else{
                    if(isset($request_data->other_first_name[$index_counter - 1]) && !empty($request_data->other_first_name[$index_counter - 1])){
                        $name = $request_data->other_first_name[$index_counter - 1];
                        $surname =$request_data->other_last_name[$index_counter -1];
                    }else{
                        $name = $request_data->lead_first_name;
                        $surname = $request_data->lead_last_name;
                    }
                    
                }
                $paxes_arr[] = (Object)[
                        'roomId' => $counter,
                        'type' => 'AD',
                        'name' => $name,
                        'surname' => $surname,
                    ];
                
                $index_counter++;
            }
            
            for($child = 0; $child < $room_res->childs; $child++){
                
                if(isset($request_data->child_first_name[$child_counter]) && !empty($request_data->child_first_name[$child_counter])){
                    $name = $request_data->child_first_name[$child_counter];
                    $surname =$request_data->child_last_name[$child_counter];
                }else{
                        $name = $request_data->lead_first_name;
                        $surname = $request_data->lead_last_name;
                }
                
                $paxes_arr[] = (Object)[
                        'roomId' => $counter,
                        'type' => 'CH',
                        'name' => $name,
                        'surname' => $surname,
                    ];
                
                $child_counter++;
            }
            
            $rooms_arr[] = (Object)[
                    'rateKey' => $room_res->booking_req_id,
                    'paxes'=>$paxes_arr
                    
                ];
                
                // $counter++;
        }
    }
    
    $hotel_request_Object = (Object)[
            'holder' => (Object)[
                    'name' => $request_data->lead_first_name,
                    'surname' => $request_data->lead_last_name
                ],
            'rooms' => $rooms_arr,
            'clientReference' => 'IntegrationAgency',
            'remark' => 'test Remarks',
            'tolerance' => 2
        ];
    
    // print_r($hotel_request_Object);
    // // print_r($hotel_data);
    // die;
   
  $data_res = json_encode($hotel_request_Object);
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
  CURLOPT_MAXREDIRS => 1,
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
// $confirm_data = json_decode($response);
curl_close($curl); 
// print_r($confirm_data);
echo json_encode(['request' => $data_res,'response' => $response]);

    // die;
}

function multi_rooms_confirmbooking(){
    // print_r($_POST);
    
    $request_data = [];
    if(isset($_POST['hotel_request_data'])){
        $request_data = json_decode($_POST['hotel_request_data']);
    }
    
    $hotel_data = [];
    if(isset($_POST['hotel_checkout_select'])){
        $hotel_data = json_decode($_POST['hotel_checkout_select']);
    }


    $rooms_arr = [];
    if(isset($hotel_data->rooms_list)){
        
        $index_counter = 0;
        $child_counter = 0;
        foreach($hotel_data->rooms_list as $index => $room_res){
            $counter = 1;
            $paxes_arr = [];
            
            $total_adults = $room_res->adults * $room_res->selected_qty;
            
            for($adult = 0; $adult < $total_adults; $adult++){
                
                if($index_counter == 0){
                    $name = $request_data->lead_first_name;
                    $surname = $request_data->lead_last_name;
                }else{
                    if(isset($request_data->other_first_name[$index_counter - 1]) && !empty($request_data->other_first_name[$index_counter - 1])){
                        $name = $request_data->other_first_name[$index_counter - 1];
                        $surname =$request_data->other_last_name[$index_counter -1];
                    }else{
                        $name = $request_data->lead_first_name;
                        $surname = $request_data->lead_last_name;
                    }
                    
                }
                $paxes_arr[] = (Object)[
                        'roomId' => $counter,
                        'type' => 'AD',
                        'name' => $name,
                        'surname' => $surname,
                    ];
                
                $index_counter++;
                
                $adult_counter = $adult + 1;
                if($adult_counter % $room_res->adults == 0){
                    $counter++;
                }
            }
            
            for($child = 0; $child < $room_res->childs; $child++){
                
                if(isset($request_data->child_first_name[$child_counter]) && !empty($request_data->child_first_name[$child_counter])){
                    $name = $request_data->child_first_name[$child_counter];
                    $surname =$request_data->child_last_name[$child_counter];
                }else{
                        $name = $request_data->lead_first_name;
                        $surname = $request_data->lead_last_name;
                }
                
                $paxes_arr[] = (Object)[
                        'roomId' => $counter,
                        'type' => 'CH',
                        'name' => $name,
                        'surname' => $surname,
                    ];
                
                $child_counter++;
            }
            
   
                $rateKey = $room_res->booking_req_id;
            
            
            $rooms_arr[] = (Object)[
                    'rateKey' => $rateKey,
                    'paxes'=>$paxes_arr
                    
                ];
                
                // $counter++;
        }
    }
    
    // print_r($rooms_arr);
    // die;
    
    $hotel_request_Object = (Object)[
            'holder' => (Object)[
                    'name' => $request_data->lead_first_name,
                    'surname' => $request_data->lead_last_name
                ],
            'rooms' => $rooms_arr,
            'clientReference' => 'IntegrationAgency',
            'remark' => 'test Remarks',
            'tolerance' => 2
        ];
    
    // print_r($hotel_request_Object);
    // // print_r($hotel_data);
    // die;
   
  $data_res = json_encode($hotel_request_Object);
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
  CURLOPT_MAXREDIRS => 1,
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
// $confirm_data = json_decode($response);
curl_close($curl); 
// print_r($confirm_data);
echo json_encode(['request' => $data_res,'response' => $response]);

    // die;
}

function cancellation_booking(){
    
    $refrence_id= $_POST['refrence_id'];
    
    $curl = curl_init();
    $signature = pr($refrence_id);
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/bookings/'.$refrence_id.'?cancellationFlag=CANCELLATION',
    
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 1,
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
 //echo $response;die();
    curl_close($curl);
    echo $response;
    
}
?>


