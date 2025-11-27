<?php

namespace App\Http\Controllers\SunHotel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TBOHotel_3rdPartyBooking_Controller;
use App\Models\booking_customers;
use App\Models\CustomerSubcription\CustomerSubcription;
use Carbon\Carbon;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Session;

class SunHotel_Controller extends Controller
{
    public static function sunHotelSearch($child_per_room,$CheckInDate,$CheckOutDate,$numberOfRooms,$destinationCode,$numberOfAdults,$room,$numberOfChildrenWithInfants,$total_Adults,$total_Rooms,$child_Age_Array,$total_Childs,$sunhotelAdminMarkup,$sunhotelCustomerMarkup,$customer_Hotel_markup_SunHotel){
        $child_ages_string          = '';
        $infant                     = 0;
        $totalNumberSunhotel        = 0;
        $hotelsSunhotel             = [];
        $numberOfChildren           = 0;
        // $sunhotelAdminMarkup        = 0;
        $sunhotelCustomerMarkup     = $customer_Hotel_markup_SunHotel;
        $sunhotelAdminMarkupType    = 'Percentage';
        $sunhotelCustomerMarkupType = 'Percentage';
        
        // 46572 For Room Notes
        
        for ($i = 0; $i < count($child_per_room); $i++) {
            $varName    = 'child_ages' . ($i + 1);
            $child_arr  = $varName;
            if (isset ($child_arr) && is_array($child_arr)) {
                foreach ($child_arr as $key => $child) {
                    if ($child == 1) {
                        $infant = 1;
                        unset($child_arr[$key]);
                    }
                }
                $numberOfChildren += count($child_arr);
                if (!empty ($child_arr)) {
                    $child_ages_string .= implode(',', $child_arr);
                    if ($i < count($child_per_room) - 1) {
                        $child_ages_string .= ',';
                    }
                }
            }
        }
        
        $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/SearchV2?";
        $paramsSunhotel                 = array(
            'username'                  => env('SUN_HOTEL_USERNAME'),
            'password'                  => env('SUN_HOTEL_PASSWORD'),
            'language'                  => 'en',
            'currencies'                => 'EUR',
            'checkInDate'               => $CheckInDate,
            'checkOutDate'              => $CheckOutDate,
            'numberOfRooms'             => $numberOfRooms,
            'destination'               => $destinationCode,
            'destinationID'             => '',
            'hotelIDs'                  => '',
            'resortIDs'                 => '',
            'accommodationTypes'        => '',
            'numberOfAdults'            => $numberOfAdults,
            'numberOfChildren'          => $numberOfChildren,
            'childrenAges'              => $numberOfChildren ? $child_ages_string : '',
            'infant'                    => $infant,
            'sortBy'                    => '',
            'sortOrder'                 => '',
            'exactDestinationMatch'     => '',
            'mealIds'                   => '',
            'excludeSharedFacilities'   => '',
            'showReviews'               => '1',
            'blockSuperdeal'            => $request->blockSuperdeal ?? '',
            'customerCountry'           => $request->customerCountry ?? '',
            'showRoomTypeName'          => $request->showRoomTypeName ?? '',
        );
        // return $paramsSunhotel;
        $curl                           = curl_init();
        $url                            = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        // return $url;
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                // CURLOPT_ENCODING => '',
                CURLOPT_ACCEPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        
        $response_xml   = curl_exec($curl);
        // return $response_xml;
        $response_json  = simplexml_load_string($response_xml);
        $response_json  = json_encode($response_json);
        $data           = json_decode($response_json, true);
        // return $data;
        curl_close($curl);
        
        // return $CheckInDate;
        
        if (!empty ($data) && isset ($data['hotels']['hotel'])) {
            foreach ($data['hotels']['hotel'] as $hotel) {
                $room_prices_arr    = [];
                $roomsSunhotel      = [];
                $rooms_list         = [];
                $total_Pax          = $numberOfAdults + $numberOfChildrenWithInfants;
                $new_roomsSunhotel  = [];
                
                if(isset($hotel['roomtypes']['roomtype'])){
                    $roomtype_Hotels = $hotel['roomtypes']['roomtype'];
                    foreach($roomtype_Hotels as $val_Rooms){
                        if(isset($val_Rooms['rooms']['room'])){
                            if(isset($val_Rooms['rooms']['room']['beds'])){
                                if($total_Pax == $val_Rooms['rooms']['room']['beds'] || $total_Pax < $val_Rooms['rooms']['room']['beds']){
                                    // return 'MATCHED';
                                    if(count($total_Adults) > 1){
                                        // return 'if';
                                        
                                        $status_Check_Array = [];
                                        for($ad=0; $ad<count($total_Adults); $ad++){
                                            $status                     = '0';
                                            $meal_ID                    = '';
                                            $singleRoomPrice            = '';
                                            $meal_Type                  = '';
                                            $meals                      = $val_Rooms['rooms']['room']['meals']['meal'] ?? null;
                                            $meal_ID                    = $meals['id'] ?? '';
                                            
                                            if ($meals !== null) {
                                                if (!is_array($meals)) {
                                                    $meals              = [$meals];
                                                } elseif (isset ($meals['id'])) {
                                                    $meals              = [$meals];
                                                }
                                                if (!empty ($meals) && isset ($meals[0]['prices']['price'])) {
                                                    $singleRoomPrice    = $meals[0]['prices']['price'];
                                                    $meal_Type          = $meals[0]['labelText'] ?? 'BB';
                                                }
                                            }
                                            
                                            if ($singleRoomPrice !== '') {
                                                $room_prices_arr[] = $singleRoomPrice;
                                            }
                                            
                                            $cancelationPolicyArr       = [];
                                            $cancel_Status              = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['text'] ?? '';
                                            $percentage                 = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['percentage'] ?? '';
                                            $deadline                   = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['deadline'] ?? '';
                                            if ($deadline == []) {
                                                $deadline               = '';
                                            }
                                            
                                            $formattedDeadlineDate      = '';
                                            if($deadline != ''){
                                                // return $deadline;
                                                $checkInDate            = Carbon::parse($CheckInDate);
                                                $deadline_Date          = $checkInDate->subHours($deadline);
                                                $formattedDeadlineDate  = $deadline_Date->format('Y-m-d');
                                                // return $formattedDeadlineDate;
                                            }
                                            
                                            $cancel_items               = (Object) [
                                                'amount'                => $percentage,
                                                'from_date'             => $formattedDeadlineDate,
                                            ];
                                            $cancelationPolicyArr[]     = $cancel_items;
                                            
                                            $rooms_list[]               = (object)[
                                                'booking_req_id'            => $val_Rooms['rooms']['room']['id'],
                                                'allotment'                 => $val_Rooms['rooms']['room']['beds'],
                                                // 'allotment'                 => $total_Adults[$ad],
                                                'room_name'                 => $val_Rooms['room.type'] ?? '',
                                                'room_code'                 => $val_Rooms['rooms']['room']['id'],
                                                'request_type'              => '',
                                                'board_id'                  => $meal_Type,
                                                'board_code'                => $val_Rooms['roomtype.ID'],
                                                'rooms_total_tax'           => $singleRoomPrice,
                                                'rooms_total_price'         => $singleRoomPrice,
                                                'rooms_selling_price'       => $singleRoomPrice,
                                                // 'rooms_qty'                 => $numberOfRooms,
                                                // 'adults'                    => $numberOfAdults,
                                                'rooms_qty'                 => 1,
                                                'adults'                    => $total_Adults[$ad],
                                                'childs'                    => $total_Childs[$ad] ?? $numberOfChildrenWithInfants,
                                                'cancliation_policy_arr'    => $cancelationPolicyArr,
                                                'rooms_list'                => $val_Rooms['rooms']['room'],
                                                'meal_Type'                 => $meal_Type,
                                                'cancel_Status'             => $cancel_Status,
                                                'meal_ID'                   => $meal_ID,
                                                'child_Ages'                => $child_Age_Array[$ad] ?? '',
                                            ];
                                            
                                            array_push($roomsSunhotel, $rooms_list);
                                        }
                                    }else{
                                        // return 'else';
                                        
                                        $meal_ID                    = '';
                                        $singleRoomPrice            = '';
                                        $meal_Type                  = '';
                                        $meals                      = $val_Rooms['rooms']['room']['meals']['meal'] ?? null;
                                        $meal_ID                    = $meals['id'] ?? '';
                                        
                                        if ($meals !== null) {
                                            if (!is_array($meals)) {
                                                $meals              = [$meals];
                                            } elseif (isset ($meals['id'])) {
                                                $meals              = [$meals];
                                            }
                                            if (!empty ($meals) && isset ($meals[0]['prices']['price'])) {
                                                $singleRoomPrice    = $meals[0]['prices']['price'];
                                                $meal_Type          = $meals[0]['labelText'] ?? 'BB';
                                            }
                                        }
                                        
                                        if ($singleRoomPrice !== '') {
                                            $room_prices_arr[] = $singleRoomPrice;
                                        }
                                        
                                        $cancelationPolicyArr       = [];
                                        $cancel_Status              = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['text'] ?? '';
                                        $percentage                 = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['percentage'] ?? '';
                                        $deadline                   = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['deadline'] ?? '';
                                        if ($deadline == []) {
                                            $deadline               = '';
                                        }
                                        
                                        $formattedDeadlineDate      = '';
                                        if($deadline != ''){
                                            // return $deadline;
                                            $checkInDate            = Carbon::parse($CheckInDate);
                                            $deadline_Date          = $checkInDate->subHours($deadline);
                                            $formattedDeadlineDate  = $deadline_Date->format('Y-m-d');
                                            // return $formattedDeadlineDate;
                                        }
                                        
                                        // if($formattedDeadlineDate == ''){
                                        //     return $val_Rooms;
                                        // }
                                        
                                        $cancel_items               = (Object) [
                                            'amount'                => $percentage,
                                            'from_date'             => $formattedDeadlineDate,
                                        ];
                                        $cancelationPolicyArr[]     = $cancel_items;
                                        
                                        $rooms_list[] = (object)[
                                            'booking_req_id'            => $val_Rooms['rooms']['room']['id'],
                                            'allotment'                 => $val_Rooms['rooms']['room']['beds'],
                                            'room_name'                 => $val_Rooms['room.type'] ?? '',
                                            'room_code'                 => $val_Rooms['rooms']['room']['id'],
                                            'request_type'              => '',
                                            'board_id'                  => $meal_Type,
                                            'board_code'                => $val_Rooms['roomtype.ID'],
                                            'rooms_total_tax'           => $singleRoomPrice,
                                            'rooms_total_price'         => $singleRoomPrice,
                                            'rooms_selling_price'       => $singleRoomPrice,
                                            'rooms_qty'                 => $numberOfRooms,
                                            'adults'                    => $numberOfAdults,
                                            'childs'                    => $numberOfChildrenWithInfants,
                                            'cancliation_policy_arr'    => $cancelationPolicyArr,
                                            'rooms_list'                => $val_Rooms['rooms']['room'],
                                            'meal_Type'                 => $meal_Type,
                                            'cancel_Status'             => $cancel_Status,
                                            'meal_ID'                   => $meal_ID,
                                            'child_Ages'                => $child_Age_Array,
                                        ];
                                        
                                        array_push($roomsSunhotel, $rooms_list);
                                    }
                                }
                            }
                        }
                    }
                }
                
                $sunHotel_hotel_details = SunHotel_Controller::sunHotelDetails($hotel['hotel.id'] ?? '');
                
                $hotel_list_item = (Object) [
                    'hotel_provider'        => 'Sunhotel',
                    'admin_markup'          => $sunhotelAdminMarkup,
                    'customer_markup'       => $sunhotelCustomerMarkup,
                    'admin_markup_type'     => $sunhotelAdminMarkupType,
                    'customer_markup_type'  => $sunhotelCustomerMarkupType,
                    'hotel_id'              => $sunHotel_hotel_details['hotels']['hotel']['hotel.id'] ?? '',
                    'hotel_name'            => $sunHotel_hotel_details['hotels']['hotel']['name'] ?? '',
                    'hotel_notes'           => $sunHotel_hotel_details['hotels']['hotel']['notes'] ?? '',
                    'stars_rating'          => isset ($hotel['review']['rating']) ? (int) $hotel['review']['rating'] : 1,
                    'hotel_curreny'         => 'EUR',
                    'min_price'             => !empty ($room_prices_arr) ? min($room_prices_arr) : 0,
                    'max_price'             => !empty ($room_prices_arr) ? max($room_prices_arr) : 0,
                    'hotel_Details'         => $sunHotel_hotel_details['hotels']['hotel'] ?? '',
                    'rooms_options'         => $rooms_list,
                ];
                
                if(count($roomsSunhotel) > 0){
                    array_push($hotelsSunhotel, $hotel_list_item);
                }
                $roomsSunhotel = [];
            }
            // return 'Not Matched';
        }
        
        $totalNumberSunhotel    = count($hotelsSunhotel);
        return $result_Data     = ['hotelsSunhotel'=>$hotelsSunhotel,'totalNumberSunhotel'=>$totalNumberSunhotel];
    }
    
