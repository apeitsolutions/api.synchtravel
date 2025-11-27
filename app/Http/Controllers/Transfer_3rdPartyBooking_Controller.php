<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use Session;
use DB;
use Validator;
use Stripe;

class Transfer_3rdPartyBooking_Controller extends Controller
{
    public static function search_Transfer_Api_static(){
        $data_request = '<TCOML version="NEWFORMAT">
                            <TransferOnly>
                                <P2PAvailability>
                                    <Request>
                                        <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                        <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                        <Lang>EN</Lang>
                                        <LatitudeP1>39.5517448</LatitudeP1>
                                        <LongitudeP1>2.7339762</LongitudeP1>
                                        <LatitudeP2>39.485644</LatitudeP2>
                                        <LongitudeP2>2.905978</LongitudeP2>
                                        <PlaceFrom>Mallorca Airport(PMI)</PlaceFrom>
                                        <PlaceTo>Finca Can Titos, Lluchmajor</PlaceTo>
                                        <CountryCodeFrom>ES</CountryCodeFrom>
                                        <CountryCodeTo>ES</CountryCodeTo>
                                        <IsReturn>1</IsReturn>
                                        <ArrDate>15/02/2024</ArrDate>
                                        <ArrTime>1000</ArrTime>
                                        <RetDate>18/02/2024</RetDate>
                                        <RetTime>1000</RetTime>
                                        <Adults>1</Adults>
                                        <Children>0</Children>
                                        <Infants>0</Infants>
                                        <ResponseType>0</ResponseType>
                                        <CalcJ1PickupTime>1</CalcJ1PickupTime>
                                        <CalcJ2PickupTime>1</CalcJ2PickupTime>
                                        <GoogleAPIKey></GoogleAPIKey>
                                    </Request>
                                </P2PAvailability>
                            </TransferOnly>
                        </TCOML>';
        
        $data_request1 = '<TCOML version="NEWFORMAT">
                            <TransferOnly>
                                <P2PAvailability>
                                    <Request>
                                        <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                        <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                        <Lang>EN</Lang>
                                        <LatitudeP1>41.2974433898926</LatitudeP1>
                                        <LongitudeP1>2.0832941532135</LongitudeP1>
                                        <LatitudeP2>41.3770366</LatitudeP2>
                                        <LongitudeP2>2.1888715</LongitudeP2>
                                        <PlaceFrom>Barcelona El Prat Airport (BCN)</PlaceFrom>
                                        <PlaceTo>Hotel 54 Barceloneta, Passeig de Joan de Borbo, Barcelona, Spain</PlaceTo>
                                        <IsReturn>1</IsReturn>
                                        <ArrDate>2024-06-12</ArrDate>
                                        <ArrTime>1200</ArrTime>
                                        <RetDate>2024-06-19</RetDate>
                                        <RetTime>1200</RetTime>
                                        <Adults>1</Adults>
                                        <Children>0</Children>
                                        <Infants>0</Infants>
                                        <Deposit>1</Deposit>
                                        <CountryCodeFrom>ES</CountryCodeFrom>
                                        <CountryCodeTo>ES</CountryCodeTo>
                                        <ProductType>0</ProductType>
                                        <ResponseType>0</ResponseType>
                                        <ChannelFlag>1</ChannelFlag>
                                        <CalcJ1PickupTime>1</CalcJ1PickupTime>
                                        <CalcJ2PickupTime>1</CalcJ2PickupTime>
                                    </Request>
                                </P2PAvailability>
                            </TransferOnly>
                        </TCOML>';
        
        $data_request2 = '<TCOML version="NEWFORMAT">
                            <TransferOnly>
                                <P2PAvailability>
                                    <Request>
                                        <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                        <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                        <Lang>EN</Lang>
                                        <IATAj1>AGP</IATAj1>
                                        <LatitudeP2>36.720377</LatitudeP2>
                                        <LongitudeP2>-4.4251799</LongitudeP2>
                                        <PlaceFrom>AGP</PlaceFrom>
                                        <PlaceTo>Hotel Vincci Selecci贸n Posada del Patio, Pasillo de Sta. Isabel, 7, Distrito Centro, 29005 M谩laga, Spain</PlaceTo>
                                        <IsReturn>1</IsReturn>
                                        <ArrDate>2024-06-12</ArrDate>
                                        <ArrTime>1200</ArrTime>
                                        <RetDate>2024-06-19</RetDate>
                                        <RetTime>1200</RetTime>
                                        <Adults>1</Adults>
                                        <Children>0</Children>
                                        <Infants>0</Infants>
                                        <Deposit>1</Deposit>
                                        <CountryCodeFrom>ES</CountryCodeFrom>
                                        <CountryCodeTo>ES</CountryCodeTo>
                                        <ProductType>0</ProductType>
                                        <ResponseType>0</ResponseType>
                                        <ChannelFlag>1</ChannelFlag>
                                        <CalcJ1PickupTime>1</CalcJ1PickupTime>
                                        <CalcJ2PickupTime>1</CalcJ2PickupTime>
                                    </Request>
                                </P2PAvailability>
                            </TransferOnly>
                        </TCOML>';
                        
        $data_request4 = '<TCOML version="NEWFORMAT">
                            <TransferOnly>
                                <P2PAvailability>
                                    <Request>
                                        <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                        <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                        <Lang>EN</Lang>
                                        <LatitudeP1>39.5517448</LatitudeP1>
                                        <LongitudeP1>2.7339762</LongitudeP1>
                                        <LatitudeP2>39.485644</LatitudeP2>
                                        <LongitudeP2>2.905978</LongitudeP2>
                                        <PlaceFrom>Mallorca Airport(PMI)</PlaceFrom>
                                        <PlaceTo>Finca Can Titos, Lluchmajor</PlaceTo>
                                        <CountryCodeFrom>ES</CountryCodeFrom>
                                        <CountryCodeTo>ES</CountryCodeTo>
                                        <IsReturn>1</IsReturn>
                                        <ArrDate>15/05/2024</ArrDate>
                                        <ArrTime>1000</ArrTime>
                                        <RetDate>18/05/2024</RetDate>
                                        <RetTime>1000</RetTime>
                                        <Adults>1</Adults>
                                        <Children>0</Children>
                                        <Infants>0</Infants>
                                        <ResponseType>0</ResponseType>
                                        <CalcJ1PickupTime>1</CalcJ1PickupTime>
                                        <CalcJ2PickupTime>1</CalcJ2PickupTime>
                                        <GoogleAPIKey></GoogleAPIKey>
                                    </Request>
                                </P2PAvailability>
                            </TransferOnly>
                        </TCOML>';
                        
        // return $data_request4;
        
        $url        = "http://xmlp2p.com/xml/";
        $timeout    = 7;
        $data       = array('xml' => $data_request4);
        $headers    = array("Content-type: application/x-www-form-urlencoded");
        $ch = curl_init();
        $payload = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        // return $response;
        curl_close($ch);
        
        $xml    = simplexml_load_string($response);
        $json   = json_encode($xml, JSON_PRETTY_PRINT);
        $json   = stripslashes($json);
        $json   = json_decode($json);
        
        return $json;
        
        try {
            $sessionID = $json->TransferOnly->P2PResults->SessionID;
        }catch (\Throwable $th) {
            return 'Error Catch';
        }
        
        $details    = $json->TransferOnly->P2PResults->Request;
        $trans      = $json->TransferOnly->P2PResults->Response->Transfers->Transfer;
        $tranfer_Ar = [
                        'trans'     => $trans,
                        'details'   => $details,
                        ];
        return $tranfer_Ar;
        
        // return view('trasnfers', compact('trans', 'sessionID'));
    }
    
