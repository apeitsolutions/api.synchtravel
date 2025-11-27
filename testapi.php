<?php

    function pr($x){
        $apiKey     = '833583586f0fd4fccd3757cd8a57c0a8';
        $secret     = 'ae2dc887d0';
        $signature  = hash("sha256", $apiKey.$secret.time());
        return $signature;
	}
	
	$curl           = curl_init();
	$refrence_id    = '164-6287156';
    $signature      = pr($refrence_id);
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
    // echo $response;die();
    curl_close($curl);
    echo $response;
        
        // $apiKey     = '4b11b3c941ea25825a4de05ec398c4ab';
        // $secret     = 'afe693d5e4';
        // $signature  = hash("sha256", $apiKey.$secret.time());
        
        // $curl           = curl_init();
        // $geolocation    = array(
        //     "latitude"      => '39.57119',
        //     "longitude"     => '2.646633999999949',
        //     "radius"        => 20,
        //     "unit"          => "km",
        // );
        // $data               = array(
        //     'platform'      => "207",
        //     'stay'          => array('checkIn'=>'2024-02-15','checkOut'=>'2024-02-16'),
        //     'occupancies'   => array(
        //         array(
        //             'rooms' => 1,
        //             'adults' => 1,
        //             'children' => 0
        //         )
        //     ),
        //     'geolocation'   => $geolocation,
            
        // );
        // // echo $data;
        // $hotel_bed_request_data = $data;
        // // $signature              = pr($data);  
        // // print_r($data);die();
        // $data                   = json_encode($data);
        
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://api.hotelbeds.com/hotel-api/1.0/hotels',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 1,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS =>$data,
        //     CURLOPT_HTTPHEADER => array(
        //         'Api-key: 4b11b3c941ea25825a4de05ec398c4ab',
        //         "X-Signature: $signature",
        //         'Accept: application/json',
        //         'Accept-Encoding: gzip',
        //         'Content-Type: application/json'
        //     ),
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);
?>