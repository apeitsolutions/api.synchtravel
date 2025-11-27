<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\addManageQuotation;
use App\Models\addManageQuotationPackage;
use App\Models\viewBooking;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\country;
use DateTime;
use Carbon\Carbon;
use DB;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Agents_detail;
use App\Models\addManageInvoice;
use App\Models\pay_Invoice_Agent;
use App\Models\Tour;
use App\Models\Active;
use App\Models\Activities;
use App\Models\payInvoiceAgent;
use App\Models\rooms_Invoice_Supplier;
use App\Models\alhijaz_Notofication;

class ManageQuotationsControllerApi extends Controller
{
    // Get Flights
    public function get_flight_name(Request $req){
        $thml = '';
        $search = $req->keyword;
        $id = $req->id;
        $airport_pls_code = DB::table('bir_airports')->where('code',$search)->get();
        
        return response()->json([
            'search'           => $search,
            'id'               => $id,
            'airport_pls_code' => $airport_pls_code,
            'thml'             => $thml,
        ]);
        // echo $thml;
   }

    // Manage Quotation
    public function manage_QuotationOLD(Request $req){
        $customer_id            = $req->customer_id;
        $categories             = DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes             = DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer               = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries          = country::all();
        $all_countries_currency = country::all();
        $bir_airports           = DB::table('bir_airports')->get();
        $payment_gateways       = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes          = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol        = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail          = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $user_hotels            = Hotels::where('owner_id',$customer_id)->get();
        return response()->json(['message'=>'success','user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes]);
    }
    
    public function manage_Quotation(Request $req){
        
        $customer_id            = $req->customer_id;
        $categories             = DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes             = DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer               = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries          = country::all();
        $all_countries_currency = country::all();
        $bir_airports           = DB::table('bir_airports')->get();
        $payment_gateways       = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes          = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol        = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail          = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $user_hotels            = Hotels::where('owner_id',$customer_id)->get();
        $mange_currencies       = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
        $supplier_detail        = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $tranfer_supplier       = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $destination_details    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $customers_data         = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
        $all_curr_symboles      = country::all();
        $all_flight_routes      = DB::table('flight_rute')->get();
        $flight_suppliers       = DB::table('supplier')->get();
        
        return response()->json(['message'=>'success','flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'all_curr_symboles'=>$all_curr_symboles,'destination_details'=>$destination_details,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function manage_package_Quotation(Request $req){
        $customer_id            = $req->customer_id;
        $categories             = DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes             = DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer               = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries          = country::all();
        $all_countries_currency = country::all();
        $bir_airports           = DB::table('bir_airports')->get();
        $payment_gateways       = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes          = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol        = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail          = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $user_hotels            = Hotels::where('owner_id',$customer_id)->get();
        $mange_currencies       = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
        $supplier_detail        = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $tranfer_supplier       = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $destination_details    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $customers_data         = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
        $all_curr_symboles      = country::all();
        return response()->json(['message'=>'success','all_curr_symboles'=>$all_curr_symboles,'destination_details'=>$destination_details,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function add_manage_Package_Quotation(Request $req){
        
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
    
        function getBetweenDates($startDate, $endDate){
            $rangArray  = [];
            $startDate  = strtotime($startDate);
            $endDate    = strtotime($endDate);
            $startDate  += (86400); 
            for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                $date = date('Y-m-d', $currentDate);
                $rangArray[] = $date;
            }
      
            return $rangArray;
        }
        
        $insert = new addManageQuotationPackage();
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
        
        DB::beginTransaction();
        try {
            $insert->save();
            $invoice_id                                     = $insert->id;
            $notification_insert                            = new alhijaz_Notofication();
            $notification_insert->type_of_notification_id   = $insert->id ?? ''; 
            $notification_insert->customer_id               = $insert->customer_id ?? ''; 
            $notification_insert->type_of_notification      = 'create_Package_Quotation' ?? ''; 
            $notification_insert->generate_id               = $insert->generate_id ?? '';
            $notification_insert->notification_creator_name = $req->agent_Name ?? '';
            $notification_insert->total_price               = $insert->total_sale_price_all_Services ?? ''; 
            $notification_insert->remaining_price           = $insert->total_sale_price_all_Services ?? ''; 
            $notification_insert->notification_status       = '1' ?? ''; 
            $notification_insert->save();
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Quotation added Succesfully']); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function view_manage_Package_Quotation(Request $req){
        $data1              = addManageQuotationPackage::where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
        $all_countries      = country::all();
        $booking_customers  = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
        return response()->json([
            'data1'             => $data1, 
            'all_countries'     => $all_countries,
            'booking_customers' => $booking_customers,
        ]); 
    }
    
    public function view_manage_Package_Quotation_Single(Request $req){
        $data1              = addManageQuotationPackage::where('customer_id',$req->customer_id)->where('id', $req->id)->first();
        $all_countries      = country::all();
        $booking_customers  = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
        $Agents_detail      = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        return response()->json([
            'data1'             => $data1, 
            'all_countries'     => $all_countries,
            'booking_customers' => $booking_customers,
            'Agents_detail'     => $Agents_detail,
        ]); 
    }
    
    public function edit_manage_Package_Quotation(Request $req){
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
        $b2b_Agents_detail              = DB::table('b2b_agents')->where('token',$req->token)->get();
        
        $mange_currencies               = DB::table('mange_currencies')->where('customer_id',$req->customer_id)->get();
        $user_hotels                    = Hotels::where('owner_id',$customer_id)->get();
        $get_invoice                    = addManageQuotationPackage::where('id',$req->id)->first();
        
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
        
        return response()->json(['message'=>'success','b2b_Agents_detail'=>$b2b_Agents_detail,'tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=>$tranfer_destination,'tranfer_company'=>$tranfer_company,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'all_flight_routes'=>$all_flight_routes,'flight_suppliers'=>$flight_suppliers,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'visa_type'=>$visa_type,'get_invoice'=>$get_invoice,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);        
    }
    
    public function update_manage_Package_Quotation(Request $req){
        $id     = $req->id;
        $insert = addManageQuotationPackage::find($id);
        
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
        
        if($insert){
            
            $prev_acc = $insert->accomodation_details;
            $prev_acc_more = $insert->accomodation_details_more;
            
            $previous_agent = $insert->agent_Id;
            $new_agent = $req->agent_Id;
            
            $previous_customer = $insert->booking_customer_id;
            $new_customer = $req->booking_customer_id;
            
            $previous_total_price = $insert->total_sale_price_all_Services;
            $new_total_price = $req->total_sale_price_all_Services;
            
            $prev_flight_pax = json_decode($insert->flights_Pax_details);
            $new_flight_pax = json_decode($req->flights_Pax_details);
            
            $accomodation_data = json_decode($req->accomodation_details);
            $accomodation_more_data = json_decode($req->more_accomodation_details);
            
            if(isset($accomodation_data)){
                foreach($accomodation_data as $index => $acc_res){
                if($acc_res->room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->new_rooms_type);
                     $Rooms = new Rooms;
                                $Rooms->hotel_id =  $acc_res->hotel_id;
                                 $Rooms->rooms_on_rq= '';
                                $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name =  $room_type_data->room_type; 
                                $Rooms->room_type_cat =  $room_type_data->id; 
                              
                               
                                $Rooms->quantity =  $acc_res->acc_qty;  
                                $Rooms->min_stay =  0; 
                                $Rooms->max_child =  1; 
                                $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                $Rooms->extra_beds =  0; 
                                $Rooms->extra_beds_charges =  0; 
                                $Rooms->availible_from =  $acc_res->acc_check_in; 
                                $Rooms->availible_to =  $acc_res->acc_check_out; 
                                $Rooms->room_option_date =  $acc_res->acc_check_in; 
                                $Rooms->price_week_type =  'for_all_days'; 
                                $Rooms->price_all_days =  $acc_res->price_per_room_purchase;
                                $Rooms->room_supplier_name =  $acc_res->new_supplier_id;
                                $Rooms->room_meal_type  = $acc_res->hotel_meal_type;
                                // $Rooms->weekdays = serialize($request->weekdays);
                                $Rooms->weekdays = null;
                                $Rooms->weekdays_price =  NULL; 
                                // $Rooms->weekends =  serialize($request->weekend); 
                                $Rooms->weekends =  null;
                                $Rooms->weekends_price =  NULL; 
                                
                              
                                
                                $user_id = $req->customer_id;
                                $Rooms->owner_id = $user_id;
                                
                                
                                $result = $Rooms->save();
                                $Roomsid = $Rooms->id;
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                            if($Rooms->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                $total_price = $Rooms->price_all_days * $avaiable_days;
                                            }else{
                                                 $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                 
                                                 
                                                 $all_days = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
                                                 $week_days = json_decode($Rooms->weekdays);
                                                 $week_end_days = json_decode($Rooms->weekends);
                                                 
                                                
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
                                                         $week_days_total += $Rooms->weekdays_price;
                                                     }else{
                                                         $week_end_days_totals += $Rooms->weekends_price;
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
                                            
                                            
                                        $all_days_price = $total_price * $Rooms->quantity;
                                        $supplier_balance = $supplier_data->balance + $all_days_price;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$all_days_price,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_data->payable,
                                            'room_id'=>$Roomsid,
                                            'customer_id'=>$user_id,
                                            'date'=>date('Y-m-d'),
                                            'available_from'=>$Rooms->availible_from,
                                            'available_to'=>$Rooms->availible_to,
                                            'room_quantity'=>$Rooms->quantity,
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                        
                                        
                                          
                                                                    
                                    }
                                    
                                $accomodation_data[$index]->acc_type = $room_type_data->parent_cat;
                                $accomodation_data[$index]->hotel_supplier_id = $acc_res->new_supplier_id;
                                $accomodation_data[$index]->hotel_type_id = $room_type_data->id;
                                $accomodation_data[$index]->hotel_type_cat = $room_type_data->room_type;
                                $accomodation_data[$index]->hotelRoom_type_id = $Roomsid;
                }
                
            }
            }
            
            if(isset($accomodation_more_data)){
                foreach($accomodation_more_data as $index => $acc_res){
                if($acc_res->more_room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->more_new_rooms_type);
                     $Rooms = new Rooms;
                                $Rooms->hotel_id =  $acc_res->more_hotel_id;
                                 $Rooms->rooms_on_rq= '';
                                $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name =  $room_type_data->room_type; 
                                $Rooms->room_type_cat =  $room_type_data->id; 
                              
                               
                                $Rooms->quantity =  $acc_res->more_acc_qty;  
                                $Rooms->min_stay =  0; 
                                $Rooms->max_child =  1; 
                                $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                $Rooms->extra_beds =  0; 
                                $Rooms->extra_beds_charges =  0; 
                                $Rooms->availible_from =  $acc_res->more_acc_check_in; 
                                $Rooms->availible_to =  $acc_res->more_acc_check_out; 
                                $Rooms->room_option_date =  $acc_res->more_acc_check_in; 
                                $Rooms->price_week_type =  'for_all_days'; 
                                $Rooms->price_all_days =  $acc_res->more_price_per_room_purchase;
                                $Rooms->room_supplier_name =  $acc_res->more_new_supplier_id;
                                $Rooms->room_meal_type  = $acc_res->more_hotel_meal_type;
                                // $Rooms->weekdays = serialize($request->weekdays);
                                $Rooms->weekdays = null;
                                $Rooms->weekdays_price =  NULL; 
                                // $Rooms->weekends =  serialize($request->weekend); 
                                $Rooms->weekends =  null;
                                $Rooms->weekends_price =  NULL; 
                                
                              
                                
                                $user_id = $req->customer_id;
                                $Rooms->owner_id = $user_id;
                                
                                
                                $result = $Rooms->save();
                                $Roomsid = $Rooms->id;
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                            if($Rooms->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                $total_price = $Rooms->price_all_days * $avaiable_days;
                                            }
                                            
                                            
                                        $all_days_price = $total_price * $Rooms->quantity;
                                        $supplier_balance = $supplier_data->balance + $all_days_price;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$all_days_price,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_data->payable,
                                            'room_id'=>$Roomsid,
                                            'customer_id'=>$user_id,
                                            'date'=>date('Y-m-d'),
                                            'available_from'=>$Rooms->availible_from,
                                            'available_to'=>$Rooms->availible_to,
                                            'room_quantity'=>$Rooms->quantity,
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                        
                                        
                                          
                                                                    
                                    }
                                    
                                $accomodation_more_data[$index]->more_acc_type = $room_type_data->parent_cat;
                                $accomodation_more_data[$index]->more_hotel_supplier_id = $acc_res->more_new_supplier_id;
                                $accomodation_more_data[$index]->more_hotel_type_id = $room_type_data->id;
                                $accomodation_more_data[$index]->more_hotel_type_cat = $room_type_data->room_type;
                                $accomodation_more_data[$index]->more_hotelRoom_type_id = $Roomsid;
                }
                
            }
            }
            
            $req->accomodation_details = json_encode($accomodation_data);
            $req->more_accomodation_details = json_encode($accomodation_more_data);
            
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            $insert->quotation_validity      = $req->quotation_validity;
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
             
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
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
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
            
            $insert->total_sale_price_all_Services=$req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services=$req->total_cost_price_all_Services;
            
            $insert->transfer_supplier      = $req->transfer_supplier;
            $insert->transfer_supplier_id   = $req->transfer_supplier_id;
            
            $insert->ziyarat_details        = $req->ziyarat_details ?? '';
            
            $insert->all_Details                = $req->all_Details ?? '';
            $insert->acc_Details                = $req->acc_Details ?? '';
            $insert->city_Details               = $req->city_Details ?? '';
            $insert->hotel_supplier_multiple    = $req->hotel_supplier_multiple ?? '';
            
            DB::beginTransaction();
            
            try {
                    $insert->update();
                    
                    $invoice_id     = $insert->id;
                    $prev_acc       = json_decode($prev_acc);
                    $prev_acc_more  = json_decode($prev_acc_more);
                    $new_acc        = json_decode($req->accomodation_details);
                    $new_acc_more   = json_decode($req->more_accomodation_details);
                    
                    $flights_Pax_details = json_decode($req->flights_Pax_details);
                    if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                        
                        $occupied_against_id = DB::table('flight_seats_occupied')->where('booking_id',$id)->get();
                        if(isset($occupied_against_id) && $occupied_against_id != null && $occupied_against_id != ''){
                            foreach($occupied_against_id as $occupied_against_idS){
                                DB::table('flight_seats_occupied')->where('id',$occupied_against_idS->id)->delete();
                            }
                        }
                        
                        foreach($flights_Pax_details as $value){
                            $check_id = DB::table('flight_seats_occupied')->where('booking_id',$id)->where('flight_route_id',$value->flight_route_id_occupied)->first();
                            if(isset($check_id) && $check_id != null && $check_id != ''){
                                DB::table('flight_seats_occupied')->insert([
                                    'token'                         => $req->token,
                                    'type'                          => 'Quotation',
                                    'booking_id'                    => $invoice_id,
                                    'flight_supplier'               => $req->flight_supplier,
                                    'flight_route_id'               => $value->flight_route_id_occupied,
                                    'flights_adult_seats'           => $value->flights_adult_seats,
                                    'flights_child_seats'           => $value->flights_child_seats,
                                    'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                                ]);
                            }else{
                                DB::table('flight_seats_occupied')->insert([
                                    'token'                         => $req->token,
                                    'type'                          => 'Quotation',
                                    'booking_id'                    => $invoice_id,
                                    'flight_supplier'               => $req->flight_supplier,
                                    'flight_route_id'               => $value->flight_route_id_occupied,
                                    'flights_adult_seats'           => $value->flights_adult_seats,
                                    'flights_child_seats'           => $value->flights_child_seats,
                                    'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                                ]);
                            }
                        }
                    }
                 
                    DB::commit();
                 
            } catch (Throwable $e) {
                echo $e;
                DB::rollback();
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
            return response()->json(['status'=>'success','message'=>'Agent Invoice Updated Succesfully']); 
        }else{
            return response()->json(['status'=>'error','message'=>$id]); 
        }
    }
    
    public function update_manage_Package_Quotation_New(Request $req){
        $id     = $req->id;
        $insert = addManageQuotationPackage::find($id);
        
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
        
        if($insert){
            
            $prev_acc               = $insert->accomodation_details;
            $prev_acc_more          = $insert->accomodation_details_more;
            
            $previous_agent         = $insert->agent_Id;
            $new_agent              = $req->agent_Id;
            
            $previous_customer      = $insert->booking_customer_id;
            $new_customer           = $req->booking_customer_id;
            
            $previous_total_price   = $insert->total_sale_price_all_Services;
            $new_total_price        = $req->total_sale_price_all_Services;
            
            $prev_flight_pax        = json_decode($insert->flights_Pax_details);
            $new_flight_pax         = json_decode($req->flights_Pax_details);
            
            $accomodation_data      = json_decode($req->accomodation_details);
            $accomodation_more_data = json_decode($req->more_accomodation_details);
            
            if(isset($accomodation_data)){
                foreach($accomodation_data as $index => $acc_res){
                if($acc_res->room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->new_rooms_type);
                     $Rooms = new Rooms;
                                $Rooms->hotel_id =  $acc_res->hotel_id;
                                 $Rooms->rooms_on_rq= '';
                                $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name =  $room_type_data->room_type; 
                                $Rooms->room_type_cat =  $room_type_data->id; 
                              
                               
                                $Rooms->quantity =  $acc_res->acc_qty;  
                                $Rooms->min_stay =  0; 
                                $Rooms->max_child =  1; 
                                $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                $Rooms->extra_beds =  0; 
                                $Rooms->extra_beds_charges =  0; 
                                $Rooms->availible_from =  $acc_res->acc_check_in; 
                                $Rooms->availible_to =  $acc_res->acc_check_out; 
                                $Rooms->room_option_date =  $acc_res->acc_check_in; 
                                $Rooms->price_week_type =  'for_all_days'; 
                                $Rooms->price_all_days =  $acc_res->price_per_room_purchase;
                                $Rooms->room_supplier_name =  $acc_res->new_supplier_id;
                                $Rooms->room_meal_type  = $acc_res->hotel_meal_type;
                                // $Rooms->weekdays = serialize($request->weekdays);
                                $Rooms->weekdays = null;
                                $Rooms->weekdays_price =  NULL; 
                                // $Rooms->weekends =  serialize($request->weekend); 
                                $Rooms->weekends =  null;
                                $Rooms->weekends_price =  NULL; 
                                
                              
                                
                                $user_id = $req->customer_id;
                                $Rooms->owner_id = $user_id;
                                
                                
                                $result = $Rooms->save();
                                $Roomsid = $Rooms->id;
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                            if($Rooms->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                $total_price = $Rooms->price_all_days * $avaiable_days;
                                            }else{
                                                 $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                 
                                                 
                                                 $all_days = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
                                                 $week_days = json_decode($Rooms->weekdays);
                                                 $week_end_days = json_decode($Rooms->weekends);
                                                 
                                                
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
                                                         $week_days_total += $Rooms->weekdays_price;
                                                     }else{
                                                         $week_end_days_totals += $Rooms->weekends_price;
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
                                            
                                            
                                        $all_days_price = $total_price * $Rooms->quantity;
                                        $supplier_balance = $supplier_data->balance + $all_days_price;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$all_days_price,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_data->payable,
                                            'room_id'=>$Roomsid,
                                            'customer_id'=>$user_id,
                                            'date'=>date('Y-m-d'),
                                            'available_from'=>$Rooms->availible_from,
                                            'available_to'=>$Rooms->availible_to,
                                            'room_quantity'=>$Rooms->quantity,
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                        
                                        
                                          
                                                                    
                                    }
                                    
                                $accomodation_data[$index]->acc_type = $room_type_data->parent_cat;
                                $accomodation_data[$index]->hotel_supplier_id = $acc_res->new_supplier_id;
                                $accomodation_data[$index]->hotel_type_id = $room_type_data->id;
                                $accomodation_data[$index]->hotel_type_cat = $room_type_data->room_type;
                                $accomodation_data[$index]->hotelRoom_type_id = $Roomsid;
                }
                
            }
            }
            
            if(isset($accomodation_more_data)){
                foreach($accomodation_more_data as $index => $acc_res){
                if($acc_res->more_room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->more_new_rooms_type);
                     $Rooms = new Rooms;
                                $Rooms->hotel_id =  $acc_res->more_hotel_id;
                                 $Rooms->rooms_on_rq= '';
                                $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name =  $room_type_data->room_type; 
                                $Rooms->room_type_cat =  $room_type_data->id; 
                              
                               
                                $Rooms->quantity =  $acc_res->more_acc_qty;  
                                $Rooms->min_stay =  0; 
                                $Rooms->max_child =  1; 
                                $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                $Rooms->extra_beds =  0; 
                                $Rooms->extra_beds_charges =  0; 
                                $Rooms->availible_from =  $acc_res->more_acc_check_in; 
                                $Rooms->availible_to =  $acc_res->more_acc_check_out; 
                                $Rooms->room_option_date =  $acc_res->more_acc_check_in; 
                                $Rooms->price_week_type =  'for_all_days'; 
                                $Rooms->price_all_days =  $acc_res->more_price_per_room_purchase;
                                $Rooms->room_supplier_name =  $acc_res->more_new_supplier_id;
                                $Rooms->room_meal_type  = $acc_res->more_hotel_meal_type;
                                // $Rooms->weekdays = serialize($request->weekdays);
                                $Rooms->weekdays = null;
                                $Rooms->weekdays_price =  NULL; 
                                // $Rooms->weekends =  serialize($request->weekend); 
                                $Rooms->weekends =  null;
                                $Rooms->weekends_price =  NULL; 
                                
                              
                                
                                $user_id = $req->customer_id;
                                $Rooms->owner_id = $user_id;
                                
                                
                                $result = $Rooms->save();
                                $Roomsid = $Rooms->id;
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                            if($Rooms->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                $total_price = $Rooms->price_all_days * $avaiable_days;
                                            }
                                            
                                            
                                        $all_days_price = $total_price * $Rooms->quantity;
                                        $supplier_balance = $supplier_data->balance + $all_days_price;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$all_days_price,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_data->payable,
                                            'room_id'=>$Roomsid,
                                            'customer_id'=>$user_id,
                                            'date'=>date('Y-m-d'),
                                            'available_from'=>$Rooms->availible_from,
                                            'available_to'=>$Rooms->availible_to,
                                            'room_quantity'=>$Rooms->quantity,
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                        
                                        
                                          
                                                                    
                                    }
                                    
                                $accomodation_more_data[$index]->more_acc_type = $room_type_data->parent_cat;
                                $accomodation_more_data[$index]->more_hotel_supplier_id = $acc_res->more_new_supplier_id;
                                $accomodation_more_data[$index]->more_hotel_type_id = $room_type_data->id;
                                $accomodation_more_data[$index]->more_hotel_type_cat = $room_type_data->room_type;
                                $accomodation_more_data[$index]->more_hotelRoom_type_id = $Roomsid;
                }
                
            }
            }
            
            $req->accomodation_details      = json_encode($accomodation_data);
            $req->more_accomodation_details = json_encode($accomodation_more_data);
            
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            $insert->quotation_validity      = $req->quotation_validity;
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
            
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            
            $insert->passport_Image          = $req->passport_Image ?? '';
            
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
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
            
            $insert->total_sale_price_all_Services      = $req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services      = $req->total_cost_price_all_Services;
            $insert->total_discount_price_all_Services  = $req->total_discount_price_all_Services ?? '';
            $insert->total_discount_all_Services        = $req->total_discount_all_Services ?? '';
            
            $insert->transfer_supplier      = $req->transfer_supplier;
            $insert->transfer_supplier_id   = $req->transfer_supplier_id;
            
            $insert->all_costing_details            = $req->all_costing_details;
            $insert->all_costing_details_child      = $req->all_costing_details_child;
            $insert->all_costing_details_infant     = $req->all_costing_details_infant;
            
            // Adult
            $insert->WQFVT_details                  = $req->WQFVT_details ?? '';
            $insert->WDFVT_details                  = $req->WDFVT_details ?? '';
            $insert->WTFVT_details                  = $req->WTFVT_details ?? '';
            $insert->WAFVT_details                  = $req->WAFVT_details ?? '';
            
            // Child
            $insert->WQFVT_details_child            = $req->WQFVT_details_child ?? '';
            $insert->WTFVT_details_child            = $req->WTFVT_details_child ?? '';
            $insert->WDFVT_details_child            = $req->WDFVT_details_child ?? '';
            $insert->WAFVT_details_child            = $req->WAFVT_details_child ?? '';
            
            // Infant
            $insert->WQFVT_details_infant           = $req->WQFVT_details_infant ?? '';
            $insert->WTFVT_details_infant           = $req->WTFVT_details_infant ?? '';
            $insert->WDFVT_details_infant           = $req->WDFVT_details_infant ?? '';
            $insert->WAFVT_details_infant           = $req->WAFVT_details_infant ?? '';
            
            $insert->ziyarat_details            = $req->ziyarat_details ?? '';
            
            DB::beginTransaction();
            
            try {
                    $insert->update();
                    
                    $invoice_id     = $insert->id;
                    // $prev_acc       = json_decode($prev_acc);
                    // $prev_acc_more  = json_decode($prev_acc_more);
                    // $new_acc        = json_decode($req->accomodation_details);
                    // $new_acc_more   = json_decode($req->more_accomodation_details);
                    
                    // $flights_Pax_details = json_decode($req->flights_Pax_details);
                    // if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                        
                    //     $occupied_against_id = DB::table('flight_seats_occupied')->where('booking_id',$id)->get();
                    //     if(isset($occupied_against_id) && $occupied_against_id != null && $occupied_against_id != ''){
                    //         foreach($occupied_against_id as $occupied_against_idS){
                    //             DB::table('flight_seats_occupied')->where('id',$occupied_against_idS->id)->delete();
                    //         }
                    //     }
                        
                    //     foreach($flights_Pax_details as $value){
                    //         $check_id = DB::table('flight_seats_occupied')->where('booking_id',$id)->where('flight_route_id',$value->flight_route_id_occupied)->first();
                    //         if(isset($check_id) && $check_id != null && $check_id != ''){
                    //             DB::table('flight_seats_occupied')->insert([
                    //                 'token'                         => $req->token,
                    //                 'type'                          => 'Invoice',
                    //                 'booking_id'                    => $invoice_id,
                    //                 'flight_supplier'               => $req->flight_supplier,
                    //                 'flight_route_id'               => $value->flight_route_id_occupied,
                    //                 'flights_adult_seats'           => $value->flights_adult_seats,
                    //                 'flights_child_seats'           => $value->flights_child_seats,
                    //                 'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                    //             ]);
                    //         }else{
                    //             DB::table('flight_seats_occupied')->insert([
                    //                 'token'                         => $req->token,
                    //                 'type'                          => 'Invoice',
                    //                 'booking_id'                    => $invoice_id,
                    //                 'flight_supplier'               => $req->flight_supplier,
                    //                 'flight_route_id'               => $value->flight_route_id_occupied,
                    //                 'flights_adult_seats'           => $value->flights_adult_seats,
                    //                 'flights_child_seats'           => $value->flights_child_seats,
                    //                 'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                    //             ]);
                    //         }
                    //     }
                    // }
                    
                    // Agent Data Updated
                    // if($previous_agent != $new_agent){
                         
                    //      // Agent is Changed
                    //      // previous Agent Working
                   
                              
                              
                    //             $agent_data = DB::table('Agents_detail')->where('id',$previous_agent)->select('id','balance')->first();
       
                    //             if(isset($agent_data)){
                    //                 // echo "Enter hre ";
                    //                 $received_amount = -1 * abs($previous_total_price);
                    //                 $agent_balance = $agent_data->balance - $previous_total_price;
                                    
                    //                 // update Agent Balance
                    //                 DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                    //                 DB::table('agents_ledgers_new')->insert([
                    //                     'agent_id'=>$agent_data->id,
                    //                     'received'=>$received_amount,
                    //                     'balance'=>$agent_balance,
                    //                     'invoice_no'=>$insert->id,
                    //                     'customer_id'=>$insert->customer_id,
                    //                     'date'=>date('Y-m-d'),
                    //                     'remarks'=>'Invoice Updated',
                    //                     ]);
                    //             }
                        
                         
                         
                    //      // New Agent Working
                    
                             
                              
                    //             $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
       
                    //             if(isset($agent_data)){
                    //                 // echo "Enter hre ";
                    //                 $received_amount = $new_total_price;
                    //                 $agent_balance = $agent_data->balance + $new_total_price;
                                    
                    //                 // update Agent Balance
                    //                 DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                    //                 DB::table('agents_ledgers_new')->insert([
                    //                     'agent_id'=>$agent_data->id,
                    //                     'received'=>$received_amount,
                    //                     'balance'=>$agent_balance,
                    //                     'invoice_no'=>$insert->id,
                    //                     'customer_id'=>$insert->customer_id,
                    //                     'date'=>date('Y-m-d'),
                    //                     'remarks'=>'Invoice Updated',
                    //                     ]);
                    //             }
                        
                         
                         
                    // }else{
                    //      // Agent is Not Changed
                         
                    //           $difference  = $new_total_price - $previous_total_price;
                              
                    //         //   echo "Differ is $difference ";
                              
                    //             $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
       
                    //             if(isset($agent_data)){
                    //                 // echo "Enter hre ";
                    //                 $agent_balance = $agent_data->balance + $difference;
                                    
                    //                 // update Agent Balance
                    //                 DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                    //                 DB::table('agents_ledgers_new')->insert([
                    //                     'agent_id'=>$agent_data->id,
                    //                     'received'=>$difference,
                    //                     'balance'=>$agent_balance,
                    //                     'invoice_no'=>$insert->id,
                    //                     'customer_id'=> $insert->customer_id,
                    //                     'date'=>date('Y-m-d'),
                    //                     'remarks'=>'Invoice Updated',
                    //                     ]);
                    //             }
                        
                         
                    //  }
                    
                    // Customer Data Updated
                    // if($previous_customer != $new_customer){
                         
                    //      // Agent is Changed
                    //      // previous Agent Working
                   
                              
                              
                    //             $customer_data = DB::table('booking_customers')->where('id',$previous_customer)->select('id','balance')->first();
       
                    //             if(isset($customer_data)){
                    //                 // echo "Enter hre ";
                    //                 $received_amount = -1 * abs($previous_total_price);
                    //                 $customer_balance = $customer_data->balance - $previous_total_price;
                                    
                    //                 // update Agent Balance
                                    
                    //                 DB::table('customer_ledger')->insert([
                    //                     'booking_customer'=>$customer_data->id,
                    //                     'received'=>$received_amount,
                    //                     'balance'=>$customer_balance,
                    //                     'invoice_no'=>$insert->id,
                    //                     'customer_id'=>$insert->customer_id,
                    //                     'date'=>date('Y-m-d'),
                    //                     'remarks'=>'Invoice Updated',
                    //                     ]);
                                        
                    //                 DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                    //             }
                        
                         
                         
                    //      // New Agent Working
                    
                             
                              
                    //             $customer_data = DB::table('booking_customers')->where('id',$new_customer)->select('id','balance')->first();
       
                    //             if(isset($customer_data)){
                    //                 // echo "Enter hre ";
                    //                 $received_amount = $new_total_price;
                    //                 $customer_balance = $customer_data->balance + $new_total_price;
                                    
                    //                 // update Agent Balance
                                    
                    //                 DB::table('customer_ledger')->insert([
                    //                     'booking_customer'=>$customer_data->id,
                    //                     'received'=>$received_amount,
                    //                     'balance'=>$customer_balance,
                    //                     'invoice_no'=>$insert->id,
                    //                     'customer_id'=>$insert->customer_id,
                    //                     'date'=>date('Y-m-d'),
                    //                     'remarks'=>'Invoice Updated',
                    //                     ]);
                                        
                    //                 DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                    //             }
                        
                         
                         
                    // }else{
                    //      // Agent is Not Changed
                         
                    //           $difference  = $new_total_price - $previous_total_price;
                              
                    //         //   echo "Differ is $difference ";
                              
                    //             $customer_data = DB::table('booking_customers')->where('id',$new_customer)->select('id','balance')->first();
       
                    //             if(isset($customer_data)){
                    //                 // echo "Enter hre ";
                    //                 $customer_balance = $customer_data->balance + $difference;
                                    
                    //                 // update Agent Balance
                    //                 DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                    //                 DB::table('customer_ledger')->insert([
                    //                     'booking_customer'=>$customer_data->id,
                    //                     'received'=>$difference,
                    //                     'balance'=>$customer_balance,
                    //                     'invoice_no'=>$insert->id,
                    //                     'customer_id'=> $insert->customer_id,
                    //                     'date'=>date('Y-m-d'),
                    //                     'remarks'=>'Invoice Updated',
                    //                     ]);
                    //             }
                        
                         
                    // }
                
                    // Loop on Previous Accomodations 
                    // DB::table('rooms_bookings_details')->where('booking_id', "$insert->id")->where('booking_from','Invoices')->delete();
                    
                    // Previous Element Found Working
                    // $arr_ele_found = [];
                    // if(isset($prev_acc) && !empty($prev_acc)){
                    //     foreach($prev_acc as $prev_acc_res){
                            
                    //         if(isset($prev_acc_res->hotelRoom_type_id) AND !empty($prev_acc_res->hotelRoom_type_id)){
                    //             // echo $prev_acc_res->hotelRoom_type_id;
                                
                    //             $found = false;
                    //             foreach($arr_ele_found as $arr_id_res){
                    //                 if($arr_id_res == $prev_acc_res->hotelRoom_type_id){
                    //                     $found = true;
                    //                 }
                    //             }
                                
                    //             if(!$found){
                    //                 $perv_total = 0;
                    //                 $rooms_total_pr_prev = 0;
                    //                 foreach($prev_acc as $cal_total_prev){
                    //                     if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                    //                         $perv_total += $cal_total_prev->acc_qty;
                                            
                    //                         // Calaculate Room Prices
                    //                          $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                            
                    //                             if($room_data){
                    //                                      $week_days_total = 0;
                    //                                      $week_end_days_totals = 0;
                    //                                      $total_price = 0;
                    //                                      $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                    //                                      $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                    //                                     if($room_data->price_week_type == 'for_all_days'){
                    //                                         $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                    //                                         $total_price = $room_data->price_all_days * $avaiable_days;
                    //                                     }else{
                    //                                          $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                             
                    //                                          $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                             
                    //                                         //  print_r($all_days);
                    //                                          $week_days = json_decode($room_data->weekdays);
                    //                                          $week_end_days = json_decode($room_data->weekends);
                                                             
                    //                                          foreach($all_days as $day_res){
                    //                                              $day = date('l', strtotime($day_res));
                    //                                              $day = trim($day);
                    //                                              $week_day_found = false;
                    //                                              $week_end_day_found = false;
                                                                
                    //                                              foreach($week_days as $week_day_res){
                    //                                                  if($week_day_res == $day){
                    //                                                      $week_day_found = true;
                    //                                                  }
                    //                                              }
                                                          
                    //                                             // echo "  ".$room_data->weekdays_price;
                    //                                              if($week_day_found){
                    //                                                  $week_days_total += $room_data->weekdays_price;
                    //                                              }else{
                    //                                                  $week_end_days_totals += $room_data->weekends_price;
                    //                                              }
                                                                 
                                                                 
                    //                                             //  foreach($week_end_days as $week_day_res){
                    //                                             //      if($week_day_res == $day){
                    //                                             //          $week_end_day_found = true;
                    //                                             //      }
                    //                                             //  }
                    //                                             //   if($week_end_day_found){
                                                                      
                    //                                             //  }
                    //                                          }
                                                             
                                                             
                    //                                         //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                             
                    //                                         //  print_r($all_days);
                    //                                          $total_price = $week_days_total + $week_end_days_totals;
                    //                                     }
                                                        
                                                        
                    //                                     $all_days_price = $total_price * $cal_total_prev->acc_qty;
                    //                                     $rooms_total_pr_prev += $all_days_price;
                    //                             }
                                                    
                                                    
                                                      
                                             
                                            
                                            
                                            
                                            
                    //                     }
                    //                 }
                                    
                                    
                    //                 if(isset($prev_acc_more) && !empty($prev_acc_more)){
                    //                     foreach($prev_acc_more as $cal_total_prev){
                    //                         if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                    //                             $perv_total += $cal_total_prev->more_acc_qty;
                                                
                    //                             // Calaculate Room Prices
                    //                       $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                
                    //                                 if($room_data){
                    //                                          $week_days_total = 0;
                    //                                          $week_end_days_totals = 0;
                    //                                          $total_price = 0;
                    //                                          $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                    //                                          $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                    //                                         if($room_data->price_week_type == 'for_all_days'){
                    //                                             $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                    //                                             $total_price = $room_data->price_all_days * $avaiable_days;
                    //                                         }else{
                    //                                              $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                    //                                              $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
                    //                                             //  print_r($all_days);
                    //                                              $week_days = json_decode($room_data->weekdays);
                    //                                              $week_end_days = json_decode($room_data->weekends);
                                                                 
                    //                                              foreach($all_days as $day_res){
                    //                                                  $day = date('l', strtotime($day_res));
                    //                                                  $day = trim($day);
                    //                                                  $week_day_found = false;
                    //                                                  $week_end_day_found = false;
                                                                    
                    //                                                  foreach($week_days as $week_day_res){
                    //                                                      if($week_day_res == $day){
                    //                                                          $week_day_found = true;
                    //                                                      }
                    //                                                  }
                                                              
                    //                                                 // echo "  ".$room_data->weekdays_price;
                    //                                                  if($week_day_found){
                    //                                                      $week_days_total += $room_data->weekdays_price;
                    //                                                  }else{
                    //                                                      $week_end_days_totals += $room_data->weekends_price;
                    //                                                  }
                                                                     
                                                                     
                    //                                                 //  foreach($week_end_days as $week_day_res){
                    //                                                 //      if($week_day_res == $day){
                    //                                                 //          $week_end_day_found = true;
                    //                                                 //      }
                    //                                                 //  }
                    //                                                 //   if($week_end_day_found){
                                                                          
                    //                                                 //  }
                    //                                              }
                                                                 
                                                                 
                    //                                             //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                 
                    //                                             //  print_r($all_days);
                    //                                              $total_price = $week_days_total + $week_end_days_totals;
                    //                                         }
                                                            
                                                            
                    //                                         $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                    //                                         $rooms_total_pr_prev += $all_days_price;
                    //                                 }
                    //                         }
                    //                     }
                    //                 }
                                    
                                    
                                    
                                   
                    //                 $new_total = 0;
                    //                 $rooms_total_pr_new = 0;
                    //                 foreach($new_acc as $cal_total_prev){
                    //                     if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                    //                         $new_total += $cal_total_prev->acc_qty;
                                            
                    //                         // Calaculate Room Prices
                    //                          $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                            
                    //                             if($room_data){
                    //                                      $week_days_total = 0;
                    //                                      $week_end_days_totals = 0;
                    //                                      $total_price = 0;
                    //                                      $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                    //                                      $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                    //                                     if($room_data->price_week_type == 'for_all_days'){
                    //                                         $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                    //                                         $total_price = $room_data->price_all_days * $avaiable_days;
                    //                                     }else{
                    //                                          $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                             
                    //                                          $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                             
                    //                                         //  print_r($all_days);
                    //                                          $week_days = json_decode($room_data->weekdays);
                    //                                          $week_end_days = json_decode($room_data->weekends);
                                                             
                    //                                          foreach($all_days as $day_res){
                    //                                              $day = date('l', strtotime($day_res));
                    //                                              $day = trim($day);
                    //                                              $week_day_found = false;
                    //                                              $week_end_day_found = false;
                                                                
                    //                                              foreach($week_days as $week_day_res){
                    //                                                  if($week_day_res == $day){
                    //                                                      $week_day_found = true;
                    //                                                  }
                    //                                              }
                                                          
                    //                                             // echo "  ".$room_data->weekdays_price;
                    //                                              if($week_day_found){
                    //                                                  $week_days_total += $room_data->weekdays_price;
                    //                                              }else{
                    //                                                  $week_end_days_totals += $room_data->weekends_price;
                    //                                              }
                                                                 
                                                                 
                    //                                             //  foreach($week_end_days as $week_day_res){
                    //                                             //      if($week_day_res == $day){
                    //                                             //          $week_end_day_found = true;
                    //                                             //      }
                    //                                             //  }
                    //                                             //   if($week_end_day_found){
                                                                      
                    //                                             //  }
                    //                                          }
                                                             
                                                             
                    //                                         //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                             
                    //                                         //  print_r($all_days);
                    //                                          $total_price = $week_days_total + $week_end_days_totals;
                    //                                     }
                                                        
                                                        
                    //                                     $all_days_price = $total_price * $cal_total_prev->acc_qty;
                    //                                     $rooms_total_pr_new += $all_days_price;
                    //                             }
                                            
                    //                         DB::table('rooms_bookings_details')->insert([
                    //                                 'room_id'=> $cal_total_prev->hotelRoom_type_id,
                    //                                 'booking_from'=> 'Invoices',
                    //                                 'quantity'=> $cal_total_prev->acc_qty,
                    //                                 'booking_id'=> $insert->id,
                    //                                 'date'=> date('Y-m-d',strtotime($insert->created_at)),
                    //                                 'check_in'=> $cal_total_prev->acc_check_in,
                    //                                 'check_out'=> $cal_total_prev->acc_check_out,
                    //                             ]);
                                            
                    //                     }
                    //                 }
                                    
                                    
                    //                 if(isset($new_acc_more) && !empty($new_acc_more)){
                    //                     foreach($new_acc_more as $cal_total_prev){
                    //                         if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                    //                             $new_total += $cal_total_prev->more_acc_qty;
                                                
                    //                               // Calaculate Room Prices
                    //                              $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                
                    //                                 if($room_data){
                    //                                          $week_days_total = 0;
                    //                                          $week_end_days_totals = 0;
                    //                                          $total_price = 0;
                    //                                          $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                    //                                          $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                    //                                         if($room_data->price_week_type == 'for_all_days'){
                    //                                             $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                    //                                             $total_price = $room_data->price_all_days * $avaiable_days;
                    //                                         }else{
                    //                                              $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                    //                                              $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
                    //                                             //  print_r($all_days);
                    //                                              $week_days = json_decode($room_data->weekdays);
                    //                                              $week_end_days = json_decode($room_data->weekends);
                                                                 
                    //                                              foreach($all_days as $day_res){
                    //                                                  $day = date('l', strtotime($day_res));
                    //                                                  $day = trim($day);
                    //                                                  $week_day_found = false;
                    //                                                  $week_end_day_found = false;
                                                                    
                    //                                                  foreach($week_days as $week_day_res){
                    //                                                      if($week_day_res == $day){
                    //                                                          $week_day_found = true;
                    //                                                      }
                    //                                                  }
                                                              
                    //                                                 // echo "  ".$room_data->weekdays_price;
                    //                                                  if($week_day_found){
                    //                                                      $week_days_total += $room_data->weekdays_price;
                    //                                                  }else{
                    //                                                      $week_end_days_totals += $room_data->weekends_price;
                    //                                                  }
                                                                     
                                                                     
                    //                                                 //  foreach($week_end_days as $week_day_res){
                    //                                                 //      if($week_day_res == $day){
                    //                                                 //          $week_end_day_found = true;
                    //                                                 //      }
                    //                                                 //  }
                    //                                                 //   if($week_end_day_found){
                                                                          
                    //                                                 //  }
                    //                                              }
                                                                 
                                                                 
                    //                                             //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                 
                    //                                             //  print_r($all_days);
                    //                                              $total_price = $week_days_total + $week_end_days_totals;
                    //                                         }
                                                            
                                                            
                    //                                         $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                    //                                         $rooms_total_pr_new += $all_days_price;
                    //                                 }
                                                
                    //                             DB::table('rooms_bookings_details')->insert([
                    //                                     'room_id'=> $cal_total_prev->more_hotelRoom_type_id,
                    //                                     'booking_from'=> 'Invoices',
                    //                                     'quantity'=> $cal_total_prev->more_acc_qty,
                    //                                     'booking_id'=> $insert->id,
                    //                                     'date'=> date('Y-m-d',strtotime($insert->created_at)),
                    //                                     'check_in'=> $cal_total_prev->more_acc_check_in,
                    //                                     'check_out'=> $cal_total_prev->more_acc_check_out,
                    //                                 ]);
                    //                         }
                    //                     }
                    //                 }
                                    
                    //                 array_push($arr_ele_found,$prev_acc_res->hotelRoom_type_id);
                    //             }
                                
                    //             $room_data = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->first();
                                 
                    //             $difference = $new_total - $perv_total;
                    //             $Price_difference = $rooms_total_pr_new - $rooms_total_pr_prev;
                                
                    //             $update_booked = $room_data->booked + $difference;
                                
                    //             $room_data = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->update(['booked'=>$update_booked]);
                                    
                    //             $room_data = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->first();
                                
                    //             $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
        
                    //             if(isset($supplier_data)){

                    //                 $supplier_balance = $supplier_data->balance;
                    //                     $supplier_payable_balance = $supplier_data->payable + $Price_difference;
                                        
                    //                     // update Agent Balance
                                        
                    //                     DB::table('hotel_supplier_ledger')->insert([
                    //                         'supplier_id'=>$supplier_data->id,
                    //                         'payment'=>$Price_difference,
                    //                         'balance'=>$supplier_balance,
                    //                         'payable_balance'=>$supplier_payable_balance,
                    //                         'room_id'=>$room_data->id,
                    //                         'customer_id'=>$insert->customer_id,
                    //                         'date'=>date('Y-m-d'),
                    //                         'invoice_no'=>$insert->id,
                    //                         'available_from'=>'',
                    //                         'available_to'=>'',
                    //                         'remarks'=>'Invoice Updated',
                    //                         ]);
                                            
                    //                     DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                    //                         'balance'=>$supplier_balance,
                    //                         'payable'=>$supplier_payable_balance
                    //                         ]);

                    //             }
                    //         }
                    //     }
                    // }
                    
                    // if(isset($prev_acc_more) && !empty($prev_acc_more)){
                    //     foreach($prev_acc_more as $prev_acc_res){
                    //         if(isset($prev_acc_res->more_hotelRoom_type_id) AND !empty($prev_acc_res->more_hotelRoom_type_id)){
                    //             $found = false;
                    //             foreach($arr_ele_found as $arr_id_res){
                    //                 if($arr_id_res == $prev_acc_res->more_hotelRoom_type_id){
                    //                     $found = true;
                    //                 }
                    //             }
                    //             if(!$found){
                                    
                    //                 $perv_total = 0;
                    //                 $rooms_total_pr_prev = 0;
                    //                 foreach($prev_acc as $cal_total_prev){
                    //                     if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                    //                         $perv_total += $cal_total_prev->acc_qty;
                                            
                    //                          $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                                
                    //                                 if($room_data){
                    //                                          $week_days_total = 0;
                    //                                          $week_end_days_totals = 0;
                    //                                          $total_price = 0;
                    //                                          $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                    //                                          $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                    //                                         if($room_data->price_week_type == 'for_all_days'){
                    //                                             $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                    //                                             $total_price = $room_data->price_all_days * $avaiable_days;
                    //                                         }else{
                    //                                              $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                    //                                              $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
                    //                                             //  print_r($all_days);
                    //                                              $week_days = json_decode($room_data->weekdays);
                    //                                              $week_end_days = json_decode($room_data->weekends);
                                                                 
                    //                                              foreach($all_days as $day_res){
                    //                                                  $day = date('l', strtotime($day_res));
                    //                                                  $day = trim($day);
                    //                                                  $week_day_found = false;
                    //                                                  $week_end_day_found = false;
                                                                    
                    //                                                  foreach($week_days as $week_day_res){
                    //                                                      if($week_day_res == $day){
                    //                                                          $week_day_found = true;
                    //                                                      }
                    //                                                  }
                                                              
                    //                                                 // echo "  ".$room_data->weekdays_price;
                    //                                                  if($week_day_found){
                    //                                                      $week_days_total += $room_data->weekdays_price;
                    //                                                  }else{
                    //                                                      $week_end_days_totals += $room_data->weekends_price;
                    //                                                  }
                                                                     
                                                                     
                    //                                                 //  foreach($week_end_days as $week_day_res){
                    //                                                 //      if($week_day_res == $day){
                    //                                                 //          $week_end_day_found = true;
                    //                                                 //      }
                    //                                                 //  }
                    //                                                 //   if($week_end_day_found){
                                                                          
                    //                                                 //  }
                    //                                              }
                                                                 
                                                                 
                    //                                             //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                 
                    //                                             //  print_r($all_days);
                    //                                              $total_price = $week_days_total + $week_end_days_totals;
                    //                                         }
                                                            
                                                            
                    //                                         $all_days_price = $total_price * $cal_total_prev->acc_qty;
                    //                                         $rooms_total_pr_prev += $all_days_price;
                    //                                 }
                    //                     }
                    //                 }
                                    
                                    
                    //                 if(isset($prev_acc_more) && !empty($prev_acc_more)){
                    //                     foreach($prev_acc_more as $cal_total_prev){
                    //                         if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                    //                             $perv_total += $cal_total_prev->more_acc_qty;
                                                
                    //                              // Calaculate Room Prices
                    //                           $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                    
                    //                                     if($room_data){
                    //                                              $week_days_total = 0;
                    //                                              $week_end_days_totals = 0;
                    //                                              $total_price = 0;
                    //                                              $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                    //                                              $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                    //                                             if($room_data->price_week_type == 'for_all_days'){
                    //                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                    //                                                 $total_price = $room_data->price_all_days * $avaiable_days;
                    //                                             }else{
                    //                                                  $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                     
                    //                                                  $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                     
                    //                                                 //  print_r($all_days);
                    //                                                  $week_days = json_decode($room_data->weekdays);
                    //                                                  $week_end_days = json_decode($room_data->weekends);
                                                                     
                    //                                                  foreach($all_days as $day_res){
                    //                                                      $day = date('l', strtotime($day_res));
                    //                                                      $day = trim($day);
                    //                                                      $week_day_found = false;
                    //                                                      $week_end_day_found = false;
                                                                        
                    //                                                      foreach($week_days as $week_day_res){
                    //                                                          if($week_day_res == $day){
                    //                                                              $week_day_found = true;
                    //                                                          }
                    //                                                      }
                                                                  
                    //                                                     // echo "  ".$room_data->weekdays_price;
                    //                                                      if($week_day_found){
                    //                                                          $week_days_total += $room_data->weekdays_price;
                    //                                                      }else{
                    //                                                          $week_end_days_totals += $room_data->weekends_price;
                    //                                                      }
                                                                         
                                                                         
                    //                                                     //  foreach($week_end_days as $week_day_res){
                    //                                                     //      if($week_day_res == $day){
                    //                                                     //          $week_end_day_found = true;
                    //                                                     //      }
                    //                                                     //  }
                    //                                                     //   if($week_end_day_found){
                                                                              
                    //                                                     //  }
                    //                                                  }
                                                                     
                                                                     
                    //                                                 //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                     
                    //                                                 //  print_r($all_days);
                    //                                                  $total_price = $week_days_total + $week_end_days_totals;
                    //                                             }
                                                                
                                                                
                    //                                             $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                    //                                             $rooms_total_pr_prev += $all_days_price;
                    //                                     }
                    //                         }
                    //                     }
                    //                 }
                                    
                                    
                    //                 $new_total = 0;
                    //                 $rooms_total_pr_new = 0;
                    //                 foreach($new_acc as $cal_total_prev){
                    //                     if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                    //                         $new_total += $cal_total_prev->acc_qty;
                                            
                    //                         // Calaculate Room Prices
                    //                              $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                                
                    //                                 if($room_data){
                    //                                          $week_days_total = 0;
                    //                                          $week_end_days_totals = 0;
                    //                                          $total_price = 0;
                    //                                          $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                    //                                          $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                    //                                         if($room_data->price_week_type == 'for_all_days'){
                    //                                             $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                    //                                             $total_price = $room_data->price_all_days * $avaiable_days;
                    //                                         }else{
                    //                                              $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                    //                                              $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
                    //                                             //  print_r($all_days);
                    //                                              $week_days = json_decode($room_data->weekdays);
                    //                                              $week_end_days = json_decode($room_data->weekends);
                                                                 
                    //                                              foreach($all_days as $day_res){
                    //                                                  $day = date('l', strtotime($day_res));
                    //                                                  $day = trim($day);
                    //                                                  $week_day_found = false;
                    //                                                  $week_end_day_found = false;
                                                                    
                    //                                                  foreach($week_days as $week_day_res){
                    //                                                      if($week_day_res == $day){
                    //                                                          $week_day_found = true;
                    //                                                      }
                    //                                                  }
                                                              
                    //                                                 // echo "  ".$room_data->weekdays_price;
                    //                                                  if($week_day_found){
                    //                                                      $week_days_total += $room_data->weekdays_price;
                    //                                                  }else{
                    //                                                      $week_end_days_totals += $room_data->weekends_price;
                    //                                                  }
                                                                     
                                                                     
                    //                                                 //  foreach($week_end_days as $week_day_res){
                    //                                                 //      if($week_day_res == $day){
                    //                                                 //          $week_end_day_found = true;
                    //                                                 //      }
                    //                                                 //  }
                    //                                                 //   if($week_end_day_found){
                                                                          
                    //                                                 //  }
                    //                                              }
                                                                 
                                                                 
                    //                                             //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                 
                    //                                             //  print_r($all_days);
                    //                                              $total_price = $week_days_total + $week_end_days_totals;
                    //                                         }
                                                            
                                                            
                    //                                         $all_days_price = $total_price * $cal_total_prev->acc_qty;
                    //                                         $rooms_total_pr_new += $all_days_price;
                    //                                 }
                                            
                    //                          DB::table('rooms_bookings_details')->insert([
                    //                                 'room_id'=> $cal_total_prev->hotelRoom_type_id,
                    //                                 'booking_from'=> 'Invoices',
                    //                                 'quantity'=> $cal_total_prev->acc_qty,
                    //                                 'booking_id'=> $insert->id,
                    //                                 'date'=> date('Y-m-d',strtotime($insert->created_at)),
                    //                                 'check_in'=> $cal_total_prev->acc_check_in,
                    //                                 'check_out'=> $cal_total_prev->acc_check_out,
                    //                             ]);
                    //                     }
                    //                 }
                                    
                                    
                    //                  if(isset($new_acc_more) && !empty($new_acc_more)){
                    //                     foreach($new_acc_more as $cal_total_prev){
                    //                         if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                    //                             $new_total += $cal_total_prev->more_acc_qty;
                                                
                    //                               // Calaculate Room Prices
                    //                                  $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                    
                    //                                     if($room_data){
                    //                                              $week_days_total = 0;
                    //                                              $week_end_days_totals = 0;
                    //                                              $total_price = 0;
                    //                                              $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                    //                                              $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                    //                                             if($room_data->price_week_type == 'for_all_days'){
                    //                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                    //                                                 $total_price = $room_data->price_all_days * $avaiable_days;
                    //                                             }else{
                    //                                                  $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                     
                    //                                                  $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                     
                    //                                                 //  print_r($all_days);
                    //                                                  $week_days = json_decode($room_data->weekdays);
                    //                                                  $week_end_days = json_decode($room_data->weekends);
                                                                     
                    //                                                  foreach($all_days as $day_res){
                    //                                                      $day = date('l', strtotime($day_res));
                    //                                                      $day = trim($day);
                    //                                                      $week_day_found = false;
                    //                                                      $week_end_day_found = false;
                                                                        
                    //                                                      foreach($week_days as $week_day_res){
                    //                                                          if($week_day_res == $day){
                    //                                                              $week_day_found = true;
                    //                                                          }
                    //                                                      }
                                                                  
                    //                                                     // echo "  ".$room_data->weekdays_price;
                    //                                                      if($week_day_found){
                    //                                                          $week_days_total += $room_data->weekdays_price;
                    //                                                      }else{
                    //                                                          $week_end_days_totals += $room_data->weekends_price;
                    //                                                      }
                                                                         
                                                                         
                    //                                                     //  foreach($week_end_days as $week_day_res){
                    //                                                     //      if($week_day_res == $day){
                    //                                                     //          $week_end_day_found = true;
                    //                                                     //      }
                    //                                                     //  }
                    //                                                     //   if($week_end_day_found){
                                                                              
                    //                                                     //  }
                    //                                                  }
                                                                     
                                                                     
                    //                                                 //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                     
                    //                                                 //  print_r($all_days);
                    //                                                  $total_price = $week_days_total + $week_end_days_totals;
                    //                                             }
                                                                
                                                                
                    //                                             $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                    //                                             $rooms_total_pr_new += $all_days_price;
                    //                                     }
                                                
                    //                             DB::table('rooms_bookings_details')->insert([
                    //                                     'room_id'=> $cal_total_prev->more_hotelRoom_type_id,
                    //                                     'booking_from'=> 'Invoices',
                    //                                     'quantity'=> $cal_total_prev->more_acc_qty,
                    //                                     'booking_id'=> $insert->id,
                    //                                     'date'=> date('Y-m-d',strtotime($insert->created_at)),
                    //                                     'check_in'=> $cal_total_prev->more_acc_check_in,
                    //                                     'check_out'=> $cal_total_prev->more_acc_check_out,
                    //                                 ]);
                    //                         }
                    //                     }
                    //                  }
                                    
                    //                 array_push($arr_ele_found,$prev_acc_res->more_hotelRoom_type_id);
                                    
                    //                 $room_data = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->first();
                    //                 $difference = $new_total - $perv_total;
                                    
                    //                 $Price_difference = $rooms_total_pr_new - $rooms_total_pr_prev;
                                    
                    //                 $update_booked = $room_data->booked + $difference;
                                    
                    //                 $room_data = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->update([
                    //                 'booked'=>$update_booked
                    //                 ]);
                                    
                    //                 $room_data = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->first();
                    //                  $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
           
                    //                  if(isset($supplier_data)){
    
                    //                     $supplier_balance = $supplier_data->balance;
                    //                         $supplier_payable_balance = $supplier_data->payable + $Price_difference;
                                            
                    //                         // update Agent Balance
                                            
                    //                         DB::table('hotel_supplier_ledger')->insert([
                    //                             'supplier_id'=>$supplier_data->id,
                    //                             'payment'=>$Price_difference,
                    //                             'balance'=>$supplier_balance,
                    //                             'payable_balance'=>$supplier_payable_balance,
                    //                             'room_id'=>$room_data->id,
                    //                             'customer_id'=>$insert->customer_id,
                    //                             'date'=>date('Y-m-d'),
                    //                             'invoice_no'=>$insert->id,
                    //                             'available_from'=>'',
                    //                             'available_to'=>'',
                    //                             'remarks'=>'Invoice Updated',
                    //                             ]);
                                                
                    //                         DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                    //                             'balance'=>$supplier_balance,
                    //                             'payable'=>$supplier_payable_balance
                    //                             ]);
    
                    //                 }
                    //                 // echo "The Room id is ".$prev_acc_res->more_hotelRoom_type_id." and total qty is $perv_total and new $new_total "."<br>";
                    //             }
                    //         }
                    //     }
                    // }
                    
                    // New Element Found Working
                    // if(isset($new_acc) && !empty($new_acc)){
                    //     foreach($new_acc as $new_acc_res){
                    //         $ele_found = false;
                         
                    //         foreach($arr_ele_found as $arr_res){
                    //             if($new_acc_res->hotelRoom_type_id == $arr_res){
                    //                 $ele_found = true;
                    //             }
                    //         }
                         
                    //         if(isset($new_acc_res->hotelRoom_type_id) AND !empty($new_acc_res->hotelRoom_type_id)){
                    //             if(!$ele_found){
                    //                 $room_data = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->first();
                                
                                
                    //                 $update_booked = (int)$room_data->booked + (int)$new_acc_res->acc_qty;
                                
                    //                 $room_update = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->update(['booked'=>$update_booked
                    //                 ]);
                            
                    //                 DB::table('rooms_bookings_details')->insert([
                    //                         'room_id'=> $new_acc_res->hotelRoom_type_id,
                    //                         'booking_from'=> 'Invoices',
                    //                         'quantity'=> $new_acc_res->acc_qty,
                    //                         'booking_id'=> $insert->id,
                    //                         'date'=> date('Y-m-d',strtotime($insert->created_at)),
                    //                         'check_in'=> $new_acc_res->acc_check_in,
                    //                         'check_out'=> $new_acc_res->acc_check_out,
                    //                     ]);
                                        
                    //                 $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
   
                    //                 if(isset($supplier_data)){
                    //                     // echo "Enter hre ";
                                    
                    //                      $week_days_total = 0;
                    //                      $week_end_days_totals = 0;
                    //                      $total_price = 0;
                    //                      $new_acc_res->acc_check_in = date('Y-m-d',strtotime($new_acc_res->acc_check_in));
                    //                      $new_acc_res->acc_check_out = date('Y-m-d',strtotime($new_acc_res->acc_check_out));
                    //                     if($room_data->price_week_type == 'for_all_days'){
                    //                         $avaiable_days = dateDiffInDays($new_acc_res->acc_check_in, $new_acc_res->acc_check_out);
                    //                         $total_price = $room_data->price_all_days * $avaiable_days;
                    //                     }else{
                    //                          $avaiable_days = dateDiffInDays($new_acc_res->acc_check_in, $new_acc_res->acc_check_out);
                                             
                    //                          $all_days = getBetweenDates($new_acc_res->acc_check_in, $new_acc_res->acc_check_out);
                                             
                    //                         //  print_r($all_days);
                    //                          $week_days = json_decode($room_data->weekdays);
                    //                          $week_end_days = json_decode($room_data->weekends);
                                             
                    //                          foreach($all_days as $day_res){
                    //                              $day = date('l', strtotime($day_res));
                    //                              $day = trim($day);
                    //                              $week_day_found = false;
                    //                              $week_end_day_found = false;
                                                
                    //                              foreach($week_days as $week_day_res){
                    //                                  if($week_day_res == $day){
                    //                                      $week_day_found = true;
                    //                                  }
                    //                              }
                                          
                    //                             // echo "  ".$room_data->weekdays_price;
                    //                              if($week_day_found){
                    //                                  $week_days_total += $room_data->weekdays_price;
                    //                              }else{
                    //                                  $week_end_days_totals += $room_data->weekends_price;
                    //                              }
                                                 
                                                 
                    //                             //  foreach($week_end_days as $week_day_res){
                    //                             //      if($week_day_res == $day){
                    //                             //          $week_end_day_found = true;
                    //                             //      }
                    //                             //  }
                    //                             //   if($week_end_day_found){
                                                      
                    //                             //  }
                    //                          }
                                             
                                             
                    //                         //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                             
                    //                         //  print_r($all_days);
                    //                          $total_price = $week_days_total + $week_end_days_totals;
                    //                     }
                                        
                                        
                    //                 $all_days_price = $total_price * $new_acc_res->acc_qty;
                                    
                    //                 // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                    //                 // die;
                                    
                    //                 // echo "The supplier Balance is ".$supplier_data->balance;
                    //                 $supplier_balance = $supplier_data->balance;
                    //                 $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                    
                    //                 // update Agent Balance
                                    
                    //                 DB::table('hotel_supplier_ledger')->insert([
                    //                     'supplier_id'=>$supplier_data->id,
                    //                     'payment'=>$all_days_price,
                    //                     'balance'=>$supplier_balance,
                    //                     'payable_balance'=>$supplier_payable_balance,
                    //                     'room_id'=>$room_data->id,
                    //                     'customer_id'=>$insert->customer_id,
                    //                     'date'=>date('Y-m-d'),
                    //                     'invoice_no'=>$insert->id,
                    //                     'available_from'=>$new_acc_res->acc_check_in,
                    //                     'available_to'=>$new_acc_res->acc_check_out,
                    //                     'room_quantity'=>$new_acc_res->acc_qty,
                    //                     ]);
                                        
                    //                 DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                    //                     'balance'=>$supplier_balance,
                    //                     'payable'=>$supplier_payable_balance
                    //                     ]);
                                    
                                    
                                      
                                                                
                    //             }
                    //             }
                    //         }
                    //     }
                    // }
                  
                    // if(isset($new_acc_more) && !empty($new_acc_more)){
                    //      foreach($new_acc_more as $new_acc_res){
                    //          $ele_found = false;
                             
                    //          foreach($arr_ele_found as $arr_res){
                    //              if($new_acc_res->more_hotelRoom_type_id == $arr_res){
                    //                  $ele_found = true;
                    //              }
                    //          }
                             
                    //          if(isset($new_acc_res->more_hotelRoom_type_id) AND !empty($new_acc_res->more_hotelRoom_type_id)){
                    //              if(!$ele_found){
                    //              $room_data = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->first();
                                    
                    //                 $update_booked = (int)$room_data->booked + (int)$new_acc_res->more_acc_qty;
                                    
                    //                 $room_update = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->update([
                    //                 'booked'=>$update_booked
                    //                 ]);
                                    
                    //                 // echo "room id is from new".$new_acc_res->more_hotelRoom_type_id;
                    //                 // print_r($room_data);
                                
                    //               DB::table('rooms_bookings_details')->insert([
                    //                             'room_id'=> $new_acc_res->more_hotelRoom_type_id,
                    //                             'booking_from'=> 'Invoices',
                    //                             'quantity'=> $new_acc_res->more_acc_qty,
                    //                             'booking_id'=> $insert->id,
                    //                             'date'=> date('Y-m-d',strtotime($insert->created_at)),
                    //                             'check_in'=> $new_acc_res->more_acc_check_in,
                    //                             'check_out'=> $new_acc_res->more_acc_check_out,
                    //                         ]);
                                            
                                    
                                            
                                            
                                            
                    //              $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
       
                    //              if(isset($supplier_data)){
                    //                     // echo "Enter hre ";
                                        
                    //                          $week_days_total = 0;
                    //                          $week_end_days_totals = 0;
                    //                          $total_price = 0;
                    //                          $new_acc_res->more_acc_check_in = date('Y-m-d',strtotime($new_acc_res->more_acc_check_in));
                    //                          $new_acc_res->more_acc_check_out = date('Y-m-d',strtotime($new_acc_res->more_acc_check_out));
                    //                         if($room_data->price_week_type == 'for_all_days'){
                    //                             $avaiable_days = dateDiffInDays($new_acc_res->more_acc_check_in, $new_acc_res->more_acc_check_out);
                    //                             $total_price = $room_data->price_all_days * $avaiable_days;
                    //                         }else{
                    //                              $avaiable_days = dateDiffInDays($new_acc_res->more_acc_check_in, $new_acc_res->more_acc_check_out);
                                                 
                    //                              $all_days = getBetweenDates($new_acc_res->more_acc_check_in, $new_acc_res->more_acc_check_out);
                                                 
                    //                             //  print_r($all_days);
                    //                              $week_days = json_decode($room_data->weekdays);
                    //                              $week_end_days = json_decode($room_data->weekends);
                                                 
                    //                              foreach($all_days as $day_res){
                    //                                  $day = date('l', strtotime($day_res));
                    //                                  $day = trim($day);
                    //                                  $week_day_found = false;
                    //                                  $week_end_day_found = false;
                                                    
                    //                                  foreach($week_days as $week_day_res){
                    //                                      if($week_day_res == $day){
                    //                                          $week_day_found = true;
                    //                                      }
                    //                                  }
                                              
                    //                                 // echo "  ".$room_data->weekdays_price;
                    //                                  if($week_day_found){
                    //                                      $week_days_total += $room_data->weekdays_price;
                    //                                  }else{
                    //                                      $week_end_days_totals += $room_data->weekends_price;
                    //                                  }
                                                     
                                                     
                    //                                 //  foreach($week_end_days as $week_day_res){
                    //                                 //      if($week_day_res == $day){
                    //                                 //          $week_end_day_found = true;
                    //                                 //      }
                    //                                 //  }
                    //                                 //   if($week_end_day_found){
                                                          
                    //                                 //  }
                    //                              }
                                                 
                                                 
                    //                             //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                 
                    //                             //  print_r($all_days);
                    //                              $total_price = $week_days_total + $week_end_days_totals;
                    //                         }
                                            
                                            
                    //                     $all_days_price = $total_price * $new_acc_res->more_acc_qty;
                                        
                    //                     // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                    //                     // die;
                                        
                    //                     // echo "The supplier Balance is ".$supplier_data->balance;
                    //                     $supplier_balance = $supplier_data->balance;
                    //                     $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                        
                    //                     // update Agent Balance
                                        
                    //                     DB::table('hotel_supplier_ledger')->insert([
                    //                         'supplier_id'=>$supplier_data->id,
                    //                         'payment'=>$all_days_price,
                    //                         'balance'=>$supplier_balance,
                    //                         'payable_balance'=>$supplier_payable_balance,
                    //                         'room_id'=>$room_data->id,
                    //                         'customer_id'=>$insert->customer_id,
                    //                         'date'=>date('Y-m-d'),
                    //                         'invoice_no'=>$insert->id,
                    //                         'available_from'=>$new_acc_res->more_acc_check_in,
                    //                         'available_to'=>$new_acc_res->more_acc_check_out,
                    //                         'room_quantity'=>$new_acc_res->more_acc_qty,
                    //                         ]);
                                            
                    //                     DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                    //                         'balance'=>$supplier_balance,
                    //                         'payable'=>$supplier_payable_balance
                    //                         ]);
                                        
                                        
                                          
                                                                    
                    //                 }
                    //             }
                    //          }
                    //      }
                    //  }
                    
                    // 1 Loop on Previous
                    // if(isset($prev_flight_pax)){
                    //     foreach($prev_flight_pax as $flight_prev_res){
                    //         $ele_found = false;
                    //         foreach($new_flight_pax as $flight_new_res){
                    //             if($flight_prev_res->flight_route_id_occupied == $flight_new_res->flight_route_id_occupied){
                    //                 $ele_found = true;
                    //                 $route_obj = DB::table('flight_rute')->where('id',$flight_prev_res->flight_route_id_occupied)->first();
                                    
                    //                 if($route_obj != null){
                    //                     // Calaculate Child Prev Price Differ
                    //                     $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_prev_res->flights_child_seats;
                    //                     $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                        
                    //                     $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                        
                    //                     // Calaculate Child New Price Differ
                    //                     $child_price_wi_adult_price_new = $route_obj->flights_per_person_price * $flight_new_res->flights_child_seats;
                    //                     $child_price_wi_child_price_new = $route_obj->flights_per_child_price * $flight_new_res->flights_child_seats;
                                        
                    //                     $price_diff_new = $child_price_wi_adult_price_new - $child_price_wi_child_price_new;
                                        
                    //                     // Calculate Final Differ
                    //                     $child_price_diff = $price_diff_new - $price_diff_prev;
                                        
                                        
                    //                     // Calaculate Infant Prev Price
                    //                     $infant_price_prev = $route_obj->flights_per_infant_price * $flight_prev_res->flights_infant_seats;
                                        
                    //                      // Calaculate Infant New Price
                    //                     $infant_price_new = $route_obj->flights_per_infant_price * $flight_new_res->flights_infant_seats;
                                        
                    //                     // Calculate Final Differ
                    //                     $infant_price_diff = $infant_price_new - $infant_price_prev;
                                        
                    //                     $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                        
                    //                     if($child_price_diff != 0 || $infant_price_diff != 0){
                    //                         $supplier_balance = $supplier_data->balance - $child_price_diff;
                                            
                    //                         $supplier_balance = $supplier_balance + $infant_price_diff;
                    //                         $total_differ = $infant_price_diff - $child_price_diff;
                                            
                    //                         DB::table('flight_supplier_ledger')->insert([
                    //                                     'supplier_id'=>$supplier_data->id,
                    //                                     'payment'=>$total_differ,
                    //                                     'balance'=>$supplier_balance,
                    //                                     'route_id'=>$flight_prev_res->flight_route_id_occupied,
                    //                                     'date'=>date('Y-m-d'),
                    //                                     'customer_id'=>$insert->customer_id,
                    //                                     'adult_price'=>$route_obj->flights_per_person_price,
                    //                                     'child_price'=>$route_obj->flights_per_child_price,
                    //                                     'infant_price'=>$route_obj->flights_per_infant_price,
                    //                                     'adult_seats_booked'=>$flight_new_res->flights_adult_seats,
                    //                                     'child_seats_booked'=>$flight_new_res->flights_child_seats,
                    //                                     'infant_seats_booked'=>$flight_new_res->flights_infant_seats,
                    //                                     'invoice_no'=>$insert->id,
                    //                                     'remarks'=>'Invoice Update',
                    //                                   ]);
                                                      
                    //                         DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                    //                     }
                    //                 }
                    //             }
                    //         }
                            
                    //         // If element Not Found in New
                    //         if(!$ele_found){
                    //             $route_obj = DB::table('flight_rute')->where('id',$flight_prev_res->flight_route_id_occupied)->first();
                    //                 // print_r($route_obj);
                    //                 // die;
                    //             if($route_obj != null){
                    //                 // Calaculate Child Prev Price Differ
                    //                 $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_prev_res->flights_child_seats;
                    //                 $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                    
                    //                 $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    
                    //                 // Calaculate Infant Prev Price
                    //                 $infant_price_prev = $route_obj->flights_per_infant_price * $flight_prev_res->flights_infant_seats;
                                    
                    //                 $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                    
                    //                 if($price_diff_prev != 0 || $infant_price_prev != 0){
                    //                     $supplier_balance = $supplier_data->balance + $price_diff_prev;
                                        
                    //                     $supplier_balance = $supplier_balance - $infant_price_prev;
                    //                     $total_differ = $price_diff_prev - $infant_price_prev;
                                        
                    //                     DB::table('flight_supplier_ledger')->insert([
                    //                                 'supplier_id'=>$supplier_data->id,
                    //                                 'payment'=>$total_differ,
                    //                                 'balance'=>$supplier_balance,
                    //                                 'route_id'=>$flight_prev_res->flight_route_id_occupied,
                    //                                 'date'=>date('Y-m-d'),
                    //                                 'customer_id'=>$insert->customer_id,
                    //                                 'adult_price'=>$route_obj->flights_per_person_price,
                    //                                 'child_price'=>$route_obj->flights_per_child_price,
                    //                                 'infant_price'=>$route_obj->flights_per_infant_price,
                    //                                 'adult_seats_booked'=>$flight_prev_res->flights_adult_seats,
                    //                                 'child_seats_booked'=>$flight_prev_res->flights_child_seats,
                    //                                 'infant_seats_booked'=>$flight_prev_res->flights_infant_seats,
                    //                                 'invoice_no'=>$insert->id,
                    //                                 'remarks'=>'Invoice Update',
                    //                               ]);
                                                  
                    //                     DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                
                    // 2 Loop on New 
                    // if(isset($new_flight_pax)){
                    //     foreach($new_flight_pax as $flight_new_res){
                    //         $pre_el_found = false;
                    //         foreach($prev_flight_pax as $flight_prev_res){
                    //             if($flight_prev_res->flight_route_id_occupied == $flight_new_res->flight_route_id_occupied){
                    //                 $pre_el_found = true;
                    //             }
                    //         }
                            
                    //         // If element Not Found in Prev
                    //         if(!$pre_el_found){
                    //             $route_obj = DB::table('flight_rute')->where('id',$flight_new_res->flight_route_id_occupied)->first();
                    //                 // dd($route_obj);
                    //                 // die;
                                    
                    //                 if($route_obj != null){
                    //                     // Calaculate Child Prev Price Differ
                    //                     $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_new_res->flights_child_seats;
                    //                     $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_new_res->flights_child_seats;
                                        
                    //                     $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                        
                                        
                    //                     // Calaculate Infant Prev Price
                    //                     $infant_price_prev = $route_obj->flights_per_infant_price * $flight_new_res->flights_infant_seats;
                                        
                    //                     $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                        
                    //                     if($price_diff_prev != 0 || $infant_price_prev != 0){
                    //                         $supplier_balance = $supplier_data->balance - $price_diff_prev;
                                            
                    //                         $supplier_balance = $supplier_balance + $infant_price_prev;
                    //                         $total_differ = $infant_price_prev - $price_diff_prev;
                                            
                    //                         DB::table('flight_supplier_ledger')->insert([
                    //                                     'supplier_id'=>$supplier_data->id,
                    //                                     'payment'=>$total_differ,
                    //                                     'balance'=>$supplier_balance,
                    //                                     'route_id'=>$flight_new_res->flight_route_id_occupied,
                    //                                     'date'=>date('Y-m-d'),
                    //                                     'customer_id'=>$insert->customer_id,
                    //                                     'adult_price'=>$route_obj->flights_per_person_price,
                    //                                     'child_price'=>$route_obj->flights_per_child_price,
                    //                                     'infant_price'=>$route_obj->flights_per_infant_price,
                    //                                     'adult_seats_booked'=>$flight_new_res->flights_adult_seats,
                    //                                     'child_seats_booked'=>$flight_new_res->flights_child_seats,
                    //                                     'infant_seats_booked'=>$flight_new_res->flights_infant_seats,
                    //                                     'invoice_no'=>$insert->id,
                    //                                     'remarks'=>'Invoice Update',
                    //                                   ]);
                                                      
                    //                         DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                    //                     }
                    //                 }
                    //         }
                    //     }
                    // }
                 
                    DB::commit();
                 
            } catch (Throwable $e) {
                echo $e;
                DB::rollback();
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
            return response()->json(['status'=>'success','message'=>'Quotation Updated Succesfully']); 
        }else{
            return response()->json(['status'=>'error','message'=>$id]); 
        }
    }
    
    public function confirm_manage_Package_Quotation(Request $req){
        $id     = $req->id;
        $q_S    = DB::table('addManageQuotationPackage')->where('id',$id)->update(['quotation_Invoice' => '1']);
        $i_S    = DB::table('add_manage_invoices')->where('quotation_id',$id)->update(['quotation_Invoice' => '0']);
        return response()->json(['message'=>'Both Status Update']);
    }
    
    public function confirm_Quotation(Request $req){
        $quotation_Status = DB::table('addManageQuotationPackage')->where('id',$req->id)->update(['quotation_status' => '1']);
        return response()->json(['message'=>'Quotation Status Update','quotation_Status'=>$quotation_Status]);
    }

    // Add Manage Quotation
    public function add_Manage_Quotation(Request $req){
        // Data
            $insert = new addManageQuotation();
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            $insert->quotation_prepared      = $req->quotation_prepared;
            $insert->quotation_valid_date    = $req->quotation_valid_date;
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            $insert->passengerDetailAdults   = $req->passengerDetailAdults;
            $insert->passengerDetailChilds   = $req->passengerDetailChilds;
            $insert->passengerDetailInfant   = $req->passengerDetailInfant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $invoiceId                              =  "AHQ".$generate_id;
            $insert->generate_id                    = $invoiceId;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            $insert->flights_details                = $req->flights_details;
            $insert->flights_details_more           = $req->more_flights_details;
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_fee                       = $req->visa_fee;
            
            $insert->without_markup_total_price_visa                       = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa                       = $req->markup_total_price_visa;
            
            $insert->visa_image                     = $req->visa_image;
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
            $insert->save();
            
            return response()->json(['status'=>'success','message'=>'Quotation Added Successfully']); 
            
            // $lastInsertId = $insert->id;
            // $current_Date = $insert->ceates_at;
            // $P_Name       = $insert->f_name.' '.$insert->middle_name;
            
            // if($lastInsertId){
            //     $pay_Invoice_Agent                  = new pay_Invoice_Agent();
            //     $pay_Invoice_Agent->invoice_Id      = $lastInsertId;
            //     $pay_Invoice_Agent->generate_id     = $generate_id;
            //     $pay_Invoice_Agent->customer_id     = $req->customer_id;
            //     $pay_Invoice_Agent->agent_Name      = $req->agent_Name;
            //     $pay_Invoice_Agent->passenger_Name  = $P_Name;
            //     $pay_Invoice_Agent->date            = $current_Date;
            //     $pay_Invoice_Agent->total_Amount    = $req->total_AmountIA;
            //     $pay_Invoice_Agent->save();
            //      return response()->json(['status'=>'success','message'=>'Agent_Invoice_Payment is add','pay_Invoice_Agent'=>$pay_Invoice_Agent]); 
            // }
            // else{
            //     return response()->json(['message'=>'success','tour'=>$insert,'status'=>'error','message2'=>'Agent_Invoice_Payment is not add']);
            // }
    }

    // View Quotations 
    public function view_Quotations(Request $req){
        $data1 = addManageQuotation::where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
        return response()->json([
            'data1' => $data1,
        ]);
    }

    // View QuotationsID
    public function view_QuotationsID(Request $req) {
        $a = addManageQuotation::find($req->id);
        return response()->json([
            'a' => $a
        ]);
        // echo $a ;
    }

    // Edit_Quotations
    public function edit_Quotations(Request $req){
        $data1 = addManageQuotation::find($req->id);
        $decode_DataOW = json_decode($data1->oneWayDetails);
        $decode_DataRT = json_decode($data1->roundTripDetails);
        $passengerDetailAdults = json_decode($data1->passengerDetailAdults);
        
        $tours=addManageQuotation::find($req->id);
        $categories=DB::table('activities_categories')->where('customer_id',$req->customer_id)->get();
        $attributes=DB::table('activities_attributes')->where('customer_id',$req->customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id','!=',$req->customer_id)->get();
        $all_countries = country::all();
        $all_countries_currency = country::all();
        $bir_airports=DB::table('bir_airports')->get();
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$req->customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$req->customer_id)->get();
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$req->customer_id)->get();

        return response()->json([
            'tours'                   => $tours,
            'data1'                   => $data1,
            'decode_DataOW'           => $decode_DataOW,
            'decode_DataRT'           => $decode_DataRT,
            'passengerDetailAdults'   => $passengerDetailAdults,
            'all_countries'           => $all_countries,
            'bir_airports'            => $bir_airports,
            'payment_gateways'        => $payment_gateways,
            'payment_modes'           => $payment_modes,
            'currency_Symbol'         => $currency_Symbol,
            'customer'                => $customer,
            'attributes'              => $attributes,
            'categories'              => $categories,
            'all_countries_currency'  => $all_countries_currency
      ]);
      // return view('template/frontend/userdashboard/pages/quotations/edit_Quotations',compact('data','decode_DataOW','decode_DataRT','passengerDetailAdults'));
   }
   
    // invoice_Quotations
    public function invoice_Quotations(Request $req){
        // $data1 = addManageQuotation::find($req->id);
        // $customer_data = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
        // $contact_details=DB::table('contact_details')->where('customer_id',$req->customer_id)->first();
        // return response()->json([
        //     'data1' => $data1,
        //     'customer_data' => $customer_data,
        //     'contact_details' => $contact_details
        // ]);
        
        DB::beginTransaction();
        try {
            $data1                      = addManageQuotationPackage::find($req->id);
            // dd($data1);
            $agent_data                 = DB::table('Agents_detail')->where('id',$data1->agent_Id)->first();
            $customer_data              = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
            $contact_details            = DB::table('contact_details')->where('customer_id',$req->customer_id)->first();
            $invoice_P_details          = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->get();
            $total_invoice_Payments     = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->first();
            $recieved_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('amount_paid');
            $remainig_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('remaining_amount');
            $all_countries              = country::all();
            return response()->json([
                'message'                   => 'success',
                'data1'                     => $data1,
                'customer_data'             => $customer_data,
                'contact_details'           => $contact_details,
                'invoice_P_details'         => $invoice_P_details,
                'total_invoice_Payments'    => $total_invoice_Payments,
                'recieved_invoice_Payments' => $recieved_invoice_Payments,
                'remainig_invoice_Payments' => $remainig_invoice_Payments,
                'all_countries' => $all_countries,
                'agent_data'=>$agent_data
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
   }

   // Update Manage Quotation
    public function update_Manage_Quotation(Request $req){
        $generate_id=rand(0,9999999);
        $insert = addManageQuotation::find($req->id);
        //1
        $insert->prefix                  = $req->prefix;
        $insert->f_name                  = $req->f_name;
        $insert->middle_name             = $req->middle_name;
        $insert->surname                 = $req->surname;
        $insert->email                   = $req->email;
        $insert->contact_landline        = $req->contact_landline;
        $insert->mobile                  = $req->mobile;
        $insert->contact_work            = $req->contact_work;
        $insert->postCode                = $req->postCode;
        $insert->country                 = $req->country;
        $insert->city                    = $req->city;
        $insert->primery_address         = $req->primery_address;
        $insert->quotation_prepared      = $req->quotation_prepared;
        $insert->quotation_valid_date    = $req->quotation_valid_date;
        //2
        $insert->adults                  = $req->adults;
        $insert->childs                  = $req->childs;
        $insert->infant                  = $req->infant;
        $insert->passengerDetailAdults   = $req->passengerDetailAdults;
        $insert->passengerDetailChilds   = $req->passengerDetailChilds;
        $insert->passengerDetailInfant   = $req->passengerDetailInfant;
        // Date
        $currDateTime = date('Y-m-d H:i:s');
        $insert->created_at = $currDateTime;
        // dd($insert);
        $insert->generate_id=$generate_id;
        $insert->customer_id=$req->customer_id;
        $insert->title=$req->title;
        $insert->external_packages=$req->external_packages;
        $insert->content=$req->content;
        $insert->categories=$req->tour_categories;
        $insert->tour_attributes=$req->tour_attributes;
        $insert->no_of_pax_days=$req->no_of_pax_days;
        $insert->destination_details=$req->destination_details;
        $insert->destination_details_more=$req->destination_details_more;
        $insert->flights_details=$req->flights_details;
        $insert->flights_details_more=$req->more_flights_details;
        $insert->accomodation_details=$req->accomodation_details;
        $insert->transportation_details=$req->transportation_details;
        $insert->transportation_details_more=$req->transportation_details_more;
        $insert->visa_type=$req->visa_type;
        $insert->visa_rules_regulations=$req->visa_rules_regulations;
        $insert->visa_fee=$req->visa_fee;
        $insert->visa_image=$req->visa_image;
        $insert->quad_grand_total_amount=$req->quad_grand_total_amount;
        $insert->triple_grand_total_amount=$req->triple_grand_total_amount;
        $insert->double_grand_total_amount=$req->double_grand_total_amount;
        $insert->quad_cost_price=$req->quad_cost_price;
        $insert->triple_cost_price=$req->triple_cost_price;
        $insert->double_cost_price=$req->double_cost_price;
        $insert->all_markup_type=$req->all_markup_type;
        $insert->all_markup_add=$req->all_markup_add;
        $insert->gallery_images=$req->gallery_images;
        $insert->start_date=$req->start_date;
        $insert->end_date=$req->end_date;
        $insert->time_duration=$req->time_duration;
        $insert->tour_location=$req->tour_location;
        $insert->whats_included=$req->whats_included;
        $insert->whats_excluded=$req->whats_excluded;
        $insert->currency_symbol=$req->currency_symbol;
        $insert->tour_publish=$req->tour_publish;
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
        $insert->update();
        return response()->json(['message'=>'Success']);
   }

   // Add Bookings
   public function add_Bookings(Request $req){
      $currDateTime = date('Y-m-d H:i:s');
      $data = addManageQuotation::where('id',$req->id)->update([
         'confirm' => '1',
         'created_at' => $currDateTime,
      ]);
      return response()->json([
         'data' => $data,
      ]);
   }

   // View Bookings
   public function view_Bookings(){
      $data1 = addManageQuotation::all();
      return response()->json([
         'data1' => $data1,
      ]);
   }

   // Hotel Makkah
   public function hotel_Makkah_Room(Request $req){
      $available_From = $req->availible_from;
      $available_To   = $req->availible_to;
      $rooms  = DB::table('rooms')->where('availible_from','<=',$available_From)->where('availible_to','>=',$available_To)
               ->join('rooms_types','rooms.room_type_id','=','rooms_types.id')
               ->join('hotels','rooms.hotel_id','=','hotels.id')->where('property_city',1)
               ->get();
      return response()->json([
         'available_From' => $available_From,
         'available_To' => $available_To,
         'rooms' => $rooms,
      ]);
   }

   // Room Makkah
   public function makkah_Room(Request $req){
      $rooms = Rooms::where('hotel_id',$req->id)->join('rooms_types','rooms.room_type_id','=','rooms_types.id')->get();
      return response()->json([
         'rooms' => $rooms,
      ]);
   }

   // Hotel Madinah
   public function hotel_Madinah_Room(Request $req){
      $available_From = $req->availible_from;
      $available_To   = $req->availible_to;
      $rooms  = DB::table('rooms')->where('availible_from','<=',$available_From)->where('availible_to','>=',$available_To)
               ->join('rooms_types','rooms.room_type_id','=','rooms_types.id')
               ->join('hotels','rooms.hotel_id','=','hotels.id')->where('property_city',2)
               ->get();
      return response()->json([
         'available_From' => $available_From,
         'available_To' => $available_To,
         'rooms' => $rooms,
      ]);
   }

   // Room Madinah 
   public function madinah_Room(Request $req){
      $rooms = Rooms::where('hotel_id',$req->id)->join('rooms_types','rooms.room_type_id','=','rooms_types.id')->get();
      return response()->json([
         'rooms' => $rooms,
      ]);
   }

}
