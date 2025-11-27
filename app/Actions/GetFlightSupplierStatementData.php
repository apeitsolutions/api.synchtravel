<?php

namespace App\Actions;
use DB;
use Carbon\Carbon;

class GetFlightSupplierStatementData
{
    public function __construct() {}

    /**
     * Delete the given user.
     */
    public function execute($clientId,$currency,$supplierId,$startDate,$endDate)
    {
         if(!empty($startDate)){

            $startDate = $startDate;
            $endDate = $endDate;
             
            $flight_routes = DB::table('flight_rute')->where('dep_supplier',$supplierId)
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
                        'supplier_id'=> $supplierId,
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
         
                $supplier_invoices = DB::table('add_manage_invoices')->where('flight_supplier',$supplierId)
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
                                            'supplier_id'=> $supplierId,
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
                                        ->where('tours_2.flight_supplier',$supplierId)
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
                                            'supplier_id'=> $supplierId,
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
                ->where('Content_Ids',$supplierId)
                ->whereDate('payment_date','>=', $startDate)
                ->whereDate('payment_date','<=', $endDate)
                ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate",'company_amount')
                ->orderBy('created_at')
                ->get();
                
            $make_payments_data = DB::table('make_payments_details')
                ->where('Criteria','Flight Supplier')
                ->where('Content_Ids',$supplierId)
                ->whereDate('payment_date','>=', $startDate)
                ->whereDate('payment_date','<=', $endDate)
                ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate",'company_amount')
                ->orderBy('created_at')
                ->get();
        }else{

            $flight_routes = DB::table('flight_rute')->where('dep_supplier',$supplierId)
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
                        'supplier_id'=> $supplierId,
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
         
                $supplier_invoices = DB::table('add_manage_invoices')->where('flight_supplier',$supplierId)
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
                                            'supplier_id'=> $supplierId,
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
                                        ->where('tours_2.flight_supplier',$supplierId)
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
                                            'supplier_id'=> $supplierId,
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
                ->where('Content_Ids',$supplierId)
                ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate",'company_amount')
                ->orderBy('created_at')
                ->get();
                
            $make_payments_data = DB::table('make_payments_details')
                ->where('Criteria','Flight Supplier')
                ->where('Content_Ids',$supplierId)
                ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate",'company_amount')
                ->orderBy('created_at')
                ->get();
        }

        
            
            
        $supplier_invoices_arr = collect($supplier_invoices_arr);
        $supplier_seats_data = collect($supplier_seats_data);
        $supplier_packages_arr = collect($supplier_packages_arr);
        
        $all_data = $supplier_seats_data->concat($supplier_invoices_arr)->concat($supplier_packages_arr)->concat($payments_data)->concat($make_payments_data)->sortBy('created_at');
           
        $suppliers_data     = DB::table('supplier')->where('id',$supplierId)->first();
        return $this->getSupplierOverallPayableDetails($suppliers_data,$currency,$all_data);
        
        
        
    }
    
    private function getSupplierOverallPayableDetails($supplier,$currency,$allBooking){
        $totalRevenue = 0;
        $totalPaid = 0;

        $purchase_currency = '';
        $purchase_currency          = $supplier->currency;
        
        
        foreach($allBooking as $booking){
            
            $purchase_currency          = $supplier->currency;
            $sale_currency              = $currency;
            if (isset($booking->flight_total_price) && !empty($booking->flight_total_price)) {
                $totalRevenue += (float)$booking->flight_total_price ?? (float)$booking->company_amount ?? 0;
            }
            
            if (isset($booking->price_difference) && !empty($booking->price_difference)) {
                $totalRevenue += (float)$booking->price_difference ?? (float)$booking->company_amount ?? 0;
            }

            if(isset($booking->company_amount)){
                $totalRevenue += (float)$booking->company_amount;
            }
            
            if(isset($booking->purchase_amount)){
                $totalRevenue += (float)$booking->purchase_amount;
            }
            
            if(isset($booking->Amount)){
                $totalPaid += (float)$booking->Amount;
            }          
        }
        
        return [
                'totalRevenue' => $totalRevenue,
                'totalPaid' => $totalPaid,
            ];
    }
}