    public static function sunHotelCustomSearch($hotel_Id,$child_per_room,$CheckInDate,$CheckOutDate,$numberOfRooms,$destinationCode,$numberOfAdults,$room,$numberOfChildrenWithInfants,$total_Adults,$total_Rooms,$child_Age_Array,$total_Childs,$sunhotelAdminMarkup,$sunhotelCustomerMarkup,$customer_Hotel_markup_SunHotel){
        $child_ages_string          = '';
        $infant                     = 0;
        $totalNumberSunhotel        = 0;
        $hotelsSunhotel             = [];
        $numberOfChildren           = 0;
        // $sunhotelAdminMarkup        = 0;
        $sunhotelCustomerMarkup     = $customer_Hotel_markup_SunHotel;
        $sunhotelAdminMarkupType    = 'Percentage';
        $sunhotelCustomerMarkupType = 'Percentage';
        
        // 46572 For Room Notes
        
        for ($i = 0; $i < count($child_per_room); $i++) {
            $varName    = 'child_ages' . ($i + 1);
            $child_arr  = $varName;
            if (isset ($child_arr) && is_array($child_arr)) {
                foreach ($child_arr as $key => $child) {
                    if ($child == 1) {
                        $infant = 1;
                        unset($child_arr[$key]);
                    }
                }
                $numberOfChildren += count($child_arr);
                if (!empty ($child_arr)) {
                    $child_ages_string .= implode(',', $child_arr);
                    if ($i < count($child_per_room) - 1) {
                        $child_ages_string .= ',';
                    }
                }
            }
        }
        
        $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/SearchV2?";
        $paramsSunhotel                 = array(
            'username'                  => env('SUN_HOTEL_USERNAME'),
            'password'                  => env('SUN_HOTEL_PASSWORD'),
            'language'                  => 'en',
            'currencies'                => 'EUR',
            'checkInDate'               => $CheckInDate,
            'checkOutDate'              => $CheckOutDate,
            'numberOfRooms'             => $numberOfRooms,
            'destination'               => '',
            'destinationID'             => '',
            'hotelIDs'                  => $hotel_Id,
            'resortIDs'                 => '',
            'accommodationTypes'        => '',
            'numberOfAdults'            => $numberOfAdults,
            'numberOfChildren'          => $numberOfChildren,
            'childrenAges'              => $numberOfChildren ? $child_ages_string : '',
            'infant'                    => $infant,
            'sortBy'                    => '',
            'sortOrder'                 => '',
            'exactDestinationMatch'     => '',
            'mealIds'                   => '',
            'excludeSharedFacilities'   => '',
            'showReviews'               => '1',
            'blockSuperdeal'            => $request->blockSuperdeal ?? '',
            'customerCountry'           => $request->customerCountry ?? '',
            'showRoomTypeName'          => $request->showRoomTypeName ?? '',
        );
        // return $paramsSunhotel;
        
        $curl                           = curl_init();
        $url                            = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        // return $url;
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                // CURLOPT_ENCODING => '',
                CURLOPT_ACCEPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        
        $response_xml   = curl_exec($curl);
        // return $response_xml;
        $response_json  = simplexml_load_string($response_xml);
        $response_json  = json_encode($response_json);
        $data           = json_decode($response_json, true);
        // return $data;
        curl_close($curl);
        
        // return $CheckInDate;
        
        if (!empty ($data) && isset ($data['hotels']['hotel'])) {
            $hotel = $data['hotels']['hotel'];
            // foreach ($data['hotels']['hotel'] as $hotel) {
                // return $hotel;
                $room_prices_arr    = [];
                $roomsSunhotel      = [];
                $rooms_list         = [];
                $total_Pax          = $numberOfAdults + $numberOfChildrenWithInfants;
                $new_roomsSunhotel  = [];
                
                if(isset($hotel['roomtypes']['roomtype'])){
                    $roomtype_Hotels = $hotel['roomtypes']['roomtype'];
                    foreach($roomtype_Hotels as $val_Rooms){
                        if(isset($val_Rooms['rooms']['room'])){
                            if(isset($val_Rooms['rooms']['room']['beds'])){
                                if($total_Pax == $val_Rooms['rooms']['room']['beds'] || $total_Pax < $val_Rooms['rooms']['room']['beds']){
                                    // return 'MATCHED';
                                    if(count($total_Adults) > 1){
                                        // return 'if';
                                        
                                        $status_Check_Array = [];
                                        for($ad=0; $ad<count($total_Adults); $ad++){
                                            $status                     = '0';
                                            $meal_ID                    = '';
                                            $singleRoomPrice            = '';
                                            $meal_Type                  = '';
                                            $meals                      = $val_Rooms['rooms']['room']['meals']['meal'] ?? null;
                                            $meal_ID                    = $meals['id'] ?? '';
                                            
                                            if ($meals !== null) {
                                                if (!is_array($meals)) {
                                                    $meals              = [$meals];
                                                } elseif (isset ($meals['id'])) {
                                                    $meals              = [$meals];
                                                }
                                                if (!empty ($meals) && isset ($meals[0]['prices']['price'])) {
                                                    $singleRoomPrice    = $meals[0]['prices']['price'];
                                                    $meal_Type          = $meals[0]['labelText'] ?? 'BB';
                                                }
                                            }
                                            
                                            if ($singleRoomPrice !== '') {
                                                $room_prices_arr[] = $singleRoomPrice;
                                            }
                                            
                                            $cancelationPolicyArr       = [];
                                            $cancel_Status              = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['text'] ?? '';
                                            $percentage                 = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['percentage'] ?? '';
                                            $deadline                   = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['deadline'] ?? '';
                                            if ($deadline == []) {
                                                $deadline               = '';
                                            }
                                            
                                            $formattedDeadlineDate      = '';
                                            if($deadline != ''){
                                                $checkInDate            = Carbon::parse($CheckInDate);
                                                $deadline_Date          = $checkInDate->subHours($deadline);
                                                $formattedDeadlineDate  = $deadline_Date->format('Y-m-d');
                                            }
                                            
                                            $cancel_items               = (Object) [
                                                'amount'                => $percentage,
                                                'from_date'             => $formattedDeadlineDate,
                                            ];
                                            $cancelationPolicyArr[]     = $cancel_items;
                                            
                                            $rooms_list[]               = (object)[
                                                'booking_req_id'            => $val_Rooms['rooms']['room']['id'],
                                                'allotment'                 => $val_Rooms['rooms']['room']['beds'],
                                                // 'allotment'                 => $total_Adults[$ad],
                                                'room_name'                 => $val_Rooms['room.type'] ?? '',
                                                'room_code'                 => $val_Rooms['rooms']['room']['id'],
                                                'request_type'              => '',
                                                'board_id'                  => $meal_Type,
                                                'board_code'                => $val_Rooms['roomtype.ID'],
                                                'rooms_total_tax'           => $singleRoomPrice,
                                                'rooms_total_price'         => $singleRoomPrice,
                                                'rooms_selling_price'       => $singleRoomPrice,
                                                'rooms_qty'                 => 1,
                                                'adults'                    => $total_Adults[$ad],
                                                'childs'                    => $total_Childs[$ad] ?? $numberOfChildrenWithInfants,
                                                'cancliation_policy_arr'    => $cancelationPolicyArr,
                                                'rooms_list'                => $val_Rooms['rooms']['room'],
                                                'meal_Type'                 => $meal_Type,
                                                'cancel_Status'             => $cancel_Status,
                                                'meal_ID'                   => $meal_ID,
                                                'child_Ages'                => $child_Age_Array[$ad] ?? '',
                                            ];
                                            
                                            array_push($roomsSunhotel, $rooms_list);
                                        }
                                    }else{
                                        // return 'else';
                                        
                                        $meal_ID                    = '';
                                        $singleRoomPrice            = '';
                                        $meal_Type                  = '';
                                        $meals                      = $val_Rooms['rooms']['room']['meals']['meal'] ?? null;
                                        $meal_ID                    = $meals['id'] ?? '';
                                        
                                        if ($meals !== null) {
                                            if (!is_array($meals)) {
                                                $meals              = [$meals];
                                            } elseif (isset ($meals['id'])) {
                                                $meals              = [$meals];
                                            }
                                            if (!empty ($meals) && isset ($meals[0]['prices']['price'])) {
                                                $singleRoomPrice    = $meals[0]['prices']['price'];
                                                $meal_Type          = $meals[0]['labelText'] ?? 'BB';
                                            }
                                        }
                                        
                                        if ($singleRoomPrice !== '') {
                                            $room_prices_arr[] = $singleRoomPrice;
                                        }
                                        
                                        $cancelationPolicyArr       = [];
                                        $cancel_Status              = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['text'] ?? '';
                                        $percentage                 = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['percentage'] ?? '';
                                        $deadline                   = $val_Rooms['rooms']['room']['cancellation_policies']['cancellation_policy']['deadline'] ?? '';
                                        if ($deadline == []) {
                                            $deadline               = '';
                                        }
                                        
                                        $formattedDeadlineDate      = '';
                                        if($deadline != ''){
                                            $checkInDate            = Carbon::parse($CheckInDate);
                                            $deadline_Date          = $checkInDate->subHours($deadline);
                                            $formattedDeadlineDate  = $deadline_Date->format('Y-m-d');
                                        }
                                        
                                        $cancel_items               = (Object) [
                                            'amount'                => $percentage,
                                            'from_date'             => $formattedDeadlineDate,
                                        ];
                                        $cancelationPolicyArr[]     = $cancel_items;
                                        
                                        $rooms_list[] = (object)[
                                            'booking_req_id'            => $val_Rooms['rooms']['room']['id'],
                                            'allotment'                 => $val_Rooms['rooms']['room']['beds'],
                                            'room_name'                 => $val_Rooms['room.type'] ?? '',
                                            'room_code'                 => $val_Rooms['rooms']['room']['id'],
                                            'request_type'              => '',
                                            'board_id'                  => $meal_Type,
                                            'board_code'                => $val_Rooms['roomtype.ID'],
                                            'rooms_total_tax'           => $singleRoomPrice,
                                            'rooms_total_price'         => $singleRoomPrice,
                                            'rooms_selling_price'       => $singleRoomPrice,
                                            'rooms_qty'                 => $numberOfRooms,
                                            'adults'                    => $numberOfAdults,
                                            'childs'                    => $numberOfChildrenWithInfants,
                                            'cancliation_policy_arr'    => $cancelationPolicyArr,
                                            'rooms_list'                => $val_Rooms['rooms']['room'],
                                            'meal_Type'                 => $meal_Type,
                                            'cancel_Status'             => $cancel_Status,
                                            'meal_ID'                   => $meal_ID,
                                            'child_Ages'                => $child_Age_Array,
                                        ];
                                        
                                        array_push($roomsSunhotel, $rooms_list);
                                    }
                                }
                            }
                        }
                    }
                }
                
                $sunHotel_hotel_details = SunHotel_Controller::sunHotelDetails($hotel['hotel.id'] ?? '');
                
                $hotel_list_item = (Object) [
                    'hotel_provider'        => 'Sunhotel',
                    'admin_markup'          => $sunhotelAdminMarkup,
                    'customer_markup'       => $sunhotelCustomerMarkup,
                    'admin_markup_type'     => $sunhotelAdminMarkupType,
                    'customer_markup_type'  => $sunhotelCustomerMarkupType,
                    'hotel_id'              => $sunHotel_hotel_details['hotels']['hotel']['hotel.id'] ?? '',
                    'hotel_name'            => $sunHotel_hotel_details['hotels']['hotel']['name'] ?? '',
                    'hotel_notes'           => $sunHotel_hotel_details['hotels']['hotel']['notes'] ?? '',
                    'stars_rating'          => isset ($hotel['review']['rating']) ? (int) $hotel['review']['rating'] : 1,
                    'hotel_curreny'         => 'EUR',
                    'min_price'             => !empty ($room_prices_arr) ? min($room_prices_arr) : 0,
                    'max_price'             => !empty ($room_prices_arr) ? max($room_prices_arr) : 0,
                    'hotel_Details'         => $sunHotel_hotel_details['hotels']['hotel'] ?? '',
                    'rooms_options'         => $rooms_list,
                ];
                
                if(count($roomsSunhotel) > 0){
                    array_push($hotelsSunhotel, $hotel_list_item);
                }
                $roomsSunhotel = [];
            // }
        }
        
        $totalNumberSunhotel    = count($hotelsSunhotel);
        return $result_Data     = ['hotelsSunhotel'=>$hotelsSunhotel,'totalNumberSunhotel'=>$totalNumberSunhotel];
    }
    
