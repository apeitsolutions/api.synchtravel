<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\addManageInvoice;
use App\Models\otherPaxDetail;

class InvoiceVisaGroupController extends Controller
{
    public static function seasonDetailsCheck($all_data,$request){
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
                $start_Date     = Carbon::parse($start_Date);
                $end_Date       = Carbon::parse($end_Date);
                
                // If services exists and is valid JSON
                $services = [];
            
                if (isset($record->services)) {
                    $decoded = json_decode($record->services, true);
                    $services = is_array($decoded) ? $decoded : [];
                }
                
                // If 'accomodation_tab' is present in services
                if (is_array($services) && in_array('accomodation_tab', $services)) {
                    if (!isset($record->start_date) || empty($record->start_date)) {
                        return false;
                    }
                    
                    $created_at = Carbon::parse($record->start_date);
                } else {
                    // Fallback to created_at
                    if (!isset($record->created_at) || empty($record->created_at)) {
                        return false;
                    }
                    
                    $created_at = Carbon::parse($record->created_at);
                }
                
                // Now check if the date falls within range
                return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function view_invoices_visa_groups(Request $request){
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
        // Season
        
        return response()->json([
            'season_Details'        => $season_Details,
            'season_Id'             => $season_Id,
        ]); 
    }
    
    public function view_invoices_visa_groups_Ajax(Request $request){
        $draw               = json_decode($request->draw);
        $start              = json_decode($request->start);
        $rowperpage         = json_decode($request->length);
        $columnIndex_arr    = json_decode($request->order);
        $columnName_arr     = json_decode($request->columns);
        $order_arr          = json_decode($request->order);
        $search_arr         = json_decode($request->search);
        $columnIndex        = $columnIndex_arr[0]->column ?? 0;
        $columnName         = $columnName_arr[$columnIndex]->data ?? 'created_at';
        $columnSortOrder    = $order_arr[0]->dir ?? 'desc';
        $searchValue        = $search_arr->value;
        
        $totalRecords       = addManageInvoice::count();
        // Season
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $query              = addManageInvoice::with(['otherPax' => function ($q) {
                                    $q->select('invoice_id','pilgramsName','passportNumber','dateOfBirth','groupType','issueDate','groupCode');
                                }])
                                ->where('add_manage_invoices.customer_id', $request->customer_id)
                                ->where('add_manage_invoices.SU_id',$request->SU_id)
                                ->select(
                                    'add_manage_invoices.id','add_manage_invoices.generate_id','add_manage_invoices.total_sale_price_all_Services','add_manage_invoices.b2b_Agent_Company_Name','add_manage_invoices.services',
                                    'add_manage_invoices.booking_customer_id','add_manage_invoices.agent_Company_Name','add_manage_invoices.created_at','add_manage_invoices.f_name','add_manage_invoices.middle_name',
                                    'add_manage_invoices.start_Date','add_manage_invoices.end_Date'
                                );
        }else{
            $query              = addManageInvoice::with(['otherPax' => function ($q) {
                                    $q->select('invoice_id','pilgramsName','passportNumber','dateOfBirth','groupType','issueDate','groupCode');
                                }])
                                ->where('add_manage_invoices.customer_id', $request->customer_id)
                                ->select(
                                    'add_manage_invoices.id','add_manage_invoices.generate_id','add_manage_invoices.total_sale_price_all_Services','add_manage_invoices.b2b_Agent_Company_Name','add_manage_invoices.services',
                                    'add_manage_invoices.booking_customer_id','add_manage_invoices.agent_Company_Name','add_manage_invoices.created_at','add_manage_invoices.f_name','add_manage_invoices.middle_name',
                                    'add_manage_invoices.start_Date','add_manage_invoices.end_Date'
                                );
        }
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
        // dd($season_Id);
        // Season
        
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('add_manage_invoices.id', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.generate_id', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.created_at', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.total_sale_price_all_Services', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.b2b_Agent_Company_Name', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.services', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.agent_Company_Name', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.f_name', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.middle_name', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.start_Date', 'like', "%$searchValue%")
                ->orwhere('add_manage_invoices.end_Date', 'like', "%$searchValue%")
                ->orWhereHas('otherPax', function ($q) use ($searchValue) {
                    $q->where('otherPaxDetails.pilgramsName', 'like', "%$searchValue%")
                      ->orWhere('otherPaxDetails.passportNumber', 'like', "%$searchValue%")
                      ->orWhere('otherPaxDetails.dateOfBirth', 'like', "%$searchValue%")
                      ->orWhere('otherPaxDetails.groupType', 'like', "%$searchValue%")
                      ->orWhere('otherPaxDetails.issueDate', 'like', "%$searchValue%")
                      ->orWhere('otherPaxDetails.groupCode', 'like', "%$searchValue%");
                });
            });
        }
        
        $totalRecordswithFilter     = $query->count();
        $records                    = $query->orderBy($columnName, $columnSortOrder)->skip($start)->take($rowperpage)->get();
        $data_arr                   = [];
        
        // View Working
        $i                  = 1;
        $flights_count      = 0;
        $hotel_count        = 0;
        $transfer_count     = 0;
        $visa_count         = 0;
        $all_services_count = 0;
        
        if($records->isEmpty()){
        }else{
            $records   = $this->seasonDetailsCheck($records,$request);
        }
        
        foreach ($records as $value) {
            $check_Hotel        = false;
            $flight_available   = false;
            $service_Type       = '';
            $services           = json_decode($value->services);
            if(isset($services)){
                foreach($services as $services_res){
                    if($services_res == '1'){
                        $flights_count++;
                        $hotel_count++;
                        $transfer_count++;
                        $visa_count++;
                        $all_services_count++;
                        $service_Type       = 'All Services';
                    }
                    
                    if($services_res == 'accomodation_tab'){
                        $check_Hotel        = true;
                        $hotel_count++;
                        $service_Type       .= 'Hotel' . ",";
                    }
                    if($services_res == 'transportation_tab'){
                        $transfer_count++;
                        $service_Type       .= 'Transfer' . ",";
                    }
                    if($services_res == 'flights_tab'){
                        $flight_available   = true;
                        $flights_count++;
                        $service_Type       .= 'Flight' . ",";
                    }
                    if($services_res == 'visa_tab'){
                        $visa_count++;
                        $service_Type       .= 'Visa' . ",";
                    }
                }
                $service_Type = rtrim($service_Type, ",");
            }
            
            $currency           = $value->currency_symbol;
            
            if(isset($value->total_sale_price_all_Services) && !empty($value->total_sale_price_all_Services)){
                $total_Payable = $value->total_sale_price_all_Services;
            }else{
                $total_Payable = 0;
            }
            
            $agent_name = '';
            if(isset($value->b2b_Agent_Company_Name) && $value->b2b_Agent_Company_Name > '1'){
                $agent_name = $value->b2b_Agent_Company_Name ?? '' .'<b>(B2BAgent)</b>';
            }elseif($value->booking_customer_id == '-1' || $value->booking_customer_id == 0 || $value->booking_customer_id == '' || $value->booking_customer_id == null){
                $agent_name = $value->agent_Company_Name ?? '' .'<b>(Agent)</b>';
            }else{
                $booking_customers = DB::table('booking_customers')->where('id', $value->booking_customer_id)->first();
                if($booking_customers != null){
                    $agent_name = $booking_customers->name ?? '' .'<b>(Customer)</b>';
                } 
            }
            
            if($value->total_discount_price_all_Services != null && $value->total_discount_price_all_Services != '' && $value->total_discount_price_all_Services != 'null'){
                '<del>'.$currency.' '.$value->total_discount_price_all_Services.'</del><br>';
                $currency.' '.$total_Payable;
            }else{
                $currency.' '.$total_Payable;
            }
            
            // token_UmrahShop
            // mail_From_Dashboard_UmrahShop
            $dashboardURL   = '';
            $editRoute      = '';
            $deleteRoute    = '';
            $userData       = DB::table('customer_subcriptions')->where('id', $request->customer_id)->first();
            if($userData->Auth_key == config('token_UmrahShop')){
                $dashboardURL = config('mail_From_Dashboard_UmrahShop');
            }
            
            if($service_Type != '-1'){
                $edit_route     = $dashboardURL.'/edit_Invoices/'.$value->id;
                $deleteRoute    = $dashboardURL.'/super_admin/delete_invoice/'.$value->id;
            }else{
                $edit_route     = $dashboardURL.'/edit_Invoices_CP/'.$value->id;
                $deleteRoute    = $dashboardURL.'/super_admin/delete_invoice/'.$value->id;
            }
            $viewRoute          = $dashboardURL.'/invoice_Agent/'.$value->id;
            // View Working
            
            $otherPax       = $value->otherPax;
            if ($otherPax->isEmpty()) {
                $action = '<ul class="action">
                                <a class="btn btn-primary" href="'. $viewRoute .'" target="_blank">View</a>
                                <a class="btn btn-primary" href="'. $edit_route .'" target="_blank">Edit</a>
                                <a class="btn btn-primary" href="'. $deleteRoute .'" onclick="return confirm(\'Are you sure you want to delete?\');">Delete</a>
                            </ul>';
            } else {
                $otherPaxHtml = '<div class="container p-2">';
                foreach ($otherPax as $index => $stop) {
                    $otherPaxHtml .=    '<div class="card mb-3 shadow-sm border border-primary">
                                            <div class="card-body">
                                                <h5 class="card-title text-center text-primary">
                                                    <i class="fas fa-user-friends"></i> Passenger #' . ($index + 1) . '
                                                </h5>
                                                <hr>
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <p><i class="fas fa-user text-secondary"></i> <strong>Name:</strong> ' . $stop->pilgramsName . '</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><i class="fas fa-birthday-cake text-secondary"></i> <strong>Date of Birth:</strong> ' . \Carbon\Carbon::parse($stop->dateOfBirth)->format('d-F-Y') . '</p>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <p><i class="fas fa-passport text-secondary"></i> <strong>Passport Number:</strong> ' . $stop->passportNumber . '</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><i class="fas fa-calendar-check text-secondary"></i> <strong>Issue Date:</strong> ' . \Carbon\Carbon::parse($stop->issueDate)->format('d-F-Y') . '</p>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <p><i class="fas fa-qrcode text-secondary"></i> <strong>Group Code:</strong> ' . $stop->groupCode . '</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><i class="fas fa-layer-group text-secondary"></i> <strong>Group Type:</strong> ' . $stop->groupType . '</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                }
                $otherPaxHtml .= '</div>';
                
                $action = '<ul class="action">
                                <a href="#" class="btn btn-info view-stops" data-id="' . $value->id . '" style="margin-bottom: 5px;">View Other Pax Details</a><br>
                                <a class="btn btn-primary" href="'. $viewRoute .'" target="_blank">View</a>
                                <a class="btn btn-primary" href="'. $edit_route .'" target="_blank">Edit</a>
                                <a class="btn btn-primary" href="'. $deleteRoute .'" onclick="return confirm(\'Are you sure you want to delete?\');">Delete</a>
                            </ul>
                            <div class="d-none stops-html" id="stops-' . $value->id . '">' . $otherPaxHtml . '</div>';
            }
            
            $data_arr[]             = [
                "id"                => $i,
                "generate_id"       => '<a href="'.$viewRoute.'" target="_blank">'.$value->generate_id.'</a>',
                "invoice_type"      => $service_Type,
                "agent_name"        => $agent_name,
                "lead_name"         => $value->f_name.' '.$value->middle_name,
                "total_payable"     => $total_Payable,
                "created_at"        => \Carbon\Carbon::parse($value->created_at)->format('d-F-Y'),
                "action"            => $action
            ];
            
            $i++;
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ];
        
        return response()->json($response);
    }
}