    public static function search_Transfer_Api($req){
        // dd($req);
        
        //Start Latitude and longitude
        $startlatlng    = $req->startName;
        $startlatlng    = explode(',', $startlatlng);
        $startlat       = $startlatlng[0];
        if(isset($startlatlng[1])){
            $startlng = $startlatlng[1];
        }else{
            $startlng = $startlat;
        }
        
        //destination Latitude and longitude
        $destinationlatlng  = $req->destinationName;
        $destinationlatlng  = explode(',', $destinationlatlng);
        $destinationlat     = $destinationlatlng[0];
        if(isset($destinationlatlng[1])){
            $destinationlng = $destinationlatlng[1];
        }else{
            $destinationlng = $destinationlat;
        }
        
        //Trip type Round(1) or one way(0)
        if ($req->trip_type == 'One-Way') {
            $tyriptype =  0;
        }else{
            $tyriptype =  1;
        }
        
        //star place name
        $startplacename         = $req->startplacename;
        $destinationplacename   = $req->destinationplacename;
        // $destinationplacename   = 'Madina Saudi Arabia';
        // return $destinationplacename;
        // dd($req,$startplacename,$destinationplacename);
        
        $countryList            = country_Codes();
        
        $countryname            =  $req->startplacecountrycode;
        $countrycodestart       = get_Country_Codes($countryname, $countryList);
        
        $countryname            = $req->destinationplacenamecountrycode;
        $countrycodedestination = get_Country_Codes($countryname, $countryList);
        
        //Arival date and time
        $arrdate    =  $req->pick_up_date;
        $timestamp  = strtotime($arrdate);
        $arrdate    = date('d/m/Y', $timestamp);
        
        $arrtime    = $req->arrtime;
        $timestamp  = strtotime($arrtime);
        $arrtime    = date("Hi", $timestamp);
        
        $retdate    = $req->retdate;
        $timestamp  = strtotime($retdate);
        $retdate    = date('d/m/Y', $timestamp);
        
        $rettime    = $req->rettime;
        $timestamp  = strtotime($rettime);
        $rettime    = date("Hi", $timestamp);
        
        $childerns  = $req->childerns ?? '0';
        $infants    = $req->infants ?? '0';
        $adults     = $req->passenger ?? '';
        
        if($tyriptype == 0){
            $data_request = '<TCOML version="NEWFORMAT">
                                <TransferOnly>
                                    <P2PAvailability>
                                        <Request>
                                            <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                            <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                            <Lang>EN</Lang>
                                            <LatitudeP1>' . $startlat . '</LatitudeP1>
                                            <LongitudeP1>' . $startlng . '</LongitudeP1>
                                            <LatitudeP2>' . $destinationlat . '</LatitudeP2>
                                            <LongitudeP2>' . $destinationlng . '</LongitudeP2>
                                            <PlaceFrom>' . $startplacename . '</PlaceFrom>
                                            <PlaceTo>' . $destinationplacename . '</PlaceTo>
                                            <CountryCodeFrom>' . $countrycodestart . '</CountryCodeFrom>
                                            <CountryCodeTo>' .  $countrycodedestination . '</CountryCodeTo>
                                            <IsReturn>' . $tyriptype . '</IsReturn>
                                            <ArrDate>' . $arrdate . '</ArrDate>
                                            <ArrTime>' .  $arrtime . '</ArrTime>
                                            <Adults>' .   $adults . '</Adults>
                                            <Children>' . $childerns . '</Children>
                                            <Infants>' . $infants . '</Infants>
                                            <Deposit>1</Deposit>
                                            <ProductType>0</ProductType>
                                            <ChannelFlag>1</ChannelFlag>
                                            <ResponseType>0</ResponseType>
                                            <CalcJ1PickupTime>1</CalcJ1PickupTime>
                                            <CalcJ2PickupTime>1</CalcJ2PickupTime>
                                        </Request>
                                    </P2PAvailability>
                                </TransferOnly>
                            </TCOML>';
        }
        
        if($tyriptype == 1){
            $data_request = '<TCOML version="NEWFORMAT">
                                <TransferOnly>
                                    <P2PAvailability>
                                        <Request>
                                            <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                            <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                            <Lang>EN</Lang>
                                            <LatitudeP1>' . $startlat . '</LatitudeP1>
                                            <LongitudeP1>' . $startlng . '</LongitudeP1>
                                            <LatitudeP2>' . $destinationlat . '</LatitudeP2>
                                            <LongitudeP2>' . $destinationlng . '</LongitudeP2>
                                            <PlaceFrom>' . $startplacename . '</PlaceFrom>
                                            <PlaceTo>' . $destinationplacename . '</PlaceTo>
                                            <IsReturn>' . $tyriptype . '</IsReturn>
                                            <ArrDate>' . $arrdate . '</ArrDate>
                                            <ArrTime>' .  $arrtime . '</ArrTime>
                                            <RetDate>' . $retdate . '</RetDate>
                                            <RetTime>' . $rettime . '</RetTime>
                                            <Adults>' .   $adults . '</Adults>
                                            <Children>' . $childerns . '</Children>
                                            <Infants>' . $infants . '</Infants>
                                            <Deposit>1</Deposit>
                                            <CountryCodeFrom>' . $countrycodestart . '</CountryCodeFrom>
                                            <CountryCodeTo>' .  $countrycodedestination . '</CountryCodeTo>
                                            <ProductType>0</ProductType>
                                            <ChannelFlag>1</ChannelFlag>
                                            <ResponseType>0</ResponseType>
                                            <CalcJ1PickupTime>1</CalcJ1PickupTime>
                                            <CalcJ2PickupTime>1</CalcJ2PickupTime>
                                        </Request>
                                    </P2PAvailability>
                                </TransferOnly>
                            </TCOML>';
        }
        
        // Disclaimer
        $data_request1 = '<TCOML version="NEWFORMAT">
                            <TransferOnly>
                                <P2PAvailability>
                                    <Request>
                                        <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                        <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                        <Lang>EN</Lang>
                                        <IATAj1>AGP</IATAj1>
                                        <LatitudeP2>36.720377</LatitudeP2>
                                        <LongitudeP2>-4.4251799</LongitudeP2>
                                        <PlaceFrom>AGP</PlaceFrom>
                                        <PlaceTo>Hotel Vincci Selecci贸n Posada del Patio, Pasillo de Sta. Isabel, 7, Distrito Centro, 29005 M谩laga, Spain</PlaceTo>
                                        <IsReturn>1</IsReturn>
                                        <ArrDate>2024-06-12</ArrDate>
                                        <ArrTime>1200</ArrTime>
                                        <RetDate>2024-06-19</RetDate>
                                        <RetTime>1200</RetTime>
                                        <Adults>1</Adults>
                                        <Children>0</Children>
                                        <Infants>0</Infants>
                                        <Deposit>1</Deposit>
                                        <CountryCodeFrom>ES</CountryCodeFrom>
                                        <CountryCodeTo>ES</CountryCodeTo>
                                        <ProductType>0</ProductType>
                                        <ResponseType>0</ResponseType>
                                        <ChannelFlag>1</ChannelFlag>
                                        <CalcJ1PickupTime>1</CalcJ1PickupTime>
                                        <CalcJ2PickupTime>1</CalcJ2PickupTime>
                                    </Request>
                                </P2PAvailability>
                            </TransferOnly>
                        </TCOML>';
        // Extras
        $data_request2 = '<TCOML version="NEWFORMAT">
                            <TransferOnly>
                                <P2PAvailability>
                                    <Request>
                                        <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                        <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                        <Lang>EN</Lang>
                                        <LatitudeP1>39.5517448</LatitudeP1>
                                        <LongitudeP1>2.7339762</LongitudeP1>
                                        <LatitudeP2>39.485644</LatitudeP2>
                                        <LongitudeP2>2.905978</LongitudeP2>
                                        <PlaceFrom>Mallorca Airport(PMI)</PlaceFrom>
                                        <PlaceTo>Finca Can Titos, Lluchmajor</PlaceTo>
                                        <CountryCodeFrom>ES</CountryCodeFrom>
                                        <CountryCodeTo>ES</CountryCodeTo>
                                        <IsReturn>1</IsReturn>
                                        <ArrDate>15/05/2025</ArrDate>
                                        <ArrTime>1000</ArrTime>
                                        <RetDate>18/05/2025</RetDate>
                                        <RetTime>1000</RetTime>
                                        <Adults>1</Adults>
                                        <Children>0</Children>
                                        <Infants>0</Infants>
                                        <ResponseType>0</ResponseType>
                                        <CalcJ1PickupTime>1</CalcJ1PickupTime>
                                        <CalcJ2PickupTime>1</CalcJ2PickupTime>
                                        <GoogleAPIKey></GoogleAPIKey>
                                    </Request>
                                </P2PAvailability>
                            </TransferOnly>
                        </TCOML>';
                        // return $req;
        // return $data_request;
        
        $url        = "http://xmlp2p.com/xml/";
        $timeout    = 7;
        $data       = array('xml' => $data_request);
        $headers    = array("Content-type: application/x-www-form-urlencoded");
        $ch         = curl_init();
        $payload    = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response   = curl_exec($ch);
        // return $response;
        curl_close($ch);
        
        if(!empty($response)){
            $xml    = simplexml_load_string($response);
            $json   = json_encode($xml, JSON_PRETTY_PRINT);
            $json   = stripslashes($json);
            // echo $json;die;
            $json_decode   = json_decode($json);
            // return $json_decode;
            
            try {
                $sessionID = $json_decode->TransferOnly->P2PResults->SessionID;
            }catch (\Throwable $th) {
                return 'Error Catch';
            }
            
            $details    = $json_decode->TransferOnly->P2PResults->Request;
            $trans      = $json_decode->TransferOnly->P2PResults->Response->Transfers->Transfer;
            $tranfer_Ar = [
                            'sessionID' => $sessionID,
                            'trans'     => $trans,
                            'details'   => $details,
                            ];
            // Session::put('sessionID',$sessionID);
            return $tranfer_Ar;
        }else{
            return 'Something went wrong try again!';
        }
    }
    
