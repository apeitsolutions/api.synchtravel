<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\country;
use DateTime;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\addManageInvoice;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use DB;
use Illuminate\Support\Collection;

class HotelSupplierController extends Controller
{
    public function add_Supplier_To_Customer(Request $request1){
        DB::beginTransaction();
        try {
            $request            = json_decode($request1->suuplier_Details);
            
            $words_RSN          = preg_split('/\s+/', $request->room_supplier_name);
            $room_supplier_name = '';
            foreach ($words_RSN as $word) {
                if (ctype_alpha($word[0])) {
                    $room_supplier_name .= strtoupper($word[0]);
                }
            }
            
            // dd($room_supplier_name);
            
            if($request->room_supplier_name != null && $request->room_supplier_name != '' && $request->email != null && $request->email != ''){
                $data = DB::table('rooms_Invoice_Supplier')->insert([  
                    'SU_id'                 => $request->SU_id ?? NULL,
                    'sub_customer_id'       => $request1->customer_id,
                    'customer_id'           => $request1->customer_Details,
                    'opening_balance'       => $request->opening_balance,
                    'balance'               => $request->opening_balance,
                    'opening_payable'       => $request->payable,
                    'payable'               => $request->payable,
                    'room_supplier_name'    => $request->room_supplier_name,
                    'room_supplier_code'    => $room_supplier_name.'-'.rand(0,4444),
                    'email'                 => $request->email,
                    'phone_no'              => $request->phone_no,
                    'address'               => $request->address,
                    'contact_person_name'   => $request->contact_person_name,
                    'country'               => $request->country,
                    'city'                  => $request->city,
                    'more_phone_no'         => $request->more_phone_no,
                ]);
                if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                    $all_HS = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
                }else{
                    $all_HS = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
                }
                
                DB::commit();
                
                return response()->json(['message'=>'success','Status'=>'SuccessFull','data'=>$data,'all_HS'=>$all_HS]);
            }else{
                return response()->json(['message'=>'error']);    
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
   
    public function hotel_suppliers_statement(Request $request){
        $supplier_id            = $request->supplier_id;
        $customer_Id            = $request->customer_id;
        
        // dd($supplier_id,$customer_Id);
        
        if(isset($request->start_date)){
            $startDate          = $request->start_date;
            $endDate            = $request->end_date;
            $supplierBooking    = [];
            $other_Supplier     = DB::table('rooms_Invoice_Supplier')->where('sub_Supplier_Id',$request->supplier_id)->get();
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
            
            if($request->supplier_id == 135 && $request->customer_id != 45){
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
                                        ->where(function ($query) use ($supplier_id,$customer_Id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name', $supplier_id)
                                            ->where('hotels_bookings.customer_id', $customer_Id)
                                            // ->WhereJsonContains('rooms.allowed_Clients', [['client_Id' => (string)$customer_Id]])
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->orWhere(function ($query) use ($supplier_id,$customer_Id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name_AR', $supplier_id)
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
                                        ->where(function ($query) use ($supplier_id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name', $supplier_id)
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->orWhere(function ($query) use ($supplier_id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name_AR', $supplier_id)
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
                                    ->where('Content_Ids',$request->supplier_id)
                                    ->whereDate('payment_date','>=', $startDate)
                                    ->whereDate('payment_date','<=', $endDate)
                                    ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate",'company_amount')
                                    ->orderBy('check_in')
                                    ->get();
                
            $make_payments_data = DB::table('make_payments_details')
                                    ->where('Criteria','Hotel Supplier')
                                    ->where('Content_Ids',$request->supplier_id)
                                    ->whereDate('payment_date','>=', $startDate)
                                    ->whereDate('payment_date','<=', $endDate)
                                    ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate",'company_amount')
                                    ->orderBy('check_in')
                                    ->get();
        }else{
            $supplierBooking        = [];
            $other_Supplier         = DB::table('rooms_Invoice_Supplier')->where('sub_Supplier_Id',$request->supplier_id)->get();
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
            
            if($request->supplier_id == 135 && $request->customer_id != 45){
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
                                        ->where(function ($query) use ($supplier_id,$customer_Id) {
                                            $query->where('rooms.room_supplier_name', $supplier_id)
                                            ->where('hotels_bookings.customer_id', $customer_Id);
                                            
                                            // ->WhereJsonContains('rooms.allowed_Clients', [['client_Id' => (string)$customer_Id]]);
                                        })
                                        ->orWhere(function ($query) use ($supplier_id,$customer_Id) {
                                            $query->where('rooms.room_supplier_name_AR', $supplier_id)
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
                                        ->where(function ($query) use ($supplier_id) {
                                            $query->where('rooms.room_supplier_name', $supplier_id);
                                        })
                                        ->orWhere(function ($query) use ($supplier_id) {
                                            $query->where('rooms.room_supplier_name_AR', $supplier_id);
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
                    // dd($matches);
                    $invoice_Currency   = $matches[0] ?? 'SAR';
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
                                        ->where('Content_Ids',$request->supplier_id)
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data','exchange_rate','company_amount')
                                        ->orderBy('check_in')
                                        ->get();
                
            $make_payments_data     = DB::table('make_payments_details')
                                        ->where('Criteria','Hotel Supplier')
                                        ->where('Content_Ids',$request->supplier_id)
                                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data','exchange_rate','company_amount')
                                        ->orderBy('check_in')
                                        ->get();
        }
        
        $supplier_rooms_bookings    = collect($supplier_rooms_bookings);
        $all_data                   = $supplier_rooms_bookings->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
        $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->first();
        
        $season_Details             = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        if($request->customer_id == 4 || $request->customer_id == 54){
            if(isset($request->start_date) && isset($request->end_date)){
            }else{
                $all_data           = $this->hotel_Suppliers_Season_Working($all_data,$request);
                // dd($all_data);
            }
        }
        
        // dd($all_data);
        
        $countries  = DB::table('countries')->get();
        
        return response()->json(['status'=>'success','countries'=>$countries,'supplier_details'=>$supplier_detail,'booking_statement'=>$all_data,'season_Details'=>$season_Details]);
    }
    
    public function hotel_Suppliers_Season_Working($all_data,$request){
        // dd($request->start_date);
        $today_Date         = date('Y-m-d');
        // $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date     = $season_Details->start_Date;
            $end_Date       = $season_Details->end_Date;
            
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
        return $all_data;
    }
    
    public function hotel_suppliers_statement_Client(Request $request){
        $supplier_id            = $request->supplier_id;
        $customer_Id            = $request->customer_id;
        $client_Id              = $request->client_Id;
        // dd($supplier_id,$customer_Id,$client_Id); 
        
        if(isset($request->start_date)){
            $startDate          = $request->start_date;
            $endDate            = $request->end_date;
            $supplierBooking    = [];
            $other_Supplier     = DB::table('rooms_Invoice_Supplier')->where('sub_Supplier_Id',$request->supplier_id)->get();
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
                                        ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                        ->whereDate('rooms_bookings_details.check_in','<=', $endDate)
                                        ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                                ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                                ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID')
                                        ->orderBy('rooms.id','asc')
                                        ->get();
                if ($supplierBooking_1->isEmpty()) {} else {
                    array_push($supplierBooking,$supplierBooking_1[0]);
                }
            }
            
            if($request->supplier_id == 135 && $request->customer_id != 45){
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
                                        ->where(function ($query) use ($supplier_id,$customer_Id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name', $supplier_id)
                                            ->WhereJsonContains('rooms.allowed_Clients', [['client_Id' => (string)$customer_Id]])
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->orWhere(function ($query) use ($supplier_id,$customer_Id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name_AR', $supplier_id)
                                            ->WhereJsonContains('rooms.allowed_Clients', [['client_Id' => (string)$customer_Id]])
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                                ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                                ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID')
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
                                        ->where(function ($query) use ($supplier_id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name', $supplier_id)
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->orWhere(function ($query) use ($supplier_id,$startDate,$endDate) {
                                            $query->where('rooms.room_supplier_name_AR', $supplier_id)
                                            ->whereDate('rooms_bookings_details.check_in','>=', $startDate)
                                            ->whereDate('rooms_bookings_details.check_in','<=', $endDate);
                                        })
                                        ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                                ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                                ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID')
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
            
            foreach($supplierBooking as $book_res){
                
                $client_Data = DB::table('customer_subcriptions')->where('id',$book_res->CLIENT_ID)->first();
                
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
                                    ->where('Content_Ids',$request->supplier_id)
                                    ->whereDate('payment_date','>=', $startDate)
                                    ->whereDate('payment_date','<=', $endDate)
                                    ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                    ->orderBy('check_in')
                                    ->get();
                
            $make_payments_data = DB::table('make_payments_details')
                                    ->where('Criteria','Hotel Supplier')
                                    ->where('Content_Ids',$request->supplier_id)
                                    ->whereDate('payment_date','>=', $startDate)
                                    ->whereDate('payment_date','<=', $endDate)
                                    ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                    ->orderBy('check_in')
                                    ->get();
        }else{
            $supplierBooking        = [];
            $other_Supplier         = DB::table('rooms_Invoice_Supplier')->where('sub_Supplier_Id',$request->supplier_id)->get();
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
                                                ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID')
                                        ->orderBy('rooms.id','asc')
                                        ->get();
                if ($supplierBooking_1->isEmpty()) {} else {
                    array_push($supplierBooking,$supplierBooking_1[0]);
                }
            }
            
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
                                    ->where(function ($query) use ($supplier_id,$customer_Id,$client_Id) {
                                        $query->where('rooms.room_supplier_name', $supplier_id);
                                        $query->where('hotels_bookings.customer_id', '!=' ,$customer_Id);
                                        $query->where('hotels_bookings.customer_id', $client_Id);
                                        $query->where('rooms.room_supplier_name_AR', $supplier_id);
                                    })
                                    ->orWhere(function ($query) use ($supplier_id,$customer_Id,$client_Id) {
                                        $query->where('rooms.room_supplier_name_AR', $supplier_id);
                                        $query->where('hotels_bookings.customer_id', '!=' ,$customer_Id);
                                        $query->where('hotels_bookings.customer_id', $client_Id);
                                        $query->where('rooms.room_supplier_name_AR', $supplier_id);
                                    })
                                    ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name','rooms_bookings_details.quantity','rooms_bookings_details.date as booking_at'
                                            ,'tours.accomodation_details as accomodation_details_package','tours.accomodation_details_more  as accomodation_details_more_package','tours.id as package_id'
                                            ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                            ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response','hotels_bookings.customer_id as CLIENT_ID')
                                    ->orderBy('rooms.id','asc')
                                    ->get();
            // dd($supplierBooking_2);
            
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
                
                $client_Data = DB::table('customer_subcriptions')->where('id',$book_res->CLIENT_ID)->first();
                // dd($client_Data);
                
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
                                        ->where('Content_Ids',$request->supplier_id)
                                        ->WhereJsonContains('converion_data->customer_id', (string)$client_Id)
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data','exchange_rate','company_amount')
                                        ->orderBy('check_in')
                                        ->get();
                
            $make_payments_data     = DB::table('make_payments_details')
                                        ->where('Criteria','Hotel Supplier')
                                        ->where('Content_Ids',$request->supplier_id)
                                        ->WhereJsonContains('converion_data->customer_id', (string)$client_Id)
                                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data','exchange_rate','company_amount')
                                        ->orderBy('check_in')
                                        ->get();
        }
        
        $supplier_rooms_bookings    = collect($supplier_rooms_bookings);
        $all_data                   = $supplier_rooms_bookings->concat($payments_data)->concat($make_payments_data)->sortBy('check_in');
        $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->first();
        
        // dd($all_data);
        
        return response()->json(['status'=>'success','supplier_details'=>$supplier_detail,'booking_statement'=>$all_data]);
    }
    
    public function supplier_rooms_booking_subUser(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            // Get Top Three Suppliers Most Rooms Booking
            $supplierBookingCounts = DB::table('rooms_Invoice_Supplier')
            ->join('rooms', 'rooms_Invoice_Supplier.id', '=', 'rooms.room_supplier_name')
            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
            ->where('rooms_Invoice_Supplier.customer_id',$userData->id)
            ->where('rooms_Invoice_Supplier.SU_id',$request->SU_id)
            ->select('rooms_Invoice_Supplier.id','rooms_Invoice_Supplier.room_supplier_name','rooms_Invoice_Supplier.payable','rooms_Invoice_Supplier.currrency', DB::raw('sum(rooms_bookings_details.quantity) as booking_count'))
            ->groupBy('rooms_Invoice_Supplier.id')
            ->orderBy('booking_count','desc')
            ->limit(4)
            ->get();
            
            // Calaculate Their Nights of Bookings
            foreach($supplierBookingCounts as $index => $supplier_res){
                
                $supplierBooking = DB::table('rooms')
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
                    ->where('rooms.room_supplier_name',$supplier_res->id)
                    ->where('rooms.SU_id',$request->SU_id)
                    ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                            ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                            ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                            ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                    ->orderBy('rooms.id','asc')
                    ->get();
                    
                    
                    $total_nights = 0;
                    foreach($supplierBooking as $book_res){
                        
                        // From Accomodation Details
                        if(isset($book_res->accomodation_details)){
                            $accomodation_data = json_decode($book_res->accomodation_details);
                            if($accomodation_data){
                                foreach($accomodation_data as $acc_res){
                                    if($acc_res->hotel_supplier_id == $book_res->room_supplier_name  && $book_res->id == $acc_res->hotelRoom_type_id){
                                        $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                        $no_of_nights = abs(round($diff / 86400));
                                        
                                        $total_nights += $no_of_nights;
                                    }
                                }
                                
                                
                            }  
                        }
                        
                         // From More Accomodation Details
                        if(isset($book_res->accomodation_details_more)){
                            $accomodation_data = json_decode($book_res->accomodation_details_more);
                            if($accomodation_data){
                                foreach($accomodation_data as $acc_res){
                                    if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name  && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                        $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                        $no_of_nights = abs(round($diff / 86400));
                                        
                                        $total_nights += $no_of_nights;
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
                                    
                                
                                
                            }
                            
                        }
                        
                    }
                    
                    
                    $supplierBookingCounts[$index]->no_of_nights = $total_nights;
                    $supplierBookingCounts[$index]->payable = number_format($supplier_res->payable);
                    
                    
                    // echo "Night are $total_nights supplier id is ".$supplier_res->id;
                    // print_r($supplierBooking);
                
            }
            
            $series_data = [];
            $categories_data = [];
            
            $currentYear = date('Y');
            $monthsData = [];
            
            for ($month = 1; $month <= 12; $month++) {
                
                 $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                 $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                 
                  $categories_data[] = $startOfMonth->format('F');
                 
                 $startOfMonth = $startOfMonth->format('Y-m-d');
                 $endOfMonth = $endOfMonth->format('Y-m-d');

                $monthsData[] = (Object)[
                    'month' => $month,
                    'start_date' => $startOfMonth,
                    'end_date' => $endOfMonth,
                ];
            }
            
            foreach($supplierBookingCounts as $supplier_res){
                
           
                
                $supplier_booking_data = [];
                foreach($monthsData as $month_res){
                    // Add 7 days to the start date
                    
                    
                    $totalQuantityBooked = DB::table('rooms')
                                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                            ->where('rooms.room_supplier_name', $supplier_res->id)
                                            ->where('rooms.SU_id',$request->SU_id)
                                            ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                                            ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                                            ->sum('rooms_bookings_details.quantity');
                                            
                
                                                
                  
                    
                    $supplier_booking_data[] = floor($totalQuantityBooked * 100) / 100;
                   
                }
                
                $series_data[] = [
                        'name' => $supplier_res->room_supplier_name,
                        'data' => $supplier_booking_data
                    ];
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $supplierBookingCounts,
                'series_data' => $series_data,
                'categories_data' => $categories_data,
            ]);
            
        }else{
            return response()->json([
                'status' => 'error',
                'data' => '',
            ]);
        }
        
   }
    
    public function supplier_rooms_booking(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            
            // Get Top Three Suppliers Most Rooms Booking
            $supplierBookingCounts  = DB::table('rooms_Invoice_Supplier')
                                        ->join('rooms', 'rooms_Invoice_Supplier.id', '=', 'rooms.room_supplier_name')
                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                        ->where('rooms_Invoice_Supplier.customer_id',$userData->id)
                                        ->select('rooms_Invoice_Supplier.id','rooms_Invoice_Supplier.room_supplier_name','rooms_Invoice_Supplier.payable','rooms_Invoice_Supplier.currrency', DB::raw('sum(rooms_bookings_details.quantity) as booking_count'))
                                        ->groupBy('rooms_Invoice_Supplier.id')
                                        ->orderBy('booking_count','desc')
                                        ->limit(4)
                                        ->get();
            // Season
            if($userData->id == 4){
                $today_Date                 = date('Y-m-d');
                if(isset($request->season_Id)){
                    if($request->season_Id == 'all_Seasons'){
                        $season_Details     = null;
                    }elseif($request->season_Id > 0){
                        $season_Details     = DB::table('add_Seasons')->where('customer_id', $userData->id)->where('id', $request->season_Id)->first();
                    }else{
                        $season_Details     = null;
                    }
                }else{
                    $season_Details         = DB::table('add_Seasons')->where('customer_id', $userData->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
                }
                
                if($season_Details != null){
                    $start_Date             = $season_Details->start_Date;
                    $end_Date               = $season_Details->end_Date;
                    $supplierBookingCounts  = DB::table('rooms_Invoice_Supplier')
                                                ->join('rooms', 'rooms_Invoice_Supplier.id', '=', 'rooms.room_supplier_name')
                                                ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                ->where('rooms_Invoice_Supplier.customer_id',$userData->id)
                                                ->whereBetween('created_at', [$start_Date, $end_Date])
                                                ->select('rooms_Invoice_Supplier.id','rooms_Invoice_Supplier.room_supplier_name','rooms_Invoice_Supplier.payable','rooms_Invoice_Supplier.currrency', DB::raw('sum(rooms_bookings_details.quantity) as booking_count'))
                                                ->groupBy('rooms_Invoice_Supplier.id')
                                                ->orderBy('booking_count','desc')
                                                ->limit(4)
                                                ->get();
                }
            }
            // Season
            
            // Calaculate Their Nights of Bookings
            foreach($supplierBookingCounts as $index => $supplier_res){
    
                $supplierBooking = DB::table('rooms')
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
                    ->where('rooms.room_supplier_name',$supplier_res->id)
                    ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                            ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                            ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                            ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                    ->orderBy('rooms.id','asc')
                    ->get();
                    
                    
                    $total_nights = 0;
                    foreach($supplierBooking as $book_res){
                        
                        // From Accomodation Details
                        if(isset($book_res->accomodation_details)){
                            $accomodation_data = json_decode($book_res->accomodation_details);
                            if($accomodation_data){
                                foreach($accomodation_data as $acc_res){
                                    if($acc_res->hotel_supplier_id == $book_res->room_supplier_name  && $book_res->id == $acc_res->hotelRoom_type_id){
                                        $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                        $no_of_nights = abs(round($diff / 86400));
                                        
                                        $total_nights += $no_of_nights;
                                    }
                                }
                                
                                
                            }  
                        }
                        
                         // From More Accomodation Details
                        if(isset($book_res->accomodation_details_more)){
                            $accomodation_data = json_decode($book_res->accomodation_details_more);
                            if($accomodation_data){
                                foreach($accomodation_data as $acc_res){
                                    if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name  && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                        $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                        $no_of_nights = abs(round($diff / 86400));
                                        
                                        $total_nights += $no_of_nights;
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
                                    
                                
                                
                            }
                            
                        }
                        
                    }
                    
                    
                    $supplierBookingCounts[$index]->no_of_nights = $total_nights;
                    $supplierBookingCounts[$index]->payable = number_format($supplier_res->payable);
                    
                    
                    // echo "Night are $total_nights supplier id is ".$supplier_res->id;
                    // print_r($supplierBooking);
                
            }
            
            $series_data        = [];
            $categories_data    = [];
            $currentYear        = date('Y');
            $monthsData         = [];
            
            for ($month = 1; $month <= 12; $month++) {
                
                 $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                 $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                 
                  $categories_data[] = $startOfMonth->format('F');
                 
                 $startOfMonth = $startOfMonth->format('Y-m-d');
                 $endOfMonth = $endOfMonth->format('Y-m-d');

                $monthsData[] = (Object)[
                    'month' => $month,
                    'start_date' => $startOfMonth,
                    'end_date' => $endOfMonth,
                ];
            }
            
            foreach($supplierBookingCounts as $supplier_res){
                
           
                
                $supplier_booking_data = [];
                foreach($monthsData as $month_res){
                    // Add 7 days to the start date
                    
                    
                    $totalQuantityBooked = DB::table('rooms')
                                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                            ->where('rooms.room_supplier_name', $supplier_res->id)
                                            ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                                            ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                                            ->sum('rooms_bookings_details.quantity');
                                            
                
                                                
                  
                    
                    $supplier_booking_data[] = floor($totalQuantityBooked * 100) / 100;
                   
                }
                
                $series_data[] = [
                        'name' => $supplier_res->room_supplier_name,
                        'data' => $supplier_booking_data
                    ];
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $supplierBookingCounts,
                'series_data' => $series_data,
                'categories_data' => $categories_data,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'data' => '',
            ]);
        }
        
   }
   
    public function all_suppliers_booking_details(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            
            // Get Top Three Suppliers Most Rooms Booking
            $supplierBookingCounts = DB::table('rooms_Invoice_Supplier')
            ->join('rooms', 'rooms_Invoice_Supplier.id', '=', 'rooms.room_supplier_name')
            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
             ->where('rooms_Invoice_Supplier.customer_id',$userData->id)
            ->select('rooms_Invoice_Supplier.id','rooms_Invoice_Supplier.room_supplier_name','rooms_Invoice_Supplier.payable','rooms_Invoice_Supplier.currrency', DB::raw('sum(rooms_bookings_details.quantity) as booking_count'))
            ->groupBy('rooms_Invoice_Supplier.id')
            ->orderBy('booking_count','desc')
            ->get();
            
            
            // Calaculate Their Nights of Bookings
            foreach($supplierBookingCounts as $index => $supplier_res){
    
                $supplierBooking = DB::table('rooms')
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
                    ->where('rooms.room_supplier_name',$supplier_res->id)
                    ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                            ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                            ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                            ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                    ->orderBy('rooms.id','asc')
                    ->get();
                    
                    
                    $total_nights = 0;
                    foreach($supplierBooking as $book_res){
                        
                        // From Accomodation Details
                        if(isset($book_res->accomodation_details)){
                            $accomodation_data = json_decode($book_res->accomodation_details);
                            if($accomodation_data){
                                foreach($accomodation_data as $acc_res){
                                    if($acc_res->hotel_supplier_id == $book_res->room_supplier_name  && $book_res->id == $acc_res->hotelRoom_type_id){
                                        $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                        $no_of_nights = abs(round($diff / 86400));
                                        
                                        $total_nights += $no_of_nights;
                                    }
                                }
                                
                                
                            }  
                        }
                        
                         // From More Accomodation Details
                        if(isset($book_res->accomodation_details_more)){
                            $accomodation_data = json_decode($book_res->accomodation_details_more);
                            if($accomodation_data){
                                foreach($accomodation_data as $acc_res){
                                    if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name  && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                        $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                        $no_of_nights = abs(round($diff / 86400));
                                        
                                        $total_nights += $no_of_nights;
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
                                    
                                
                                
                            }
                            
                        }
                        
                    }
                    
                    
                    $supplierBookingCounts[$index]->no_of_nights = $total_nights;
                    $supplierBookingCounts[$index]->payable = number_format($supplier_res->payable);
                    
                    // echo "Night are $total_nights supplier id is ".$supplier_res->id;
                    // print_r($supplierBooking);
                
            }
            
            return response()->json([
                    'status' => 'success',
                    'data' => $supplierBookingCounts,
                ]);
        //   print_r($supplierBookingCounts);  
        //   die;
        }else{
            return response()->json([
                    'status' => 'error',
                    'data' => '',
                ]);
        }
        
   }
    
    public function supplier_rooms_booking_details(Request $request){

       $supplierBooking = DB::table('rooms')
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
                    ->where('rooms.room_supplier_name',$request->supplier_id)
                    ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                            ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                            ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                            ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                    ->orderBy('rooms.id','asc')
                    ->get();
                    
        // print_r($supplierBooking);
        // die;
                    $total_nights = 0;
                    $total_payable_price = 0;
                    $total_rooms_count = 0;
                    $supplier_rooms_bookings = [];
                    foreach($supplierBooking as $book_res){
                        
                      
                        // From Accomodation Details
                        if(isset($book_res->accomodation_details)){
                            $accomodation_data = json_decode($book_res->accomodation_details);
                            if($accomodation_data){
                                foreach($accomodation_data as $acc_res){
                                    if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                        $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                        $no_of_nights = abs(round($diff / 86400));
                                        
                                        $total_nights += $no_of_nights;
                                        
                                        $supplier_rooms_bookings[] = (Object)[
                                                'hotel_name' => $acc_res->acc_hotel_name,
                                                'room_id' => $acc_res->hotelRoom_type_id,
                                                'room_type' => $acc_res->hotel_type_cat,
                                                'check_in' => $acc_res->acc_check_in,
                                                'check_out' => $acc_res->acc_check_out,
                                                'no_of_nights' => $no_of_nights,
                                                'rooms_qty' => $acc_res->acc_qty,
                                                'rooms_price' => $acc_res->price_per_room_purchase,
                                                'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                            ];
                                        
                                        $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                        $total_payable_price += $total_price;
                                        $total_rooms_count += $acc_res->acc_qty;
                                    }
                                }
                                
                                
                            }  
                        }
                        
                         // From More Accomodation Details
                        if(isset($book_res->accomodation_details_more)){
                            $accomodation_data = json_decode($book_res->accomodation_details_more);
                            if($accomodation_data){
                                foreach($accomodation_data as $acc_res){
                                    if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                        $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                        $no_of_nights = abs(round($diff / 86400));
                                        
                                        $total_nights += $no_of_nights;
                                        
                                         $supplier_rooms_bookings[] = (Object)[
                                                'hotel_name' => $acc_res->more_acc_hotel_name,
                                                'room_id' => $acc_res->more_hotelRoom_type_id,
                                                'room_type' => $acc_res->more_hotel_type_cat,
                                                'check_in' => $acc_res->more_acc_check_in,
                                                'check_out' => $acc_res->more_acc_check_out,
                                                'no_of_nights' => $no_of_nights,
                                                'rooms_qty' => $acc_res->more_acc_qty,
                                                'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                            ];
                                            
                                        $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                        $total_payable_price += $total_price;
                                        $total_rooms_count += $acc_res->more_acc_qty;    
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
                    
                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                    
                    $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                    // echo "<pre>";
                    // print_r($supplier_total_paid);
                    // echo "</pre>";
                    // die;
                    return response()->json([
                        'status' => 'success',
                        'supplier_booking_details' => $supplier_rooms_bookings,
                        'supplier_data' => $supplier_data,
                        'total_payable_price' => $total_payable_price,
                        'remaining_payable_from_table' => $supplier_data->payable,
                        'remaining_payable_calculate' => $total_payable_price - $supplier_total_paid,
                        'supplier_total_paid' => $supplier_total_paid,
                        'total_rooms_count' => $total_rooms_count,
                    ]);
                    // print_r($supplier_rooms_bookings);
   }
   
    public function hotel_supplier_reports(Request $request){
    //   print_r($request->all());
       $all_suppliers = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();

       return response()->json(['all_suppliers'=>$all_suppliers]);
   }
   
    public function hotel_supplier_reports_sub(Request $request){
        $request_data           =  json_decode($request->request_data);
        $hotel_Supplier_Data    = [];
        $check_in               = $request->check_in;
        $check_out              = $request->check_out;
        $customer_id            = $request->customer_id;
        
        if($request_data->report_type == 'all_data'){
            
            if($request_data->supplier == 'all'){
                // $hotel_supplier_ledger    = DB::table('hotel_supplier_ledger')
                //                             ->leftJoin('add_manage_invoices', 'hotel_supplier_ledger.invoice_no', '=', 'add_manage_invoices.id')
                //                             ->leftJoin('rooms_Invoice_Supplier', 'hotel_supplier_ledger.supplier_id', '=', 'rooms_Invoice_Supplier.id')
                //                             ->where('rooms_Invoice_Supplier.customer_id',$request->customer_id)
                //                             ->select('rooms_Invoice_Supplier.*', 'hotel_supplier_ledger.id','hotel_supplier_ledger.supplier_id','hotel_supplier_ledger.payment','hotel_supplier_ledger.invoice_no as hotel_supplier_invoice','add_manage_invoices.agent_Company_Name','hotel_supplier_ledger.date as booking_date',
                //                                 'add_manage_invoices.generate_id as invoice_id','add_manage_invoices.confirm as invoice_status','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name','add_manage_invoices.middle_name'
                //                             )
                //                             ->orderByRaw("JSON_EXTRACT(add_manage_invoices.accomodation_details, '$.acc_check_in') DESC")->get();
                // return response()->json(['hotel_supplier'=>$hotel_supplier_ledger]);
                
                $hotel_supplier_ledger  = DB::table('hotel_supplier_ledger')
                                            ->leftJoin('add_manage_invoices', 'hotel_supplier_ledger.invoice_no', '=', 'add_manage_invoices.id')
                                            ->leftJoin('rooms_Invoice_Supplier', 'hotel_supplier_ledger.supplier_id', '=', 'rooms_Invoice_Supplier.id')
                                            ->where('hotel_supplier_ledger.customer_id', $customer_id)
                                            ->where(function ($query) use ($check_in, $check_out) {
                                                $query->where(function ($query) use ($check_in, $check_out) {
                                                    $query->whereNotNull('add_manage_invoices.accomodation_details');
                                                });
                                            })
                                            ->get();
            
            }else{
                // $hotel_supplier_ledger    = DB::table('hotel_supplier_ledger')
                //                             ->leftJoin('add_manage_invoices', 'hotel_supplier_ledger.invoice_no', '=', 'add_manage_invoices.id')
                //                             ->leftJoin('rooms_Invoice_Supplier', 'hotel_supplier_ledger.supplier_id', '=', 'rooms_Invoice_Supplier.id')
                //                             ->where('hotel_supplier_ledger.supplier_id',$request_data->supplier)
                //                             ->select('rooms_Invoice_Supplier.*', 'hotel_supplier_ledger.id','hotel_supplier_ledger.supplier_id','hotel_supplier_ledger.payment','hotel_supplier_ledger.invoice_no as hotel_supplier_invoice','add_manage_invoices.agent_Company_Name','hotel_supplier_ledger.date as booking_date',
                //                             'add_manage_invoices.generate_id as invoice_id','add_manage_invoices.confirm as invoice_status','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name','add_manage_invoices.middle_name'
                //                             )
                //                             ->orderByRaw("JSON_EXTRACT(add_manage_invoices.accomodation_details, '$.acc_check_in') DESC")->get();
                // return response()->json(['hotel_supplier'=>$hotel_supplier_ledger]);
                
                $hotel_supplier_ledger  = DB::table('hotel_supplier_ledger')
                                            ->leftJoin('add_manage_invoices', 'hotel_supplier_ledger.invoice_no', '=', 'add_manage_invoices.id')
                                            ->leftJoin('rooms_Invoice_Supplier', 'hotel_supplier_ledger.supplier_id', '=', 'rooms_Invoice_Supplier.id')
                                            ->where('hotel_supplier_ledger.supplier_id', $request_data->supplier)
                                            ->where('hotel_supplier_ledger.customer_id', $customer_id)
                                            ->where(function ($query) use ($check_in, $check_out) {
                                                $query->where(function ($query) use ($check_in, $check_out) {
                                                    $query->whereNotNull('add_manage_invoices.accomodation_details');
                                                });
                                            })
                                            ->get();
            }
        }
      
        if($request_data->report_type == 'data_wise'){
            if($request_data->supplier == 'all'){
                
                // $hotel_supplier_ledger  = DB::table('hotel_supplier_ledger')
                //                             ->leftJoin('add_manage_invoices', 'hotel_supplier_ledger.invoice_no', '=', 'add_manage_invoices.id')
                //                             ->leftJoin('rooms_Invoice_Supplier', 'hotel_supplier_ledger.supplier_id', '=', 'rooms_Invoice_Supplier.id')
                //                             ->where('rooms_Invoice_Supplier.customer_id',$request->customer_id)
                //                             ->where(\DB::raw('JSON_EXTRACT(add_manage_invoices.accomodation_details, "$[*].acc_check_in")'), '<=', $request_data->check_in)
                //                             ->orwhere(\DB::raw('JSON_EXTRACT(add_manage_invoices.accomodation_details, "$[*].acc_check_out")'), '>=', $request_data->check_out)
                //                             ->whereJsonContains('add_manage_invoices.accomodation_details','acc_check_in')
                //                             ->whereJsonContains('add_manage_invoices.accomodation_details','acc_check_out')
                //                             ->select('rooms_Invoice_Supplier.*', 'hotel_supplier_ledger.id','hotel_supplier_ledger.supplier_id','hotel_supplier_ledger.payment','hotel_supplier_ledger.invoice_no as hotel_supplier_invoice','add_manage_invoices.agent_Company_Name','hotel_supplier_ledger.date as booking_date',
                //                                 'add_manage_invoices.generate_id as invoice_id','add_manage_invoices.confirm as invoice_status','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name','add_manage_invoices.middle_name'
                //                             )
                //                             ->orderByRaw("JSON_EXTRACT(add_manage_invoices.accomodation_details, '$.acc_check_in') DESC")->get();
                
                $hotel_supplier_ledger  = DB::table('hotel_supplier_ledger')
                                            ->leftJoin('add_manage_invoices', 'hotel_supplier_ledger.invoice_no', '=', 'add_manage_invoices.id')
                                            ->leftJoin('rooms_Invoice_Supplier', 'hotel_supplier_ledger.supplier_id', '=', 'rooms_Invoice_Supplier.id')
                                            ->where('hotel_supplier_ledger.customer_id', $customer_id)
                                            ->where(function ($query) use ($check_in, $check_out) {
                                                $query->where(function ($query) use ($check_in, $check_out) {
                                                    $query->whereNotNull('add_manage_invoices.accomodation_details');
                                                });
                                            })
                                            ->get();
            }
            else{
                // $hotel_supplier_ledger  = DB::table('hotel_supplier_ledger')
                //                             ->leftJoin('add_manage_invoices', 'hotel_supplier_ledger.invoice_no', '=', 'add_manage_invoices.id')
                //                             ->leftJoin('rooms_Invoice_Supplier', 'hotel_supplier_ledger.supplier_id', '=', 'rooms_Invoice_Supplier.id')
                //                             ->where('hotel_supplier_ledger.supplier_id',$request_data->supplier)
                //                             ->select('rooms_Invoice_Supplier.*', 'hotel_supplier_ledger.id','hotel_supplier_ledger.supplier_id','hotel_supplier_ledger.payment','hotel_supplier_ledger.invoice_no as hotel_supplier_invoice','add_manage_invoices.agent_Company_Name','hotel_supplier_ledger.date as booking_date',
                //                             'add_manage_invoices.generate_id as invoice_id','add_manage_invoices.confirm as invoice_status','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name','add_manage_invoices.middle_name'
                //                             )
                //                             ->orderByRaw("JSON_EXTRACT(add_manage_invoices.accomodation_details, '$.acc_check_in') DESC")->get();
                // return response()->json(['hotel_supplier'=>$hotel_supplier_ledger]);
                
                $hotel_supplier_ledger  = DB::table('hotel_supplier_ledger')
                                            ->leftJoin('add_manage_invoices', 'hotel_supplier_ledger.invoice_no', '=', 'add_manage_invoices.id')
                                            ->leftJoin('rooms_Invoice_Supplier', 'hotel_supplier_ledger.supplier_id', '=', 'rooms_Invoice_Supplier.id')
                                            ->where('hotel_supplier_ledger.supplier_id', $request_data->supplier)
                                            ->where('hotel_supplier_ledger.customer_id', $customer_id)
                                            ->where(function ($query) use ($check_in, $check_out) {
                                                $query->where(function ($query) use ($check_in, $check_out) {
                                                    $query->whereNotNull('add_manage_invoices.accomodation_details');
                                                });
                                            })
                                            ->get();
                
            }
        }
        
        
        if (!empty($hotel_supplier_ledger) || $hotel_supplier_ledger->count() > 0) {
            foreach($hotel_supplier_ledger as $val_HSL){
                if($val_HSL->payment > 0){
                    
                    if(isset($val_HSL->invoice_status) && $val_HSL->invoice_status == 1){
                        $status = 'Confirm';
                    }
                    else{
                        $status = 'Tentative';  
                    }
                    
                    if(isset($val_HSL->f_name) && $val_HSL->f_name != null && $val_HSL->f_name != ''){
                        $lead_name = $val_HSL->f_name ?? '' + ' ' + $val_HSL->middle_name ?? '';
                    }
                    else{
                        $lead_name = ''; 
                    }
                    
                    if(isset($val_HSL->agent_Company_Name) && $val_HSL->agent_Company_Name != null && $val_HSL->agent_Company_Name != ''){
                        $agent_Company_Name = $val_HSL->agent_Company_Name; 
                    }else{
                        $agent_Company_Name = $val_HSL->f_name ?? '' + ' ' + $val_HSL->middle_name ?? '';
                    }
                    
                    if(isset($val_HSL->accomodation_details) && $val_HSL->accomodation_details != null && $val_HSL->accomodation_details != ''){
                        // dd($accomodation_details);
                        $accomodation_details = json_decode($val_HSL->accomodation_details);
                        foreach($accomodation_details as $val_AD){
                            if($val_AD->acc_check_in >= $check_in && $val_AD->acc_check_out <= $check_out){
                                $supplier_Data = [
                                    'supplier_Id'           => $val_HSL->id,
                                    'room_supplier_name'    => $val_HSL->room_supplier_name,
                                    'invoice_id'            => $val_HSL->generate_id,
                                    'lead_name'             => $lead_name,
                                    'acc_hotel_name'        => $val_AD->acc_hotel_name,
                                    'acc_check_in'          => $val_AD->acc_check_in,
                                    'acc_check_out'         => $val_AD->acc_check_out,
                                    'acc_type'              => $val_AD->acc_type,
                                    'acc_qty'               => $val_AD->acc_qty,
                                    'booking_Status'        => $status,
                                    'payment'               => $val_HSL->payment,
                                    'booking_date'          => $val_HSL->date,
                                    'agent_Company_Name'    => $agent_Company_Name,
                                ];
                                array_push($hotel_Supplier_Data,$supplier_Data);
                            }
                        }
                    }
                    
                    if(isset($val_HSL->accomodation_details_more) && $val_HSL->accomodation_details_more != null && $val_HSL->accomodation_details_more != ''){
                        // dd($accomodation_details_more);
                        $accomodation_details_more = json_decode($val_HSL->accomodation_details_more);
                        if($accomodation_details_more != null && count($accomodation_details_more) > 0){
                            foreach($accomodation_details_more as $val_MAD){
                                if($val_MAD->more_acc_check_in >= $check_in && $val_MAD->more_acc_check_out <= $check_out){
                                    $supplier_Data = [
                                        'supplier_Id'           => $val_HSL->id,
                                        'room_supplier_name'    => $val_HSL->room_supplier_name,
                                        'invoice_id'            => $val_HSL->generate_id,
                                        'lead_name'             => $lead_name,
                                        'acc_hotel_name'        => $val_MAD->more_acc_hotel_name,
                                        'acc_check_in'          => $val_MAD->more_acc_check_in,
                                        'acc_check_out'         => $val_MAD->more_acc_check_out,
                                        'acc_type'              => $val_MAD->more_acc_type,
                                        'acc_qty'               => $val_MAD->more_acc_qty,
                                        'booking_Status'        => $status,
                                        'payment'               => $val_HSL->payment,
                                        'booking_date'          => $val_HSL->date,
                                        'agent_Company_Name'    => $agent_Company_Name,
                                    ];
                                    array_push($hotel_Supplier_Data,$supplier_Data);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return response()->json(['hotel_supplier'=>$hotel_Supplier_Data]);
   }
   
    // PDF
    public function get_PDF_AD(Request $request){
        $all_send_pdf = DB::table('send_PDF_AD')->where('token',$request->token)->where('status','Pending')->get();
        return response()->json(['all_send_pdf'=>$all_send_pdf]);
    }
    
    public function send_PDF_AD(Request $request){
        // dd($request->selected_date);
        
        $hotel_supplier = DB::table('add_manage_invoices')
                            ->where('customer_id',$request->customer_id)
                            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                            ->get();
        
        $aray_data = array();
        foreach($hotel_supplier as $hotel){
            
            if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL){
                $accomodation_details = json_decode($hotel->accomodation_details);
                
                // dd($accomodation_details);
                
                foreach($accomodation_details as $arr_hotel){
                    if($arr_hotel->acc_check_in == $request->selected_date){  
                        $aray_data[]=(object)[
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                        ];
                    }
                }
            }
            
            if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL){
              
                $accomodation_details_more=json_decode($hotel->accomodation_details_more);
             
                foreach($accomodation_details_more as $arr_hotel){
                    if($arr_hotel->more_acc_check_in == $request->selected_date){
                        $aray_data[]=(object)[
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=>  date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                        ];
                    }
                }
            }
        }
        
        $all_send_pdf = DB::table('send_PDF_AD')->where('customer_id',$request->customer_id)->get();
        
        return response()->json(['hotel_supplier'=>$aray_data,'all_send_pdf'=>$all_send_pdf]);
    }
    
    public function add_PDF_AD(Request $request){
        DB::table('send_PDF_AD')->insert([
            'customer_id'       => $request->customer_id,
            'token'             => $request->token,
            'customer_number'   => $request->customer_number,
            'selected_dateTime' => $request->selected_dateTime,
            'selected_date'     => $request->selected_date,
            'selected_time'     => $request->selected_time,
            'status'            => 'Pending',
        ]);
        
        return response()->json(['message'=>'success']);
    }
    
    public function change_PDF_AD(Request $request){
        $all_send_pdf = DB::table('send_PDF_AD')->where('customer_id',$request->customer_id)->where('id',$request->id)->update(['status'=>'send']);
        return response()->json(['message'=>'Status Changed']);
    }
    // PDF
    
    public function all_Cients_Arrival_list(Request $request){
        $all_suppliers  = DB::table('rooms_Invoice_Supplier')->get();
        $all_agents     = DB::table('Agents_detail')->get();
        $all_customers  = DB::table('booking_customers')->get();
        $all_countries  = DB::table('countries')->get();
        $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        return response()->json(['all_suppliers'=>$all_suppliers,'all_agents'=>$all_agents,'all_customers'=>$all_customers,'all_countries'=>$all_countries,'season_Details'=>$season_Details]);
    }
    
    public function all_Cients_Arrival_list_Report(Request $request){
        $request_data =  json_decode($request->request_data);
      
        $start_date         = '';
        $end_date           = '';
        
        if($request_data->report_type == 'data_wise'){
            $start_date     = $request_data->check_in;
            $end_date       = $request_data->check_out;
        }
        
        if($request_data->report_type == 'data_today_wise'){
            $start_date     = date('Y-m-d');
            $end_date       = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_tomorrow_wise'){
            $start_date     = date('Y-m-d',strtotime("+1 days"));
            $end_date       = date('Y-m-d',strtotime("+1 days"));
        }
        
        if($request_data->report_type == 'data_week_wise'){
            $startOfWeek    = Carbon::now()->startOfWeek();
            $start_date     = $startOfWeek->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfWeek();
            $end_date       = $endOfWeek->format('Y-m-d');
            // dd($start_date,$end_date,$request_data);
            // $end_date       = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_month_wise'){
            $startOfMonth   = Carbon::now()->startOfMonth();
            $start_date     = $startOfMonth->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfMonth();
            $end_date       = $endOfWeek->format('Y-m-d');
            // $end_date       = date('Y-m-d');
        }
        
        if(isset($request_data->person_report_type)){
            $person_report_type = $request_data->person_report_type;
        }else if(isset($request_data->person_report_type)){
            $person_report_type = $request_data->report_type;
        }else{
            $person_report_type = '';
        }
      
        //********************************************
        // Invoice Working
        //********************************************
      
        $invoices_query                 = DB::table('add_manage_invoices');
        $invoice_select_common_element  = ['customer_id','generate_id','confirm','accomodation_details','accomodation_details_more','f_name','middle_name','created_at','id','lead_fname','lead_lname'];
        
        if($person_report_type != '' && $person_report_type == 'AgentWise'){
            $invoices_query->select('id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
            if($request_data->agent_Id == 'all_agent'){
                $invoices_query->where('agent_Id','>',0);
            }
          
            if($request_data->agent_Id != 'all_agent'){
                $invoices_query->where('agent_Id',$request_data->agent_Id);
            }
        }else if($person_report_type != '' && $person_report_type == 'CustomerWise'){
            $invoices_query->select('id','booking_customer_id',...$invoice_select_common_element);
            if($request_data->customer_Id == 'all_customer'){
                $invoices_query->where('booking_customer_id','>',0);
            }
          
            if($request_data->customer_Id != 'all_customer'){
                $invoices_query->where('booking_customer_id',$request_data->customer_Id);
            }
        }else{
            $invoices_query->select('id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
        }
        
        $invoices_data = $invoices_query->get();
      
        $aray_data = [];
        foreach($invoices_data as $invoice_res){
            
            if(isset($invoice_res->customer_id) && $invoice_res->customer_id != null && $invoice_res->customer_id != ''){
                $client_Details = DB::table('customer_subcriptions')->where('id',$invoice_res->customer_id)->first();
                // $client_Name    = $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')';
                // return $client_Name;
            }else{
                $client_Details = '';
            }
            
            $invoice_Supplier_Name = '';
            
            if($request_data->report_type == 'all_data'){
                
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                    'room_type'=>$arr_hotel->more_acc_type,
                                    'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                    'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'=>$arr_hotel->more_acc_qty,
                                    'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                    'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                    'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                        'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'=> $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                    'room_type'=> $arr_hotel->acc_type,
                                    'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                    'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                    'quantity'=> $arr_hotel->acc_qty,
                                    'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                    'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                    'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                        'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                    ];
                                }
                            }
                        }
                    }
                }
                
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                        'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            'room_type'=>$arr_hotel->more_acc_type,
                                            'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$arr_hotel->more_acc_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                            'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                            'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        // return $invoice_res;
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                      
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    
                                    // return $arr_hotel;
                                    
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                        'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $arr_hotel->acc_qty,
                                            'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                            'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                            'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                        ];
                                    }
                                }
                             
                            }
                        }
                    }
                }
            }
        }
      
        //********************************************
        //Package Working
        //********************************************
      
        $invoices_query                 = DB::table('cart_details')->join('tours','tours.id','=','cart_details.tour_id')->join('tours_bookings','tours_bookings.id','=','cart_details.booking_id');
        $invoice_select_common_element  = ['tours.customer_id','cart_details.booking_id as booking_id','tours.id as tour_id','cart_details.agent_name as agent_Id','cart_details.invoice_no as generate_id','cart_details.confirm','tours.accomodation_details','tours.accomodation_details_more','tours_bookings.passenger_name as f_name','cart_details.created_at','cart_details.cart_total_data'];
        // return $invoices_query;
        
        if($person_report_type != '' && $person_report_type == 'AgentWise'){
            if($request_data->agent_Id == 'all_agent'){
                $invoices_query->where('cart_details.agent_name','>',0);
            }
            
            if($request_data->agent_Id != 'all_agent'){
                $invoices_query->where('cart_details.agent_name',$request_data->agent_Id);
            }
        }else if($person_report_type != '' && $person_report_type == 'CustomerWise'){
            if($request_data->customer_Id == 'all_customer'){
                $invoices_query->where(function($query){
                    $query->where('agent_name','-1')->orWhere('agent_name','=',NULL);
                });
            }
          
            if($request_data->customer_Id != 'all_customer'){
                $invoices_query->whereJsonContains('cart_details.cart_total_data->customer_id',"$request_data->customer_Id");
            }
        }else{
            // $invoices_query->select($invoice_select_common_element);
        }
        
        $invoices_query->select($invoice_select_common_element);
        $invoices_data = $invoices_query->get();
        
        foreach($invoices_data as $invoice_res){
            $invoice_res->confirm = "confirm";
            
            if(isset($invoice_res->customer_id) && $invoice_res->customer_id != null && $invoice_res->customer_id != ''){
                $client_Details = DB::table('customer_subcriptions')->where('id',$invoice_res->customer_id)->first();
            }else{
                $client_Details = '';
            }
            
            if(isset($invoice_res->agent_Id) && $invoice_res->agent_Id > 0){
                $agent_Details_PB                   = DB::table('Agents_detail')->where('id',$invoice_res->agent_Id)->first();
                $agent_id                           = $agent_Details_PB->id;
                $agent_name                         = $agent_Details_PB->agent_Name ?? '';
                $agent_company_name                 = $agent_Details_PB->agent_company_name ?? '';
            }else{
                if(isset($invoice_res->booking_customer_id) && $invoice_res->booking_customer_id != null && $invoice_res->booking_customer_id != ''){
                    $customer_Details_PB            = DB::table('booking_customers')->where('id',$invoice_res->booking_customer_id)->first();
                    $agent_id                       = $customer_Details_PB->id;
                    $agent_name                     = $customer_Details_PB->agent_Name ?? '';
                    $agent_company_name             = $customer_Details_PB->agent_company_name ?? '';
                }else{
                    $cart_total_data                = json_decode($invoice_res->cart_total_data);
                    if(isset($cart_total_data->agent_name) && $cart_total_data->agent_name > 0){
                        $agent_Details_PB           = DB::table('Agents_detail')->where('id',$cart_total_data->agent_name)->first();
                        $agent_id                   = $agent_Details_PB->id;
                        $agent_name                 = $agent_Details_PB->agent_Name ?? '';
                        $agent_company_name         = $agent_Details_PB->agent_company_name ?? '';
                    }else{
                        if(isset($cart_total_data->customer_id) && $cart_total_data->customer_id > 0){
                            $customer_Details_PB    = DB::table('booking_customers')->where('id',$cart_total_data->customer_id)->first();
                            $agent_id               = $customer_Details_PB->id ?? '';
                            $agent_name             = $customer_Details_PB->name ?? '';
                            $agent_company_name     = $customer_Details_PB->name ?? '';
                        }
                    }
                }
            }
            
            if($request_data->report_type == 'all_data'){
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'            => $invoice_res->generate_id,
                                    'invoice_id'            => $invoice_res->tour_id,
                                    'booking_id'            => $invoice_res->booking_id,
                                    'check_in'              => date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'             => date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'            => $arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'       => $arr_hotel->more_hotel_city,
                                    'room_type'             => $arr_hotel->more_acc_type,
                                    'reservation_no'        => $arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'              => $room_qty,
                                    'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'   => $invoice_res->f_name,
                                    'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                    'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                    'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                    'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'          => 'Package',
                                    'status'                => $invoice_res->confirm,
                                    'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                    'dashboard_Address'     => $client_Details->dashboard_Address ?? '',
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'            => $invoice_res->generate_id,
                                        'invoice_id'            => $invoice_res->tour_id,
                                        'booking_id'            => $invoice_res->booking_id,
                                        'check_in'              => date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'             => date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'            => $arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'       => $arr_hotel->more_hotel_city,
                                        'room_type'             => $arr_hotel->more_acc_type,
                                        'reservation_no'        => $arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'              => $room_qty,
                                        'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'   => $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'          => 'Package',
                                        'status'                => $invoice_res->confirm,
                                        'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                        'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $room_qty                   = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'            => $invoice_res->generate_id,
                                    'invoice_id'            => $invoice_res->tour_id,
                                    'booking_id'            => $invoice_res->booking_id,
                                    'check_in'              => date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'             => date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'            => $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'       => $arr_hotel->hotel_city_name,
                                    'room_type'             => $arr_hotel->acc_type,
                                    'reservation_no'        => $arr_hotel->room_reservation_no ?? '',
                                    'quantity'              => $room_qty,
                                    'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'   => $invoice_res->f_name,
                                    'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                    'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                    'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                    'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'          => 'Package',
                                    'status'                => $invoice_res->confirm,
                                    'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                    'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $room_qty                   = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'            => $invoice_res->generate_id,
                                        'invoice_id'            => $invoice_res->tour_id,
                                        'booking_id'            => $invoice_res->booking_id,
                                        'check_in'              => date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'             => date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'            => $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'       => $arr_hotel->hotel_city_name,
                                        'room_type'             => $arr_hotel->acc_type,
                                        'reservation_no'        => $arr_hotel->room_reservation_no ?? '',
                                        'quantity'              => $room_qty,
                                        'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'   => $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'          => 'Package',
                                        'status'                => $invoice_res->confirm,
                                        'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                        'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                    ];
                                }
                            }
                        }
                    }
                }
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'booking_id'            => $invoice_res->booking_id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$room_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                        'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                        'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'booking_id'            => $invoice_res->booking_id,
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            'room_type'=>$arr_hotel->more_acc_type,
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                            'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                            'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                            'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                            'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'booking_id'            => $invoice_res->booking_id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $room_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                        'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                        'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'booking_id'            => $invoice_res->booking_id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                            'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                            'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                            'client_Name'       => $client_Details->name.' '.$client_Details->lname.'('.$client_Details->company_name.')',
                                            'dashboard_Address' => $client_Details->dashboard_Address ?? '',
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
      
        return response()->json(['hotel_supplier'=>$aray_data]);
    }
    
    public function supplierCheckin(Request $request){
        $all_countries  = DB::table('countries')->get();
        $all_suppliers  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
        $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        return response()->json(['all_countries'=>$all_countries,'all_suppliers'=>$all_suppliers,'season_Details'=>$season_Details]);
    }
    
    public function checkSupplierCheckin(Request $request){
        $todayDate          = date('d-m-Y');
        $data_arr           = [];
        $matchedDates       = [];
        $matchedIds         = [];
        $sr                 = 1;
        $listChecking       = json_decode($request->listChecking);
        $pdf_checkin_data   = collect($listChecking);
        $pdf_checkin_data   = $pdf_checkin_data instanceof Collection ? $pdf_checkin_data : collect($pdf_checkin_data);
        $uniqueDates        = $pdf_checkin_data->pluck('check_in')->filter()->unique()->implode(',');
        $customerId         = $request->customer_id;
        
        if (!empty($uniqueDates)) {
            $results = DB::select("CALL match_checkins_bulk(?, ?)", [$uniqueDates, $customerId]);
            
            foreach ($results as $row) {
                // if (in_array($row->id, $matchedIds)) continue;
                
                if ($row->supplier_name == NULL) continue;
                
                if ($row->From < $todayDate) continue;
                
                $confirm = 'Confirmed';
                if($row->confirm != '1'){
                    $confirm = 'Tentative';
                }
                
                $From           = !empty($row->From) ? date('d/m/Y', strtotime(str_replace('/', '-', $row->From))) : '';
                $matchedRecords = $pdf_checkin_data->where('From', $From);
                if ($matchedRecords) {
                    $dataPdf        = $matchedRecords->values();
                    $RsvPdf         = $dataPdf[0]->Rsv;
                    
                    $maxLength      = 25;
                    if (strlen($dataPdf[0]->GuestName) > $maxLength) {
                        $shortGuestName = substr($dataPdf[0]->GuestName, 0, strrpos(substr($dataPdf[0]->GuestName, 0, $maxLength), ' ')) . '...';
                    } else {
                        $shortGuestName = $dataPdf[0]->GuestName;
                    }
                    $GuestNamePdf       = $shortGuestName;
                    
                    if (strlen($dataPdf[0]->Hotel) > $maxLength) {
                        $shortHotelName = substr($dataPdf[0]->Hotel, 0, strrpos(substr($dataPdf[0]->Hotel, 0, $maxLength), ' ')) . '...';
                    } else {
                        $shortHotelName = $dataPdf[0]->Hotel;
                    }
                    $HotelPdf           = $shortHotelName;
                }
                
                $f_name         = $row->f_name ?? '';
                $middle_name    = $row->middle_name ?? '';
                
                $data_arr[]         = [
                    'SR'            => $sr++,
                    'status'        => $row->status ?? 'Found',
                    'id'            => $row->id,
                    'Rsv#'          => $row->{"Rsv#"},
                    'RsvPdf'        => $RsvPdf ?? '',
                    'GuestNamePdf'  => $f_name.' '.$middle_name,
                    'HotelPdf'      => $row->hotelName ?? '',
                    'From'          => $row->From,
                    'Till'          => $row->Till,
                    'supplier_name' => $row->supplier_name,
                    'state'         => $confirm,
                ];
                $matchedIds[]       = $row->id;
            }
        }
        
        // Build list of matched check-in dates from procedure results
        $matchedDates = collect($results)->pluck('From')->map(function ($date) {
            // Convert back to Y-m-d format (since you use '%d-%m-%Y' in SQL)
            return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->toDateString();
        })->unique()->toArray();
        
        // Add unmatched records
        foreach ($pdf_checkin_data as $unmatched) {
            if (empty($unmatched->check_in)) continue;
            
            $checkIn = $unmatched->check_in;
            
            // Skip if this date was already matched in results
            if (in_array($checkIn, $matchedDates)) continue;
            
            // Optional: Avoid duplicate Rsvs
            if (in_array($unmatched->Rsv ?? '', $matchedIds)) continue;
            
            if ($unmatched->From < $todayDate) continue;
            
            if (isset($unmatched->supplier_name)){
                if(empty($unmatched->supplier_name)){
                    continue;
                }
            }
            
            $status         = 'Tentative';
            if(isset($unmatched->state)){
                if($unmatched->state == 'C'){
                    $status = 'Confirmed';
                }
            }
            
            $From = !empty($unmatched->From) ? date('d-m-Y', strtotime(str_replace('/', '-', $unmatched->From))) : '';
            $Till = !empty($unmatched->Till) ? date('d-m-Y', strtotime(str_replace('/', '-', $unmatched->Till))) : '';
            
            $maxLength      = 25;
            if (strlen($unmatched->GuestName) > $maxLength) {
                $shortGuestName = substr($unmatched->GuestName, 0, strrpos(substr($unmatched->GuestName, 0, $maxLength), ' ')) . '...';
            } else {
                $shortGuestName = $unmatched->GuestName;
            }
            $GuestNamePdf       = $shortGuestName;
            
            if (strlen($unmatched->Hotel) > $maxLength) {
                $shortHotelName = substr($unmatched->Hotel, 0, strrpos(substr($unmatched->Hotel, 0, $maxLength), ' ')) . '...';
            } else {
                $shortHotelName = $unmatched->Hotel;
            }
            $HotelPdf           = $shortHotelName;
            
            $data_arr[] = [
                'SR'            => $sr++,
                'status'        => 'Booking Not Found',
                'id'            => '',
                'Rsv#'          => '',
                'RsvPdf'        => $unmatched->Rsv ?? '',
                'GuestNamePdf'  => $GuestNamePdf ?? '',
                'HotelPdf'      => $HotelPdf ?? '',
                'From'          => $From ?? '',
                'Till'          => $Till ?? '',
                'supplier_name' => $unmatched->supplier_name ?? '',
                'state'         => $status,
                
            ];
        }
        
        usort($data_arr, function ($a, $b) {
            $dateA = strtotime($a['From']);
            $dateB = strtotime($b['From']);
            return $dateA <=> $dateB; // ascending order
        });
        
        return response()->json([
            'supplierData'  => $data_arr
        ]);
    }
    
    public function hotel_supplier_reports_new(Request $request){
        $all_suppliers  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
        $all_agents     = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
        $customer_Data  = DB::table('customer_subcriptions')->where('id',$request->customer_id)->first();
        $b2b_All_Agents = DB::table('b2b_agents')->where('token',$customer_Data->Auth_key)->get();
        $all_customers  = DB::table('booking_customers')->where('customer_id',$request->customer_id)->get();
        $all_countries  = DB::table('countries')->get();
        $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        return response()->json(['season_Details'=>$season_Details,'all_suppliers'=>$all_suppliers,'b2b_All_Agents'=>$b2b_All_Agents,'all_agents'=>$all_agents,'all_customers'=>$all_customers,'all_countries'=>$all_countries]);
    }
    
    public function hotel_supplier_reports_sub_new_subUser(Request $request){
        // dd('OK');
        
        $request_data       =  json_decode($request->request_data);
        
        $start_date         = '';
        $end_date           = '';
        
        if($request_data->report_type == 'data_wise'){
            $start_date     = $request_data->check_in;
            $end_date       = $request_data->check_out;
        }
        
        if($request_data->report_type == 'data_today_wise'){
            $start_date     = date('Y-m-d');
            $end_date       = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_tomorrow_wise'){
            $start_date     = date('Y-m-d',strtotime("+1 days"));
            $end_date       = date('Y-m-d',strtotime("+1 days"));
        }
        
        if($request_data->report_type == 'data_week_wise'){
            $startOfWeek    = Carbon::now()->startOfWeek();
            $start_date     = $startOfWeek->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfWeek();
            $end_date       = $endOfWeek->format('Y-m-d');
            // dd($start_date,$end_date,$request_data);
            // $end_date       = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_month_wise'){
            $startOfMonth   = Carbon::now()->startOfMonth();
            $start_date     = $startOfMonth->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfMonth();
            $end_date       = $endOfWeek->format('Y-m-d');
            // $end_date       = date('Y-m-d');
        }
        
        if(isset($request_data->person_report_type)){
            $person_report_type = $request_data->person_report_type;
        }else if(isset($request_data->person_report_type)){
            $person_report_type = $request_data->report_type;
        }else{
            $person_report_type = '';
        }
      
        //********************************************
        // Invoice Working
        //********************************************
      
        $invoices_query                 = DB::table('add_manage_invoices')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id);
        $invoice_select_common_element  = ['all_services_quotation','generate_id','confirm','accomodation_details','accomodation_details_more','f_name','middle_name','created_at','id','lead_fname','lead_lname'];
        
        if($person_report_type != '' && $person_report_type == 'AgentWise'){
            $invoices_query->select('id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
            if($request_data->agent_Id == 'all_agent'){
                $invoices_query->where('agent_Id','>',0);
            }
          
            if($request_data->agent_Id != 'all_agent'){
                $invoices_query->where('agent_Id',$request_data->agent_Id);
            }
        }else if($person_report_type != '' && $person_report_type == 'CustomerWise'){
            $invoices_query->select('id','booking_customer_id',...$invoice_select_common_element);
            if($request_data->customer_Id == 'all_customer'){
                $invoices_query->where('booking_customer_id','>',0);
            }
          
            if($request_data->customer_Id != 'all_customer'){
                $invoices_query->where('booking_customer_id',$request_data->customer_Id);
            }
        }else{
            $invoices_query->select('id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
        }
        
        $invoices_data = $invoices_query->get();
        
        // return $invoices_data;
      
        $aray_data = [];
        foreach($invoices_data as $invoice_res){
            
            $invoice_Supplier_Name = '';
            
            if($request_data->report_type == 'all_data'){
                
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->more_hotel_room_view) && $arr_hotel->more_hotel_room_view != null && $arr_hotel->more_hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->more_hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                    'room_type'=>$arr_hotel->more_acc_type,
                                    'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                    'hotel_room_view'=>$hotel_room_view ?? '',
                                    'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'=>$arr_hotel->more_acc_qty,
                                    'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                    'all_services_quotation'=> $invoice_res->all_services_quotation,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->hotel_room_view) && $arr_hotel->hotel_room_view != null && $arr_hotel->hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'=> $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                    'room_type'=> $arr_hotel->acc_type,
                                    'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                    'hotel_room_view'=>$hotel_room_view ?? '',
                                    'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                    'quantity'=> $arr_hotel->acc_qty,
                                    'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                    'all_services_quotation'=> $invoice_res->all_services_quotation,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }
                    }
                }
                
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->more_hotel_room_view) && $arr_hotel->more_hotel_room_view != null && $arr_hotel->more_hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->more_hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            'room_type'=>$arr_hotel->more_acc_type,
                                            'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                            'hotel_room_view'=>$hotel_room_view ?? '',
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$arr_hotel->more_acc_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                            'all_services_quotation'=> $invoice_res->all_services_quotation,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        // return $invoice_res;
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->hotel_room_view) && $arr_hotel->hotel_room_view != null && $arr_hotel->hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                      
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                            'hotel_room_view'=>$hotel_room_view ?? '',
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $arr_hotel->acc_qty,
                                            'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                            'all_services_quotation'=> $invoice_res->all_services_quotation,
                                        ];
                                    }
                                }
                             
                            }
                        }
                    }
                }
            }
        }
        
        // dd($aray_data);
        
        // dd('BEFORE PACKAGE');
      
        //********************************************
        //Package Working
        //********************************************
      
        $invoices_query                 = DB::table('cart_details')
                                            // ->where('SU_id',$request->SU_id)
                                            ->where('client_id',$request->customer_id)
                                            ->join('tours','tours.id','=','cart_details.tour_id')->join('tours_bookings','tours_bookings.id','=','cart_details.booking_id');
        $invoice_select_common_element  = ['tours.id as tour_id','tours_bookings.id as tour_Booking_Id','cart_details.agent_name as agent_Id','cart_details.invoice_no as generate_id','cart_details.confirm','tours.accomodation_details','tours.accomodation_details_more','tours_bookings.passenger_name as f_name','cart_details.created_at','cart_details.cart_total_data'];
        // return $invoices_query;
        
        // return $person_report_type;
        
        if($person_report_type != '' && $person_report_type == 'AgentWise'){
            if($request_data->agent_Id == 'all_agent'){
                $invoices_query->where('cart_details.agent_name','>',0);
            }
            
            if($request_data->agent_Id != 'all_agent'){
                $invoices_query->where('cart_details.agent_name',$request_data->agent_Id);
            }
        }else if($person_report_type != '' && $person_report_type == 'CustomerWise'){
            if($request_data->customer_Id == 'all_customer'){
                $invoices_query->where(function($query){
                    $query->where('agent_name','-1')->orWhere('agent_name','=',NULL);
                });
            }
          
            if($request_data->customer_Id != 'all_customer'){
                $invoices_query->whereJsonContains('cart_details.cart_total_data->customer_id',"$request_data->customer_Id");
            }
        }else{
            // $invoices_query->select($invoice_select_common_element);
        }
        
        $invoices_query->select($invoice_select_common_element);
        $invoices_data = $invoices_query->get();
        // $invoices_data = $invoices_query;
        // return $invoices_data;
      
        // foreach($invoices_data as $invoice_res){
        //     if($request_data->report_type == 'all_data'){}
        // }
        
        foreach($invoices_data as $invoice_res){
            $invoice_res->confirm = "confirm";
            
            if(isset($invoice_res->agent_Id) && $invoice_res->agent_Id > 0){
                $agent_Details_PB                   = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$invoice_res->agent_Id)->first();
                $agent_id                           = $agent_Details_PB->id;
                $agent_name                         = $agent_Details_PB->agent_Name ?? '';
                $agent_company_name                 = $agent_Details_PB->agent_company_name ?? '';
            }else{
                if(isset($invoice_res->booking_customer_id) && $invoice_res->booking_customer_id != null && $invoice_res->booking_customer_id != ''){
                    $customer_Details_PB            = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$invoice_res->booking_customer_id)->first();
                    $agent_id                       = $customer_Details_PB->id;
                    $agent_name                     = $customer_Details_PB->agent_Name ?? '';
                    $agent_company_name             = $customer_Details_PB->agent_company_name ?? '';
                }else{
                    $cart_total_data                = json_decode($invoice_res->cart_total_data);
                    if(isset($cart_total_data->agent_name) && $cart_total_data->agent_name > 0){
                        $agent_Details_PB           = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$cart_total_data->agent_name)->first();
                        $agent_id                   = $agent_Details_PB->id;
                        $agent_name                 = $agent_Details_PB->agent_Name ?? '';
                        $agent_company_name         = $agent_Details_PB->agent_company_name ?? '';
                    }else{
                        if(isset($cart_total_data->customer_id) && $cart_total_data->customer_id > 0){
                            $customer_Details_PB    = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$cart_total_data->customer_id)->first();
                            $agent_id               = $customer_Details_PB->id ?? '';
                            $agent_name             = $customer_Details_PB->name ?? '';
                            $agent_company_name     = $customer_Details_PB->name ?? '';
                        }
                    }
                }
            }
            
            if($request_data->report_type == 'all_data'){
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'            => $invoice_res->generate_id,
                                    'invoice_id'            => $invoice_res->tour_id,
                                    'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                    'check_in'              => date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'             => date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'            => $arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'       => $arr_hotel->more_hotel_city,
                                    'room_type'             => $arr_hotel->more_acc_type,
                                    'reservation_no'        => $arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'              => $room_qty,
                                    'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'   => $invoice_res->f_name,
                                    'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                    'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                    'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                    'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'          => 'Package',
                                    'status'                => $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'            => $invoice_res->generate_id,
                                        'invoice_id'            => $invoice_res->tour_id,
                                        'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                        'check_in'              => date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'             => date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'            => $arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'       => $arr_hotel->more_hotel_city,
                                        'room_type'             => $arr_hotel->more_acc_type,
                                        'reservation_no'        => $arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'              => $room_qty,
                                        'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'   => $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'          => 'Package',
                                        'status'                => $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $room_qty                   = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'            => $invoice_res->generate_id,
                                    'invoice_id'            => $invoice_res->tour_id,
                                    'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                    'check_in'              => date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'             => date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'            => $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'       => $arr_hotel->hotel_city_name,
                                    'room_type'             => $arr_hotel->acc_type,
                                    'reservation_no'        => $arr_hotel->room_reservation_no ?? '',
                                    'quantity'              => $room_qty,
                                    'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'   => $invoice_res->f_name,
                                    'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                    'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                    'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                    'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'          => 'Package',
                                    'status'                => $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $room_qty               = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'            => $invoice_res->generate_id,
                                        'invoice_id'            => $invoice_res->tour_id,
                                        'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                        'check_in'              => date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'             => date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'            => $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'       => $arr_hotel->hotel_city_name,
                                        'room_type'             => $arr_hotel->acc_type,
                                        'reservation_no'        => $arr_hotel->room_reservation_no ?? '',
                                        'quantity'              => $room_qty,
                                        'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'   => $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'          => 'Package',
                                        'status'                => $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$room_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            'room_type'=>$arr_hotel->more_acc_type,
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                            'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                            'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $room_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                            'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                            'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // dd($aray_data);
        
        //********************************************
        // Hotel Working
        //********************************************
        if($request->customer_id == 48){
            $aray_data          = [];
            
            if($person_report_type != '' && $person_report_type == 'B2BAgentWise'){
                $hotel_Booking_Data = DB::table('hotels_bookings')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('b2b_agent_id',$request_data->b2b_agent_id)
                                        ->select('id','invoice_no','b2b_agent_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
                                        ->orderBy('hotels_bookings.id','asc')
                                        ->get();
            }else{
                $hotel_Booking_Data = DB::table('hotels_bookings')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('b2b_agent_id','!=',NULL)
                                        ->select('id','invoice_no','b2b_agent_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
                                        ->orderBy('hotels_bookings.id','asc')
                                        ->get();
            }
            
            foreach($hotel_Booking_Data as $val_HB){
                $invoice_Supplier_Name = '';
                
                if(isset($val_HB->reservation_response) && $val_HB->reservation_response != 'null' && $val_HB->reservation_response != NULL){
                    $reservation_request    = json_decode($val_HB->reservation_request);
                    $reservation_response   = json_decode($val_HB->reservation_response);
                    if(isset($reservation_response->hotel_details) && $reservation_response->hotel_details != 'null' && $reservation_response->hotel_details != NULL){
                    $hotel_details          = $reservation_response->hotel_details;
                    if(isset($hotel_details->rooms) && $hotel_details->rooms != 'null' && $hotel_details->rooms != NULL){
                        $rooms              = $hotel_details->rooms;
                            foreach($rooms as $arr_hotel){
                                // dd($arr_hotel->room_name);
                                
                                if(isset($request_data->supplier) && $request_data->supplier != null && $request_data->supplier != '' && $request_data->supplier != 'all_Suppliers'){
                                    $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$request_data->supplier)->select('room_supplier_name')->first();
                                }
                                
                                if($reservation_response->provider == 'Custome_hotel'){
                                    $hotel_Data     = DB::table('hotels')->where('id',$hotel_details->hotel_code)->first();
                                    $room_Details   = DB::table('rooms')->where('id',$arr_hotel->room_code)->first();
                                    
                                    if($room_Details != null){
                                        if(isset($room_Details->room_supplier_name) && $room_Details->room_supplier_name != null && $room_Details->room_supplier_name != '' && $room_Details->room_supplier_name != 'Select One'){
                                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$room_Details->room_supplier_name)->select('room_supplier_name')->first();
                                        }else{
                                            if(isset($room_Details->room_supplier_name_AR) && $room_Details->room_supplier_name_AR != null && $room_Details->room_supplier_name_AR != '' && $room_Details->room_supplier_name_AR != 'Select One'){
                                                $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$room_Details->room_supplier_name_AR)->select('room_supplier_name')->first();
                                            }
                                        }
                                    }
                                }
                                
                                $adults     = $arr_hotel->adults ?? 0;
                                $childs     = $arr_hotel->childs ?? 0;
                                $quantity   = $adults + $childs;
                                $b2b_agents = DB::table('b2b_agents')->where('id',$val_HB->b2b_agent_id)->first();
                                
                                if($request_data->supplier == 'all'){
                                    if($start_date != '' && $end_date != ''){
                                        if($hotel_details->checkIn >= $start_date && $hotel_details->checkIn <= $end_date){
                                            $aray_data[]                    = (object)[
                                                'invoice_no'                => $val_HB->invoice_no,
                                                'invoice_id'                => $val_HB->id,
                                                'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                'hotel_name'                => $hotel_details->hotel_name,
                                                'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                'reservation_no'            => $arr_hotel->room_code ?? '',
                                                'quantity'                  => $quantity,
                                                'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                                'agent_id'                  => $b2b_agents->id ?? '',
                                                'agent_name'                => $b2b_agents->first_name . ' ' . $b2b_agents->last_name,
                                                'agent_company_name'        => $b2b_agents->company_name ?? $b2b_agents->first_name . ' ' . $b2b_agents->last_name,
                                                'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                'booking_type'              => 'Hotel Booking',
                                                'status'                    => $val_HB->status,
                                                'all_services_quotation'    => 'NO',
                                            ];
                                        }
                                    }else{
                                        $aray_data[]                    = (object)[
                                            'invoice_no'                => $val_HB->invoice_no,
                                            'invoice_id'                => $val_HB->id,
                                            'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                            'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                            'hotel_name'                => $hotel_details->hotel_name,
                                            'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                            'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                            'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                            'reservation_no'            => $arr_hotel->room_code ?? '',
                                            'quantity'                  => $quantity,
                                            'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                            'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                            'agent_id'                  => $b2b_agents->id ?? '',
                                            'agent_name'                => $b2b_agents->first_name . ' ' . $b2b_agents->last_name,
                                            'agent_company_name'        => $b2b_agents->company_name ?? $b2b_agents->first_name . ' ' . $b2b_agents->last_name,
                                            'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                            'booking_type'              => 'Hotel Booking',
                                            'status'                    => $val_HB->status,
                                            'all_services_quotation'    => 'NO',
                                        ];
                                    }
                                }else{
                                    if(isset($room_Details->room_supplier_name)){
                                        if($request_data->supplier == $room_Details->room_supplier_name){
                                            if($start_date != '' && $end_date != ''){
                                                if($hotel_details->checkIn >= $start_date && $hotel_details->checkIn <= $end_date){
                                                    $aray_data[]                    = (object)[
                                                        'invoice_no'                => $val_HB->invoice_no,
                                                        'invoice_id'                => $val_HB->id,
                                                        'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                        'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                        'hotel_name'                => $hotel_details->hotel_name,
                                                        'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                        'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                        'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                        'reservation_no'            => $arr_hotel->room_code ?? '',
                                                        'quantity'                  => $quantity,
                                                        'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                        'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                        'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                                        'agent_id'                  => $b2b_agents->id ?? '',
                                                        'agent_name'                => $b2b_agents->first_name . ' ' . $b2b_agents->last_name,
                                                        'agent_company_name'        => $b2b_agents->company_name ?? $b2b_agents->first_name . ' ' . $b2b_agents->last_name,
                                                        'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                        'booking_type'              => 'Hotel Booking',
                                                        'status'                    => $val_HB->status,
                                                        'all_services_quotation'    => 'NO',
                                                    ];
                                                }
                                            }else{
                                                $aray_data[]                    = (object)[
                                                    'invoice_no'                => $val_HB->invoice_no,
                                                    'invoice_id'                => $val_HB->id,
                                                    'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                    'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                    'hotel_name'                => $hotel_details->hotel_name,
                                                    'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                    'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                    'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                    'reservation_no'            => $arr_hotel->room_code ?? '',
                                                    'quantity'                  => $quantity,
                                                    'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                    'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                    'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                                    'agent_id'                  => $b2b_agents->id ?? '',
                                                    'agent_name'                => $b2b_agents->first_name . ' ' . $b2b_agents->last_name,
                                                    'agent_company_name'        => $b2b_agents->company_name ?? $b2b_agents->first_name . ' ' . $b2b_agents->last_name,
                                                    'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                    'booking_type'              => 'Hotel Booking',
                                                    'status'                    => $val_HB->status,
                                                    'all_services_quotation'    => 'NO',
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } 
      
        return response()->json(['hotel_supplier'=>$aray_data]);
    }
   
    public function getRoomsQty($cart_data,$room_type){
       $cart_data = json_decode($cart_data);
       if($room_type == 'Double'){
           return $cart_data->double_rooms ?? '';
       }
       
       if($room_type == 'Triple'){
           return $cart_data->triple_rooms ?? '';
       }
       
       if($room_type == 'Quad'){
           return $cart_data->quad_rooms ?? '';
       }
   }
    
    public function hotel_supplier_reports_sub_new(Request $request){
        $request_data =  json_decode($request->request_data);
        $start_date         = '';
        $end_date           = '';
        
        if($request_data->report_type == 'data_wise'){
            $start_date     = $request_data->check_in;
            $end_date       = $request_data->check_out;
        }
        
        if($request_data->report_type == 'data_today_wise'){
            $start_date     = date('Y-m-d');
            $end_date       = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_tomorrow_wise'){
            $start_date     = date('Y-m-d',strtotime("+1 days"));
            $end_date       = date('Y-m-d',strtotime("+1 days"));
        }
        
        if($request_data->report_type == 'data_week_wise'){
            $startOfWeek    = Carbon::now()->startOfWeek();
            $start_date     = $startOfWeek->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfWeek();
            $end_date       = $endOfWeek->format('Y-m-d');
            // dd($start_date,$end_date,$request_data);
            // $end_date       = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_month_wise'){
            $startOfMonth   = Carbon::now()->startOfMonth();
            $start_date     = $startOfMonth->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfMonth();
            $end_date       = $endOfWeek->format('Y-m-d');
            // $end_date       = date('Y-m-d');
        }
        
        if(isset($request_data->person_report_type)){
            $person_report_type = $request_data->person_report_type;
        }else if(isset($request_data->person_report_type)){
            $person_report_type = $request_data->report_type;
        }else{
            $person_report_type = '';
        }
      
        //********************************************
        // Invoice Working
        //********************************************
      
        $invoices_query                 = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id);
        $invoice_select_common_element  = ['b2b_Agent_Id','all_services_quotation','generate_id','confirm','accomodation_details','accomodation_details_more','f_name','middle_name','created_at','id','lead_fname','lead_lname'];
        
        if($person_report_type != '' && $person_report_type == 'AgentWise'){
            $invoices_query->select('id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
            if($request_data->agent_Id == 'all_agent'){
                $invoices_query->where('agent_Id','>',0);
            }
          
            if($request_data->agent_Id != 'all_agent'){
                $invoices_query->where('agent_Id',$request_data->agent_Id);
            }
        }else if($person_report_type != '' && $person_report_type == 'CustomerWise'){
            $invoices_query->select('id','booking_customer_id',...$invoice_select_common_element);
            if($request_data->customer_Id == 'all_customer'){
                $invoices_query->where('booking_customer_id','>',0);
            }
          
            if($request_data->customer_Id != 'all_customer'){
                $invoices_query->where('booking_customer_id',$request_data->customer_Id);
            }
        }else{
            if(isset($request_data->b2b_Agent_Id)){
                if($request_data->b2b_Agent_Id == 'all_b2b_agent'){
                    $invoices_query->select('id','b2b_Agent_Id','b2b_Agent_Company_Name',...$invoice_select_common_element);
                    $invoices_query->where('b2b_Agent_Id','>',0);
                }elseif($request_data->b2b_Agent_Id != 'all_b2b_agent'){
                    $invoices_query->select('id','b2b_Agent_Id','b2b_Agent_Company_Name',...$invoice_select_common_element);
                    $invoices_query->where('b2b_Agent_Id',$request_data->b2b_Agent_Id);
                }else{
                    $invoices_query->select('id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
                }
            }else{
                $invoices_query->select('id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
            }
        }
        
        $invoices_data = $invoices_query->get();
      
        $aray_data = [];
        foreach($invoices_data as $invoice_res){
            
            $invoice_Supplier_Name  = '';
            $agent_id               = '';
            $agent_name             = '';
            $agent_company_name     = '';
            
            if(isset($invoice_res->b2b_Agent_Id) && $invoice_res->b2b_Agent_Id > 0){
                $agent_Details_PB                   = DB::table('b2b_agents')->where('id',$invoice_res->b2b_Agent_Id)->first();
                $agent_id                           = $agent_Details_PB->id;
                $agent_name                         = $agent_Details_PB->first_name ?? '';
                $agent_company_name                 = $agent_Details_PB->company_name ?? '';
            }elseif(isset($invoice_res->agent_Id) && $invoice_res->agent_Id > 0){
                $agent_Details_PB                   = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$invoice_res->agent_Id)->first();
                $agent_id                           = $agent_Details_PB->id ?? '';
                $agent_name                         = $agent_Details_PB->agent_Name ?? '';
                $agent_company_name                 = $agent_Details_PB->company_name ?? '';
            }else{
                if(isset($invoice_res->booking_customer_id) && $invoice_res->booking_customer_id != null && $invoice_res->booking_customer_id != '' && $invoice_res->booking_customer_id > 0){
                    $customer_Details_PB            = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$invoice_res->booking_customer_id)->first();
                    $agent_id                       = $customer_Details_PB->id;
                    $agent_name                     = $customer_Details_PB->name ?? '';
                    $agent_company_name             = $customer_Details_PB->name ?? '';
                }else{
                    if(isset($invoice_res->cart_total_data) && $invoice_res->cart_total_data != null && $invoice_res->cart_total_data != ''){
                        $cart_total_data                = json_decode($invoice_res->cart_total_data);
                        if(isset($cart_total_data->agent_name) && $cart_total_data->agent_name > 0){
                            $agent_Details_PB           = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$cart_total_data->agent_name)->first();
                            $agent_id                   = $agent_Details_PB->id;
                            $agent_name                 = $agent_Details_PB->agent_Name ?? '';
                            $agent_company_name         = $agent_Details_PB->company_name ?? '';
                        }else{
                            if(isset($cart_total_data->customer_id) && $cart_total_data->customer_id > 0){
                                $customer_Details_PB    = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$cart_total_data->customer_id)->first();
                                $agent_id               = $customer_Details_PB->id ?? '';
                                $agent_name             = $customer_Details_PB->name ?? '';
                                $agent_company_name     = $customer_Details_PB->name ?? '';
                            }
                        }
                    }
                }
            }
            
            if($request_data->report_type == 'all_data'){
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->more_hotel_room_view) && $arr_hotel->more_hotel_room_view != null && $arr_hotel->more_hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->more_hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                    'room_type'=>$arr_hotel->more_hotel_type_cat ?? $arr_hotel->more_acc_type ?? '',
                                    'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                    'hotel_room_view'=>$hotel_room_view ?? '',
                                    'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'=>$arr_hotel->more_acc_qty,
                                    'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'agent_id'=> $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $agent_company_name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                    'all_services_quotation'=> $invoice_res->all_services_quotation,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=> $arr_hotel->more_hotel_type_cat ?? $arr_hotel->more_acc_type ?? '',
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $agent_company_name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->hotel_room_view) && $arr_hotel->hotel_room_view != null && $arr_hotel->hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'=> $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                    'room_type'=> $arr_hotel->hotel_type_cat ?? $arr_hotel->acc_type,
                                    'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                    'hotel_room_view'=>$hotel_room_view ?? '',
                                    'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                    'quantity'=> $arr_hotel->acc_qty,
                                    'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                    'agent_id'=> $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $agent_company_name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                    'all_services_quotation'=> $invoice_res->all_services_quotation,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->hotel_type_cat ?? $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $agent_company_name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }
                    }
                }
                
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->more_hotel_room_view) && $arr_hotel->more_hotel_room_view != null && $arr_hotel->more_hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->more_hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_hotel_type_cat ?? $arr_hotel->more_acc_type ?? '',
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $agent_company_name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            'room_type'=>$arr_hotel->more_hotel_type_cat ?? $arr_hotel->more_acc_type ?? '',
                                            'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                            'hotel_room_view'=>$hotel_room_view ?? '',
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$arr_hotel->more_acc_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $agent_company_name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                            'all_services_quotation'=> $invoice_res->all_services_quotation,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        // return $invoice_res;
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        $hotel_room_view = '';
                        if(isset($arr_hotel->hotel_room_view) && $arr_hotel->hotel_room_view != null && $arr_hotel->hotel_room_view != ''){
                            $hotel_room_view = $arr_hotel->hotel_room_view;
                            if($hotel_room_view == 'City View'){
                                $hotel_room_view = 'CV';
                            }elseif($hotel_room_view == 'Haram View'){
                                $hotel_room_view = 'HV';
                            }elseif($hotel_room_view == 'Kabbah View'){
                                $hotel_room_view = 'KV';
                            }elseif($hotel_room_view == 'Partial Haram View'){
                                $hotel_room_view = 'PHV';
                            }elseif($hotel_room_view == 'Patio View'){
                                $hotel_room_view = 'PV';
                            }elseif($hotel_room_view == 'Towers View'){
                                $hotel_room_view = 'TV';
                            }elseif($hotel_room_view == 'Kabbah Partial View'){
                                $hotel_room_view = 'KPV';
                            }else{
                                $hotel_room_view = '';
                            }
                        }
                      
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->hotel_type_cat ?? $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'hotel_room_view'=>$hotel_room_view ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $agent_company_name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                        'all_services_quotation'=> $invoice_res->all_services_quotation,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->hotel_type_cat ?? $arr_hotel->acc_type,
                                            'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                            'hotel_room_view'=>$hotel_room_view ?? '',
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $arr_hotel->acc_qty,
                                            'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $agent_company_name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                            'all_services_quotation'=> $invoice_res->all_services_quotation,
                                        ];
                                    }
                                }
                             
                            }
                        }
                    }
                }
            }
        }
        