    public static function sunHotelDetails($hotel_code){
        $curl                           = curl_init();
        $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/GetStaticHotelsAndRooms?";
        $paramsSunhotel                 = array(
            'username'                  => env('SUN_HOTEL_USERNAME'),
            'password'                  => env('SUN_HOTEL_PASSWORD'),
            'language'                  => 'en',
            'destination'               => '',
            'hotelIDs'                  => $hotel_code ?? '',
            'resortIDs'                 => '',
            'accommodationTypes'        => '',
            'sortBy'                    => '',
            'sortOrder'                 => '',
            'exactDestinationMatch'     => '',
        );
        // return $paramsSunhotel;
        $url                            = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        // return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml       = curl_exec($curl);
        // return $response_xml;
        $response_json      = simplexml_load_string($response_xml);
        $response_json      = json_encode($response_json);
        $data               = json_decode($response_json, true);
        return $data;
        curl_close($curl);
    }
    
    public static function sunHotelPreBook($request){
        // return $request['childrenAges'];
        // return count($request['childrenAges']);
        
        $child_Ages_Index       = '';
        // return $request['childrenAges'];
        if(isset($request['childrenAges'][0]) && $request['childrenAges'][0] != null){
            $children_Ages_Array    = array_merge(...$request['childrenAges']);
            
            // return $children_Ages_Array;
            
            if(count($children_Ages_Array) > 0){
                $child_Ages_Index       = $children_Ages_Array[0];
                $child_Ages_Arr         = array(
                    'childrenAges'      => $child_Ages_Index,
                );
            }
        }
        
        if($child_Ages_Index != ''){
            for($cA=1; $cA<count($children_Ages_Array); $cA++){
                $child_Ages_Index   = $child_Ages_Index.', '.$children_Ages_Array[$cA];
            }
            
            $child_Ages_Arr = array(
                'childrenAges'      => $child_Ages_Index
            );
        }else{
            $child_Ages_Arr         = array(
                'childrenAges'      => '',
            );
        }
        
        // return $child_Ages_Arr;
        
        $curl                   = curl_init();
        // $baseUrlSunhotel        = "http://xml.sunhotels.net/15/PostGet/StaticXMLAPI.asmx/PreBook?";
        $baseUrlSunhotel        = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/PreBookV2?";
        $paramsSunhotel         = array(
            'userName'          => env('SUN_HOTEL_USERNAME'),
            'password'          => env('SUN_HOTEL_PASSWORD'),
            'currency'          => $request['currency'],
            'language'          => $request['language'],
            'checkInDate'       => $request['checkInDate'],
            'checkOutDate'      => $request['checkOutDate'],
            'rooms'             => $request['rooms'],
            'adults'            => $request['adults'],
            'children'          => $request['children'],
            'childrenAges'      => $child_Ages_Arr ?? '',
            'infant'            => $request['infant'],
            'mealId'            => $request['mealId'],
            'CustomerCountry'   => $request['CustomerCountry'],
            'B2C'               => $request['B2C'],
            'searchPrice'       => $request['searchPrice'],
            'roomId'            => $request['roomId'],
            'hotelId'           => $request['hotelId'] ?? '',
            'roomtypeId'        => $request['roomtypeId'] ?? '',
            'blockSuperDeal'    => $request['blockSuperDeal'] ?? '',
            'showPriceBreakdown'=> $request['showPriceBreakdown'] ?? '',
        );
        
        $paramsSunhotel = array_merge($paramsSunhotel,$child_Ages_Arr);
        
        // return $paramsSunhotel;
        $url                    = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        // return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml           = curl_exec($curl);
        // return $response_xml;
        $response_json          = simplexml_load_string($response_xml);
        $response_json          = json_encode($response_json);
        $data                   = json_decode($response_json, true);
        return $data;
        curl_close($curl);
    }
    