    // public function search_Disclaimer_Transfer_Api($bookingid,$sessionID)
    public static function search_Disclaimer_Transfer_Api(Request $req)
    {
        $bookingid      = $req->bookingid;
        $sessionID      = $req->sessionID;
        // $sessionID      = Session::get('sessionID');
        $data_request   =   '<TCOML version="NEWFORMAT">
                                 <TransferOnly>
                                    <Info>
                                        <Disclaimer>
                                            <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                            <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                            <Lang>EN</Lang>
                                            <SessionID>' . $sessionID . '</SessionID>
                                            <BookingID>' . $bookingid . '</BookingID>
                                        </Disclaimer>
                                    </Info>
                                </TransferOnly>
                            </TCOML>';
        // return $data_request;
        $url        = "http://xmlp2p.com/xml/";
        $timeout    = 7;
        $data       = array('xml' => $data_request);
        $headers    = array("Content-type: application/x-www-form-urlencoded");
        $ch         = curl_init();
        $payload    = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response   = curl_exec($ch);
        // return $response;
        curl_close($ch);
        
        $xml                = simplexml_load_string($response);
        $json               = json_encode($xml, JSON_PRETTY_PRINT);
        $json               = stripslashes($json);
        $data               = json_decode($json);
        return $data;
        
        return response()->json(['message'=>'success','data'=>$data]);
    }
    
