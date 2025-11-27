<?php

namespace App\Actions;
use DB;
use Carbon\Carbon;

class GetHotelSupplierStatementData
{
    public function __construct() {}

    /**
     * Delete the given user.
     */
    public function execute($clientId,$currency,$supplierId,$startDate,$endDate)
    {
        if(!empty($startDate)){
            $supplierBooking    = [];
            $other_Supplier     = DB::table('rooms_Invoice_Supplier')->where('id',$supplierId)->get();

            foreach($other_Supplier as $val_SD){
                $supplierBooking_1     = DB::table('rooms')
                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                        ->leftJoin('add_manage_invoices', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                        })
                                        ->leftJoin('tours', function ($join) {
                                            $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'package');
                                        })
                                        ->leftJoin('hotels_bookings', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.invoice_no')
                                                ->where('rooms_bookings_details.booking_from', '=', 'website');
                                        })
                                        ->where('rooms.room_supplier_name',$val_SD->id)
                                        ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                        ->whereDate('rooms_bookings_details.check_in','<=', $endDate)
                                        ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                                ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                                ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID',
                                                'add_manage_invoices.customer_id as invoice_CID','add_manage_invoices.f_name as invoice_Lead_First_Name',
                                                'add_manage_invoices.middle_name as invoice_Lead_Last_Name','add_manage_invoices.currency_conversion as invoice_Currency')
                                        ->orderBy('rooms.id','asc')
                                        ->get();
                if ($supplierBooking_1->isEmpty()) {} else {
                    array_push($supplierBooking,$supplierBooking_1[0]);
                }
            }
            
            if($supplierId == 135 && $request->customer_id != 45){
                $supplierBooking_2  = DB::table('rooms')
                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                        ->leftJoin('add_manage_invoices', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                        })
                                        ->leftJoin('tours', function ($join) {
                                            $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'package');
                                        })
                                        ->leftJoin('hotels_bookings', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.invoice_no')
                                                ->where('rooms_bookings_details.booking_from', '=', 'website');
                                        })
                                        ->where(function ($query) use ($supplierId,$customer_Id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name', $supplierId)
                                            ->where('hotels_bookings.customer_id', $customer_Id)
                                            // ->WhereJsonContains('rooms.allowed_Clients', [['client_Id' => (string)$customer_Id]])
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->orWhere(function ($query) use ($supplierId,$customer_Id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name_AR', $supplierId)
                                            ->where('hotels_bookings.customer_id', $customer_Id)
                                            // ->WhereJsonContains('rooms.allowed_Clients', [['client_Id' => (string)$customer_Id]])
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                                ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                                ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID',
                                                'add_manage_invoices.customer_id as invoice_CID','add_manage_invoices.f_name as invoice_Lead_First_Name',
                                                'add_manage_invoices.middle_name as invoice_Lead_Last_Name','add_manage_invoices.currency_conversion as invoice_Currency')
                                        ->orderBy('rooms.id','asc')
                                        ->get();
                // dd($supplierBooking_2);
            }else{
                $supplierBooking_2  = DB::table('rooms')
                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                        ->leftJoin('add_manage_invoices', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                        })
                                        ->leftJoin('tours', function ($join) {
                                            $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'package');
                                        })
                                        ->leftJoin('hotels_bookings', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.invoice_no')
                                                ->where('rooms_bookings_details.booking_from', '=', 'website');
                                        })
                                        ->where(function ($query) use ($supplierId,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name', $supplierId)
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->orWhere(function ($query) use ($supplierId,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name_AR', $supplierId)
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                                ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                                ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID',
                                                'add_manage_invoices.customer_id as invoice_CID','add_manage_invoices.f_name as invoice_Lead_First_Name',
                                                'add_manage_invoices.middle_name as invoice_Lead_Last_Name','add_manage_invoices.currency_conversion as invoice_Currency')
                                        ->orderBy('rooms.id','asc')
                                        ->get();
                // dd($supplierBooking_2);
            }
            
            if ($supplierBooking_2->isEmpty()) {} else {
                foreach($supplierBooking_2 as $val_SB2){
                    array_push($supplierBooking,$val_SB2);
                }
            }
            
            $supplier_rooms_bookings    = [];
            $total_nights               = 0;
            $total_payable_price        = 0;
            $total_rooms_count          = 0;
            
            // dd($supplierBooking);
            
            foreach($supplierBooking as $book_res){
                
                // $client_Data = DB::table('customer_subcriptions')->where('id',$book_res->CLIENT_ID)->first();
                if(isset($book_res->CLIENT_ID) && $book_res->CLIENT_ID != null && $book_res->CLIENT_ID != ''){
                    $client_Data = DB::table('customer_subcriptions')->where('id',$book_res->CLIENT_ID)->first();
                }else{
                    $client_Data = DB::table('customer_subcriptions')->where('id',$book_res->invoice_CID)->first();
                }
                
                if(isset($book_res->invoice_Currency) && $book_res->invoice_Currency != null && $book_res->invoice_Currency != ''){
                    $string             = $book_res->invoice_Currency;
                    preg_match('/^[A-Z]+/', $string, $matches);
                    $invoice_Currency   = $matches[0] ?? '';
                }
                
                // From Accomodation Details Invoice
                if(isset($book_res->accomodation_details)){
                    $accomodation_data                      = json_decode($book_res->accomodation_details);
                    if($accomodation_data){
                        foreach($accomodation_data as $acc_res){
                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                $diff                       = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                $no_of_nights               = abs(round($diff / 86400));
                                $total_nights               += $no_of_nights;
                                $supplier_rooms_bookings[]  = (Object)[
                                    'booking_type'      => 'Invoice',
                                    'client_Id'             => $client_Data->id ?? '',
                                    'client_Name'           => $client_Data->company_name ?? '',
                                    'invoice_Lead_Name'     => $book_res->invoice_Lead_First_Name ?? '' .' '. $book_res->invoice_Lead_Last_Name ?? '',
                                    'invoice_Currency'      => $invoice_Currency ?? '',
                                    'created_at'        => $book_res->booking_at,
                                    'booking_id'        => $book_res->booking_id,
                                    'hotel_name'        => $acc_res->acc_hotel_name,
                                    'room_id'           => $acc_res->hotelRoom_type_id,
                                    'room_type'         => $acc_res->hotel_type_cat,
                                    'check_in'          => $acc_res->acc_check_in,
                                    'check_out'         => $acc_res->acc_check_out,
                                    'no_of_nights'      => $no_of_nights,
                                    'rooms_qty'         => $acc_res->acc_qty,
                                    'rooms_price'       => $acc_res->price_per_room_purchase,
                                    'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                ];
                            }
                        }
                    }  
                }
                
                // From More Accomodation Details Invoice
                if(isset($book_res->accomodation_details_more)){
                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                    if($accomodation_data){
                        foreach($accomodation_data as $acc_res){
                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                $diff                       = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                $no_of_nights               = abs(round($diff / 86400));
                                $total_nights               += $no_of_nights;
                                
                                $supplier_rooms_bookings[]  = (Object)[
                                    'booking_type'      => 'Invoice',
                                    'client_Id'         => $client_Data->id ?? '',
                                    'client_Name'       => $client_Data->company_name ?? '',
                                    'invoice_Lead_Name'     => $book_res->invoice_Lead_First_Name ?? '' .' '. $book_res->invoice_Lead_Last_Name ?? '',
                                    'invoice_Currency'      => $invoice_Currency ?? '',
                                    'booking_id'        => $book_res->booking_id,
                                    'created_at'        => $book_res->booking_at,
                                    'hotel_name'        => $acc_res->more_acc_hotel_name,
                                    'room_id'           => $acc_res->more_hotelRoom_type_id,
                                    'room_type'         => $acc_res->more_hotel_type_cat,
                                    'check_in'          => $acc_res->more_acc_check_in,
                                    'check_out'         => $acc_res->more_acc_check_out,
                                    'no_of_nights'      => $no_of_nights,
                                    'rooms_qty'         => $acc_res->more_acc_qty,
                                    'rooms_price'       => $acc_res->more_price_per_room_purchase,
                                    'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                ];
                                
                                $total_price                = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                $total_payable_price        += $total_price;
                                $total_rooms_count          += $acc_res->more_acc_qty;    
                            }
                        }
                    }
                }
                
                // From Accomodation Details Package
                if(isset($book_res->accomodation_details_package)){
                    $accomodation_data = json_decode($book_res->accomodation_details_package);
                    if($accomodation_data){
                        foreach($accomodation_data as $acc_res){
                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                $no_of_nights = abs(round($diff / 86400));
                                
                                $total_nights += $no_of_nights;
                                
                                $supplier_rooms_bookings[] = (Object)[
                                        'booking_type' => 'Package',
                                        'client_Id'         => $client_Data->id ?? '',
                                        'client_Name'       => $client_Data->company_name ?? '',
                                        'invoice_Lead_Name'     => $book_res->invoice_Lead_First_Name ?? '' .' '. $book_res->invoice_Lead_Last_Name ?? '',
                                        'invoice_Currency'      => $invoice_Currency ?? '',
                                        'created_at' => $book_res->booking_at,
                                        'booking_id' => $book_res->booking_id,
                                        'hotel_name' => $acc_res->acc_hotel_name,
                                        'room_id' => $acc_res->hotelRoom_type_id,
                                        'room_type' => $acc_res->hotel_type_cat,
                                        'check_in' => $acc_res->acc_check_in,
                                        'check_out' => $acc_res->acc_check_out,
                                        'no_of_nights' => $no_of_nights,
                                        'rooms_qty' => $book_res->quantity,
                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                    ];
                                
                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                $total_payable_price += $total_price;
                                $total_rooms_count += $book_res->quantity;
                            }
                        }
                        
                        
                    }  
                }
                
                // From More Accomodation Details Package
                if(isset($book_res->accomodation_details_more_package)){
                    $accomodation_data = json_decode($book_res->accomodation_details_more_package);
                    if($accomodation_data){
                        foreach($accomodation_data as $acc_res){
                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                $no_of_nights = abs(round($diff / 86400));
                                
                                $total_nights += $no_of_nights;
                                
                                 $supplier_rooms_bookings[] = (Object)[
                                        'booking_type' => 'Package',
                                        'client_Id'         => $client_Data->id ?? '',
                                        'client_Name'       => $client_Data->company_name ?? '',
                                        'invoice_Lead_Name'     => $book_res->invoice_Lead_First_Name ?? '' .' '. $book_res->invoice_Lead_Last_Name ?? '',
                                        'invoice_Currency'      => $invoice_Currency ?? '',
                                        'created_at' => $book_res->booking_at,
                                        'booking_id' => $book_res->booking_id,
                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                        'room_type' => $acc_res->more_hotel_type_cat,
                                        'check_in' => $acc_res->more_acc_check_in,
                                        'check_out' => $acc_res->more_acc_check_out,
                                        'no_of_nights' => $no_of_nights,
                                        'rooms_qty' => $book_res->quantity,
                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                    ];
                                    
                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                $total_payable_price += (int)$total_price;
                                $total_rooms_count += (int)$book_res->quantity;    
                            }
                        }
                        
                        
                    }
                    
                }
                
                // From Website Booking
                if(isset($book_res->reservation_response)){
                    $reservation_data = json_decode($book_res->reservation_response);
                    if($reservation_data){
                     
                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                $no_of_nights = abs(round($diff / 86400));
                                
                                $total_nights += $no_of_nights;
                                
                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                    $supplier_rooms_bookings[] = (Object)[
                                        'booking_type' => 'Website Booking',
                                        'client_Id'         => $client_Data->id ?? '',
                                        'client_Name'       => $client_Data->company_name ?? '',
                                        'invoice_Lead_Name'     => $book_res->invoice_Lead_First_Name ?? '' .' '. $book_res->invoice_Lead_Last_Name ?? '',
                                        'invoice_Currency'      => $invoice_Currency ?? '',
                                        'created_at' => $book_res->booking_at,
                                        'booking_id' => $book_res->booking_id,
                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                        'room_id' => $room_res->room_code,
                                        'room_type' => $room_res->room_name,
                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                        'no_of_nights' => $no_of_nights,
                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                        'rooms_price' => $room_res->room_rates[0]->net,
                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                    ];
                                    
                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                    $total_payable_price += $total_price;
                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                }
                            
                                
                        
                    }
                    
                }
            }
            
            $payments_data      = DB::table('recevied_payments_details')
                                    ->where('Criteria','Hotel Supplier')
                                    ->where('Content_Ids',$supplierId)
                                    ->whereDate('payment_date','>=', $startDate)
                                    ->whereDate('payment_date','<=', $endDate)
                                    ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                    ->orderBy('check_in')
                                    ->get();
                
            $make_payments_data = DB::table('make_payments_details')
                                    ->where('Criteria','Hotel Supplier')
                                    ->where('Content_Ids',$supplierId)
                                    ->whereDate('payment_date','>=', $startDate)
                                    ->whereDate('payment_date','<=', $endDate)
                                    ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                    ->orderBy('check_in')
                                    ->get();
        }else{
            $supplierBooking        = [];
            $other_Supplier         = DB::table('rooms_Invoice_Supplier')->where('id',$supplierId)->get();
            // dd($other_Supplier);
            foreach($other_Supplier as $val_SD){
                $supplierBooking_1     = DB::table('rooms')
                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                        ->leftJoin('add_manage_invoices', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                        })
                                        ->leftJoin('tours', function ($join) {
                                            $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'package');
                                        })
                                        ->leftJoin('hotels_bookings', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.invoice_no')
                                                ->where('rooms_bookings_details.booking_from', '=', 'website');
                                        })
                                        ->where('rooms.room_supplier_name',$val_SD->id)
                                        ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                                ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                                ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID',
                                                'add_manage_invoices.customer_id as invoice_CID','add_manage_invoices.f_name as invoice_Lead_First_Name',
                                                'add_manage_invoices.middle_name as invoice_Lead_Last_Name','add_manage_invoices.currency_conversion as invoice_Currency')
                                        ->orderBy('rooms.id','asc')
                                        ->get();
                if ($supplierBooking_1->isEmpty()) {} else {
                    array_push($supplierBooking,$supplierBooking_1[0]);
                }
            }
            
            if($supplierId == 135 && $request->customer_id != 45){
                // dd('IF');
                $supplierBooking_2  = DB::table('rooms')
                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                        ->leftJoin('add_manage_invoices', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                        })
                                        ->leftJoin('tours', function ($join) {
                                            $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'package');
                                        })
                                        ->leftJoin('hotels_bookings', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.invoice_no')
                                                ->where('rooms_bookings_details.booking_from', '=', 'website');
                                        })
                                        ->where(function ($query) use ($supplierId,$customer_Id) {
                                            $query->where('rooms.room_supplier_name', $supplierId)
                                            ->where('hotels_bookings.customer_id', $customer_Id);
                                            
                                            // ->WhereJsonContains('rooms.allowed_Clients', [['client_Id' => (string)$customer_Id]]);
                                        })
                                        ->orWhere(function ($query) use ($supplierId,$customer_Id) {
                                            $query->where('rooms.room_supplier_name_AR', $supplierId)
                                            ->where('hotels_bookings.customer_id', $customer_Id);
                                            
                                            // ->WhereJsonContains('rooms.allowed_Clients', [['client_Id' => (string)$customer_Id]]);
                                        })
                                        ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                                ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                                ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID',
                                                'add_manage_invoices.customer_id as invoice_CID','add_manage_invoices.f_name as invoice_Lead_First_Name',
                                                'add_manage_invoices.middle_name as invoice_Lead_Last_Name','add_manage_invoices.currency_conversion as invoice_Currency')
                                        ->orderBy('rooms.id','asc')
                                        ->get();
                // dd($supplierBooking_2);
            }else{
                // dd('else');
                $supplierBooking_2     = DB::table('rooms')
                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                        ->leftJoin('add_manage_invoices', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                        })
                                        ->leftJoin('tours', function ($join) {
                                            $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                                ->where('rooms_bookings_details.booking_from', '=', 'package');
                                        })
                                        ->leftJoin('hotels_bookings', function ($join) {
                                            $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.invoice_no')
                                                ->where('rooms_bookings_details.booking_from', '=', 'website');
                                        })
                                        ->where(function ($query) use ($supplierId) {
                                            $query->where('rooms.room_supplier_name', $supplierId);
                                        })
                                        ->orWhere(function ($query) use ($supplierId) {
                                            $query->where('rooms.room_supplier_name_AR', $supplierId);
                                        })
                                        ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                                ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                                ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID',
                                                'add_manage_invoices.customer_id as invoice_CID','add_manage_invoices.f_name as invoice_Lead_First_Name',
                                                'add_manage_invoices.middle_name as invoice_Lead_Last_Name','add_manage_invoices.currency_conversion as invoice_Currency')
                                        ->orderBy('rooms.id','asc')
                                        ->get();
                // dd($supplierBooking_2);
            }
            
            if ($supplierBooking_2->isEmpty()) {} else {
                foreach($supplierBooking_2 as $val_SB2){
                    array_push($supplierBooking,$val_SB2);
                }
            }
            
            $supplier_rooms_bookings    = [];
            $total_nights               = 0;
            $total_payable_price        = 0;
            $total_rooms_count          = 0;
            
            // dd($supplierBooking);
            
            $c=1;
            
            foreach($supplierBooking as $book_res){
                
                // dd($book_res);
                if(isset($book_res->CLIENT_ID) && $book_res->CLIENT_ID != null && $book_res->CLIENT_ID != ''){
                    $client_Data = DB::table('customer_subcriptions')->where('id',$book_res->CLIENT_ID)->first();
                }else{
                    $client_Data = DB::table('customer_subcriptions')->where('id',$book_res->invoice_CID)->first();
                }
                
                if(isset($book_res->invoice_Currency) && $book_res->invoice_Currency != null && $book_res->invoice_Currency != ''){
                    $string             = $book_res->invoice_Currency;
                    preg_match('/^[A-Z]+/', $string, $matches);
                    $invoice_Currency   = $matches[0] ?? '';
                }
                
                // From Accomodation Details Invoice
                if(isset($book_res->accomodation_details)){
                    $accomodation_data                      = json_decode($book_res->accomodation_details);
                    // dd($accomodation_data);
                    if($accomodation_data){
                        foreach($accomodation_data as $acc_res){
                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                $diff                       = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                $no_of_nights               = abs(round($diff / 86400));
                                $total_nights               += $no_of_nights;
                                $supplier_rooms_bookings[]  = (Object)[
                                    'booking_type'          => 'Invoice',
                                    'client_Id'             => $client_Data->id ?? '',
                                    'client_Name'           => $client_Data->company_name ?? '',
                                    'invoice_Lead_Name'     => $book_res->invoice_Lead_First_Name ?? '' .' '. $book_res->invoice_Lead_Last_Name ?? '',
                                    'invoice_Currency'      => $invoice_Currency ?? '',
                                    'created_at'            => $book_res->booking_at,
                                    'booking_id'            => $book_res->booking_id,
                                    'hotel_name'            => $acc_res->acc_hotel_name,
                                    'room_id'               => $acc_res->hotelRoom_type_id,
                                    'room_type'             => $acc_res->hotel_type_cat,
                                    'check_in'              => $acc_res->acc_check_in,
                                    'check_out'             => $acc_res->acc_check_out,
                                    'no_of_nights'          => $no_of_nights,
                                    'rooms_qty'             => $acc_res->acc_qty,
                                    'rooms_price'           => $acc_res->price_per_room_purchase,
                                    'rooms_total_price'     => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                ];
                            }
                        }
                    }  
                }
                
                // From More Accomodation Details Invoice
                if(isset($book_res->accomodation_details_more)){
                    $accomodation_data                      = json_decode($book_res->accomodation_details_more);
                    // dd($accomodation_data);
                    if($accomodation_data){
                        foreach($accomodation_data as $acc_res){
                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                $diff                       = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                $no_of_nights               = abs(round($diff / 86400));
                                $total_nights               += $no_of_nights;
                                
                                $supplier_rooms_bookings[] = (Object)[
                                    'booking_type'      => 'Invoice',
                                    'client_Id'         => $client_Data->id ?? '',
                                    'client_Name'       => $client_Data->company_name ?? '',
                                    'invoice_Lead_Name'     => $book_res->invoice_Lead_First_Name ?? '' .' '. $book_res->invoice_Lead_Last_Name ?? '',
                                    'invoice_Currency'      => $invoice_Currency ?? '',
                                    'booking_id'        => $book_res->booking_id,
                                    'created_at'        => $book_res->booking_at,
                                    'hotel_name'        => $acc_res->more_acc_hotel_name,
                                    'room_id'           => $acc_res->more_hotelRoom_type_id,
                                    'room_type'         => $acc_res->more_hotel_type_cat,
                                    'check_in'          => $acc_res->more_acc_check_in,
                                    'check_out'         => $acc_res->more_acc_check_out,
                                    'no_of_nights'      => $no_of_nights,
                                    'rooms_qty'         => $acc_res->more_acc_qty,
                                    'rooms_price'       => $acc_res->more_price_per_room_purchase,
                                    'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                ];
                                
                                $total_price                = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                $total_payable_price        += $total_price;
                                $total_rooms_count          += $acc_res->more_acc_qty;    
                            }
                        }
                    }
                }
                
                // From Accomodation Details Package
                if(isset($book_res->accomodation_details_package)){
                    $accomodation_data = json_decode($book_res->accomodation_details_package);
                    if($accomodation_data){
                        foreach($accomodation_data as $acc_res){
                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                $no_of_nights = abs(round($diff / 86400));
                                
                                $total_nights += $no_of_nights;
                                
                                $supplier_rooms_bookings[] = (Object)[
                                        'booking_type' => 'Package',
                                        'client_Id'         => $client_Data->id ?? '',
                                        'client_Name'       => $client_Data->company_name ?? '',
                                        'invoice_Lead_Name'     => $book_res->invoice_Lead_First_Name ?? '' .' '. $book_res->invoice_Lead_Last_Name ?? '',
                                        'invoice_Currency'      => $invoice_Currency ?? '',
                                        'created_at' => $book_res->booking_at,
                                        'booking_id' => $book_res->booking_id,
                                        'hotel_name' => $acc_res->acc_hotel_name,
                                        'room_id' => $acc_res->hotelRoom_type_id,
                                        'room_type' => $acc_res->hotel_type_cat,
                                        'check_in' => $acc_res->acc_check_in,
                                        'check_out' => $acc_res->acc_check_out,
                                        'no_of_nights' => $no_of_nights,
                                        'rooms_qty' => $book_res->quantity,
                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                    ];
                                
                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                $total_payable_price += $total_price;
                                $total_rooms_count += $book_res->quantity;
                            }
                        }
                        
                        
                    }  
                }
                
                // From More Accomodation Details Package
                if(isset($book_res->accomodation_details_more_package)){
                    $accomodation_data = json_decode($book_res->accomodation_details_more_package);
                    if($accomodation_data){
                        foreach($accomodation_data as $acc_res){
                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                $no_of_nights = abs(round($diff / 86400));
                                
                                $total_nights += $no_of_nights;
                                
                                 $supplier_rooms_bookings[] = (Object)[
                                        'booking_type' => 'Package',
                                        'client_Id'         => $client_Data->id ?? '',
                                        'client_Name'       => $client_Data->company_name ?? '',
                                        'invoice_Lead_Name'     => $book_res->invoice_Lead_First_Name ?? '' .' '. $book_res->invoice_Lead_Last_Name ?? '',
                                        'invoice_Currency'      => $invoice_Currency ?? '',
                                        'created_at' => $book_res->booking_at,
                                        'booking_id' => $book_res->booking_id,
                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                        'room_type' => $acc_res->more_hotel_type_cat,
                                        'check_in' => $acc_res->more_acc_check_in,
                                        'check_out' => $acc_res->more_acc_check_out,
                                        'no_of_nights' => $no_of_nights,
                                        'rooms_qty' => $book_res->quantity,
                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                    ];
                                    
                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                $total_payable_price += (int)$total_price;
                                $total_rooms_count += (int)$book_res->quantity;    
                            }
                        }
                        
                        
                    }
                    
                }
                
                // dd($book_res->reservation_response);
                
                // From Website Booking
                if(isset($book_res->reservation_response)){
                    $reservation_data = json_decode($book_res->reservation_response);
                    if($reservation_data){
                        $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                        $no_of_nights = abs(round($diff / 86400));
                        
                        $total_nights += $no_of_nights;
                        
                        foreach($reservation_data->hotel_details->rooms as $room_res){
                            $supplier_rooms_bookings[] = (Object)[
                                'booking_type'      => 'Website Booking',
                                'client_Id'         => $client_Data->id ?? '',
                                'client_Name'       => $client_Data->company_name ?? '',
                                'created_at'        => $book_res->booking_at,
                                'booking_id'        => $book_res->booking_id,
                                'hotel_name'        => $reservation_data->hotel_details->hotel_name,
                                'room_id'           => $room_res->room_code,
                                'room_type'         => $room_res->room_name,
                                'check_in'          => $reservation_data->hotel_details->checkOut,
                                'check_out'         => $reservation_data->hotel_details->checkOut,
                                'no_of_nights'      => $no_of_nights,
                                'rooms_qty'         => $room_res->room_rates[0]->room_qty,
                                'rooms_price'       => $room_res->room_rates[0]->net,
                                'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                            ];
                            $total_price            = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                            $total_payable_price    += $total_price;
                            $total_rooms_count      += $room_res->room_rates[0]->room_qty;  
                        }
                    }
                }
                $c++;
            }
            
            // dd($supplier_rooms_bookings);
            
            $payments_data          = DB::table('recevied_payments_details')
                                        ->where('Criteria','Hotel Supplier')
                                        ->where('Content_Ids',$supplierId)
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data','exchange_rate','company_amount')
                                        ->orderBy('check_in')
                                        ->get();
                
            $make_payments_data     = DB::table('make_payments_details')
                                        ->where('Criteria','Hotel Supplier')
                                        ->where('Content_Ids',$supplierId)
                                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data','exchange_rate','company_amount')
                                        ->orderBy('check_in')
                                        ->get();
        }
        
        $supplier_rooms_bookings    = collect($supplier_rooms_bookings);
        $all_data                   = $supplier_rooms_bookings->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
        $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('id',$supplierId)->first();
        
        if($clientId == 4){
            $today_Date     = date('Y-m-d');
            $season_Details = DB::table('add_Seasons')->where('customer_id', $clientId)
                                ->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            $start_Date     = $season_Details->start_Date;
            $end_Date       = $season_Details->end_Date;
            if($season_Details != null){
                $filtered_data = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                    if (!isset($record->check_in)) {
                        return false;
                    }
                    $checkIn    = Carbon::parse($record->check_in);
                    $checkOut   = isset($record->check_out) ? Carbon::parse($record->check_out) : $checkIn;
                    return $checkIn->between($start_Date, $end_Date) || $checkOut->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkOut->gte($end_Date));
                });
                
                $all_data = $filtered_data;
            }
        }
        
        
        return $this->getSupplierOverallPayableDetails($other_Supplier,$currency,$all_data);
        
        
        
    }
    
    private function getSupplierOverallPayableDetails($suppliers,$currency,$allBooking){
        $totalRevenue = 0;
        $totalPaid = 0;

        $purchase_currency = '';
        foreach($suppliers as $supplier){
            $purchase_currency          = $supplier->currrency;
        }
        
        foreach($allBooking as $booking){
            
            $sale_currency              = $currency;
            if(isset($booking->converion_data)){
                $conversion_data        = json_decode($booking->converion_data);
                if(isset($conversion_data->purchase_currency)){
                    $purchase_currency  = $conversion_data->purchase_currency;
                    $sale_currency      = $conversion_data->sale_currency;
                }
            }
            
            $totalRevenue += $booking->company_amount ?? $booking->rooms_total_price ?? 0;
            $totalPaid    += $booking->purchase_amount ?? 0;
        }
        
        return [
                'totalRevenue' => $totalRevenue,
                'totalPaid' => $totalPaid,
            ];
    }
}
