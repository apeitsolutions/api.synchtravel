<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Agents_detail;
use App\Models\addManageInvoice;
use App\Models\pay_Invoice_Agent;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\Tour;
use App\Models\Active;
use App\Models\country;
use App\Models\Activities;
use App\Models\payInvoiceAgent;
use App\Models\rooms_Invoice_Supplier;
use App\Models\alhijaz_Notofication;
use App\Models\flight_seats_occupied;
use App\Models\addLead;
use App\Models\addAtol;
use App\Models\addAtolFlightPackage;
use App\Models\addManageQuotationPackage;
use DB;
use Carbon\Carbon;

class AtolController_Admin extends Controller
{
 
/*
|--------------------------------------------------------------------------
| AtolController_Admin Function Started
|--------------------------------------------------------------------------
*/   
/*
|--------------------------------------------------------------------------
| Atol Register Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Atol List.
*/   
public function atol_Register(Request $req){
        $all_countries  = country::all();
        $atol_detail    = DB::table('addAtol')->where('customer_id',$req->customer_id)->get();
        return response()->json([
            'message'           => 'success',
            'all_countries'     => $all_countries,
            'atol_detail'       => $atol_detail,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Atol Register Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Add Atol Register Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Insert the details Atol for our client.
*/    
public function add_Register_Atol(Request $req){
        
        DB::beginTransaction();
        try {
            $atol                   = new addAtol();
            $atol->token            = $req->token;
            $atol->customer_id      = $req->customer_id;
            $atol->generate_id      = rand(0,9999999);
            $atol->company_Name     = $req->company_Name;
            $atol->atol_Number      = $req->atol_Number;
            $atol->atol_Sub_Agent   = $req->atol_Sub_Agent;
            $atol->atol_country     = $req->atol_country;
            $atol->atol_city        = $req->atol_city;
            $atol->atol_date        = $req->atol_date;
            $atol->save();
            
            $all_countries  = country::all();
            $atol_detail    = DB::table('addAtol')->where('customer_id',$req->customer_id)->get();
            
            return response()->json([
                'message'           => 'success',
                'all_countries'     => $all_countries,
                'atol_detail'       => $atol_detail,
            ]);
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
    }
/*
|--------------------------------------------------------------------------
| Add Atol Register Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Atol Flight Package Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display atol flight package details for our client.
*/    
public function atol_Flight_Package(Request $req){
        $customer_id            = $req->customer_id;
        $all_countries          = country::all();
        $atol_detail            = DB::table('addAtol')->where('customer_id',$req->customer_id)->get();
        $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->where('customer_id',$req->customer_id)->first();
        return response()->json([
            'message'               => 'success',
            'all_countries'         => $all_countries,
            'atol_detail'           => $atol_detail,
            'addAtolFlightPackage'  => $addAtolFlightPackage,
        ]);
    }
/*
|--------------------------------------------------------------------------
|  Atol Flight Package Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Add Register Flight Package Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to update register flight package details for our client.
*/    
public function add_Register_Flight_Package(Request $req){
        
        DB::beginTransaction();
        try {
            $addAtolFlightPackage = DB::table('addAtolFlightPackage')->where('customer_id',$req->customer_id)->first();
            if(isset($addAtolFlightPackage) && $addAtolFlightPackage != null && $addAtolFlightPackage != ''){
                // dd($addAtolFlightPackage);
                
                DB::table('addAtolFlightPackage')->where('id',$addAtolFlightPackage->id)->update([
                    'atol_id_Flight'    => $req->atol_id_Flight,
                    'atol_Fee_Flight'   => $req->atol_Fee_Flight,
                    'atol_id_Package'   => $req->atol_id_Package,
                    'atol_Fee_Package'  => $req->atol_Fee_Package,
                ]);
                
                $all_countries          = country::all();
                $atol_detail            = DB::table('addAtol')->where('customer_id',$req->customer_id)->get();
                $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->where('customer_id',$req->customer_id)->first();
                
                return response()->json([
                    'message'               => 'success',
                    'all_countries'         => $all_countries,
                    'atol_detail'           => $atol_detail,
                    'addAtolFlightPackage'  => $addAtolFlightPackage,
                ]); 
            }else{
                // dd('else');
            
                $atol                   = new addAtolFlightPackage();
                $atol->token            = $req->token;
                $atol->customer_id      = $req->customer_id;
                $atol->atol_id_Flight   = $req->atol_id_Flight;
                $atol->atol_Fee_Flight  = $req->atol_Fee_Flight;
                $atol->atol_id_Package  = $req->atol_id_Package;
                $atol->atol_Fee_Package = $req->atol_Fee_Package;
                $atol->save();
                
                $all_countries          = country::all();
                $atol_detail            = DB::table('addAtol')->where('customer_id',$req->customer_id)->get();
                $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->where('customer_id',$req->customer_id)->first();
                
                return response()->json([
                    'message'               => 'success',
                    'all_countries'         => $all_countries,
                    'atol_detail'           => $atol_detail,
                    'addAtolFlightPackage'  => $addAtolFlightPackage,
                ]);   
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
    }
/*
|--------------------------------------------------------------------------
|  Add Register Flight Package Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Atol Report Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display atol report details for our client.
*/    
public function atol_Report_Admin(Request $req){
        $all_countries          = country::all();
        $flight_invoice         = DB::table('add_manage_invoices')->whereJsonContains('services',['flights_tab'])->get();
        $atol_detail            = DB::table('addAtol')->get();
        $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->first();
        $all_Users              = DB::table('customer_subcriptions')->get();
        $package_Flights = [];
        // Package
        $cart_details           = DB::table('cart_details')->where('pakage_type','tour')->get();
        if(isset($cart_details) && $cart_details != null && $cart_details != ''){
            foreach($cart_details as $value){
                $cart_total_data_E = $value->cart_total_data;
                if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                    $cart_total_data = json_decode($cart_total_data_E);
                    // dd($cart_total_data);
                    
                    if(isset($cart_total_data->double_adults) || isset($cart_total_data->triple_adults) || isset($cart_total_data->quad_adults) || isset($cart_total_data->without_acc_adults)){
                        $total_adults  = $cart_total_data->double_adults + $cart_total_data->triple_adults + $cart_total_data->quad_adults + $cart_total_data->without_acc_adults;   
                    }else{
                        $total_adults = 0;
                    }
                    
                    if(isset($cart_total_data->double_childs) || isset($cart_total_data->triple_childs) || isset($cart_total_data->quad_childs)){
                        $total_childs  = $cart_total_data->double_childs + $cart_total_data->triple_childs + $cart_total_data->quad_childs;
                    }else{
                        $total_childs = 0;
                    }
                    
                    if(isset($cart_total_data->double_infant) || isset($cart_total_data->triple_infant) || isset($cart_total_data->quad_infant)){
                        $total_infant  = $cart_total_data->double_infant + $cart_total_data->triple_infant + $cart_total_data->quad_infant;
                    }else{
                        $total_infant = 0;
                    }
                    
                    $tour_booking_data =  DB::table('tours_bookings')->where('id',$value->booking_id)->select('passenger_detail')->first();;
                    if(isset($tour_booking_data) && $tour_booking_data != null && $tour_booking_data != ''){
                        $passenger_detail   = json_decode($tour_booking_data->passenger_detail);
                        $agent_name         = $passenger_detail[0]->name.' '.$passenger_detail[0]->lname;
                    }else{
                        $agent_name = '';
                    }
                    
                    $tours_data     = DB::table('tours_2')->where('tour_id',$cart_total_data->tourId)->select('tour_id','flight_supplier','flight_route_id_occupied','flights_per_person_price','flights_details')->first();
                    $flight_rute    = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    $flights_details_E = $tours_data->flights_details;
                    if(isset($flights_details_E) && $flights_details_E != null && $flights_details_E != ''){
                        $flights_details = json_decode($flights_details_E);
                        // dd($flights_details);
                        if(is_array($flights_details)){
                            foreach($flights_details as $flights_detailsS){
                                // dd($flights_details);
                                
                                if(isset($flight_rute->flights_per_person_price) && $flight_rute->flights_per_person_price != null && $flight_rute->flights_per_person_price != ''){
                                    $flights_per_person_price = $flight_rute->flights_per_person_price;
                                }elseif(isset($tours_data->flights_per_person_price) && $tours_data->flights_per_person_price != null && $tours_data->flights_per_person_price != ''){
                                    $flights_per_person_price = $tours_data->flights_per_person_price;
                                }else{
                                    $flights_per_person_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_child_price) && $flight_rute->flights_per_child_price != null && $flight_rute->flights_per_child_price != ''){
                                    $flights_per_child_price = $flight_rute->flights_per_child_price;
                                }else{
                                    $flights_per_child_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_infant_price) && $flight_rute->flights_per_infant_price != null && $flight_rute->flights_per_infant_price != ''){
                                    $flights_per_infant_price = $flight_rute->flights_per_infant_price;
                                }else{
                                    $flights_per_infant_price = 0;
                                }
                                
                                $total_price = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price ?? '0';
                                
                                // if($flights_detailsS->departure_airport_code == '' || $flights_detailsS->departure_airport_code == null){
                                //     dd($flights_details);
                                // }
                                
                                $PF = [
                                    'customer_id'   => $value->client_id,
                                    'invoice_no'    => $value->invoice_no,
                                    'booking_id'    => $value->booking_id,
                                    'package_id'    => $value->tour_id,
                                    'agent_name'    => $agent_name,
                                    'departure'     => $flights_detailsS->departure_airport_code,
                                    'arrival'       => $flights_detailsS->arrival_airport_code,
                                    'airline_name'  => $flights_detailsS->other_Airline_Name2,
                                    'flight_no'     => $flights_detailsS->departure_flight_number,
                                    'adult_price'   => $flights_per_person_price,
                                    'child_price'   => $flights_per_child_price,
                                    'infant_price'  => $flights_per_infant_price,
                                    'total_adults'  => $total_adults,
                                    'total_childs'  => $total_childs,
                                    'total_infant'  => $total_infant,
                                    'total_price'   => $total_price,
                                ];
                                if($total_adults <= 0 && $total_childs <= 0 && $total_infant <= 0){
                                }else{
                                    array_push($package_Flights,$PF);
                                }
                            }
                        }else{
                            $flights_per_person_price   = $flight_rute->flights_per_person_price ?? '0';
                            $flights_per_child_price    = $flight_rute->flights_per_child_price ?? '0';
                            $flights_per_infant_price   = $flight_rute->flights_per_infant_price ?? '0';
                            $total_price                = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price;
                            
                            $PF = [
                                'customer_id'   => $value->client_id ?? '',
                                'invoice_no'    => $value->invoice_no ?? '',
                                'booking_id'    => $value->booking_id ?? '',
                                'package_id'    => $value->tour_id ?? '',
                                'agent_name'    => $agent_name ?? '',
                                'departure'     => $flights_details->departure_airport_code ?? '',
                                'arrival'       => $flights_details->arrival_airport_code ?? '',
                                'airline_name'  => $flights_details->other_Airline_Name2 ?? '',
                                'flight_no'     => $flights_details->departure_flight_number ?? '',
                                'adult_price'   => $flight_rute->flights_per_person_price ?? '',
                                'child_price'   => $flight_rute->flights_per_child_price ?? '',
                                'infant_price'  => $flight_rute->flights_per_infant_price ?? '',
                                'total_adults'  => $total_adults ?? '',
                                'total_childs'  => $total_childs ?? '',
                                'total_infant'  => $total_infant ?? '',
                                'total_price'   => $total_price ?? '',
                            ];
                        }
                    }
                }
            }
        }else{
            $package_Flights = '';
        }
        
        $type = 'All';
        
        return view('template/frontend/userdashboard/pages/Atol/Report/atol_Report_Weekly',compact('type','all_Users','package_Flights','flight_invoice','all_countries','addAtolFlightPackage','atol_detail'));
    }
/*
|--------------------------------------------------------------------------
|  Atol Report Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Atol Report Weekly Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display atol report weekly details for our client.
*/    
public function atol_Report_Weekly_Admin(Request $req){
        $all_countries          = country::all();
        $flight_invoice         = DB::table('add_manage_invoices')->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->whereJsonContains('services',['flights_tab'])->get();
        $atol_detail            = DB::table('addAtol')->get();
        $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->first();
        $all_Users              = DB::table('customer_subcriptions')->get();
        $package_Flights = [];
        // Package
        $cart_details           = DB::table('cart_details')->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->where('pakage_type','tour')->get();
        if(isset($cart_details) && $cart_details != null && $cart_details != ''){
            foreach($cart_details as $value){
                $cart_total_data_E = $value->cart_total_data;
                if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                    $cart_total_data = json_decode($cart_total_data_E);
                    // dd($cart_total_data);
                    
                    if(isset($cart_total_data->double_adults) || isset($cart_total_data->triple_adults) || isset($cart_total_data->quad_adults) || isset($cart_total_data->without_acc_adults)){
                        $total_adults  = $cart_total_data->double_adults + $cart_total_data->triple_adults + $cart_total_data->quad_adults + $cart_total_data->without_acc_adults;   
                    }else{
                        $total_adults = 0;
                    }
                    
                    if(isset($cart_total_data->double_childs) || isset($cart_total_data->triple_childs) || isset($cart_total_data->quad_childs)){
                        $total_childs  = $cart_total_data->double_childs + $cart_total_data->triple_childs + $cart_total_data->quad_childs;
                    }else{
                        $total_childs = 0;
                    }
                    
                    if(isset($cart_total_data->double_infant) || isset($cart_total_data->triple_infant) || isset($cart_total_data->quad_infant)){
                        $total_infant  = $cart_total_data->double_infant + $cart_total_data->triple_infant + $cart_total_data->quad_infant;
                    }else{
                        $total_infant = 0;
                    }
                    
                    $tour_booking_data =  DB::table('tours_bookings')->where('id',$value->booking_id)->select('passenger_detail')->first();;
                    if(isset($tour_booking_data) && $tour_booking_data != null && $tour_booking_data != ''){
                        $passenger_detail   = json_decode($tour_booking_data->passenger_detail);
                        $agent_name         = $passenger_detail[0]->name.' '.$passenger_detail[0]->lname;
                    }else{
                        $agent_name = '';
                    }
                    
                    $tours_data     = DB::table('tours_2')->where('tour_id',$cart_total_data->tourId)->select('tour_id','flight_supplier','flight_route_id_occupied','flights_per_person_price','flights_details')->first();
                    $flight_rute    = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    $flights_details_E = $tours_data->flights_details;
                    if(isset($flights_details_E) && $flights_details_E != null && $flights_details_E != ''){
                        $flights_details = json_decode($flights_details_E);
                        if(is_array($flights_details)){
                            foreach($flights_details as $flights_detailsS){
                                // dd($flights_details);
                                
                                if(isset($flight_rute->flights_per_person_price) && $flight_rute->flights_per_person_price != null && $flight_rute->flights_per_person_price != ''){
                                    $flights_per_person_price = $flight_rute->flights_per_person_price;
                                }elseif(isset($tours_data->flights_per_person_price) && $tours_data->flights_per_person_price != null && $tours_data->flights_per_person_price != ''){
                                    $flights_per_person_price = $tours_data->flights_per_person_price;
                                }else{
                                    $flights_per_person_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_child_price) && $flight_rute->flights_per_child_price != null && $flight_rute->flights_per_child_price != ''){
                                    $flights_per_child_price = $flight_rute->flights_per_child_price;
                                }else{
                                    $flights_per_child_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_infant_price) && $flight_rute->flights_per_infant_price != null && $flight_rute->flights_per_infant_price != ''){
                                    $flights_per_infant_price = $flight_rute->flights_per_infant_price;
                                }else{
                                    $flights_per_infant_price = 0;
                                }
                                
                                $total_price = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price ?? '0';
                                
                                $PF = [
                                    'customer_id'   => $value->client_id,
                                    'invoice_no'    => $value->invoice_no,
                                    'booking_id'    => $value->booking_id,
                                    'package_id'    => $value->tour_id,
                                    'agent_name'    => $agent_name,
                                    'departure'     => $flights_detailsS->departure_airport_code,
                                    'arrival'       => $flights_detailsS->arrival_airport_code,
                                    'airline_name'  => $flights_detailsS->other_Airline_Name2,
                                    'flight_no'     => $flights_detailsS->departure_flight_number,
                                    'adult_price'   => $flights_per_person_price,
                                    'child_price'   => $flights_per_child_price,
                                    'infant_price'  => $flights_per_infant_price,
                                    'total_adults'  => $total_adults,
                                    'total_childs'  => $total_childs,
                                    'total_infant'  => $total_infant,
                                    'total_price'   => $total_price,
                                ];
                                if($total_adults <= 0 && $total_childs <= 0 && $total_infant <= 0){
                                }else{
                                    array_push($package_Flights,$PF);
                                }
                            }
                        }else{
                            $flights_per_person_price   = $flight_rute->flights_per_person_price ?? '0';
                            $flights_per_child_price    = $flight_rute->flights_per_child_price ?? '0';
                            $flights_per_infant_price   = $flight_rute->flights_per_infant_price ?? '0';
                            $total_price                = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price;
                            // dd($flights_details);
                            
                            $PF = [
                                'customer_id'   => $value->client_id ?? '',
                                'invoice_no'    => $value->invoice_no ?? '',
                                'booking_id'    => $value->booking_id ?? '',
                                'package_id'    => $value->tour_id ?? '',
                                'agent_name'    => $agent_name ?? '',
                                'departure'     => $flights_details->departure_airport_code ?? '',
                                'arrival'       => $flights_details->arrival_airport_code ?? '',
                                'airline_name'  => $flights_details->other_Airline_Name2 ?? '',
                                'flight_no'     => $flights_details->departure_flight_number ?? '',
                                'adult_price'   => $flight_rute->flights_per_person_price ?? '',
                                'child_price'   => $flight_rute->flights_per_child_price ?? '',
                                'infant_price'  => $flight_rute->flights_per_infant_price ?? '',
                                'total_adults'  => $total_adults ?? '',
                                'total_childs'  => $total_childs ?? '',
                                'total_infant'  => $total_infant ?? '',
                                'total_price'   => $total_price ?? '',
                            ];
                            // array_push($package_Flights,$PF);
                        }
                    }
                }
            }
        }else{
            $package_Flights = '';
        }
        
        $type = 'Weekly';
        
        return view('template/frontend/userdashboard/pages/Atol/Report/atol_Report_Weekly',compact('type','all_Users','package_Flights','flight_invoice','all_countries','addAtolFlightPackage','atol_detail'));
    }
/*
|--------------------------------------------------------------------------
|  Atol Report Weekly Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Atol Report monthly Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display atol report monthly details for our client.
*/    
public function atol_Report_Monthly_Admin(Request $req){
        $all_countries          = country::all();
        $flight_invoice         = DB::table('add_manage_invoices')->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->whereJsonContains('services',['flights_tab'])->get();
        $atol_detail            = DB::table('addAtol')->get();
        $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->first();
        $all_Users              = DB::table('customer_subcriptions')->get();
        $package_Flights = [];
        
        // Package
        $cart_details           = DB::table('cart_details')->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('pakage_type','tour')->get();
        if(isset($cart_details) && $cart_details != null && $cart_details != ''){
            foreach($cart_details as $value){
                $cart_total_data_E = $value->cart_total_data;
                if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                    $cart_total_data = json_decode($cart_total_data_E);
                    // dd($cart_total_data);
                    
                    if(isset($cart_total_data->double_adults) || isset($cart_total_data->triple_adults) || isset($cart_total_data->quad_adults) || isset($cart_total_data->without_acc_adults)){
                        $total_adults  = $cart_total_data->double_adults + $cart_total_data->triple_adults + $cart_total_data->quad_adults + $cart_total_data->without_acc_adults;   
                    }else{
                        $total_adults = 0;
                    }
                    
                    if(isset($cart_total_data->double_childs) || isset($cart_total_data->triple_childs) || isset($cart_total_data->quad_childs)){
                        $total_childs  = $cart_total_data->double_childs + $cart_total_data->triple_childs + $cart_total_data->quad_childs;
                    }else{
                        $total_childs = 0;
                    }
                    
                    if(isset($cart_total_data->double_infant) || isset($cart_total_data->triple_infant) || isset($cart_total_data->quad_infant)){
                        $total_infant  = $cart_total_data->double_infant + $cart_total_data->triple_infant + $cart_total_data->quad_infant;
                    }else{
                        $total_infant = 0;
                    }
                    
                    $tour_booking_data =  DB::table('tours_bookings')->where('id',$value->booking_id)->select('passenger_detail')->first();;
                    if(isset($tour_booking_data) && $tour_booking_data != null && $tour_booking_data != ''){
                        $passenger_detail   = json_decode($tour_booking_data->passenger_detail);
                        $agent_name         = $passenger_detail[0]->name.' '.$passenger_detail[0]->lname;
                    }else{
                        $agent_name = '';
                    }
                    
                    $tours_data     = DB::table('tours_2')->where('tour_id',$cart_total_data->tourId)->select('tour_id','flight_supplier','flight_route_id_occupied','flights_per_person_price','flights_details')->first();
                    $flight_rute    = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    $flights_details_E = $tours_data->flights_details;
                    if(isset($flights_details_E) && $flights_details_E != null && $flights_details_E != ''){
                        $flights_details = json_decode($flights_details_E);
                        if(is_array($flights_details)){
                            foreach($flights_details as $flights_detailsS){
                                // dd($flights_details);
                                
                                if(isset($flight_rute->flights_per_person_price) && $flight_rute->flights_per_person_price != null && $flight_rute->flights_per_person_price != ''){
                                    $flights_per_person_price = $flight_rute->flights_per_person_price;
                                }elseif(isset($tours_data->flights_per_person_price) && $tours_data->flights_per_person_price != null && $tours_data->flights_per_person_price != ''){
                                    $flights_per_person_price = $tours_data->flights_per_person_price;
                                }else{
                                    $flights_per_person_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_child_price) && $flight_rute->flights_per_child_price != null && $flight_rute->flights_per_child_price != ''){
                                    $flights_per_child_price = $flight_rute->flights_per_child_price;
                                }else{
                                    $flights_per_child_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_infant_price) && $flight_rute->flights_per_infant_price != null && $flight_rute->flights_per_infant_price != ''){
                                    $flights_per_infant_price = $flight_rute->flights_per_infant_price;
                                }else{
                                    $flights_per_infant_price = 0;
                                }
                                
                                $total_price = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price ?? '0';
                                
                                $PF = [
                                    'customer_id'   => $value->client_id,
                                    'invoice_no'    => $value->invoice_no,
                                    'booking_id'    => $value->booking_id,
                                    'package_id'    => $value->tour_id,
                                    'agent_name'    => $agent_name,
                                    'departure'     => $flights_detailsS->departure_airport_code,
                                    'arrival'       => $flights_detailsS->arrival_airport_code,
                                    'airline_name'  => $flights_detailsS->other_Airline_Name2,
                                    'flight_no'     => $flights_detailsS->departure_flight_number,
                                    'adult_price'   => $flights_per_person_price,
                                    'child_price'   => $flights_per_child_price,
                                    'infant_price'  => $flights_per_infant_price,
                                    'total_adults'  => $total_adults,
                                    'total_childs'  => $total_childs,
                                    'total_infant'  => $total_infant,
                                    'total_price'   => $total_price,
                                ];
                                if($total_adults <= 0 && $total_childs <= 0 && $total_infant <= 0){
                                }else{
                                    array_push($package_Flights,$PF);
                                }
                            }
                        }else{
                            $flights_per_person_price   = $flight_rute->flights_per_person_price ?? '0';
                            $flights_per_child_price    = $flight_rute->flights_per_child_price ?? '0';
                            $flights_per_infant_price   = $flight_rute->flights_per_infant_price ?? '0';
                            $total_price                = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price;
                            // dd($flights_details);
                            
                            $PF = [
                                'customer_id'   => $value->client_id ?? '',
                                'invoice_no'    => $value->invoice_no ?? '',
                                'booking_id'    => $value->booking_id ?? '',
                                'package_id'    => $value->tour_id ?? '',
                                'agent_name'    => $agent_name ?? '',
                                'departure'     => $flights_details->departure_airport_code ?? '',
                                'arrival'       => $flights_details->arrival_airport_code ?? '',
                                'airline_name'  => $flights_details->other_Airline_Name2 ?? '',
                                'flight_no'     => $flights_details->departure_flight_number ?? '',
                                'adult_price'   => $flight_rute->flights_per_person_price ?? '',
                                'child_price'   => $flight_rute->flights_per_child_price ?? '',
                                'infant_price'  => $flight_rute->flights_per_infant_price ?? '',
                                'total_adults'  => $total_adults ?? '',
                                'total_childs'  => $total_childs ?? '',
                                'total_infant'  => $total_infant ?? '',
                                'total_price'   => $total_price ?? '',
                            ];
                            // array_push($package_Flights,$PF);
                        }
                    }
                }
            }
        }else{
            $package_Flights = '';
        }
        
        $type = 'Monthly';
        
        return view('template/frontend/userdashboard/pages/Atol/Report/atol_Report_Weekly',compact('type','all_Users','package_Flights','flight_invoice','all_countries','addAtolFlightPackage','atol_detail'));
    }