    public static function search_Extras_Transfer_Api($bookingid,$sessionID)
    // public function search_Extras_Transfer_Api(Request $req)
    {
        // $bookingid      = $req->bookingid;
        // $sessionID      = $req->sessionID;
        $data_request   =   '<TCOML version="NEWFORMAT">
                                <TransferOnly>
                                    <P2PExtras>
                                        <Availability>
                                            <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                            <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                            <SessionID>' . $sessionID . '</SessionID>
                                            <BookingID>' . $bookingid . '</BookingID>
                                        </Availability>   
                                    </P2PExtras>
                                </TransferOnly>
                            </TCOML>';
        // return $data_request;
        $url        = "http://xmlp2p.com/xml/";
        $timeout    = 7;
        $data       = array('xml' => $data_request);
        $headers    = array("Content-type: application/x-www-form-urlencoded");
        $ch         = curl_init();
        $payload    = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response   = curl_exec($ch);
        // return $response;
        curl_close($ch);
        
        $xml                = simplexml_load_string($response);
        $json               = json_encode($xml, JSON_PRETTY_PRINT);
        $json               = stripslashes($json);
        $data               = json_decode($json);
        // return $data;
        
        if(isset($data->TransferOnly->P2PResults->errors->error)){
            return response()->json(['message'=>'error','data'=>$data]); 
        }else{
            return response()->json(['message'=>'success','data'=>$data]);
        }
        
        return response()->json(['message'=>'success','data'=>$data]);
    }
    
