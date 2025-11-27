<?php

namespace App\Http\Controllers\Travelenda;

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

class Travelenda_Controller extends Controller
{
    // public static function travelenda_Hotel_Policies($OptionId)
    public static function travelenda_Hotel_Policies(Request $request)
    {
        // return $request->OptionId;
        $OptionId = $request->OptionId;
        
        $reqdata =  "<Request>
                        <Head>
                            <Username>1124fb7683cf22910ea6e3c97473bb9c</Username>
                            <Password>XjzqSyyOL0EV</Password>
                            <RequestType>HotelPolicies</RequestType>
                        </Head>
                        <Body>
                            <OptionId>".$OptionId."</OptionId>
                        </Body>
                    </Request>";
        // return $reqdata;
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
        // return $response;
        $xml                    = simplexml_load_string($response);
        $json                   = json_encode($xml);
        // return $json;
        $cancliation_policy_arr = [];
        $result_HotelPolicies   = json_decode($json);
        if(isset($result_HotelPolicies->Body->OptionId)){
            if(isset($result_HotelPolicies->Body->Policies->Policy)){
                foreach($result_HotelPolicies->Body->Policies->Policy as $cancel_res){
                    $cancel_tiem                 = (Object)[
                        'amount'                => $cancel_res->Value,
                        'type'                  => $cancel_res->Type,
                        'from_date'             => $cancel_res->From,
                    ];
                    $cancliation_policy_arr[]    = $cancel_tiem;
                }
            }
        }
        return $cancliation_policy_arr;
    }
}