<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\addManageQuotation;
use App\Models\AccountDetailsApi;
use App\Models\viewBooking;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\country;
use App\Models\visa_Pay;
use App\Models\Transportation_Pay;
use App\Models\Hotel_Booking_Reservation_Details;
use App\Models\flight_Pay;
use App\Models\accomodation_Pay;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use DateTime;
use Carbon\Carbon;
use DB;

class AccountDetailsApiController extends Controller
{
/*
|--------------------------------------------------------------------------
| AccountDetailsApiController Function Started
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Income Statement Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Income Statement list For Our Client.
*/  
public function income_statement(Request $req){
       
        $data1 = DB::table('view_booking_payment_recieve')
                ->join('tours_bookings','view_booking_payment_recieve.package_id','tours_bookings.id')
                ->join('cart_details','tours_bookings.invoice_no','cart_details.invoice_no')
                ->where('tours_bookings.customer_id',$req->customer_id)->where('cart_details.pakage_type','tour')->get();
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Income Statement Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Income Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Expense Income list For Our Client.
*/ 
public function expenses_IncomeAll(Request $req){
        DB::beginTransaction();
        try {   
            $data1 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$req->customer_id)->get();
            return response()->json([
                'data'  => $data1,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
    }
/*
|--------------------------------------------------------------------------
| Expense Income Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Income Client Wise data Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Expense Income Client Wise data list.
*/ 
public function expenses_Income_client_wise_data(Request $req){
        $data1 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)->get();
        return response()->json([
            'data'  => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Expense Income Client Wise data Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Booking Details Single Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Booking Details Single list.
*/ 
public function booking_details_single(Request $req){
        $data1  = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('tours_bookings.invoice_no',$req->id)->get();
        return response()->json([
            'data'  => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Booking Details Single Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Hotel Name Details Single Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display Hotel Name Details Single list.
*/
public function hotel_Name_Details_Single(Request $req){
        // $data1  = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('tours_bookings.invoice_no',$req->id)->get();
        $data1                  = DB::table('cart_details')
                                    ->join('tours_bookings','cart_details.booking_id','tours_bookings.id')
                                    ->where('cart_details.tour_id',$req->id)
                                    ->where('cart_details.pakage_type','tour')
                                    ->get();
        $reservation_Details    = DB::table('Hotel_Booking_Reservation_Details')
                                    ->where('Hotel_Booking_Reservation_Details.tour_No',$req->id)
                                    ->where('Hotel_Booking_Reservation_Details.hotel_No',$req->hotel_City_Name)
                                    ->get();
        return response()->json([
            'data'                  => $data1,
            'reservation_Details'   => $reservation_Details,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Hotel Name Details Single Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Reservation No Added Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Insert and Update Data From Database. Table Name(Hotel Booking Reservation Details).
*/
public function reservation_no_add(Request $request){
        $reservation_Details = DB::table('Hotel_Booking_Reservation_Details')->where('Hotel_Booking_Reservation_Details.tour_No',$request->tour_No)->get();
        $reservation_Details_C = count($reservation_Details);
        $i = 1;
        foreach($reservation_Details as $value){
            $hotel_No   = $value->hotel_No;
            $invoice_No = $value->invoice_No;
            if($hotel_No == $request->hotel_No && $invoice_No == $request->invoice_No){
                DB::table('Hotel_Booking_Reservation_Details')->where('hotel_No',$request->hotel_No)->where('invoice_No',$request->invoice_No)->update([
                        'reservation_input' => $request->reservation_input,
                    ]);
                return response()->json(['message' => 'Success','reservation_Details' => $reservation_Details,]);
            }else{
                if($i == $reservation_Details_C){
                    $insert = new Hotel_Booking_Reservation_Details();
                    $insert->customer_id        = $request->customer_id;
                    $insert->tour_No            = $request->tour_No;
                    $insert->hotel_No           = $request->hotel_No;
                    $insert->booking_No         = $request->booking_No;
                    $insert->invoice_No         = $request->invoice_No;
                    $insert->reservation_input  = $request->reservation_input;
                    $insert->save();
                    return response()->json(['message' => 'Success','reservation_Details' => $reservation_Details,]);
                }
            }
            $i++;
        }
    }
/*
|--------------------------------------------------------------------------
| Reservation No Added Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Reservation No Update Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Update Data From Database and Display the hotel booking reservation list.Table Name(Hotel Booking Reservation Details).
*/
public function reservation_no_update(Request $request){
        $reservation_No_id      = $request->reservation_No_id;
        $reservation_input      = $request->reservation_input;
        
        $data1                  = DB::table('Hotel_Booking_Reservation_Details')->where('id',$reservation_No_id)
                                    ->update([
                                        'reservation_input' => $reservation_input,
                                    ]);
        $reservation_Details    = DB::table('Hotel_Booking_Reservation_Details')
                                    ->where('Hotel_Booking_Reservation_Details.id',$request->reservation_No_id)
                                    ->get();
        return response()->json(['message' => 'Success','reservation_Details' => $reservation_Details,]);
        
    }
/*
|--------------------------------------------------------------------------
| Reservation No Update Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Reservation Detail Single Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display the Single hotel booking reservation list.Table Name(Hotel Booking Reservation Details).
*/
public function reservation_Detail_Single(Request $request){
        $invoice_no         = $request->invoice_no;
        $hotel_City_Name    = $request->hotel_City_Name;
        $reservation_Details    = DB::table('Hotel_Booking_Reservation_Details')
                                    ->where('Hotel_Booking_Reservation_Details.invoice_No',$invoice_no)
                                    ->where('Hotel_Booking_Reservation_Details.hotel_No',$hotel_City_Name)
                                    ->first();
        return response()->json(['reservation_Details' => $reservation_Details,]);
        
    }
/*
|--------------------------------------------------------------------------
| Reservation Detail Single Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Add More Passenger Package Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Confirm The Package Booking and gather essential customer details.
| Collect and validate customer details provided in the request and Save the confirmed booking and customer information.
*/
public function add_more_passenger_package_booking(Request $req){
    
            if($req->request_form == 'leadPassenger'){
                try {
                    $insert = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$req->booking_Inovice_No)->update([
                        'passenger_detail'    => $req->passenger_detail,
                    ]);
                    $bookings_detaisl = DB::table('tours_bookings')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                        ->where('cart_details.pakage_type','tour')
                                        ->where('tours_bookings.invoice_no',$req->booking_Inovice_No)->first();
                    return response()->json(['status'=>'success','message'=>'Lead Passengers Updated Successfully','bookings_detaisl'=>$bookings_detaisl]);
                } catch (Throwable $e) {
                    echo $e;
                }
            }
            else if($req->request_form == 'AddMPassenger_A'){
                $request_data   = json_decode($req->request_data);
                $adults_detail  = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->select('adults_detail')->first();
                $adults_detail  = $adults_detail->adults_detail;
                // dd($adults_detail);
                $adults_data    =   [
                                        "_token"            => $req->token,
                                        "passengerName"     => $request_data->passengerName,
                                        "lname"             => $request_data->lname,
                                        "gender"            => $request_data->gender,
                                        "date_of_birth"     => $request_data->date_of_birth,
                                        "country"           => $request_data->country,
                                        "passport_lead"     => $request_data->passport_lead,
                                        "passport_exp_lead" => $request_data->passport_exp_lead,
                                        "passengerType"     => $request_data->passengerType,
                                    ];
                                 
                if(isset($adults_detail) && $adults_detail != null && $adults_detail != '' && $adults_detail != 'null'){
                    $adults_detail = json_decode($adults_detail);
                }else{
                    $adults_detail = [];
                }
                // dd($adults_detail);
                array_push($adults_detail,$adults_data);
                $adults_detail = json_encode($adults_detail);
                // dd($adults_detail);
                try {
                    $update = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->update([
                        'adults_detail' => $adults_detail,
                    ]);
                    return response()->json(['status'=>'success','message'=>'Adult Details added Successfully']);
                } catch (Throwable $e) {
                    echo $e;
                }
            }
            else if($req->request_form == 'AddMPassenger_C'){
                $request_data   = json_decode($req->request_data);
                $child_detail  = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->select('child_detail')->first();
                $child_detail  = $child_detail->child_detail;
                $childs_data    =   [
                                        "_token"            => $req->token,
                                        "passengerName"     => $request_data->passengerName,
                                        "lname"             => $request_data->lname,
                                        "gender"            => $request_data->gender,
                                        "date_of_birth"     => $request_data->date_of_birth,
                                        "country"           => $request_data->country,
                                        "passport_lead"     => $request_data->passport_lead,
                                        "passport_exp_lead" => $request_data->passport_exp_lead,
                                        "passengerType"     => $request_data->passengerType,
                                    ];
                                 
                if(isset($child_detail) && $child_detail != null && $child_detail != '' && $child_detail != 'null'){
                    $child_detail = json_decode($child_detail);
                }else{
                    $child_detail = [];
                }
                
                array_push($child_detail,$childs_data);
                $child_detail = json_encode($child_detail);
                
                try {
                    $update = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->update([
                        'child_detail' => $child_detail,
                    ]);
                    return response()->json(['status'=>'success','message'=>'Child Details added Successfully']);
                } catch (Throwable $e) {
                    echo $e;
                }
            }
            else if($req->request_form == 'AddMPassenger_I'){
                $request_data   = json_decode($req->request_data);
                $infant_details  = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->select('infant_details')->first();
                $infant_details  = $infant_details->infant_details;
                $infant_data    =   [
                                        "_token"            => $req->token,
                                        "passengerName"     => $request_data->passengerName,
                                        "lname"             => $request_data->lname,
                                        "gender"            => $request_data->gender,
                                        "date_of_birth"     => $request_data->date_of_birth,
                                        "country"           => $request_data->country,
                                        "passport_lead"     => $request_data->passport_lead,
                                        "passport_exp_lead" => $request_data->passport_exp_lead,
                                        "passengerType"     => $request_data->passengerType,
                                    ];
                                 
                if(isset($infant_details) && $infant_details != null && $infant_details != '' && $infant_details != 'null'){
                    $infant_details = json_decode($infant_details);
                }else{
                    $infant_details = [];
                }
                
                array_push($infant_details,$infant_data);
                $infant_details = json_encode($infant_details);
                
                try {
                    $update = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->update([
                        'infant_details' => $infant_details,
                    ]);
                    return response()->json(['status'=>'success','message'=>'More Passengers of Invoice added Successfully']);
                } catch (Throwable $e) {
                    echo $e;
                }
            }
            else if($req->request_form == 'updateAdultPassenger'){
                $request_data = json_decode($req->request_data);
                $adults_detail  = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->select('adults_detail')->first();
                $adults_detail  = $adults_detail->adults_detail;
                $adults_data    =   [
                                        "_token"            => $req->token,
                                        "passengerName"     => $request_data->passengerName,
                                        "lname"             => $request_data->lname,
                                        "gender"            => $request_data->gender,
                                        "date_of_birth"     => $request_data->date_of_birth,
                                        "country"           => $request_data->country,
                                        "passport_lead"     => $request_data->passport_lead,
                                        "passport_exp_lead" => $request_data->passport_exp_lead,
                                        "passengerType"     => $request_data->passengerType,
                                    ];

                if(!is_null($adults_detail)){
                    $adults_detail = json_decode($adults_detail);
                }else{
                    $adults_detail = [];
                }
                $adults_detail[$request_data->index] = $adults_data;
                $adults_detail = json_encode($adults_detail);
                
                try {
                    $update = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->update([
                        'adults_detail' => $adults_detail,
                    ]);
                    $bookings_detaisl = DB::table('tours_bookings')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                        ->where('cart_details.pakage_type','tour')
                                        ->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->first();
                    return response()->json(['status'=>'success','message'=>'Adult Details Updated Successfully','bookings_detaisl'=>$bookings_detaisl]);
                } catch (Throwable $e) {
                    echo $e;
                }
            }
            else if($req->request_form == 'updateChildPassenger'){
                $request_data   = json_decode($req->request_data);
                // dd($request_data);
                $child_detail   = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->select('child_detail')->first();
                $child_detail   = $child_detail->child_detail;
                // dd($child_detail);
                $child_data    =   [
                                        "_token"            => $req->token,
                                        "passengerName"     => $request_data->passengerName,
                                        "lname"             => $request_data->lname,
                                        "gender"            => $request_data->gender,
                                        "date_of_birth"     => $request_data->date_of_birth,
                                        "country"           => $request_data->country,
                                        "passport_lead"     => $request_data->passport_lead,
                                        "passport_exp_lead" => $request_data->passport_exp_lead,
                                        "passengerType"     => $request_data->passengerType,
                                    ];

                if(!is_null($child_detail)){
                    $child_detail = json_decode($child_detail);
                }else{
                    $child_detail = [];
                }
                // dd($child_detail);
                $child_detail[$request_data->index] = $child_data;
                $child_detail = json_encode($child_detail);
                
                try {
                    $update = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->update([
                        'child_detail' => $child_detail,
                    ]);
                    $bookings_detaisl = DB::table('tours_bookings')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                        ->where('cart_details.pakage_type','tour')
                                        ->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->first();
                    return response()->json(['status'=>'success','message'=>'Child Details Updated Successfully','bookings_detaisl'=>$bookings_detaisl]);
                } catch (Throwable $e) {
                    echo $e;
                }
            }
            else if($req->request_form == 'updateInfantPassenger'){
                $request_data   = json_decode($req->request_data);
                // dd($request_data);
                $infant_details   = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->select('infant_details')->first();
                $infant_details   = $infant_details->infant_details;
                // dd($infant_details);
                $child_data    =   [
                                        "_token"            => $req->token,
                                        "passengerName"     => $request_data->passengerName,
                                        "lname"             => $request_data->lname,
                                        "gender"            => $request_data->gender,
                                        "date_of_birth"     => $request_data->date_of_birth,
                                        "country"           => $request_data->country,
                                        "passport_lead"     => $request_data->passport_lead,
                                        "passport_exp_lead" => $request_data->passport_exp_lead,
                                        "passengerType"     => $request_data->passengerType,
                                    ];

                if(!is_null($infant_details)){
                    $infant_details = json_decode($infant_details);
                }else{
                    $infant_details = [];
                }
                // dd($infant_details);
                $infant_details[$request_data->index] = $child_data;
                $infant_details = json_encode($infant_details);
                
                try {
                    $update = DB::table('tours_bookings')->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->update([
                        'infant_details' => $infant_details,
                    ]);
                    $bookings_detaisl = DB::table('tours_bookings')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                        ->where('cart_details.pakage_type','tour')
                                        ->where('tours_bookings.invoice_no',$request_data->booking_Inovice_No)->first();
                    return response()->json(['status'=>'success','message'=>'Infant Details Updated Successfully','bookings_detaisl'=>$bookings_detaisl]);
                } catch (Throwable $e) {
                    echo $e;
                }
            }
            else{
                return response()->json(['status'=>'error','message'=>'CHECK REQUEST!']);
            }  
    }
/*
|--------------------------------------------------------------------------
| Add More Passenger Package Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Income Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Expense Income List For Our Client.
*/
public function expenses_Income(Request $req){
        DB::beginTransaction();
        try {
            $data1          = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$req->customer_id)->get();
            $data1Decoded   = json_decode($data1);
            $package_Name   = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$req->customer_id)->get();
            DB::commit();
            return response()->json([
                'message'       => 'success',
                'data1'         => $data1Decoded,
                'package_Name'  => $package_Name,
            ]);
        } catch (Throwable $e) {
            echo $e;
        }
    }
/*
|--------------------------------------------------------------------------
| Expense Income Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Out Standing Or Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Out Standing List For Our Client.
*/
public function out_Standings_Or(Request $request){
 
        $data1              = [];
        $booking_all_data   = [];
        $invoices_all_data  = [];
        
        $agent_lists = DB::table('Agents_detail')->get();
            
        $all_agents_data = [];
        foreach($agent_lists as $agent_res){
            $tour_booking_data = DB::table('view_booking_payment_recieve')
                                    ->join('tours_bookings','view_booking_payment_recieve.package_id','tours_bookings.id')
                                    ->join('cart_details','tours_bookings.invoice_no','cart_details.invoice_no')
                                    ->where('tours_bookings.customer_id',$request->customer_id)
                                    ->where('cart_details.pakage_type','tour')
                                    ->where('view_booking_payment_recieve.customer_id',$request->customer_id)
                                    ->select(
                                        'cart_details.tour_id',
                                        'tours_bookings.invoice_no',
                                        'view_booking_payment_recieve.total_amount',
                                        'view_booking_payment_recieve.recieved_amount',
                                        'view_booking_payment_recieve.remaining_amount',
                                        'cart_details.tour_name',
                                        'tours_bookings.passenger_name',
                                        'cart_details.agent_name',
                                        'cart_details.booking_id'
                                    )
                                    ->get();
            
            foreach($tour_booking_data as $tour_res){
                $agent_lists    = DB::table('Agents_detail')->get();
                $agent_name     = $tour_res->agent_name;
                foreach($agent_lists as $agent_res){
                    if($agent_res->id == $tour_res->agent_name){
                        $agent_name = $agent_res->agent_Name;
                    }
                }
                $booking_data = [
                    'tour_id'           => $tour_res->tour_id,
                    'booking_id'        => $tour_res->booking_id,
                    'agent_Id'          => $tour_res->agent_name,
                    'agent_Name'        => $agent_name,
                    'invoice_no'        => $tour_res->invoice_no,
                    'tour_name'         => $tour_res->tour_name,
                    'passenger_name'    => $tour_res->passenger_name,
                    'total_amount'      => $tour_res->total_amount,
                    'recieved_amount'   => $tour_res->recieved_amount,
                    'remaining_amount'  => $tour_res->remaining_amount,
                ];
                array_push($booking_all_data,$booking_data);
            }
            
            $agents_invoice_booking = DB::table('add_manage_invoices')
                                    ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
                                    ->select(
                                        'add_manage_invoices.id',
                                        'add_manage_invoices.services',
                                        'add_manage_invoices.generate_id',   
                                        'add_manage_invoices.agent_Name',
                                        'add_manage_invoices.f_name',
                                        'add_manage_invoices.middle_name',
                                        'add_manage_invoices.agent_Id',
                                        'pay_Invoice_Agent.total_Amount',
                                        'pay_Invoice_Agent.amount_Paid'
                                    )
                                    ->get();
    
            foreach($agents_invoice_booking as $agent_inv_res){
                
                $service_type = '';
                $services = json_decode($agent_inv_res->services);
                if($services[0] == 'accomodation_tab'){
                    $service_type = 'Hotel';
                }else if($services[0] == 'flight_tab'){
                    $service_type = 'Flight';
                }else if($services[0] == 'visa_tab'){
                    $service_type = 'Visa';
                }else if($services[0] == 'transportation_tab'){
                    $service_type = 'Transfer';
                }else{
                    $service_type = 'Not Selected';
                }
                
                $inv_single_data = [
                    'id'                => $agent_inv_res->id,
                    'agent_Id'          => $agent_inv_res->agent_Id,
                    'agent_name'        => $agent_inv_res->agent_Name,
                    'service_type'      => $service_type,
                    'invoice_id'        => $agent_inv_res->generate_id,
                    'total_Amount'      => $agent_inv_res->total_Amount,
                    'amount_Paid'       => $agent_inv_res->amount_Paid,
                    'remaing_amount'    => $agent_inv_res->total_Amount - $agent_inv_res->amount_Paid,
                ];
                array_push($invoices_all_data,$inv_single_data);
            }
        }
        
        $agent_data = [
            'agents_tour_booking'       => $booking_all_data,
            'agents_invoices_booking'   => $invoices_all_data,
        ];
        array_push($data1,$agent_data);       
        
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Out Standing Or Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Out Standings Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Out Standings List.
*/
public function out_Standings(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $all_agents     = DB::table('Agents_detail')->where('customer_id',$userData->id)->get();
            $all_customers  = DB::table('booking_customers')->where('customer_id',$userData->id)->get();
            
            return response()->json([
                 'error' => false,
                 'data' => [
                        'customers' => $all_customers,
                        'agents' => $all_agents,
                     ]
                ]);
        }
    }
    
    public function supplier_out_Standings(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $hotelSuppliers     = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->select('id','room_supplier_name','contact_person_name')->get();
            $flightSuppliers     = DB::table('supplier')->where('customer_id',$userData->id)->select('id','companyname','contactpersonname')->get();
            $transferSuppliers     = DB::table('transfer_Invoice_Supplier')->where('customer_id',$userData->id)->select('id','room_supplier_name','contact_person_name')->get();
            $visaSuppliers     = DB::table('visa_Sup')->where('customer_id',$userData->id)->select('id','visa_supplier_name','contact_person_name')->get();

            return response()->json([
                 'error' => false,
                 'data' => [
                        'hotelSupplier' => $hotelSuppliers,
                        'flightSuppliers' => $flightSuppliers,
                        'transferSuppliers' => $transferSuppliers,
                        'visaSuppliers' => $visaSuppliers
                     ]
                ]);
        }
    }
    
    public function get_out_Standings(Request $request){

        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

        if($userData){
            $requestData = json_decode($request->request_data);
            
            $startDate = '';
            $endDate = '';
                if($requestData->report_type !== 'all_data'){
                     $dates = $this->getStartAndEndDate($requestData->report_type,$requestData);
                      $startDate = $dates['start_date'];
                      $endDate = $dates['end_date'];
                }
                
            
                
                
                $invoices_query                 = DB::table('add_manage_invoices');
                $invoices_query->where('customer_id',$userData->id);
                $invoice_select_common_element  = ['id','services','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC','total_cost_price_AC'];
                $agent_basic_attributes = ['id','agent_Id','agent_Name','agent_Company_Name'];
                $customer_basic_attributes = ['id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name'];
                
                if($requestData->person_report_type == 'All'){
                    $invoices_query->select(...$agent_basic_attributes,...$customer_basic_attributes,...$invoice_select_common_element);
                    $invoices_data = $invoices_query->get();
                }else{
                    if($requestData->person_report_type == 'AgentWise'){
                        if($requestData->agent_Id == 'all_agent'){
                            $invoices_query->where('agent_Id','>',0);
                            $invoices_query->select('id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
                            $invoices_data = $invoices_query->get();
                        }else{
                            $invoices_query->where('agent_Id',$requestData->agent_Id);
                            $invoices_query->select('id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
                            $invoices_data = $invoices_query->get();
                        }
                    }else{
                         if($requestData->customer_Id == 'all_customer'){
                            $invoices_query->where('booking_customer_id','>',0);
                            $invoices_query->select('id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
                            $invoices_data = $invoices_query->get();
                        }else{
                            $invoices_query->where('booking_customer_id',$requestData->customer_Id);
                            $invoices_query->select('id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
                            $invoices_data = $invoices_query->get();
                        }
                    }
                }
                
                
                //  filter Date Wise = [];
                
                $dateWiseInvoices = [];
                if(!empty($startDate)){
                    foreach($invoices_data as $inv_res){
                        $accomodation_details   = json_decode($inv_res->accomodation_details);
                        $services               = json_decode($inv_res->services);
                        $check_in               = '';
                        if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                            $check_in = $accomodation_details[0]->acc_check_in;
                            $inv_res->check_in = $check_in;
                            $dateWiseInvoices[] = $inv_res;
                        }
                        
                        if(isset($services[0])){
                            if($services[0] == 'visa_tab' && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                                $inv_res->check_in = $inv_res->created_at;
                                $dateWiseInvoices[] = $inv_res;
                            }
                        }
                        
                        if(isset($services[0])){
                            if($services[0] == 'transportation_tab'  && $inv_res->created_at >= $startDate && $inv_res->created_at <= $endDate){
                                $inv_res->check_in = $inv_res->created_at;
                                $dateWiseInvoices[] = $inv_res;
                            }
                        }
                    }
                }else{
                    $dateWiseInvoices = $invoices_data;
                }
                
                

                // Grouping logic using Laravel Collection
                $agentInvoices = [];
                $customrInvoices = [];
                foreach($dateWiseInvoices as $invoice){
                    if (isset($invoice->agent_Id)) {
                        $agentInvoices[] = [
                                'agent_id' => $invoice->agent_Id,
                                'type' => 'Invoices',
                                'price' => $invoice->total_sale_price_AC,
                                'cost_price' => $invoice->total_cost_price_AC,
                                'profit' => (float)$invoice->total_sale_price_AC - (float)$invoice->total_cost_price_AC
                            ];
                    } else {
                        $customrInvoices[] = [
                                'customer_id' => $invoice->booking_customer_id,
                                'price' => $invoice->total_sale_price_AC,
                                'type' => 'Invoices',
                                'cost_price' => $invoice->total_cost_price_AC,
                                'profit' => (float)$invoice->total_sale_price_AC - (float)$invoice->total_cost_price_AC
                            ];
                    }
                }
                
                
                
                // dd($agentInvoices);
                
                
                
                $invoices_query                 = DB::table('add_manage_invoices');
                $invoices_query->where('customer_id',$userData->id);
                $invoice_select_common_element  = ['id','services','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_AC','total_cost_price_AC'];
                $agent_basic_attributes = ['id','agent_Id','agent_Name','agent_Company_Name'];
                $customer_basic_attributes = ['id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name'];
                
                if($requestData->person_report_type == 'All'){
                    $invoices_query->select(...$agent_basic_attributes,...$customer_basic_attributes,...$invoice_select_common_element);
                    $invoices_data = $invoices_query->get();
                }else{
                    if($requestData->person_report_type == 'AgentWise'){
                        if($requestData->agent_Id == 'all_agent'){
                            $invoices_query->where('agent_Id','>',0);
                            $invoices_query->select('id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
                            $invoices_data = $invoices_query->get();
                        }else{
                            $invoices_query->where('agent_Id',$requestData->agent_Id);
                            $invoices_query->select('id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
                            $invoices_data = $invoices_query->get();
                        }
                    }else{
                         if($requestData->customer_Id == 'all_customer'){
                            $invoices_query->where('booking_customer_id','>',0);
                            $invoices_query->select('id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
                            $invoices_data = $invoices_query->get();
                        }else{
                            $invoices_query->where('booking_customer_id',$requestData->customer_Id);
                            $invoices_query->select('id','booking_customer_id','agent_Id','agent_Name','agent_Company_Name',...$invoice_select_common_element);
                            $invoices_data = $invoices_query->get();
                        }
                    }
                }
                
                // $packages_booking_all   = DB::table('cart_details')
                //                         ->join('tours','tours.id','=','cart_details.tour_id')
                //                         ->whereJsonContains('cart_total_data->customer_id',"$requestData->customer_Id")
                //                         ->select('cart_details.id','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                //                              ,'tours.accomodation_details','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date')
                //                         ->orderBy('created_at')
                //                         ->get();
              
              
               
                
                $packages_query = DB::table('cart_details')
                                        ->join('tours','tours.id','=','cart_details.tour_id');
                $invoice_select_common_element  = ['cart_details.id','cart_details.agent_name','cart_details.currency','cart_details.double_room','cart_details.triple_room','cart_details.quad_room','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.cart_total_data','cart_details.visa_change_data','cart_details.created_at'
                                        ,'tours.accomodation_details','tours.id as tour_id','tours.accomodation_details_more','tours.visa_type','tours.visa_fee','tours.id as package_id','tours.start_date','tours.end_date','tours.customer_id'];
                                $packages_query->where('tours.customer_id',$userData->id);

                if($requestData->person_report_type == 'All'){
                    $packages_query->select(...$invoice_select_common_element);
                    $packages = $packages_query->get();
                }else{
                    if($requestData->person_report_type == 'AgentWise'){
                        if($requestData->agent_Id == 'all_agent'){
                                $packages_query->where('cart_details.agent_name','!=','-1');
                            $packages_query->select(...$invoice_select_common_element);
                            $packages = $packages_query->get();
                        }else{
                            $packages_query->where('cart_details.agent_name',$requestData->agent_Id);
                            $packages_query->select(...$invoice_select_common_element);
                            $packages = $packages_query->get();
                        }
                    }else{
                         if($requestData->customer_Id == 'all_customer'){
                             $packages_query->where('cart_details.agent_name','=',-1);
                             $packages_query->orwhere('cart_details.agent_name','=' ,NULL);
                            $packages_query->select(...$invoice_select_common_element);
                            $packages = $packages_query->get();
                        }else{
                            $packages_query->whereJsonContains('cart_details.cart_total_data->customer_id',$requestData->customer_Id);
                            $packages_query->select(...$invoice_select_common_element);
                            $packages = $packages_query->get();
                        }
                    }
                }
                
                                 
                $packages_bookings   = [];
                if(!empty($startDate)){
                    foreach($packages as $inv_res){
                        
                        if($inv_res->customer_id != $userData->id){
                            continue;
                        }
                        
                        $accomodation_details = json_decode($inv_res->accomodation_details);
                        
                        $check_in = '';
                        if(isset($accomodation_details[0]->acc_check_in) && $accomodation_details[0]->acc_check_in >= $startDate && $accomodation_details[0]->acc_check_in <= $endDate){
                            $check_in = $accomodation_details[0]->acc_check_in;
                            $inv_res->check_in  = $check_in;
                            $packages_bookings[] = $inv_res;
                        }
                        
                    }
                }else{
                    foreach($packages as $inv_res){
                        
                        if($inv_res->customer_id != $userData->id){
                            continue;
                        }
                        
                        $packages_bookings[] = $inv_res;
                    }
                    
                }

                $bookingsProfit = [];
                
                foreach($packages_bookings as $packageBooking){
                    
                    $type = 'Package';
                    if($packageBooking->pakage_type == 'tour'){
                        $profit  = $this->getPackageProfit($packageBooking);
                    }else{
                        $type = 'Activity';
                        $profit  = $this->getActivityProfit($packageBooking);
                    }

                    if (isset($packageBooking->agent_name) && $packageBooking->agent_name != '-1') {
                        $agentInvoices[] = [
                                'agent_id' => $packageBooking->agent_name,
                                'price' => $packageBooking->price,
                                'type' => $type,
                                'cost_price' => NULL,
                                'profit' => $profit
                            ];
                    } else {
                        
                        $cart_all_data = json_decode($packageBooking->cart_total_data);
                
                        $customrInvoices[] = [
                                'customer_id' => $cart_all_data->customer_id ?? '',
                                'price' => $packageBooking->price,
                                'type' => $type,
                                'cost_price' => 0,
                                'profit' => $profit
                            ];
                    }
                    
                    
                   $bookingsProfit[] = [
                       'profit' => $profit
                       ];
                }
                
                $agentsRevenueArr = collect($agentInvoices)
                    ->groupBy('agent_id')
                    ->map(function ($items, $agent_id) {
                        return [
                            'agent_id' => $agent_id,
                            'total_price' => $items->sum('price'),
                            'total_profit' => $items->sum('profit'),
                        ];
                    })
                    ->values()
                    ->toArray();
                    
                $customerRevenueArr = collect($customrInvoices)
                    ->groupBy('customer_id')
                    ->map(function ($items, $customer_id) {
                        return [
                            'customer_id' => $customer_id,
                            'total_price' => $items->sum('price'),
                            'total_profit' => $items->sum('profit'),
                        ];
                    })
                    ->values()
                    ->toArray();
                    
                foreach($agentsRevenueArr as $index => $agentsRevenue){
                    if(!empty($startDate)){
                        $payments_data      = DB::table('recevied_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$agentsRevenue['agent_id'])
                                        ->whereDate('payment_date','>=', $startDate)
                                        ->whereDate('payment_date','<=', $endDate)
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
                    }else{
                        $payments_data      = DB::table('recevied_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$agentsRevenue['agent_id'])
                                        ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
                    }
                    
                    if(!empty($startDate)){
                          $make_payments_data = DB::table('make_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$agentsRevenue['agent_id'])
                                        ->whereDate('payment_date','>=', $startDate)
                                        ->whereDate('payment_date','<=', $endDate)
                                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
                    }else{
                        $make_payments_data = DB::table('make_payments_details')
                                        ->where('Criteria','Agent')
                                        ->where('Content_Ids',$agentsRevenue['agent_id'])
                                        ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                        ->orderBy('check_in')
                                        ->get();
                    }
                    
                    
                    $grand_receved_amount = 0;
                    foreach($payments_data as $value){
                        $total_payment_received = 0;
                        $exchange_rate = $value->exchange_rate;
                        $total_puchase_price = 0;
                        $currency = $userData->currency_value;

                            
                            
                             if(isset($value->converion_data)){
                                $conversion_data = json_decode($value->converion_data);

                                if(isset($conversion_data->purchase_currency)){
                                    if($currency == $conversion_data->purchase_currency){
                                        $total_payment_received = $value->purchase_amount;
                                        $total_puchase_price = $value->purchase_amount;
                                    } 
                                    
                                    if($currency == $conversion_data->sale_currency){
                                        $total_payment_received = $value->Amount;
                                        $total_puchase_price = $value->purchase_amount;
                                    }
                                }else{
                                    $total_payment_received = $value->Amount;
                                    $total_puchase_price = $value->Amount;
                                }
                                
                                
                               
                                
                            }else{
                                $total_payment_received = $value->Amount;
                                $total_puchase_price = $value->Amount;
                            }
                            
                            $grand_receved_amount += $total_payment_received;
                    }
                    
                    $totalMakePayments = 0;
                    foreach($make_payments_data as $value){
                            $total_payment_received = 0;
                                                          
                            $exchange_rate = $value->exchange_rate;
                            $total_puchase_price = 0;
                            $currency = $userData->currency_value;
                          
                            
                            
                             if(isset($value->converion_data)){
                                $conversion_data = json_decode($value->converion_data);

                                if(isset($conversion_data->purchase_currency)){
                                    if($currency == $conversion_data->purchase_currency){
                                        $total_payment_received = $value->purchase_amount;
                                        $total_puchase_price = $value->purchase_amount;
                                    } 
                                    
                                    if($currency == $conversion_data->sale_currency){
                                        $total_payment_received = $value->Amount;
                                        $total_puchase_price = $value->purchase_amount;
                                    }
                                }else{
                                    $total_payment_received = $value->Amount;
                                    $total_puchase_price = $value->Amount;
                                }
                                
                                
                               
                                
                            }else{
                                $total_payment_received = $value->Amount;
                                $total_puchase_price = $value->Amount;
                            }
                                                                    
                                                                   
                                                                
                                                                
                                                            
                        $totalMakePayments += $total_payment_received;
                    }
                    
                    
                    $agentsRevenueArr[$index]['total_price'] = $agentsRevenue['total_price'] + $totalMakePayments;                            
                    $agentsRevenueArr[$index]['total_payment_received'] = $grand_receved_amount;
                    $agentsRevenueArr[$index]['oudstanging'] = ($agentsRevenue['total_price'] + $totalMakePayments) - $grand_receved_amount;
                    
                    $agent = DB::table('Agents_detail')->find($agentsRevenue['agent_id']);
                    $agentsRevenueArr[$index]['agent_details'] = $agent;
                }
                
               
               foreach($customerRevenueArr as $index => $customerRevenue){
                    if(!empty($startDate)){
                            $payments_data = DB::table('recevied_payments_details')
                                                ->where('Criteria','Customer')
                                                ->where('Content_Ids',$customerRevenue['customer_id'])
                                                ->whereDate('payment_date','>=', $startDate)
                                                ->whereDate('payment_date','<=', $endDate)
                                                ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                                ->orderBy('check_in')
                                                ->get();
                    }else{
                        $payments_data = DB::table('recevied_payments_details')
                                            ->where('Criteria','Customer')
                                            ->where('Content_Ids',$customerRevenue['customer_id'])
                                            ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                            ->orderBy('check_in')
                                            ->get();
                    }
                    
                    if(!empty($startDate)){
                               $make_payments_data = DB::table('make_payments_details')
                                    ->where('Criteria','Customer')
                                    ->where('Content_Ids',$customerRevenue['customer_id'])
                                    ->whereDate('payment_date','>=', $startDate)
                                    ->whereDate('payment_date','<=', $endDate)
                                    ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                    ->orderBy('check_in')
                                    ->get();
                    }else{
                        $make_payments_data = DB::table('make_payments_details')
                                    ->where('Criteria','Customer')
                                    ->where('Content_Ids',$customerRevenue['customer_id'])
                                    ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as check_in','purchase_amount','converion_data',"exchange_rate")
                                    ->orderBy('check_in')
                                    ->get();
                    }
                    
                    
                    
                    $grand_receved_amount = 0;
                    foreach($payments_data as $value){

                              $total_payment_received = 0;
                                                       
                                                            
                            $exchange_rate = $value->exchange_rate;
                            $total_puchase_price = 0;
                            $currency = $userData->currency_value;;
                                  
                                    
                                    
                                     if(isset($value->converion_data)){
                                        $conversion_data = json_decode($value->converion_data);

                                        if(isset($conversion_data->purchase_currency)){
                                            if($currency == $conversion_data->purchase_currency){
                                                $total_payment_received = $value->purchase_amount;
                                                $total_puchase_price = $value->purchase_amount;
                                            } 
                                            
                                            if($currency == $conversion_data->sale_currency){
                                                $total_payment_received = $value->Amount;
                                                $total_puchase_price = $value->purchase_amount;
                                            }
                                        }else{
                                            $total_payment_received = $value->Amount;
                                            $total_puchase_price = $value->Amount;
                                        }
                                        
                                        
                                       
                                        
                                    }else{
                                        $total_payment_received = $value->Amount;
                                        $total_puchase_price = $value->Amount;
                                    }
                            
                            $grand_receved_amount += $total_payment_received;
                    }
                    
                    $customerTotalMakePayments = 0;
                    foreach($make_payments_data as $value){
                            $total_payment_received = 0;
                                                    
                            $exchange_rate = $value->exchange_rate;
                            $total_puchase_price = 0;
                            $currency = $userData->currency_value;
                                  
                                    
                                    
                                     if(isset($value->converion_data)){
                                        $conversion_data = json_decode($value->converion_data);

                                        if(isset($conversion_data->purchase_currency)){
                                            if($currency == $conversion_data->purchase_currency){
                                                $total_payment_received = $value->purchase_amount;
                                                $total_puchase_price = $value->purchase_amount;
                                            } 
                                            
                                            if($currency == $conversion_data->sale_currency){
                                                $total_payment_received = $value->Amount;
                                                $total_puchase_price = $value->purchase_amount;
                                            }
                                        }else{
                                            $total_payment_received = $value->Amount;
                                            $total_puchase_price = $value->Amount;
                                        }
                                        
                                        
                                       
                                        
                                    }else{
                                        $total_payment_received = $value->Amount;
                                        $total_puchase_price = $value->Amount;
                                    }
                                    
                          $customerTotalMakePayments += $total_payment_received;
                    }
                    
                    $customerRevenueArr[$index]['total_price'] = $customerRevenue['total_price'] + $customerTotalMakePayments;                            
                    $customerRevenueArr[$index]['total_payment_received'] = $grand_receved_amount;
                    $customerRevenueArr[$index]['oudstanging'] = ($customerRevenue['total_price'] + $customerTotalMakePayments) - $grand_receved_amount;
                    
                    $customer = DB::table('booking_customers')->where('id',$customerRevenue['customer_id'])->first();
                    $customerRevenueArr[$index]['customer_details'] = $customer;
                }
                
                $allData = array_merge($agentsRevenueArr,$customerRevenueArr);
                
                
                return response()->json([
                        'error' => false,
                        'data' => [
                                'outstanding_data' => $allData,
                                'start_date' => $startDate,
                                'end_date' => $endDate
                            ]
                    ]);
        }
        
        return response()->json([
                        'error' => true,
                        'data' => []
                    ]);
    }
    
    public function supplier_out_Standings_ajax(Request $request){

        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $requestData = json_decode($request->request_data);
            $startDate = '';
            $endDate = '';
                if($requestData->report_type !== 'all_data'){
                     $dates = $this->getStartAndEndDate($requestData->report_type,$requestData);
                      $startDate = $dates['start_date'];
                      $endDate = $dates['end_date'];
                }
                
                $hotelSuppliersPayable = [];
                $flightSuppliersPayable = [];
                $transferSuppliersPayable = [];
                $visaSuppliersPayable = [];
                
                if($requestData->person_report_type == 'All'){
                    
                    // Get Hotel Supplier
                    
                    $hotelSuppliers = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->get();
                    
                    foreach($hotelSuppliers as $hotelSupplier){
                        $getHotelSupplierStatementData = app(\App\Actions\GetHotelSupplierStatementData::class);
                        $revenueDetails = $getHotelSupplierStatementData->execute($userData->id,$userData->currency,$hotelSupplier->id,$startDate,$endDate);
                        
                        
                        $hotelSuppliersPayable[] = [
                                'id' => $hotelSupplier->id,
                                'type' => 'hotelSupplier',
                                'currency' => $hotelSupplier->currrency,
                                'supplierName' => $hotelSupplier->room_supplier_name,
                                'totalRevenue' => $revenueDetails['totalRevenue'],
                                'totalPaid' => $revenueDetails['totalPaid'],
                                'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                            ];
                   
                    }
                    
                    
                    // Get Flight Supplier
                    
                    $flightSuppliers = DB::table('supplier')->where('customer_id',$userData->id)->get();
                    
                    foreach($flightSuppliers as $flightSupplier){
                        $getFlightSupplierStatementData = app(\App\Actions\GetFlightSupplierStatementData::class);
                        $revenueDetails = $getFlightSupplierStatementData->execute($userData->id,$userData->currency,$flightSupplier->id,$startDate,$endDate);
                        
                    
                        
                        $flightSuppliersPayable[] = [
                                'id' => $flightSupplier->id,
                                'type' => 'flightSupplier',
                                'currency' => $flightSupplier->currency,
                                'supplierName' => $flightSupplier->companyname,
                                'contactPersonName' => $flightSupplier->contactpersonname,
                                'totalRevenue' => $revenueDetails['totalRevenue'],
                                'totalPaid' => $revenueDetails['totalPaid'],
                                'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                            ];
                   
                    }
                    

                    // Get Visa Suppliers
                    
                    $VisaSuppliers = DB::table('visa_Sup')->where('customer_id',$userData->id)->get();
                    
                    foreach($VisaSuppliers as $VisaSupplier){
                        $getVisaSupplierStatementData = app(\App\Actions\GetVisaSupplierStatementData::class);
                        $revenueDetails = $getVisaSupplierStatementData->execute($userData->id,$userData->currency,$VisaSupplier->id,$startDate,$endDate);
                        
                    
                        
                        $visaSuppliersPayable[] = [
                                'id' => $VisaSupplier->id,
                                'supplierName' => $VisaSupplier->visa_supplier_name,
                                'currency' => $VisaSupplier->currency,
                                'type' => 'visaSupplier',
                                'contactPersonName' => $VisaSupplier->contact_person_name,
                                'totalRevenue' => $revenueDetails['totalRevenue'],
                                'totalPaid' => $revenueDetails['totalPaid'],
                                'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                            ];
                   
                    }
                    
                    
                    // Get Transfer Supplier
                    $transferSuppliers     = DB::table('transfer_Invoice_Supplier')->where('customer_id',$userData->id)->get();
                    
                    foreach($transferSuppliers as $transferSupplier){
                       $getTransferSupplierStatementData = app(\App\Actions\GetTransferSupplierStatementData::class);
                        $revenueDetails = $getTransferSupplierStatementData->execute($userData->id,$userData->currency,$transferSupplier->id,$startDate,$endDate);

                        $transferSuppliersPayable[] = [
                            'id' => $transferSupplier->id,
                            'supplierName' => $transferSupplier->room_supplier_name,
                            'currency' => $transferSupplier->currency,
                            'type' => 'transferSupplier',
                            'contactPersonName' => $transferSupplier->contact_person_name,
                            'totalRevenue' => $revenueDetails['totalRevenue'],
                            'totalPaid' => $revenueDetails['totalPaid'],
                            'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                        ];
                           
                    }
                    
                    
                    
                    
                }else{
                    if($requestData->person_report_type == 'HotelSupplier'){
                        // hotel_supplier_id
                        
                        if($requestData->hotel_supplier_id == 'all_suppliers'){
                             $hotelSuppliers = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->get();
                    
                            
                            foreach($hotelSuppliers as $hotelSupplier){
                                $getHotelSupplierStatementData = app(\App\Actions\GetHotelSupplierStatementData::class);
                                $revenueDetails = $getHotelSupplierStatementData->execute($userData->id,$userData->currency,$hotelSupplier->id,$startDate,$endDate);
                                
                                
                                $hotelSuppliersPayable[] = [
                                        'id' => $hotelSupplier->id,
                                        'supplierName' => $hotelSupplier->room_supplier_name,
                                        'type' => 'hotelSupplier',
                                        'currency' => $hotelSupplier->currrency,
                                        'totalRevenue' => $revenueDetails['totalRevenue'],
                                        'totalPaid' => $revenueDetails['totalPaid'],
                                        'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                                    ];
                           
                            }
                            
                        }else{
                            $hotelSupplier = DB::table('rooms_Invoice_Supplier')->where('id',$requestData->hotel_supplier_id)->first();
                    
                            $getHotelSupplierStatementData = app(\App\Actions\GetHotelSupplierStatementData::class);
                            $revenueDetails = $getHotelSupplierStatementData->execute($userData->id,$userData->currency,$requestData->hotel_supplier_id,$startDate,$endDate);
                            
                            
                            $hotelSuppliersPayable[] = [
                                    'id' => $hotelSupplier->id,
                                    'supplierName' => $hotelSupplier->room_supplier_name,
                                    'type' => 'hotelSupplier',
                                    'currency' => $hotelSupplier->currrency,
                                    'totalRevenue' => $revenueDetails['totalRevenue'],
                                    'totalPaid' => $revenueDetails['totalPaid'],
                                    'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                                ];
                        }
                        
                        
                    }
                    
                    if($requestData->person_report_type == 'FlightSupplier'){
                        // flight_supplier_id
                        
                        if($requestData->flight_supplier_id == 'all_suppliers'){
                            $flightSuppliers = DB::table('supplier')->where('customer_id',$userData->id)->get();
                    
                                foreach($flightSuppliers as $flightSupplier){
                                    $getFlightSupplierStatementData = app(\App\Actions\GetFlightSupplierStatementData::class);
                                    $revenueDetails = $getFlightSupplierStatementData->execute($userData->id,$userData->currency,$flightSupplier->id,$startDate,$endDate);
                                    
                                
                                    
                                    $flightSuppliersPayable[] = [
                                            'id' => $flightSupplier->id,
                                            'supplierName' => $flightSupplier->companyname,
                                            'currency' => $flightSupplier->currency,
                                            'type' => 'flightSupplier',
                                            'contactPersonName' => $flightSupplier->contactpersonname,
                                            'totalRevenue' => $revenueDetails['totalRevenue'],
                                            'totalPaid' => $revenueDetails['totalPaid'],
                                            'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                                        ];
                               
                                }
                        }else{
                            
                            $getFlightSupplierStatementData = app(\App\Actions\GetFlightSupplierStatementData::class);
                            $revenueDetails = $getFlightSupplierStatementData->execute($userData->id,$userData->currency,$requestData->flight_supplier_id,$startDate,$endDate);
                            $flightSupplier     = DB::table('supplier')->where('id',$requestData->flight_supplier_id)->first();
                            
                            $flightSuppliersPayable[] = [
                                'id' => $flightSupplier->id,
                                'supplierName' => $flightSupplier->companyname,
                                'currency' => $flightSupplier->currency,
                                'type' => 'flightSupplier',
                                'contactPersonName' => $flightSupplier->contactpersonname,
                                'totalRevenue' => $revenueDetails['totalRevenue'],
                                'totalPaid' => $revenueDetails['totalPaid'],
                                'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                            ];
                          
                        }
                        
                    }
                    
                    if($requestData->person_report_type == 'VisaSupplier'){
                    //  visa_supplier_id   
                    
                        if($requestData->visa_supplier_id == 'all_suppliers'){
                            $VisaSuppliers = DB::table('visa_Sup')->where('customer_id',$userData->id)->get();
                    
                                foreach($VisaSuppliers as $VisaSupplier){
                                    $getVisaSupplierStatementData = app(\App\Actions\GetVisaSupplierStatementData::class);
                                    $revenueDetails = $getVisaSupplierStatementData->execute($userData->id,$userData->currency,$VisaSupplier->id,$startDate,$endDate);
                                    
                                
                                    
                                    $visaSuppliersPayable[] = [
                                            'id' => $VisaSupplier->id,
                                            'supplierName' => $VisaSupplier->visa_supplier_name,
                                            'currency' => $VisaSupplier->currency,
                                            'type' => 'visaSupplier',
                                            'contactPersonName' => $VisaSupplier->contact_person_name,
                                            'totalRevenue' => $revenueDetails['totalRevenue'],
                                            'totalPaid' => $revenueDetails['totalPaid'],
                                            'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                                        ];
                               
                                }
                        }else{
                            
                            $getVisaSupplierStatementData = app(\App\Actions\GetVisaSupplierStatementData::class);
                            $revenueDetails = $getVisaSupplierStatementData->execute($userData->id,$userData->currency,$requestData->visa_supplier_id,$startDate,$endDate);

                            $VisaSupplier     = DB::table('visa_Sup')->where('id',$requestData->visa_supplier_id)->first();
                            
                            $visaSuppliersPayable[] = [
                                'id' => $VisaSupplier->id,
                                'supplierName' => $VisaSupplier->visa_supplier_name,
                                'currency' => $VisaSupplier->currency,
                                'type' => 'visaSupplier',
                                'contactPersonName' => $VisaSupplier->contact_person_name,
                                'totalRevenue' => $revenueDetails['totalRevenue'],
                                'totalPaid' => $revenueDetails['totalPaid'],
                                'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                            ];
                          
                        }
                    
                    }
                    
                    if($requestData->person_report_type == 'TransferSupplier'){
                        
                        // transfer_supplier_id
                        
                        if($requestData->transfer_supplier_id == 'all_suppliers'){
                            $transferSuppliers     = DB::table('transfer_Invoice_Supplier')->where('customer_id',$userData->id)->get();
                    
                                foreach($transferSuppliers as $transferSupplier){
                                   $getTransferSupplierStatementData = app(\App\Actions\GetTransferSupplierStatementData::class);
                                    $revenueDetails = $getTransferSupplierStatementData->execute($userData->id,$userData->currency,$transferSupplier->id,$startDate,$endDate);

                                    $transferSuppliersPayable[] = [
                                        'id' => $transferSupplier->id,
                                        'supplierName' => $transferSupplier->room_supplier_name,
                                        'currency' => $transferSupplier->currency,
                                        'type' => 'transferSupplier',
                                        'type' => 'visaSupplier',
                                        'contactPersonName' => $transferSupplier->contact_person_name,
                                        'totalRevenue' => $revenueDetails['totalRevenue'],
                                        'totalPaid' => $revenueDetails['totalPaid'],
                                        'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                                    ];
                                       
                                }
                        }else{
                            
                            $getTransferSupplierStatementData = app(\App\Actions\GetTransferSupplierStatementData::class);
                            $revenueDetails = $getTransferSupplierStatementData->execute($userData->id,$userData->currency,$requestData->transfer_supplier_id,$startDate,$endDate);

                            $transferSupplier     = DB::table('transfer_Invoice_Supplier')->where('id',$requestData->transfer_supplier_id)->first();
                            
                            $transferSuppliersPayable[] = [
                                'id' => $transferSupplier->id,
                                'supplierName' => $transferSupplier->room_supplier_name,
                                'currency' => $transferSupplier->currency,
                                'type' => 'transferSupplier',
                                'contactPersonName' => $transferSupplier->contact_person_name,
                                'totalRevenue' => $revenueDetails['totalRevenue'],
                                'totalPaid' => $revenueDetails['totalPaid'],
                                'payable' => $revenueDetails['totalRevenue'] - $revenueDetails['totalPaid']
                            ];
                          
                        }
                    }
                }
            
                // dd($hotelSuppliersPayable,$flightSuppliersPayable,$transferSuppliersPayable,$visaSuppliersPayable);
                
              
              
                
           
             
                $allData = array_merge($hotelSuppliersPayable,$flightSuppliersPayable,$transferSuppliersPayable,$visaSuppliersPayable);

                return response()->json([
                        'error' => false,
                        'data' => [
                                'outstanding_data' => $allData,
                                'start_date' => $startDate,
                                'end_date' => $endDate
                            ]
                    ]);
        }
        
        return response()->json([
                        'error' => true,
                        'data' => []
                    ]);
    }
    
    
    
    public function getPackageProfit($tour_res){

                $tours_costing      = DB::table('tours_2')
                                        ->join('tours','tours_2.tour_id','tours.id')
                                        ->where('tours_2.tour_id',$tour_res->tour_id)
                                        ->select('tours.created_at','tours.title','tours_2.quad_cost_price','tours_2.triple_cost_price','tours_2.double_cost_price','tours_2.without_acc_cost_price','tours_2.child_grand_total_cost_price','tours_2.infant_total_cost_price')->first();
                
                
                $cart_all_data = json_decode($tour_res->cart_total_data);
                
                $grand_profit = 0;
                $grand_cost = 0;
                $grand_sale = 0;
             
                // Profit From Double Adults
                if($cart_all_data->double_adults > 0){
                    $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                    $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    $grand_profit += $double_profit;
                    $grand_cost += $double_adult_total_cost;
                    $grand_sale += $cart_all_data->double_adult_total;
                }
                 
                // Profit From Triple Adults
                if($cart_all_data->triple_adults > 0){
                    $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                    $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    $grand_profit += $triple_profit;
                    $grand_cost += $triple_adult_total_cost;
                    $grand_sale += $cart_all_data->triple_adult_total;
                }
                    
                // Profit From Quad Adults
                if($cart_all_data->quad_adults > 0){
                    $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                    $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    $grand_profit += $quad_profit;
                    $grand_cost += $quad_adult_total_cost;
                    $grand_sale += $cart_all_data->quad_adult_total;
                }
                 
                // Profit From Without Acc
                if($cart_all_data->without_acc_adults > 0){
                    $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                    $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    $grand_profit += $without_acc_profit;
                    $grand_cost += $without_acc_adult_total_cost;
                    $grand_sale += $cart_all_data->without_acc_adult_total;
                }
                 
                // Profit From Double Childs
                if($cart_all_data->double_childs > 0){
                    $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                    $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    $grand_profit += $double_profit;
                    $grand_cost += $double_child_total_cost;
                    $grand_sale += $cart_all_data->double_childs_total;
                }
                 
                // Profit From Triple Childs
                if($cart_all_data->triple_childs > 0){
                    $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                    $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    $grand_profit += $triple_profit;
                    $grand_cost += $triple_child_total_cost;
                    $grand_sale += $cart_all_data->triple_childs_total;
                }
                    
                // Profit From Quad Childs
                if($cart_all_data->quad_childs > 0){
                    $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                    $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    $grand_profit += $quad_profit;
                    $grand_cost += $quad_child_total_cost;
                    $grand_sale += $cart_all_data->quad_child_total;
                }
                 
                // Profit From Without Acc Child
                if($cart_all_data->children > 0){
                    $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                    $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    $grand_profit += $without_acc_profit;
                    $grand_cost += $without_acc_child_total_cost;
                    $grand_sale += $cart_all_data->without_acc_child_total;
                }

                // Profit From Double Infant
                if($cart_all_data->double_infant > 0){
                    $double_infant_total_cost   = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                    $double_profit              = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_infant_total_cost;
                    $grand_sale                 += $cart_all_data->double_infant_total;
                }
                 
                // Profit From Triple Infant
                if($cart_all_data->triple_infant > 0){
                    $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                    $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    $grand_profit += $triple_profit;
                    $grand_cost += $triple_infant_total_cost;
                    $grand_sale += $cart_all_data->triple_infant_total;
                }
                 
                // Profit From Quad Infant
                if($cart_all_data->quad_infant > 0){
                    $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                    $quad_profit            = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    $grand_profit           += $quad_profit;
                    $grand_cost            += $quad_infant_total_cost;
                    $grand_sale             += $cart_all_data->quad_infant_total;
                }
                 
                // Profit From Without Acc Infant  
                if($cart_all_data->infants > 0){
                    $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                    $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    $grand_profit += $without_acc_profit;
                    $grand_cost += $without_acc_infant_total_cost;
                    $grand_sale += $cart_all_data->without_acc_infant_total;
                }
                  
                $over_all_dis = 0;
                
                if($cart_all_data->discount_type == 'amount'){
                    $final_profit   = $grand_profit - $cart_all_data->discount_enter_am;
                    $grand_sale     = $grand_sale - $cart_all_data->discount_enter_am;
                }else{
                   $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                   $final_profit = $grand_profit - $discunt_am_over_all;
                   $grand_sale   = $grand_sale - $discunt_am_over_all;
                }
                
                if(isset($cart_all_data->special_discount)){
                    $final_profit   = $grand_profit - $cart_all_data->special_discount;
                    $grand_sale     = $grand_sale - $cart_all_data->special_discount;
                }
                // else{
                //     $final_profit   = $grand_profit;
                // }
                
                $commission_add = '';
                if(isset($cart_all_data->agent_commsion_add_total)){
                    $commission_add = $cart_all_data->agent_commsion_add_total;
                }
                
               return  $final_profit;
            
    }
    
    public function getActivityProfit($activity_res){

                $tours_costing      = DB::table('new_activites')->where('new_activites.id',$activity_res->tour_id)->first();
                

                $cart_all_data      = json_decode($activity_res->cart_total_data);
                $grand_profit       = 0;
                $grand_cost         = 0;
                $grand_sale         = 0;
                
                // dd($tours_costing);
                
                // Profit From Double Adults
                if(isset($cart_all_data) && $cart_all_data->adults > 0 && isset($tours_costing->cost_price)){
                    $double_adult_total_cost    = $tours_costing->cost_price * $cart_all_data->adults;
                    $double_profit              = $cart_all_data->adult_price - $double_adult_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_adult_total_cost;
                    $grand_sale                 += $cart_all_data->adult_price;
                }
                
                if(isset($cart_all_data) && $cart_all_data->children > 0 && isset($tours_costing->cost_price)){
                    $double_child_total_cost    = $tours_costing->cost_price * $cart_all_data->children;
                    $double_profit              = $cart_all_data->child_price - $double_child_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_child_total_cost;
                    $grand_sale                 += $cart_all_data->child_price;
                }
        
                return $grand_profit;
                
                // array_push($booking_all_data,$booking_data);
                
                // dd($booking_data);
            
    }
    
    public function getStartAndEndDate($type,$requestData){
        if($type == 'data_wise'){
            $start_date     = $requestData->check_in;
            $end_date       = $requestData->check_out;
        }
        
        if($type == 'data_today_wise'){
            $start_date     = date('Y-m-d');
            $end_date       = date('Y-m-d');
        }
        
        if($type == 'data_tomorrow_wise'){
            $start_date     = date('Y-m-d',strtotime("+1 days"));
            $end_date       = date('Y-m-d',strtotime("+1 days"));
        }
        
        if($type == 'data_week_wise'){
            $startOfWeek    = Carbon::now()->startOfWeek();
            $start_date     = $startOfWeek->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfWeek();
            $end_date       = $endOfWeek->format('Y-m-d');
            // dd($start_date,$end_date,$request_data);
            // $end_date       = date('Y-m-d');
        }
        
        if($type == 'data_month_wise'){
            $startOfMonth   = Carbon::now()->startOfMonth();
            $start_date     = $startOfMonth->format('Y-m-d');
            $endOfWeek      = Carbon::now()->endOfMonth();
            $end_date       = $endOfWeek->format('Y-m-d');
            // $end_date       = date('Y-m-d');
        }
        
        return [
                'start_date' => $start_date,
                'end_date' => $end_date,
            ];
    }
/*
|--------------------------------------------------------------------------
| Out Standings Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Received Payments Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All Received Payment List For Our Client.
*/
public function recieved_Payments(Request $req){
         $userData = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
         
         $cash_accounts = DB::table('cash_accounts')->where('customer_id',$userData->id)->get();
         
         $users = DB::table('customer_tour_pay_dt')
            ->join('tours_bookings','tours_bookings.invoice_no', '=','customer_tour_pay_dt.invoice_id')
            ->join('cart_details','cart_details.invoice_no', '=','customer_tour_pay_dt.invoice_id')
            ->where('customer_tour_pay_dt.customer_id',$userData->id)
            // ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('customer_tour_pay_dt.*', 'tours_bookings.passenger_name','cart_details.pakage_type','cart_details.tour_id','cart_details.tour_name','cart_details.price')
            ->get();
            
        return response()->json([
            'cash_accounts'=>$cash_accounts,
            'customer_payments' => $users,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Received Payments Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Received Payments Approve Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to insert and update data from database and Display All Received Payment Approve List For Our Client.
*/
public function recieved_Payments_approve(Request $req){
   
     
     DB::beginTransaction();

        try {
                 $payments_ids = json_decode($req->payment_id);
                $result = '';
                $customer_emails = [];
                $customer_ids = [];
                foreach($payments_ids as $pay_id_res){
                       $userData = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
                 
                 
                         $customer_payments = DB::table('customer_tour_pay_dt')->where('id',$pay_id_res)->first();
                         

                         // Update Customer Balance
                         $customer_data =     DB::table('booking_customers')->where('email',$customer_payments->email)
                                                                            ->where('customer_id',$userData->id)
                                                                            ->first();
                                                                            
                            $customer_emails[] = $customer_data->email;                                              
                            $customer_ids[] = $customer_data->id;                                        
                            
                         $updatedBalance = $customer_data->balance - $customer_payments->payment_am;
                            DB::table('customer_ledger')->insert([
                                'booking_customer'=>$customer_data->id,
                                'payment'=>$customer_payments->payment_am,
                                'balance'=>$updatedBalance,
                                'web_pay_request'=>$customer_payments->id,
                                'customer_id'=>$userData->id,
                                'date'=>$customer_payments->payment_date,
                                'remarks'=> "Payment Against Package Invoice ".$customer_payments->invoice_id."",
                            ]);
                            DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$updatedBalance]);
                            
                            
                        // Update Account Balance 
                        $cash_account_data = DB::table('cash_accounts')->where('id',$req->cash_account_id)->select('id','balance','name')->first();
                        
                        $updatedBalance =  $cash_account_data->balance + $customer_payments->payment_am;
                        
                        DB::table('cash_accountledgers')->insert([
                                            'account_id'=>$cash_account_data->id,
                                            'received'=>$customer_payments->payment_am,
                                            'balance'=>$updatedBalance,
                                            'web_pay_request'=>$customer_payments->id,
                                            'customer_id'=>$userData->id,
                                            'date'=>$customer_payments->payment_date,
                                            ]);
                        
                        DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);    
                            
                          
                        $result = DB::table('customer_tour_pay_dt')->where('id',$pay_id_res)->update([
                                'status'=>'approve'
                              ]);
                }
              
                      
                      
                DB::commit();      
                
                return response()->json([
                     'message' => 'Success',
                     'customer_emails'=> $customer_emails,
                     'customer_ids'=> $customer_ids,
                    ]);
            
          
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            
            echo $e;
            return response()->json([
                     'message' => 'error',
                     'customer_emails'=> '',
                     'customer_ids'=> '',
                    ]);
            // something went wrong
        }
   
      
}
/*
|--------------------------------------------------------------------------
| Received Payments Approve Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Update Customer Payment Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to update Customer Payments from database and Display All  Payment List For Our Client.
*/
public function update_customer_payment(Request $req){
    
         
      
      $insert =  view_booking_payment_recieve::find($req->payment_id);
      

      
        $cart_details1          = DB::table('cart_details')->where('booking_id',$insert->package_id)->first();
        
        
        // Calculate Difference
        
        $differ = $insert->recieved_amount - $req->recieved_amount;
        $differ_check = abs($differ);
        
        // Check Total = to Total Paid
        if($cart_details1->tour_total_price == $cart_details1->total_paid_amount){
            if($cart_details1->agent_name != '-1'){
            $agent_data = DB::table('Agents_detail')->where('id',$cart_details1->agent_name)->select('id','over_paid_am')->first();
            }
            
            if($differ < 0){
                // 1 Add Payments Tot Wallet
                $wallet_am = $agent_data->over_paid_am - $differ;
                $wallet_am = abs($wallet_am);
                if($cart_details1->agent_name != '-1'){
                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['over_paid_am'=>$wallet_am]);
                }
                // 2 Update invoice Over Paid
                
                $differ_check = abs($differ);
                $remain_over_paid = $cart_details1->over_paid_amount + $differ_check;
                
                 DB::table('cart_details')->where('booking_id',$insert->package_id)->update([
                    'over_paid_amount' => $remain_over_paid,
                ]);
            }else{
                // Check Wallet Greater than or equal to Diff
                if($agent_data->over_paid_am >= $differ_check){
                     // Yes 1 Subtract From Wallet
                     $wallet_am = $agent_data->over_paid_am - $differ_check;
                     
                     if($cart_details1->agent_name != '-1'){
                        DB::table('Agents_detail')->where('id',$agent_data->id)->update(['over_paid_am'=>$wallet_am]);
                     }
                    // 2 Update invoice Over Paid
                    
                    if($cart_details1->over_paid_amount > $differ_check){
                        $remain_over_paid = $cart_details1->over_paid_amount - $differ_check;
                         DB::table('cart_details')->where('booking_id',$insert->package_id)->update([
                            'over_paid_amount' => $remain_over_paid,
                        ]);
                    }else{
                        DB::table('cart_details')->where('booking_id',$insert->package_id)->update([
                            'over_paid_amount' => 0,
                        ]);
                    }
                
                    
                     
                }else{
                    // No 1  Subtract Difference From Wallet And Save Remain
                    $remain_amount = $differ_check - $agent_data->over_paid_am;
                    
                    // No 2  Subtract Remain From Payment Amount
                    $remaning_paid_am = $cart_details1->total_paid_amount - $remain_amount;
                    
                    // No 3  Both Wallet Update Zero
                    if($cart_details1->agent_name != '-1'){
                        DB::table('Agents_detail')->where('id',$agent_data->id)->update(['over_paid_am'=>0]);
                    }
                     
                     DB::table('cart_details')->where('booking_id',$insert->package_id)->update([
                            'over_paid_amount' => 0,
                            'total_paid_amount' => $remaning_paid_am,
                     ]);
                }
                   
                
               
                
                    
            }
           
        }else{
            $total_amount = $cart_details1->tour_total_price;
            $paid_amount = $cart_details1->total_paid_amount;
            $over_paid_amount = $cart_details1->over_paid_amount;
            $over_paid_am = 0;
            
            if($differ >= 0){
             
                 $total_paid_amount = $paid_amount - $differ;
                $total_over_paid = 0;
                if($total_paid_amount > $total_amount){
                    $over_paid_am = $total_paid_amount - $total_amount;
                    $total_over_paid = $over_paid_amount + $over_paid_am;
                    
                    $total_paid_amount = $total_paid_amount - $over_paid_am;
                }
            }else{
             
                $total_paid_amount = $paid_amount + abs($differ);

                $total_over_paid = 0;
                if($total_paid_amount > $total_amount){
                    
                    $over_paid_am = $total_paid_amount - $total_amount;
                    $total_over_paid = $over_paid_amount + $over_paid_am;
                    
                    $total_paid_amount = $total_paid_amount - $over_paid_am;
                }
            }
            
           
            
            DB::table('cart_details')->where('booking_id',$insert->package_id)->update([
                'total_paid_amount' => $total_paid_amount,
                'over_paid_amount' => $total_over_paid,
            ]);
            
            
            if($cart_details1->agent_name != '-1'){
                $agent_data = DB::table('Agents_detail')->where('id',$cart_details1->agent_name)->select('id','over_paid_am')->first();
                $agent_over_paid = $agent_data->over_paid_am + $over_paid_am;
                DB::table('Agents_detail')->where('id',$agent_data->id)->update(['over_paid_am'=>$agent_over_paid]);
            }
            
            
            
            
        //   $total_paid_update =  $cart_details1->total_paid_amount - $differ;
        //     DB::table('cart_details')->where('booking_id',$insert->package_id)->update([
        //         'total_paid_amount' => $total_paid_update,
        //     ]);
        
        }
        
    

      $insert->date             = $req->data;

      $insert->recieved_amount  = $req->recieved_amount;
      $insert->amount_paid  = $req->recieved_amount;

      $result = $insert->update();
      
              
            //   echo $result;
              
        
        if($result){
            return response()->json([
             'message' => 'Success',
            ]);
        }else{
            return response()->json([
             'message' => 'error',
            ]);
        }
          
    }
/*
|--------------------------------------------------------------------------
| Update Customer Payment Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Stats Tours Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Display All stats Tour List For Our Client.
*/
public function stats_Tours(Request $req){
        DB::beginTransaction();
        try {
            $data1                      = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$req->customer_id)->get();
            $booked_Pax                 = DB::table('tours')->join('cart_details','tours.id','cart_details.tour_id')->where('tours.customer_id',$req->customer_id)->get();
            $data1Decoded               = json_decode($data1);
            $currency_Symbol            = DB::table('customer_subcriptions')->where('id',$req->customer_id)->get();
            $total_Amount               = Db::table('view_booking_payment_recieve')->sum('total_amount');
            $recieved_Amount            = Db::table('view_booking_payment_recieve')->sum('recieved_amount');
            $total_Amount_Activity      = Db::table('view_booking_payment_recieve_Activity')->sum('total_amount');
            $recieved_Amount_Activity   = Db::table('view_booking_payment_recieve_Activity')->sum('recieved_amount');
            return response()->json([
                'message'                   => 'success',
                'booked_Pax'                => $booked_Pax,
                'data1'                     => $data1Decoded,
                'currency_Symbol'           => $currency_Symbol,
                'total_Amount'              => $total_Amount,
                'recieved_Amount'           => $recieved_Amount,
                'total_Amount_Activity'     => $total_Amount_Activity,
                'recieved_Amount_Activity'  => $recieved_Amount_Activity,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
    }
/*
|--------------------------------------------------------------------------
| Stats Tours Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| More Tours Details1 Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to count the adult and child from cart details Tour.
*/
public function more_Tour_Details1(Request $request){
        
        $adults  = DB::table('cart_details')->where('pakage_type','tour')->where('tour_id',$request->id)->sum('adults');
        $childs  = DB::table('cart_details')->where('pakage_type','tour')->where('tour_id',$request->id)->sum('childs');
        
        return response()->json([
          'adults'  => $adults,
          'childs'  => $childs,
        ]);
        
    }
/*
|--------------------------------------------------------------------------
| More Tours Details1 Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Hotel Detail ID Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display all tour list.
*/
public function hotel_detail_ID(Request $req) {
        $data1 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)->get();
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Hotel Detail ID Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Flight Detail ID Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Flight Details.
*/
public function flight_detail_ID(Request $req) {
        $data1 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)->get();
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Flight Detail ID Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Transportation Detail ID Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Transportation Details.
*/
public function transportation_detail_ID(Request $req) {
        $data1 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)->get();
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Transportation Detail ID Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Visa Detail ID Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Visa Details.
*/
public function visa_detail_ID(Request $req) {
        $data1 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)->get();
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Visa Detail ID Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Cancelled Tours Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Manage Quotation list.
*/
public function cancelled_Tours(Request $req){
        $data1 = addManageQuotation::all();
        return response()->json([
            'data1' => $data1,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Cancelled Tours Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| View Total Amount Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to count The Total Amount.
*/
public function view_total_Amount(Request $req) {
        $tour_activity_check = Db::table('cart_details')->where('tour_id',$req->id)->get();
        $total_Amount = Db::table('view_booking_payment_recieve')->where('tourId',$req->id)->sum('total_amount');
        $total_Amount_Activity = Db::table('view_booking_payment_recieve_Activity')->where('tourId',$req->id)->sum('total_amount');
        return response()->json([
            'tour_activity_check' => $tour_activity_check,
            'total_Amount' => $total_Amount,
            'total_Amount_Activity' => $total_Amount_Activity,
        ]);
        // echo $a ;
    }
/*
|--------------------------------------------------------------------------
| View Total Amount Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| View Receive Amount Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display active tour list and received amount.
*/
public function view_recieve_Amount(Request $req) {
        $tour_activity_check = Db::table('cart_details')->where('tour_id',$req->id)->get();
        $recieved_Amount = Db::table('view_booking_payment_recieve')->where('tourId',$req->id)->sum('recieved_amount');
        $recieved_Amount_Activity = Db::table('view_booking_payment_recieve_Activity')->where('tourId',$req->id)->sum('recieved_amount');
        return response()->json([
            'tour_activity_check' => $tour_activity_check,
            'recieved_Amount' => $recieved_Amount,
            'recieved_Amount_Activity' => $recieved_Amount_Activity,
        ]);
    }
/*
|--------------------------------------------------------------------------
| View Receive Amount Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| View Out Standings Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display active tour list and view out standing amount.
*/
public function view_Outstandings(Request $req) {
        $tour_activity_check = Db::table('cart_details')->where('tour_id',$req->id)->get();
        $total_Amount = Db::table('view_booking_payment_recieve')->where('tourId',$req->id)->sum('total_amount');
        $total_Amount_Activity = Db::table('view_booking_payment_recieve_Activity')->where('tourId',$req->id)->sum('total_amount');
        $recieved_Amount = Db::table('view_booking_payment_recieve')->where('tourId',$req->id)->sum('recieved_amount');
        $recieved_Amount_Activity = Db::table('view_booking_payment_recieve_Activity')->where('tourId',$req->id)->sum('recieved_amount');
        return response()->json([
            'tour_activity_check' => $tour_activity_check,
            'total_Amount' => $total_Amount,
            'total_Amount_Activity' => $total_Amount_Activity,
            'recieved_Amount' => $recieved_Amount,
            'recieved_Amount_Activity' => $recieved_Amount_Activity,
        ]);
    }
/*
|--------------------------------------------------------------------------
| View Out Standings Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| View Details Accomodation Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Accomodations list.
*/
public function view_Details_Accomodation(Request $req) {
        $data = Db::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)->first();
        return response()->json([
            'data' => $data,
        ]);
    }
/*
|--------------------------------------------------------------------------
| View Details Accomodation Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Accomodation Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Accomodation pay list.
*/
public function acc_Pay(Request $req) {
        $data                   = Db::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->t_Id)->first();
        $paid_accomodation      = Db::table('accomodation_Pay')->where('tourId',$req->t_Id)->where('selected_city',$req->selected_city)->sum('amount_accomodation_paid');
        return response()->json([
            'data'                  => $data,
            'paid_accomodation'     => $paid_accomodation,
        ]);
    }
/*
|--------------------------------------------------------------------------
| Accomodation Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Submit Accomodation Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to insert the detail Accomodation pay.
*/
public function sumbit_Accomodation_Pay(Request $request){
        $insert = new accomodation_Pay();
        $insert->tourId                             = $request->tourId;
        $insert->paying_date                        = $request->paying_date;
        $insert->customer_name                      = $request->customer_name;
        $insert->package_title                      = $request->package_title;
        $insert->selected_city                      = $request->selected_city;
        $insert->total_accomodation_amount          = $request->total_accomodation_amount;
        $insert->recieved_accomodation_amount       = $request->recieved_accomodation_amount;
        $insert->remaining_accomodation_amount      = $request->remaining_accomodation_amount;
        $insert->amount_accomodation_paid           = $request->amount_accomodation_paid;

        $insert->save();
        return response()->json(['message' => 'Success']);
    }
/*
|--------------------------------------------------------------------------
|  Submit Accomodation Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Submit Flight Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to insert the detail flight pay.
*/
public function sumbit_Flight_Pay(Request $request){
        $insert = new flight_Pay();
        $insert->tourId                     = $request->tourId;
        $insert->paying_date                = $request->paying_date;
        $insert->customer_name              = $request->customer_name;
        $insert->package_title              = $request->package_title;
        $insert->total_flight_amount        = $request->total_flight_amount;
        $insert->recieved_flight_amount     = $request->recieved_flight_amount;
        $insert->remaining_flight_amount    = $request->remaining_flight_amount;
        $insert->amount_flight_paid         = $request->amount_flight_paid;

        $insert->save();
        return response()->json(['message' => 'Success']);
    }
/*
|--------------------------------------------------------------------------
|  Submit Flight Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Transportation Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to display Transportation pay.
*/
public function transportation_Pay(Request $req) {
        $data                   = Db::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)->first();
        $customer_Data          = Db::table('customer_subcriptions')->where('id',$req->customer_id)->first();
        $paid_transportation    = Db::table('Transportation_Pay')->where('tourId',$req->id)->sum('amount_transportation_paid');
        $paid_flights           = Db::table('flight_Pay')->where('tourId',$req->id)->sum('amount_flight_paid');
        return response()->json([
            'data'                  => $data,
            'customer_Data'         => $customer_Data,
            'paid_transportation'   => $paid_transportation,
            'paid_flights'          => $paid_flights,
        ]);
    }
/*
|--------------------------------------------------------------------------
|  Transportation Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Submit Transportation Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to insert the detail Transportation pay.
*/
public function sumbit_Transportation_Pay(Request $request){
        $insert = new Transportation_Pay();
        $insert->tourId                             = $request->tourId;
        $insert->paying_date                        = $request->paying_date;
        $insert->customer_name                      = $request->customer_name;
        $insert->package_title                      = $request->package_title;
        $insert->total_transportation_amount        = $request->total_transportation_amount;
        $insert->recieved_transportation_amount     = $request->recieved_transportation_amount;
        $insert->remaining_transportation_amount    = $request->remaining_transportation_amount;
        $insert->amount_transportation_paid         = $request->amount_transportation_paid;

        $insert->save();
        return response()->json(['message' => 'Success']);
    }
/*
|--------------------------------------------------------------------------
|  Submit Transportation Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  View Transportation Total Amount Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to calculate the total amount in transportation.
*/
public function view_transportation_total_Amount(Request $req) {
        $view_transportation_total_Amount    = Db::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)
        ->select('tours.no_of_pax_days','tours_2.transportation_details','tours_2.flights_details')->first();
        $view_transportation_recieve_Amount  = Db::table('Transportation_Pay')->where('tourId',$req->id)->sum('amount_transportation_paid');
        $view_flight_recieve_Amount          = Db::table('flight_Pay')->where('tourId',$req->id)->sum('amount_flight_paid');
        return response()->json([
            'view_transportation_total_Amount'    => $view_transportation_total_Amount,
            'view_transportation_recieve_Amount'  => $view_transportation_recieve_Amount,
            'view_flight_recieve_Amount'          => $view_flight_recieve_Amount,
        ]);
        // echo $a ;
    }
/*
|--------------------------------------------------------------------------
|  View Transportation Total Amount Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Visa Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Get The Pay Visa Amount.
*/
public function visa_Pay(Request $req) {
        $data           = Db::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)->first();
        $customer_Data  = Db::table('customer_subcriptions')->where('id',$req->customer_id)->first();
        $paid_visa      = Db::table('visa_Pay')->where('tourId',$req->id)->sum('amount_visa_paid');
        
        return response()->json([
            'data'          => $data,
            'customer_Data' => $customer_Data,
            'paid_visa'     => $paid_visa,
        ]);
    }
/*
|--------------------------------------------------------------------------
|  Visa Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  Submit Visa Pay Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Insert The Visa Pay.
*/
public function sumbit_Visa_Pay(Request $request){
        $insert = new visa_Pay();
        $insert->tourId                 = $request->tourId;
        $insert->paying_date            = $request->paying_date;
        $insert->customer_name          = $request->customer_name;
        $insert->package_title          = $request->package_title;
        $insert->total_visa_amount      = $request->total_visa_amount;
        $insert->recieved_visa_amount   = $request->recieved_visa_amount;
        $insert->remaining_visa_amount  = $request->remaining_visa_amount;
        $insert->amount_visa_paid       = $request->amount_visa_paid;

        $insert->save();
        return response()->json(['message' => 'Success']);
    }
/*
|--------------------------------------------------------------------------
|  Submit Visa Pay Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
|  View Visa Total Amount Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to Calculate the total visa amount.
*/
public function view_visa_total_Amount(Request $req) {
        $view_visa_total_Amount     = Db::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$req->id)->select('tours.no_of_pax_days','tours.visa_fee')->first();
        $view_visa_recieve_Amount   = Db::table('visa_Pay')->where('tourId',$req->id)->sum('amount_visa_paid');
        return response()->json([
            'view_visa_total_Amount'    => $view_visa_total_Amount,
            'view_visa_recieve_Amount'  => $view_visa_recieve_Amount,
        ]);
        // echo $a ;
    }
/*
|--------------------------------------------------------------------------
|  View Visa Total Amount Ended
|--------------------------------------------------------------------------
*/







/*
|--------------------------------------------------------------------------
| AccountDetailsApiController Function Ended
|--------------------------------------------------------------------------
*/     
}