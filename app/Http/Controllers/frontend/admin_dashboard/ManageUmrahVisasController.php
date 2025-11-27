<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Agents_detail;
use App\Models\booking_customers;
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
use App\Models\expense\expense;
use App\Models\expense\expenseCategory;
use App\Models\expense\expenseSubCategory;
use App\Models\Accounts\CashAccounts;
use App\Models\Accounts\CashAccountsBal;
use App\Models\Accounts\CashAccountledger;
use App\Models\addGroupsdetails;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ManageUmrahVisasController extends Controller
{
    // Groups
    public function create_umrah_Visas(Request $request){
        $groups_Detail  = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)->get();
        
        // $all_dates      = DB::table('addGroupsdetails')->select('group_Travel_Date')->distinct()->groupBy('group_Travel_Date')->get();
        // dd($all_dates);
        
        $all_dates = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)->select('group_Travel_Date')->distinct()->orderBy('group_Travel_Date', 'desc')->get();
        // dd($all_dates);
        
        $date_wise_data = [];
        
        if(count($all_dates) != 0){
            foreach($all_dates as $value_GD){
                if($value_GD->group_Travel_Date != null){
                    
                    $data_DW  = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)
                                ->whereDate('group_Travel_Date',$value_GD->group_Travel_Date)
                                ->orderBy('group_Travel_Date', 'ASC')
                                ->get();
                                
                    $merged_Groups  = [];
                    foreach($data_DW as $val_DW) {
                        $key = $val_DW->group_Client_Prefix;
                        if (array_key_exists($key, $merged_Groups)) {
                            if (!empty($val_DW->group_Client_Prefix)) {
                                $merged_Groups[$key]->group_Client_Prefix = $val_DW->group_Client_Prefix;
                            }
                        } else {
                            $merged_Groups[$key] = $val_DW;
                        }
                    }
                    $merged_Groups = array_values($merged_Groups);
                    
                    $count_RN   = DB::table('addGroupsdetails')
                                    ->whereDate('group_Travel_Date',$value_GD->group_Travel_Date)
                                    ->select(['group_Travel_Date','group_Client_Prefix',DB::raw('count(group_Client_Prefix) as agent_Ref_No')])
                                    ->groupBy('group_Travel_Date','group_Client_Prefix')->get();
                    
                    $more_RN = [];
                    foreach($count_RN as $val_RN){
                        if($val_RN->agent_Ref_No > 1){
                            $matched_RN     = DB::table('addGroupsdetails')
                                                ->whereDate('group_Travel_Date',$value_GD->group_Travel_Date)
                                                ->where('group_Client_Prefix',$val_RN->group_Client_Prefix)
                                                ->get();
                            array_push($more_RN,$matched_RN);
                        }
                    }
                    
                    $data       = [
                        'group_Travel_Date' => $value_GD->group_Travel_Date,
                        'data_DW'           => $merged_Groups,
                        'count_RN'          => $count_RN,
                        'more_RN'           => $more_RN,
                    ];
                    
                    array_push($date_wise_data,$data);
                }
            }
        }
        // dd($date_wise_data);
        
        $all_invoices   = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id)->select('groups_id')->get();
        $all_countries  = DB::table('countries')->get();
        $all_Agents     = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
        
        return response()->json(['status'=>'success','all_Agents'=>$all_Agents,'all_invoices'=>$all_invoices,'groups_Detail'=>$groups_Detail,'all_dates'=>$all_dates,'all_countries'=>$all_countries,'date_wise_data'=>$date_wise_data]);
    }
    
    public function add_umrah_Visas(Request $request){
        DB::beginTransaction();
        try {
            $existing_Groups        = [];
            $invalid_Agent_Format   = [];
            
            if(isset($request->upload_data) && $request->upload_data != null && $request->upload_data != ''){
                $upload_data = json_decode($request->upload_data);
                // foreach($upload_data as $val_UD){
                //     if($val_UD->group_Name != null && $val_UD->group_Name != ''){
                //         $check = DB::table('addGroupsdetails')->where('customer_id',$val_UD->customer_id)->where('group_Name',$val_UD->group_Name)->first();
                //         if($check == null){
                //             $check_VS = DB::table('visa_Sup')->where('customer_id',$val_UD->customer_id)->where('visa_Ref_No',$val_UD->group_Contract_Account)->first();
                //             if($check_VS == null){
                //                 $group_Travel_Date  = $val_UD->group_Travel_Date;
                //                 $timestamp          = strtotime(str_replace('/', '-', $group_Travel_Date));
                //                 $formattedDate      = date('Y-m-d', $timestamp);
                                
                //                 DB::table('addGroupsdetails')->insert([
                //                     'customer_id'               => $val_UD->customer_id,
                //                     'group_Id'                  => $val_UD->group_Id,
                //                     'group_Stage'               => $val_UD->group_Stage,
                //                     'group_Name'                => $val_UD->group_Name,
                //                     'group_embassy'             => $val_UD->group_embassy,
                //                     'group_Client'              => $val_UD->group_Client,
                //                     'group_Contract'            => $val_UD->group_Contract,
                //                     'group_Travel_Date'         => $formattedDate,
                //                     'group_Passport_Count'      => $val_UD->group_Passport_Count,
                //                     'group_Invoice_No'          => $val_UD->group_Invoice_No,
                //                     'group_UASP_Id'             => $val_UD->group_UASP_Id,
                //                     'group_Contract_Prefix'     => $val_UD->group_Contract_Prefix,
                //                     'group_Client_Prefix'       => $val_UD->group_Client_Prefix,
                //                     'group_Contract_Account'    => $val_UD->group_Contract_Account,
                //                     'group_Client_Account'      => $val_UD->group_Client_Account,
                //                 ]);
                //             }else{
                //                 DB::table('addGroupsdetails')->where('group_Name',$check->group_Name)->update([
                //                     'customer_id'               => $val_UD->customer_id,
                //                     'group_Id'                  => $val_UD->group_Id,
                //                     'group_Stage'               => $val_UD->group_Stage,
                //                     'group_Name'                => $val_UD->group_Name,
                //                     'group_embassy'             => $val_UD->group_embassy,
                //                     'group_Client'              => $val_UD->group_Client,
                //                     'group_Contract'            => $val_UD->group_Contract,
                //                     'group_Travel_Date'         => $formattedDate,
                //                     'group_Passport_Count'      => $val_UD->group_Passport_Count,
                //                     'group_Invoice_No'          => $val_UD->group_Invoice_No,
                //                     'group_UASP_Id'             => $val_UD->group_UASP_Id,
                //                     'group_Contract_Prefix'     => $val_UD->group_Contract_Prefix,
                //                     'group_Client_Prefix'       => $val_UD->group_Client_Prefix,
                //                     'group_Contract_Account'    => $val_UD->group_Contract_Account,
                //                     'group_Client_Account'      => $val_UD->group_Client_Account,
                //                 ]);
                //             }
                //         }else{
                //             $check_VS = DB::table('visa_Sup')->where('customer_id',$val_UD->customer_id)->where('visa_Ref_No',$val_UD->group_Contract_Account)->first();
                //             if($check_VS == null){
                //                 $group_Travel_Date  = $val_UD->group_Travel_Date;
                //                 $timestamp          = strtotime(str_replace('/', '-', $group_Travel_Date));
                //                 $formattedDate      = date('Y-m-d', $timestamp);
                //                 DB::table('addGroupsdetails')->insert([
                //                     'customer_id'               => $val_UD->customer_id,
                //                     'group_Id'                  => $val_UD->group_Id,
                //                     'group_Stage'               => $val_UD->group_Stage,
                //                     'group_Name'                => $val_UD->group_Name,
                //                     'group_embassy'             => $val_UD->group_embassy,
                //                     'group_Client'              => $val_UD->group_Client,
                //                     'group_Contract'            => $val_UD->group_Contract,
                //                     'group_Travel_Date'         => $formattedDate,
                //                     'group_Passport_Count'      => $val_UD->group_Passport_Count,
                //                     'group_Invoice_No'          => $val_UD->group_Invoice_No,
                //                     'group_UASP_Id'             => $val_UD->group_UASP_Id,
                //                     'group_Contract_Prefix'     => $val_UD->group_Contract_Prefix,
                //                     'group_Client_Prefix'       => $val_UD->group_Client_Prefix,
                //                     'group_Contract_Account'    => $val_UD->group_Contract_Account,
                //                     'group_Client_Account'      => $val_UD->group_Client_Account,
                //                 ]);
                //             }else{
                //                 DB::table('addGroupsdetails')->where('group_Name',$check->group_Name)->update([
                //                     'customer_id'               => $val_UD->customer_id,
                //                     'group_Id'                  => $val_UD->group_Id,
                //                     'group_Stage'               => $val_UD->group_Stage,
                //                     'group_Name'                => $val_UD->group_Name,
                //                     'group_embassy'             => $val_UD->group_embassy,
                //                     'group_Client'              => $val_UD->group_Client,
                //                     'group_Contract'            => $val_UD->group_Contract,
                //                     'group_Travel_Date'         => $formattedDate,
                //                     'group_Passport_Count'      => $val_UD->group_Passport_Count,
                //                     'group_Invoice_No'          => $val_UD->group_Invoice_No,
                //                     'group_UASP_Id'             => $val_UD->group_UASP_Id,
                //                     'group_Contract_Prefix'     => $val_UD->group_Contract_Prefix,
                //                     'group_Client_Prefix'       => $val_UD->group_Client_Prefix,
                //                     'group_Contract_Account'    => $val_UD->group_Contract_Account,
                //                     'group_Client_Account'      => $val_UD->group_Client_Account,
                //                 ]);
                //             }
                //         }
                //     }
                // }
                
                foreach($upload_data as $val_UD){
                    if($val_UD->group_Name != null && $val_UD->group_Name != ''){
                        $group_Travel_Date  = $val_UD->group_Travel_Date;
                        $timestamp          = strtotime(str_replace('/', '-', $group_Travel_Date));
                        $formattedDate      = date('Y-m-d', $timestamp);
                        
                        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                            // Check Existing Groups With Same Date and Name
                            $check = DB::table('addGroupsdetails')
                                    ->where('customer_id',$request->customer_id)
                                    ->where('SU_id',$request->SU_id)
                                    ->where('group_Name',$val_UD->group_Name)
                                    ->where('group_Travel_Date',$formattedDate)
                                    ->first();
                        }else{
                            // Check Existing Groups With Same Date and Name
                            $check = DB::table('addGroupsdetails')
                                    ->where('customer_id',$request->customer_id)
                                    ->where('group_Name',$val_UD->group_Name)
                                    ->where('group_Travel_Date',$formattedDate)
                                    ->first();
                        }
                        
                        if($check != null){
                            array_push($existing_Groups,$val_UD);
                        }else{
                            // Check Invalid Agent Reference Number
                            $group_Client_Prefix = $val_UD->group_Client_Prefix;
                            if (Str::startsWith($group_Client_Prefix, 'TT')) {
                                DB::table('addGroupsdetails')->insert([
                                    'customer_id'               => $val_UD->customer_id,
                                    'SU_id'                     => $request->SU_id ?? NULL,
                                    'group_Id'                  => $val_UD->group_Id,
                                    'group_Stage'               => $val_UD->group_Stage,
                                    'group_Name'                => $val_UD->group_Name,
                                    'group_embassy'             => $val_UD->group_embassy,
                                    'group_Client'              => $val_UD->group_Client,
                                    'group_Contract'            => $val_UD->group_Contract,
                                    'group_Travel_Date'         => $formattedDate,
                                    'group_Passport_Count'      => $val_UD->group_Passport_Count,
                                    'group_Invoice_No'          => $val_UD->group_Invoice_No,
                                    'group_UASP_Id'             => $val_UD->group_UASP_Id,
                                    'group_Contract_Prefix'     => $val_UD->group_Contract_Prefix,
                                    'group_Client_Prefix'       => $val_UD->group_Client_Prefix,
                                    'group_Contract_Account'    => $val_UD->group_Contract_Account,
                                    'group_Client_Account'      => $val_UD->group_Client_Account,
                                ]);
                            } else {
                                array_push($invalid_Agent_Format,$val_UD);
                            }
                        }
                    }
                }
            }
            
            // dd('STOP');
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'success','existing_Groups'=>$existing_Groups,'invalid_Agent_Format'=>$invalid_Agent_Format]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function edit_umrah_Visas(Request $request){
        $groups_Detail  = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)->get();
        $groups_DS      = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
        $all_countries  = DB::table('countries')->get();
        return response()->json(['status'=>'success','groups_Detail'=>$groups_Detail,'groups_DS'=>$groups_DS,'all_countries'=>$all_countries]);
    }
    
    public function update_umrah_Visas(Request $request){
        DB::beginTransaction();
        try {
            $id                             = $request->id;
            $group_detail                   = addGroupsdetails::find($id);
            $group_detail->customer_id      = $request->customer_id;
            $group_detail->type             = $request->type;
            $group_detail->name             = $request->name;
            $group_detail->country_symbol   = $request->country_symbol;
            $group_detail->selected_Date    = $request->selected_Date;
            $group_detail->save();
            DB::commit();
            return response()->json(['status'=>'success','message'=>'success']);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function get_agent_slot_ajax(Request $request){
        $agent_Details  = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('agent_Refrence_No',$request->agent_Refrence_No)->first();
        $slot_Details   = DB::table('agent_Slots')->where('customer_id',$request->customer_id)->where('agent_Id',$agent_Details->id)->first();
        return response()->json(['status'=>'success','agent_Details'=>$agent_Details,'slot_Details'=>$slot_Details]);
    }
    
    public function check_group_RN_ajax(Request $request){
        
        $groups_Detail  = DB::table('addGroupsdetails')
                            ->where('customer_id',$request->customer_id)
                            ->where('group_Travel_Date',$request->group_TD)
                            ->where('group_Client_Prefix',$request->group_RN)
                            ->get();
        
        // dd(count($groups_Detail));
        
        if(count($groups_Detail) > 1){
            return response()->json(['status'=>'success','matched_GD'=>$groups_Detail]);
        }else{
            return response()->json(['status'=>'error']);
        }
    }
    
    public function view_Booking_Cards(Request $req){
        $all_countries      = country::all();
        if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
            $invoices_Details   = DB::table('add_manage_invoices')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
            $confirm_quotations = DB::table('addManageQuotationPackage')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->where('quotation_status',1)->orderBy('id', 'DESC')->get();
            $booking_customers  = DB::table('booking_customers')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
            $agents_Detail      = DB::table('Agents_detail')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
            $documents_Detail   = DB::table('uploadDocumentInvoice')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
            $groups_Detail      = DB::table('addGroupsdetails')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        }else{
            $invoices_Details   = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
            $confirm_quotations = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->where('quotation_status',1)->orderBy('id', 'DESC')->get();
            $booking_customers  = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
            $agents_Detail      = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $documents_Detail   = DB::table('uploadDocumentInvoice')->where('customer_id',$req->customer_id)->get();
            $groups_Detail      = DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->get();
        }
        return response()->json([
            'invoices_Details'      => $invoices_Details,
            'confirm_quotations'    => $confirm_quotations,
            'all_countries'         => $all_countries,
            'booking_customers'     => $booking_customers,
            'agents_Detail'         => $agents_Detail,
            'documents_Detail'      => $documents_Detail,
            'groups_Detail'         => $groups_Detail,
        ]); 
    }
    
    public function get_Agent_Groups(Request $request){
        $all_Agents = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$request->agent_ID)->first();
        if($request->agent_group_filters == 'This_Week'){
            $startDate  = Carbon::now()->startOfWeek();
            $endDate    = Carbon::now()->endOfWeek();
            
            $all_AG     = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)
                            ->where('group_Client_Prefix',$all_Agents->agent_Refrence_No)
                            ->whereBetween('group_Travel_Date', [$startDate, $endDate])
                            ->get();
            
        }else if($request->agent_group_filters == 'This_Month'){
            $startDate  = Carbon::now()->startOfMonth();
            $endDate    = Carbon::now()->endOfMonth();
            
            $all_AG     = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)
                            ->where('group_Client_Prefix',$all_Agents->agent_Refrence_No)
                            ->whereBetween('group_Travel_Date', [$startDate, $endDate])
                            ->get();
            
        }else if($request->agent_group_filters == 'Custom_Dates'){
            $all_AG     = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)
                            ->where('group_Client_Prefix',$all_Agents->agent_Refrence_No)
                            ->whereBetween('group_Travel_Date', [$request->custom_Dates_From, $request->custom_Dates_To])
                            ->get();
        }
        else if($request->agent_group_filters == 'All_Data'){
            $all_AG     = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)
                            ->where('group_Client_Prefix',$all_Agents->agent_Refrence_No)
                            ->get();
        }else{
            $all_AG = '';
        }
        
        if(count($all_AG) != 0){
            return response()->json([
                'message'       => 'success',
                'all_Agents'    => $all_Agents,
                'all_AG'        => $all_AG,
            ]);
        }else{
            return response()->json([
                'message'   => 'error',
            ]);
        }
    }
}