/*
|--------------------------------------------------------------------------
|  Atol Report monthly Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Atol Report Quarter Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display atol report Quarter details for our client.
*/       
public function atol_Report_Quarter_Admin(Request $req){
        $startDate              = \Carbon\Carbon::parse('January 1 last year');
        $endDate                = $startDate->copy()->addMonths(4)->subDay();
        
        // dd($startDate);
        // dd($endDate);
        
        $all_countries          = country::all();
        $flight_invoice         = DB::table('add_manage_invoices')->whereJsonContains('services',['flights_tab'])->whereBetween('created_at', [$startDate, $endDate])->get();
        $atol_detail            = DB::table('addAtol')->get();
        $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->first();
        $all_Users              = DB::table('customer_subcriptions')->get();
        // Package
        $package_Flights        = [];
        $cart_details           = DB::table('cart_details')->where('pakage_type','tour')->whereBetween('created_at', [$startDate, $endDate])->get();
        if(isset($cart_details) && $cart_details != null && $cart_details != ''){
            foreach($cart_details as $value){
                $cart_total_data_E = $value->cart_total_data;
                if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                    $cart_total_data = json_decode($cart_total_data_E);
                    // dd($cart_total_data);
                    
                    if(isset($cart_total_data->double_adults) || isset($cart_total_data->triple_adults) || isset($cart_total_data->quad_adults) || isset($cart_total_data->without_acc_adults)){
                        $total_adults  = $cart_total_data->double_adults + $cart_total_data->triple_adults + $cart_total_data->quad_adults + $cart_total_data->without_acc_adults;   
                    }else{
                        $total_adults = 0;
                    }
                    
                    if(isset($cart_total_data->double_childs) || isset($cart_total_data->triple_childs) || isset($cart_total_data->quad_childs)){
                        $total_childs  = $cart_total_data->double_childs + $cart_total_data->triple_childs + $cart_total_data->quad_childs;
                    }else{
                        $total_childs = 0;
                    }
                    
                    if(isset($cart_total_data->double_infant) || isset($cart_total_data->triple_infant) || isset($cart_total_data->quad_infant)){
                        $total_infant  = $cart_total_data->double_infant + $cart_total_data->triple_infant + $cart_total_data->quad_infant;
                    }else{
                        $total_infant = 0;
                    }
                    
                    $tour_booking_data =  DB::table('tours_bookings')->where('id',$value->booking_id)->select('passenger_detail')->first();;
                    if(isset($tour_booking_data) && $tour_booking_data != null && $tour_booking_data != ''){
                        $passenger_detail   = json_decode($tour_booking_data->passenger_detail);
                        $agent_name         = $passenger_detail[0]->name.' '.$passenger_detail[0]->lname;
                    }else{
                        $agent_name = '';
                    }
                    
                    $tours_data     = DB::table('tours_2')->where('tour_id',$cart_total_data->tourId)->select('tour_id','flight_supplier','flight_route_id_occupied','flights_per_person_price','flights_details')->first();
                    $flight_rute    = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    $flights_details_E = $tours_data->flights_details;
                    if(isset($flights_details_E) && $flights_details_E != null && $flights_details_E != ''){
                        $flights_details = json_decode($flights_details_E);
                        if(is_array($flights_details)){
                            foreach($flights_details as $flights_detailsS){
                                // dd($flights_details);
                                
                                if(isset($flight_rute->flights_per_person_price) && $flight_rute->flights_per_person_price != null && $flight_rute->flights_per_person_price != ''){
                                    $flights_per_person_price = $flight_rute->flights_per_person_price;
                                }elseif(isset($tours_data->flights_per_person_price) && $tours_data->flights_per_person_price != null && $tours_data->flights_per_person_price != ''){
                                    $flights_per_person_price = $tours_data->flights_per_person_price;
                                }else{
                                    $flights_per_person_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_child_price) && $flight_rute->flights_per_child_price != null && $flight_rute->flights_per_child_price != ''){
                                    $flights_per_child_price = $flight_rute->flights_per_child_price;
                                }else{
                                    $flights_per_child_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_infant_price) && $flight_rute->flights_per_infant_price != null && $flight_rute->flights_per_infant_price != ''){
                                    $flights_per_infant_price = $flight_rute->flights_per_infant_price;
                                }else{
                                    $flights_per_infant_price = 0;
                                }
                                
                                $total_price = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price ?? '0';
                                
                                $PF = [
                                    'customer_id'   => $value->client_id,
                                    'invoice_no'    => $value->invoice_no,
                                    'booking_id'    => $value->booking_id,
                                    'package_id'    => $value->tour_id,
                                    'agent_name'    => $agent_name,
                                    'departure'     => $flights_detailsS->departure_airport_code,
                                    'arrival'       => $flights_detailsS->arrival_airport_code,
                                    'airline_name'  => $flights_detailsS->other_Airline_Name2,
                                    'flight_no'     => $flights_detailsS->departure_flight_number,
                                    'adult_price'   => $flights_per_person_price,
                                    'child_price'   => $flights_per_child_price,
                                    'infant_price'  => $flights_per_infant_price,
                                    'total_adults'  => $total_adults,
                                    'total_childs'  => $total_childs,
                                    'total_infant'  => $total_infant,
                                    'total_price'   => $total_price,
                                ];
                                if($total_adults <= 0 && $total_childs <= 0 && $total_infant <= 0){
                                }else{
                                    array_push($package_Flights,$PF);
                                }
                            }
                        }else{
                            $flights_per_person_price   = $flight_rute->flights_per_person_price ?? '0';
                            $flights_per_child_price    = $flight_rute->flights_per_child_price ?? '0';
                            $flights_per_infant_price   = $flight_rute->flights_per_infant_price ?? '0';
                            $total_price                = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price;
                            // dd($flights_details);
                            
                            $PF = [
                                'customer_id'   => $value->client_id,
                                'invoice_no'    => $value->invoice_no ?? '',
                                'booking_id'    => $value->booking_id ?? '',
                                'package_id'    => $value->tour_id ?? '',
                                'agent_name'    => $agent_name ?? '',
                                'departure'     => $flights_details->departure_airport_code ?? '',
                                'arrival'       => $flights_details->arrival_airport_code ?? '',
                                'airline_name'  => $flights_details->other_Airline_Name2 ?? '',
                                'flight_no'     => $flights_details->departure_flight_number ?? '',
                                'adult_price'   => $flight_rute->flights_per_person_price ?? '',
                                'child_price'   => $flight_rute->flights_per_child_price ?? '',
                                'infant_price'  => $flight_rute->flights_per_infant_price ?? '',
                                'total_adults'  => $total_adults ?? '',
                                'total_childs'  => $total_childs ?? '',
                                'total_infant'  => $total_infant ?? '',
                                'total_price'   => $total_price ?? '',
                            ];
                            // array_push($package_Flights,$PF);
                        }
                    }
                }
            }
        }else{
            $package_Flights = '';
        }
        
        $type = 'Quarter';
        
        return view('template/frontend/userdashboard/pages/Atol/Report/atol_Report_Weekly',compact('type','all_Users','package_Flights','flight_invoice','all_countries','addAtolFlightPackage','atol_detail'));
    }
