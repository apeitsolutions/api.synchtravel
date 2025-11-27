<?php

namespace App\Http\Controllers\Stuba;

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

class Stuba_Controller extends Controller
{
    public static function stubaSearch($request,$hotelscodes,$arivaldate,$nights,$country_code,$roomsData,$customer_Hotel_markup_Stuba){
        $stuba_hotels_items = [];
        $stuba_hotels_count = 0;
        
        $xmlPayload = '<AvailabilitySearch xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                        <Authority xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                            <Org>'.env('STUBA_HOTEL_ORG').'</Org>
                            <User>'.env('STUBA_HOTEL_USERNAME').'</User>
                            <Password>'.env('STUBA_HOTEL_PASSWORD').'</Password>
                            <Currency>GBP</Currency>
                            <Language>en</Language>
                            <TestDebug>false</TestDebug>
                            <Version>1.28</Version>
                        </Authority>
                    <Hotels>';
        $hotelscodes        = array_slice($hotelscodes, 0, 300);
        // return $hotelscodes;
        if (isset($hotelscodes)) {
            foreach ($hotelscodes as $hotelscode) {
                $xmlPayload .= '<Id>' . $hotelscode . '</Id>';
                // $xmlPayload .= '<Id>16146172</Id>';
                // $xmlPayload .= '<Id>136620</Id>';
                // $xmlPayload .= '<Id>2976236</Id>';
                // $xmlPayload .= '<Id>46456121</Id>';
            }
        }
        
        $xmlPayload .= '</Hotels>
                            <HotelStayDetails>
                                <ArrivalDate>' . $arivaldate . '</ArrivalDate>
                            <Nights>' . $nights . '</Nights>
                            <Nationality>' . $country_code . '</Nationality>';
        foreach ($roomsData as $room) {
            $xmlPayload .= '<Room>';
            $xmlPayload .= '<Guests>';
            
            for ($i = 0; $i < $room->Adults; $i++) {
                $xmlPayload .= '<Adult/>';
            }
            
            foreach ($room->ChildrenAge as $childAgeIndex) {
                // return $childAgeIndex;
                $xmlPayload .= '<Child age="'.$childAgeIndex.'"/>';
            }
            
            $xmlPayload .= '</Guests>';
            $xmlPayload .= '</Room>';
        }
        
        $xmlPayload .= '</HotelStayDetails>
                            <HotelSearchCriteria>
                                <AvailabilityStatus>allocation</AvailabilityStatus>
                                <DetailLevel>basic</DetailLevel>
                            </HotelSearchCriteria>
                        </AvailabilitySearch>';
        // return $xmlPayload;
        
        // http://www.stubademo.com/RXLStagingServices/ASMX/XmlService.asmx
        
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'http://api.stuba.com/RXLServices/ASMX/XmlService.asmx',
                CURLOPT_RETURNTRANSFER => true,
                // CURLOPT_ENCODING => '',
                CURLOPT_ACCEPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $xmlPayload,
                CURLOPT_HTTPHEADER => array(
                    'op: AvailabilitySearch',
                    'Content-Type: text/xml; charset=utf-8'
                ),
            )
        );
        $response = curl_exec($curl);
        // echo $response;die;
        curl_close($curl);
        $xml            = simplexml_load_string($response);
        $json           = json_encode($xml);
        $responseData3  = json_decode($json, true);
        // return $responseData3;
        
        if (isset($responseData3['HotelAvailability'])) {
            $currency               = $responseData3['Currency'];
            $stuba_obj              = [];
            $stuba_hotels_count     = count($responseData3['HotelAvailability']);
            $stuba_curreny          = $currency;
            $stuba_obj              = $responseData3['HotelAvailability'];
            $hotels                 = [];
            
            // return $stuba_obj;
            
            if(isset($stuba_obj['Hotel'])){
                $res                        = $stuba_obj;
                // if($res['Hotel']['@attributes']['id'] == '46456121'){
                //     return 'IF';
                // }
                
                $hotelStars                 = DB::table('stuba_hotel_details')->where('hotelid',  $res['Hotel']['@attributes']['id'])->value('hotelstars');
                $hotels_res                 = [
                    'hotel_provider'        => 'Stuba',
                    'admin_markup'          => 0,
                    'customer_markup'       => $customer_Hotel_markup_Stuba,
                    'admin_markup_type'     => 'Percentage',
                    'customer_markup_type'  => 'Percentage',
                    'hotel_id'              => $res['Hotel']['@attributes']['id'],
                    'hotel_name'            => $res['Hotel']['@attributes']['name'],
                    'stars_rating'          => (float)$hotelStars,
                    'hotel_curreny'         => $currency,
                    'min_price'             => '',
                    'max_price'             => '',
                ];
                $option_rooms               = [];
                if (isset($res['Result']['Room'])) {
                    $rooms                  = $res['Result'];
                    
                    $option_room            = [
                        'booking_req_id'    => $rooms['@attributes']['id'] ?? '',
                        'allotment'         => '2',
                    ];
                    
                    if (count($request->rooms_counter) == 1 && isset($rooms['Room']['Price']['@attributes']['amt'])) {
                        $price                                  = $rooms['Room']['Price']['@attributes']['amt'];
                        $roomPrices[]                           = $price;
                        $maxPrice                               = max($roomPrices);
                        $minPrice                               = min($roomPrices);
                        $hotels_res['min_price']                = $minPrice;
                        $hotels_res['max_price']                = $maxPrice;
                        $option_room['room_name']               = $rooms['Room']['RoomType']['@attributes']['text'];
                        $option_room['room_code']               = $rooms['Room']['RoomType']['@attributes']['code'];
                        $option_room['request_type']            = '';
                        $option_room['board_id']                = $rooms['Room']['MealType']['@attributes']['text'] ?? '';
                        $option_room['board_code']              = $rooms['Room']['MealType']['@attributes']['code'] ?? '';
                        $option_room['rooms_total_price']       = $rooms['Room']['Price']['@attributes']['amt'];
                        $option_room['CancellationPolicyStatus']= $rooms['Room']['CancellationPolicyStatus'] ?? '';
                        $option_room['adults']                  = $request->adult;
                        $option_room['childs']                  = $request->child;
                        $option_room['rooms_selling_price']     = '';
                        $option_room['rooms_qty']               = 1;
                        $option_room['cancliation_policy_arr']  = array((object)
                        [
                            'amount'                            => '',
                            'from_date'                         => ''
                        ]);
                        $option_room['rooms_list']              = $rooms;
                    }
                    
                    if (count($request->rooms_counter) > 1 || count($request->rooms_counter) == 1) {
                        $i = 0;
                        if (isset($rooms['Room'])) {
                            foreach ($rooms['Room'] as $room_res) {
                                if (isset($room_res['Price']['@attributes']['amt'])) {
                                    $price                                  = $room_res['Price']['@attributes']['amt'];
                                    $roomPrices[]                           = $price;
                                    $maxPrice                               = max($roomPrices);
                                    $minPrice                               = min($roomPrices);
                                    $hotels_res['min_price']                = $minPrice;
                                    $hotels_res['max_price']                = $maxPrice;
                                    $option_room['room_name']               = $room_res['RoomType']['@attributes']['text'];
                                    $option_room['room_code']               = $room_res['RoomType']['@attributes']['code'];
                                    $option_room['request_type']            = '';
                                    $option_room['board_id']                = $room_res['MealType']['@attributes']['text'] ?? '';
                                    $option_room['board_code']              = $room_res['MealType']['@attributes']['code'] ?? '';
                                    $option_room['rooms_total_price']       = $room_res['Price']['@attributes']['amt'];
                                    $option_room['room_code']               = $room_res['MealType']['@attributes']['code'] ?? '';
                                    $option_room['CancellationPolicyStatus']= $room_res['CancellationPolicyStatus'] ?? '';
                                    $adults                                 = $request->Adults[$i] ?? '0';
                                    $option_room['adults']                  = $adults;
                                    $children                               = $request->children[$i] ?? '0';
                                    $option_room['childs']                  = $children;
                                    $option_room['rooms_selling_price']     = '';
                                    $option_room['rooms_qty']               = $request->room;
                                    $option_room['cancliation_policy_arr']  = array((object)
                                    [
                                        'amount'                            => '',
                                        'from_date'                         => ''
                                    ]);
                                    $option_room['rooms_list']              = $rooms;
                                    $i++;
                                }
                                array_push($option_rooms, $option_room);
                            }
                        }
                    }
                }else{
                    foreach ($res['Result']  as $rooms) {
                        $option_room            = [
                            'booking_req_id'    => $rooms['@attributes']['id'] ?? '',
                            'allotment'         => '2',
                        ];
                        
                        // return count($request->rooms_counter);
                        
                        if (count($request->rooms_counter) == 1 && isset($rooms['Room']['Price']['@attributes']['amt'])) {
                            $price                                  = $rooms['Room']['Price']['@attributes']['amt'];
                            $roomPrices[]                           = $price;
                            $maxPrice                               = max($roomPrices);
                            $minPrice                               = min($roomPrices);
                            $hotels_res['min_price']                = $minPrice;
                            $hotels_res['max_price']                = $maxPrice;
                            $option_room['room_name']               = $rooms['Room']['RoomType']['@attributes']['text'];
                            $option_room['room_code']               = $rooms['Room']['RoomType']['@attributes']['code'];
                            $option_room['request_type']            = '';
                            $option_room['board_id']                = $rooms['Room']['MealType']['@attributes']['text'] ?? '';
                            $option_room['board_code']              = $rooms['Room']['MealType']['@attributes']['code'] ?? '';
                            $option_room['rooms_total_price']       = $rooms['Room']['Price']['@attributes']['amt'];
                            $option_room['CancellationPolicyStatus']= $rooms['Room']['CancellationPolicyStatus'] ?? '';
                            $option_room['adults']                  = $request->adult;
                            $option_room['childs']                  = $request->child;
                            $option_room['rooms_selling_price']     = '';
                            $option_room['rooms_qty']               = 1;
                            $option_room['cancliation_policy_arr']  = array((object)
                            [
                                'amount'                            => '',
                                'from_date'                         => ''
                            ]);
                            $option_room['rooms_list']              = $rooms;
                        }
                        
                        if (count($request->rooms_counter) > 1 || count($request->rooms_counter) == 1) {
                            $i = 0;
                            if (isset($rooms['Room'])) {
                                foreach ($rooms['Room'] as $room_res) {
                                    if (isset($room_res['Price']['@attributes']['amt'])) {
                                        $price                                  = $room_res['Price']['@attributes']['amt'];
                                        $roomPrices[]                           = $price;
                                        $maxPrice                               = max($roomPrices);
                                        $minPrice                               = min($roomPrices);
                                        $hotels_res['min_price']                = $minPrice;
                                        $hotels_res['max_price']                = $maxPrice;
                                        $option_room['room_name']               = $room_res['RoomType']['@attributes']['text'];
                                        $option_room['room_code']               = $room_res['RoomType']['@attributes']['code'];
                                        $option_room['request_type']            = '';
                                        $option_room['board_id']                = $room_res['MealType']['@attributes']['text'] ?? '';
                                        $option_room['board_code']              = $room_res['MealType']['@attributes']['code'] ?? '';
                                        $option_room['rooms_total_price']       = $room_res['Price']['@attributes']['amt'];
                                        $option_room['room_code']               = $room_res['MealType']['@attributes']['code'] ?? '';
                                        $option_room['CancellationPolicyStatus']= $room_res['CancellationPolicyStatus'] ?? '';
                                        $adults                                 = $request->Adults[$i] ?? '0';
                                        $option_room['adults']                  = $adults;
                                        $children                               = $request->children[$i] ?? '0';
                                        $option_room['childs']                  = $children;
                                        $option_room['rooms_selling_price']     = '';
                                        $option_room['rooms_qty']               = $request->room;
                                        $option_room['cancliation_policy_arr']  = array((object)
                                        [
                                            'amount'                            => '',
                                            'from_date'                         => ''
                                        ]);
                                        $option_room['rooms_list']              = $rooms;
                                        $i++;
                                    }
                                    array_push($option_rooms, $option_room);
                                }
                            }
                        }
                    }
                }
                
                $aggregatedRooms = [];
                
                if (count($roomsData) > 1) {
                    foreach ($option_rooms as $room) {
                        $key = $room['booking_req_id'] . '_' . $room['adults'] . '_' . $room['childs'];
                        if (!isset($aggregatedRooms[$key])) {
                            
                            // return 'Last IF';
                            
                            $aggregatedRooms[$key]          = [
                                'booking_req_id'            => $room['booking_req_id'],
                                'allotment'                 => $room['allotment'],
                                'room_name'                 => $room['room_name'],
                                'room_code'                 => $room['room_code'],
                                'request_type'              => $room['request_type'],
                                'board_id'                  => $room['board_id'],
                                'board_code'                => $room['board_code'],
                                'rooms_total_price'         => $room['rooms_total_price'],
                                'rooms_selling_price'       => $room['rooms_selling_price'],
                                'rooms_qty'                 => 1,
                                'adults'                    => $room['adults'],
                                'childs'                    => $room['childs'],
                                'cancliation_policy_arr'    => $room['cancliation_policy_arr'],
                                'rooms_list'                => $room['rooms_list']
                            ];
                        } else {
                            $aggregatedRooms[$key]['rooms_qty']++;
                            $aggregatedRooms[$key]['rooms_total_price'] += $room['rooms_total_price'];
                        }
                    }
                    $rooms =  array_values($aggregatedRooms);
                }
                else{
                    $rooms = $option_rooms;
                }
                
                $unique_booking_req_ids = [];
                $filtered_rooms         = [];
                
                foreach ($rooms as $room) {
                    if (!isset($unique_booking_req_ids[$room['booking_req_id']]) || $unique_booking_req_ids[$room['booking_req_id']] !== $room['adults']) {
                        $unique_booking_req_ids[$room['booking_req_id']]    = $room['adults'];
                        $filtered_rooms[]                                   = $room;
                    }
                }
                
                $hotels_res['rooms_options']    = $filtered_rooms;
                array_push($hotels, $hotels_res);
                $stuba_hotels_items             = $hotels;
                
            }else{
                foreach ($stuba_obj as $res) {
                    // if($res['Hotel']['@attributes']['id'] == '46456121'){
                    //     return $res;
                    // }
                    
                    $hotelStars                 = DB::table('stuba_hotel_details')->where('hotelid',  $res['Hotel']['@attributes']['id'])->value('hotelstars');
                    $hotels_res                 = [
                        'hotel_provider'        => 'Stuba',
                        'admin_markup'          => 0,
                        'customer_markup'       => $customer_Hotel_markup_Stuba,
                        'admin_markup_type'     => 'Percentage',
                        'customer_markup_type'  => 'Percentage',
                        'hotel_id'              => $res['Hotel']['@attributes']['id'],
                        'hotel_name'            => $res['Hotel']['@attributes']['name'],
                        'stars_rating'          => (float)$hotelStars,
                        'hotel_curreny'         => $currency,
                        'min_price'             => '',
                        'max_price'             => '',
                    ];
                    $option_rooms               = [];
                    
                    if (isset($res['Result']['Room'])) {
                        $rooms                  = $res['Result'];
                        
                        $option_room            = [
                            'booking_req_id'    => $rooms['@attributes']['id'] ?? '',
                            'allotment'         => '2',
                        ];
                        
                        if (count($request->rooms_counter) == 1 && isset($rooms['Room']['Price']['@attributes']['amt'])) {
                            $price                                  = $rooms['Room']['Price']['@attributes']['amt'];
                            $roomPrices[]                           = $price;
                            $maxPrice                               = max($roomPrices);
                            $minPrice                               = min($roomPrices);
                            $hotels_res['min_price']                = $minPrice;
                            $hotels_res['max_price']                = $maxPrice;
                            $option_room['room_name']               = $rooms['Room']['RoomType']['@attributes']['text'];
                            $option_room['room_code']               = $rooms['Room']['RoomType']['@attributes']['code'];
                            $option_room['request_type']            = '';
                            $option_room['board_id']                = $rooms['Room']['MealType']['@attributes']['text'] ?? '';
                            $option_room['board_code']              = $rooms['Room']['MealType']['@attributes']['code'] ?? '';
                            $option_room['rooms_total_price']       = $rooms['Room']['Price']['@attributes']['amt'];
                            $option_room['CancellationPolicyStatus']= $rooms['Room']['CancellationPolicyStatus'] ?? '';
                            $option_room['adults']                  = $request->adult;
                            $option_room['childs']                  = $request->child;
                            $option_room['rooms_selling_price']     = '';
                            $option_room['rooms_qty']               = 1;
                            $option_room['cancliation_policy_arr']  = array((object)
                            [
                                'amount'                            => '',
                                'from_date'                         => ''
                            ]);
                            $option_room['rooms_list']              = $rooms;
                        }
                        
                        if (count($request->rooms_counter) > 1 || count($request->rooms_counter) == 1) {
                            $i = 0;
                            if (isset($rooms['Room'])) {
                                foreach ($rooms['Room'] as $room_res) {
                                    if (isset($room_res['Price']['@attributes']['amt'])) {
                                        $price                                  = $room_res['Price']['@attributes']['amt'];
                                        $roomPrices[]                           = $price;
                                        $maxPrice                               = max($roomPrices);
                                        $minPrice                               = min($roomPrices);
                                        $hotels_res['min_price']                = $minPrice;
                                        $hotels_res['max_price']                = $maxPrice;
                                        $option_room['room_name']               = $room_res['RoomType']['@attributes']['text'];
                                        $option_room['room_code']               = $room_res['RoomType']['@attributes']['code'];
                                        $option_room['request_type']            = '';
                                        $option_room['board_id']                = $room_res['MealType']['@attributes']['text'] ?? '';
                                        $option_room['board_code']              = $room_res['MealType']['@attributes']['code'] ?? '';
                                        $option_room['rooms_total_price']       = $room_res['Price']['@attributes']['amt'];
                                        $option_room['room_code']               = $room_res['MealType']['@attributes']['code'] ?? '';
                                        $option_room['CancellationPolicyStatus']= $room_res['CancellationPolicyStatus'] ?? '';
                                        $adults                                 = $request->Adults[$i] ?? '0';
                                        $option_room['adults']                  = $adults;
                                        $children                               = $request->children[$i] ?? '0';
                                        $option_room['childs']                  = $children;
                                        $option_room['rooms_selling_price']     = '';
                                        $option_room['rooms_qty']               = $request->room;
                                        $option_room['cancliation_policy_arr']  = array((object)
                                        [
                                            'amount'                            => '',
                                            'from_date'                         => ''
                                        ]);
                                        $option_room['rooms_list']              = $rooms;
                                        $i++;
                                    }
                                    array_push($option_rooms, $option_room);
                                }
                            }
                        }
                    }else{
                        if (isset($res['Result'])) {
                            foreach ($res['Result']  as $rooms) {
                                $option_room            = [
                                    'booking_req_id'    => $rooms['@attributes']['id'] ?? '',
                                    'allotment'         => '2',
                                ];
                                
                                if (count($request->rooms_counter) == 1 && isset($rooms['Room']['Price']['@attributes']['amt'])) {
                                    
                                    $price                                  = $rooms['Room']['Price']['@attributes']['amt'];
                                    $roomPrices[]                           = $price;
                                    $maxPrice                               = max($roomPrices);
                                    $minPrice                               = min($roomPrices);
                                    $hotels_res['min_price']                = $minPrice;
                                    $hotels_res['max_price']                = $maxPrice;
                                    $option_room['room_name']               = $rooms['Room']['RoomType']['@attributes']['text'];
                                    $option_room['room_code']               = $rooms['Room']['RoomType']['@attributes']['code'];
                                    $option_room['request_type']            = '';
                                    $option_room['board_id']                = $rooms['Room']['MealType']['@attributes']['text'] ?? '';
                                    $option_room['board_code']              = $rooms['Room']['MealType']['@attributes']['code'] ?? '';
                                    $option_room['rooms_total_price']       = $rooms['Room']['Price']['@attributes']['amt'];
                                    $option_room['CancellationPolicyStatus']= $rooms['Room']['CancellationPolicyStatus'] ?? '';
                                    $option_room['adults']                  = $request->adult;
                                    $option_room['childs']                  = $request->child;
                                    $option_room['rooms_selling_price']     = '';
                                    $option_room['rooms_qty']               = 1;
                                    $option_room['cancliation_policy_arr']  = array((object)
                                    [
                                        'amount'                            => '',
                                        'from_date'                         => ''
                                    ]);
                                    $option_room['rooms_list']              = $rooms;
                                }
                                
                                if (count($request->rooms_counter) > 1 || count($request->rooms_counter) == 1) {
                                    $i = 0;
                                    if (isset($rooms['Room'])) {
                                        foreach ($rooms['Room'] as $room_res) {
                                            if (isset($room_res['Price']['@attributes']['amt'])) {
                                                $price                                  = $room_res['Price']['@attributes']['amt'];
                                                $roomPrices[]                           = $price;
                                                $maxPrice                               = max($roomPrices);
                                                $minPrice                               = min($roomPrices);
                                                $hotels_res['min_price']                = $minPrice;
                                                $hotels_res['max_price']                = $maxPrice;
                                                $option_room['room_name']               = $room_res['RoomType']['@attributes']['text'];
                                                $option_room['room_code']               = $room_res['RoomType']['@attributes']['code'];
                                                $option_room['request_type']            = '';
                                                $option_room['board_id']                = $room_res['MealType']['@attributes']['text'] ?? '';
                                                $option_room['board_code']              = $room_res['MealType']['@attributes']['code'] ?? '';
                                                $option_room['rooms_total_price']       = $room_res['Price']['@attributes']['amt'];
                                                $option_room['room_code']               = $room_res['MealType']['@attributes']['code'] ?? '';
                                                $option_room['CancellationPolicyStatus']= $room_res['CancellationPolicyStatus'] ?? '';
                                                $adults                                 = $request->Adults[$i] ?? '0';
                                                $option_room['adults']                  = $adults;
                                                $children                               = $request->children[$i] ?? '0';
                                                $option_room['childs']                  = $children;
                                                $option_room['rooms_selling_price']     = '';
                                                $option_room['rooms_qty']               = $request->room;
                                                $option_room['cancliation_policy_arr']  = array((object)
                                                [
                                                    'amount'                            => '',
                                                    'from_date'                         => ''
                                                ]);
                                                $option_room['rooms_list']              = $rooms;
                                                $i++;
                                            }
                                            array_push($option_rooms, $option_room);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if (count($roomsData) > 1) {
                        foreach ($option_rooms as $room) {
                            $key = $room['booking_req_id'] . '_' . $room['adults'] . '_' . $room['childs'];
                            if (!isset($aggregatedRooms[$key])) {
                                $aggregatedRooms[$key]          = [
                                    'booking_req_id'            => $room['booking_req_id'],
                                    'allotment'                 => $room['allotment'],
                                    'room_name'                 => $room['room_name'],
                                    'room_code'                 => $room['room_code'],
                                    'request_type'              => $room['request_type'],
                                    'board_id'                  => $room['board_id'],
                                    'board_code'                => $room['board_code'],
                                    'rooms_total_price'         => $room['rooms_total_price'],
                                    'rooms_selling_price'       => $room['rooms_selling_price'],
                                    'rooms_qty'                 => 1,
                                    'adults'                    => $room['adults'],
                                    'childs'                    => $room['childs'],
                                    'cancliation_policy_arr'    => $room['cancliation_policy_arr'],
                                    'rooms_list'                => $room['rooms_list']
                                ];
                            } else {
                                $aggregatedRooms[$key]['rooms_qty']++;
                                $aggregatedRooms[$key]['rooms_total_price'] += $room['rooms_total_price'];
                            }
                        }
                        $rooms =  array_values($aggregatedRooms);
                    }
                    else{
                        $rooms = $option_rooms;
                    }
                    
                    $unique_booking_req_ids     = [];
                    $filtered_rooms             = [];
                    foreach ($rooms as $room) {
                        if (!isset($unique_booking_req_ids[$room['booking_req_id']]) || $unique_booking_req_ids[$room['booking_req_id']] !== $room['adults']) {
                            $unique_booking_req_ids[$room['booking_req_id']]    = $room['adults'];
                            $filtered_rooms[]                                   = $room;
                        }
                    }
                    $hotels_res['rooms_options'] = $filtered_rooms;
                    array_push($hotels, $hotels_res);
                    $stuba_hotels_items         = $hotels;
                    
                }
            }
        }
        
        return $result_Data = ['stuba_hotels_items'=>$stuba_hotels_items,'stuba_hotel_count'=>$stuba_hotels_count];
    }
    
    public static function stuba_Hotel_Codes(Request $request){
        $hotelscodes    = [];
        $hotels         = DB::table('stuba_cityids')->whereRaw('LOWER(Name) = ?', [strtolower($request->city)])->first();
        // return $hotels;
        if (isset($hotels)) {
            $id             = $hotels->cityid;
            $hoteldetails   = DB::table('stuba_hotel_details')->get();
            $hotelsresults  = [];
            foreach ($hoteldetails as $details) {
                $region = json_decode($details->hotelRegion);
                $cityid = $region->CityId;
                if ($cityid == $id) {
                    $hotelsresults[] = $details;
                }
            }
            // $hotelsresults = $hoteldetails;
            foreach ($hotelsresults as $hotel) {
                // if($hotel->hotelid == '53998'){
                //     return $hotel;
                // }
                $hotelscodes[] = $hotel->hotelid;
            }
        }
        
        return $hotelscodes;
    }
    
    public static function stuba_Booking_Prepare($booking_Id){
        $xmlPayload =   '<BookingCreate>
                            <Authority xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                                <Org>'.env('STUBA_HOTEL_ORG').'</Org>
                                <User>'.env('STUBA_HOTEL_USERNAME').'</User>
                                <Password>'.env('STUBA_HOTEL_PASSWORD').'</Password>
                                <Currency>GBP</Currency>
                                <Language>en</Language>
                                <TestDebug>false</TestDebug>
                                <Version>1.28</Version>
                            </Authority>
                            <QuoteId>'.$booking_Id.'</QuoteId>
                            <HotelStayDetails>
                                <Nationality>PK</Nationality>
                                <Room>
                                    <Guests>
                                        <Adult title="MR" first="Usama1" last="Ali1"/>
                                        <Adult title="MR" first="Usama2" last="Ali2"/>
                                    </Guests>
                                </Room>
                            </HotelStayDetails>
                            <CommitLevel>prepare</CommitLevel>
                        </BookingCreate>';
        // return $xmlPayload;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.stuba.com/RXLServices/ASMX/XmlService.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xmlPayload, // Pass the dynamically generated XML payload
            CURLOPT_HTTPHEADER => array(
                'op: AvailabilitySearch',
                'Content-Type: text/xml; charset=utf-8'
            ),
        ));
        $response       = curl_exec($curl);
        curl_close($curl);
        $xml            = simplexml_load_string($response);
        $json           = json_encode($xml);
        $responseData3  = json_decode($json, true);
        return $responseData3;
    }
    
    public static function stuba_Cancel($booking_Id){
        $xmlPayload =   '<BookingCancel>
                            <Authority xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                                <Org>'.env('STUBA_HOTEL_ORG').'</Org>
                                <User>'.env('STUBA_HOTEL_USERNAME').'</User>
                                <Password>'.env('STUBA_HOTEL_PASSWORD').'</Password>
                                <Currency>GBP</Currency>
                                <Language>en</Language>
                                <TestDebug>false</TestDebug>
                                <Version>1.28</Version>
                            </Authority>
                            <BookingId>'.$booking_Id.'</BookingId>
                            <CommitLevel>confirm</CommitLevel>
                        </BookingCancel>';
        // return $xmlPayload;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.stuba.com/RXLServices/ASMX/XmlService.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xmlPayload, // Pass the dynamically generated XML payload
            CURLOPT_HTTPHEADER => array(
                'op: AvailabilitySearch',
                'Content-Type: text/xml; charset=utf-8'
            ),
        ));
        $response       = curl_exec($curl);
        curl_close($curl);
        $xml            = simplexml_load_string($response);
        $json           = json_encode($xml);
        $responseData3  = json_decode($json, true);
        return $responseData3;
    }
    
    public static function test_Cases_Hotel_Codes($city){
        $hotelscodes        = [];
        $hotels             = DB::table('stuba_cityids')->whereRaw('LOWER(Name) = ?', [strtolower($city)])->first();
        // if (isset($hotels)) {
        //     $id             = $hotels->cityid;
        //     $hoteldetails   = DB::table('stuba_hotel_details')->get();
        //     $hotelsresults  = [];
        //     foreach ($hoteldetails as $details) {
        //         $region     = json_decode($details->hotelRegion);
        //         $cityid     = $region[0]->CityID;
        //         if ($cityid == $id) {
        //             $hotelsresults[] = $details;
        //         }
        //     }
        //     foreach ($hotelsresults as $hotel) {
        //         $hotelscodes[] = $hotel->hotelid;
        //     }
        // }
        
        if (isset($hotels)) {
            $id                         = $hotels->cityid;
            $hotelsresults              = [];
            $hoteldetails               = DB::table('stuba_hotel_details')->WhereJsonContains('hotelRegion', [['CityID' => (string)$id]])->get();
            // return $hoteldetails;
            
            foreach ($hoteldetails as $details) {
                $region                 = json_decode($details->hotelRegion);
                $cityid                 = $region[0]->CityID ?? '';
                if ($cityid == (string)$id) {
                    $hotelsresults[]    = $details;
                }
            }
            
            foreach ($hotelsresults as $hotel) {
                $hotelscodes[]          = $hotel->hotelid;
            }
        }
        
        return $hotelscodes;
    }
    
    public static function test_Cases_Search(Request $request){
        // return $request;
        
        $xmlPayload =   '<AvailabilitySearch xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                            <Authority xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                                <Org>'.env('STUBA_HOTEL_ORG').'</Org>
                                <User>'.env('STUBA_HOTEL_USERNAME').'</User>
                                <Password>'.env('STUBA_HOTEL_PASSWORD').'</Password>
                                <Currency>GBP</Currency>
                                <Language>en</Language>
                                <TestDebug>false</TestDebug>
                                <Version>1.28</Version>
                            </Authority>
                            <Hotels>';
                            
        $hotelscodes_All    = Stuba_Controller::test_Cases_Hotel_Codes($request->city);
        $hotelscodes        = array_slice($hotelscodes_All, 0, 500);
        // return $hotelscodes;
        
        if (isset($hotelscodes)) {
            foreach ($hotelscodes as $hotelscode) {
                $xmlPayload .= '<Id>' . $hotelscode . '</Id>';
                // $xmlPayload .= '<Id>' . $request->hotel_Id . '</Id>';
            }
        }
        
        $xmlPayload .=      '</Hotels>
                            <HotelStayDetails>
                                <ArrivalDate>'.$request->ArrivalDate.'</ArrivalDate>
                                <Nights>'.$request->nights.'</Nights>
                                <Nationality>PK</Nationality>
                                <Room>
                                    <Guests>
                                        <Adult/>
                                        <Adult/>
                                    </Guests>
                                </Room>
                            </HotelStayDetails>
                            <HotelSearchCriteria>
                                <AvailabilityStatus>allocation</AvailabilityStatus>
                                <DetailLevel>basic</DetailLevel>
                            </HotelSearchCriteria>
                        </AvailabilitySearch>';
        return $xmlPayload;
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'http://api.stuba.com/RXLServices/ASMX/XmlService.asmx',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $xmlPayload,
                CURLOPT_HTTPHEADER => array(
                    'op: AvailabilitySearch',
                    'Content-Type: text/xml; charset=utf-8'
                ),
            )
        );
        $response = curl_exec($curl);
        // return $response;die;
        curl_close($curl);
        $xml            = simplexml_load_string($response);
        $json           = json_encode($xml);
        $responseData3  = json_decode($json, true);
        return $responseData3;
    }
    
    public static function test_Cases_Hotel_Details(Request $request){
        $xmlPayload =   '<AvailabilitySearch xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                            <Authority xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                                <Org>'.env('STUBA_HOTEL_ORG').'</Org>
                                <User>'.env('STUBA_HOTEL_USERNAME').'</User>
                                <Password>'.env('STUBA_HOTEL_PASSWORD').'</Password>
                                <Currency>GBP</Currency>
                                <Language>en</Language>
                                <TestDebug>false</TestDebug>
                                <Version>1.28</Version>
                            </Authority>
                            <HotelId>55492</HotelId>
                            <HotelStayDetails>
                                <ArrivalDate>2024-07-25</ArrivalDate>
                                <Nights>5</Nights>
                                <Room>
                                    <Guests>
                                        <Adult/>
                                    </Guests>
                                    <Guests>
                                        <Adult/>
                                        <Adult/>
                                    </Guests>
                                </Room>
                            </HotelStayDetails>
                            <DetailLevel>custom</DetailLevel>
                            <CustomDetailLevel>basic</CustomDetailLevel>
                        </AvailabilitySearch>';
        return $xmlPayload;
        
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'http://api.stuba.com/RXLServices/ASMX/XmlService.asmx',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $xmlPayload,
                CURLOPT_HTTPHEADER => array(
                    'op: AvailabilitySearch',
                    'Content-Type: text/xml; charset=utf-8'
                ),
            )
        );
        $response = curl_exec($curl);
        // return $response;
        curl_close($curl);
        $xml            = simplexml_load_string($response);
        $json           = json_encode($xml);
        $responseData3  = json_decode($json, true);
        return $responseData3;
    }

    public static function test_Cases_Booking_Prepare(Request $request){
        $xmlPayload =   '<BookingCreate>
                            <Authority xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                                <Org>'.env('STUBA_HOTEL_ORG').'</Org>
                                <User>'.env('STUBA_HOTEL_USERNAME').'</User>
                                <Password>'.env('STUBA_HOTEL_PASSWORD').'</Password>
                                <Currency>GBP</Currency>
                                <Language>en</Language>
                                <TestDebug>false</TestDebug>
                                <Version>1.28</Version>
                            </Authority>
                            <QuoteId>'.$request->booking_Id.'</QuoteId>
                            <HotelStayDetails>
                                <Nationality>PK</Nationality>
                                <Room>
                                    <Guests>
                                        <Adult title="MR" first="Usama1" last="Ali1"/>
                                        <Adult title="MR" first="Usama2" last="Ali2"/>
                                    </Guests>
                                </Room>
                            </HotelStayDetails>
                            <CommitLevel>prepare</CommitLevel>
                        </BookingCreate>';
        return $xmlPayload;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.stuba.com/RXLServices/ASMX/XmlService.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xmlPayload, // Pass the dynamically generated XML payload
            CURLOPT_HTTPHEADER => array(
                'op: AvailabilitySearch',
                'Content-Type: text/xml; charset=utf-8'
            ),
        ));
        $response       = curl_exec($curl);
        curl_close($curl);
        $xml            = simplexml_load_string($response);
        $json           = json_encode($xml);
        $responseData3  = json_decode($json, true);
        return $responseData3;
    }
    
    public static function test_Cases_Booking(Request $request){
        $xmlPayload =   '<BookingCreate>
                            <Authority xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                                <Org>'.env('STUBA_HOTEL_ORG').'</Org>
                                <User>'.env('STUBA_HOTEL_USERNAME').'</User>
                                <Password>'.env('STUBA_HOTEL_PASSWORD').'</Password>
                                <Currency>GBP</Currency>
                                <Language>en</Language>
                                <TestDebug>false</TestDebug>
                                <Version>1.28</Version>
                            </Authority>
                            <QuoteId>'.$request->booking_Id.'</QuoteId>
                            <HotelStayDetails>
                                <Nationality>PK</Nationality>
                                <Room>
                                    <Guests>
                                        <Adult title="MR" first="Usama1" last="Ali1"/>
                                        <Adult title="MR" first="Usama2" last="Ali2"/>
                                    </Guests>
                                </Room>
                            </HotelStayDetails>
                            <CommitLevel>confirm</CommitLevel>
                        </BookingCreate>';
        return $xmlPayload;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.stuba.com/RXLServices/ASMX/XmlService.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xmlPayload, // Pass the dynamically generated XML payload
            CURLOPT_HTTPHEADER => array(
                'op: AvailabilitySearch',
                'Content-Type: text/xml; charset=utf-8'
            ),
        ));
        $response       = curl_exec($curl);
        curl_close($curl);
        $xml            = simplexml_load_string($response);
        $json           = json_encode($xml);
        $responseData3  = json_decode($json, true);
        return $responseData3;
    }
    
    public static function test_Cases_Cancellation(Request $request){
        $xmlPayload =   '<BookingCancel>
                            <Authority xmlns="http://www.reservwire.com/namespace/WebServices/Xml">
                                <Org>'.env('STUBA_HOTEL_ORG').'</Org>
                                <User>'.env('STUBA_HOTEL_USERNAME').'</User>
                                <Password>'.env('STUBA_HOTEL_PASSWORD').'</Password>
                                <Currency>GBP</Currency>
                                <Language>en</Language>
                                <TestDebug>false</TestDebug>
                                <Version>1.28</Version>
                            </Authority>
                            <BookingId>'.$request->booking_Id.'</BookingId>
                            <CommitLevel>confirm</CommitLevel>
                        </BookingCancel>';
        return $xmlPayload;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.stuba.com/RXLServices/ASMX/XmlService.asmx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xmlPayload, // Pass the dynamically generated XML payload
            CURLOPT_HTTPHEADER => array(
                'op: AvailabilitySearch',
                'Content-Type: text/xml; charset=utf-8'
            ),
        ));
        $response       = curl_exec($curl);
        curl_close($curl);
        $xml            = simplexml_load_string($response);
        $json           = json_encode($xml);
        $responseData3  = json_decode($json, true);
        return $responseData3;
    }
}