    // public function reserve_Extras_Transfer_Api($bookingid,$sessionID)
    public static function reserve_Extras_Transfer_Api(Request $req)
    {
        $bookingid      = $req->bookingid;
        $sessionID      = $req->sessionID;
        $ExtrasID       = $req->ExtrasID;
        $SeatsNo        = $req->MaxNumberOfExtras;
        $ExtrasCode     = $req->ExtrasCode;
        
        $data_request   =  '<TCOML version="NEWFORMAT">
                                <TransferOnly>
                                    <P2PExtras>
                                        <Reserve>
                                            <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                            <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                            <SessionID>' . $sessionID . '</SessionID>
                                            <BookingID>' . $bookingid . '</BookingID>
                                            <ExtrasList>
                                                <Extra ID="'.$ExtrasID.'" NumberOfExtras="'.$SeatsNo.'" ExtrasCode="'.$ExtrasCode.'"/>
                                            </ExtrasList>
                                        </Reserve>
                                    </P2PExtras>
                                </TransferOnly>
                            </TCOML>';
        // return $data_request;
        $url        = "http://xmlp2p.com/xml/";
        $timeout    = 7;
        $data       = array('xml' => $data_request);
        $headers    = array("Content-type: application/x-www-form-urlencoded");
        $ch         = curl_init();
        $payload    = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response   = curl_exec($ch);
        return $data_request .''. $response;
        curl_close($ch);
        
        $xml                = simplexml_load_string($response);
        $json               = json_encode($xml, JSON_PRETTY_PRINT);
        $json               = stripslashes($json);
        $data               = json_decode($json);
        // return $data;
        
        if(isset($data->TransferOnly->P2PResults->errors->error)){
            return response()->json(['message'=>'error','data'=>$data]); 
        }else{
            return response()->json(['message'=>'success','data'=>$data]);
        }
    }
    