/*
|--------------------------------------------------------------------------
|  Atol Report Quarter Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Atol Report half yearly Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display atol report half yearly details for our client.
*/    
public function atol_Report_Half_Yearly_Admin(Request $req){
        $startDate              = \Carbon\Carbon::parse('January 1 last year');
        $endDate                = $startDate->copy()->addMonths(6)->subDay();
        
        // dd($startDate);
        // dd($endDate);
        
        $all_countries          = country::all();
        $flight_invoice         = DB::table('add_manage_invoices')->whereJsonContains('services',['flights_tab'])->whereBetween('created_at', [$startDate, $endDate])->get();
        $atol_detail            = DB::table('addAtol')->get();
        $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->first();
        $all_Users              = DB::table('customer_subcriptions')->get();
        // Package
        $package_Flights        = [];
        $cart_details           = DB::table('cart_details')->where('pakage_type','tour')->whereBetween('created_at', [$startDate, $endDate])->get();
        if(isset($cart_details) && $cart_details != null && $cart_details != ''){
            foreach($cart_details as $value){
                $cart_total_data_E = $value->cart_total_data;
                if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                    $cart_total_data = json_decode($cart_total_data_E);
                    // dd($cart_total_data);
                    
                    if(isset($cart_total_data->double_adults) || isset($cart_total_data->triple_adults) || isset($cart_total_data->quad_adults) || isset($cart_total_data->without_acc_adults)){
                        $total_adults  = $cart_total_data->double_adults + $cart_total_data->triple_adults + $cart_total_data->quad_adults + $cart_total_data->without_acc_adults;   
                    }else{
                        $total_adults = 0;
                    }
                    
                    if(isset($cart_total_data->double_childs) || isset($cart_total_data->triple_childs) || isset($cart_total_data->quad_childs)){
                        $total_childs  = $cart_total_data->double_childs + $cart_total_data->triple_childs + $cart_total_data->quad_childs;
                    }else{
                        $total_childs = 0;
                    }
                    
                    if(isset($cart_total_data->double_infant) || isset($cart_total_data->triple_infant) || isset($cart_total_data->quad_infant)){
                        $total_infant  = $cart_total_data->double_infant + $cart_total_data->triple_infant + $cart_total_data->quad_infant;
                    }else{
                        $total_infant = 0;
                    }
                    
                    $tour_booking_data =  DB::table('tours_bookings')->where('id',$value->booking_id)->select('passenger_detail')->first();;
                    if(isset($tour_booking_data) && $tour_booking_data != null && $tour_booking_data != ''){
                        $passenger_detail   = json_decode($tour_booking_data->passenger_detail);
                        $agent_name         = $passenger_detail[0]->name.' '.$passenger_detail[0]->lname;
                    }else{
                        $agent_name = '';
                    }
                    
                    $tours_data     = DB::table('tours_2')->where('tour_id',$cart_total_data->tourId)->select('tour_id','flight_supplier','flight_route_id_occupied','flights_per_person_price','flights_details')->first();
                    $flight_rute    = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    $flights_details_E = $tours_data->flights_details;
                    if(isset($flights_details_E) && $flights_details_E != null && $flights_details_E != ''){
                        $flights_details = json_decode($flights_details_E);
                        if(is_array($flights_details)){
                            foreach($flights_details as $flights_detailsS){
                                // dd($flights_details);
                                
                                if(isset($flight_rute->flights_per_person_price) && $flight_rute->flights_per_person_price != null && $flight_rute->flights_per_person_price != ''){
                                    $flights_per_person_price = $flight_rute->flights_per_person_price;
                                }elseif(isset($tours_data->flights_per_person_price) && $tours_data->flights_per_person_price != null && $tours_data->flights_per_person_price != ''){
                                    $flights_per_person_price = $tours_data->flights_per_person_price;
                                }else{
                                    $flights_per_person_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_child_price) && $flight_rute->flights_per_child_price != null && $flight_rute->flights_per_child_price != ''){
                                    $flights_per_child_price = $flight_rute->flights_per_child_price;
                                }else{
                                    $flights_per_child_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_infant_price) && $flight_rute->flights_per_infant_price != null && $flight_rute->flights_per_infant_price != ''){
                                    $flights_per_infant_price = $flight_rute->flights_per_infant_price;
                                }else{
                                    $flights_per_infant_price = 0;
                                }
                                
                                $total_price = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price ?? '0';
                                
                                $PF = [
                                    'customer_id'   => $value->client_id,
                                    'invoice_no'    => $value->invoice_no,
                                    'booking_id'    => $value->booking_id,
                                    'package_id'    => $value->tour_id,
                                    'agent_name'    => $agent_name,
                                    'departure'     => $flights_detailsS->departure_airport_code,
                                    'arrival'       => $flights_detailsS->arrival_airport_code,
                                    'airline_name'  => $flights_detailsS->other_Airline_Name2,
                                    'flight_no'     => $flights_detailsS->departure_flight_number,
                                    'adult_price'   => $flights_per_person_price,
                                    'child_price'   => $flights_per_child_price,
                                    'infant_price'  => $flights_per_infant_price,
                                    'total_adults'  => $total_adults,
                                    'total_childs'  => $total_childs,
                                    'total_infant'  => $total_infant,
                                    'total_price'   => $total_price,
                                ];
                                if($total_adults <= 0 && $total_childs <= 0 && $total_infant <= 0){
                                }else{
                                    array_push($package_Flights,$PF);
                                }
                            }
                        }else{
                            $flights_per_person_price   = $flight_rute->flights_per_person_price ?? '0';
                            $flights_per_child_price    = $flight_rute->flights_per_child_price ?? '0';
                            $flights_per_infant_price   = $flight_rute->flights_per_infant_price ?? '0';
                            $total_price                = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price;
                            // dd($flights_details);
                            
                            $PF = [
                                'customer_id'   => $value->client_id ?? '',
                                'invoice_no'    => $value->invoice_no ?? '',
                                'booking_id'    => $value->booking_id ?? '',
                                'package_id'    => $value->tour_id ?? '',
                                'agent_name'    => $agent_name ?? '',
                                'departure'     => $flights_details->departure_airport_code ?? '',
                                'arrival'       => $flights_details->arrival_airport_code ?? '',
                                'airline_name'  => $flights_details->other_Airline_Name2 ?? '',
                                'flight_no'     => $flights_details->departure_flight_number ?? '',
                                'adult_price'   => $flight_rute->flights_per_person_price ?? '',
                                'child_price'   => $flight_rute->flights_per_child_price ?? '',
                                'infant_price'  => $flight_rute->flights_per_infant_price ?? '',
                                'total_adults'  => $total_adults ?? '',
                                'total_childs'  => $total_childs ?? '',
                                'total_infant'  => $total_infant ?? '',
                                'total_price'   => $total_price ?? '',
                            ];
                            // array_push($package_Flights,$PF);
                        }
                    }
                }
            }
        }else{
            $package_Flights = '';
        }
        
        $type = 'Half_Yearly';
        
        return view('template/frontend/userdashboard/pages/Atol/Report/atol_Report_Weekly',compact('type','all_Users','package_Flights','flight_invoice','all_countries','addAtolFlightPackage','atol_detail'));
    }
