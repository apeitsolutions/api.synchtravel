<?php

namespace App\Actions;
use DB;
use Carbon\Carbon;

class GetTransferSupplierStatementData
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
                        
                         $supplier_invoices = DB::table('add_manage_invoices')->where('transfer_supplier_id',$supplierId)
                                                         ->whereDate('created_at','>=', $startDate)
                                                         ->whereDate('created_at','<=', $endDate)
                                                         ->select('id as invoice_no','transportation_details','transportation_details_more','created_at')
                                                         ->get();
        
            
                        $supplier_invoices_arr = [];
                        if(isset($supplier_invoices)){
                            foreach($supplier_invoices as $supplier_res){
                                $transportation_details = json_decode($supplier_res->transportation_details);
                                $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                                
                                if(isset($transportation_details)){
                                    $total_price = 0;
                                    $no_of_vehicle = 0;
                                    $price_per_vehicle = 0;
                                    $vehcile_type = 0;
                                    $pick_up_location = '';
                                    $drop_off_location = '';
                                    $transportation_type = '';
    
                                    foreach($transportation_details as $trans_res){
                                        if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                                            $total_price += $trans_res->transportation_vehicle_total_price;
                                            
                                            $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                                            $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                                            $vehcile_type = $trans_res->transportation_vehicle_type;
                                            $pick_up_location = $trans_res->transportation_pick_up_location;
                                            $drop_off_location = $trans_res->transportation_drop_off_location;
                                            $transportation_type = $trans_res->transportation_trip_type;
                                        }
                                    }
                                    
                                if(isset($transportation_details_more)){
                                    foreach($transportation_details_more as $trans_res){
                                        $drop_off_location = $trans_res->more_transportation_drop_off_location;
                                    }
                                }
                                    
                                    
                                    $supplier_invoices_arr[] = (Object)[
                                        'supplier_id'=> $supplierId,
                                        'booking_type'=> "Invoice",
                                        'invoice_id'=> $supplier_res->invoice_no,
                                        'pick_up_location'=> $pick_up_location,
                                        'drop_off_location'=> $drop_off_location,
                                        'transportation_type'=> $transportation_type,
                                        'vehcile_type'=> $vehcile_type,
                                        'price_per_vehicle'=> $price_per_vehicle,
                                        'number_of_vehicles'=> $no_of_vehicle,
                                        'total_price'=> $total_price,
                                        'created_at' => $supplier_res->created_at
                                    ];
                                }
                                 
                                // print_r($transportation_details);
                                // print_r($transportation_details_more);
                               
                            }
                        }
                        $supplier_packages = DB::table('tours_2')->where('transfer_supplier_id',$supplierId)
                                                            ->whereDate('created_at','>=', $startDate)
                                                            ->whereDate('created_at','<=', $endDate)
                                                             ->select('tour_id as package_id','transportation_details','transportation_details_more','created_at')
                                                             ->get();
                        
                        
                        $supplier_packages_arr = [];
                        if(isset($supplier_packages)){
                            foreach($supplier_packages as $supplier_res){
                                $transportation_details = json_decode($supplier_res->transportation_details);
                                $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                                
                                    if(isset($transportation_details)){
                                     
                                        $total_price = 0;
                                        $no_of_vehicle = 0;
                                        $price_per_vehicle = 0;
                                        $vehcile_type = 0;
                                        $pick_up_location = '';
                                        $drop_off_location = '';
                                        $transportation_type = '';
        
                                        foreach($transportation_details as $trans_res){
                                            if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                                                $total_price += $trans_res->transportation_vehicle_total_price;
                                                
                                                $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                                                $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                                                $vehcile_type = $trans_res->transportation_vehicle_type;
                                                $pick_up_location = $trans_res->transportation_pick_up_location;
                                                $drop_off_location = $trans_res->transportation_drop_off_location;
                                                $transportation_type = $trans_res->transportation_trip_type;
                                            }
                                        }
                                        
                                    if(isset($transportation_details_more)){
                                        foreach($transportation_details_more as $trans_res){
                                            $drop_off_location = $trans_res->more_transportation_drop_off_location;
                                        }
                                    }
                                        
                                         $supplier_packages_arr[] = (Object)[
                                                            'supplier_id'=> $supplierId,
                                                            'booking_type'=> "Package",
                                                            'package_id'=> $supplier_res->package_id,
                                                            'pick_up_location'=> $pick_up_location,
                                                            'drop_off_location'=> $drop_off_location,
                                                            'transportation_type'=> $transportation_type,
                                                            'vehcile_type'=> $vehcile_type,
                                                            'price_per_vehicle'=> $price_per_vehicle,
                                                            'number_of_vehicles'=> $no_of_vehicle,
                                                            'total_price'=> $total_price,
                                                            'created_at' => $supplier_res->created_at
                                                        ];
                                    }
                                
                            }
                        }
                        
                        $payments_data = DB::table('recevied_payments_details')
                            ->where('Criteria','Transfer Supplier')
                            ->where('Content_Ids',$supplierId)
                            ->whereDate('payment_date','>=', $startDate)
                            ->whereDate('payment_date','<=', $endDate)
                            ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                            ->orderBy('created_at')
                            ->get();
                            
                        $make_payments_data = DB::table('make_payments_details')
                            ->where('Criteria','Transfer Supplier')
                            ->where('Content_Ids',$supplierId)
                            ->whereDate('payment_date','>=', $startDate)
                            ->whereDate('payment_date','<=', $endDate)
                            ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                            ->orderBy('created_at')
                            ->get();
                        
                    }else{
                         $supplier_invoices = DB::table('add_manage_invoices')->where('transfer_supplier_id',$supplierId)
                                                         ->select('id as invoice_no','transportation_details','transportation_details_more','created_at')
                                                         ->get();
        
            
                        $supplier_invoices_arr = [];
                        if(isset($supplier_invoices)){
                            foreach($supplier_invoices as $supplier_res){
                                $transportation_details = json_decode($supplier_res->transportation_details);
                                $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                                
                                if(isset($transportation_details)){
                                    $total_price = 0;
                                    $no_of_vehicle = 0;
                                    $price_per_vehicle = 0;
                                    $vehcile_type = 0;
                                    $pick_up_location = '';
                                    $drop_off_location = '';
                                    $transportation_type = '';
    
                                    foreach($transportation_details as $trans_res){
                                        if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                                            $total_price += $trans_res->transportation_vehicle_total_price;
                                            
                                            $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                                            $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                                            $vehcile_type = $trans_res->transportation_vehicle_type;
                                            $pick_up_location = $trans_res->transportation_pick_up_location;
                                            $drop_off_location = $trans_res->transportation_drop_off_location;
                                            $transportation_type = $trans_res->transportation_trip_type;
                                        }
                                    }
                                    
                                if(isset($transportation_details_more)){
                                    foreach($transportation_details_more as $trans_res){
                                        $drop_off_location = $trans_res->more_transportation_drop_off_location;
                                    }
                                }
                                    
                                    
                                    $supplier_invoices_arr[] = (Object)[
                                        'supplier_id'=> $supplierId,
                                        'booking_type'=> "Invoice",
                                        'invoice_id'=> $supplier_res->invoice_no,
                                        'pick_up_location'=> $pick_up_location,
                                        'drop_off_location'=> $drop_off_location,
                                        'transportation_type'=> $transportation_type,
                                        'vehcile_type'=> $vehcile_type,
                                        'price_per_vehicle'=> $price_per_vehicle,
                                        'number_of_vehicles'=> $no_of_vehicle,
                                        'total_price'=> $total_price,
                                        'created_at' => $supplier_res->created_at
                                    ];
                                }
                                 
                                // print_r($transportation_details);
                                // print_r($transportation_details_more);
                               
                            }
                        }
                        $supplier_packages = DB::table('tours_2')->where('transfer_supplier_id',$supplierId)
                                                             ->select('tour_id as package_id','transportation_details','transportation_details_more','created_at')
                                                             ->get();
                        
                        
                        $supplier_packages_arr = [];
                        if(isset($supplier_packages)){
                            foreach($supplier_packages as $supplier_res){
                                $transportation_details = json_decode($supplier_res->transportation_details);
                                $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                                
                                    if(isset($transportation_details)){
                                     
                                        $total_price = 0;
                                        $no_of_vehicle = 0;
                                        $price_per_vehicle = 0;
                                        $vehcile_type = 0;
                                        $pick_up_location = '';
                                        $drop_off_location = '';
                                        $transportation_type = '';
        
                                        foreach($transportation_details as $trans_res){
                                            if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                                                $total_price += $trans_res->transportation_vehicle_total_price;
                                                
                                                $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                                                $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                                                $vehcile_type = $trans_res->transportation_vehicle_type;
                                                $pick_up_location = $trans_res->transportation_pick_up_location;
                                                $drop_off_location = $trans_res->transportation_drop_off_location;
                                                $transportation_type = $trans_res->transportation_trip_type;
                                            }
                                        }
                                        
                                    if(isset($transportation_details_more)){
                                        foreach($transportation_details_more as $trans_res){
                                            $drop_off_location = $trans_res->more_transportation_drop_off_location;
                                        }
                                    }
                                        
                                         $supplier_packages_arr[] = (Object)[
                                                            'supplier_id'=> $supplierId,
                                                            'booking_type'=> "Package",
                                                            'package_id'=> $supplier_res->package_id,
                                                            'pick_up_location'=> $pick_up_location,
                                                            'drop_off_location'=> $drop_off_location,
                                                            'transportation_type'=> $transportation_type,
                                                            'vehcile_type'=> $vehcile_type,
                                                            'price_per_vehicle'=> $price_per_vehicle,
                                                            'number_of_vehicles'=> $no_of_vehicle,
                                                            'total_price'=> $total_price,
                                                            'created_at' => $supplier_res->created_at
                                                        ];
                                    }
                                
                            }
                        }
                        
                        $payments_data = DB::table('recevied_payments_details')
                            ->where('Criteria','Transfer Supplier')
                            ->where('Content_Ids',$supplierId)
                            ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                            ->orderBy('created_at')
                            ->get();
                            
                        $make_payments_data = DB::table('make_payments_details')
                            ->where('Criteria','Transfer Supplier')
                            ->where('Content_Ids',$supplierId)
                            ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                            ->orderBy('created_at')
                            ->get();
                            
                            
                    }
                  
                        
                    $supplier_invoices_arr = collect($supplier_invoices_arr);
                    $supplier_packages_arr = collect($supplier_packages_arr);
                    
                    $all_data = $supplier_invoices_arr->concat($supplier_packages_arr)->concat($payments_data)->concat($make_payments_data)->sortBy('created_at');

        $suppliers_data     = DB::table('transfer_Invoice_Supplier')->where('id',$supplierId)->first();
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
            if(isset($booking->total_price) && !empty($booking->total_price)){
                $totalRevenue += $booking->total_price ?? 0;
            }
            
            if(isset($booking->price_difference)  && !empty($booking->price_difference)){
                $totalRevenue += $booking->price_difference ?? 0;
            }   
            
            if(isset($booking->Amount)){
                $totalPaid += $booking->Amount;
            }
            
        }
        
        return [
                'totalRevenue' => $totalRevenue,
                'totalPaid' => $totalPaid,
            ];
    }
}