    public static function book_Transfer_Api(Request $req){
        $extras_Data        = [];
        $bookingid          = $req->bookingid;
        $sessionID          = $req->sessionID;
        
        $extras_Avline      = json_decode($req->extras_Avline);
        // return $req->extras_Avline;
        
        if(count($extras_Avline) > 0){
            foreach($extras_Avline as $val_Avline){
                $ExtrasID       = $val_Avline->ExtrasID;
                $ExtrasCode     = $val_Avline->ExtrasCode;
                $SeatsNo        = $val_Avline->MaxNumberOfExtras;
                
                $data_request   =  '<TCOML version="NEWFORMAT">
                                        <TransferOnly>
                                            <P2PExtras>
                                                <Reserve>
                                                    <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                                    <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                                    <SessionID>' . $sessionID . '</SessionID>
                                                    <BookingID>' . $bookingid . '</BookingID>
                                                    <ExtrasList>
                                                        <Extra ID="'.$ExtrasID.'" NumberOfExtras="'.$SeatsNo.'" ExtrasCode="'.$ExtrasCode.'"/>
                                                    </ExtrasList>
                                                </Reserve>
                                            </P2PExtras>
                                        </TransferOnly>
                                    </TCOML>';
                // return $data_request;
                $url        = "http://xmlp2p.com/xml/";
                $timeout    = 7;
                $data       = array('xml' => $data_request);
                $headers    = array("Content-type: application/x-www-form-urlencoded");
                $ch         = curl_init();
                $payload    = http_build_query($data);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response   = curl_exec($ch);
                // return $data_request .''. $response;
                curl_close($ch);
                
                $xml                = simplexml_load_string($response);
                $json               = json_encode($xml, JSON_PRETTY_PRINT);
                $json               = stripslashes($json);
                $data               = json_decode($json);
                // return $data;
                
                if(isset($data->TransferOnly->P2PResults->Reserve->Response->ExtrasTransacNo)){
                    $extras_Data_arr = [
                        'ExtrasID'              => $val_Avline->ExtrasID,
                        'ExtrasCode'            => $val_Avline->ExtrasCode,
                        'MaxNumberOfExtras'     => $val_Avline->MaxNumberOfExtras,
                        'Extras_Description'    => $val_Avline->Extras_Description,
                        'ExtrasTransacNo'       => $data->TransferOnly->P2PResults->Reserve->Response->ExtrasTransacNo,
                        'Price'                 => $data->TransferOnly->P2PResults->Reserve->Response->Price
                    ];
                    array_push($extras_Data,$extras_Data_arr);
                }
            }
        }
        
        // return 'stop';
        
        $data_request   =   '<TCOML version="NEWFORMAT">
                                <TransferOnly>
                                    <Booking>
                                        <P2PReserve>
                                            <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                            <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                            <SessionID>' . $sessionID . '</SessionID>
                                            <BookingID>' . $bookingid . '</BookingID>
                                        </P2PReserve>
                                    </Booking>
                                </TransferOnly>
                            </TCOML>';
                            
        // return $data_request;
        
        $url        = "http://xmlp2p.com/xml/";
        $timeout    = 7;
        $data       = array('xml' => $data_request);
        $headers    = array("Content-type: application/x-www-form-urlencoded");
        $ch         = curl_init();
        $payload    = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response   = curl_exec($ch);
        // return $data_request.' '.$response;
        curl_close($ch);
        
        $xml                = simplexml_load_string($response);
        $json               = json_encode($xml, JSON_PRETTY_PRINT);
        $json               = stripslashes($json);
        $data               = json_decode($json);
        
        // return $data;
        
        $transactionNumber  = $data->TransferOnly->P2PResults->Reserve->Response->TransacNo;
        // Session::put('transactionNumber',$transactionNumber);
        return response()->json(['message'=>'Success','transactionNumber'=>$transactionNumber,'extras_Data'=>$extras_Data]);
    }
    
