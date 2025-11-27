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
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use DB;

class AllowSupplierController extends Controller
{
    public function allow_Suppliers(Request $request){
        try {
            $hotel_Suppliers    = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
            $flight_Suppliers   = DB::table('supplier')->where('customer_id',$request->customer_id)->get();
            $transfer_Suppliers = DB::table('transfer_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
            $visa_Suppliers     = DB::table('visa_Sup')->where('customer_id',$request->customer_id)->get();
            $all_Customer       = DB::table('customer_subcriptions')->where('id', '!=' ,$request->customer_id)->get();
            return response()->json(['hotel_Suppliers'=>$hotel_Suppliers,'flight_Suppliers'=>$flight_Suppliers,'transfer_Suppliers'=>$transfer_Suppliers,'visa_Suppliers'=>$visa_Suppliers,'all_Customer'=>$all_Customer]);
            
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
    
    public function view_Invoices_Suppliers(Request $request){
        try {
            $all_countries      = country::all();
            
            $hotel_Suppliers    = DB::table('rooms_Invoice_Supplier')
                                    // ->where('id',131)
                                    ->where('customer_id',$request->customer_id)
                                    ->Orwhere('sub_customer_id',$request->customer_id)
                                    ->get();
                                    
            foreach($hotel_Suppliers as $val_HS){
                $potentialMatches   = DB::table('add_manage_invoices')
                                        ->whereNotNull('accomodation_details')
                                        ->where('accomodation_details', '!=', '')
                                        ->where(function ($query) use ($val_HS) {
                                          $query->where('accomodation_details', 'LIKE', '%"hotel_supplier_id":"' . $val_HS->id . '"%');
                                        })
                                        ->get();
                          
                $hotel_Invoices = $potentialMatches->filter(function ($record) use ($val_HS) {
                    $accomodationDetails = json_decode($record->accomodation_details, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        foreach ($accomodationDetails as $detail) {
                            if (isset($detail['hotel_supplier_id']) && $detail['hotel_supplier_id'] == (string)$val_HS->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });
            }
            
            // dd($hotel_Invoices);
            
            $booking_customers  = [];
            $b2b_Agents_Detail  = [];
            $agents_Detail      = [];
            $agent_data         = [];
            $documents_Detail   = [];
            $groups_Detail      = [];
            $role_managers      = [];
            
            foreach($hotel_Invoices as $val_HI){
                $customer_Id            = DB::table('customer_subcriptions')->where('id',$val_HI->customer_id)->first();
                $booking_customers_New  = DB::table('booking_customers')->where('customer_id',$val_HI->customer_id)->get();
                array_push($booking_customers,$booking_customers_New[0] ?? '');
                $b2b_Agents_Detail_New  = DB::table('b2b_agents')->where('token',$customer_Id->Auth_key)->get();
                array_push($booking_customers,$b2b_Agents_Detail_New[0] ?? '');
                $agents_Detail_New      = DB::table('Agents_detail')->where('customer_id',$val_HI->customer_id)->get();
                array_push($booking_customers,$agents_Detail_New[0] ?? '');
                $agent_data_New         = DB::table('Agents_detail')->where('customer_id',$val_HI->customer_id)->get();
                array_push($booking_customers,$agent_data_New[0] ?? '');
                $documents_Detail_New   = DB::table('uploadDocumentInvoice')->where('customer_id',$val_HI->customer_id)->get();
                array_push($booking_customers,$documents_Detail_New[0] ?? '');
                $groups_Detail_New      = DB::table('addGroupsdetails')->where('customer_id',$val_HI->customer_id)->get();
                array_push($booking_customers,$groups_Detail_New[0] ?? '');
                $role_managers_New      = DB::table('role_managers')->where('customer_id',$val_HI->customer_id)->get();
                array_push($booking_customers,$role_managers_New[0] ?? '');
            }
            
            return response()->json([
                'hotel_Invoices'        => $hotel_Invoices,
                'role_managers'         => $role_managers,
                'all_countries'         => $all_countries,
                'booking_customers'     => $booking_customers,
                'agents_Detail'         => $agents_Detail,
                'documents_Detail'      => $documents_Detail,
                'groups_Detail'         => $groups_Detail,
                'b2b_Agents_Detail'     => $b2b_Agents_Detail,
            ]);
            
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
    
    public function add_Hotel_Supplier_To_Customer(Request $request1){
        DB::beginTransaction();
        try {
            $request                = json_decode($request1->hotel_Supplier_Details);
            $check_Supplier_Exist   = DB::table('rooms_Invoice_Supplier')->where('sub_Supplier_Id',$request->id)->where('customer_id',$request1->hotel_Customer_Id)->where('sub_customer_id',$request1->customer_id)->first();
            
            // dd($check_Supplier_Exist);
            if($check_Supplier_Exist != null && $check_Supplier_Exist != '' && !empty($check_Supplier_Exist)){
                return response()->json(['message'=>'error']);
            }else{
                $words_RSN          = preg_split('/\s+/', $request->room_supplier_name);
                $room_supplier_name = '';
                foreach ($words_RSN as $word) {
                    if (ctype_alpha($word[0])) {
                        $room_supplier_name .= strtoupper($word[0]);
                    }
                }
                
                if($request->room_supplier_name != null && $request->room_supplier_name != '' && $request->email != null && $request->email != ''){
                    $data = DB::table('rooms_Invoice_Supplier')->insert([  
                        'SU_id'                 => $request->SU_id ?? NULL,
                        'sub_Supplier_Id'       => $request->id,
                        'sub_customer_id'       => $request1->customer_id,
                        'customer_id'           => $request1->hotel_Customer_Id,
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
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
    
    public function add_Flight_Supplier_To_Customer(Request $request1){
        DB::beginTransaction();
        try {
            $request                = json_decode($request1->flight_Supplier_Details);
            $check_Supplier_Exist   = DB::table('supplier')->where('customer_id',$request1->flight_Customer_Id)->where('sub_customer_id',$request1->customer_id)->first();
            
            // dd($check_Supplier_Exist,$request);
            
            if($check_Supplier_Exist != null && $check_Supplier_Exist != '' && !empty($check_Supplier_Exist)){
                return response()->json(['message'=>'error']);
            }else{
                $selected_CD = DB::table('customer_subcriptions')->where('id',$request1->flight_Customer_Id)->select('Auth_key')->first();
                DB::table('supplier')->insert([
                    'SU_id'                 => $request1->SU_id ?? NULL,
                    'token'                 => $selected_CD->Auth_key,
                    'sub_customer_id'       => $request1->customer_id,
                    'customer_id'           => $request1->flight_Customer_Id,
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
                
                DB::commit();
                
                return response()->json(['message'=>'success','Status'=>'SuccessFull']);
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
}