    public static function sunHotelBook($hotel_request_data,$rooms_list,$others_adults,$childs,$child_Ages_Arr,$total_Rooms,$total_Adults,$total_Childs,$hotel_checkout_select){
        // return $hotel_request_data;
        
        // return $rooms_list;
        
        $curl                           = curl_init();
        // $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/StaticXMLAPI.asmx/Book?";
        $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/BookV2?";
        // $paramsSunhotel                 = array(
        //     'username'                  => env('SUN_HOTEL_USERNAME'),
        //     'password'                  => env('SUN_HOTEL_PASSWORD'),
        //     'currency'                  => 'EUR',
        //     'language'                  => 'en',
        //     'email'                     => $hotel_request_data->lead_email,
        //     'checkInDate'               => '2024-06-25',
        //     'checkOutDate'              => '2024-06-30',
        //     'roomId'                    => $rooms_list->room_code,
        //     'rooms'                     => $rooms_list->rooms_qty,
        //     'adults'                    => $rooms_list->adults,
        //     'children'                  => $rooms_list->childs,
        //     'infant'                    => '0',
        //     'yourRef'                   => 'Test@20booking',
        //     'specialrequest'            => '',
        //     'mealId'                    => $rooms_list->meal_ID,
        //     // Adult
        //     'adultGuest1FirstName'      => $hotel_request_data->lead_first_name,
        //     'adultGuest1LastName'       => $hotel_request_data->lead_last_name,
        //     'adultGuest2FirstName'      => 'Test2',
        //     'adultGuest2LastName'       => 'Adult2',
        //     'adultGuest3FirstName'      => '',
        //     'adultGuest3LastName'       => '',
        //     'adultGuest4FirstName'      => '',
        //     'adultGuest4LastName'       => '',
        //     'adultGuest5FirstName'      => '',
        //     'adultGuest5LastName'       => '',
        //     'adultGuest6FirstName'      => '',
        //     'adultGuest6LastName'       => '',
        //     'adultGuest7FirstName'      => '',
        //     'adultGuest7LastName'       => '',
        //     'adultGuest8FirstName'      => '',
        //     'adultGuest8LastName'       => '',
        //     'adultGuest9FirstName'      => '',
        //     'adultGuest9LastName'       => '',
        //     // Adult
        //     // Child
        //     'childrenGuest1FirstName'   => '',
        //     'childrenGuest1LastName'    => '',
        //     'childrenGuestAge1'         => '',
        //     'childrenGuest2FirstName'   => '',
        //     'childrenGuest2LastName'    => '',
        //     'childrenGuestAge2'         => '',
        //     'childrenGuest3FirstName'   => '',
        //     'childrenGuest3LastName'    => '',
        //     'childrenGuestAge3'         => '',
        //     'childrenGuest4FirstName'   => '',
        //     'childrenGuest4LastName'    => '',
        //     'childrenGuestAge4'         => '',
        //     'childrenGuest5FirstName'   => '',
        //     'childrenGuest5LastName'    => '',
        //     'childrenGuestAge5'         => '',
        //     'childrenGuest6FirstName'   => '',
        //     'childrenGuest6LastName'    => '',
        //     'childrenGuestAge6'         => '',
        //     'childrenGuest7FirstName'   => '',
        //     'childrenGuest7LastName'    => '',
        //     'childrenGuestAge7'         => '',
        //     'childrenGuest8FirstName'   => '',
        //     'childrenGuest8LastName'    => '',
        //     'childrenGuestAge8'         => '',
        //     'childrenGuest9FirstName'   => '',
        //     'childrenGuest9LastName'    => '',
        //     'childrenGuestAge9'         => '',
        //     // Child
        //     'customerEmail'             => '',
        //     'paymentMethodId'           => '1',
        //     'creditCardType'            => '',
        //     'creditCardNumber'          => '',
        //     'creditCardHolder'          => '',
        //     'creditCardCVV2'            => '',
        //     'creditCardExpYear'         => '',
        //     'creditCardExpMonth'        => '',
        //     'customerEmail'             => '',
        //     'invoiceRef'                => '',
        //     'CustomerCountry'           => 'gb',
        //     'B2C'                       => '0',
        // );
        
        $paramsSunhotel                 = array(
            'username'                  => env('SUN_HOTEL_USERNAME'),
            'password'                  => env('SUN_HOTEL_PASSWORD'),
            'currency'                  => 'EUR',
            'language'                  => 'en',
            'email'                     => $hotel_request_data->lead_email,
            'checkInDate'               => $hotel_checkout_select->checkIn,
            'checkOutDate'              => $hotel_checkout_select->checkOut,
            'roomId'                    => $rooms_list->room_code,
            'rooms'                     => $total_Rooms,
            'adults'                    => $total_Adults,
            'children'                  => $total_Childs,
            'infant'                    => '0',
            'yourRef'                   => 'Test@20booking',
            'specialrequest'            => '',
            'mealId'                    => $rooms_list->meal_ID,
            'customerEmail'             => '',
            'paymentMethodId'           => '1',
            'creditCardType'            => '',
            'creditCardNumber'          => '',
            'creditCardHolder'          => '',
            'creditCardCVV2'            => '',
            'creditCardExpYear'         => '',
            'creditCardExpMonth'        => '',
            'customerEmail'             => '',
            'invoiceRef'                => '',
            'CustomerCountry'           => 'gb',
            'B2C'                       => '0',
            'commissionAmountInHotelCurrency' => '',
            'preBookCode'               => '',
            // Adult
            'adultGuest1FirstName'      => $hotel_request_data->lead_first_name,
            'adultGuest1LastName'       => $hotel_request_data->lead_last_name,
            // Adult
        );
        
        // Remaining Adult
        if(count($others_adults) > 0){
            for($x=2; $x<10; $x++){
                $Adult_Arr                       = array(
                    // Adult
                    'adultGuest'.$x.'FirstName' => $others_adults[$x-2]->first_Name ?? '',
                    'adultGuest'.$x.'LastName'  => $others_adults[$x-2]->last_Name ?? '',
                    // Adult
                );
                $paramsSunhotel = array_merge($paramsSunhotel,$Adult_Arr);
            }
        }else{
            for($x=2; $x<10; $x++){
                $Adult_Arr                       = array(
                    // Adult
                    'adultGuest'.$x.'FirstName' => '',
                    'adultGuest'.$x.'LastName'  => '',
                    // Adult
                );
                $paramsSunhotel = array_merge($paramsSunhotel,$Adult_Arr);
            }
        }
        // Remaining Adult
        
        // Remaining Child
        if(count($childs) > 0){
            for($x=1; $x<10; $x++){
                $child_Arr                           = array(
                    // Child
                    'childrenGuest'.$x.'FirstName'  => $childs[$x-1]->first_Name ?? '',
                    'childrenGuest'.$x.'LastName'   => $childs[$x-1]->last_Name ?? '',
                    'childrenGuestAge'.$x           => $child_Ages_Arr[$x-1] ?? '',
                    // Child
                );
                $paramsSunhotel = array_merge($paramsSunhotel,$child_Arr);
            }
        }else{
            for($x=1; $x<10; $x++){
                $child_Arr                           = array(
                    // Child
                    'childrenGuest'.$x.'FirstName'  => '',
                    'childrenGuest'.$x.'LastName'   => '',
                    'childrenGuestAge'.$x           => '',
                    // Child
                );
                $paramsSunhotel = array_merge($paramsSunhotel,$child_Arr);
            }
        }
        // Remaining Child
        
        // return $paramsSunhotel;
        
        $url                            = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        // return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml   = curl_exec($curl);
        curl_close($curl);
        // return $response_xml;
        
        try {
            $response_json  = simplexml_load_string($response_xml);
            $response_json  = json_encode($response_json);
            $data           = json_decode($response_json, true);
            // return $data;
            // return $data['booking']['bookingnumber'];
            if (isset($data['booking']['bookingnumber'])) {
                $decode_Response = [
                    'request'   => $paramsSunhotel,
                    'response'  => $data['booking'],
                ];
                return $decode_Response;
            } else {
                Log::error('API request failed: ' . $response->status());
            }
        } catch (Exception $e) {
            Log::error('Error occurred while searching hotels: ' . $e->getMessage());
        }
    }
    