        // dd($aray_data);
      
        //********************************************
        //Package Working
        //********************************************
      
        $invoices_query                 = DB::table('cart_details')->where('client_id',$request->customer_id)
                                            ->join('tours','tours.id','=','cart_details.tour_id')->join('tours_bookings','tours_bookings.id','=','cart_details.booking_id');
        $invoice_select_common_element  = ['tours.id as tour_id','tours_bookings.id as tour_Booking_Id','cart_details.agent_name as agent_Id','cart_details.invoice_no as generate_id','cart_details.confirm','tours.accomodation_details','tours.accomodation_details_more','tours_bookings.passenger_name as f_name','cart_details.created_at','cart_details.cart_total_data'];
        // return $invoices_query;
        
        // return $person_report_type;
        
        if($person_report_type != '' && $person_report_type == 'AgentWise'){
            if($request_data->agent_Id == 'all_agent'){
                $invoices_query->where('cart_details.agent_name','>',0);
            }
            
            if($request_data->agent_Id != 'all_agent'){
                $invoices_query->where('cart_details.agent_name',$request_data->agent_Id);
            }
        }else if($person_report_type != '' && $person_report_type == 'CustomerWise'){
            if($request_data->customer_Id == 'all_customer'){
                $invoices_query->where(function($query){
                    $query->where('agent_name','-1')->orWhere('agent_name','=',NULL);
                });
            }
          
            if($request_data->customer_Id != 'all_customer'){
                $invoices_query->whereJsonContains('cart_details.cart_total_data->customer_id',"$request_data->customer_Id");
            }
        }else{
            // $invoices_query->select($invoice_select_common_element);
        }
        