    public static function confbook_Transfer_Api(Request $request){
        $req                    = json_decode($request->confirm_Booking_Arr);
        // return $req;
        $countryList            = country_Codes();
        $country                = $req->country;
        $countrycodes           = get_Country_Codes($country, $countryList);
        
        // One Way
        if($req->Tranfer_type == 'One-Way'){
            $data_request = '<TCOML version="NEWFORMAT" sess="">
                                <TransferOnly>
                                    <Booking>
                                        <P2PConfirm>
                                            <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                            <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                            <TransacNo>' . $req->transactionnumber . '</TransacNo>
                                            <ExtrasTransacNo></ExtrasTransacNo>
                                            <AgentBref></AgentBref>
                                            <PropertyName>'. $req->hotel_Name .'</PropertyName>
                                            <AccommodationAddress>'. $req->hotel_Address .'</AccommodationAddress>
                                            <DepPoint>'.$req->deppoint.'</DepPoint>
                                            <DepInfo>'.$req->depinfo.'</DepInfo>
                                            <Client>
                                                <LastName>' . $req->lastname . '</LastName>
                                                <FirstName>' . $req->firstname . '</FirstName>
                                                <Title>' . $req->title . '</Title>
                                                <Phone>03017188122</Phone>
                                                <Mobile>03017188122</Mobile>
                                                <CountryCode>' . $countrycodes . '</CountryCode>
                                                <Email>' . $req->email . '</Email>
                                            </Client>
                                            <J1_IATA_Airline>TK</J1_IATA_Airline>
                                            <J2_IATA_Airline>TK</J2_IATA_Airline>
                                            <SendEmailToCustomer>0</SendEmailToCustomer>
                                            <Remark></Remark>
                                            <WA_Flag>1</WA_Flag>
                                        </P2PConfirm>
                                    </Booking>
                                </TransferOnly>
                            </TCOML>';
        }
        
        // Return
        if($req->Tranfer_type == 'Return'){
            $data_request  =   '<TCOML version="NEWFORMAT" sess="">
                                    <TransferOnly>
                                        <Booking>
                                            <P2PConfirm>
                                                <Username>'.env('TRANSFER_3RDPARTY_USERNAME').'</Username>
                                                <Password>'.env('TRANSFER_3RDPARTY_PASSWORD').'</Password>
                                                <TransacNo>' . $req->transactionnumber . '</TransacNo>
                                                <ExtrasTransacNo>
                                                
                                                </ExtrasTransacNo>
                                                <AgentBref></AgentBref>
                                                <PropertyName>'. $req->hotel_Name .'</PropertyName>
                                                <AccommodationAddress>'. $req->hotel_Address .'</AccommodationAddress>
                                                <AccommodationAddress2>
                                                </AccommodationAddress2>
                                                <J1_PropertyName></J1_PropertyName>
                                                <J1_AccommodationAddress>
                                                </J1_AccommodationAddress>
                                                <J1_AccommodationAddress2>
                                                </J1_AccommodationAddress2>
                                                <DepPoint>' . $req->deppoint . '</DepPoint>
                                                <RetPoint>' . $req->RetPoint . '</RetPoint>
                                                <DepInfo>' . $req->depinfo . '</DepInfo>
                                                <RetInfo>' . $req->RetInfo . '</RetInfo>
                                                <DepExtraInfo></DepExtraInfo>
                                                <RetExtraInfo></RetExtraInfo>
                                                <Client>
                                                    <LastName>' . $req->lastname . '</LastName>
                                                    <FirstName>' . $req->firstname . '</FirstName>
                                                    <Title>' . $req->title . '</Title>
                                                    <Phone>03017188122</Phone>
                                                    <Mobile>03017188122</Mobile>
                                                    <CountryCode>' . $countrycodes . '</CountryCode>
                                                    <Email>' . $req->email . '</Email>
                                                </Client>
                                                <J1_IATA_Airline>TK</J1_IATA_Airline>
                                                <J2_IATA_Airline>TK</J2_IATA_Airline>
                                                <SendEmailToCustomer>0</SendEmailToCustomer>
                                                <Remark></Remark>
                                                <WA_Flag>1</WA_Flag>
                                            </P2PConfirm>
                                        </Booking>
                                    </TransferOnly>
                                </TCOML>';
        }
        
        // return $data_request;
        
        $url        = "http://xmlp2p.com/xml/";
        $timeout    = 7;
        $data       = array('xml' => $data_request);
        $headers    = array("Content-type: application/x-www-form-urlencoded");
        $ch         = curl_init();
        $payload    = http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        // return $response;
        curl_close($ch);
        
        $xml    = simplexml_load_string($response);
        $json   = json_encode($xml, JSON_PRETTY_PRINT);
        $json   = stripslashes($json);
        $data   = json_decode($json, true);
        // return $data;
        
        if ($data === false){
            return response()->json(['error'=>'Vehical is already booked please select an other']);
        }
        
        if (isset($data['TransferOnly']['P2PResults']['errors']['error']['text'])) {
            $errorMessage = $data['TransferOnly']['P2PResults']['errors']['error']['text'];
            return response()->json(['error'=>$errorMessage]);
        }
        
        return response()->json(['message'=>'success','data'=>$data]);
    }
}