    public static function sunHotelCancel($bookingnumber){
        // return $request;
        $curl                           = curl_init();
        $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/CancelBooking?";
        $paramsSunhotel                 = array(
            'username'                  => env('SUN_HOTEL_USERNAME'),
            'password'                  => env('SUN_HOTEL_PASSWORD'),
            'language'                  => 'en',
            'bookingID'                 => $bookingnumber,
        );
        // return $paramsSunhotel;
        $url                            = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        // return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml       = curl_exec($curl);
        $response_json      = simplexml_load_string($response_xml);
        $response_json      = json_encode($response_json);
        $data               = json_decode($response_json, true);
        return $data;
        curl_close($curl);
    }
    
    public static function sunHotelBookingDetails($bookingID){
        // return $request;
        $curl                           = curl_init();
        $baseUrlSunhotel        = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/GetBookingInformationV2?";
        $paramsSunhotel         = array(
            'username'          => env('SUN_HOTEL_USERNAME'),
            'password'          => env('SUN_HOTEL_PASSWORD'),
            'language'          => 'en',
            'bookingID'         => $bookingID,
            'reference'         => '',
            'createdDateFrom'   => '',
            'createdDateTo'     => '',
            'arrivalDateFrom'   => '',
            'arrivalDateTo'     => '',
            'showGuests'        => '',
        );
        // return $paramsSunhotel;
        $url                            = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        // return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml       = curl_exec($curl);
        $response_json      = simplexml_load_string($response_xml);
        $response_json      = json_encode($response_json);
        $data               = json_decode($response_json, true);
        return $data;
        curl_close($curl);
    }
    