/*
|--------------------------------------------------------------------------
|  Atol Report half yearly Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Atol Report yearly Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display atol report yearly details for our client.
*/    
public function atol_Report_Yearly_Admin(Request $req){
        $all_countries          = country::all();
        $flight_invoice         = DB::table('add_manage_invoices')->whereYear('created_at', now()->subYear()->year)->whereJsonContains('services',['flights_tab'])->get();
        $atol_detail            = DB::table('addAtol')->get();
        $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->first();
        $all_Users              = DB::table('customer_subcriptions')->get();
        $package_Flights = [];
        // Package
        $cart_details           = DB::table('cart_details')->whereYear('created_at', now()->subYear()->year)->where('pakage_type','tour')->get();
        if(isset($cart_details) && $cart_details != null && $cart_details != ''){
            foreach($cart_details as $value){
                $cart_total_data_E = $value->cart_total_data;
                if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                    $cart_total_data = json_decode($cart_total_data_E);
                    // dd($cart_total_data);
                    
                    if(isset($cart_total_data->double_adults) || isset($cart_total_data->triple_adults) || isset($cart_total_data->quad_adults) || isset($cart_total_data->without_acc_adults)){
                        $total_adults  = $cart_total_data->double_adults + $cart_total_data->triple_adults + $cart_total_data->quad_adults + $cart_total_data->without_acc_adults;   
                    }else{
                        $total_adults = 0;
                    }
                    
                    if(isset($cart_total_data->double_childs) || isset($cart_total_data->triple_childs) || isset($cart_total_data->quad_childs)){
                        $total_childs  = $cart_total_data->double_childs + $cart_total_data->triple_childs + $cart_total_data->quad_childs;
                    }else{
                        $total_childs = 0;
                    }
                    
                    if(isset($cart_total_data->double_infant) || isset($cart_total_data->triple_infant) || isset($cart_total_data->quad_infant)){
                        $total_infant  = $cart_total_data->double_infant + $cart_total_data->triple_infant + $cart_total_data->quad_infant;
                    }else{
                        $total_infant = 0;
                    }
                    
                    $tour_booking_data =  DB::table('tours_bookings')->where('id',$value->booking_id)->select('passenger_detail')->first();;
                    if(isset($tour_booking_data) && $tour_booking_data != null && $tour_booking_data != ''){
                        $passenger_detail   = json_decode($tour_booking_data->passenger_detail);
                        $agent_name         = $passenger_detail[0]->name.' '.$passenger_detail[0]->lname;
                    }else{
                        $agent_name = '';
                    }
                    
                    $tours_data     = DB::table('tours_2')->where('tour_id',$cart_total_data->tourId)->select('tour_id','flight_supplier','flight_route_id_occupied','flights_per_person_price','flights_details')->first();
                    $flight_rute    = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    $flights_details_E = $tours_data->flights_details;
                    if(isset($flights_details_E) && $flights_details_E != null && $flights_details_E != ''){
                        $flights_details = json_decode($flights_details_E);
                        if(is_array($flights_details)){
                            foreach($flights_details as $flights_detailsS){
                                // dd($flights_details);
                                
                                if(isset($flight_rute->flights_per_person_price) && $flight_rute->flights_per_person_price != null && $flight_rute->flights_per_person_price != ''){
                                    $flights_per_person_price = $flight_rute->flights_per_person_price;
                                }elseif(isset($tours_data->flights_per_person_price) && $tours_data->flights_per_person_price != null && $tours_data->flights_per_person_price != ''){
                                    $flights_per_person_price = $tours_data->flights_per_person_price;
                                }else{
                                    $flights_per_person_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_child_price) && $flight_rute->flights_per_child_price != null && $flight_rute->flights_per_child_price != ''){
                                    $flights_per_child_price = $flight_rute->flights_per_child_price;
                                }else{
                                    $flights_per_child_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_infant_price) && $flight_rute->flights_per_infant_price != null && $flight_rute->flights_per_infant_price != ''){
                                    $flights_per_infant_price = $flight_rute->flights_per_infant_price;
                                }else{
                                    $flights_per_infant_price = 0;
                                }
                                
                                $total_price = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price ?? '0';
                                
                                $PF = [
                                    'customer_id'   => $value->client_id,
                                    'invoice_no'    => $value->invoice_no,
                                    'booking_id'    => $value->booking_id,
                                    'package_id'    => $value->tour_id,
                                    'agent_name'    => $agent_name,
                                    'departure'     => $flights_detailsS->departure_airport_code,
                                    'arrival'       => $flights_detailsS->arrival_airport_code,
                                    'airline_name'  => $flights_detailsS->other_Airline_Name2,
                                    'flight_no'     => $flights_detailsS->departure_flight_number,
                                    'adult_price'   => $flights_per_person_price,
                                    'child_price'   => $flights_per_child_price,
                                    'infant_price'  => $flights_per_infant_price,
                                    'total_adults'  => $total_adults,
                                    'total_childs'  => $total_childs,
                                    'total_infant'  => $total_infant,
                                    'total_price'   => $total_price,
                                ];
                                if($total_adults <= 0 && $total_childs <= 0 && $total_infant <= 0){
                                }else{
                                    array_push($package_Flights,$PF);
                                }
                            }
                        }else{
                            $flights_per_person_price   = $flight_rute->flights_per_person_price ?? '0';
                            $flights_per_child_price    = $flight_rute->flights_per_child_price ?? '0';
                            $flights_per_infant_price   = $flight_rute->flights_per_infant_price ?? '0';
                            $total_price                = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price;
                            // dd($flights_details);
                            
                            $PF = [
                                'customer_id'   => $value->client_id ?? '',
                                'invoice_no'    => $value->invoice_no ?? '',
                                'booking_id'    => $value->booking_id ?? '',
                                'package_id'    => $value->tour_id ?? '',
                                'agent_name'    => $agent_name ?? '',
                                'departure'     => $flights_details->departure_airport_code ?? '',
                                'arrival'       => $flights_details->arrival_airport_code ?? '',
                                'airline_name'  => $flights_details->other_Airline_Name2 ?? '',
                                'flight_no'     => $flights_details->departure_flight_number ?? '',
                                'adult_price'   => $flight_rute->flights_per_person_price ?? '',
                                'child_price'   => $flight_rute->flights_per_child_price ?? '',
                                'infant_price'  => $flight_rute->flights_per_infant_price ?? '',
                                'total_adults'  => $total_adults ?? '',
                                'total_childs'  => $total_childs ?? '',
                                'total_infant'  => $total_infant ?? '',
                                'total_price'   => $total_price ?? '',
                            ];
                            // array_push($package_Flights,$PF);
                        }
                    }
                }
            }
        }else{
            $package_Flights = '';
        }
        
        $type = 'Yearly';
        
        return view('template/frontend/userdashboard/pages/Atol/Report/atol_Report_Weekly',compact('type','all_Users','package_Flights','flight_invoice','all_countries','addAtolFlightPackage','atol_detail'));
    }
