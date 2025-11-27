<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotels;
use App\Models\country;
use App\Models\city;
use DB;
class CountryController extends Controller
{
    function countries_flag_OLD() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://restcountries.com/v3.1/all?fields=name%2Cflags%2Ccca2%2Ccca3%2Cidd',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        if ($response) {
            // return response()->json(['response'=>json_decode($response, true)]);
            return json_decode($response, true);
        }else{
            $fallbackData = [
                [
                    "name" => ["common" => "Fallback Country"],
                    "flags" => ["png" => "https://example.com/fallback_flag.png"],
                    "cca2" => "FB",
                    "cca3" => "FBC",
                    "idd" => ["root" => "+99", "suffixes" => ["999"]]
                ]
            ];
            // return response()->json(['fallbackData'=>$fallbackData]);
            return $fallbackData;
        }
        // return $response;
    }
    
    function countries_flag() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://restcountries.com/v3.1/all?fields=name%2Cflags%2Ccca2%2Ccca3%2Cidd',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10, // Set reasonable timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $response = curl_exec($curl);
        
        // Check for cURL errors
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            error_log("CURL Error: " . $error_msg); // log error
            curl_close($curl);
            return [
                [
                    "name" => ["common" => "Fallback Country"],
                    "flags" => ["png" => "https://example.com/fallback_flag.png"],
                    "cca2" => "FB",
                    "cca3" => "FBC",
                    "idd" => ["root" => "+99", "suffixes" => ["999"]]
                ]
            ];
            // return [
            //     "error" => true,
            //     "message" => "Failed to fetch country flags: " . $error_msg,
            // ];
        }
        
        curl_close($curl);
        
        if ($response) {
            $decoded = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            } else {
                error_log("JSON Decode Error: " . json_last_error_msg());
                return [
                    [
                        "name" => ["common" => "Fallback Country"],
                        "flags" => ["png" => "https://example.com/fallback_flag.png"],
                        "cca2" => "FB",
                        "cca3" => "FBC",
                        "idd" => ["root" => "+99", "suffixes" => ["999"]]
                    ]
                ];
                // return [
                //     "error" => true,
                //     "message" => "Invalid JSON received from API."
                // ];
            }
        } else {
            return [
                [
                    "name" => ["common" => "Fallback Country"],
                    "flags" => ["png" => "https://example.com/fallback_flag.png"],
                    "cca2" => "FB",
                    "cca3" => "FBC",
                    "idd" => ["root" => "+99", "suffixes" => ["999"]]
                ]
            ];
        }
    }

    
    public function allcountriesfetch()
    {
        $country = country::all();
        return response()->json(['countries'=>$country]);
    }
    public function allcountriesfetch_new()
    {
        $country = country::all();
       
        return response()->json(['countries'=>$country]);
    }
    
    function allCountries(){
        DB::beginTransaction();
        try {
            $country = country::select('id','name','phonecode')->get();
            return response()->json(['message'=>'success','country'=>$country]);
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error','country'=> '']);
        }
    }
    
    function countryCites(Request $request){
        $country = country::find($request->id);
        $cites = $country->cites;
        $options = '';
        foreach($cites as $city_res){
            $options .="<option value='".$city_res['id']."'>".$city_res['name']."</option>";
        }
        return response()->json($options);
    }
    
    function get_Cities_Using_Code(Request $request){
        $country    = city::where('country_code',$request->country_Code)->get();
        return response()->json(['country'=>$country]);
       
    }
    
    function countryCites_api(Request $request){
        $id         = $request->id;
        $country    = city::where('country_id',$id)->get();
        return response()->json(['country'=>$country]);
    }
    
    function country_cites_laln(Request $request){
        $id         = $request->id;
        $city_D     = city::where('id',$id)->first();
        return response()->json(['city_D'=>$city_D]);
    }
    
    function country_cites_ID(Request $request){
        $id         = $request->id;
        $city_D     = DB::table('cities')->where('name',$id)->where('country_code',$request->country_id)->first();
        return response()->json(['city_D'=>$city_D]);
    }

    function getCountryName($id){
        $country = country::find($id);
        return $country->country_name;
    }
    
     function country_code(Request $request){
         
        $country = DB::table('countries')->where('name','=',$request->country)->first();
        print_r($country->phonecode);
        // return $country;
    }
}
