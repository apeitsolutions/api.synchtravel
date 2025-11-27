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
use App\Models\addAgentsEnquiry;
use DB;
use Carbon\Carbon;

class ManageAgentEnquiryController extends Controller
{
    // Enquiry
    public function view_Agents_Enquiry(Request $request){
        $all_countries      = country::all();
        $manage_currencies  = DB::table('mange_currencies')->where('customer_id',$request->customer_id)->get();
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            if($request->agent_Id != null){
                $enquiry_Details    = DB::table('addAgentsEnquiry')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('agent_Id',$request->agent_Id)->orderBy('id','DESC')->get();
            }else{
                $enquiry_Details    = DB::table('addAgentsEnquiry')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->orderBy('id','DESC')->get();
            }
        }else{
            $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
            if($request->agent_Id != null){
                $enquiry_Details    = DB::table('addAgentsEnquiry')->where('customer_id',$request->customer_id)->where('agent_Id',$request->agent_Id)->orderBy('id','DESC')->get();
            }else{
                $enquiry_Details    = DB::table('addAgentsEnquiry')->where('customer_id',$request->customer_id)->orderBy('id','DESC')->get();
            }
        }
        return response()->json(['message'=>'success','enquiry_Details'=>$enquiry_Details,'all_countries'=>$all_countries,'manage_currencies'=>$manage_currencies,'supplier_detail'=>$supplier_detail]);
    }
    
    public function view_Agents_Enquiry_Quotations(Request $request){
        $all_countries      = country::all();
        $booking_customers  = DB::table('booking_customers')->where('customer_id',$request->customer_id)->get();
        $Agents_detail      = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
        $enquiry_Quotations = DB::table('addManageQuotationPackage')->where('customer_id',$request->customer_id)->where('agent_Id',$request->agent_Id)->orderBy('id', 'DESC')->get();
        return response()->json([
            'message'               => 'success',
            'enquiry_Quotations'    => $enquiry_Quotations, 
            'all_countries'         => $all_countries,
            'booking_customers'     => $booking_customers,
            'Agents_detail'         => $Agents_detail,
        ]);
    }
    
    public function view_Agents_Enquiry_Invoices(Request $request){
        $enquiry_Invoices   = DB::table('add_manage_invoices')->where('customer_id',$request->customer_id)->where('agent_Id',$request->agent_Id)->orderBy('id', 'DESC')->get();
        $confirm_quotations = DB::table('addManageQuotationPackage')->where('customer_id',$request->customer_id)->where('agent_Id',$request->agent_Id)->where('quotation_status',1)->orderBy('id', 'DESC')->get();
        $all_countries      = country::all();
        $booking_customers  = DB::table('booking_customers')->where('customer_id',$request->customer_id)->get();
        $agents_Detail      = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
        $documents_Detail   = DB::table('uploadDocumentInvoice')->where('customer_id',$request->customer_id)->get();
        $groups_Detail      = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)->get();
        return response()->json([
            'enquiry_Invoices'      => $enquiry_Invoices,
            'confirm_quotations'    => $confirm_quotations,
            'all_countries'         => $all_countries,
            'booking_customers'     => $booking_customers,
            'agents_Detail'         => $agents_Detail,
            'documents_Detail'      => $documents_Detail,
            'groups_Detail'         => $groups_Detail,
        ]); 
    }
    
    public function create_Agents_Enquiry(Request $request){
        $customer_id                = $request->customer_id;
        $all_countries              = country::all();
        $all_countries_currency     = country::all();
        $all_curr_symboles          = country::all();
        $customer                   = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $currency_Symbol            = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $categories                 = DB::table('categories')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $attributes                 = DB::table('attributes')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $bir_airports               = DB::table('bir_airports')->get();
            $payment_gateways           = DB::table('payment_gateways')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $payment_modes              = DB::table('payment_modes')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            // $Agents_detail              = DB::table('Agents_detail')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            $agent_Slots                = DB::table('agent_Slots')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            $user_hotels                = Hotels::where('owner_id',$customer_id)->where('SU_id',$request->SU_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            // $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $customers_data             = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
            $all_flight_routes          = DB::table('flight_rute')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $flight_suppliers           = DB::table('supplier')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $visa_supplier              = DB::table('visa_Sup')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $visa_types                 = DB::table('visa_types')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_vehicle            = DB::table('tranfer_vehicle')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_destination        = DB::table('tranfer_destination')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_company            = DB::table('transfer_Invoice_Company')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $vehicle_category           = DB::table('vehicle_category')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $groups_Detail              = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            if($request->id != null){
                $agent_Detail           = DB::table('Agents_detail')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            }else{
                $agent_Detail           = DB::table('Agents_detail')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            }
        }else{
            $categories                 = DB::table('categories')->where('customer_id',$customer_id)->get();
            $attributes                 = DB::table('attributes')->where('customer_id',$customer_id)->get();
            $bir_airports               = DB::table('bir_airports')->get();
            $payment_gateways           = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
            $payment_modes              = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
            // $Agents_detail              = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
            $agent_Slots                = DB::table('agent_Slots')->where('customer_id',$request->customer_id)->get();
            $user_hotels                = Hotels::where('owner_id',$customer_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
            // $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $customers_data             = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
            $all_flight_routes          = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
            $flight_suppliers           = DB::table('supplier')->where('customer_id',$customer_id)->get();
            $visa_supplier              = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
            $visa_types                 = DB::table('visa_types')->where('customer_id',$customer_id)->get();
            $tranfer_vehicle            = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
            $tranfer_destination        = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $tranfer_company            = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
            $vehicle_category           = DB::table('vehicle_category')->where('customer_id',$customer_id)->get();
            $groups_Detail              = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)->get();
            if($request->id != null){
                $agent_Detail           = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            }else{
                $agent_Detail           = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
            }
        }
        
        $Agents_detail                  = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
        $supplier_detail                = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        
        // dd($agent_Detail);
        
        return response()->json(['message'=>'success','agent_Detail'=>$agent_Detail,'agent_Slots'=>$agent_Slots,'groups_Detail'=>$groups_Detail,'vehicle_category'=>$vehicle_category,'tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=> $tranfer_destination,'tranfer_supplier'=> $tranfer_supplier,'tranfer_company'=>$tranfer_company,'mange_currencies'=>$mange_currencies,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function add_Agents_Enquiry(Request $req){
        
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
        
        function getBetweenDates($startDate, $endDate){
            $rangArray = [];
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
                 
                 $startDate += (86400); 
            for ($currentDate = $startDate; $currentDate <= $endDate; 
                                            $currentDate += (86400)) {
                                                    
                $date = date('Y-m-d', $currentDate);
                $rangArray[] = $date;
            }
      
            return $rangArray;
        }
       
        DB::beginTransaction();
        try {
            
            $insert = new addAgentsEnquiry();
            //new additon
            //1
            $insert->customer_id             = $req->customer_id;
            
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $insert->SU_id                   = $req->SU_id;
            }
            
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
            $insert->city_Count              = $req->city_Count;
            
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            
            $insert->passport_Image          = $req->passport_Image ?? '';
            
            // Lead Pass
            $insert->lead_title             = $req->lead_title ?? '';
            $insert->lead_fname             = $req->first_Name_passport ?? '';
            $insert->lead_lname             = $req->last_Name_passport ?? '';
            $insert->lead_gender            = $req->gender ?? '';
            $insert->lead_dob               = $req->date_of_birth_passport ?? '';
            $insert->lead_nationality       = $req->nationality_passport ?? '';
            $insert->lead_passport_number   = $req->passport_Number ?? '';
            $insert->lead_passport_expiry   = $req->passport_Expiry ?? '';
            $insert->more_Passenger_Data    = $req->more_Passenger_Data ?? '';
            $insert->count_P_Input          = $req->count_P_Input ?? '';
            // Lead Pass
            
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            $insert->passengerDetailAdults   = $req->passengerDetailAdults;
            $insert->passengerDetailChilds   = $req->passengerDetailChilds;
            $insert->passengerDetailInfant   = $req->passengerDetailInfant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            
            $insert->flight_route_type              = $req->flight_route_type;
            $insert->flight_supplier                = $req->flight_supplier;
            $insert->flights_details                = $req->flights_details;
            $insert->return_flights_details         = $req->return_flights_details;
            $insert->flights_Pax_details            = $req->flights_Pax_details;
            
            // $accomodation_details = json_decode($req->accomodation_details);
            // dd($accomodation_details);
            
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services = $req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services = $req->total_cost_price_all_Services;
            
            $insert->adultfinalqty              = $req->adultfinalqty ?? ""; 
            $insert->adultPerprice              = $req->adultPerprice ?? ""; 
            $insert->adult_total_with_markup    = $req->adult_total_with_markup ?? ""; 
            $insert->adult_markup_price         = $req->adult_markup_price ?? ""; 
            $insert->childfinalqty              = $req->childfinalqty ?? ""; 
            $insert->childPerprice              = $req->childPerprice ?? ""; 
            $insert->child_total_with_markup    = $req->child_total_with_markup ?? ""; 
            $insert->infantfinalqty             = $req->infantfinalqty ?? ""; 
            $insert->infantPerprice             = $req->infantPerprice ?? ""; 
            $insert->infant_total_with_markup   = $req->infant_total_with_markup ?? ""; 
            
            $insert->adult_markup_price1        = $req->adult_markup_price1 ?? ""; 
            $insert->total_markup_for_child     = $req->total_markup_for_child ?? ""; 
            $insert->total_markup_for_infant    = $req->total_markup_for_infant ?? ""; 
            
            $insert->transfer_supplier          = $req->transfer_supplier;
            $insert->transfer_supplier_id       = $req->transfer_supplier_id;
            
            $insert->quotation_id               = $req->quotation_id ?? '';
            $insert->quotation_Invoice          = $req->quotation_Invoice ?? '';
            
            // $insert->hotel_Reservation_details  = $req->hotel_Reservation_details ?? '';
            // $insert->transfer_Reservation_No    = $req->transfer_Reservation_No ?? '';
            // $insert->flight_Reservation_No      = $req->flight_Reservation_No ?? '';
            // $insert->visa_Reservation_No        = $req->visa_Reservation_No ?? '';
            
            $insert->ziyarat_details                = $req->ziyarat_details ?? '';
            
            // $insert->lead_currency              = $req->lead_currency ?? '';
            
            // $insert->currency_Rate_AC           = $req->currency_Rate_AC ?? '';
            // $insert->currency_Type_AC           = $req->currency_Type_AC ?? '';
            // $insert->currency_value_AC          = $req->currency_value_AC ?? '';
            // $insert->total_cost_price_AC        = $req->total_cost_price_AC ?? '';
            // $insert->total_sale_price_AC        = $req->total_sale_price_AC ?? '';
            
            // $insert->currency_Rate_Company      = $req->currency_Rate_Company ?? '';
            // $insert->currency_Type_Company      = $req->currency_Type_Company ?? '';
            // $insert->currency_value_Company     = $req->currency_value_Company ?? '';
            // $insert->total_cost_price_Company   = $req->total_cost_price_Company ?? '';
            // $insert->total_sale_price_Company   = $req->total_sale_price_Company ?? '';
            
            // $insert->groups_id                  = $req->groups_id ?? '';
            // $insert->agent_Currency             = $req->agent_Currency ?? '';
            // $insert->agent_Currency_all_details = $req->agent_Currency_all_details ?? '';
            
            $insert->city_Details               = $req->city_Details;
            $insert->hotel_supplier_multiple    = $req->hotel_supplier_multiple;
            
            $insert->group_list_API             = $req->group_list_API;
            $insert->selected_GLAPI             = $req->selected_GLAPI ?? '';
            
            $insert->enquiry_Ref_No             = $req->enquiry_Ref_No;
            
            // $country_code       = $req->country;
            // $all_countries      = country::all();
            // foreach($all_countries as $val_country){
            //     if($val_country->id == $req->country){
            //         $country_code = $val_country->iso2;
            //     }
            // }
            // $generate_RN                        = rand(0,9999);
            // $enquiry_Ref_No                     = 'TT-'.$country_code.'-'.$generate_RN.'-ENQ';
            // $insert->enquiry_Ref_No             = $enquiry_Ref_No;
            
            $insert->save();
            
            $invoice_id                         = $insert->id;
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Agent Enquiry added Succesfully']); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function add_Agents_Enquiry_Next(Request $req){
        
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
        
        function getBetweenDates($startDate, $endDate){
            $rangArray = [];
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
                 
                 $startDate += (86400); 
            for ($currentDate = $startDate; $currentDate <= $endDate; 
                                            $currentDate += (86400)) {
                                                    
                $date = date('Y-m-d', $currentDate);
                $rangArray[] = $date;
            }
      
            return $rangArray;
        }
       
        DB::beginTransaction();
        try {
            
            $insert = new addAgentsEnquiry();
            //new additon
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
            $insert->city_Count              = $req->city_Count;
        
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            
            $insert->passport_Image          = $req->passport_Image ?? '';
            
            // Lead Pass
            $insert->lead_title             = $req->lead_title ?? '';
            $insert->lead_fname             = $req->first_Name_passport ?? '';
            $insert->lead_lname             = $req->last_Name_passport ?? '';
            $insert->lead_gender            = $req->gender ?? '';
            $insert->lead_dob               = $req->date_of_birth_passport ?? '';
            $insert->lead_nationality       = $req->nationality_passport ?? '';
            $insert->lead_passport_number   = $req->passport_Number ?? '';
            $insert->lead_passport_expiry   = $req->passport_Expiry ?? '';
            $insert->more_Passenger_Data    = $req->more_Passenger_Data ?? '';
            $insert->count_P_Input          = $req->count_P_Input ?? '';
            // Lead Pass
            
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            $insert->passengerDetailAdults   = $req->passengerDetailAdults;
            $insert->passengerDetailChilds   = $req->passengerDetailChilds;
            $insert->passengerDetailInfant   = $req->passengerDetailInfant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            
            $insert->flight_route_type              = $req->flight_route_type;
            $insert->flight_supplier                = $req->flight_supplier;
            $insert->flights_details                = $req->flights_details;
            $insert->return_flights_details         = $req->return_flights_details;
            $insert->flights_Pax_details            = $req->flights_Pax_details;
            
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services = $req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services = $req->total_cost_price_all_Services;
            
            $insert->adultfinalqty              = $req->adultfinalqty ?? ""; 
            $insert->adultPerprice              = $req->adultPerprice ?? ""; 
            $insert->adult_total_with_markup    = $req->adult_total_with_markup ?? ""; 
            $insert->adult_markup_price         = $req->adult_markup_price ?? ""; 
            $insert->childfinalqty              = $req->childfinalqty ?? ""; 
            $insert->childPerprice              = $req->childPerprice ?? ""; 
            $insert->child_total_with_markup    = $req->child_total_with_markup ?? ""; 
            $insert->infantfinalqty             = $req->infantfinalqty ?? ""; 
            $insert->infantPerprice             = $req->infantPerprice ?? ""; 
            $insert->infant_total_with_markup   = $req->infant_total_with_markup ?? ""; 
            
            $insert->adult_markup_price1        = $req->adult_markup_price1 ?? ""; 
            $insert->total_markup_for_child     = $req->total_markup_for_child ?? ""; 
            $insert->total_markup_for_infant    = $req->total_markup_for_infant ?? ""; 
            
            $insert->transfer_supplier          = $req->transfer_supplier;
            $insert->transfer_supplier_id       = $req->transfer_supplier_id;
            
            $insert->quotation_id               = $req->quotation_id ?? '';
            $insert->quotation_Invoice          = $req->quotation_Invoice ?? '';
            
            // $insert->hotel_Reservation_details  = $req->hotel_Reservation_details ?? '';
            // $insert->transfer_Reservation_No    = $req->transfer_Reservation_No ?? '';
            // $insert->flight_Reservation_No      = $req->flight_Reservation_No ?? '';
            // $insert->visa_Reservation_No        = $req->visa_Reservation_No ?? '';
            
            $insert->ziyarat_details            = $req->ziyarat_details ?? '';
            
            // $insert->lead_currency              = $req->lead_currency ?? '';
            
            // $insert->currency_Rate_AC           = $req->currency_Rate_AC ?? '';
            // $insert->currency_Type_AC           = $req->currency_Type_AC ?? '';
            // $insert->currency_value_AC          = $req->currency_value_AC ?? '';
            // $insert->total_cost_price_AC        = $req->total_cost_price_AC ?? '';
            // $insert->total_sale_price_AC        = $req->total_sale_price_AC ?? '';
            
            // $insert->currency_Rate_Company      = $req->currency_Rate_Company ?? '';
            // $insert->currency_Type_Company      = $req->currency_Type_Company ?? '';
            // $insert->currency_value_Company     = $req->currency_value_Company ?? '';
            // $insert->total_cost_price_Company   = $req->total_cost_price_Company ?? '';
            // $insert->total_sale_price_Company   = $req->total_sale_price_Company ?? '';
            
            // $insert->groups_id                  = $req->groups_id ?? '';
            // $insert->agent_Currency             = $req->agent_Currency ?? '';
            // $insert->agent_Currency_all_details = $req->agent_Currency_all_details ?? '';
            
            $insert->city_Details               = $req->city_Details;
            $insert->hotel_supplier_multiple    = $req->hotel_supplier_multiple;
            
            $insert->group_list_API             = $req->group_list_API ?? '';
            $insert->selected_GLAPI             = $req->selected_GLAPI ?? '';
            
            $country_code       = $req->country;
            $all_countries      = country::all();
            foreach($all_countries as $val_country){
                if($val_country->id == $req->country){
                    $country_code = $val_country->iso2;
                }
            }
            $generate_RN                        = rand(0,9999);
            $enquiry_Ref_No                     = 'TT-'.$country_code.'-'.$generate_RN.'-ENQ';
            $insert->enquiry_Ref_No             = $enquiry_Ref_No;
            
            $insert->save();
            
            $invoice_id                         = $insert->id;
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Agent Enquiry added Succesfully','invoice_id'=>$invoice_id]); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function proceed_Agents_Enquiry(Request $req){
        $customer_id                    = $req->customer_id;
        $categories                     = DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes                     = DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer                       = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries                  = country::all();
        $all_countries_currency         = country::all();
        $bir_airports                   = DB::table('bir_airports')->get();
        $payment_gateways               = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes                  = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol                = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail                  = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $mange_currencies               = DB::table('mange_currencies')->where('customer_id',$req->customer_id)->get();
        $user_hotels                    = Hotels::where('owner_id',$customer_id)->get();
        $get_invoice                    = addAgentsEnquiry::where('id',$req->id)->first();
        
        $visa_type                      = DB::table('visa_types')->where('customer_id',$customer_id)->get();
        
        $supplier_detail                = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        
        $tranfer_supplier               = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        // $destination_details            = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $customers_data                 = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
        
        $all_curr_symboles              = country::all();
        
        $all_flight_routes              = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
        $flight_suppliers               = DB::table('supplier')->where('customer_id',$customer_id)->get();
        
        $visa_supplier                  = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
        $visa_types                     = DB::table('visa_types')->where('customer_id',$customer_id)->get();
        
        $tranfer_vehicle                = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
        $tranfer_destination            = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $tranfer_company                = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
        
        return response()->json(['message'=>'success','tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=>$tranfer_destination,'tranfer_company'=>$tranfer_company,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'all_flight_routes'=>$all_flight_routes,'flight_suppliers'=>$flight_suppliers,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'visa_type'=>$visa_type,'get_invoice'=>$get_invoice,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);        
   }
   
    public function edit_Agents_Enquiry(Request $request){
        $customer_id                = $request->customer_id;
        $all_countries              = country::all();
        $all_countries_currency     = country::all();
        $all_curr_symboles          = country::all();
        $customer                   = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $currency_Symbol            = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $categories                 = DB::table('categories')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $attributes                 = DB::table('attributes')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $bir_airports               = DB::table('bir_airports')->get();
            $payment_gateways           = DB::table('payment_gateways')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $payment_modes              = DB::table('payment_modes')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $Agents_detail              = DB::table('Agents_detail')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            $agent_Slots                = DB::table('agent_Slots')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            $user_hotels                = Hotels::where('owner_id',$customer_id)->where('SU_id',$request->SU_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $customers_data             = DB::table('booking_customers')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $all_flight_routes          = DB::table('flight_rute')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $flight_suppliers           = DB::table('supplier')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $visa_supplier              = DB::table('visa_Sup')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $visa_types                 = DB::table('visa_types')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_vehicle            = DB::table('tranfer_vehicle')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_destination        = DB::table('tranfer_destination')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $tranfer_company            = DB::table('transfer_Invoice_Company')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $vehicle_category           = DB::table('vehicle_category')->where('SU_id',$request->SU_id)->where('customer_id',$customer_id)->get();
            $groups_Detail              = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            $agent_Detail               = DB::table('Agents_detail')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            $agent_Enquiry              = DB::table('addAgentsEnquiry')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            if($agent_Enquiry->agent_Id != null && $agent_Enquiry->agent_Id != ''){
                $agent_Detail           = DB::table('Agents_detail')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->where('id',$agent_Enquiry->agent_Id)->first();    
            }
        }else{
            $categories                 = DB::table('categories')->where('customer_id',$customer_id)->get();
            $attributes                 = DB::table('attributes')->where('customer_id',$customer_id)->get();
            $bir_airports               = DB::table('bir_airports')->get();
            $payment_gateways           = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
            $payment_modes              = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
            $Agents_detail              = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
            $agent_Slots                = DB::table('agent_Slots')->where('customer_id',$request->customer_id)->get();
            $user_hotels                = Hotels::where('owner_id',$customer_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
            $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $customers_data             = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
            $all_flight_routes          = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
            $flight_suppliers           = DB::table('supplier')->where('customer_id',$customer_id)->get();
            $visa_supplier              = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
            $visa_types                 = DB::table('visa_types')->where('customer_id',$customer_id)->get();
            $tranfer_vehicle            = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
            $tranfer_destination        = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $tranfer_company            = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
            $vehicle_category           = DB::table('vehicle_category')->where('customer_id',$customer_id)->get();
            $groups_Detail              = DB::table('addGroupsdetails')->where('customer_id',$request->customer_id)->get();
            $agent_Detail               = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            $agent_Enquiry              = DB::table('addAgentsEnquiry')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            if($agent_Enquiry->agent_Id != null && $agent_Enquiry->agent_Id != ''){
                $agent_Detail           = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('id',$agent_Enquiry->agent_Id)->first();    
            }
        }
        
        return response()->json(['message'=>'success','agent_Enquiry'=>$agent_Enquiry,'agent_Detail'=>$agent_Detail,'agent_Slots'=>$agent_Slots,'groups_Detail'=>$groups_Detail,'vehicle_category'=>$vehicle_category,'tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=> $tranfer_destination,'tranfer_supplier'=> $tranfer_supplier,'tranfer_company'=>$tranfer_company,'mange_currencies'=>$mange_currencies,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function update_Agents_Enquiry_Next(Request $req){
        
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
        
        function getBetweenDates($startDate, $endDate){
            $rangArray  = [];
            $startDate  = strtotime($startDate);
            $endDate    = strtotime($endDate);
            $startDate += (86400); 
            for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                $date           = date('Y-m-d', $currentDate);
                $rangArray[]    = $date;
            }
            return $rangArray;
        }
       
        DB::beginTransaction();
        try {
            $id     = $req->id;
            $insert = addAgentsEnquiry::find($id);
            //new additon
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
            $insert->city_Count              = $req->city_Count;
        
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            
            $insert->passport_Image          = $req->passport_Image ?? '';
            
            // Lead Pass
            $insert->lead_title             = $req->lead_title ?? '';
            $insert->lead_fname             = $req->first_Name_passport ?? '';
            $insert->lead_lname             = $req->last_Name_passport ?? '';
            $insert->lead_gender            = $req->gender ?? '';
            $insert->lead_dob               = $req->date_of_birth_passport ?? '';
            $insert->lead_nationality       = $req->nationality_passport ?? '';
            $insert->lead_passport_number   = $req->passport_Number ?? '';
            $insert->lead_passport_expiry   = $req->passport_Expiry ?? '';
            $insert->more_Passenger_Data    = $req->more_Passenger_Data ?? '';
            $insert->count_P_Input          = $req->count_P_Input ?? '';
            // Lead Pass
            
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            $insert->passengerDetailAdults   = $req->passengerDetailAdults;
            $insert->passengerDetailChilds   = $req->passengerDetailChilds;
            $insert->passengerDetailInfant   = $req->passengerDetailInfant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            
            $insert->flight_route_type              = $req->flight_route_type;
            $insert->flight_supplier                = $req->flight_supplier;
            $insert->flights_details                = $req->flights_details;
            $insert->return_flights_details         = $req->return_flights_details;
            $insert->flights_Pax_details            = $req->flights_Pax_details;
            
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services = $req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services = $req->total_cost_price_all_Services;
            
            $insert->adultfinalqty              = $req->adultfinalqty ?? ""; 
            $insert->adultPerprice              = $req->adultPerprice ?? ""; 
            $insert->adult_total_with_markup    = $req->adult_total_with_markup ?? ""; 
            $insert->adult_markup_price         = $req->adult_markup_price ?? ""; 
            $insert->childfinalqty              = $req->childfinalqty ?? ""; 
            $insert->childPerprice              = $req->childPerprice ?? ""; 
            $insert->child_total_with_markup    = $req->child_total_with_markup ?? ""; 
            $insert->infantfinalqty             = $req->infantfinalqty ?? ""; 
            $insert->infantPerprice             = $req->infantPerprice ?? ""; 
            $insert->infant_total_with_markup   = $req->infant_total_with_markup ?? ""; 
            
            $insert->adult_markup_price1        = $req->adult_markup_price1 ?? ""; 
            $insert->total_markup_for_child     = $req->total_markup_for_child ?? ""; 
            $insert->total_markup_for_infant    = $req->total_markup_for_infant ?? ""; 
            
            $insert->transfer_supplier          = $req->transfer_supplier;
            $insert->transfer_supplier_id       = $req->transfer_supplier_id;
            
            $insert->quotation_id               = $req->quotation_id ?? '';
            $insert->quotation_Invoice          = $req->quotation_Invoice ?? '';
            
            // $insert->hotel_Reservation_details  = $req->hotel_Reservation_details ?? '';
            // $insert->transfer_Reservation_No    = $req->transfer_Reservation_No ?? '';
            // $insert->flight_Reservation_No      = $req->flight_Reservation_No ?? '';
            // $insert->visa_Reservation_No        = $req->visa_Reservation_No ?? '';
            
            $insert->ziyarat_details            = $req->ziyarat_details ?? '';
            
            // $insert->lead_currency              = $req->lead_currency ?? '';
            
            // $insert->currency_Rate_AC           = $req->currency_Rate_AC ?? '';
            // $insert->currency_Type_AC           = $req->currency_Type_AC ?? '';
            // $insert->currency_value_AC          = $req->currency_value_AC ?? '';
            // $insert->total_cost_price_AC        = $req->total_cost_price_AC ?? '';
            // $insert->total_sale_price_AC        = $req->total_sale_price_AC ?? '';
            
            // $insert->currency_Rate_Company      = $req->currency_Rate_Company ?? '';
            // $insert->currency_Type_Company      = $req->currency_Type_Company ?? '';
            // $insert->currency_value_Company     = $req->currency_value_Company ?? '';
            // $insert->total_cost_price_Company   = $req->total_cost_price_Company ?? '';
            // $insert->total_sale_price_Company   = $req->total_sale_price_Company ?? '';
            
            // $insert->groups_id                  = $req->groups_id ?? '';
            // $insert->agent_Currency             = $req->agent_Currency ?? '';
            // $insert->agent_Currency_all_details = $req->agent_Currency_all_details ?? '';
            
            $insert->city_Details               = $req->city_Details;
            $insert->hotel_supplier_multiple    = $req->hotel_supplier_multiple;
            
            $insert->group_list_API             = $req->group_list_API ?? '';
            $insert->selected_GLAPI             = $req->selected_GLAPI ?? '';
            
            $country_code       = $req->country;
            $all_countries      = country::all();
            foreach($all_countries as $val_country){
                if($val_country->id == $req->country){
                    $country_code = $val_country->iso2;
                }
            }
            $generate_RN                        = rand(0,9999);
            $enquiry_Ref_No                     = 'TT-'.$country_code.'-'.$generate_RN.'-ENQ';
            $insert->enquiry_Ref_No             = $enquiry_Ref_No;
            
            $insert->update();
            
            $invoice_id                         = DB::table('addAgentsEnquiry')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Agent Enquiry added Succesfully','invoice_id'=>$invoice_id]); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function update_Agents_Enquiry(Request $req){
        
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
        
        function getBetweenDates($startDate, $endDate){
            $rangArray  = [];
            $startDate  = strtotime($startDate);
            $endDate    = strtotime($endDate);
            $startDate += (86400); 
            for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                $date           = date('Y-m-d', $currentDate);
                $rangArray[]    = $date;
            }
            return $rangArray;
        }
       
        DB::beginTransaction();
        try {
            $id     = $req->id;
            $insert = addAgentsEnquiry::find($id);
            //new additon
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
            $insert->city_Count              = $req->city_Count;
        
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            
            $insert->passport_Image          = $req->passport_Image ?? '';
            
            // Lead Pass
            $insert->lead_title             = $req->lead_title ?? '';
            $insert->lead_fname             = $req->first_Name_passport ?? '';
            $insert->lead_lname             = $req->last_Name_passport ?? '';
            $insert->lead_gender            = $req->gender ?? '';
            $insert->lead_dob               = $req->date_of_birth_passport ?? '';
            $insert->lead_nationality       = $req->nationality_passport ?? '';
            $insert->lead_passport_number   = $req->passport_Number ?? '';
            $insert->lead_passport_expiry   = $req->passport_Expiry ?? '';
            $insert->more_Passenger_Data    = $req->more_Passenger_Data ?? '';
            $insert->count_P_Input          = $req->count_P_Input ?? '';
            // Lead Pass
            
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            $insert->passengerDetailAdults   = $req->passengerDetailAdults;
            $insert->passengerDetailChilds   = $req->passengerDetailChilds;
            $insert->passengerDetailInfant   = $req->passengerDetailInfant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            
            $insert->flight_route_type              = $req->flight_route_type;
            $insert->flight_supplier                = $req->flight_supplier;
            $insert->flights_details                = $req->flights_details;
            $insert->return_flights_details         = $req->return_flights_details;
            $insert->flights_Pax_details            = $req->flights_Pax_details;
            
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services = $req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services = $req->total_cost_price_all_Services;
            
            $insert->adultfinalqty              = $req->adultfinalqty ?? ""; 
            $insert->adultPerprice              = $req->adultPerprice ?? ""; 
            $insert->adult_total_with_markup    = $req->adult_total_with_markup ?? ""; 
            $insert->adult_markup_price         = $req->adult_markup_price ?? ""; 
            $insert->childfinalqty              = $req->childfinalqty ?? ""; 
            $insert->childPerprice              = $req->childPerprice ?? ""; 
            $insert->child_total_with_markup    = $req->child_total_with_markup ?? ""; 
            $insert->infantfinalqty             = $req->infantfinalqty ?? ""; 
            $insert->infantPerprice             = $req->infantPerprice ?? ""; 
            $insert->infant_total_with_markup   = $req->infant_total_with_markup ?? ""; 
            
            $insert->adult_markup_price1        = $req->adult_markup_price1 ?? ""; 
            $insert->total_markup_for_child     = $req->total_markup_for_child ?? ""; 
            $insert->total_markup_for_infant    = $req->total_markup_for_infant ?? ""; 
            
            $insert->transfer_supplier          = $req->transfer_supplier;
            $insert->transfer_supplier_id       = $req->transfer_supplier_id;
            
            $insert->quotation_id               = $req->quotation_id ?? '';
            $insert->quotation_Invoice          = $req->quotation_Invoice ?? '';
            
            // $insert->hotel_Reservation_details  = $req->hotel_Reservation_details ?? '';
            // $insert->transfer_Reservation_No    = $req->transfer_Reservation_No ?? '';
            // $insert->flight_Reservation_No      = $req->flight_Reservation_No ?? '';
            // $insert->visa_Reservation_No        = $req->visa_Reservation_No ?? '';
            
            $insert->ziyarat_details            = $req->ziyarat_details ?? '';
            
            // $insert->lead_currency              = $req->lead_currency ?? '';
            
            // $insert->currency_Rate_AC           = $req->currency_Rate_AC ?? '';
            // $insert->currency_Type_AC           = $req->currency_Type_AC ?? '';
            // $insert->currency_value_AC          = $req->currency_value_AC ?? '';
            // $insert->total_cost_price_AC        = $req->total_cost_price_AC ?? '';
            // $insert->total_sale_price_AC        = $req->total_sale_price_AC ?? '';
            
            // $insert->currency_Rate_Company      = $req->currency_Rate_Company ?? '';
            // $insert->currency_Type_Company      = $req->currency_Type_Company ?? '';
            // $insert->currency_value_Company     = $req->currency_value_Company ?? '';
            // $insert->total_cost_price_Company   = $req->total_cost_price_Company ?? '';
            // $insert->total_sale_price_Company   = $req->total_sale_price_Company ?? '';
            
            // $insert->groups_id                  = $req->groups_id ?? '';
            // $insert->agent_Currency             = $req->agent_Currency ?? '';
            // $insert->agent_Currency_all_details = $req->agent_Currency_all_details ?? '';
            
            $insert->city_Details               = $req->city_Details;
            $insert->hotel_supplier_multiple    = $req->hotel_supplier_multiple;
            
            $insert->group_list_API             = $req->group_list_API ?? '';
            $insert->selected_GLAPI             = $req->selected_GLAPI ?? '';
            
            // $insert->enquiry_Ref_No             = $req->enquiry_Ref_No;
            
            // $country_code       = $req->country;
            // $all_countries      = country::all();
            // foreach($all_countries as $val_country){
            //     if($val_country->id == $req->country){
            //         $country_code = $val_country->iso2;
            //     }
            // }
            // $generate_RN                        = rand(0,9999);
            // $enquiry_Ref_No                     = 'TT-'.$country_code.'-'.$generate_RN.'-ENQ';
            // $insert->enquiry_Ref_No             = $enquiry_Ref_No;
            
            $insert->update();
            
            $invoice_id                         = DB::table('addAgentsEnquiry')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Agent Enquiry Updated Succesfully','invoice_id'=>$invoice_id]); 
            
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function invoice_Agents_Enquiry(Request $request){
        // $all_countries      = country::all();
        // $enquiry_Details    = DB::table('addAgentsEnquiry')->where('customer_id',$request->customer_id)->where('agent_Id',$request->agent_Id)->get();
        // return response()->json(['message'=>'success','enquiry_Details'=>$enquiry_Details,'all_countries'=>$all_countries]);
        
        DB::beginTransaction();
        try {
            $data1                      = addAgentsEnquiry::find($request->id);
            // dd($data1);
            $agent_data                 = DB::table('Agents_detail')->where('id',$data1->agent_Id)->first();
            $customer_data              = DB::table('customer_subcriptions')->where('id',$request->customer_id)->first();
            $contact_details            = DB::table('contact_details')->where('customer_id',$request->customer_id)->first();
            $all_countries              = country::all();
            
            return response()->json([
                'message'                   => 'success',
                'data1'                     => $data1,
                'customer_data'             => $customer_data,
                'contact_details'           => $contact_details,
                'all_countries'             => $all_countries,
                'agent_data'                => $agent_data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
    }
    
    public function add_Reject_Enquiry(Request $req){
        DB::beginTransaction();
        try {
            $id                             = $req->reject_EID;
            $insert                         = addAgentsEnquiry::find($id);
            $insert->reject_Enquiry_Status  = 1;
            $insert->reject_Content         = $req->reject_Content;
            $insert->update();
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Agent Enquiry Rejected Succesfully']); 
            
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function delete_Agents_Enquiry(Request $request){
        DB::beginTransaction();
        try {
            DB::table('addAgentsEnquiry')->where('id', $request->id)->where('customer_id', $request->customer_id)->delete();
            
            DB::commit();
            return response()->json(['message'=>'success']);
        } catch (Throwable $e) {
            // echo "etner i catch ";
                echo $e;
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
    }
}