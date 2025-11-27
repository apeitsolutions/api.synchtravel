<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Session;
use DateTime;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\flight_rute;
use App\Models\flight_seats_occupied;
use Carbon\Carbon;

class SupplierControllerApi extends Controller
{
    public function createsupplier(Request $request){
        $countries = DB::table('countries')->get();
        return response()->json(['message'=>'success','countries'=>$countries]);
    }
    
    public function addsupplier(Request $request){
        $categories=\DB::table('supplier')->insert([
            'SU_id'                 => $request->SU_id ?? NULL,
            'token'                 => $request->token,
            'customer_id'           => $request->customer_id,
            'country'               => $request->country ?? '',
            'city'                  => $request->city ?? '',
            'companyname'           => $request->companyname,
            'Companyaddress'        => $request->Companyaddress,
            'companyemail'          => $request->companyemail,
            'contactpersonname'     => $request->contactpersonname,
            'contactpersonemail'    => $request->contactpersonemail,
            'personcontactno'       => $request->personcontactno,
            'opening_balance'       => $request->opening_balance,
            'balance'               => $request->opening_balance,
        ]);
        return response()->json(['message'=>'success']);
    }
    
    public function fetchsupplier(Request $request){
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            // $suppliers      = DB::table('supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            $customer_Id    = $request->customer_id;
            $SU_id          = $request->SU_id;
            $suppliers      = DB::table('supplier')
                                ->where(function ($query) use ($customer_Id) {
                                    $query->where('customer_id', $customer_Id);
                                })
                                ->orWhere(function ($query) use ($SU_id) {
                                    $query->where('SU_id', $SU_id);
                                })
                                ->orderBy('id','DESC')
                                ->get();
        }else{
            $suppliers      = DB::table('supplier')->where('customer_id',$request->customer_id)->orderBy('id','DESC')->get();
        }
        