    // Test Cases
    public static function sunHotel_Test_Cases_Meta(Request $request){
        $curl                           = curl_init();
        $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/GetStaticHotelsAndRooms?";
        $paramsSunhotel                 = array(
            'username'                  => env('SUN_HOTEL_USERNAME'),
            'password'                  => env('SUN_HOTEL_PASSWORD'),
            'language'                  => $request->language,
            'destination'               => $request->destination ?? '',
            'hotelIDs'                  => $request->hotelIDs ?? '',
            'resortIDs'                 => $request->resortIDs ?? '',
            'accommodationTypes'        => $request->accommodationTypes ?? '',
            'sortBy'                    => $request->sortBy ?? '',
            'sortOrder'                 => $request->sortOrder ?? '',
            'exactDestinationMatch'     => $request->exactDestinationMatch ?? '',
        );
        // return $paramsSunhotel;
        $url                            = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        // return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml       = curl_exec($curl);
        // return $response_xml;
        $response_json      = simplexml_load_string($response_xml);
        $response_json      = json_encode($response_json);
        $data               = json_decode($response_json, true);
        // return $data;
        curl_close($curl);
        $hotels_detials_data    = [];
        if (!empty ($data) && isset ($data['hotels']['hotel'])) {
            $sunHotel_faclility_arr = [];
            $image_URL              = '';
            
            // return $data['hotels']['hotel']['features']['feature'][0]['@attributes']['name'];
            
            if(isset($data['hotels']['hotel']['images']['image'][0]['smallImage']['@attributes']['url'])){
                $image_URL    = env('SUN_HOTEL_IMAGE_URL').$data['hotels']['hotel']['images']['image'][0]['smallImage']['@attributes']['url'];
            }
            
            if (isset($data['hotels']['hotel']['features']['feature'])) {
                $sunHotel_facility = $data['hotels']['hotel']['features']['feature'];
                for ($count = 0; $count < count($sunHotel_facility); $count++) {
                    if ($count < 7) {
                        $sunHotel_faclility_arr[] = $sunHotel_facility[$count]['@attributes']['name'];
                    } else {
                        break;
                    }
                }
            }
            
            $hotels_detials_data = [
                'image'         => $image_URL,
                'address'       => $data['hotels']['hotel']['hotel.address'],
                'facilities'    => $sunHotel_faclility_arr
            ];
            
            // foreach ($data['hotels']['hotel']['images']['image'] as $val_Image) {
            //     return $val_Image;
                
            //     if(isset($val_Image['smallImage']['@attributes']['url'])){
            //         $image_URL    = 'https://hotelimages.sunhotels.net/HotelInfo/hotelImage.aspx?'.$val_Image['smallImage']['@attributes']['url'];
            //         $image_Height = $val_Image['smallImage']['@attributes']['height'];
            //         $image_Width  = $val_Image['smallImage']['@attributes']['width'];
            //     }
                
            //     // fullSizeImage
            // }
            
            
        }
        
        return $hotels_detials_data;
    }
    