        $invoices_query->select($invoice_select_common_element);
        $invoices_data = $invoices_query->get();
        // $invoices_data = $invoices_query;
        // return $invoices_data;
      
        // foreach($invoices_data as $invoice_res){
        //     if($request_data->report_type == 'all_data'){}
        // }
        
        // dd($invoices_data);
        
        foreach($invoices_data as $invoice_res){
            // dd($invoice_res);
            
            $invoice_res->confirm = "confirm";
            
            if(isset($invoice_res->agent_Id) && $invoice_res->agent_Id > 0){
                $agent_Details_PB                   = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$invoice_res->agent_Id)->first();
                $agent_id                           = $agent_Details_PB->id ?? '';
                $agent_name                         = $agent_Details_PB->agent_Name ?? '';
                $agent_company_name                 = $agent_Details_PB->company_name ?? '';
            }else{
                if(isset($invoice_res->booking_customer_id) && $invoice_res->booking_customer_id != null && $invoice_res->booking_customer_id != ''){
                    $customer_Details_PB            = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$invoice_res->booking_customer_id)->first();
                    $agent_id                       = $customer_Details_PB->id;
                    $agent_name                     = $customer_Details_PB->name ?? '';
                    $agent_company_name             = $customer_Details_PB->name ?? '';
                }else{
                    $cart_total_data                = json_decode($invoice_res->cart_total_data);
                    if(isset($cart_total_data->agent_name) && $cart_total_data->agent_name > 0){
                        $agent_Details_PB           = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$cart_total_data->agent_name)->first();
                        $agent_id                   = $agent_Details_PB->id;
                        $agent_name                 = $agent_Details_PB->agent_Name ?? '';
                        $agent_company_name         = $agent_Details_PB->company_name ?? '';
                    }else{
                        if(isset($cart_total_data->customer_id) && $cart_total_data->customer_id > 0){
                            $customer_Details_PB    = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$cart_total_data->customer_id)->first();
                            $agent_id               = $customer_Details_PB->id ?? '';
                            $agent_name             = $customer_Details_PB->name ?? '';
                            $agent_company_name     = $customer_Details_PB->name ?? '';
                        }
                    }
                }
            }
            
            if($request_data->report_type == 'all_data'){
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'            => $invoice_res->generate_id,
                                    'invoice_id'            => $invoice_res->tour_id,
                                    'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                    'check_in'              => date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'             => date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'            => $arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'       => $arr_hotel->more_hotel_city,
                                    'room_type'             => $arr_hotel->more_acc_type,
                                    'reservation_no'        => $arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'              => $room_qty,
                                    'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'   => $invoice_res->f_name,
                                    'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                    'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                    'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                    'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'          => 'Package',
                                    'status'                => $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'            => $invoice_res->generate_id,
                                        'invoice_id'            => $invoice_res->tour_id,
                                        'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                        'check_in'              => date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'             => date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'            => $arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'       => $arr_hotel->more_hotel_city,
                                        'room_type'             => $arr_hotel->more_acc_type,
                                        'reservation_no'        => $arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'              => $room_qty,
                                        'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'   => $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'          => 'Package',
                                        'status'                => $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $room_qty                   = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'            => $invoice_res->generate_id,
                                    'invoice_id'            => $invoice_res->tour_id,
                                    'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                    'check_in'              => date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'             => date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'            => $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'       => $arr_hotel->hotel_city_name,
                                    'room_type'             => $arr_hotel->acc_type,
                                    'reservation_no'        => $arr_hotel->room_reservation_no ?? '',
                                    'quantity'              => $room_qty,
                                    'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'   => $invoice_res->f_name,
                                    'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                    'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                    'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                    'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'          => 'Package',
                                    'status'                => $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $room_qty                   = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'            => $invoice_res->generate_id,
                                        'invoice_id'            => $invoice_res->tour_id,
                                        'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                        'check_in'              => date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'             => date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'            => $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'       => $arr_hotel->hotel_city_name,
                                        'room_type'             => $arr_hotel->acc_type,
                                        'reservation_no'        => $arr_hotel->room_reservation_no ?? '',
                                        'quantity'              => $room_qty,
                                        'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'   => $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'          => 'Package',
                                        'status'                => $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$room_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            'room_type'=>$arr_hotel->more_acc_type,
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                            'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                            'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $room_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'tour_Booking_Id'       => $invoice_res->tour_Booking_Id ?? '',
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                            'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                            'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // dd($aray_data);
        
        //********************************************
        // Hotel Working
        //********************************************
        
        // B2B Website
        if($request->customer_id == 48 || $request->customer_id == 58){
            // dd('OK');
            
            // $aray_data          = [];
            
            if($person_report_type != '' && $person_report_type == 'B2BAgentWise'){
                $hotel_Booking_Data = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('b2b_agent_id',$request_data->b2b_agent_id)
                                        ->select('id','invoice_no','b2b_agent_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
                                        ->orderBy('hotels_bookings.id','asc')
                                        ->get();
            }else{
                $hotel_Booking_Data = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('b2b_agent_id','!=',NULL)
                                        ->select('id','invoice_no','b2b_agent_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
                                        ->orderBy('hotels_bookings.id','asc')
                                        ->get();
            }
            
            foreach($hotel_Booking_Data as $val_HB){
                $invoice_Supplier_Name = '';
                
                if(isset($val_HB->reservation_response) && $val_HB->reservation_response != 'null' && $val_HB->reservation_response != NULL){
                    $reservation_request    = json_decode($val_HB->reservation_request);
                    $reservation_response   = json_decode($val_HB->reservation_response);
                    if(isset($reservation_response->hotel_details) && $reservation_response->hotel_details != 'null' && $reservation_response->hotel_details != NULL){
                    $hotel_details          = $reservation_response->hotel_details;
                    if(isset($hotel_details->rooms) && $hotel_details->rooms != 'null' && $hotel_details->rooms != NULL){
                        $rooms              = $hotel_details->rooms;
                            foreach($rooms as $arr_hotel){
                                // dd($arr_hotel->room_name);
                                
                                if(isset($request_data->supplier) && $request_data->supplier != null && $request_data->supplier != '' && $request_data->supplier != 'all_Suppliers'){
                                    $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$request_data->supplier)->select('room_supplier_name')->first();
                                }
                                
                                if($reservation_response->provider == 'Custome_hotel'){
                                    $hotel_Data     = DB::table('hotels')->where('id',$hotel_details->hotel_code)->first();
                                    $room_Details   = DB::table('rooms')->where('id',$arr_hotel->room_code)->first();
                                    
                                    if($room_Details != null){
                                        if(isset($room_Details->room_supplier_name) && $room_Details->room_supplier_name != null && $room_Details->room_supplier_name != '' && $room_Details->room_supplier_name != 'Select One'){
                                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$room_Details->room_supplier_name)->select('room_supplier_name')->first();
                                        }else{
                                            if(isset($room_Details->room_supplier_name_AR) && $room_Details->room_supplier_name_AR != null && $room_Details->room_supplier_name_AR != '' && $room_Details->room_supplier_name_AR != 'Select One'){
                                                $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$room_Details->room_supplier_name_AR)->select('room_supplier_name')->first();
                                            }
                                        }
                                    }
                                }
                                
                                $adults     = $arr_hotel->adults ?? 0;
                                $childs     = $arr_hotel->childs ?? 0;
                                $quantity   = $adults + $childs;
                                $b2b_agents = DB::table('b2b_agents')->where('id',$val_HB->b2b_agent_id)->first();
                                
                                $agent_first_name = $b2b_agents->first_name ?? '';
                                $agent_last_name = $b2b_agents->last_name ?? '' ?? '';
                                $agent_Name = $agent_first_name .' '.$agent_last_name;
                                
                                if($request_data->supplier == 'all'){
                                    if($start_date != '' && $end_date != ''){
                                        if($hotel_details->checkIn >= $start_date && $hotel_details->checkIn <= $end_date){
                                            $aray_data[]                    = (object)[
                                                'invoice_no'                => $val_HB->invoice_no,
                                                'invoice_id'                => $val_HB->id,
                                                'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                'hotel_name'                => $hotel_details->hotel_name,
                                                'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                'reservation_no'            => $arr_hotel->room_code ?? '',
                                                'quantity'                  => $quantity,
                                                'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                                'agent_id'                  => $b2b_agents->id ?? '',
                                                'agent_name'                => $agent_last_name,
                                                'agent_company_name'        => $b2b_agents->company_name ?? $agent_last_name ?? '',
                                                'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                'booking_type'              => 'Hotel Booking',
                                                'status'                    => $val_HB->status,
                                                'all_services_quotation'    => 'NO',
                                            ];
                                        }
                                    }else{
                                        $aray_data[]                    = (object)[
                                            'invoice_no'                => $val_HB->invoice_no,
                                            'invoice_id'                => $val_HB->id,
                                            'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                            'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                            'hotel_name'                => $hotel_details->hotel_name,
                                            'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                            'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                            'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                            'reservation_no'            => $arr_hotel->room_code ?? '',
                                            'quantity'                  => $quantity,
                                            'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                            'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                            'agent_id'                  => $b2b_agents->id ?? '',
                                            'agent_name'                => $agent_last_name,
                                            'agent_company_name'        => $b2b_agents->company_name ?? $agent_last_name ?? '',
                                            'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                            'booking_type'              => 'Hotel Booking',
                                            'status'                    => $val_HB->status,
                                            'all_services_quotation'    => 'NO',
                                        ];
                                    }
                                }else{
                                    if(isset($room_Details->room_supplier_name)){
                                        if($request_data->supplier == $room_Details->room_supplier_name){
                                            if($start_date != '' && $end_date != ''){
                                                if($hotel_details->checkIn >= $start_date && $hotel_details->checkIn <= $end_date){
                                                    $aray_data[]                    = (object)[
                                                        'invoice_no'                => $val_HB->invoice_no,
                                                        'invoice_id'                => $val_HB->id,
                                                        'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                        'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                        'hotel_name'                => $hotel_details->hotel_name,
                                                        'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                        'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                        'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                        'reservation_no'            => $arr_hotel->room_code ?? '',
                                                        'quantity'                  => $quantity,
                                                        'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                        'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                        'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                                        'agent_id'                  => $b2b_agents->id ?? '',
                                                        'agent_name'                => $agent_last_name ?? '',
                                                        'agent_company_name'        => $b2b_agents->company_name ?? $agent_last_name ?? '',
                                                        'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                        'booking_type'              => 'Hotel Booking',
                                                        'status'                    => $val_HB->status,
                                                        'all_services_quotation'    => 'NO',
                                                    ];
                                                }
                                            }else{
                                                $aray_data[]                    = (object)[
                                                    'invoice_no'                => $val_HB->invoice_no,
                                                    'invoice_id'                => $val_HB->id,
                                                    'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                    'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                    'hotel_name'                => $hotel_details->hotel_name,
                                                    'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                    'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                    'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                    'reservation_no'            => $arr_hotel->room_code ?? '',
                                                    'quantity'                  => $quantity,
                                                    'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                    'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                    'booking_customer_id'       => $b2b_agents->booking_customer_id ?? '',
                                                    'agent_id'                  => $b2b_agents->id ?? '',
                                                    'agent_name'                => $agent_last_name ?? '',
                                                    'agent_company_name'        => $b2b_agents->company_name ?? $agent_last_name ?? '',
                                                    'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                    'booking_type'              => 'Hotel Booking',
                                                    'status'                    => $val_HB->status,
                                                    'all_services_quotation'    => 'NO',
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } 
        // B2B Website
        
        // dd($aray_data);
        
        if($request->customer_id == 54){
            // dd('OK');
            
            // $aray_data          = [];
            
            // if($person_report_type != '' && $person_report_type == 'B2BAgentWise'){
            //     $hotel_Booking_Data = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('b2b_agent_id',$request_data->b2b_agent_id)
            //                             ->select('id','invoice_no','b2b_agent_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
            //                             ->orderBy('hotels_bookings.id','asc')
            //                             ->get();
            // }else{
            //     $hotel_Booking_Data = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('b2b_agent_id','!=',NULL)
            //                             ->select('id','invoice_no','b2b_agent_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
            //                             ->orderBy('hotels_bookings.id','asc')
            //                             ->get();
            // }
            
            // dd($person_report_type,$request_data->customer_Id);
            
            if(isset($person_report_type) && $person_report_type == 'CustomerWise' && $request_data->customer_Id != 'all_customer'){
                $hotel_Booking_Data = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('booking_customer_id',$request_data->customer_Id)
                                            ->select('id','invoice_no','b2b_agent_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
                                            ->orderBy('hotels_bookings.id','asc')
                                            ->get();
            }else{
                $hotel_Booking_Data = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)
                                            ->select('id','invoice_no','b2b_agent_id','booking_customer_id','status','lead_passenger','exchange_price','reservation_request','reservation_response','created_at')
                                            ->orderBy('hotels_bookings.id','asc')
                                            ->get();
            }
            
            // dd($hotel_Booking_Data);
            
            foreach($hotel_Booking_Data as $val_HB){
                $invoice_Supplier_Name = '';
                
                if(isset($val_HB->reservation_response) && $val_HB->reservation_response != 'null' && $val_HB->reservation_response != NULL){
                    $reservation_request    = json_decode($val_HB->reservation_request);
                    $reservation_response   = json_decode($val_HB->reservation_response);
                    if(isset($reservation_response->hotel_details) && $reservation_response->hotel_details != 'null' && $reservation_response->hotel_details != NULL){
                        $hotel_details          = $reservation_response->hotel_details;
                        if(isset($hotel_details->rooms) && $hotel_details->rooms != 'null' && $hotel_details->rooms != NULL){
                            $rooms              = $hotel_details->rooms;
                            foreach($rooms as $arr_hotel){
                                // dd($arr_hotel->room_name);
                                
                                if(isset($request_data->supplier) && $request_data->supplier != null && $request_data->supplier != '' && $request_data->supplier != 'all_Suppliers'){
                                    $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$request_data->supplier)->select('room_supplier_name')->first();
                                }
                                
                                if($reservation_response->provider == 'Custome_hotel'){
                                    $hotel_Data     = DB::table('hotels')->where('id',$hotel_details->hotel_code)->first();
                                    $room_Details   = DB::table('rooms')->where('id',$arr_hotel->room_code)->first();
                                    
                                    if($room_Details != null){
                                        if(isset($room_Details->room_supplier_name) && $room_Details->room_supplier_name != null && $room_Details->room_supplier_name != '' && $room_Details->room_supplier_name != 'Select One'){
                                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$room_Details->room_supplier_name)->select('room_supplier_name')->first();
                                        }else{
                                            if(isset($room_Details->room_supplier_name_AR) && $room_Details->room_supplier_name_AR != null && $room_Details->room_supplier_name_AR != '' && $room_Details->room_supplier_name_AR != 'Select One'){
                                                $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$room_Details->room_supplier_name_AR)->select('room_supplier_name')->first();
                                            }
                                        }
                                    }
                                }
                                
                                $adults             = $arr_hotel->adults ?? 0;
                                $childs             = $arr_hotel->childs ?? 0;
                                $quantity           = $adults + $childs;
                                $agent_id           = '';
                                $agent_Name         = '';
                                $agent_company_name = '';
                                
                                if(isset($val_HB->b2b_agent_id) && $val_HB->b2b_agent_id > 0){
                                    $b2b_agents             = DB::table('b2b_agents')->where('id',$val_HB->b2b_agent_id)->first();
                                    $agent_first_name       = $b2b_agents->first_name ?? '';
                                    $agent_last_name        = $b2b_agents->last_name ?? '' ?? '';
                                    $agent_id               = $b2b_agents->id;
                                    $agent_Name             = $agent_first_name .' '.$agent_last_name;
                                    $agent_company_name     = $b2b_agents->company_name ?? '';
                                }else{
                                    // dd($val_HB);
                                    if(isset($val_HB->booking_customer_id) && $val_HB->booking_customer_id != null && $val_HB->booking_customer_id != '' && $val_HB->booking_customer_id > 0){
                                        $booking_customers  = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$val_HB->booking_customer_id)->first();
                                        $agent_id           = $booking_customers->id;
                                        $agent_Name         = $booking_customers->name ?? '';
                                        $agent_company_name = $booking_customers->name ?? '';
                                    }
                                }
                                
                                // $b2b_agents         = DB::table('b2b_agents')->where('id',$val_HB->b2b_agent_id)->first();
                                // $agent_first_name   = $b2b_agents->first_name ?? '';
                                // $agent_last_name    = $b2b_agents->last_name ?? '' ?? '';
                                // $agent_Name         = $agent_first_name .' '.$agent_last_name;
                                
                                if($request_data->supplier == 'all'){
                                    if($start_date != '' && $end_date != ''){
                                        if($hotel_details->checkIn >= $start_date && $hotel_details->checkIn <= $end_date){
                                            $aray_data[]                    = (object)[
                                                'invoice_no'                => $val_HB->invoice_no,
                                                'invoice_id'                => $val_HB->id,
                                                'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                'hotel_name'                => $hotel_details->hotel_name,
                                                'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                'reservation_no'            => $arr_hotel->room_code ?? '',
                                                'quantity'                  => $quantity,
                                                'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                'booking_customer_id'       => $agent_id,
                                                'agent_id'                  => $agent_id,
                                                'agent_name'                => $agent_Name,
                                                'agent_company_name'        => $agent_company_name,
                                                'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                'booking_type'              => 'Hotel Booking',
                                                'status'                    => $val_HB->status,
                                                'all_services_quotation'    => 'NO',
                                            ];
                                        }
                                    }else{
                                        $aray_data[]                    = (object)[
                                            'invoice_no'                => $val_HB->invoice_no,
                                            'invoice_id'                => $val_HB->id,
                                            'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                            'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                            'hotel_name'                => $hotel_details->hotel_name,
                                            'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                            'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                            'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                            'reservation_no'            => $arr_hotel->room_code ?? '',
                                            'quantity'                  => $quantity,
                                            'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                            'booking_customer_id'       => $agent_id,
                                            'agent_id'                  => $agent_id,
                                            'agent_name'                => $agent_Name,
                                            'agent_company_name'        => $agent_company_name,
                                            'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                            'booking_type'              => 'Hotel Booking',
                                            'status'                    => $val_HB->status,
                                            'all_services_quotation'    => 'NO',
                                        ];
                                    }
                                }else{
                                    if(isset($room_Details->room_supplier_name)){
                                        if($request_data->supplier == $room_Details->room_supplier_name){
                                            if($start_date != '' && $end_date != ''){
                                                if($hotel_details->checkIn >= $start_date && $hotel_details->checkIn <= $end_date){
                                                    $aray_data[]                    = (object)[
                                                        'invoice_no'                => $val_HB->invoice_no,
                                                        'invoice_id'                => $val_HB->id,
                                                        'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                        'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                        'hotel_name'                => $hotel_details->hotel_name,
                                                        'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                        'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                        'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                        'reservation_no'            => $arr_hotel->room_code ?? '',
                                                        'quantity'                  => $quantity,
                                                        'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                        'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                        'booking_customer_id'       => $agent_id,
                                                        'agent_id'                  => $agent_id,
                                                        'agent_name'                => $agent_Name,
                                                        'agent_company_name'        => $agent_company_name,
                                                        'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                        'booking_type'              => 'Hotel Booking',
                                                        'status'                    => $val_HB->status,
                                                        'all_services_quotation'    => 'NO',
                                                    ];
                                                }
                                            }else{
                                                $aray_data[]                    = (object)[
                                                    'invoice_no'                => $val_HB->invoice_no,
                                                    'invoice_id'                => $val_HB->id,
                                                    'check_in'                  => date('d-m-Y', strtotime($hotel_details->checkIn)),
                                                    'check_out'                 => date('d-m-Y', strtotime($hotel_details->checkOut)),
                                                    'hotel_name'                => $hotel_details->hotel_name,
                                                    'hotel_city_name'           => $hotel_Data->property_city ?? $hotel_details->zoneName ?? $hotel_details->destinationName ?? '',
                                                    'room_type'                 => $arr_hotel->room_name ?? $room_Details->room_type_name ?? '',
                                                    'meal_Type'                 => $arr_hotel->hotel_meal_type ?? $room_Details->room_meal_type ?? '',
                                                    'reservation_no'            => $arr_hotel->room_code ?? '',
                                                    'quantity'                  => $quantity,
                                                    'supplier'                  => $invoice_Supplier_Name->room_supplier_name ?? '',
                                                    'lead_passenger_name'       => $val_HB->lead_passenger ?? '',
                                                    'booking_customer_id'       => $agent_id,
                                                    'agent_id'                  => $agent_id,
                                                    'agent_name'                => $agent_Name,
                                                    'agent_company_name'        => $agent_company_name,
                                                    'booking_date'              => date('d-m-Y', strtotime($val_HB->created_at)),
                                                    'booking_type'              => 'Hotel Booking',
                                                    'status'                    => $val_HB->status,
                                                    'all_services_quotation'    => 'NO',
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // dd($aray_data);
        
        $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->get();
        if($request->customer_id == 4 || $request->customer_id == 54){
            // dd($aray_data);
            $aray_data      = $this->arrival_List_Season_Working($aray_data,$request);
            // dd($aray_data);
        }
      
        return response()->json(['hotel_supplier'=>$aray_data]);
    }
    
    public function arrival_List_Season_Working($all_data,$request){
        // dd($request->season_Id);
        
        $today_Date     = date('Y-m-d');
        
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
                // dd('else if');
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        // dd($season_Details);
        
        if($season_Details != null){
            $start_Date = $season_Details->start_Date;
            $end_Date   = $season_Details->end_Date;
            
            $filtered_data = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->check_in)) {
                    return false;
                }
                
                if($record->check_in != null && $record->check_in != ''){
                    $checkIn    = Carbon::parse($record->check_in);
                    $checkOut   = isset($record->check_out) ? Carbon::parse($record->check_out) : $checkIn;
                    return $checkIn->between($start_Date, $end_Date) || $checkOut->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkOut->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            // $all_data = $filtered_data;
            $all_data = array_values($filtered_data->toArray());
        }
        return $all_data;
    }
   
    public function hotel_supplier_reports_sub_departure_new_subUser(Request $request){
        $request_data =  json_decode($request->request_data);
        if($request_data->report_type == 'all_data'){
          
          // Check ALL Supplier Or Not
          if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
              $allgetsuppliers = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get(); 
             $hotel_supplier = DB::table('add_manage_invoices')
             ->where('customer_id',$request->customer_id)
             ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL 
                // && isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL
                )
                {
                //   $accomodation_details=json_decode($hotel->accomodation_details);
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                //   $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->more_hotel_supplier_id))
                       {
                    foreach($allgetsuppliers as $suppliers)
                    {
                       if($suppliers->id == $arr_hotel->more_hotel_supplier_id)
                       {
                           
                       
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=>$arr_hotel->more_acc_type,
                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=>$arr_hotel->more_acc_qty,
                        'supplier'=>$arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                       }
                    }
                  }
                }
                }
                
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                       {
                     foreach($allgetsuppliers as $suppliers)
                    {
                       if($suppliers->id == $arr_hotel->hotel_supplier_id)
                       {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                       }
                    }
                  }
                  }
                }
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
        
          }
          
          if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent')
          {
               
               
                          $hotel_supplier = DB::table('add_manage_invoices')
                          ->where('customer_id',$request->customer_id)
                          ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL 
                && isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL
                )
                {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                  $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                  foreach($array_merge as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                       'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                       
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                else
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                }
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);   
         
           }
           
            if($request_data->supplier != 'all' && $request_data->agent_Id == 'all_agent')
           {
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
          
          else
          {
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
      }
      
        if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise' || $request_data->report_type == 'data_week_wise'|| $request_data->report_type == 'data_month_wise'){
       
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
                 
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                                            
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise')
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_out == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise')
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_out == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                             'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
             }
           
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent'){
                 
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                                            
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_out == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_out == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
             } 
             
            else{
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                                            
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_out == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_out == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
                  
                  
                 
              }
        }
      
        if($request_data->report_type == 'data_wise'){
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                    if($arr_hotel->acc_check_out >= $request_data->check_in && $arr_hotel->acc_check_out <= $request_data->check_out)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_out >= $request_data->check_in && $arr_hotel->more_acc_check_out <= $request_data->check_out
                     
                      )
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=>  date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
         }
            
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent')
            {
               
        $hotel_supplier = DB::table('add_manage_invoices')
        ->where('customer_id',$request->customer_id)
        ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                     
                    if($arr_hotel->acc_check_out >= $request_data->check_in && $arr_hotel->acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_out >= $request_data->check_in && $arr_hotel->more_acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
         
           }
           
            else
            {
                $hotel_supplier = DB::table('add_manage_invoices')
                ->where('customer_id',$request->customer_id)
                ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if(isset($arr_hotel->acc_check_in) && isset($arr_hotel->acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->hotel_supplier_id))
                      {
                    if($arr_hotel->acc_check_out >= $request_data->check_in && $arr_hotel->acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }
                  }
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->more_acc_check_in) && isset($arr_hotel->more_acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->more_hotel_supplier_id))
                      {
                      if($arr_hotel->more_acc_check_out >= $request_data->check_in && $arr_hotel->more_acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $arr_hotel->more_hotel_supplier_id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                      }
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]); 
         }
        }
   }
   
    public function hotel_supplier_reports_sub_new_HH(Request $request){
        $request_data       =  json_decode($request->request_data);
        $start_date         = '';
        $end_date           = '';
        
        if($request_data->report_type == 'data_wise'){
            $start_date     = $request_data->check_in;
            $end_date       = $request_data->check_out;
        }
        
        if($request_data->report_type == 'data_today_wise'){
            $start_date     = date('Y-m-d');
            $end_date       = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_tomorrow_wise'){
            $start_date     = date('Y-m-d',strtotime("+1 days"));
            $end_date       = date('Y-m-d',strtotime("+1 days"));
        }
        
        if($request_data->report_type == 'data_week_wise'){
            $startOfWeek    = Carbon::now()->startOfWeek();
            $start_date     = $startOfWeek->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfWeek();
            $end_date       = $endOfWeek->format('Y-m-d');
            // dd($start_date,$end_date,$request_data);
            // $end_date       = date('Y-m-d');
        }
        
        if($request_data->report_type == 'data_month_wise'){
            $startOfMonth   = Carbon::now()->startOfMonth();
            $start_date     = $startOfMonth->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfMonth();
            $end_date       = $endOfWeek->format('Y-m-d');
            // $end_date       = date('Y-m-d');
        }
        
        if(isset($request_data->person_report_type)){
            $person_report_type = $request_data->person_report_type;
        }else if(isset($request_data->person_report_type)){
            $person_report_type = $request_data->report_type;
        }else{
            $person_report_type = '';
        }
      
        //********************************************
        // Invoice Working
        //********************************************
      
        $invoices_query                 = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id);
        $invoice_select_common_element  = ['generate_id','confirm','accomodation_details','accomodation_details_more','f_name','middle_name','created_at','id','lead_fname','lead_lname'];
        
        if($person_report_type != '' && $person_report_type == 'AgentWise'){
            $invoices_query->select('id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
            if($request_data->agent_Id == 'all_agent'){
                $invoices_query->where('agent_Id','>',0);
            }
          
            if($request_data->agent_Id != 'all_agent'){
                $invoices_query->where('agent_Id',$request_data->agent_Id);
            }
        }else if($person_report_type != '' && $person_report_type == 'CustomerWise'){
            $invoices_query->select('id','booking_customer_id',...$invoice_select_common_element);
            if($request_data->customer_Id == 'all_customer'){
                $invoices_query->where('booking_customer_id','>',0);
            }
          
            if($request_data->customer_Id != 'all_customer'){
                $invoices_query->where('booking_customer_id',$request_data->customer_Id);
            }
        }else{
            $invoices_query->select('id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
        }
        
        $invoices_data = $invoices_query->get();
        
        // return $invoices_data;
      
        $aray_data = [];
        foreach($invoices_data as $invoice_res){
            
            $invoice_Supplier_Name = '';
            
            if($request_data->report_type == 'all_data'){
                
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                    'room_type'=>$arr_hotel->more_acc_type,
                                    'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                    'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'=>$arr_hotel->more_acc_qty,
                                    'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $aray_data[]=(object)[
                                    'invoice_no'=> $invoice_res->generate_id,
                                    'invoice_id'=>$invoice_res->id,
                                    'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'=> $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                    'room_type'=> $arr_hotel->acc_type,
                                    'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                    'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                    'quantity'=> $arr_hotel->acc_qty,
                                    'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                    'agent_id'=> $invoice_res->agent_Id ?? '',
                                    'agent_name'=> $invoice_res->agent_Name ?? '',
                                    'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                    'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'=> 'invoice',
                                    'status'=> $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
                
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        // dd($arr_hotel);
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$arr_hotel->more_acc_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            'room_type'=>$arr_hotel->more_acc_type,
                                            'meal_Type'=>$arr_hotel->more_hotel_meal_type ?? '',
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$arr_hotel->more_acc_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        // return $invoice_res;
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                      
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    
                                    // return $arr_hotel;
                                    
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $arr_hotel->acc_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                        'agent_id'=> $invoice_res->agent_Id ?? '',
                                        'agent_name'=> $invoice_res->agent_Name ?? '',
                                        'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'invoice',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'meal_Type'=> $arr_hotel->hotel_meal_type ?? '',
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $arr_hotel->acc_qty,
                                            'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_customer_id'=> $invoice_res->booking_customer_id ?? '',
                                            'agent_id'=> $invoice_res->agent_Id ?? '',
                                            'agent_name'=> $invoice_res->agent_Name ?? '',
                                            'agent_company_name'=> $invoice_res->agent_Company_Name ?? $invoice_res->f_name . ' ' . $invoice_res->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                             
                            }
                        }
                    }
                }
            }
        }
        
        // dd($aray_data);
      
        //********************************************
        //Package Working
        //********************************************
      
        $invoices_query                 = DB::table('cart_details')->where('client_id',$request->customer_id)
                                            ->join('tours','tours.id','=','cart_details.tour_id')->join('tours_bookings','tours_bookings.id','=','cart_details.booking_id');
        $invoice_select_common_element  = ['tours.id as tour_id','cart_details.agent_name as agent_Id','cart_details.invoice_no as generate_id','cart_details.confirm','tours.accomodation_details','tours.accomodation_details_more','tours_bookings.passenger_name as f_name','cart_details.created_at','cart_details.cart_total_data'];
        // return $invoices_query;
        
        // return $person_report_type;
        
        if($person_report_type != '' && $person_report_type == 'AgentWise'){
            if($request_data->agent_Id == 'all_agent'){
                $invoices_query->where('cart_details.agent_name','>',0);
            }
            
            if($request_data->agent_Id != 'all_agent'){
                $invoices_query->where('cart_details.agent_name',$request_data->agent_Id);
            }
        }else if($person_report_type != '' && $person_report_type == 'CustomerWise'){
            if($request_data->customer_Id == 'all_customer'){
                $invoices_query->where(function($query){
                    $query->where('agent_name','-1')->orWhere('agent_name','=',NULL);
                });
            }
          
            if($request_data->customer_Id != 'all_customer'){
                $invoices_query->whereJsonContains('cart_details.cart_total_data->customer_id',"$request_data->customer_Id");
            }
        }else{
            // $invoices_query->select($invoice_select_common_element);
        }
        
        $invoices_query->select($invoice_select_common_element);
        $invoices_data = $invoices_query->get();
        // $invoices_data = $invoices_query;
        // return $invoices_data;
      
        // foreach($invoices_data as $invoice_res){
        //     if($request_data->report_type == 'all_data'){}
        // }
        
        foreach($invoices_data as $invoice_res){
            $invoice_res->confirm = "confirm";
            
            if(isset($invoice_res->agent_Id) && $invoice_res->agent_Id > 0){
                $agent_Details_PB                   = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$invoice_res->agent_Id)->first();
                $agent_id                           = $agent_Details_PB->id;
                $agent_name                         = $agent_Details_PB->agent_Name ?? '';
                $agent_company_name                 = $agent_Details_PB->agent_company_name ?? '';
            }else{
                if(isset($invoice_res->booking_customer_id) && $invoice_res->booking_customer_id != null && $invoice_res->booking_customer_id != ''){
                    $customer_Details_PB            = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$invoice_res->booking_customer_id)->first();
                    $agent_id                       = $customer_Details_PB->id;
                    $agent_name                     = $customer_Details_PB->agent_Name ?? '';
                    $agent_company_name             = $customer_Details_PB->agent_company_name ?? '';
                }else{
                    $cart_total_data                = json_decode($invoice_res->cart_total_data);
                    if(isset($cart_total_data->agent_name) && $cart_total_data->agent_name > 0){
                        $agent_Details_PB           = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$cart_total_data->agent_name)->first();
                        $agent_id                   = $agent_Details_PB->id;
                        $agent_name                 = $agent_Details_PB->agent_Name ?? '';
                        $agent_company_name         = $agent_Details_PB->agent_company_name ?? '';
                    }else{
                        if(isset($cart_total_data->customer_id) && $cart_total_data->customer_id > 0){
                            $customer_Details_PB    = DB::table('booking_customers')->where('customer_id',$request->customer_id)->where('id',$cart_total_data->customer_id)->first();
                            $agent_id               = $customer_Details_PB->id ?? '';
                            $agent_name             = $customer_Details_PB->name ?? '';
                            $agent_company_name     = $customer_Details_PB->name ?? '';
                        }
                    }
                }
            }
            
            if($request_data->report_type == 'all_data'){
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'            => $invoice_res->generate_id,
                                    'invoice_id'            => $invoice_res->tour_id,
                                    'check_in'              => date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                    'check_out'             => date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                    'hotel_name'            => $arr_hotel->more_acc_hotel_name,
                                    'hotel_city_name'       => $arr_hotel->more_hotel_city,
                                    'room_type'             => $arr_hotel->more_acc_type,
                                    'reservation_no'        => $arr_hotel->more_room_reservation_no ?? '',
                                    'quantity'              => $room_qty,
                                    'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'   => $invoice_res->f_name,
                                    'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                    'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                    'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                    'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'          => 'Package',
                                    'status'                => $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'            => $invoice_res->generate_id,
                                        'invoice_id'            => $invoice_res->tour_id,
                                        'check_in'              => date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'             => date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'            => $arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'       => $arr_hotel->more_hotel_city,
                                        'room_type'             => $arr_hotel->more_acc_type,
                                        'reservation_no'        => $arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'              => $room_qty,
                                        'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'   => $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'          => 'Package',
                                        'status'                => $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                $room_qty                   = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                $aray_data[]=(object)[
                                    'invoice_no'            => $invoice_res->generate_id,
                                    'invoice_id'            => $invoice_res->tour_id,
                                    'check_in'              => date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                    'check_out'             => date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                    'hotel_name'            => $arr_hotel->acc_hotel_name,
                                    'hotel_city_name'       => $arr_hotel->hotel_city_name,
                                    'room_type'             => $arr_hotel->acc_type,
                                    'reservation_no'        => $arr_hotel->room_reservation_no ?? '',
                                    'quantity'              => $room_qty,
                                    'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                    'lead_passenger_name'   => $invoice_res->f_name,
                                    'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                    'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                    'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                    'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                    'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                    'booking_type'          => 'Package',
                                    'status'                => $invoice_res->confirm,
                                ];
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    $room_qty                   = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'            => $invoice_res->generate_id,
                                        'invoice_id'            => $invoice_res->tour_id,
                                        'check_in'              => date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'             => date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'            => $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'       => $arr_hotel->hotel_city_name,
                                        'room_type'             => $arr_hotel->acc_type,
                                        'reservation_no'        => $arr_hotel->room_reservation_no ?? '',
                                        'quantity'              => $room_qty,
                                        'supplier'              => $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'   => $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'          => date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'          => 'Package',
                                        'status'                => $invoice_res->confirm,
                                    ];
                                }
                            }
                        }
                    }
                }
            }else{
                if(isset($invoice_res->accomodation_details_more) && $invoice_res->accomodation_details_more != 'null' && $invoice_res->accomodation_details_more != NULL){
                    $accomodation_details_more = json_decode($invoice_res->accomodation_details_more);
                    foreach($accomodation_details_more as $arr_hotel){
                        
                        if(isset($arr_hotel->more_new_supplier_id) && $arr_hotel->more_new_supplier_id != null && $arr_hotel->more_new_supplier_id != '' && $arr_hotel->more_new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->more_hotel_supplier_id) && $arr_hotel->more_hotel_supplier_id != null && $arr_hotel->more_hotel_supplier_id != '' && $arr_hotel->more_hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->more_hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                        'room_type'=>$arr_hotel->more_acc_type,
                                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                        'quantity'=>$room_qty,
                                        'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->more_hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->more_hotel_supplier_id){
                                    if($arr_hotel->more_acc_check_in >= $start_date && $arr_hotel->more_acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->more_acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                                            'room_type'=>$arr_hotel->more_acc_type,
                                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                                            'quantity'=>$room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                            'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                            'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(isset($invoice_res->accomodation_details) && $invoice_res->accomodation_details != 'null' && $invoice_res->accomodation_details != NULL){
                    $accomodation_details = json_decode($invoice_res->accomodation_details);
                    foreach($accomodation_details as $arr_hotel){
                        
                        if(isset($arr_hotel->new_supplier_id) && $arr_hotel->new_supplier_id != null && $arr_hotel->new_supplier_id != '' && $arr_hotel->new_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->new_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if(isset($arr_hotel->hotel_supplier_id) && $arr_hotel->hotel_supplier_id != null && $arr_hotel->hotel_supplier_id != '' && $arr_hotel->hotel_supplier_id != 'Select One'){
                            $invoice_Supplier_Name = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$arr_hotel->hotel_supplier_id)->select('room_supplier_name')->first();
                        }
                        
                        if($request_data->supplier == 'all'){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                    $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                    $aray_data[]=(object)[
                                        'invoice_no'=> $invoice_res->generate_id,
                                        'invoice_id'=>$invoice_res->tour_id,
                                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                        'room_type'=> $arr_hotel->acc_type,
                                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                        'quantity'=> $room_qty,
                                        'supplier'=> $invoice_Supplier_Name->room_supplier_name ?? '',
                                        'lead_passenger_name'=> $invoice_res->f_name,
                                        'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                        'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                        'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                        'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                        'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                        'booking_type'=> 'Package',
                                        'status'=> $invoice_res->confirm,
                                    ];
                                }
                            }
                        }else{
                            if(isset($arr_hotel->hotel_supplier_id)){
                                if($request_data->supplier == $arr_hotel->hotel_supplier_id){
                                    if($arr_hotel->acc_check_in >= $start_date && $arr_hotel->acc_check_in <= $end_date){
                                        $room_qty = $this->getRoomsQty($invoice_res->cart_total_data,$arr_hotel->acc_type);
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $invoice_res->generate_id,
                                            'invoice_id'=>$invoice_res->tour_id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $room_qty,
                                            'supplier'=>$invoice_Supplier_Name->room_supplier_name ?? '',
                                            'lead_passenger_name'=> $invoice_res->f_name,
                                            'booking_customer_id'   => $agent_id ?? $invoice_res->booking_customer_id ?? '',
                                            'agent_id'              => $agent_id ?? $invoice_res->agent_Id ?? '',
                                            'agent_name'            => $agent_name ?? $invoice_res->agent_Name ?? '',
                                            'agent_company_name'    => $agent_company_name ?? $invoice_res->agent_Name ?? '',
                                            'booking_date'=> date('d-m-Y', strtotime($invoice_res->created_at)),
                                            'booking_type'=> 'Package',
                                            'status'=> $invoice_res->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // dd($aray_data);
      
        return response()->json(['hotel_supplier'=>$aray_data]);
    }
   
    public function hotel_supplier_reports_sub_departure_new_subUser_HH(Request $request){
        $request_data =  json_decode($request->request_data);
        if($request_data->report_type == 'all_data'){
          
          // Check ALL Supplier Or Not
          if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
              $allgetsuppliers = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get(); 
             $hotel_supplier = DB::table('add_manage_invoices')
             ->where('customer_id',$request->customer_id)
             ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL 
                // && isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL
                )
                {
                //   $accomodation_details=json_decode($hotel->accomodation_details);
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                //   $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->more_hotel_supplier_id))
                       {
                    foreach($allgetsuppliers as $suppliers)
                    {
                       if($suppliers->id == $arr_hotel->more_hotel_supplier_id)
                       {
                           
                       
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=>$arr_hotel->more_acc_type,
                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=>$arr_hotel->more_acc_qty,
                        'supplier'=>$arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                       }
                    }
                  }
                }
                }
                
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                       {
                     foreach($allgetsuppliers as $suppliers)
                    {
                       if($suppliers->id == $arr_hotel->hotel_supplier_id)
                       {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                       }
                    }
                  }
                  }
                }
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
        
          }
          
          if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent')
          {
               
               
                          $hotel_supplier = DB::table('add_manage_invoices')
                          ->where('customer_id',$request->customer_id)
                          ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL 
                && isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL
                )
                {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                  $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                  foreach($array_merge as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                       'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                       
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                else
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                }
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);   
         
           }
           
            if($request_data->supplier != 'all' && $request_data->agent_Id == 'all_agent')
           {
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
          
          else
          {
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
      }
      
        if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise' || $request_data->report_type == 'data_week_wise'|| $request_data->report_type == 'data_month_wise'){
       
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
                 
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                                            
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise')
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_out == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise')
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_out == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                             'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
             }
           
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent'){
                 
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                                            
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_out == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_out == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
             } 
             
            else{
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                                            
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_out == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_out == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
                  
                  
                 
              }
        }
      
        if($request_data->report_type == 'data_wise'){
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                    if($arr_hotel->acc_check_out >= $request_data->check_in && $arr_hotel->acc_check_out <= $request_data->check_out)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_out >= $request_data->check_in && $arr_hotel->more_acc_check_out <= $request_data->check_out
                     
                      )
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=>  date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
         }
            
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent')
            {
               
        $hotel_supplier = DB::table('add_manage_invoices')
        ->where('customer_id',$request->customer_id)
        ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                     
                    if($arr_hotel->acc_check_out >= $request_data->check_in && $arr_hotel->acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_out >= $request_data->check_in && $arr_hotel->more_acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
         
           }
           
            else
            {
                $hotel_supplier = DB::table('add_manage_invoices')
                ->where('customer_id',$request->customer_id)
                ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if(isset($arr_hotel->acc_check_in) && isset($arr_hotel->acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->hotel_supplier_id))
                      {
                    if($arr_hotel->acc_check_out >= $request_data->check_in && $arr_hotel->acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }
                  }
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->more_acc_check_in) && isset($arr_hotel->more_acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->more_hotel_supplier_id))
                      {
                      if($arr_hotel->more_acc_check_out >= $request_data->check_in && $arr_hotel->more_acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $arr_hotel->more_hotel_supplier_id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                      }
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]); 
         }
        }
   }
   
    public function hotel_supplier_reports_sub_departure_new(Request $request){
        $request_data =  json_decode($request->request_data);
        //dd($request_data);
        if($request_data->report_type == 'all_data'){
          
          // Check ALL Supplier Or Not
          if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
              $allgetsuppliers = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get(); 
             $hotel_supplier = DB::table('add_manage_invoices')
             ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL 
                // && isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL
                )
                {
                //   $accomodation_details=json_decode($hotel->accomodation_details);
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                //   $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->more_hotel_supplier_id))
                       {
                    foreach($allgetsuppliers as $suppliers)
                    {
                       if($suppliers->id == $arr_hotel->more_hotel_supplier_id)
                       {
                           
                       
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=>$arr_hotel->more_acc_type,
                        'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=>$arr_hotel->more_acc_qty,
                        'supplier'=>$arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                       }
                    }
                  }
                }
                }
                
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                       {
                     foreach($allgetsuppliers as $suppliers)
                    {
                       if($suppliers->id == $arr_hotel->hotel_supplier_id)
                       {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                       }
                    }
                  }
                  }
                }
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
        
          }
          if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent')
          {
               
               
                          $hotel_supplier = DB::table('add_manage_invoices')
                          ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL 
                && isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL
                )
                {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                  $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                  foreach($array_merge as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                       'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                       
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                else
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                }
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);   
         
           }
            if($request_data->supplier != 'all' && $request_data->agent_Id == 'all_agent')
           {
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
          else
          {
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
      }
      
      
      if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise' || $request_data->report_type == 'data_week_wise'|| $request_data->report_type == 'data_month_wise')
      {
       if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
            $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->report_type == 'data_today_wise')
                       {
                         $today_date=date('Y-m-d');
                        if($arr_hotel->acc_check_out == $today_date)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_tomorrow_wise')
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+1 day');
                            $tomorrowdate=$datetime->format('Y-m-d');
                        if($arr_hotel->acc_check_out == $tomorrowdate)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_week_wise')
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+6 day');
                            $this_week=$datetime->format('Y-m-d');
                        if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_week)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_month_wise')
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                             $datetime->modify('+29 day');
                            $this_month=$datetime->format('Y-m-d');
                        if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_month)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($request_data->report_type == 'data_today_wise')
                       {
                          $today_date=date('Y-m-d');
                          if($arr_hotel->more_acc_check_out == $today_date)
                          {
                            $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];  
                          }
                       }
                      if($request_data->report_type == 'data_tomorrow_wise')
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+1 day');
                            $tomorrowdate=$datetime->format('Y-m-d');
                        if($arr_hotel->more_acc_check_out == $tomorrowdate)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_week_wise')
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+6 day');
                            $this_week=$datetime->format('Y-m-d');
                        if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_week)
                        {  
                      
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_month_wise')
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                             $datetime->modify('+29 day');
                            $this_month=$datetime->format('Y-m-d');
                        if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_month)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                         'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                    
                       
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
         }
       if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent'){
             
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
            $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                       {
                         $today_date=date('Y-m-d');
                        if($arr_hotel->acc_check_out == $today_date)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+1 day');
                            $tomorrowdate=$datetime->format('Y-m-d');
                        if($arr_hotel->acc_check_out == $tomorrowdate)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+6 day');
                            $this_week=$datetime->format('Y-m-d');
                        if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_week)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                             $datetime->modify('+29 day');
                            $this_month=$datetime->format('Y-m-d');
                        if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_month)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                       {
                          $today_date=date('Y-m-d');
                          if($arr_hotel->more_acc_check_out == $today_date)
                          {
                            $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];  
                          }
                       }
                      if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+1 day');
                            $tomorrowdate=$datetime->format('Y-m-d');
                        if($arr_hotel->more_acc_check_out == $tomorrowdate)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+6 day');
                            $this_week=$datetime->format('Y-m-d');
                        if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_week)
                        {  
                      
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                             $datetime->modify('+29 day');
                            $this_month=$datetime->format('Y-m-d');
                        if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_month)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                    
                       
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
         } 
       else{
              
              
              
              
              
              
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
            $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      
                      if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                       {
                         $today_date=date('Y-m-d');
                        if($arr_hotel->acc_check_out == $today_date)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+1 day');
                            $tomorrowdate=$datetime->format('Y-m-d');
                        if($arr_hotel->acc_check_out == $tomorrowdate)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+6 day');
                            $this_week=$datetime->format('Y-m-d');
                        if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_week)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                             $datetime->modify('+29 day');
                            $this_month=$datetime->format('Y-m-d');
                        if($arr_hotel->acc_check_out >= $today_date && $arr_hotel->acc_check_out <= $this_month)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                       {
                          $today_date=date('Y-m-d');
                          if($arr_hotel->more_acc_check_out == $today_date)
                          {
                            $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];  
                          }
                       }
                      if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+1 day');
                            $tomorrowdate=$datetime->format('Y-m-d');
                        if($arr_hotel->more_acc_check_out == $tomorrowdate)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+6 day');
                            $this_week=$datetime->format('Y-m-d');
                        if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_week)
                        {  
                      
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                       if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                       {
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                             $datetime->modify('+29 day');
                            $this_month=$datetime->format('Y-m-d');
                        if($arr_hotel->more_acc_check_out >= $today_date && $arr_hotel->more_acc_check_out <= $this_month)
                        {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }    
                       }
                    
                       
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
              
              
             
          }
      }
      
      
      if($request_data->report_type == 'data_wise'){
         if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                    if($arr_hotel->acc_check_out >= $request_data->check_in && $arr_hotel->acc_check_out <= $request_data->check_out)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_out >= $request_data->check_in && $arr_hotel->more_acc_check_out <= $request_data->check_out
                     
                      )
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=>  date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
         }
         if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent')
           {
               
        $hotel_supplier = DB::table('add_manage_invoices')
        ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                     
                    if($arr_hotel->acc_check_out >= $request_data->check_in && $arr_hotel->acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_out >= $request_data->check_in && $arr_hotel->more_acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
         
           }
         else
         {
                $hotel_supplier = DB::table('add_manage_invoices')
                ->where('customer_id',$request->customer_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if(isset($arr_hotel->acc_check_in) && isset($arr_hotel->acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->hotel_supplier_id))
                      {
                    if($arr_hotel->acc_check_out >= $request_data->check_in && $arr_hotel->acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }
                  }
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->more_acc_check_in) && isset($arr_hotel->more_acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->more_hotel_supplier_id))
                      {
                      if($arr_hotel->more_acc_check_out >= $request_data->check_in && $arr_hotel->more_acc_check_out <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $arr_hotel->more_hotel_supplier_id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                      }
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]); 
         }
      }
    //   print_r($request_data);
    //   die;
   }

    public function hotel_supplier_stats(Request $request){
        // print_r($request->all());
         $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            
            function dateDiffInDays($date1, $date2){
                $diff = strtotime($date2) - strtotime($date1);
                return abs(round($diff / 86400));
            }
            
            function getBetweenDates($startDate, $endDate){
                $rangArray = [];
                    
                $startDate = strtotime($startDate);
                $endDate = strtotime($endDate);
                     
                for ($currentDate = $startDate; $currentDate <= $endDate; 
                                                $currentDate += (86400)) {
                                                        
                    $date = date('Y-m-d', $currentDate);
                    $rangArray[] = $date;
                }
          
                return $rangArray;
            }
 



              $suppliers = DB::table('rooms_Invoice_Supplier')
                                ->where('customer_id',$userData->id)->get();
              $all_rooms_types = DB::table('rooms_types')
                                ->where('customer_id',$userData->id)->get();
              $all_suppliers_data = [];
              foreach($suppliers as $sup_res){
                  
                  
                  
                  $supplier_total_rooms = 0;
                  $supplier_total_rooms_booked = 0;
                  $supplier_total_cost = 0;
                  $supplier_total_payable = 0;
                  $supplier_total_paid = 0;
                

                  $room_records = [];
                  $rooms_types_arr = [];
                  
                  foreach($all_rooms_types as $room_type_res){
                      $rooms_types_count = 0;
                      $rooms_types_count_booked = 0;

                        $suppliers_rooms = DB::table('rooms')
                                        ->where('room_supplier_name',$sup_res->id)
                                        ->where('room_type_cat',$room_type_res->id)
                                        // ->orWhere(function($query) use($sup_res,$room_type_res) {
                                        //     $query->whereJsonContains('more_room_type_details',['more_room_supplier_name'=>"$sup_res->id",'more_room_type_id'=> $room_type_res->id]);
                                        //  })
                                        ->get();
                        // echo "supplier id is ".$sup_res->id." room type id is ".$room_type_res->id." Room name is ".$room_type_res->room_type."  \n";
                        // print_r($suppliers_rooms);
                        // die;
                                  foreach($suppliers_rooms as $room_res){
                                    //   echo "The room id is ".$room_res->id;
                                      $hotel_name = DB::table('hotels')->where('id',$room_res->hotel_id)->select('property_name','property_city')->first();
                                       
                                        //  echo "supplier id is ".$sup_res->id."⧵n";
                                      if($room_res->room_supplier_name == $sup_res->id && $room_res->room_type_cat == $room_type_res->id){
                                        //   echo "The supplier id is $sup_res->id and room sup id is ".$room_res->room_supplier_name;
                                         $supplier_total_rooms += $room_res->quantity;
                                         $rooms_types_count += $room_res->quantity;
                                         $rooms_types_count_booked += $room_res->booked;
                                         $supplier_total_rooms_booked += $room_res->booked;
                                     
                                          
                                          
                                          
                                        
                                        
                                        // Calculate Price 
                                        
                                         $week_days_total = 0;
                                         $week_end_days_totals = 0;
                                         $total_price = 0;
                                        if($room_res->price_week_type == 'for_all_days'){
                                            $avaiable_days = dateDiffInDays($room_res->availible_from, $room_res->availible_to);
                                            $total_price = $room_res->price_all_days * $avaiable_days;
                                        }else{
                                             $avaiable_days = dateDiffInDays($room_res->availible_from, $room_res->availible_to);
                                             
                                             
                                             $all_days = getBetweenDates($room_res->availible_from, $room_res->availible_to);
                                             $week_days = json_decode($room_res->weekdays);
                                             $week_end_days = json_decode($room_res->weekends);
                                             
                                            
                                             foreach($all_days as $day_res){
                                                 $day = date('l', strtotime($day_res));
                                                 $day = trim($day);
                                                 $week_day_found = false;
                                                 $week_end_day_found = false;
                                                
                                                 foreach($week_days as $week_day_res){
                                                     if($week_day_res == $day){
                                                         $week_day_found = true;
                                                     }
                                                 }
                                          
                                                 if($week_day_found){
                                                     $week_days_total += $room_res->weekdays_price;
                                                 }else{
                                                     $week_end_days_totals += $room_res->weekends_price;
                                                 }
                                                 
                                                 
                                                //  foreach($week_end_days as $week_day_res){
                                                //      if($week_day_res == $day){
                                                //          $week_end_day_found = true;
                                                //      }
                                                //  }
                                                //   if($week_end_day_found){
                                                      
                                                //  }
                                             }
                                             
                                             
                                              
                                             
                                            //  print_r($all_days);
                                             $total_price = $week_days_total + $week_end_days_totals;
                                        }
                                      
                                      
                                             $Invoices_cost_SAR = 0;
                                             $Invoices_cost_GBP = 0;
                                             $website_booking_cost = 0;
                                            $purchase_currency = '';
                                            $sale_currency = '';
                                            
                                            
                                            $rooms_booking_details = DB::table('rooms_bookings_details')->where('room_id',$room_res->id)->get();
                                            if(isset($rooms_booking_details) && !empty($rooms_booking_details)){
                                                $booking_details = $rooms_booking_details;
                                                
                                                foreach($booking_details as $book_res){
                                                    if($book_res->booking_from == 'website'){
                                                        $website_booking = DB::table('hotel_booking')->where('search_id',$book_res->booking_id)->select('payment_status','recieved_amount','amount_paid','total_price')->first();
                                                        
                                                        // dd($website_booking);
                                                        $website_booking_cost += $website_booking->total_price ?? '0';
                                                        // print_r($website_booking);
                                                    }
                                                    
                                                    
                                                   
                                                    if($book_res->booking_from == 'Invoices'){
                                                        $website_booking = DB::table('add_manage_invoices')->where('id',$book_res->booking_id)
                                                        ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details','markup_details','more_markup_details')->first();
                                                        
                                                        
                                                        if(isset($website_booking)){
                                                            $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                            
                                                            $conversion_type = '';
                                                            if(isset($conversion_data)){
                                                                 $purchase_currency = $conversion_data->purchase_currency;
                                                                $sale_currency =  $conversion_data->sale_currency;
                                                                $conversion_type = $conversion_data->conversion_type;
                                                            }else{
                                                                 $purchase_currency = '';
                                                                 $sale_currency =  '';
                                                                 $conversion_type = 'Divided';
                                                            }
                                                           
                                                            $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                            $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                            
                                                            if(isset($accomodation_detials)){
                                                                foreach($accomodation_detials as $acc_res){
                                                                    if($sup_res->id == $acc_res->hotel_supplier_id && $room_type_res->id == $acc_res->hotel_type_id){
                                                                        // print_r($acc_res);
                                                                        $single_inv_cost = $acc_res->acc_total_amount * $acc_res->acc_qty;
                                                                        $Invoices_cost_GBP += $single_inv_cost;
                                                                        
                                                                        
                                                                        if($conversion_type == 'Divided'){
                                                                            $single_inv_cost_SAR = $single_inv_cost * $acc_res->exchange_rate_price;
                                                                        }else{
                                                                            $single_inv_cost_SAR = $single_inv_cost / $acc_res->exchange_rate_price;
                                                                        }
                                                                        
                                                                        $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                        // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                                
                                                                     }
                                                                }
                                                            }
                                                            
                                                            if(isset($accomodation_detials_more)){
                                                                foreach($accomodation_detials_more as $acc_more_res){
                                                                    if($sup_res->id == $acc_more_res->more_hotel_supplier_id  && $room_type_res->id == $acc_more_res->more_hotel_type_id){
                                                                        // print_r($acc_more_res);
                                                                    $single_inv_cost = $acc_more_res->more_acc_total_amount * $acc_more_res->more_acc_qty;
                                                                    $Invoices_cost_GBP += $single_inv_cost;
                                                                    
                                                                    
                                                                      if($conversion_type == 'Divided'){
                                                                            $single_inv_cost_SAR = $single_inv_cost * $acc_more_res->more_exchange_rate_price;
                                                                        }else{
                                                                            $single_inv_cost_SAR = $single_inv_cost / $acc_more_res->more_exchange_rate_price;
                                                                        }
                                                                        
                                                                    
                                                                    $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                    // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                                
                                                                    }
                                                                }
                                                            }
                                                        }
                                                       
                                                        // print_r($accomodation_detials);
                                                        // print_r($accomodation_detials_more);
                                                    
                                                        
                                                        // echo "This is Next ";
                                                    }
                                                    
                                                     if($book_res->booking_from == 'package'){
                                                        //  print_r($book_res);
                                                          $cart_data = DB::table('cart_details')->where('booking_id',$book_res->booking_id)->select('id','tour_id')->first();
                                            
                                                            if(isset($cart_data)){
                                                                $website_booking = DB::table('tours')->where('id',$cart_data->tour_id)
                                                                 ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details')->first();
                                                              
                                                                if(isset($website_booking)){
                                                                  $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                                  
                                                                  
                                                                  $conversion_type = '';
                                                                 if(isset($conversion_data)){
                                                                        $purchase_currency = $conversion_data->purchase_currency;
                                                                        $sale_currency =  $conversion_data->sale_currency;
                                                                        $conversion_type == $conversion_data->conversion_type;
                                                                    }else{
                                                                        $purchase_currency = '';
                                                                        $sale_currency =  '';
                                                                        
                                                                        $conversion_type = 'Divided';
                                                                    }
                                                                  $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                                  $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                                  
                                                                  
                                                                //   print_r($accomodation_detials);
                                                                //   die;
                                                                  if(isset($accomodation_detials)){
                                                                      foreach($accomodation_detials as $acc_res){
                                                                          if(isset($acc_res->hotel_supplier_id)){
                                                                         if($sup_res->id == $acc_res->hotel_supplier_id && $room_type_res->id == $acc_res->hotel_type_id){
                                                                              // print_r($acc_res);
                                                                              $single_inv_cost = ($acc_res->price_per_room_sale * $acc_res->acc_no_of_nightst) * $book_res->quantity;
                                                                              $Invoices_cost_GBP += $single_inv_cost;
                                                                              
                                                                              if($conversion_type == 'Divided'){
                                                                                  $single_inv_cost_SAR = $single_inv_cost * $acc_res->exchange_rate_price;
                                                                              }else{
                                                                                  $single_inv_cost_SAR = $single_inv_cost / $acc_res->exchange_rate_price;
                                                                              }
                                                            
                                                                              
                                                                              $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                              // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                                      
                                                                            
                                                                           }
                                                                          }
                                                                      }
                                                                  }
                                                                  
                                                                  if(isset($accomodation_detials_more)){
                                                                      foreach($accomodation_detials_more as $acc_more_res){
                                                                          
                                                                          if(isset($acc_res->hotel_supplier_id)){
                                                                          if($sup_res->id == $acc_more_res->more_hotel_supplier_id  && $room_type_res->id == $acc_more_res->more_hotel_type_id){
                                                                              // print_r($acc_more_res);
                                                                          $single_inv_cost = ($acc_more_res->more_price_per_room_sale * $acc_more_res->more_acc_no_of_nightst) * $book_res->quantity;
                                                                          $Invoices_cost_GBP += $single_inv_cost;
                                                                          
                                                                           if($conversion_type == 'Divided'){
                                                                              $single_inv_cost_SAR = $single_inv_cost * $acc_more_res->more_exchange_rate_price;
                                                                          }else{
                                                                              $single_inv_cost_SAR = $single_inv_cost / $acc_more_res->more_exchange_rate_price;
                                                                          }
                                                                              
                                                                          
                                                                          $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                          // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                                      
                                                                             
                                                                          }
                                                                      }
                                                                      }
                                                                  }
                                                              }
                                                            }
                                                         
                                                      }
                                                    
                                                    
                                                }
                                                
                                            }
                                            
                                            
                                             
                                      
                                            $room_record_single = ['room_id'=>$room_res->id,
                                                             'paid_amount'=>$room_res->paid_amount,
                                                             'over_paid_amount'=>$room_res->over_paid,
                                                             'room_supplier_id'=>$sup_res->id,
                                                             'room_supplier_name'=>$sup_res->room_supplier_name,
                                                             'wallat_balance'=>$sup_res->wallat_balance,
                                                             'hotel_id'=>$room_res->hotel_id,
                                                             'hotel_name'=>$hotel_name->property_name ?? '',
                                                             'hotel_city'=>$hotel_name->property_city ?? '',
                                                             'hotel_id'=>$room_res->hotel_id,
                                                             'room_type'=>$room_res->room_type_id,
                                                             'room_generate_id'=>$room_res->room_gen_id,
                                                             'room_type_id'=>$room_res->room_type_cat,
                                                             'room_type_name'=>$room_type_res->room_type,
                                                             'quantity'=>$room_res->quantity,
                                                             'booked'=>$room_res->booked,
                                                             'booked_details'=>$room_res->booking_details,
                                                             'purchase_currency'=>$purchase_currency,
                                                             'sale_currency'=>$sale_currency,
                                                             'total_cost_purchase'=>$Invoices_cost_SAR + $website_booking_cost,
                                                             'total_cost_convert'=>$Invoices_cost_GBP,
                                                             'website_booking_cost'=>$website_booking_cost,
                                                             'booked_details'=>$room_res->booking_details,
                                                             'availible_from'=>$room_res->availible_from,
                                                             'availible_to'=>$room_res->availible_to,
                                                             'avaiable_days'=>$avaiable_days,
                                                             'price_week_type'=>$room_res->price_week_type,
                                                             'price_all_days'=>$room_res->price_all_days,
                                                             'weekdays'=>$room_res->weekdays,
                                                             'weekdays_price'=>$room_res->weekdays_price,
                                                             'weekends'=>$room_res->weekends,
                                                             'weekends_price'=>$room_res->weekends_price,
                                                             'weekdays_total'=>$week_days_total,
                                                             'weekends_total'=>$week_end_days_totals,
                                                             'single_room_price'=>$total_price,
                                                             'all_room_price'=>$total_price * $room_res->quantity
                                                             ];
                                                             
                                                             $supplier_total_paid += $room_res->paid_amount;
                                            $supplier_total_cost += $total_price * $room_res->quantity;
                                            $supplier_total_payable += ($Invoices_cost_SAR + $website_booking_cost);
                                             array_push($room_records,$room_record_single);
                                      }
                                      
                                      
                                     
                                                             
                                       
                                      
                                
                                  }
                                  
                                  $single_type_record = [
                                        'room_type_id'=>$room_type_res->id,
                                        'type_name'=>$room_type_res->room_type,
                                        'rooms_count'=>$rooms_types_count,
                                        'booked'=>$rooms_types_count_booked
                                      ];
                                      
                                 array_push($rooms_types_arr,$single_type_record);
                  }
                  
                //   print_r($suppliers_rooms);
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  $supplier_data = ['supplier_id'=>$sup_res->id,
                                    'supplier_name'=>$sup_res->room_supplier_name,
                                    'wallet_amount'=>$sup_res->wallat_balance,
                                    'total_rooms'=>$supplier_total_rooms,
                                    'total_booked'=>$supplier_total_rooms_booked,
                                    'supplier_total_cost' =>$supplier_total_cost,
                                    'supplier_total_payable'=>$supplier_total_payable,
                                    'total_paid'=>$supplier_total_paid,
                                    'rooms_details'=>$rooms_types_arr,
                                    'rooms_data'=>$room_records
                  ];
                  
                  array_push($all_suppliers_data,$supplier_data);
                //   
              }
              
              
              
            // print_r($all_suppliers_data);
        }
      
        
    // $customer_id = $request->customer_id;
    //   $data=DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
      

      return response()->json(['status'=>'success','data'=>$all_suppliers_data]);
        
        
   }

    public function hotel_supplier_payments(Request $request){
    //   print_r($request->all());
    //   die;
       $request_data = json_decode($request->request_data);
         DB::beginTransaction();
                        try {
                            
                                $room_data = DB::table('rooms')->where('id',$request_data->room_id)->first();
                      
                                if($request_data->payment_type == 'payable_amount'){
                                    $total_amount = $request_data->total_amount_payable;
                                }else{
                                    $total_amount = $request_data->total_amount;
                                }
                                
                                if(!empty($request_data->room_id_generate) && $request_data->room_id_generate != null && $request_data->room_id_generate != 'null'){
                                    
                                    $rooms_more_data = json_decode($room_data->more_room_type_details);
                                    
                                     foreach($rooms_more_data as $key => $room_more_res){
                                        // echo "The room gen ".$room_more_res->room_gen_id." and request is ".$request->generate_id;
                                        if($room_more_res->room_gen_id == $request_data->room_id_generate){
                                            // print_r($room_more_res);
                                            
                                            
                                            $paid_amount = 0;
                                            $over_paid_amount = 0;
                                            if(isset($room_more_res->paid_amount)){
                                                $paid_amount = $room_more_res->paid_amount;
                                                $over_paid_amount = $room_more_res->over_paid;
                                            }
                                            
                                            $total_paid_amount = $paid_amount + $request_data->amount_paid;
                                            $total_over_paid = 0;
                                            $over_paid_am = 0;
                                            if($total_paid_amount > $total_amount){
                                                $over_paid_am = $total_paid_amount - $total_amount;
                                                $total_over_paid = $over_paid_amount + $over_paid_am;
                                                
                                                $total_paid_amount = $total_paid_amount - $over_paid_am;
                                            }
                                            
                                            $rooms_more_data[$key]->paid_amount = $total_paid_amount;
                                            $rooms_more_data[$key]->over_paid = $total_over_paid;
                                            
                                                  DB::table('rooms')->where('id',$request_data->room_id)->update([
                                                        'more_room_type_details' => $rooms_more_data,
                                                  ]);
                                                    
                                                    
                                                    
                                                    
                                                    
                                                   
                                                    
                                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->select('id','wallat_balance')->first();
                                                    $supplier_wallet_am = $supplier_data->wallat_balance + $over_paid_am;
                                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['wallat_balance'=>$supplier_wallet_am]);
                                                    
                                                    
                                                    if($over_paid_am != 0){ 
                                                           DB::table('hotel_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                                            'payment_am'=>$request_data->amount_paid,
                                                                                                            'balance'=>$supplier_wallet_am,
                                                                                                            'room_id'=>$request_data->room_id,
                                                                                                            'room_generted_id'=>$request_data->room_id_generate,
                                                                                                            'supplier_id'=>$request_data->supplier_id,
                                                                                                            'date'=>$request_data->date,
                                                                                                            'pay_method'=>$request_data->payment_method,
                                                                                                         ]);
                                                    }
                                                 
                                                    
                                                    if($request_data->payment_method == 'Wallet'){
                                                        $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->select('id','wallat_balance')->first();
                                                        $supplier_wallet_am = $supplier_data->wallat_balance - $request_data->amount_paid;
                                                        DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->update(['wallat_balance'=>$supplier_wallet_am]);
                                                        
                                                        DB::table('hotel_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                                        'payment_am'=>$request_data->amount_paid,
                                                                                                        'balance'=>$supplier_wallet_am,
                                                                                                        'room_id'=>$request_data->room_id,
                                                                                                        'room_generted_id'=>$request_data->room_id_generate,
                                                                                                        'supplier_id'=>$request_data->supplier_id,
                                                                                                        'date'=>$request_data->date,
                                                                                                        'pay_method'=>$request_data->payment_method,
                                                                                                     ]);
                                                    }
                                                    
                                                    DB::table('hotel_supplier_payments')->insert(['supplier_id'=>$request_data->supplier_id,
                                                                                                    'over_paid_am'=>$over_paid_am,
                                                                                                    'room_id'=>$request_data->room_id,
                                                                                                    'room_generted_id'=>$request_data->room_id_generate,
                                                                                                    'payment_amount'=>$request_data->amount_paid,
                                                                                                    'date'=>$request_data->date,
                                                                                                     ]);
                                            
                                            
                                            // echo "total booked rooms is $total_booked";
                                        }
                                    }
                                    
                               
                                }else{
                                     $paid_amount = $room_data->paid_amount;
                                    $over_paid_amount = $room_data->over_paid;
                                    
                                    $total_paid_amount = $paid_amount + $request_data->amount_paid;
                                    $total_over_paid = 0;
                                    $over_paid_am = 0;
                                    if($total_paid_amount > $total_amount){
                                        $over_paid_am = $total_paid_amount - $total_amount;
                                        $total_over_paid = $over_paid_amount + $over_paid_am;
                                        
                                        $total_paid_amount = $total_paid_amount - $over_paid_am;
                                    }
                                    
                                    DB::table('rooms')->where('id',$request_data->room_id)->update([
                                        'paid_amount' => $total_paid_amount,
                                        'over_paid' => $total_over_paid,
                                    ]);
                                    
                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->select('id','wallat_balance')->first();
                                    $supplier_wallet_am = $supplier_data->wallat_balance + $over_paid_am;
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['wallat_balance'=>$supplier_wallet_am]);
                                    
                                    
                                    if($over_paid_am != 0){
                                           DB::table('hotel_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                            'payment_am'=>$request_data->amount_paid,
                                                                                            'balance'=>$supplier_wallet_am,
                                                                                            'room_id'=>$request_data->room_id,
                                                                                            'supplier_id'=>$request_data->supplier_id,
                                                                                            'date'=>$request_data->date,
                                                                                            'pay_method'=>$request_data->payment_method,
                                                                                         ]);
                                    }
                                 
                                    
                                    if($request_data->payment_method == 'Wallet'){
                                        $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->select('id','wallat_balance')->first();
                                        $supplier_wallet_am = $supplier_data->wallat_balance - $request_data->amount_paid;
                                        DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->update(['wallat_balance'=>$supplier_wallet_am]);
                                        
                                        DB::table('hotel_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                        'payment_am'=>$request_data->amount_paid,
                                                                                        'balance'=>$supplier_wallet_am,
                                                                                        'room_id'=>$request_data->room_id,
                                                                                        'supplier_id'=>$request_data->supplier_id,
                                                                                        'date'=>$request_data->date,
                                                                                        'pay_method'=>$request_data->payment_method,
                                                                                     ]);
                                    }
                                    
                                    DB::table('hotel_supplier_payments')->insert(['supplier_id'=>$request_data->supplier_id,
                                                                                    'over_paid_am'=>$over_paid_am,
                                                                                    'room_id'=>$request_data->room_id,
                                                                                    'payment_amount'=>$request_data->amount_paid,
                                                                                    'date'=>$request_data->date,
                                                                                     ]);
                                }
                                
                               
                                
                    
          
                            DB::commit();
                            
                            return response()->json(['status'=>'success','message'=>'Balance is Updated Successfully']);
                        } catch (\Exception $e) {
                            DB::rollback();
                            echo $e;die;
                            return response()->json(['status'=>'error','message'=>'Something Went Wrong try Again']);
                            // something went wrong
                        }
                        
   }
}
