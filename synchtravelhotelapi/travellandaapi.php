<?php

    if(isset($_POST)){
        $case = $_POST['case'];
      
        if($case == 'travelandaSearch'){
            travelandaSearch();
        }
        
        if($case =='travelandapreBooking'){
            travelandapreBooking();
        }
        
        if($case == 'travelandaCnfrmBooking'){
            travelandaCnfrmBooking();
        }
        
        if($case == 'travelandaCnfrmBookingnew'){
            travelandaCnfrmBookingnew();
        }
        
        if($case == 'GetHotelDetails'){
            GetHotelDetails();
        }
        
        if($case == 'HotelPolicies'){
            travellanda_HotelPolicies();
        }
        
        if($case == 'HotelBookingDetails'){
            HotelBookingDetails();
        }
        
        if($case == 'HotelBookingCancel'){
            HotelBookingCancel();
        }
        
        if($case == 'travelandaPolicy'){
            travelandaPolicy();
        }
    }
    
    function travelandaSearch(){
        $CityId                 = $_POST['CityId'];
        $CheckInDate            = $_POST['CheckInDate'];
        $CheckOutDate           = $_POST['CheckOutDate'];
        $res_data               = $_POST['res_data'];
        $country_nationality    = $_POST['country_nationality'];
        $res_data               = json_decode($res_data);
        
        $data_request="<Request>
                        <Head>
                            <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                            <Password>XjzqSyyOL0EV</Password>
                            <RequestType>HotelSearch</RequestType>
                        </Head>
                        <Body>
                            <CityIds>
                            <CityId>$CityId</CityId>
                            </CityIds>
                            <CheckInDate>$CheckInDate</CheckInDate>
                            <CheckOutDate>$CheckOutDate</CheckOutDate>
                            <Rooms>";
                                foreach($res_data as $res_data){
                                    $data_request .="<Room>
                                                        <NumAdults>". $res_data->Adults ."</NumAdults>
                                                        <Children>";
                                                        if(isset($res_data->ChildrenAge)){
                                                            foreach ($res_data->ChildrenAge as $item){
                                                                $data_request .='<ChildAge>'. $item[0] .'</ChildAge>';
                                                            }
                                                        }
                                    $data_request .= "</Children>
                                                    </Room>"; 
                                }
            $data_request .="</Rooms>
                            <Nationality>".$country_nationality."</Nationality>
                            <Currency>GBP</Currency>
                            <AvailableOnly>1</AvailableOnly>
                        </Body>
                    </Request>";            
        // print_r($data_request); die();
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://xml5.travellanda.com/xmlv1',
                CURLOPT_RETURNTRANSFER => true,
                // CURLOPT_ENCODING => '',
                CURLOPT_ACCEPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query(array('xml' => $data_request)),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            )
        );
        $response = curl_exec($curl);
        // print_r($response);die;
        $xml    = simplexml_load_string($response);
        $json   = json_encode($xml);
        echo $json;
    }
    
    function GetHotelDetails(){
        $HotelId        = $_POST['HotelId'];
        $data_request   =   "<Request>
                                <Head>
                                    <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                                    <Password>XjzqSyyOL0EV</Password>
                                    <RequestType>GetHotelDetails</RequestType>
                                </Head>
                                <Body>
                                    <HotelIds> 
                                        <HotelId>".$HotelId."</HotelId>
                                    </HotelIds>
                                </Body>
                            </Request>";
        // print_r($data_request);die;
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://xml.travellanda.com/xmlv1/GetHotelDetailsRequest.xsd',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query(array('xml' => $data_request)),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            )
        );
        $response = curl_exec($curl);
        // print_r($response);die;
        $xml    = simplexml_load_string($response);
        $json   = json_encode($xml);
        echo $json;
        
        // $url        = "https://xml.travellanda.com/xmlv1GetHotelDetailsRequest.xsd";
        // $timeout    = 1;
        // $data       = array('xml' => $data_request);
        // $headers    = array(
        //     "Content-type: application/x-www-form-urlencoded",
        // );
        // $ch = curl_init();
        // $payload = http_build_query($data);
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // $response   = curl_exec($ch);
        // $xml        = simplexml_load_string($response);
        // $json       = json_encode($xml);
        // echo $json;                          
    }

    function travelandapreBooking(){
        // print_r('OK');die;
        
        $roomRateIn     = $_POST['roomRate'];
        $roomRate       = json_decode($roomRateIn);
        // print_r($roomRate);die;
        $reqdata        =   "<Request>
                                <Head>
                                    <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                                    <Password>XjzqSyyOL0EV</Password>
                                    <RequestType>HotelPolicies</RequestType>
                                </Head>
                                <Body>";
                                    foreach($roomRate as $roomRate1){
                                        $reqdata .= "<OptionId>".$roomRate1->OptionId."</OptionId>"; 
                                    }
                $reqdata .=     "</Body>
                            </Request>";
        // // nt_r($reqdata);die();
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://xml.travellanda.com/xmlv1/HotelPoliciesRequest.xsd',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query(array('xml' => $reqdata)),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            )
        );
        $response = curl_exec($curl);
        // print_r($response);die;
        $xml    = simplexml_load_string($response);
        $json   = json_encode($xml);
        echo $json;
        
        // $url            = "https://xml.travellanda.com/xmlv1";
        // $timeout        = 1;
        // $data       = array('xml' => $reqdata);
        // $headers    = array(
        //     "Content-type: application/x-www-form-urlencoded",
        // );
        // $ch = curl_init();
        // $payload = http_build_query($data);
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // $response = curl_exec($ch);
        // curl_close($ch);
        // $xml = simplexml_load_string($response);
        // $json = json_encode($xml);
        // echo $json;
    }
    
    function travelandaCnfrmBookingnew(){
        // print_r('OK');die;
        
        $request_data       = [];
        if(isset($_POST['hotel_request_data'])){
            $request_data   = json_decode($_POST['hotel_request_data']);
        }
        
        $hotel_data = [];
        if(isset($_POST['hotel_checkout_select'])){
            $hotel_data     = json_decode($_POST['hotel_checkout_select']);
        }
        
        $data_request   =  "<Request>
                                <Head>
                                    <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                                    <Password>XjzqSyyOL0EV</Password>
                                    <RequestType>HotelBooking</RequestType>
                                </Head>
                                <Body>
                                    <OptionId>".$hotel_data->rooms_list[0]->booking_req_id."</OptionId>
                                    <YourReference>XMLTEST</YourReference>
                                    <Rooms>";
                                        if(isset($hotel_data->rooms_list[0]->rooms_list)){
                                            $index_counter = 0;
                                            $child_counter = 0;
                                            foreach($hotel_data->rooms_list[0]->rooms_list as $rooms_data){
                                                $data_request   .=  "<Room>
                                                                        <RoomId>". $rooms_data->room_id ."</RoomId>
                                                                        <PaxNames>";
                                                                            $data_request .="";
                                                                            for($adult = 0; $adult < $rooms_data->adults; $adult++){
                                                                                if($index_counter == 0){
                                                                                    $title          = $request_data->lead_title;
                                                                                    if($request_data->lead_title == 'MR'){
                                                                                        $title      = 'Mr.';
                                                                                    }
                                                                                    
                                                                                    if($request_data->lead_title == 'MRS'){
                                                                                        $title      = 'Mrs.';
                                                                                    }
                                                                                    
                                                                                    if($request_data->lead_title == 'MISS'){
                                                                                        $title      = 'Miss.';
                                                                                    }
                                                                                    
                                                                                    $name       = $request_data->lead_first_name;
                                                                                    $surname    = $request_data->lead_last_name;
                                                                                }else{
                                                                                    if(isset($request_data->other_title[$index_counter]) && !empty($request_data->other_title[$index_counter])){
                                                                                        $title      = $request_data->other_title[$index_counter];
                                                                                        if($request_data->other_title[$index_counter] == 'MR'){
                                                                                            $title      = 'Mr.';
                                                                                        }
                                                                                        
                                                                                        if($request_data->other_title[$index_counter] == 'MRS'){
                                                                                            $title      = 'Mrs.';
                                                                                        }
                                                                                        
                                                                                        if($request_data->other_title[$index_counter] == 'MISS'){
                                                                                            $title      = 'Miss.';
                                                                                        }
                                                                                        
                                                                                        $name       = $request_data->other_first_name[$index_counter];
                                                                                        $surname    = $request_data->other_last_name[$index_counter];
                                                                                        $index_counter++;
                                                                                    }else{
                                                                                        $title      = $request_data->lead_title;
                                                                                        if($request_data->lead_title == 'MR'){
                                                                                            $title      = 'Mr.';
                                                                                        }
                                                                                        
                                                                                        if($request_data->lead_title == 'MRS'){
                                                                                            $title      = 'Mrs.';
                                                                                        }
                                                                                        
                                                                                        if($request_data->lead_title == 'MISS'){
                                                                                            $title      = 'Miss.';
                                                                                        }
                                                                                        
                                                                                        $name       = $request_data->lead_first_name;
                                                                                        $surname    = $request_data->lead_last_name;
                                                                                        $index_counter++;
                                                                                    }
                                                                                }
                                                                                $data_request   .=  "<AdultName>
                                                                                                        <Title>". $title ."</Title>
                                                                                                        <FirstName>". $name ."</FirstName>
                                                                                                        <LastName>". $surname ."</LastName>
                                                                                                    </AdultName>";
                                                                            }
                                                                            
                                                                            for($childs = 0; $childs < $rooms_data->childs; $childs++){
                                                                                if(isset($request_data->child_title[$child_counter]) && !empty($request_data->child_title[$child_counter])){
                                                                                    $title      = $request_data->child_title[$child_counter];
                                                                                    if($request_data->child_title[$child_counter] == 'MR'){
                                                                                        $title      = 'Mr.';
                                                                                    }
                                                                                    
                                                                                    if($request_data->child_title[$child_counter] == 'MRS'){
                                                                                        $title      = 'Mrs.';
                                                                                    }
                                                                                    
                                                                                    if($request_data->child_title[$child_counter] == 'MISS'){
                                                                                        $title      = 'Miss.';
                                                                                    }
                                                                                    
                                                                                    $name       = $request_data->child_first_name[$child_counter];
                                                                                    $surname    = $request_data->child_last_name[$child_counter];
                                                                                    $child_counter++;
                                                                                }else{
                                                                                    $title      = $request_data->lead_title;
                                                                                    if($request_data->lead_title == 'MR'){
                                                                                        $title      = 'Mr.';
                                                                                    }
                                                                                    
                                                                                    if($request_data->lead_title == 'MRS'){
                                                                                        $title      = 'Mrs.';
                                                                                    }
                                                                                    
                                                                                    if($request_data->lead_title == 'MISS'){
                                                                                        $title      = 'Miss.';
                                                                                    }
                                                                                    
                                                                                    $name       = $request_data->lead_first_name;
                                                                                    $surname    = $request_data->lead_last_name;
                                                                                    $child_counter++;
                                                                                }
                                                                                $data_request .=    "<ChildName>
                                                                                                        <FirstName>". $name ."</FirstName>
                                                                                                        <LastName>". $surname ."</LastName>
                                                                                                    </ChildName>";
                                                                            }
                                                                            $data_request .="
                                                                        </PaxNames>
                                                                    </Room>";
                                            }
                                        }
                $data_request   .=  "</Rooms>
                                </Body>
                            </Request>";
        // print_r($data_request);die;
        
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://xml.travellanda.com/xmlv1/HotelBookingRequest.xsd',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query(array('xml' => $data_request)),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            )
        );
        $response = curl_exec($curl);
        // print_r($response);die;
        $xml    = simplexml_load_string($response);
        $json   = json_encode($xml);
        echo json_encode(['request' => $data_request,'response' => $json]);
        
        // $url            = "https://xml.travellanda.com/xmlv1/HotelBookingRequest.xsd";
        // $timeout        = 1; 
        // $data = array('xml' => $data_request);
        // $headers = array(
        //     "Content-type: application/x-www-form-urlencoded",
        // );
        // $ch = curl_init();
        // $payload = http_build_query($data);
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // $response   = curl_exec($ch);
        // $xml        = simplexml_load_string($response);
        // $json       = json_encode($xml);
        // echo json_encode(['request' => $data_request,'response' => $json]);
    }
    
    function HotelBookingCancel(){
        $id         = $_POST['id'];
        $url        = "https://xml.travellanda.com/xmlv1";
        $timeout    =   1;
        $reqdata    =  "<Request>
                            <Head>
                                <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                                <Password>XjzqSyyOL0EV</Password>
                                <RequestType>HotelBookingCancel</RequestType>
                            </Head>
                            <Body>
                                <BookingReference>$id</BookingReference>
                            </Body>
                        </Request>";
        // print_r($reqdata);die; 
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://xml.travellanda.com/xmlv1/HotelBookingCancelRequest.xsd',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query(array('xml' => $reqdata)),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            )
        );
        $response = curl_exec($curl);
        // print_r($response);die;
        $xml    = simplexml_load_string($response);
        $json   = json_encode($xml);
        echo $json;
        
        // $data       = array('xml' => $reqdata);
        // $headers    = array(
        //     "Content-type: application/x-www-form-urlencoded",
        // );
        // $ch = curl_init();
        // $payload = http_build_query($data);
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // $rawdata = curl_exec($ch);
        // // print_r($rawdata);die;
        // $xml = simplexml_load_string($rawdata);
        // $json = json_encode($xml);
        // echo $json;
    }
    
    function travelandaPolicy(){
        $OptionId   = $_POST['OptionId'];
        // return $OptionId;
        $reqdata    =   "<Request>
                            <Head>
                                <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                                <Password>XjzqSyyOL0EV</Password>
                                <RequestType>HotelPolicies</RequestType>
                            </Head>
                            <Body>
                                <OptionId>".$OptionId."</OptionId>
                            </Body>
                        </Request>";
        // print_r($reqdata);die;
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://xml.travellanda.com/xmlv1/HotelPoliciesRequest.xsd',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query(array('xml' => $reqdata)),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            )
        );
        $response   = curl_exec($curl);
        $xml        = simplexml_load_string($response);
        $json       = json_encode($xml);
        
        // $reqdata    =   "<Request>
        //                     <Head>
        //                         <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
        //                         <Password>XjzqSyyOL0EV</Password>
        //                         <RequestType>HotelPolicies</RequestType>
        //                     </Head>
        //                     <Body>
        //                         <OptionId>".$OptionId."</OptionId>
        //                     </Body>
        //                 </Request>";
        // print_r($reqdata);die();
        // $curl = curl_init();
        // curl_setopt_array(
        //     $curl,
        //     array(
        //         CURLOPT_URL => 'https://xml.travellanda.com/xmlv1/HotelPoliciesRequest.xsd',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS => http_build_query(array('xml' => $reqdata)),
        //         CURLOPT_HTTPHEADER => array(
        //             'Content-Type: application/x-www-form-urlencoded'
        //         ),
        //     )
        // );
        // $response = curl_exec($curl);
        // // print_r($response);die;
        // $xml    = simplexml_load_string($response);
        // $json   = json_encode($xml);
        // echo $json;
        
        // $url        = "https://xml.travellanda.com/xmlv1";
        // $timeout    = 1;
        // $data = array('xml' => $reqdata);
        // $headers = array(
        //     "Content-type: application/x-www-form-urlencoded",
        // );
        // $ch = curl_init();
        // $payload = http_build_query($data);
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // $response = curl_exec($ch);
        // curl_close($ch);
        // $xml = simplexml_load_string($response);
        // $json = json_encode($xml);
        // echo $json;
    }
    
    function travelandaCnfrmBooking(){
        
        $rooms_details= $_POST['rooms_details'];
        $array_merge= $_POST['array_merge'];
//     print_r($array_merge);
//  die();
      
          $room_search= $_POST['room_search'];
        $children_details= $_POST['children_details'];

        $t_passenger= $_POST['t_passenger'];
           $lead_passenger_details= $_POST['lead_passenger_details'];
        $other_passenger_details= $_POST['other_passenger_details'];



         $rooms_details= json_decode($rooms_details);
        $children_details= json_decode($children_details);
         $lead_passenger_details= json_decode($lead_passenger_details);
        $other_passenger_details= json_decode($other_passenger_details);
$array_merge= json_decode($array_merge);
           
//                   print_r($children_details);
//  die();

         $url = "https://xml.travellanda.com/xmlv1";
        $timeout = 1; 
   
   if($room_search == 1)
   {
     $data_request="<Request>
<Head>
<Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
<Password>XjzqSyyOL0EV</Password>
<RequestType>HotelBooking</RequestType>
</Head>
<Body>
<OptionId>". $rooms_details[0]->OptionId ."</OptionId>
<YourReference>XMLTEST</YourReference>
<Rooms>";

foreach($rooms_details as $rooms_data)
{
  
$data_request .="
 <Room>
 
<RoomId>". $rooms_data->Rooms->Room->RoomId ."</RoomId>

<PaxNames>";
 
 

 $data_request .="
";

if(isset($array_merge))
{
   $count=1; 
foreach($array_merge as $other_details)
{
    
   if($count <= $rooms_data->Rooms->Room->NumAdults)
  { 
    
   $data_request .="
 <AdultName>
<Title>". $other_details->title ."</Title>
<FirstName>". $other_details->other_first_name ."</FirstName>
<LastName>". $other_details->other_last_name ."</LastName>
</AdultName>";

}
   $count=$count+1;
}
}
if(isset($children_details))
{
foreach ($res_data->ChildrenAge as $item)
{
  $data_request .="<ChildName>
<FirstName>ather</FirstName>
<LastName>ather</LastName>
</ChildName>";
}
}
$data_request .="
</PaxNames>
</Room>

"; 

   

}
        

// echo $data_request;


}
   else
   {
     $data_request="<Request>
<Head>
<Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
<Password>XjzqSyyOL0EV</Password>
<RequestType>HotelBooking</RequestType>
</Head>
<Body>
<OptionId>". $rooms_details[0]->OptionId ."</OptionId>
<YourReference>XMLTEST</YourReference>
<Rooms>";

foreach($rooms_details as $rooms_data)
{
  
$data_request .="
 <Room>
 
<RoomId>". $rooms_data->Rooms->Room->RoomId ."</RoomId>

<PaxNames>";
 
 

 $data_request .="
";

if(isset($array_merge))
{
   $count=1; 
foreach($array_merge as $other_details)
{
    
   if($count <= $rooms_data->Rooms->Room->NumAdults)
  { 
    
   $data_request .="
 <AdultName>
<Title>". $other_details->title ."</Title>
<FirstName>". $other_details->other_first_name ."</FirstName>
<LastName>". $other_details->other_last_name ."</LastName>
</AdultName>";

}
   $count=$count+1;
}
}
if(isset($children_details))
{
foreach ($res_data->ChildrenAge as $item)
{
  $data_request .="<ChildName>
<FirstName>ather</FirstName>
<LastName>ather</LastName>
</ChildName>";
}
}
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

//print_r($data_request);die();
      
      
      
        $data = array('xml' => $data_request);
        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
        );
        $ch = curl_init();
        $payload = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        echo $json;
        
    }
    
    function HotelBookingDetails(){
        
        $BookingReference = $_POST['BookingReference'];
        //print_r($BookingReference);die();
        $url = "https://xml.travellanda.com/xmlv1";
            $timeout = 1;
            $reqdata1 = "<Request>
            <Head>
            <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
            <Password>XjzqSyyOL0EV</Password>
            <RequestType>HotelBookingDetails</RequestType>
            </Head>
            <Body>
            <BookingReference>$BookingReference</BookingReference>
            </Body>
            </Request>";
            $data = array('xml' => $reqdata1);
            $headers = array(
                "Content-type: application/x-www-form-urlencoded",
            );
            $ch = curl_init();
            $payload = http_build_query($data);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
            $rawdata = curl_exec($ch);
            // print_r(rawdata);die;
            $xml = simplexml_load_string($rawdata);
            $json = json_encode($xml);
            echo $json;
    }
    
    function travellanda_HotelPolicies(){
          $data_room = $_POST['roomRate'];
          $data_room=json_decode($data_room);
        $url = "https://xml.travellanda.com/xmlv1";
            $timeout = 1;
            $reqdata = "<Request>
            <Head>
            <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
            <Password>XjzqSyyOL0EV</Password>
            <RequestType>HotelPolicies</RequestType>
            </Head>
            <Body>
            
                <OptionId>".$data_room[0]->OptionId."</OptionId>
                
             
            
            </Body>
            </Request>";
            
            //print_r($reqdata);die();
            
            $data = array('xml' => $reqdata);
            
            // print_r($data);die();
            
            $headers = array(
                "Content-type: application/x-www-form-urlencoded",
            );
            $ch = curl_init();
            $payload = http_build_query($data);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
            $rawdata = curl_exec($ch);
            $xml = simplexml_load_string($rawdata);
            $json = json_encode($xml);
            echo $json;
    }
?>