    public static function sunHotel_Test_Cases_Search(Request $request){
        $curl                           = curl_init();
        // $url                            = 'http://xml.sunhotels.net/15/PostGet/StaticXMLAPI.asmx/SearchV2?username=APEITSolutionsTEST&password=APE%212024%23&language=en&currencies=EUR&checkInDate=2024-06-25&checkOutDate=2024-06-30&numberOfRooms=1&destination=DXB&destinationID=&hotelIDs=&resortIDs=&accommodationTypes=&numberOfAdults=2&numberOfChildren=0&childrenAges=&infant=0&sortBy=&sortOrder=&exactDestinationMatch=&mealIds=&excludeSharedFacilities=&showReviews=1';
        
        // $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/StaticXMLAPI.asmx/Search?";
        $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/SearchV2?";
        $paramsSunhotel                 = array(
            'username'                  => env('SUN_HOTEL_USERNAME'),
            'password'                  => env('SUN_HOTEL_PASSWORD'),
            'language'                  => $request->language,
            'currencies'                => $request->currencies,
            'checkInDate'               => $request->checkInDate,
            'checkOutDate'              => $request->checkOutDate,
            'numberOfRooms'             => $request->numberOfRooms,
            'destination'               => $request->destination ?? '',
            'destinationID'             => '',
            'hotelIDs'                  => $request->hotelIDs ?? '',
            'resortIDs'                 => '',
            'accommodationTypes'        => '',
            'numberOfAdults'            => $request->numberOfAdults,
            'numberOfChildren'          => $request->numberOfChildren,
            'childrenAges'              => $request->childrenAges ?? '',
            'infant'                    => $request->infant,
            'sortBy'                    => '',
            'sortOrder'                 => '',
            'exactDestinationMatch'     => '',
            'mealIds'                   => '',
            'excludeSharedFacilities'   => '',
            'showReviews'               => $request->showReviews,
            'blockSuperdeal'            => $request->blockSuperdeal ?? '',
            'customerCountry'           => $request->customerCountry ?? '',
            'showRoomTypeName'          => $request->showRoomTypeName ?? '',
        );
        // return $paramsSunhotel;
        $url                = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml       = curl_exec($curl);
        return $response_xml;
        $response_json      = simplexml_load_string($response_xml);
        $response_json      = json_encode($response_json);
        $data               = json_decode($response_json, true);
        return $data;
        curl_close($curl);
        
        // if (!empty ($data) && isset ($data['hotels']['hotel'])) {
        //     foreach ($data['hotels']['hotel'] as $hotel) {
        //         if(isset($hotel['roomtypes']['roomtype'])){
        //             foreach($hotel['roomtypes']['roomtype'] as $val_Rooms){
        //                 if(isset($val_Rooms['rooms']['room'])){
        //                     if(isset($val_Rooms['rooms']['room']['beds'])){
        //                         $meals                      = $val_Rooms['rooms']['room']['meals']['meal'] ?? null;
                                
        //                         return $val_Rooms['rooms']['room'];
        //                         if(count($val_Rooms['rooms']['room']['meals']) > 1){
        //                             return $val_Rooms;
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        
        // return $data;
    }
    
    public static function sunHotel_Test_Cases_PreBook(Request $request){
        $curl                   = curl_init();
        // $baseUrlSunhotel        = "http://xml.sunhotels.net/15/PostGet/StaticXMLAPI.asmx/PreBook?";
        // $baseUrlSunhotel        = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/PreBook?";
        $baseUrlSunhotel        = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/PreBookV2?";
        $paramsSunhotel         = array(
            'userName'          => env('SUN_HOTEL_USERNAME'),
            'password'          => env('SUN_HOTEL_PASSWORD'),
            'currency'          => $request->currency,
            'language'          => $request->language,
            'checkInDate'       => $request->checkInDate,
            'checkOutDate'      => $request->checkOutDate,
            'rooms'             => $request->rooms,
            'adults'            => $request->adults,
            'children'          => $request->children,
            'childrenAges'      => $request->childrenAges ?? '',
            'infant'            => $request->infant,
            'mealId'            => $request->mealId,
            'CustomerCountry'   => $request->CustomerCountry,
            'B2C'               => $request->B2C,
            'searchPrice'       => $request->searchPrice,
            'roomId'            => $request->roomId ?? '',
            'hotelId'           => $request->hotelId ?? '',
            'roomtypeId'        => $request->roomtypeId ?? '',
            'blockSuperDeal'    => $request->blockSuperDeal ?? '',
            'showPriceBreakdown'=> $request->showPriceBreakdown ?? '',
        );
        // return $paramsSunhotel;
        $url                    = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml           = curl_exec($curl);
        return $response_xml;
        $response_json          = simplexml_load_string($response_xml);
        $response_json          = json_encode($response_json);
        $data                   = json_decode($response_json, true);
        return $data;
        curl_close($curl);
    }
    
