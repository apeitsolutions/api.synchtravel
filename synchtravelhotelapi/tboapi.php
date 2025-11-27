
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





    ?>