/*
|--------------------------------------------------------------------------
|  Atol Report yearly Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
|  Atol certificate Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display atol Certificate details for our client.
*/    
public function atol_Certificate_Admin(Request $req){
        $all_countries          = country::all();
        $flight_invoice         = DB::table('add_manage_invoices')->whereJsonContains('services',['flights_tab'])->get();
        $atol_detail            = DB::table('addAtol')->get();
        $addAtolFlightPackage   = DB::table('addAtolFlightPackage')->first();
        $all_Users              = DB::table('customer_subcriptions')->get();
        $package_Flights = [];
        
        // Package
        $cart_details           = DB::table('cart_details')->where('client_id',$req->customer_id)->where('pakage_type','tour')->get();
        if(isset($cart_details) && $cart_details != null && $cart_details != ''){
            foreach($cart_details as $value){
                $cart_total_data_E = $value->cart_total_data;
                if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                    $cart_total_data = json_decode($cart_total_data_E);
                    // dd($cart_total_data);
                    
                    if(isset($cart_total_data->double_adults) || isset($cart_total_data->triple_adults) || isset($cart_total_data->quad_adults) || isset($cart_total_data->without_acc_adults)){
                        $total_adults  = $cart_total_data->double_adults + $cart_total_data->triple_adults + $cart_total_data->quad_adults + $cart_total_data->without_acc_adults;   
                    }else{
                        $total_adults = 0;
                    }
                    
                    if(isset($cart_total_data->double_childs) || isset($cart_total_data->triple_childs) || isset($cart_total_data->quad_childs)){
                        $total_childs  = $cart_total_data->double_childs + $cart_total_data->triple_childs + $cart_total_data->quad_childs;
                    }else{
                        $total_childs = 0;
                    }
                    
                    if(isset($cart_total_data->double_infant) || isset($cart_total_data->triple_infant) || isset($cart_total_data->quad_infant)){
                        $total_infant  = $cart_total_data->double_infant + $cart_total_data->triple_infant + $cart_total_data->quad_infant;
                    }else{
                        $total_infant = 0;
                    }
                    
                    $tour_booking_data =  DB::table('tours_bookings')->where('id',$value->booking_id)->select('passenger_detail')->first();;
                    if(isset($tour_booking_data) && $tour_booking_data != null && $tour_booking_data != ''){
                        $passenger_detail   = json_decode($tour_booking_data->passenger_detail);
                        $agent_name         = $passenger_detail[0]->name.' '.$passenger_detail[0]->lname;
                    }else{
                        $agent_name = '';
                    }
                    
                    $tours_data     = DB::table('tours_2')->where('tour_id',$cart_total_data->tourId)->select('tour_id','flight_supplier','flight_route_id_occupied','flights_per_person_price','flights_details')->first();
                    $flight_rute    = DB::table('flight_rute')->where('id',$tours_data->flight_route_id_occupied)->select('id','dep_supplier','flights_per_person_price','flights_per_child_price','flights_per_infant_price')->first();
                    
                    $flights_details_E = $tours_data->flights_details;
                    if(isset($flights_details_E) && $flights_details_E != null && $flights_details_E != ''){
                        $flights_details = json_decode($flights_details_E);
                        if(is_array($flights_details)){
                            foreach($flights_details as $flights_detailsS){
                                // dd($flights_details);
                                
                                if(isset($flight_rute->flights_per_person_price) && $flight_rute->flights_per_person_price != null && $flight_rute->flights_per_person_price != ''){
                                    $flights_per_person_price = $flight_rute->flights_per_person_price;
                                }elseif(isset($tours_data->flights_per_person_price) && $tours_data->flights_per_person_price != null && $tours_data->flights_per_person_price != ''){
                                    $flights_per_person_price = $tours_data->flights_per_person_price;
                                }else{
                                    $flights_per_person_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_child_price) && $flight_rute->flights_per_child_price != null && $flight_rute->flights_per_child_price != ''){
                                    $flights_per_child_price = $flight_rute->flights_per_child_price;
                                }else{
                                    $flights_per_child_price = 0;
                                }
                                
                                if(isset($flight_rute->flights_per_infant_price) && $flight_rute->flights_per_infant_price != null && $flight_rute->flights_per_infant_price != ''){
                                    $flights_per_infant_price = $flight_rute->flights_per_infant_price;
                                }else{
                                    $flights_per_infant_price = 0;
                                }
                                
                                $total_price = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price ?? '0';
                                
                                $PF = [
                                    'customer_id'   => $value->client_id,
                                    'invoice_no'    => $value->invoice_no,
                                    'booking_id'    => $value->booking_id,
                                    'package_id'    => $value->tour_id,
                                    'agent_name'    => $agent_name,
                                    'departure'     => $flights_detailsS->departure_airport_code,
                                    'arrival'       => $flights_detailsS->arrival_airport_code,
                                    'airline_name'  => $flights_detailsS->other_Airline_Name2,
                                    'flight_no'     => $flights_detailsS->departure_flight_number,
                                    'adult_price'   => $flights_per_person_price,
                                    'child_price'   => $flights_per_child_price,
                                    'infant_price'  => $flights_per_infant_price,
                                    'total_adults'  => $total_adults,
                                    'total_childs'  => $total_childs,
                                    'total_infant'  => $total_infant,
                                    'total_price'   => $total_price,
                                ];
                                if($total_adults <= 0 && $total_childs <= 0 && $total_infant <= 0){
                                }else{
                                    array_push($package_Flights,$PF);
                                }
                            }
                        }else{
                            $flights_per_person_price   = $flight_rute->flights_per_person_price ?? '0';
                            $flights_per_child_price    = $flight_rute->flights_per_child_price ?? '0';
                            $flights_per_infant_price   = $flight_rute->flights_per_infant_price ?? '0';
                            $total_price                = $flights_per_person_price + $flights_per_child_price + $flights_per_infant_price;
                            // dd($flights_details);
                            
                            $PF = [
                                'customer_id'   => $value->client_id,
                                'invoice_no'    => $value->invoice_no ?? '',
                                'booking_id'    => $value->booking_id ?? '',
                                'package_id'    => $value->tour_id ?? '',
                                'agent_name'    => $agent_name ?? '',
                                'departure'     => $flights_details->departure_airport_code ?? '',
                                'arrival'       => $flights_details->arrival_airport_code ?? '',
                                'airline_name'  => $flights_details->other_Airline_Name2 ?? '',
                                'flight_no'     => $flights_details->departure_flight_number ?? '',
                                'adult_price'   => $flight_rute->flights_per_person_price ?? '',
                                'child_price'   => $flight_rute->flights_per_child_price ?? '',
                                'infant_price'  => $flight_rute->flights_per_infant_price ?? '',
                                'total_adults'  => $total_adults ?? '',
                                'total_childs'  => $total_childs ?? '',
                                'total_infant'  => $total_infant ?? '',
                                'total_price'   => $total_price ?? '',
                            ];
                            // array_push($package_Flights,$PF);
                        }
                    }
                }
            }
        }else{
            $package_Flights = '';
        }
        
        return view('template/frontend/userdashboard/pages/Atol/atol_Certificate',compact('all_Users','package_Flights','flight_invoice','all_countries','addAtolFlightPackage','atol_detail'));
    }
/*
|--------------------------------------------------------------------------
|  Atol certificate Function Ended
|--------------------------------------------------------------------------
*/    




/*
|--------------------------------------------------------------------------
| AtolController_Admin Function Ended
|--------------------------------------------------------------------------
*/ 
}