    public static function sunHotel_Test_Cases_Book(Request $request){
        $curl                           = curl_init();
        // $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/StaticXMLAPI.asmx/Book?";
        $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/BookV2?";
        // $paramsSunhotel                 = array(
        //     'username'                  => env('SUN_HOTEL_USERNAME'),
        //     'password'                  => env('SUN_HOTEL_PASSWORD'),
        //     'currency'                  => $request->currency,
        //     'language'                  => $request->language,
        //     'email'                     => $request->email,
        //     'checkInDate'               => $request->checkInDate,
        //     'checkOutDate'              => $request->checkOutDate,
        //     'roomId'                    => $request->roomId,
        //     'rooms'                     => $request->rooms,
        //     'adults'                    => $request->adults,
        //     'children'                  => $request->children,
        //     'infant'                    => $request->infant,
        //     'yourRef'                   => $request->yourRef,
        //     'specialrequest'            => $request->specialrequest,
        //     'mealId'                    => $request->mealId,
        //     // Adult
        //     'adultGuest1FirstName'      => $request->adultGuest1FirstName,
        //     'adultGuest1LastName'       => $request->adultGuest1LastName,
        //     'adultGuest2FirstName'      => $request->adultGuest2FirstName,
        //     'adultGuest2LastName'       => $request->adultGuest2LastName,
        //     'adultGuest3FirstName'      => $request->adultGuest3FirstName,
        //     'adultGuest3LastName'       => $request->adultGuest3LastName,
        //     'adultGuest4FirstName'      => $request->adultGuest4FirstName,
        //     'adultGuest4LastName'       => $request->adultGuest4LastName,
        //     'adultGuest5FirstName'      => $request->adultGuest5FirstName,
        //     'adultGuest5LastName'       => $request->adultGuest5LastName,
        //     'adultGuest6FirstName'      => $request->adultGuest6FirstName,
        //     'adultGuest6LastName'       => $request->adultGuest6LastName,
        //     'adultGuest7FirstName'      => $request->adultGuest7FirstName,
        //     'adultGuest7LastName'       => $request->adultGuest7LastName,
        //     'adultGuest8FirstName'      => $request->adultGuest8FirstName,
        //     'adultGuest8LastName'       => $request->adultGuest8LastName,
        //     'adultGuest9FirstName'      => $request->adultGuest9FirstName,
        //     'adultGuest9LastName'       => $request->adultGuest9LastName,
        //     // Adult
        //     // Child
        //     'childrenGuest1FirstName'   => $request->childrenGuest1FirstName,
        //     'childrenGuest1LastName'    => $request->childrenGuest1LastName,
        //     'childrenGuestAge1'         => $request->childrenGuestAge1,
        //     'childrenGuest2FirstName'   => $request->childrenGuest2FirstName,
        //     'childrenGuest2LastName'    => $request->childrenGuest2LastName,
        //     'childrenGuestAge2'         => $request->childrenGuestAge2,
        //     'childrenGuest3FirstName'   => $request->childrenGuest3FirstName,
        //     'childrenGuest3LastName'    => $request->childrenGuest3LastName,
        //     'childrenGuestAge3'         => $request->childrenGuestAge3,
        //     'childrenGuest4FirstName'   => $request->childrenGuest4FirstName,
        //     'childrenGuest4LastName'    => $request->childrenGuest4LastName,
        //     'childrenGuestAge4'         => $request->childrenGuestAge4,
        //     'childrenGuest5FirstName'   => $request->childrenGuest5FirstName,
        //     'childrenGuest5LastName'    => $request->childrenGuest5LastName,
        //     'childrenGuestAge5'         => $request->childrenGuestAge5,
        //     'childrenGuest6FirstName'   => $request->childrenGuest6FirstName,
        //     'childrenGuest6LastName'    => $request->childrenGuest6LastName,
        //     'childrenGuestAge6'         => $request->childrenGuestAge6,
        //     'childrenGuest7FirstName'   => $request->childrenGuest7FirstName,
        //     'childrenGuest7LastName'    => $request->childrenGuest7LastName,
        //     'childrenGuestAge7'         => $request->childrenGuestAge7,
        //     'childrenGuest8FirstName'   => $request->childrenGuest8FirstName,
        //     'childrenGuest8LastName'    => $request->childrenGuest8LastName,
        //     'childrenGuestAge8'         => $request->childrenGuestAge8,
        //     'childrenGuest9FirstName'   => $request->childrenGuest9FirstName,
        //     'childrenGuest9LastName'    => $request->childrenGuest9LastName,
        //     'childrenGuestAge9'         => $request->childrenGuestAge9,
        //     // Child
        //     'customerEmail'             => $request->customerEmail,
        //     'paymentMethodId'           => $request->paymentMethodId,
        //     'creditCardType'            => $request->creditCardType,
        //     'creditCardNumber'          => $request->creditCardNumber,
        //     'creditCardHolder'          => $request->creditCardHolder,
        //     'creditCardCVV2'            => $request->creditCardCVV2,
        //     'creditCardExpYear'         => $request->creditCardExpYear,
        //     'creditCardExpMonth'        => $request->creditCardExpMonth,
        //     'customerEmail'             => $request->customerEmail,
        //     'invoiceRef'                => $request->invoiceRef,
        //     'CustomerCountry'           => $request->CustomerCountry,
        //     'B2C'                       => $request->B2C,
        // );
        
        $paramsSunhotel                 = array(
            'username'                  => env('SUN_HOTEL_USERNAME'),
            'password'                  => env('SUN_HOTEL_PASSWORD'),
            'currency'                  => 'EUR',
            'language'                  => 'en',
            'email'                     => 'test1@gmail.com',
            'checkInDate'               => '2024-10-20',
            'checkOutDate'              => '2024-10-21',
            'roomId'                    => '185646164',
            'rooms'                     => '1',
            'adults'                    => '2',
            'children'                  => '0',
            'infant'                    => '0',
            'yourRef'                   => 'Test@20booking',
            'specialrequest'            => '',
            'mealId'                    => '1',
            // Adult
            'adultGuest1FirstName'      => 'Test1',
            'adultGuest1LastName'       => 'Adult1',
            'adultGuest2FirstName'      => 'Test2',
            'adultGuest2LastName'       => 'Adult2',
            'adultGuest3FirstName'      => '',
            'adultGuest3LastName'       => '',
            'adultGuest4FirstName'      => '',
            'adultGuest4LastName'       => '',
            'adultGuest5FirstName'      => '',
            'adultGuest5LastName'       => '',
            'adultGuest6FirstName'      => '',
            'adultGuest6LastName'       => '',
            'adultGuest7FirstName'      => '',
            'adultGuest7LastName'       => '',
            'adultGuest8FirstName'      => '',
            'adultGuest8LastName'       => '',
            'adultGuest9FirstName'      => '',
            'adultGuest9LastName'       => '',
            // Adult
            // Child
            'childrenGuest1FirstName'   => '',
            'childrenGuest1LastName'    => '',
            'childrenGuestAge1'         => '',
            'childrenGuest2FirstName'   => '',
            'childrenGuest2LastName'    => '',
            'childrenGuestAge2'         => '',
            'childrenGuest3FirstName'   => '',
            'childrenGuest3LastName'    => '',
            'childrenGuestAge3'         => '',
            'childrenGuest4FirstName'   => '',
            'childrenGuest4LastName'    => '',
            'childrenGuestAge4'         => '',
            'childrenGuest5FirstName'   => '',
            'childrenGuest5LastName'    => '',
            'childrenGuestAge5'         => '',
            'childrenGuest6FirstName'   => '',
            'childrenGuest6LastName'    => '',
            'childrenGuestAge6'         => '',
            'childrenGuest7FirstName'   => '',
            'childrenGuest7LastName'    => '',
            'childrenGuestAge7'         => '',
            'childrenGuest8FirstName'   => '',
            'childrenGuest8LastName'    => '',
            'childrenGuestAge8'         => '',
            'childrenGuest9FirstName'   => '',
            'childrenGuest9LastName'    => '',
            'childrenGuestAge9'         => '',
            // Child
            'customerEmail'             => '',
            'paymentMethodId'           => '1',
            'creditCardType'            => '',
            'creditCardNumber'          => '',
            'creditCardHolder'          => '',
            'creditCardCVV2'            => '',
            'creditCardExpYear'         => '',
            'creditCardExpMonth'        => '',
            'customerEmail'             => '',
            'invoiceRef'                => '',
            'CustomerCountry'           => 'gb',
            'B2C'                       => '0',
            'commissionAmountInHotelCurrency' => '',
            'preBookCode'               => '066fd01c-8e4b-4705-bb00-6d6b3b4daca6',
        );
        
        // return $paramsSunhotel;
        $url                            = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml   = curl_exec($curl);
        return $response_xml;
        $response_json  = simplexml_load_string($response_xml);
        $response_json  = json_encode($response_json);
        $data           = json_decode($response_json, true);
        return $data;
        curl_close($curl);
    }
    
    public static function sunHotel_Test_Cases_Cancel(Request $request){
        // return $request;
        $curl                           = curl_init();
        $baseUrlSunhotel                = "http://xml.sunhotels.net/15/PostGet/NonStaticXMLAPI.asmx/CancelBooking?";
        $paramsSunhotel                 = array(
            'username'                  => env('SUN_HOTEL_USERNAME'),
            'password'                  => env('SUN_HOTEL_PASSWORD'),
            'language'                  => $request->language,
            'bookingID'                 => $request->bookingID,
        );
        // return $paramsSunhotel;
        $url                            = $baseUrlSunhotel . http_build_query($paramsSunhotel);
        // return $url;
        
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'location: Lahore, Pakistan',
                    'Authorization: Basic QVBFSVRTb2x1dGlvbnM6QVBFITIwMjQlMjM='
                ),
            )
        );
        $response_xml       = curl_exec($curl);
        $response_json      = simplexml_load_string($response_xml);
        $response_json      = json_encode($response_json);
        $data               = json_decode($response_json, true);
        return $data;
        curl_close($curl);
    }
    // Test Cases
}