        // dd($suppliers);
        return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]);
    }
    
    public static function flight_supplier_statement_Season_Working($all_data,$request){
        $today_Date             = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            
            $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->created_at)) {
                    return false;
                }
                
                if($record->created_at != null && $record->created_at != ''){
                    $created_at     = Carbon::parse($record->created_at);
                    $start_Date     = Carbon::parse($start_Date);
                    $end_Date       = Carbon::parse($end_Date);
                    return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function flight_supplier_statement(Request $request){
        
        if(isset($request->start_date)){
            $startDate = $request->start_date;
            $endDate = $request->end_date;
             
            $flight_routes = DB::table('flight_rute')->where('dep_supplier',$request->supplier_id)
                                ->select('dep_airline','departure_airline_id','return_airline','return_airline_id','dep_object','flights_per_person_price','flights_number_of_seat',DB::raw('flights_per_person_price * flights_number_of_seat as flight_total_price'))
                                ->get();
        
            
            $supplier_seats_data = [];
            foreach($flight_routes as $route_res){
                $departure_data = json_decode($route_res->dep_object);
    
                $departure_airport = '';
                $arrival_airport = '';
                $created_at = '';
                if(!empty($departure_data)){
                    $departure_airport = $departure_data[0]->departure;
                    $arrival_airport = $departure_data[sizeOf($departure_data) - 1 ]->arival;
                    $created_at = $departure_data[0]->departure_time;
                }
                
                $created_at_date = date('Y-m-d',strtotime($created_at));
                
                if($created_at_date >= $startDate && $created_at_date <= $endDate){
                     $supplier_seats_data[] = (Object)[
                        'supplier_id'=> $request->supplier_id,
                        'departure_airport'=> $departure_airport,
                        'arrival_airport'=> $arrival_airport,
                        'dep_airline'=> $route_res->dep_airline,
                        'return_airline'=> $route_res->return_airline,
                        'departure_airline_id'=> $route_res->departure_airline_id,
                        'return_airline_id'=> $route_res->return_airline_id,
                        'flights_per_person_price'=> $route_res->flights_per_person_price,
                        'flights_number_of_seat'=> $route_res->flights_number_of_seat,
                        'flight_total_price'=> $route_res->flight_total_price,
                        'created_at' => $created_at
                    ];
                }
                
               
            }
         
                $supplier_invoices = DB::table('add_manage_invoices')->where('flight_supplier',$request->supplier_id)
                                                            ->whereDate('created_at','>=', $startDate)
                                                            ->whereDate('created_at','<=', $endDate)
                                                             ->select('id as invoice_no','flight_supplier','flights_Pax_details','return_flights_details','flights_details','flights_details_more','created_at')
                                                             ->get();
            
            
            // die;
            
            $supplier_invoices_arr = [];
                if(isset($supplier_invoices)){
                    foreach($supplier_invoices as $supplier_res){
                        $flights_Pax_details = json_decode($supplier_res->flights_Pax_details);
                        $flights_details = json_decode($supplier_res->flights_details);
                        $return_flights_details = json_decode($supplier_res->return_flights_details);
                        
                        // print_r($flights_details);
                          
                            if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                                
                                
                                foreach($flights_Pax_details as $value){
                                    
                                    $departure_data = [];
                                    if(isset($flights_details) && !empty($flights_details)){
                                        $departure_data = $flights_details;
                                    }
                             
                                   
    
                                    $departure_airport = '';
                                    $arrival_airport = '';
                                    if(!empty($departure_data)){
                                        $departure_airport = $departure_data[0]->departure_airport_code;
                                        $arrival_airport = $departure_data[sizeOf($departure_data) - 1 ]->arrival_airport_code;
                                    }
                                     
                                    $child_price_wi_adult_price = $value->flights_cost_per_seats_adult * $value->flights_child_seats;
                                    $child_price_wi_child_price = $value->flights_cost_per_seats_child * $value->flights_child_seats;
                                    
                                    $infant_price = $value->flights_cost_per_seats_infant * $value->flights_infant_seats;
                                    
                                    $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                                    
                                    $price_difference = $infant_price - $price_diff;
                                    
                                    $supplier_invoices_arr[] = (Object)[
                                            'supplier_id'=> $request->supplier_id,
                                            'booking_type'=> 'Invoice',
                                            'Invoice_no'=> $supplier_res->invoice_no,
                                            'departure_airport'=> $departure_airport,
                                            'arrival_airport'=> $arrival_airport,
                                            'dep_airline'=> $departure_data[0]->other_Airline_Name2 ?? '',
                                            'return_airline'=> $return_flights_details[0]->return_other_Airline_Name2 ?? '',
                                            'flight_route_id_occupied'=> $value->flight_route_id_occupied,
                                            'flights_cost_per_seats_adult'=> $value->flights_cost_per_seats_adult,
                                            'flights_cost_per_seats_child'=> $value->flights_cost_per_seats_child,
                                            'flights_cost_per_seats_infant'=> $value->flights_cost_per_seats_infant,
                                            'flights_adult_seats'=> $value->flights_adult_seats,
                                            'flights_child_seats'=> $value->flights_child_seats,
                                            'flights_infant_seats'=> $value->flights_infant_seats,
                                            'price_difference'=> $price_difference,
                                            'created_at' => $supplier_res->created_at
    
                                        ];
                               
                                }
                            }
                    }
                }
                
                $packages_bookings = DB::table('tours_2')
                                        ->join('cart_details','cart_details.tour_id','=','tours_2.tour_id')
                                        ->join('flight_rute','flight_rute.id','=','tours_2.flight_route_id_occupied')
                                        ->where('tours_2.flight_supplier',$request->supplier_id)
                                        ->whereDate('cart_details.created_at','>=', $startDate)
                                        ->whereDate('cart_details.created_at','<=', $endDate)
                                        ->select('cart_details.invoice_no','cart_details.tour_id','cart_details.cart_total_data','cart_details.created_at',
                                                 'flight_rute.id as flight_route_id_occupied','flight_rute.dep_supplier','flight_rute.flights_per_person_price','flight_rute.flights_per_child_price','flight_rute.flights_per_infant_price',
                                                 'tours_2.flights_details','tours_2.return_flights_details')
                                        ->get();
            
             $supplier_packages_arr = [];
             
                if(isset($packages_bookings)){
                    foreach($packages_bookings as $supplier_res){
                        $cart_res = json_decode($supplier_res->cart_total_data);
                        $flights_details = json_decode($supplier_res->flights_details);
                        $return_flights_details = json_decode($supplier_res->return_flights_details);
                        
                        // print_r($flights_details);
                        
                        
                          
                            if(isset($cart_res) && $cart_res != null && $cart_res != ''){
    
                                    //  Calculate Child Price
                                    
                                    $price_diff = 0;
                                    if($cart_res->total_childs > 0){
                                        $child_price_wi_adult_price = $supplier_res->flights_per_person_price * $cart_res->total_childs;
                                        $child_price_wi_child_price = $supplier_res->flights_per_child_price * $cart_res->total_childs;
                                        $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                                    }
                                    
                                    $infant_price = 0;
                                    if($cart_res->total_Infants > 0){
                                        $infant_price = $supplier_res->flights_per_infant_price * $cart_res->total_Infants;
                                    }
                                    
                                    $price_difference = $infant_price - $price_diff;
                                    
                                    $departure_data = [];
                                    if(isset($flights_details) && !empty($flights_details)){
                                        $departure_data = $flights_details;
                                    }
                             
                                   
    
                                    $departure_airport = '';
                                    $arrival_airport = '';
                                    if(!empty($departure_data)){
                                        $departure_airport = $departure_data[0]->departure_airport_code;
                                        $arrival_airport = $departure_data[sizeOf($departure_data) - 1 ]->arrival_airport_code;
                                    }
                                    
                                    $supplier_packages_arr[] = (Object)[
                                            'supplier_id'=> $request->supplier_id,
                                            'booking_type'=> 'Package',
                                            'Invoice_no'=> $supplier_res->invoice_no,
                                            'departure_airport'=> $departure_airport,
                                            'arrival_airport'=> $arrival_airport,
                                            'dep_airline'=> $departure_data[0]->other_Airline_Name2 ?? '',
                                            'return_airline'=> $return_flights_details[0]->return_other_Airline_Name2 ?? '',
                                            'flight_route_id_occupied'=> $supplier_res->flight_route_id_occupied,
                                            'flights_cost_per_seats_adult'=> $supplier_res->flights_per_person_price,
                                            'flights_cost_per_seats_child'=> $supplier_res->flights_per_child_price,
                                            'flights_cost_per_seats_infant'=> $supplier_res->flights_per_infant_price,
                                            'flights_adult_seats'=> $cart_res->total_adults,
                                            'flights_child_seats'=> $cart_res->total_childs,
                                            'flights_infant_seats'=> $cart_res->total_Infants,
                                            'price_difference'=> $price_difference,
                                            'created_at' => $supplier_res->created_at
    
                                        ];
                               
                                
                            }
                    }
                }
                
             $payments_data = DB::table('recevied_payments_details')
                ->where('Criteria','Flight Supplier')
                ->where('Content_Ids',$request->supplier_id)
                ->whereDate('payment_date','>=', $startDate)
                ->whereDate('payment_date','<=', $endDate)
                ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate",'company_amount')
                ->orderBy('created_at')
                ->get();
                
            $make_payments_data = DB::table('make_payments_details')
                ->where('Criteria','Flight Supplier')
                ->where('Content_Ids',$request->supplier_id)
                ->whereDate('payment_date','>=', $startDate)
                ->whereDate('payment_date','<=', $endDate)
                ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate",'company_amount')
                ->orderBy('created_at')
                ->get();
        }else{
            $flight_routes = DB::table('flight_rute')->where('dep_supplier',$request->supplier_id)
                                    ->select('dep_airline','departure_airline_id','return_airline','return_airline_id','dep_object','flights_per_person_price','flights_number_of_seat',DB::raw('flights_per_person_price * flights_number_of_seat as flight_total_price'))
                                    ->get();
            
            
            $supplier_seats_data = [];
            foreach($flight_routes as $route_res){
                $departure_data = json_decode($route_res->dep_object);
    
                $departure_airport = '';
                $arrival_airport = '';
                $created_at = '';
                if(!empty($departure_data)){
                    $departure_airport = $departure_data[0]->departure;
                    $arrival_airport = $departure_data[sizeOf($departure_data) - 1 ]->arival;
                    $created_at = $departure_data[0]->departure_time;
                }
                
                $supplier_seats_data[] = (Object)[
                        'supplier_id'=> $request->supplier_id,
                        'departure_airport'=> $departure_airport,
                        'arrival_airport'=> $arrival_airport,
                        'dep_airline'=> $route_res->dep_airline,
                        'return_airline'=> $route_res->return_airline,
                        'departure_airline_id'=> $route_res->departure_airline_id,
                        'return_airline_id'=> $route_res->return_airline_id,
                        'flights_per_person_price'=> $route_res->flights_per_person_price,
                        'flights_number_of_seat'=> $route_res->flights_number_of_seat,
                        'flight_total_price'=> $route_res->flight_total_price,
                        'created_at' => $created_at
                    ];
            }
         
                $supplier_invoices = DB::table('add_manage_invoices')->where('flight_supplier',$request->supplier_id)
                                                             ->select('id as invoice_no','flight_supplier','flights_Pax_details','return_flights_details','flights_details','flights_details_more','created_at')
                                                             ->get();
            
            
            // die;
            
            $supplier_invoices_arr = [];
                if(isset($supplier_invoices)){
                    foreach($supplier_invoices as $supplier_res){
                        $flights_Pax_details = json_decode($supplier_res->flights_Pax_details);
                        $flights_details = json_decode($supplier_res->flights_details);
                        $return_flights_details = json_decode($supplier_res->return_flights_details);
                        
                        // print_r($flights_details);
                          
                            if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                                
                                
                                foreach($flights_Pax_details as $value){
                                    
                                    $departure_data = [];
                                    if(isset($flights_details) && !empty($flights_details)){
                                        $departure_data = $flights_details;
                                    }
                             
                                   
    
                                    $departure_airport = '';
                                    $arrival_airport = '';
                                    if(!empty($departure_data)){
                                        $departure_airport = $departure_data[0]->departure_airport_code;
                                        $arrival_airport = $departure_data[sizeOf($departure_data) - 1 ]->arrival_airport_code;
                                    }
                                     
                                    $child_price_wi_adult_price = $value->flights_cost_per_seats_adult * $value->flights_child_seats;
                                    $child_price_wi_child_price = $value->flights_cost_per_seats_child * $value->flights_child_seats;
                                    
                                    $infant_price = $value->flights_cost_per_seats_infant * $value->flights_infant_seats;
                                    
                                    $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                                    
                                    $price_difference = $infant_price - $price_diff;
                                    
                                    $supplier_invoices_arr[] = (Object)[
                                            'supplier_id'=> $request->supplier_id,
                                            'booking_type'=> 'Invoice',
                                            'Invoice_no'=> $supplier_res->invoice_no,
                                            'departure_airport'=> $departure_airport,
                                            'arrival_airport'=> $arrival_airport,
                                            'dep_airline'=> $departure_data[0]->other_Airline_Name2 ?? '',
                                            'return_airline'=> $return_flights_details[0]->return_other_Airline_Name2 ?? '',
                                            'flight_route_id_occupied'=> $value->flight_route_id_occupied,
                                            'flights_cost_per_seats_adult'=> $value->flights_cost_per_seats_adult,
                                            'flights_cost_per_seats_child'=> $value->flights_cost_per_seats_child,
                                            'flights_cost_per_seats_infant'=> $value->flights_cost_per_seats_infant,
                                            'flights_adult_seats'=> $value->flights_adult_seats,
                                            'flights_child_seats'=> $value->flights_child_seats,
                                            'flights_infant_seats'=> $value->flights_infant_seats,
                                            'price_difference'=> $price_difference,
                                            'created_at' => $supplier_res->created_at
    
                                        ];
                               
                                }
                            }
                    }
                }
                
                $packages_bookings = DB::table('tours_2')
                                        ->join('cart_details','cart_details.tour_id','=','tours_2.tour_id')
                                        ->join('flight_rute','flight_rute.id','=','tours_2.flight_route_id_occupied')
                                        ->where('tours_2.flight_supplier',$request->supplier_id)
                                        ->select('cart_details.invoice_no','cart_details.tour_id','cart_details.cart_total_data','cart_details.created_at',
                                                 'flight_rute.id as flight_route_id_occupied','flight_rute.dep_supplier','flight_rute.flights_per_person_price','flight_rute.flights_per_child_price','flight_rute.flights_per_infant_price',
                                                 'tours_2.flights_details','tours_2.return_flights_details')
                                        ->get();
            
             $supplier_packages_arr = [];
             
                if(isset($packages_bookings)){
                    foreach($packages_bookings as $supplier_res){
                        $cart_res = json_decode($supplier_res->cart_total_data);
                        $flights_details = json_decode($supplier_res->flights_details);
                        $return_flights_details = json_decode($supplier_res->return_flights_details);
                        
                        // print_r($flights_details);
                        
                        
                          
                            if(isset($cart_res) && $cart_res != null && $cart_res != ''){
    
                                    //  Calculate Child Price
                                    
                                    $price_diff = 0;
                                    if($cart_res->total_childs > 0){
                                        $child_price_wi_adult_price = $supplier_res->flights_per_person_price * $cart_res->total_childs;
                                        $child_price_wi_child_price = $supplier_res->flights_per_child_price * $cart_res->total_childs;
                                        $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                                    }
                                    
                                    $infant_price = 0;
                                    if($cart_res->total_Infants > 0){
                                        $infant_price = $supplier_res->flights_per_infant_price * $cart_res->total_Infants;
                                    }
                                    
                                    $price_difference = $infant_price - $price_diff;
                                    
                                    $departure_data = [];
                                    if(isset($flights_details) && !empty($flights_details)){
                                        $departure_data = $flights_details;
                                    }
                             
                                   
    
                                    $departure_airport = '';
                                    $arrival_airport = '';
                                    if(!empty($departure_data)){
                                        $departure_airport = $departure_data[0]->departure_airport_code;
                                        $arrival_airport = $departure_data[sizeOf($departure_data) - 1 ]->arrival_airport_code;
                                    }
                                    
                                    $supplier_packages_arr[] = (Object)[
                                            'supplier_id'=> $request->supplier_id,
                                            'booking_type'=> 'Package',
                                            'Invoice_no'=> $supplier_res->invoice_no,
                                            'departure_airport'=> $departure_airport,
                                            'arrival_airport'=> $arrival_airport,
                                            'dep_airline'=> $departure_data[0]->other_Airline_Name2 ?? '',
                                            'return_airline'=> $return_flights_details[0]->return_other_Airline_Name2 ?? '',
                                            'flight_route_id_occupied'=> $supplier_res->flight_route_id_occupied,
                                            'flights_cost_per_seats_adult'=> $supplier_res->flights_per_person_price,
                                            'flights_cost_per_seats_child'=> $supplier_res->flights_per_child_price,
                                            'flights_cost_per_seats_infant'=> $supplier_res->flights_per_infant_price,
                                            'flights_adult_seats'=> $cart_res->total_adults,
                                            'flights_child_seats'=> $cart_res->total_childs,
                                            'flights_infant_seats'=> $cart_res->total_Infants,
                                            'price_difference'=> $price_difference,
                                            'created_at' => $supplier_res->created_at
    
                                        ];
                               
                                
                            }
                    }
                }
                
             $payments_data = DB::table('recevied_payments_details')
                ->where('Criteria','Flight Supplier')
                ->where('Content_Ids',$request->supplier_id)
                ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate",'company_amount')
                ->orderBy('created_at')
                ->get();
                
            $make_payments_data = DB::table('make_payments_details')
                ->where('Criteria','Flight Supplier')
                ->where('Content_Ids',$request->supplier_id)
                ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate",'company_amount')
                ->orderBy('created_at')
                ->get();
        }
        
        $supplier_invoices_arr  = collect($supplier_invoices_arr);
        $supplier_seats_data    = collect($supplier_seats_data);
        $supplier_packages_arr  = collect($supplier_packages_arr);
        $all_data               = $supplier_seats_data->concat($supplier_invoices_arr)->concat($supplier_packages_arr)->concat($payments_data)->concat($make_payments_data)->sortBy('created_at');
        $suppliers_data         = DB::table('supplier')->where('id',$request->supplier_id)->first();
        
        // Season
        $today_Date             = date('Y-m-d');
        $season_Id              = '';
        if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
            $season_Id          = 'all_Seasons';
        }elseif(isset($request->season_Id) && $request->season_Id > 0){
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
            $season_Id          = $season_SD->id ?? '';
        }else{
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            $season_Id          = $season_SD->id ?? '';
        }
        
        $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        if($request->customer_id == 4 || $request->customer_id == 54){
            if($all_data->isEmpty()){
            }else{
                // dd($all_data);
                $all_data   = $this->flight_supplier_statement_Season_Working($all_data,$request);
                // dd($all_data);
            }
        }
        // Season
        
        return response()->json(['message'=>'success','supplier_data'=>$suppliers_data,'statement_data'=>$all_data,'season_Details'=>$season_Details,'season_Id'=>$season_Id]);
    }
    
    public function flight_supplier_ledger(Request $request){
        $customer_id        = $request->customer_id;
        $suppliers_data     = DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$request->supplier_id)->first();
        $suppliers_ledger   = DB::table('flight_supplier_ledger')->where('customer_id',$request->customer_id)->where('supplier_id',$request->supplier_id)->get();
        return response()->json(['message'=>'success','supplier_data'=>$suppliers_data,'ledger_data'=>$suppliers_ledger]);
    }
    
    public function deletesupplier(Request $request){
        $deleted_id = $request->id;
        $suppliers  = DB::table('supplier')->where('id',$deleted_id)->delete();
        if($suppliers = 1){
            return response()->json(['message'=>'success']);   
        }else{
            return response()->json(['message'=>'error']);
        }  
    }
    
    public function editsupplier(Request $request){
        $edited_id = $request->id;
        $suppliers = DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$edited_id)->first();
        $countries = DB::table('countries')->get();
        return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers,'countries'=>$countries]);
    }
    
    public function updatesupplier(Request $request){
        $suppliers = DB::table('supplier')->where('customer_id',$request->customer_id)->where('id',$request->id)->update([
            'country'               => $request->country ?? '',
            'city'                  => $request->city ?? '',
            'companyname'           => $request->companyname,
            'Companyaddress'        => $request->Companyaddress,
            'companyemail'          => $request->companyemail,
            'contactpersonname'     => $request->contactpersonname,
            'contactpersonemail'    => $request->contactpersonemail,
            'personcontactno'       => $request->personcontactno,
            'opening_balance'       => $request->opening_balance,
            'balance'               => $request->opening_balance,
        ]);
        if($suppliers = 1){
            return response()->json(['message'=>'success']);   
        }else{
            return response()->json(['message'=>'error']);
        }
    }
    
    public function fetchsuppliername(Request $request){
        DB::beginTransaction();
        try {
            $supplier_id    = $request->supplier_id;
            $suppliers      = DB::table('supplier')->where('id',$supplier_id)->where('customer_id',$request->customer_id)->first();
            if($suppliers != null){
                DB::commit();
                return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]);
            }else{
                DB::commit();
                return response()->json(['message'=>'error']);
            }
            
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
    
    public function fetchairline (Request $request){
        $airlines = DB::table('airline_name_tb')->where('customer_id',$request->customer_id)->get();
        return response()->json(['message'=>'success','fetchedairline'=>$airlines]);
    }
    
    public function fetchallsupplierforseats(Request $request){
        $suppliers  = DB::table('supplier')->where('customer_id',$request->customer_id)->get();
        $new_arr    = [];
        foreach($suppliers as $key => $suppliersz){
            $type       = [];
            $all_rutes  = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('dep_supplier',$suppliersz->id)->get();
            foreach($all_rutes as $all_rutesz){
                $rute_type_of_supplier = [
                    'multi_rute' => $all_rutesz->dep_flight_type,
                ];
                array_push($type,$rute_type_of_supplier);
            }
            $rute_type_of_supplier = [
               'multi_rute_suplier' => $suppliersz,
               'multi_rute'         => $type,
            ];
            array_push($new_arr,$rute_type_of_supplier);
        }
        return response()->json(['message'=>'success','fetchedsupplier'=>$new_arr]); 
    }
    
    public function createseat(Request $request){
        $flight = DB::table('flight_rute')->insert([
    'customer_id'=> $request->customer_id ?? "",
    'token'=>$request->token ?? "",
    'flight_type'=>$request->flight_type ?? "",
    'selected_flight_airline'=>$request->selected_flight_airline ?? "",
    'supplier'=>$request->supplier ?? "",
    'no_of_stays'=>$request->no_of_stays ?? "",
    'departure_airport_code'=>$request->departure_airport_code ?? "",
    'arrival_airport_code'=>$request->arrival_airport_code ?? "",
    'other_Airline_Name2'=>$request->other_Airline_Name2 ?? "",
    'departure_Flight_Type'=>$request->departure_Flight_Type ?? "",
    'departure_flight_number'=>$request->departure_flight_number ?? "",
    'departure_time'=>$request->departure_time ?? "",
    'arrival_time'=>$request->arrival_time ?? "",
    'total_Time'=>$request->total_Time ?? "",
      'more_departure_airport_code' => $request->more_departure_airport_code ?? "",
      'more_arrival_airport_code' => $request->more_arrival_airport_code ?? "",
      'more_other_Airline_Name2' => $request->more_other_Airline_Name2 ?? "",
      'more_departure_Flight_Type' => $request->more_departure_Flight_Type ?? "",
      'more_departure_flight_number' => $request->more_departure_flight_number ?? "",
      'more_departure_time' => $request->more_departure_time ?? "",
      'more_arrival_time' => $request->more_arrival_time ?? "",
      'more_total_Time' => $request->more_total_Time ?? "",
    
    'return_departure_airport_code'=>$request->return_departure_airport_code ?? "",
    'return_arrival_airport_code'=>$request->return_arrival_airport_code ?? "",
    'return_other_Airline_Name2'=>$request->return_other_Airline_Name2 ?? "",
    'return_departure_Flight_Type'=>$request->return_departure_Flight_Type ?? "",
    'return_departure_flight_number'=>$request->return_departure_flight_number ?? "",
    'return_departure_time'=>$request->return_departure_time ?? "",
    'return_arrival_time'=>$request->return_arrival_time ?? "",
    'return_total_Time'=>$request->return_total_Time ?? "",
    
      "return_more_departure_airport_code" => $request->return_more_departure_airport_code ?? "",
      "return_more_arrival_airport_code" => $request->return_more_arrival_airport_code ?? "",
      "return_more_other_Airline_Name2" => $request->return_more_other_Airline_Name2 ?? "",
      "return_more_departure_Flight_Type" => $request->return_more_departure_Flight_Type ?? "",
      "return_more_departure_flight_number" => $request->return_more_departure_flight_number ?? "",
      "return_more_departure_time" => $request->return_more_departure_time ?? "",
      "return_more_arrival_time" => $request->return_more_arrival_time ?? "",
      "return_more_total_Time" => $request->return_more_total_Time ?? "",
    
    'flights_per_person_price'=>$request->flights_per_person_price ?? "",
    'flights_number_of_seat'=>$request->flights_number_of_seat ?? "",
    'flights_per_seat_price'=>$request->flights_per_seat_price ?? "",
    'flights_per_child_price'=>$request->flights_per_child_price ?? "",
    'flights_per_infant_price'=>$request->flights_per_infant_price ?? "",
    'flight_total_price'=>$request->flights_total_price ?? "",
    'connected_flights_duration_details'=>$request->connected_flights_duration_details ?? "",
    'terms_and_conditions'=>$request->terms_and_conditions ?? "",
    
          ]);
        if($flight = 1){
     return response()->json(['message'=>'success']);   
        }else{
            return response()->json(['message'=>'error']);
        }
    }
    
    public function createseat1(Request $request){
        // dd('ok');
        DB::beginTransaction();
        try {
            $route_id           = DB::table('flight_rute')->insertGetId([
                'SU_id'                     => $request->SU_id ?? NULL,
                
                'customer_id'               => $request->customer_id ?? "",
                'token'                     => $request->token ?? "",
                
                'departure_airline_id'      => $request->departure_airline_id ?? "",
                'dep_supplier'              => $request->dep_supplier ?? "",
                'dep_flight_type'           => $request->dep_flight_type ?? "",
                'dep_airline'               => $request->dep_airline ?? "",
                'dep_no_of_stay'            => $request->dep_no_of_stay ?? "",
                'dep_object'                => $request->dep_object ?? "",
                
                'return_airline_id'         => $request->return_airline_id ?? "",
                'return_supplier'           => $request->return_supplier ?? "",
                'return_flight_type'        => $request->return_flight_type ?? "",
                'return_airline'            => $request->return_airline ?? "",
                'return_no_of_stay'         => $request->return_no_of_stay ?? "",
                'return_object'             => $request->return_object ?? "",
                
                'flights_per_person_price'  => $request->flights_per_person_price ?? "",
                'flights_number_of_seat'    => $request->flights_number_of_seat ?? "",
                'flights_per_seat_price'    => $request->flights_per_seat_price ?? "",
                'flights_per_child_price'   => $request->flights_per_child_price ?? "",
                'flights_per_infant_price'  => $request->flights_per_infant_price ?? "",
                'flight_total_price'        => $request->flights_total_price ?? "",
                
                'connected_flights_duration_details'    => $request->connected_flights_duration_details ?? "",
                'terms_and_conditions'                  => $request->terms_and_conditions ?? "",
            ]);
            $supplier_data      = DB::table('supplier')->where('id',$request->dep_supplier)->first();
            $supplier_balance   = $supplier_data->balance + $request->flights_total_price;
                
            DB::table('flight_supplier_ledger')->insert([
                'SU_id'=> $request->SU_id ?? NULL,
                'supplier_id'=>$supplier_data->id,
                'payment'=>$request->flights_total_price,
                'balance'=>$supplier_balance,
                'route_id'=>$route_id,
                'date'=>date('Y-m-d'),
                'customer_id'=>$request->customer_id,
                'total_seats'=>$request->flights_number_of_seat,
                'adult_price'=>$request->flights_per_person_price,
                'child_price'=>$request->flights_per_child_price,
                'infant_price'=>$request->flights_per_infant_price,
            ]);
            
            DB::table('supplier')->where('id',$request->dep_supplier)->update(['balance'=>$supplier_balance]);
            
            DB::commit();
            
            return response()->json(['message'=>'success']);
            
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }

        
    }
    
    public function fetchseat_Old(Request $request){
        $customer_id    = $request->customer_id;
        $suppliers      = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
        return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]);
    }
    
    public function fetchseat(Request $request){
        // dd($request);
        
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $suppliers_flight   = DB::table('flight_rute')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
        }else{
            $suppliers_flight   = DB::table('flight_rute')->where('customer_id',$request->customer_id)->get();
        }
        
        // dd($suppliers_flight);
        
        $tours_arr          = [];
        foreach($suppliers_flight as $flight){
            $total_adults   = 0;
            $total_childs   = 0;
            $total_infants  = 0;
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                $tours  = DB::table('tours')->where('SU_id',$request->SU_id)->where('flight_id',$flight->id)->select('id')->get();
            }else{
                $tours  = DB::table('tours')->where('flight_id',$flight->id)->select('id')->get();
            }
            foreach($tours as $tourf){
                $cart_details = DB::table('cart_details')->where('tour_id',$tourf->id)->get();
                foreach($cart_details as $cart_detailsz){
                    $cart_data      = json_decode($cart_detailsz->cart_total_data);
                    $total_adults   += $cart_data->double_adults + $cart_data->triple_adults + $cart_data->quad_adults + $cart_data->without_acc_adults;
                    $total_childs   +=$cart_data->double_childs + $cart_data->triple_childs + $cart_data->quad_childs + $cart_data->children;
                    $total_infants  +=$cart_data->double_infant + $cart_data->triple_infant + $cart_data->quad_infant + $cart_data->infants;
                }
            }
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                $flights_occupied = DB::table('flight_seats_occupied')->where('SU_id',$request->SU_id)->where('token',$request->token)->where('flight_route_id',$flight->id)->count();
            }else{
                $flights_occupied = DB::table('flight_seats_occupied')->where('token',$request->token)->where('flight_route_id',$flight->id)->count();
            }
            
            // $supplier_id    = $flight->supplier_id;
            // $suppliers      = DB::table('supplier')->where('id',$supplier_id)->where('customer_id',$request->customer_id)->first();
            
            $flightpaxcount = [
                'flight_id'         => $flight->id, 
                'totaladults'       => $total_adults, 
                'totalchilds'       => $total_childs,
                'totalinfants'      => $total_infants,
                'flights_occupied'  => $flights_occupied,
                // 'companyname'       => $suppliers->companyname ?? '',
                // 'wallet_amount'     => $suppliers->wallet_amount ?? '',
            ];
            array_push($tours_arr,$flightpaxcount);
        }
        
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $suppliers_flight = flight_rute::with('flight_seats_occupied')->where('SU_id',$request->SU_id)->where('flight_rute.customer_id', $request->customer_id)->get();
        }else{
            $suppliers_flight = flight_rute::with('flight_seats_occupied')->where('flight_rute.customer_id', $request->customer_id)->get();
        }
        
        // dd($suppliers_flight);
        
        return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers_flight,'tours_arr'=>$tours_arr]);
    }
    
    public function deleteseat(Request $request){
        DB::beginTransaction();
        try {
            $flight_Details     = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            // dd($flight_Details);
            $flights_total      = $flight_Details->flights_number_of_seat * $flight_Details->flights_per_person_price;
            $supplier_Details   = DB::table('supplier')->where('id',$flight_Details->dep_supplier)->first();
            $supplier_Balance   = $supplier_Details->balance - $flights_total;
            DB::table('supplier')->where('id',$flight_Details->dep_supplier)->update(['balance'=>$supplier_Balance]);
            
            DB::table('flight_supplier_ledger')->insert([
                'SU_id'         => $request->SU_id ?? NULL,
                'supplier_id'   => $supplier_Details->id,
                'payment'       => $flight_Details->flight_total_price,
                'balance'       => $supplier_Balance,
                'route_id'      => $request->id,
                'date'          => date('Y-m-d'),
                'customer_id'   => $supplier_Details->customer_id,
                'total_seats'   => $flight_Details->flights_number_of_seat,
                'adult_price'   => $flight_Details->flights_per_person_price,
                'child_price'   => $flight_Details->flights_per_child_price,
                'infant_price'  => $flight_Details->flights_per_infant_price,
                'remarks'       => 'Delete Seat',
            ]);
            
            $flight_rute        = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$request->id)->delete();
            
            DB::commit();
            if($flight_rute = 1){
                return response()->json(['message'=>'success']);   
            }else{
                return response()->json(['message'=>'error']);
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function editseat(Request $request){
        $flights_details        = DB::table('flight_rute')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
        $flight_seats_occupied  = DB::table('flight_seats_occupied')->where('token',$request->token)->where('flight_route_id',$request->id)->sum('flight_route_seats_occupied');
        
        $suppliers          = DB::table('supplier')->where('customer_id',$request->customer_id)->get();
        $flight_supplier    = [];
        foreach($suppliers as $key => $suppliersz){
            $type           = [];
            $all_rutes      = DB::table('flight_rute')->where('dep_supplier',$suppliersz->id)->get();
            foreach($all_rutes as $all_rutesz){
                $rute_type_of_supplier = [
                   'multi_rute' => $all_rutesz->dep_flight_type,
                ];
                array_push($type,$rute_type_of_supplier);
            }
            $rute_type_of_supplier = [   
                'multi_rute_suplier'    => $suppliersz,
                'multi_rute'            => $type,
            ];
            array_push($flight_supplier,$rute_type_of_supplier);
        }
        return response()->json(['message'=>'success','flight_supplier'=>$flight_supplier,'flights_details'=>$flights_details,'flight_seats_occupied'=>$flight_seats_occupied]);
    }
    
    public function updateseat(Request $request){
        DB::beginTransaction(); 
        try {
            $previous_Flight                            = DB::table('flight_rute')->where('id',$request->id)->first();
            $flights_total_Previous                     = $previous_Flight->flights_number_of_seat * $previous_Flight->flights_per_person_price;
            $supplier_data_Previous                     = DB::table('supplier')->where('id',$previous_Flight->dep_supplier)->first();
            $supplier_balance_Previous                  = $supplier_data_Previous->balance - $flights_total_Previous;
            // dd($previous_Flight,$supplier_data_Previous,$supplier_balance_Previous);
            DB::table('supplier')->where('id',$supplier_data_Previous->id)->update(['balance'=>$supplier_balance_Previous]);
            
            $route_id                                   = DB::table('flight_rute')->where('id',$request->id)->update([
                'dep_supplier'                          => $request->dep_supplier,
                'dep_flight_type'                       => $request->dep_flight_type,
                'dep_airline'                           => $request->dep_airline,
                'dep_no_of_stay'                        => $request->dep_no_of_stay,
                'dep_object'                            => $request->dep_object,
                
                'return_supplier'                       => $request->return_supplier,
                'return_flight_type'                    => $request->return_flight_type,
                'return_airline'                        => $request->return_airline,
                'return_no_of_stay'                     => $request->return_no_of_stay,
                'return_object'                         => $request->return_object,
                
                'flights_per_person_price'              => $request->flights_per_person_price,
                'flights_number_of_seat'                => $request->flights_number_of_seat,
                'flights_per_seat_price'                => $request->flights_per_seat_price,
                'flights_per_child_price'               => $request->flights_per_child_price,
                'flights_per_infant_price'              => $request->flights_per_infant_price,
                'flight_total_price'                    => $request->flights_total_price,
                
                'connected_flights_duration_details'    => $request->connected_flights_duration_details ?? "",
                'terms_and_conditions'                  => $request->terms_and_conditions ?? "",
            ]);
            
            $flights_total_New                          = $request->flights_number_of_seat * $request->flights_per_person_price;
            $supplier_data_New                          = DB::table('supplier')->where('id',$request->dep_supplier)->first();
            $supplier_balance_New                       = $supplier_data_New->balance + $flights_total_New;
            // dd($supplier_balance_New);
            DB::table('supplier')->where('id',$supplier_data_New->id)->update(['balance'=>$supplier_balance_New]);
            
            DB::table('flight_supplier_ledger')->insert([
                'SU_id'                                 => $request->SU_id ?? NULL,
                'supplier_id'                           => $supplier_data_New->id,
                'payment'                               => $request->flights_total_price,
                'balance'                               => $supplier_balance_New,
                'route_id'                              => $request->id,
                'date'                                  => date('Y-m-d'),
                'customer_id'                           => $supplier_data_New->customer_id,
                'total_seats'                           => $request->flights_number_of_seat,
                'adult_price'                           => $request->flights_per_person_price,
                'child_price'                           => $request->flights_per_child_price,
                'infant_price'                          => $request->flights_per_infant_price,
                'remarks'                               => 'Update Seat',
            ]);
            
            // dd($route_id);
            
            DB::commit();
            // if($route_id = 1){
                return response()->json(['message'=>'success']);
            // }else{
            //     return response()->json(['message'=>'error']);
            // }
            
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function get_flights_occupancy(Request $request){
        DB::beginTransaction(); 
        try {
            $flight_Occupancy = DB::table('flight_seats_occupied')->where('token',$request->token)->where('flight_route_id',$request->flight_route_id)->get();
            if(count($flight_Occupancy) > 0){
                return response()->json(['message'=>'success']);
            }else{
                return response()->json(['message'=>'error']);
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function supplier_wallet_trans(Request $request){
        $suppliers_trans = DB::table('flight_supplier_wallet_trans')->where('customer_id',$request->customer_id)->where('supplier_id',$request->supplier_id)->get();
        return response()->json(['message'=>'success','suppliers_trans'=>$suppliers_trans]);
    }
    
    public function save_flight_payment_recieved_and_remaining(Request $req){
    // dd("in admin");
    // print_r($req->all());
    // die;
  
  
    DB::beginTransaction();
                        try {
                            
                                  $flightid= $req->flightId;
                                  
                                  $payment_routine_array = [];
                                  
                                  $flight_data = \DB::table('flight_rute')->where('id',$flightid)->first();
                      
                                $total_amount = $flight_data->flight_total_price;
                                $paid_amount = $flight_data->flight_paid_amount;
                                $over_paid_amount = $flight_data->over_paid_amount;
                                
                                $total_paid_amount = $paid_amount + $req->amount_paid;
                                $total_over_paid = 0;
                                $over_paid_am = 0;
                                if($total_paid_amount > $total_amount){
                                    $over_paid_am = $total_paid_amount - $total_amount;
                                    $total_over_paid = $over_paid_amount + $over_paid_am;
                                    
                                    $total_paid_amount = $total_paid_amount - $over_paid_am;
                                }
                                
                                DB::table('flight_rute')->where('id',$flightid)->update([
                                    'flight_paid_amount' => $total_paid_amount,
                                    'over_paid_amount' => $total_over_paid,
                                ]);
                                
                                $supplier_data = DB::table('supplier')->where('id',$flight_data->supplier)->select('id','wallet_amount')->first();
                                $supplier_wallet_am = $supplier_data->wallet_amount + $over_paid_am;
                                DB::table('supplier')->where('id',$flight_data->supplier)->update(['wallet_amount'=>$supplier_wallet_am]);
                                
                                
                                if($over_paid_am != 0){
                                       DB::table('flight_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                    'balance'=>$supplier_wallet_am,
                                                                                    'flight_id'=>$req->flightId,
                                                                                    'supplier_id'=>$flight_data->supplier,
                                                                                    'date'=>$req->date,
                                                                                     ]);
                                }
                             
                                
                                if($req->payment_method == 'Wallet'){
                                    $supplier_data = DB::table('supplier')->where('id',$flight_data->supplier)->select('id','wallet_amount')->first();
                                    $supplier_wallet_am = $supplier_data->wallet_amount - $req->amount_paid;
                                    DB::table('supplier')->where('id',$flight_data->supplier)->update(['wallet_amount'=>$supplier_wallet_am]);
                                    
                                    DB::table('flight_supplier_wallet_trans')->insert(['payment_am'=>$req->amount_paid,
                                                                                'balance'=>$supplier_wallet_am,
                                                                                'flight_id'=>$req->flightId,
                                                                                'supplier_id'=>$flight_data->supplier,
                                                                                'date'=>$req->date,
                                                                                 ]);
                                }
                                
                               
                                
                    //   dd($flight_data);
                      if($flight_data != null){
                          if($flight_data->amount_paidandremaining_of_flight){
                              $payment_routine = $flight_data->amount_paidandremaining_of_flight;
                              $payment_data = json_decode($payment_routine);
                            //   dd($payment_data);
                              
                              array_push($payment_data,$req->all());
                              $payment_routine_array = $payment_data;
                          }else{
                              array_push($payment_routine_array,$req->all());
                            //   $payment_routine_array = [$req->all()];
                          }
                      
                      }
                      
                      
                    //   dd($payment_routine_array);
                      
                      
                      
                      
                      $suppliers=\DB::table('flight_rute')->where('id',$flightid)->update([
                          
                    'amount_paidandremaining_of_flight'=>json_encode($payment_routine_array),
                    
                          ]);
          
                            DB::commit();
                            
                            return response()->json(['status'=>'success','message'=>'Balance is Updated Successfully']);
                        } catch (\Exception $e) {
                            DB::rollback();
                            echo $e;die;
                            return response()->json(['status'=>'error','message'=>'Something Went Wrong try Again']);
                            // something went wrong
                        }
                        
                        
    if($suppliers = 1){
     return response()->json(['message'=>'success']);   
    }else{
        return response()->json(['message'=>'error']);
    }
     
}
    
    public function get_suppliers_flights_detail(Request $req){
        $suppliers = DB::table('flight_rute')->where('customer_id',$req->customer_id)->where('dep_supplier',$req->supplierId)->get();
        return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]); 
    } 
    
    public function get_suppliers_flights_rute(Request $req){
        // dd($req->flight_id);
        $suppliers=\DB::table('flight_rute')->where('id',$req->flight_id)->first();
          return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]); 
         
    }
    
    public function view_seat_occupancy(Request $req){
        // dd($req->flight_id);
        $suppliers=\DB::table('tours')->where('flight_id',$req->flight_id)->get();
          return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]); 
         
    }
    
    public function invoice_for_occupancy(Request $req){
        // dd($req->tour_id);
        $invoice = \DB::table('tours_bookings')
        ->join('cart_details','cart_details.booking_id','tours_bookings.id')
        ->where('cart_details.tour_id',$req->tour_id)->get();
          return response()->json(['message'=>'success','fetchedsupplier'=>$invoice]); 
         
    }
    
    public function fetchflightrate(Request $req){
        // dd($req->tourId);
        $invoice = \DB::table('tours_2')
        ->where('tour_id',$req->tourId)
        ->first();
          return response()->json(['message'=>'success','fetchedsupplier'=>$invoice]); 
         
    }
    
    public function pax_details(Request $req){
        // dd($req->tourId);
        $invoice = \DB::table('tours_bookings')
        ->where('invoice_no',$req->invoice_no)
        ->first();
          return response()->json(['message'=>'success','fetchedsupplier'=>$invoice]); 
         
    }
    
    function getBetweenDates($startDate, $endDate){
        $rangArray  = [];
        $startDate  = strtotime($startDate);
        $endDate    = strtotime($endDate);
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
            $date           = date('Y-m-d', $currentDate);
            $rangArray[]    = $date;
        }
        return $rangArray;
    }
    
    public function fetchallhotels(Request $req){
        if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
            $hotel = DB::table('hotels')->where('SU_id',$req->SU_id)->where('owner_id',$req->customer_id)->get();
        }else{
            $hotel = DB::table('hotels')->where('owner_id',$req->customer_id)->get();
        }
        return response()->json(['message'=>'success','fetchedsupplier'=>$hotel]);
    }
    
    public function all_Hotels_Availability(Request $req){
        if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
            $hotel      = DB::table('hotels')->where('SU_id',$req->SU_id)->where('owner_id',$req->customer_id)->get();
            $room_Types = DB::table('rooms_types')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        }else{
            $hotel      = DB::table('hotels')->where('owner_id',$req->customer_id)->get();
            $room_Types = DB::table('rooms_types')->where('customer_id',$req->customer_id)->get();
        }
        
        // dd($hotel);
        $city_Details = [];
        if($hotel->isEmpty()){
        }else{
            foreach($hotel as $val_HD){
                if(isset($val_HD->property_country) && $val_HD->property_country != null && $val_HD->property_country != ''){
                    $country    = DB::table('countries')->where('name',$val_HD->property_country)->first();
                    if(isset($val_HD->property_city) && $val_HD->property_city != null && $val_HD->property_city != ''){
                        $city       = DB::table('cities')->where('country_id',$country->id)->where('name',$val_HD->property_city)->first();
                        if ($city) {
                            $city_Details[] = $city;
                        }
                    }
                }
            }
            $city_Details = collect($city_Details);
            if ($city_Details->isEmpty()) {
            } else {
                $city_Details = $city_Details->unique('name')->values()->toArray();
            }
        }
        
        return response()->json(['message'=>'success','fetchedsupplier'=>$hotel,'room_Types'=>$room_Types,'city_Details'=>$city_Details]);
    }
    
    public function fetchhotelrecord_14May2024(Request $req){
        // dd('STOP');
        
        $hotel          = $req->hotel_id;
        $from           = $req->startDate;
        $upto           = $req->includingDate;
        $stop_date      = $upto;
        $stop_date      = date('Y-m-d', strtotime($stop_date . ' +1 day'));
        
        $all_suppliers  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$req->customer_id)->get();
        $all_types      = DB::table('rooms_types')->where('customer_id',$req->customer_id)->get();
        
        if($req->hotel_id != 'all_Hotels'){
            $all_rooms      = DB::table('rooms')
                                ->where('hotel_id',$hotel)->where('availible_from', ">=" ,$from)->where('availible_to', "<=", $stop_date)->where('owner_id',$req->customer_id)->get();
            // dd($all_rooms);
        }else{
            $all_rooms      = DB::table('rooms')
                                ->where('availible_from', ">=" ,$from)->where('availible_to', "<=", $stop_date)->where('owner_id',$req->customer_id)->get();
            // dd($all_rooms);
        }
        
        $booked_room    = DB::table('hotel_booking')->where('check_in', ">" ,$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->whereJsonContains('hotel_checkavailability',['id'=>$hotel])->where('hotel_reservation','confirmed')->get();
      
        $dates = $this->getBetweenDates($from, $upto);
        
        $loop_Count             = 0;
        $all_suppliers_records  = [];
        foreach($all_suppliers as $supkey1 => $supplier){
            $supplier_id_found  = false;
            $rooms_types_arr    = [];
            $hotel_Name_Arr     = [];
            foreach($all_types as $skey1 => $all_typez){
                $all_dates_record=[];
                $room_type_found = false;
                foreach($dates as $key1 => $date){
                    $total_rooms_type_count     = 0;
                    $total_website_booked_count = 0;
                    $total_admin_booked_count   = 0;
                    $total_inhouse_count        = 0;
                    $total_stay_count           = 0;
                    $total_checkIN_count        = 0;
                    $total_checkOUT_count       = 0;
                    $total_package_count        = 0;
                    $total_package_booked_count = 0;
                    foreach($all_rooms as $skey1 => $all_booking_singlez){
                        $hotel_Name_Arr = [];
                        $hotel_Name     = DB::table('hotels')->where('owner_id',$req->customer_id)->where('id',$all_booking_singlez->hotel_id)->select('property_name')->first();
                        // dd($hotel_Name);
                        
                        $loop_Count++;
                        if($all_booking_singlez->room_supplier_name == $supplier->id && $date >= $all_booking_singlez->availible_from && $date <= $all_booking_singlez->availible_to && $all_booking_singlez->room_type_cat == $all_typez->id){
                            $total_rooms_type_count += $all_booking_singlez->quantity;
                            $rooms_booking_details  = DB::table('rooms_bookings_details')->where('room_id',$all_booking_singlez->id)->get();
                            if(isset($rooms_booking_details) && !empty($rooms_booking_details)){
                                $booking_details = $rooms_booking_details;
                                if(isset($booking_details)){
                                    foreach($booking_details as $skey1 => $booking_detailz){
                                        if($booking_detailz->booking_from == 'website' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_website_booked_count += $booking_detailz->quantity;
                                            
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            }
                                        }
                                        
                                        if($booking_detailz->booking_from == 'Invoices' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_admin_booked_count   += $booking_detailz->quantity;
                                              
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            } 
                                        }
                                        
                                        if($booking_detailz->booking_from == 'package' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_package_booked_count += $booking_detailz->quantity;
                                            
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            }
                                        }
                                        
                                    }
                                }
                            }
                            $supplier_id_found  = true;
                            $room_type_found    = true;
                        }
                    }
                    
                    $one_type_day_room = [
                        'date'                  => $date,
                        'hotel_Name'            => $hotel_Name->property_name ?? '',
                        'supplier_name'         => $supplier->room_supplier_name,
                        'room_type_name'        => $all_typez->room_type,
                        'room_type_id'          => $all_typez->id,
                        'total_rooms'           => $total_rooms_type_count,
                        'total_Inhouse'         => $total_stay_count,
                        'remaining_room'        => $total_rooms_type_count - $total_inhouse_count,
                        'total_admin_booked'    => $total_admin_booked_count,
                        'total_website_booked'  => $total_website_booked_count,
                        'total_package_booked'  => $total_package_booked_count,
                        'total_checkIN_count'   => $total_checkIN_count,
                        'total_checkOUT_count'  => $total_checkOUT_count,
                    ];
                    array_push($all_dates_record,$one_type_day_room);
                }
                
                array_push($hotel_Name_Arr,$hotel_Name->property_name ?? '');
                
                $one_type_record_data = [
                    'hotel_Name_Arr'    => $hotel_Name_Arr[0],
                    'type'              => $all_typez->room_type,
                    'rooms_record'      => $all_dates_record,
                ];
                    
                if($room_type_found){
                    array_push($rooms_types_arr,$one_type_record_data); 
                }
            }
            
            $single_supplier_arr = [
                'supplier_id'       => $supplier->id,
                'supplier_record'   => $supplier->room_supplier_name,
                'rooms_data'        => $rooms_types_arr
            ];
            
            if($supplier_id_found){
                array_push($all_suppliers_records,$single_supplier_arr);
            }
        }
        
        // dd($loop_Count);
        
        return [
            "dates"         => $dates,
            "rooms_records" => $all_suppliers_records,
            'loop_Count'    => $loop_Count,
        ];
    }
    
    public function fetchhotelrecord(Request $req){
        // dd('STOP');
        
        $hotel          = $req->hotel_id;
        $from           = $req->startDate;
        $upto           = $req->includingDate;
        $stop_date      = $upto;
        $stop_date      = date('Y-m-d', strtotime($stop_date . ' +1 day'));
        
        if($req->hotel_id != 'all_Hotels'){
            $all_rooms  = DB::table('rooms')
                            ->join('hotels','rooms.hotel_id','hotels.id')
                            ->where('rooms.hotel_id',$hotel)
                            ->where('rooms.availible_from', ">=" ,$from)
                            ->where('rooms.availible_to', "<=", $stop_date)
                            ->where('rooms.owner_id',$req->customer_id)
                            ->get();
        }else{
            $all_rooms  = DB::table('rooms')
                            ->join('hotels','rooms.hotel_id','hotels.id')
                            ->where('rooms.availible_from', ">=" ,$from)
                            ->where('rooms.availible_to', "<=", $stop_date)
                            ->where('rooms.owner_id',$req->customer_id)
                            ->get();
        }
        
        $booked_room    = DB::table('hotel_booking')->where('check_in', ">" ,$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->whereJsonContains('hotel_checkavailability',['id'=>$hotel])->where('hotel_reservation','confirmed')->get();
      
        $dates          = $this->getBetweenDates($from, $upto);
        $suppliers_Arr  = [];
        $types_Arr      = [];
        
        foreach($all_rooms as $val_All_Rooms){
            $single_Supplier  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$req->customer_id)->where('id',$val_All_Rooms->room_supplier_name)->first();
            $single_Type      = DB::table('rooms_types')->where('customer_id',$req->customer_id)->where('id',$val_All_Rooms->room_type_cat)->first();
            
            array_push($suppliers_Arr,$single_Supplier);
            array_push($types_Arr,$single_Type);
        }
        
        $all_suppliers   = collect($suppliers_Arr)->unique('id')->values()->all();
        $all_types       = collect($types_Arr)->unique('id')->values()->all();
        
        $loop_Count             = 0;
        $all_suppliers_records  = [];
        
        foreach($all_suppliers as $supkey1 => $supplier){
            $supplier_id_found  = false;
            $rooms_types_arr    = [];
            $hotel_Name_Arr     = '';
            foreach($all_types as $skey1 => $all_typez){
                $all_dates_record   = [];
                $room_type_found    = false;
                foreach($dates as $key1 => $date){
                    $total_rooms_type_count     = 0;
                    $total_website_booked_count = 0;
                    $total_admin_booked_count   = 0;
                    $total_inhouse_count        = 0;
                    $total_stay_count           = 0;
                    $total_checkIN_count        = 0;
                    $total_checkOUT_count       = 0;
                    $total_package_count        = 0;
                    $total_package_booked_count = 0;
                    foreach($all_rooms as $skey1 => $all_booking_singlez){
                        
                        $loop_Count++;
                        if($all_booking_singlez->room_supplier_name == $supplier->id && $date >= $all_booking_singlez->availible_from && $date <= $all_booking_singlez->availible_to && $all_booking_singlez->room_type_cat == $all_typez->id){
                            $total_rooms_type_count += $all_booking_singlez->quantity;
                            $rooms_booking_details  = DB::table('rooms_bookings_details')->where('room_id',$all_booking_singlez->id)->get();
                            if(isset($rooms_booking_details) && !empty($rooms_booking_details)){
                                $booking_details = $rooms_booking_details;
                                if(isset($booking_details)){
                                    foreach($booking_details as $skey1 => $booking_detailz){
                                        
                                        if($booking_detailz->booking_from == 'website' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_website_booked_count += $booking_detailz->quantity;
                                            
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            }
                                        }
                                        
                                        if($booking_detailz->booking_from == 'Invoices' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_admin_booked_count   += $booking_detailz->quantity;
                                              
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            } 
                                        }
                                        
                                        if($booking_detailz->booking_from == 'package' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_package_booked_count += $booking_detailz->quantity;
                                            
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            }
                                        }
                                    }
                                }
                            }
                            $supplier_id_found  = true;
                            $room_type_found    = true;
                            $hotel_Name_Arr     = $all_booking_singlez->property_name;
                        }
                    }
                    
                    $one_type_day_room = [
                        'date'                  => $date,
                        'hotel_Name'            => $hotel_Name_Arr,
                        'supplier_name'         => $supplier->room_supplier_name,
                        'room_type_name'        => $all_typez->room_type,
                        'room_type_id'          => $all_typez->id,
                        'total_rooms'           => $total_rooms_type_count,
                        'total_Inhouse'         => $total_stay_count,
                        'remaining_room'        => $total_rooms_type_count - $total_inhouse_count,
                        'total_admin_booked'    => $total_admin_booked_count,
                        'total_website_booked'  => $total_website_booked_count,
                        'total_package_booked'  => $total_package_booked_count,
                        'total_checkIN_count'   => $total_checkIN_count,
                        'total_checkOUT_count'  => $total_checkOUT_count,
                    ];
                    array_push($all_dates_record,$one_type_day_room);
                }
                
                $one_type_record_data = [
                    'hotel_Name_Arr'    => $hotel_Name_Arr,
                    'type'              => $all_typez->room_type,
                    'rooms_record'      => $all_dates_record,
                ];
                
                if($room_type_found){
                    array_push($rooms_types_arr,$one_type_record_data); 
                }
            }
            
            $single_supplier_arr = [
                'supplier_id'       => $supplier->id,
                'supplier_record'   => $supplier->room_supplier_name,
                'rooms_data'        => $rooms_types_arr
            ];
            
            if($supplier_id_found){
                array_push($all_suppliers_records,$single_supplier_arr);
            }
        }
        
        return [
            "dates"         => $dates,
            "rooms_records" => $all_suppliers_records,
            'loop_Count'    => $loop_Count,
        ];
    }
    
    function get_Dates($startDate, $endDate) {
        $rangArray = [];
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
        
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += 86400) {
            $date           = date('Y-m-d', $currentDate);
            $day            = date('l', $currentDate);
            $rangArray[]    = [
                'date'      => $date,
                'day'       => $day,
            ];
        }
        return $rangArray;
    }
    
    public function chart_Data_All_Hotels_OG(Request $req){
        // dd('STOP');
        
        $hotel          = $req->hotel_id;
        $from           = $req->startDate;
        $upto           = $req->includingDate;
        $city           = $req->city;
        $room_Types     = $req->room_Types;
        $stop_date      = $upto;
        $stop_date      = date('Y-m-d', strtotime($stop_date . ' +1 day'));
        
        $dates          = $this->get_Dates($from, $upto);
        // dd($dates);
        
        if($req->hotel_id != 'all_Hotels'){
            $hotel_Data = DB::table('hotels')->where('owner_id',$req->customer_id)->where('id',$req->hotel_id)->get();
        }else{
            if($req->city != 'all_Cities'){
                $hotel_Data = DB::table('hotels')->where('owner_id',$req->customer_id)->where('property_city',$req->city)->get();
            }else{
                $hotel_Data = DB::table('hotels')->where('owner_id',$req->customer_id)->get();
            }
        }
        
        $hotel_Details = [];
        
        if($hotel_Data->isEmpty()){
        }else{
            foreach($hotel_Data as $val_HD){
                $room_Details   = [];
                foreach($dates as $val_Dates){
                    if($req->room_Types != 'all_Room_Types'){
                        $all_rooms  = DB::table('rooms')
                                        ->where('rooms.hotel_id', $val_HD->id)
                                        ->where('rooms.owner_id', $req->customer_id)
                                        ->where('rooms.room_type_name', $req->room_Types)
                                        ->where(function ($query) use ($val_Dates) {
                                            $query->where('availible_from', '<=', $val_Dates['date']) // Changed '<=' to '<'
                                                  ->where('availible_to', '>', $val_Dates['date']);
                                        })
                                        ->get();
                    }else{
                        $all_rooms  = DB::table('rooms')
                                        ->where('rooms.hotel_id', $val_HD->id)
                                        ->where('rooms.owner_id', $req->customer_id)
                                        ->where(function ($query) use ($val_Dates) {
                                            $query->where('availible_from', '<=', $val_Dates['date']) // Changed '<=' to '<'
                                                  ->where('availible_to', '>', $val_Dates['date']);
                                        })
                                        ->get();
                    }
                    
                    $total_Rooms    = 0;
                    $booked_Rooms   = 0;
                    foreach($all_rooms as $val_RD){
                        $total_Rooms        += $val_RD->quantity;
                        $booking_Id         = [];
                        $hotels_bookings    = DB::table('hotels_bookings')
                                                ->where('provider','Custome_hotel')
                                                ->where('customer_id',$req->customer_id)
                                                ->get();
                        if($hotels_bookings->isEmpty()){
                        }else{
                            foreach($hotels_bookings as $val_BD){
                                $reservation_request    = json_decode($val_BD->reservation_request);
                                $reservation_response   = json_decode($val_BD->reservation_response);
                                
                                if(isset($reservation_response->hotel_details)){
                                    $hotel_details  = $reservation_response->hotel_details;
                                    if(isset($hotel_details->rooms)){
                                        $rooms      = $hotel_details->rooms;
                                        foreach($rooms as $val_Rooms){
                                            if($val_RD->id == $val_Rooms->room_code && $val_Dates['date'] >= $hotel_details->checkIn && $val_Dates['date'] < $hotel_details->checkOut){
                                                $booked_Rooms       += 1;
                                            }else{
                                                if($val_RD->id == $val_Rooms->room_code){
                                                    $check_SRB  = DB::table('rooms')
                                                                    ->where('id', $val_Rooms->room_code)
                                                                    ->where('rooms.hotel_id', $val_HD->id)
                                                                    ->where('rooms.owner_id', $req->customer_id)
                                                                    ->where(function ($query) use ($hotel_details) {
                                                                        $query->where(function ($innerQuery) use ($hotel_details) {
                                                                            // Condition 1: checkIn is within range
                                                                            $innerQuery->where('availible_from', '<=', $hotel_details->checkIn)
                                                                                       ->where('availible_to', '>=', $hotel_details->checkIn);
                                                                        })
                                                                        ->orWhere(function ($innerQuery) use ($hotel_details) {
                                                                            // Condition 2: checkOut is within range
                                                                            $innerQuery->where('availible_from', '<=', $hotel_details->checkOut)
                                                                                       ->where('availible_to', '>=', $hotel_details->checkOut);
                                                                        });
                                                                    })
                                                                    ->first();
                                                    if($check_SRB != null){
                                                        $booked_Rooms       += 1;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    $room_Data = [
                        'total_Rooms'       => $total_Rooms,
                        'booked_Rooms'      => $booked_Rooms,
                    ];
                    array_push($room_Details,$room_Data);
                }
                
                $hotel_Data                 = [
                    'hotel_Name'            => $val_HD->property_name,
                    'room_Details'          => $val_HD->property_name,
                    'room_Details'          => $room_Details,
                ];
                array_push($hotel_Details,$hotel_Data);
            }
            
            // dd($hotel_Details);
        }
        
        return [
            'dates'         => $dates,
            'hotel_Details' => $hotel_Details,
        ];
    }
    
    public function chart_Data_All_Hotels(Request $req){
        $hotel          = $req->hotel_id;
        $from           = $req->startDate;
        $upto           = $req->includingDate;
        $city           = $req->city;
        $room_Types     = $req->room_Types;
        $stop_date      = $upto;
        $stop_date      = date('Y-m-d', strtotime($stop_date . ' +1 day'));
        
        $dates          = $this->get_Dates($from, $upto);
        
        if($req->hotel_id != 'all_Hotels'){
            $hotel_Data = DB::table('hotels')->where('owner_id',$req->customer_id)->where('id',$req->hotel_id)->get();
        }else{
            if($req->city != 'all_Cities'){
                $hotel_Data = DB::table('hotels')->where('owner_id',$req->customer_id)->where('property_city',$req->city)->get();
            }else{
                $hotel_Data = DB::table('hotels')->where('owner_id',$req->customer_id)->get();
            }
        }
        
        // dd($hotel_Data);
        
        $hotel_Details = [];
        
        if($hotel_Data->isEmpty()){
        }else{
            foreach($hotel_Data as $val_HD){
                $room_Details   = [];
                
                foreach ($dates as $val_Dates) {
                    if ($req->room_Types != 'all_Room_Types') {
                        $all_rooms  = DB::table('rooms')
                                        ->where('rooms.hotel_id', $val_HD->id)
                                        ->where('rooms.owner_id', $req->customer_id)
                                        ->where('rooms.room_type_name', $req->room_Types)
                                        ->where(function ($query) use ($val_Dates) {
                                            $query->where('availible_from', '<=', $val_Dates['date'])
                                                  ->where('availible_to', '>', $val_Dates['date']);
                                        })
                                        ->get();
                    } else {
                        $all_rooms  = DB::table('rooms')
                                        ->where('rooms.hotel_id', $val_HD->id)
                                        ->where('rooms.owner_id', $req->customer_id)
                                        ->where(function ($query) use ($val_Dates) {
                                            $query->where('availible_from', '<=', $val_Dates['date'])
                                                  ->where('availible_to', '>', $val_Dates['date']);
                                        })
                                        ->get();
                    }
                    
                    $total_Rooms    = 0;
                    $booked_Rooms   = 0;
                    $booking_Id     = [];
                    foreach ($all_rooms as $val_RD) {
                        $total_Rooms                += $val_RD->quantity;
                        $hotels_bookings            = DB::table('hotels_bookings')->where('customer_id', $req->customer_id)->where('provider', 'Custome_hotel')
                                                        ->whereRaw('LOWER(status) = ?', ['confirmed'])->get();
                        foreach ($hotels_bookings as $val_BD) {
                            $reservation_request    = json_decode($val_BD->reservation_request);
                            $reservation_response   = json_decode($val_BD->reservation_response);
                            if (isset($reservation_response->hotel_details)) {
                                $hotel_details      = $reservation_response->hotel_details;
                                
                                if(isset($hotel_details->merge_Rooms) && $hotel_details->merge_Rooms != null && $hotel_details->merge_Rooms != ''){
                                    foreach ($hotel_details->merge_Rooms as $val_Rooms) {
                                        if($val_RD->id == $val_Rooms->room_code &&  $val_Dates['date'] >= $hotel_details->checkIn && $val_Dates['date'] < $hotel_details->checkOut){
                                            $booked_Rooms += 1;
                                            array_push($booking_Id,$val_BD->invoice_no);
                                        }
                                    }
                                }
                                else{
                                    if (isset($hotel_details->rooms)) {
                                        foreach ($hotel_details->rooms as $val_Rooms) {
                                            if($val_RD->id == $val_Rooms->room_code &&  $val_Dates['date'] >= $hotel_details->checkIn && $val_Dates['date'] < $hotel_details->checkOut){
                                                $booked_Rooms += 1;
                                                array_push($booking_Id,$val_BD->invoice_no);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                
                    $room_Data = [
                        'total_Rooms'   => $total_Rooms,
                        'booked_Rooms'  => $booked_Rooms,
                        'booking_Id'    => $booking_Id,
                    ];
                    array_push($room_Details, $room_Data);
                }
                
                $hotel_Data                 = [
                    'hotel_Name'            => $val_HD->property_name,
                    'room_Details'          => $room_Details,
                ];
                array_push($hotel_Details,$hotel_Data);
            }
            
            // dd($hotel_Details);
        }
        
        return [
            'dates'         => $dates,
            'hotel_Details' => $hotel_Details,
        ];
    }
    
    public function fetchhotelrecord_test(Request $req){
        // dd('STOP');
        
        // $supplier       = DB::table('rooms_Invoice_Supplier')->where('customer_id',$req->customer_id)->where('id',$all_booking_singlez->room_supplier_name)->first();
        // $all_typez      = DB::table('rooms_types')->where('customer_id',$req->customer_id)->where('id',$all_booking_singlez->room_type_cat)->first();
        
        $hotel          = $req->hotel_id;
        $from           = $req->startDate;
        $upto           = $req->includingDate;
        $stop_date      = $upto;
        $stop_date      = date('Y-m-d', strtotime($stop_date . ' +1 day'));
        
        // $all_suppliers  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$req->customer_id)->get();
        // $all_types      = DB::table('rooms_types')->where('customer_id',$req->customer_id)->get();
        
        if($req->hotel_id != 'all_Hotels'){
            // $all_rooms  = DB::table('rooms')->where('hotel_id',$hotel)->where('availible_from', ">=" ,$from)->where('availible_to', "<=", $stop_date)->where('owner_id',$req->customer_id)->get();
            
            $all_rooms  = DB::table('rooms')
                            ->join('hotels','rooms.hotel_id','hotels.id')
                            ->where('rooms.hotel_id',$hotel)
                            ->where('rooms.availible_from', ">=" ,$from)
                            ->where('rooms.availible_to', "<=", $stop_date)
                            ->where('rooms.owner_id',$req->customer_id)
                            ->get();
            // dd($all_rooms);
        }else{
            // $all_rooms  = DB::table('rooms')->where('availible_from', ">=" ,$from)->where('availible_to', "<=", $stop_date)->where('owner_id',$req->customer_id)->get();
            
            $all_rooms  = DB::table('rooms')
                            ->join('hotels','rooms.hotel_id','hotels.id')
                            ->where('rooms.availible_from', ">=" ,$from)
                            ->where('rooms.availible_to', "<=", $stop_date)
                            ->where('rooms.owner_id',$req->customer_id)
                            ->get();
            
            // dd($all_rooms);
        }
        
        $booked_room    = DB::table('hotel_booking')->where('check_in', ">" ,$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->whereJsonContains('hotel_checkavailability',['id'=>$hotel])->where('hotel_reservation','confirmed')->get();
        
        $dates          = $this->getBetweenDates($from, $upto);
        $suppliers_Arr  = [];
        $types_Arr      = [];
        
        foreach($all_rooms as $val_All_Rooms){
            $single_Supplier  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$req->customer_id)->where('id',$val_All_Rooms->room_supplier_name)->first();
            $single_Type      = DB::table('rooms_types')->where('customer_id',$req->customer_id)->where('id',$val_All_Rooms->room_type_cat)->first();
            
            array_push($suppliers_Arr,$single_Supplier);
            array_push($types_Arr,$single_Type);
        }
        
        $all_suppliers   = collect($suppliers_Arr)->unique('id')->values()->all();
        $all_types       = collect($types_Arr)->unique('id')->values()->all();
        
        $loop_Count             = 0;
        $all_suppliers_records  = [];
        foreach($all_suppliers as $supkey1 => $supplier){
            $supplier_id_found  = false;
            $rooms_types_arr    = [];
            $hotel_Name_Arr     = '';
            foreach($all_types as $skey1 => $all_typez){
                $all_dates_record   = [];
                $room_type_found    = false;
                foreach($dates as $key1 => $date){
                    $total_rooms_type_count     = 0;
                    $total_website_booked_count = 0;
                    $total_admin_booked_count   = 0;
                    $total_inhouse_count        = 0;
                    $total_stay_count           = 0;
                    $total_checkIN_count        = 0;
                    $total_checkOUT_count       = 0;
                    $total_package_count        = 0;
                    $total_package_booked_count = 0;
                    foreach($all_rooms as $skey1 => $all_booking_singlez){
                        
                        // $hotel_Name_Arr = [];
                        // $hotel_Name     = DB::table('hotels')->where('owner_id',$req->customer_id)->where('id',$all_booking_singlez->hotel_id)->select('property_name')->first();
                        
                        $loop_Count++;
                        if($all_booking_singlez->room_supplier_name == $supplier->id && $date >= $all_booking_singlez->availible_from && $date <= $all_booking_singlez->availible_to && $all_booking_singlez->room_type_cat == $all_typez->id){
                            $total_rooms_type_count += $all_booking_singlez->quantity;
                            $rooms_booking_details  = DB::table('rooms_bookings_details')->where('room_id',$all_booking_singlez->id)->get();
                            if(isset($rooms_booking_details) && !empty($rooms_booking_details)){
                                $booking_details = $rooms_booking_details;
                                if(isset($booking_details)){
                                    foreach($booking_details as $skey1 => $booking_detailz){
                                        
                                        if($booking_detailz->booking_from == 'website' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_website_booked_count += $booking_detailz->quantity;
                                            
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            }
                                        }
                                        
                                        if($booking_detailz->booking_from == 'Invoices' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_admin_booked_count   += $booking_detailz->quantity;
                                              
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            } 
                                        }
                                        
                                        if($booking_detailz->booking_from == 'package' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_package_booked_count += $booking_detailz->quantity;
                                            
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            }
                                        }
                                    }
                                }
                            }
                            $supplier_id_found  = true;
                            $room_type_found    = true;
                            $hotel_Name_Arr     = $all_booking_singlez->property_name;
                        }
                    }
                    
                    $one_type_day_room = [
                        'date'                  => $date,
                        'hotel_Name'            => $hotel_Name_Arr,
                        'supplier_name'         => $supplier->room_supplier_name,
                        'room_type_name'        => $all_typez->room_type,
                        'room_type_id'          => $all_typez->id,
                        'total_rooms'           => $total_rooms_type_count,
                        'total_Inhouse'         => $total_stay_count,
                        'remaining_room'        => $total_rooms_type_count - $total_inhouse_count,
                        'total_admin_booked'    => $total_admin_booked_count,
                        'total_website_booked'  => $total_website_booked_count,
                        'total_package_booked'  => $total_package_booked_count,
                        'total_checkIN_count'   => $total_checkIN_count,
                        'total_checkOUT_count'  => $total_checkOUT_count,
                    ];
                    array_push($all_dates_record,$one_type_day_room);
                }
                
                // array_push($hotel_Name_Arr,$hotel_Name);
                
                $one_type_record_data = [
                    'hotel_Name_Arr'    => $hotel_Name_Arr,
                    'type'              => $all_typez->room_type,
                    'rooms_record'      => $all_dates_record,
                ];
                
                if($room_type_found){
                    array_push($rooms_types_arr,$one_type_record_data); 
                }
            }
            
            $single_supplier_arr = [
                'supplier_id'       => $supplier->id,
                'supplier_record'   => $supplier->room_supplier_name,
                'rooms_data'        => $rooms_types_arr
            ];
            
            if($supplier_id_found){
                array_push($all_suppliers_records,$single_supplier_arr);
            }
        }
        
        // dd($loop_Count);
        
        return [
            "dates"         => $dates,
            "rooms_records" => $all_suppliers_records,
            'loop_Count'    => $loop_Count,
        ];
    }
    
    function get_Between_Dates($startDate, $endDate, $dates_Fetch){
        $rangArray  = [];
        $startDate  = strtotime($startDate);
        $endDate    = strtotime($endDate);
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
            $date           = date('Y-m-d', $currentDate);
            $rangArray[]    = $date;
        }
        return $rangArray;
    }

    public function chart_data_Ajax(Request $req){
        $dates_Fetch    = $req->dates_Fetch;
        $hotel          = $req->hotel_id;
        $from           = $req->startDate;
        $upto           = $req->includingDate;
        $stop_date      = $upto;
        $stop_date      = date('Y-m-d', strtotime($stop_date . ' +1 day'));
        
        $all_suppliers  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$req->customer_id)->get();
        $all_types      = DB::table('rooms_types')->where('customer_id',$req->customer_id)->get();
        
        if($req->hotel_id != 'all_Hotels'){
            $all_rooms  = DB::table('rooms')->where('hotel_id',$hotel)->where('availible_from', ">=" ,$from)->where('availible_to', "<=", $stop_date)->where('owner_id',$req->customer_id)->get();
        }else{
            $all_rooms  = DB::table('rooms')->where('availible_from', ">=" ,$from)->where('availible_to', "<=", $stop_date)->where('owner_id',$req->customer_id)->get();
        }
        
        $booked_room    = DB::table('hotel_booking')->where('check_in', ">" ,$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->whereJsonContains('hotel_checkavailability',['id'=>$hotel])->where('hotel_reservation','confirmed')->get();
        
        // $dates  = [];
        // $dates1 = $this->get_Between_Dates($from, $upto, $dates_Fetch);
        // for($x=$dates_Fetch; $x<$dates_Fetch + 5; $x++){
        //     $dates[] = $dates1[$x];
        // }
        
        $dates = $this->get_Between_Dates($from, $upto, $dates_Fetch);
        
        $loop_Count             = 0;
        $all_suppliers_records  = [];
        foreach($all_suppliers as $supkey1 => $supplier){
            $supplier_id_found  = false;
            $rooms_types_arr    = [];
            $hotel_Name_Arr     = [];
            foreach($all_types as $skey1 => $all_typez){
                $all_dates_record=[];
                $room_type_found = false;
                foreach($dates as $key1 => $date){
                    $total_rooms_type_count     = 0;
                    $total_website_booked_count = 0;
                    $total_admin_booked_count   = 0;
                    $total_inhouse_count        = 0;
                    $total_stay_count           = 0;
                    $total_checkIN_count        = 0;
                    $total_checkOUT_count       = 0;
                    $total_package_count        = 0;
                    $total_package_booked_count = 0;
                    foreach($all_rooms as $skey1 => $all_booking_singlez){
                        $hotel_Name_Arr = [];
                        $hotel_Name     = DB::table('hotels')->where('owner_id',$req->customer_id)->where('id',$all_booking_singlez->hotel_id)->select('property_name')->first();
                        // dd($hotel_Name);
                        
                        if($all_booking_singlez->room_supplier_name == $supplier->id && $date >= $all_booking_singlez->availible_from && $date <= $all_booking_singlez->availible_to && $all_booking_singlez->room_type_cat == $all_typez->id){
                            $total_rooms_type_count += $all_booking_singlez->quantity;
                            $rooms_booking_details  = DB::table('rooms_bookings_details')->where('room_id',$all_booking_singlez->id)->get();
                            if(isset($rooms_booking_details) && !empty($rooms_booking_details)){
                                $booking_details = $rooms_booking_details;
                                if(isset($booking_details)){
                                    foreach($booking_details as $skey1 => $booking_detailz){
                                        
                                        $loop_Count++;
                                        
                                        if($booking_detailz->booking_from == 'website' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_website_booked_count += $booking_detailz->quantity;
                                            
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            }
                                        }
                                        
                                        if($booking_detailz->booking_from == 'Invoices' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_admin_booked_count   += $booking_detailz->quantity;
                                              
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            } 
                                        }
                                        
                                        if($booking_detailz->booking_from == 'package' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_package_booked_count += $booking_detailz->quantity;
                                            
                                            if($date == $booking_detailz->check_in){
                                                // $total_checkIN_count ++;
                                                $total_checkIN_count = $booking_detailz->quantity;
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                // $total_checkOUT_count ++;
                                                $total_checkOUT_count = $booking_detailz->quantity;
                                            } 
                                            if($date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                // $total_stay_count ++;
                                                $total_stay_count = $booking_detailz->quantity;
                                            }
                                        }
                                        
                                    }
                                }
                            }
                            $supplier_id_found  = true;
                            $room_type_found    = true;
                        }
                    }
                    
                    $one_type_day_room = [
                        'date'                  => $date,
                        'hotel_Name'            => $hotel_Name->property_name,
                        'supplier_name'         => $supplier->room_supplier_name,
                        'room_type_name'        => $all_typez->room_type,
                        'room_type_id'          => $all_typez->id,
                        'total_rooms'           => $total_rooms_type_count,
                        'total_Inhouse'         => $total_stay_count,
                        'remaining_room'        => $total_rooms_type_count - $total_inhouse_count,
                        'total_admin_booked'    => $total_admin_booked_count,
                        'total_website_booked'  => $total_website_booked_count,
                        'total_package_booked'  => $total_package_booked_count,
                        'total_checkIN_count'   => $total_checkIN_count,
                        'total_checkOUT_count'  => $total_checkOUT_count,
                    ];
                    array_push($all_dates_record,$one_type_day_room);
                }
                
                array_push($hotel_Name_Arr,$hotel_Name->property_name);
                
                $one_type_record_data = [
                    'hotel_Name_Arr'    => $hotel_Name_Arr[0],
                    'type'              => $all_typez->room_type,
                    'rooms_record'      => $all_dates_record,
                ];
                    
                if($room_type_found){
                    array_push($rooms_types_arr,$one_type_record_data); 
                }
            }
            
            $single_supplier_arr = [
                'supplier_id'       => $supplier->id,
                'supplier_record'   => $supplier->room_supplier_name,
                'rooms_data'        => $rooms_types_arr
            ];
            
            if($supplier_id_found){
                array_push($all_suppliers_records,$single_supplier_arr);
            }
        }
        
        return [
            "dates"         => $dates,
            "rooms_records" => $all_suppliers_records,
            'loop_Count'    => $loop_Count,
        ];
    }
    
    public function chart_data_Room(Request $req){
        
        $hotel          = $req->hotel_id;
        $from           = $req->startDate;
        $upto           = $req->includingDate;
        
        $stop_date      = $upto;
        $stop_date      = date('Y-m-d', strtotime($stop_date . ' +1 day'));
        
        $all_suppliers  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$req->customer_id)->get();
        $all_types      = DB::table('rooms_types')->where('customer_id',$req->customer_id)->get();
        $all_rooms      = DB::table('rooms')
                            ->where('hotel_id',$hotel)
                            ->where('availible_from', ">=" ,$from)
                            ->where('availible_to', "<=", $stop_date)
                            ->where('owner_id',$req->customer_id)
                            ->get();
        $booked_room    = DB::table('hotel_booking')->where('check_in', ">" ,$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->whereJsonContains('hotel_checkavailability',['id'=>$hotel])->where('hotel_reservation','confirmed')->get();
        
        function getBetweenDates($startDate, $endDate){
            $rangArray  = [];
            $startDate  = strtotime($startDate);
            $endDate    = strtotime($endDate);
            for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                $date = date('Y-m-d', $currentDate);
                $rangArray[] = $date;
            }
                return $rangArray;
        }
    
        $dates                  = getBetweenDates($from, $upto);
        $all_suppliers_records  = [];
        foreach($all_suppliers as $supkey1 => $supplier){
            $supplier_id_found  = false;
            $rooms_types_arr    = [];
            foreach($all_types as $skey1 => $all_typez){
                $all_dates_record   = [];
                $room_type_found    = false;
                foreach($dates as $key1 => $date){
                    $total_rooms_type_count     = 0;  
                    $total_website_booked_count = 0;
                    $total_admin_booked_count   = 0;
                    $total_inhouse_count        = 0;
                    $total_stay_count           = 0;
                    $total_checkIN_count        = 0;
                    $total_checkOUT_count       = 0;
                    $total_package_count        = 0;
                    $total_package_booked_count = 0;
                    $total_price                = 0;
                    foreach($all_rooms as $skey1 => $all_booking_singlez){
                        if($all_booking_singlez->room_supplier_name == $supplier->id && $date >= $all_booking_singlez->availible_from && $date <= $all_booking_singlez->availible_to  && $all_booking_singlez->room_type_cat == $all_typez->id){
                            $total_rooms_type_count     += $all_booking_singlez->quantity;
                            $rooms_booking_details      = DB::table('rooms_bookings_details')->where('room_id',$all_booking_singlez->id)->get();
                            if(isset($rooms_booking_details) && !empty($rooms_booking_details)){
                                $booking_details = $rooms_booking_details;
                                if(isset($booking_details)){
                                    foreach($booking_details as $skey1 => $booking_detailz){
                                        if($booking_detailz->booking_from == 'website' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_website_booked_count += $booking_detailz->quantity;
                                            if($date == $booking_detailz->check_in){
                                                $total_checkIN_count ++;
                                                
                                                if(isset($all_booking_singlez->price_all_days) && $all_booking_singlez->price_all_days != null && $all_booking_singlez->price_all_days != ''){
                                                    $total_price = $all_booking_singlez->price_all_days;
                                                }else{
                                                    $weekdays_price = $all_booking_singlez->weekdays_price;
                                                    $weekdays       = $all_booking_singlez->weekdays;
                                                    if($weekdays != null && $weekdays != ''){
                                                        $weekdays1       = json_decode($weekdays);
                                                        foreach($weekdays1 as $weekdaysValue){
                                                            if($weekdaysValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Monday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Friday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $weekends_price = $all_booking_singlez->weekends_price;
                                                    $weekends       = $all_booking_singlez->weekends;
                                                    if($weekends != null && $weekends != ''){
                                                        $weekends1       = json_decode($weekends);
                                                        foreach($weekends1 as $weekendValue){
                                                            if($weekendValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Monday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Friday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if($date == $booking_detailz->check_out){
                                                $total_checkOUT_count ++;
                                                
                                                if(isset($all_booking_singlez->price_all_days) && $all_booking_singlez->price_all_days != null && $all_booking_singlez->price_all_days != ''){
                                                    $total_price = $all_booking_singlez->price_all_days;
                                                }else{
                                                    $weekdays_price = $all_booking_singlez->weekdays_price;
                                                    $weekdays       = $all_booking_singlez->weekdays;
                                                    if($weekdays != null && $weekdays != ''){
                                                        $weekdays1       = json_decode($weekdays);
                                                        foreach($weekdays1 as $weekdaysValue){
                                                            if($weekdaysValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Monday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Friday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $weekends_price = $all_booking_singlez->weekends_price;
                                                    $weekends       = $all_booking_singlez->weekends;
                                                    if($weekends != null && $weekends != ''){
                                                        $weekends1       = json_decode($weekends);
                                                        foreach($weekends1 as $weekendValue){
                                                            if($weekendValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Monday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Friday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                $total_stay_count ++;
                                                
                                                if(isset($all_booking_singlez->price_all_days) && $all_booking_singlez->price_all_days != null && $all_booking_singlez->price_all_days != ''){
                                                    $total_price = $all_booking_singlez->price_all_days;
                                                }else{
                                                    $weekdays_price = $all_booking_singlez->weekdays_price;
                                                    $weekdays       = $all_booking_singlez->weekdays;
                                                    if($weekdays != null && $weekdays != ''){
                                                        $weekdays1       = json_decode($weekdays);
                                                        foreach($weekdays1 as $weekdaysValue){
                                                            if($weekdaysValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Monday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Friday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $weekends_price = $all_booking_singlez->weekends_price;
                                                    $weekends       = $all_booking_singlez->weekends;
                                                    if($weekends != null && $weekends != ''){
                                                        $weekends1       = json_decode($weekends);
                                                        foreach($weekends1 as $weekendValue){
                                                            if($weekendValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Monday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Friday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        
                                        if($booking_detailz->booking_from == 'Invoices' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                         
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_admin_booked_count   += $booking_detailz->quantity;
                                            
                                            if($date == $booking_detailz->check_in){
                                                $total_checkIN_count ++;
                                                
                                                if(isset($all_booking_singlez->price_all_days) && $all_booking_singlez->price_all_days != null && $all_booking_singlez->price_all_days != ''){
                                                    $total_price = $all_booking_singlez->price_all_days;
                                                }else{
                                                    $weekdays_price = $all_booking_singlez->weekdays_price;
                                                    $weekdays       = $all_booking_singlez->weekdays;
                                                    if($weekdays != null && $weekdays != ''){
                                                        $weekdays1       = json_decode($weekdays);
                                                        foreach($weekdays1 as $weekdaysValue){
                                                            if($weekdaysValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Monday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Friday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $weekends_price = $all_booking_singlez->weekends_price;
                                                    $weekends       = $all_booking_singlez->weekends;
                                                    if($weekends != null && $weekends != ''){
                                                        $weekends1       = json_decode($weekends);
                                                        foreach($weekends1 as $weekendValue){
                                                            if($weekendValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Monday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Friday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                }
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                $total_checkOUT_count ++;
                                                
                                                if(isset($all_booking_singlez->price_all_days) && $all_booking_singlez->price_all_days != null && $all_booking_singlez->price_all_days != ''){
                                                    $total_price = $all_booking_singlez->price_all_days;
                                                }else{
                                                    $weekdays_price = $all_booking_singlez->weekdays_price;
                                                    $weekdays       = $all_booking_singlez->weekdays;
                                                    if($weekdays != null && $weekdays != ''){
                                                        $weekdays1       = json_decode($weekdays);
                                                        foreach($weekdays1 as $weekdaysValue){
                                                            if($weekdaysValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Monday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Friday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $weekends_price = $all_booking_singlez->weekends_price;
                                                    $weekends       = $all_booking_singlez->weekends;
                                                    if($weekends != null && $weekends != ''){
                                                        $weekends1       = json_decode($weekends);
                                                        foreach($weekends1 as $weekendValue){
                                                            if($weekendValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Monday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Friday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                }
                                            } 
                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                $total_stay_count ++;
                                                
                                                if(isset($all_booking_singlez->price_all_days) && $all_booking_singlez->price_all_days != null && $all_booking_singlez->price_all_days != ''){
                                                    $total_price = $all_booking_singlez->price_all_days;
                                                }else{
                                                    $weekdays_price = $all_booking_singlez->weekdays_price;
                                                    $weekdays       = $all_booking_singlez->weekdays;
                                                    if($weekdays != null && $weekdays != ''){
                                                        $weekdays1       = json_decode($weekdays);
                                                        foreach($weekdays1 as $weekdaysValue){
                                                            if($weekdaysValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Monday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Friday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $weekends_price = $all_booking_singlez->weekends_price;
                                                    $weekends       = $all_booking_singlez->weekends;
                                                    if($weekends != null && $weekends != ''){
                                                        $weekends1       = json_decode($weekends);
                                                        foreach($weekends1 as $weekendValue){
                                                            if($weekendValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Monday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Friday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        
                                        if($booking_detailz->booking_from == 'package' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                            $total_inhouse_count        += $booking_detailz->quantity;
                                            $total_package_booked_count += $booking_detailz->quantity;
                                            if($date == $booking_detailz->check_in){
                                                $total_checkIN_count ++;
                                                
                                                if(isset($all_booking_singlez->price_all_days) && $all_booking_singlez->price_all_days != null && $all_booking_singlez->price_all_days != ''){
                                                    $total_price = $all_booking_singlez->price_all_days;
                                                }else{
                                                    $weekdays_price = $all_booking_singlez->weekdays_price;
                                                    $weekdays       = $all_booking_singlez->weekdays;
                                                    if($weekdays != null && $weekdays != ''){
                                                        $weekdays1       = json_decode($weekdays);
                                                        foreach($weekdays1 as $weekdaysValue){
                                                            if($weekdaysValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Monday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Friday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $weekends_price = $all_booking_singlez->weekends_price;
                                                    $weekends       = $all_booking_singlez->weekends;
                                                    if($weekends != null && $weekends != ''){
                                                        $weekends1       = json_decode($weekends);
                                                        foreach($weekends1 as $weekendValue){
                                                            if($weekendValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Monday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Friday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                }
                                            } 
                                            if($date == $booking_detailz->check_out){
                                                $total_checkOUT_count ++;
                                                
                                                if(isset($all_booking_singlez->price_all_days) && $all_booking_singlez->price_all_days != null && $all_booking_singlez->price_all_days != ''){
                                                    $total_price = $all_booking_singlez->price_all_days;
                                                }else{
                                                    $weekdays_price = $all_booking_singlez->weekdays_price;
                                                    $weekdays       = $all_booking_singlez->weekdays;
                                                    if($weekdays != null && $weekdays != ''){
                                                        $weekdays1       = json_decode($weekdays);
                                                        foreach($weekdays1 as $weekdaysValue){
                                                            if($weekdaysValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Monday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Friday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $weekends_price = $all_booking_singlez->weekends_price;
                                                    $weekends       = $all_booking_singlez->weekends;
                                                    if($weekends != null && $weekends != ''){
                                                        $weekends1       = json_decode($weekends);
                                                        foreach($weekends1 as $weekendValue){
                                                            if($weekendValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Monday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Friday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                }
                                            } 
                                            if($date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                $total_stay_count ++;
                                                
                                                if(isset($all_booking_singlez->price_all_days) && $all_booking_singlez->price_all_days != null && $all_booking_singlez->price_all_days != ''){
                                                    $total_price = $all_booking_singlez->price_all_days;
                                                }else{
                                                    $weekdays_price = $all_booking_singlez->weekdays_price;
                                                    $weekdays       = $all_booking_singlez->weekdays;
                                                    if($weekdays != null && $weekdays != ''){
                                                        $weekdays1       = json_decode($weekdays);
                                                        foreach($weekdays1 as $weekdaysValue){
                                                            if($weekdaysValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Monday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Friday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else if($weekdaysValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekdays_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $weekends_price = $all_booking_singlez->weekends_price;
                                                    $weekends       = $all_booking_singlez->weekends;
                                                    if($weekends != null && $weekends != ''){
                                                        $weekends1       = json_decode($weekends);
                                                        foreach($weekends1 as $weekendValue){
                                                            if($weekendValue == 'Sunday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Monday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Tuesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Wednesday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Thursday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Friday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else if($weekendValue == 'Saturday'){
                                                                $total_price  = $total_price + $weekends_price;
                                                            }else{
                                                                $total_price  = $total_price;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $supplier_id_found  = true;
                            $room_type_found    = true;
                        }
                    }
                    
                    $one_type_day_room = [
                        'date'                  => $date,
                        'supplier_name'         => $supplier->room_supplier_name,
                        'room_type_name'        => $all_typez->room_type,
                        'room_type_id'          => $all_typez->id,
                        'total_rooms'           => $total_rooms_type_count,
                        'total_Inhouse'         => $total_stay_count,
                        'remaining_room'        => $total_rooms_type_count - $total_inhouse_count,
                        'total_price'           => $total_price,
                        'total_admin_booked'    => $total_admin_booked_count,
                        'total_website_booked'  => $total_website_booked_count,
                        'total_package_booked'  => $total_package_booked_count,
                        'total_checkIN_count'   => $total_checkIN_count,
                        'total_checkOUT_count'  => $total_checkOUT_count,
                    ];
                    array_push($all_dates_record,$one_type_day_room);
                }
                
                // dd($all_dates_record);
                
                $one_type_record_data = [
                    'type'          => $all_typez->room_type,
                    'rooms_record'  => $all_dates_record,
                ];
                
                if($room_type_found){
                    array_push($rooms_types_arr,$one_type_record_data);                                  
                }
            }   
            
            $single_supplier_arr = [
                'supplier_id'       => $supplier->id,
                'supplier_record'   => $supplier->room_supplier_name,
                'rooms_data'        => $rooms_types_arr
            ];
            
            if($supplier_id_found){
                array_push($all_suppliers_records,$single_supplier_arr);
            }
        }
        
        // dd('stop');
        
        return response()->json([
            "dates"         => $dates,
            "rooms_records" => $all_suppliers_records,
        ]);
    }
    
    public function supplierdetail(Request $req){

   
     $supplierId = $req->supplier_id;
     
     
    
    $supplier = \DB::table('rooms_Invoice_Supplier')->where('id',$supplierId)->first();
        //   dd($all_booking_single);
  
     return response()->json(['message'=>'success','fetchedsupplier'=>$supplier]);
         
    }
    
    public function getbooking(Request $req){
        // dd($req->suplier);
        $hotel_supplier_ledger = DB::table('hotel_supplier_ledger')
            ->leftJoin('add_manage_invoices', 'hotel_supplier_ledger.invoice_no', '=', 'add_manage_invoices.id')
            ->leftJoin('rooms_Invoice_Supplier', 'hotel_supplier_ledger.supplier_id', '=', 'rooms_Invoice_Supplier.id')
            ->where('hotel_supplier_ledger.supplier_id',$req->suplier)->whereDate('add_manage_invoices.created_at',$req->currentDate)
            ->select('rooms_Invoice_Supplier.*', 'hotel_supplier_ledger.id','hotel_supplier_ledger.supplier_id','hotel_supplier_ledger.payment','hotel_supplier_ledger.invoice_no as hotel_supplier_invoice','add_manage_invoices.agent_Company_Name','hotel_supplier_ledger.date as booking_date',
                                         'add_manage_invoices.generate_id as invoice_id','add_manage_invoices.confirm as invoice_status','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name','add_manage_invoices.middle_name'
                                            )
                                            ->orderByRaw("JSON_EXTRACT(add_manage_invoices.accomodation_details, '$.acc_check_in') DESC")->get();
                                            
    return response()->json(['hotel_supplier'=>$hotel_supplier_ledger]);                                        
    }
    
    public function getbookingbefore(Request $req){
        // dd($req);
            if($req->type == "website"){
                 $from = $req->startDate;
                //  $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $suplierid = $req->suplier;
                 $roomid = $req->roomid;
                 $hotel = $req->hotel;
  
               
               
                 $booked_room = \DB::table('hotel_booking')->where('check_in','>',$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->where('hotel_reservation','confirmed')->whereJsonContains('rooms_checkavailability',['rooms_type_id'=>$roomid,'rooms_supplier'=>$suplierid])->get();
                    //   dd($booked_room);
            
                 return response()->json(['message'=>'success','fetchedsupplier'=>$booked_room,'type'=>$req->type]);
        
            }
            if($req->type == "adminsite"){
                // dd($req);
                 $from = $req->startDate;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;

                //  
                //  $booked_room = \DB::table('add_manage_invoices')->where('start_date','>',$from)->where('end_date', "<", $stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                 $booked_room = \DB::table('add_manage_invoices')->where('start_date','>',$from)->where('end_date', "<", $stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                    //   dd($booked_room);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$booked_room,'type'=>$req->type]);
            }
            
            
           
            if($req->type == "pacsite"){
                // dd("pacadmin here");
                 $from = $req->startDate;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $suplier = $req->suplier;
                 $hotel = $req->hotel;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                //  $booked_room = \DB::table('hotel_booking')->where('check_in','>',$currentDate)->where('provider','hotels')->where('hotel_reservation','confirmed')->get();
               $booked_room = \DB::table('tours')->where('start_date', ">" ,$from)->where('end_date', "<", $stop_date)->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                    //   dd($booked_room);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$booked_room,'type'=>$req->type]);
            }
            
            
             if($req->type == "inhouse"){
                // dd("admin here");
                 $main_arr = [];
                 $from = $req->startDate;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $hotel = $req->hotel;
         
                $web_booked_room = \DB::table('hotel_booking')->where('check_in','>',$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->where('hotel_reservation','confirmed')->whereJsonContains('rooms_checkavailability',['rooms_type_id'=>$roomid,'rooms_supplier'=>$suplierid])->get();
                array_push($main_arr,$web_booked_room);
                // $admin_booked_room = \DB::table('add_manage_invoices')->where('start_date','>',$from)->where('end_date', "<", $stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                $admin_booked_room = \DB::table('add_manage_invoices')->where('start_date','>',$from)->where('end_date', "<", $stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                array_push($main_arr,$admin_booked_room);
                $package_booked_room = \DB::table('tours')->where('start_date', ">" ,$from)->where('end_date', "<", $stop_date)->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                array_push($main_arr,$admin_booked_room);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$main_arr,'type'=>$req->type]);
            } 
            if($req->type == "checkIn"){
                // dd($req);
                 $main_checkin_arr = [];
                 $from = $req->startDate;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $hotel = $req->hotel;
                
                $web_booked_room = \DB::table('hotel_booking')->where('check_in','>=',$from)->where('check_out','<=',$stop_date)->where('hotel_reservation','confirmed')->whereJsonContains('rooms_checkavailability',['rooms_type_id'=>$roomid,'rooms_supplier'=>$suplierid])->get();
                $data_arr=[
                    'type'=>'website',
                    'data'=>$web_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
                $admin_booked_room = \DB::table('add_manage_invoices')->where('start_date','>=',$from)->where('end_date','<=',$stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                $data_arr=[
                    'type'=>'admin',
                    'data'=>$admin_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
                $package_booked_room = \DB::table('tours')->where('start_date', "=" ,$from)->where('end_date', "<=" ,$stop_date)->get();
                 $data_arr=[
                    'type'=>'package',
                    'data'=>$package_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$main_checkin_arr,'type'=>$req->type]);
            }
            if($req->type == "checkOUT"){
                // dd("admin here");
               $main_checkin_arr = [];
                 $from = $req->startDate;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $hotel = $req->hotel;
                 
                $web_booked_room = \DB::table('hotel_booking')->where('check_in','>=',$from)->where('check_out','<=',$stop_date)->where('hotel_reservation','confirmed')->whereJsonContains('rooms_checkavailability',['rooms_type_id'=>$roomid,'rooms_supplier'=>$suplierid])->get();
                $data_arr=[
                    'type'=>'website',
                    'data'=>$web_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
                $admin_booked_room = \DB::table('add_manage_invoices')->where('start_date','>=',$from)->where('end_date','<=',$stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                $data_arr=[
                    'type'=>'admin',
                    'data'=>$admin_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
                $package_booked_room = \DB::table('tours')->where('start_date', "=" ,$from)->where('end_date', "<=" ,$stop_date)->get();
                 $data_arr=[
                    'type'=>'package',
                    'data'=>$package_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$main_checkin_arr,'type'=>$req->type]);
            }
    }
}
