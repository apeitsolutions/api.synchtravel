<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Agents_detail;
use App\Models\addManageInvoice;
use App\Models\pay_Invoice_Agent;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\Tour;
use App\Models\Active;
use App\Models\country;
use App\Models\Activities;
use App\Models\payInvoiceAgent;
use App\Models\Request_invoice;
use App\Mail\SendMailInvoice;
use DB;

class InvoiceRequestController extends Controller
{
    
     public function request_recieve_invoice(Request $req){
        
        $insert = new pay_Invoice_Agent();
        $insert->invoice_Id         = $req->invoice_Id;
        $insert->type         = 'request_invoice';
        $insert->generate_id        = $req->generate_id;
        $insert->customer_id        = $req->customer_id;
        $insert->agent_Name         = $req->agent_Name;
        $insert->date               = $req->date;
        $insert->passenger_Name     = $req->passenger_Name;
        $insert->total_Amount       = $req->total_Amount;
        $insert->recieved_Amount    = $req->recieved_Amount;
        $insert->remaining_Amount   = $req->remaining_Amount;
        $insert->amount_Paid        = $req->amount_Paid;
        $insert->passenger_email        = $req->passenger_email;
        
        $insert->payment_method    = $req->payment_method;
        $insert->transactionid   = $req->transactionid;
        $insert->accountno        = $req->accountno;
        
        $insert->save();
        return response()->json([
            'message' => 'Success',
        ]);
    }
       
  public function request_invoice_pay_amount(Request $request){
        // dd($request);
        $data1                      = DB::table('request_invoices')->where('id',$request->id)->get();
        $amount_Paid                = DB::table('pay_Invoice_Agent')->where('type','request_invoice')->where('invoice_Id',$request->id)->sum('amount_Paid');
        return response()->json([
            'data1'         => $data1,
            'amount_Paid'   => $amount_Paid,
        ]);
    }
 
    public function get_invoice_data(Request $req)
 {
    $data = Request_invoice::where('id',$req->invoice_id)->first();
        return response()->json([
            'data' => $data, 
        ]);
 }
     public function request_invoice_confirmed_view(Request $req)
 {
     
    $data = Request_invoice::where('id',$req->id)->first();
    
    $customer_data              = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
        $contact_details            = DB::table('contact_details')->where('customer_id',$req->customer_id)->first();
        $invoice_P_details          = DB::table('pay_Invoice_Agent')->where('type','request_invoice')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->get();
        $total_invoice_Payments     = DB::table('pay_Invoice_Agent')->where('type','request_invoice')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->first();
        $recieved_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('type','request_invoice')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('amount_paid');
        $remainig_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('type','request_invoice')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('remaining_amount');
    
    
    
        return response()->json([
            'data' => $data, 
            'customer_data' => $customer_data, 
            'contact_details' => $contact_details, 
            'invoice_P_details' => $invoice_P_details, 
            'total_invoice_Payments' => $total_invoice_Payments, 
            'recieved_invoice_Payments' => $recieved_invoice_Payments, 
            'remainig_invoice_Payments' => $remainig_invoice_Payments, 
        ]);
 }
 public function view_request_Invoices(Request $req)
 {
    $data1 = Request_invoice::where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
     $Agents_detail  = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        return response()->json([
            'data1' => $data1, 
            'Agents_detail' => $Agents_detail, 
        ]);
 }
    
    public function request_Invoices_submit(Request $req){
        // Data
        
        DB::beginTransaction();
                     try {
                        $insert = new Request_invoice();
                        //1
                        $insert->customer_id             = $req->customer_id;
                        $insert->agent_Name              = $req->agent_Name;
                         $insert->agent_id              = $req->agent_id;
                         $insert->currency_conversion    = $req->currency_conversion;
                       
                        $insert->prefix                  = $req->prefix;
                        $insert->f_name                  = $req->f_name;
                        $insert->middle_name             = $req->middle_name;
                        $insert->gender                 = $req->gender;
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
                        $insert->generate_id                    = $generate_id;
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
                         $insert->meal_markup_details=$req->meal_markup_details;
                        $insert->save();
                        
                        $lastInsertId = $insert->id;
                        $current_Date = $insert->ceates_at;
                        $P_Name       = $insert->f_name.' '.$insert->middle_name;
                        
                
                            $pay_Invoice_Agent                  = new pay_Invoice_Agent();
                            $pay_Invoice_Agent->invoice_Id      = $lastInsertId;
                            $pay_Invoice_Agent->generate_id     = $generate_id;
                            $pay_Invoice_Agent->customer_id     = $req->customer_id;
                            $pay_Invoice_Agent->agent_Name      = $req->agent_Name;
                            $pay_Invoice_Agent->passenger_Name  = $P_Name;
                            $pay_Invoice_Agent->date            = $current_Date;
                            $pay_Invoice_Agent->total_Amount    = $req->total_AmountIA;
                            $pay_Invoice_Agent->save();
                            
                             DB::commit();
                             return response()->json(['status'=>'success','message'=>'Agent_Invoice_Payment is add','pay_Invoice_Agent'=>$pay_Invoice_Agent]); 
                         
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['message'=>'error']);
                        }
    }
    
    
    public function request_Invoices(Request $req){
        $customer_id=$req->customer_id;
        $categories=DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes=DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries = country::all();
        $all_countries_currency = country::all();
        $bir_airports=DB::table('bir_airports')->get();
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail  = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $mange_currencies  = DB::table('mange_currencies')->where('customer_id',$req->customer_id)->get();
        $user_hotels    = Hotels::where('owner_id',$customer_id)->get();
        return response()->json(['message'=>'success','mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes]);
    }
    
    
    
    public function request_invoice_confirmed(Request $req)
 {
     
     $customer_id=$req->customer_id;
        $categories=DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes=DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries = country::all();
        $all_countries_currency = country::all();
        $bir_airports=DB::table('bir_airports')->get();
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail  = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $mange_currencies  = DB::table('mange_currencies')->where('customer_id',$req->customer_id)->get();
        $user_hotels    = Hotels::where('owner_id',$customer_id)->get();
     
    $get_invoice = Request_invoice::where('id',$req->invoice_id)->first();
         return response()->json(['message'=>'success','get_invoice'=>$get_invoice,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes]);
 }
 
 
 public function request_invoice_confirmed_submit(Request $req){

$id=$req->id;
            $insert =Request_invoice::find($id);
            if($insert)
            {
            //1
            DB::beginTransaction();
                     try {
                         
                            $insert->services             = $req->services;
                            $insert->currency_conversion  = $req->currency_conversion;
                            $insert->agent_id  = $req->agent_id;
                           
                            $insert->option_date                  = $req->option_date;
                            $insert->reservation_number                  = $req->reservation_number;
                            $insert->hotel_reservation_number             = $req->hotel_reservation_number;
                            $insert->meal_option                 = $req->meal_option;
                            $insert->NotIncluded_with_price                 = $req->NotIncluded_with_price;
                            $insert->hotel_count                 = $req->hotel_count;
                            $insert->hotel_meal_type                 = $req->hotel_meal_type;
                             $insert->meal_option_details                 = $req->meal_option_details;
                            $insert->customer_id             = $req->customer_id;
                            
                            $insert->meal_markup_details             = $req->meal_markup_details;
                            
                            $insert->agent_Name              = $req->agent_Name;
                            $insert->prefix                  = $req->prefix;
                            $insert->f_name                  = $req->f_name;
                            $insert->middle_name             = $req->middle_name;
                            $insert->gender                 = $req->gender;
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
                            $insert->generate_id                    = $generate_id;
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
                            $insert->confirm=1;
                            $insert->update();
                            
                            $lastInsertId = $insert->id;
                            $current_Date = $insert->ceates_at;
                            $P_Name       = $insert->f_name.' '.$insert->middle_name;
                            
                            
                                $pay_Invoice_Agent                  = new pay_Invoice_Agent();
                                $pay_Invoice_Agent->invoice_Id      = $lastInsertId;
                                $pay_Invoice_Agent->generate_id     = $generate_id;
                                $pay_Invoice_Agent->customer_id     = $req->customer_id;
                                $pay_Invoice_Agent->agent_Name      = $req->agent_Name;
                                $pay_Invoice_Agent->passenger_Name  = $P_Name;
                                $pay_Invoice_Agent->date            = $current_Date;
                                $pay_Invoice_Agent->total_Amount    = $req->total_AmountIA;
                                 $pay_Invoice_Agent->type    = 'request_invoice';
                                $pay_Invoice_Agent->save();
                                
                                DB::commit();  
                                return response()->json(['status'=>'success','message'=>'Agent_Invoice_Payment is add','pay_Invoice_Agent'=>$pay_Invoice_Agent]); 
                            
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['message'=>'error']);
                        }
    }
 
 }
 
  public function request_invoice_edit(Request $req)
 {
     
     $customer_id=$req->customer_id;
        $categories=DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes=DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries = country::all();
        $all_countries_currency = country::all();
        $bir_airports=DB::table('bir_airports')->get();
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail  = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $mange_currencies  = DB::table('mange_currencies')->where('customer_id',$req->customer_id)->get();
        $user_hotels    = Hotels::where('owner_id',$customer_id)->get();
     
    $get_invoice = Request_invoice::where('id',$req->invoice_id)->first();
         return response()->json(['message'=>'success','get_invoice'=>$get_invoice,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes]);
 }
 
 
 public function request_invoice_edit_submit(Request $req){

$id=$req->id;
            $insert =Request_invoice::find($id);
            if($insert)
            {
            //1
            DB::beginTransaction();
                     try {
                            $insert->services             = $req->services;
                            $insert->currency_conversion  = $req->currency_conversion;
                             $insert->agent_id  = $req->agent_id;
                            $insert->option_date                  = $req->option_date;
                            $insert->reservation_number                  = $req->reservation_number;
                            $insert->hotel_reservation_number             = $req->hotel_reservation_number;
                            $insert->meal_option                 = $req->meal_option;
                            $insert->NotIncluded_with_price                 = $req->NotIncluded_with_price;
                            $insert->hotel_count                 = $req->hotel_count;
                            $insert->hotel_meal_type                 = $req->hotel_meal_type;
                             $insert->meal_option_details                 = $req->meal_option_details;
                            $insert->customer_id             = $req->customer_id;
                            
                            $insert->meal_markup_details             = $req->meal_markup_details;
                            
                            $insert->agent_Name              = $req->agent_Name;
                            $insert->prefix                  = $req->prefix;
                            $insert->f_name                  = $req->f_name;
                            $insert->middle_name             = $req->middle_name;
                            $insert->gender                 = $req->gender;
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
                            $insert->generate_id                    = $generate_id;
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
                            $insert->confirm=1;
                            $insert->update();
                            
                            $lastInsertId = $insert->id;
                            $current_Date = $insert->ceates_at;
                            $P_Name       = $insert->f_name.' '.$insert->middle_name;
                    
                                $pay_Invoice_Agent                  = new pay_Invoice_Agent();
                                $pay_Invoice_Agent->invoice_Id      = $lastInsertId;
                                $pay_Invoice_Agent->generate_id     = $generate_id;
                                $pay_Invoice_Agent->customer_id     = $req->customer_id;
                                $pay_Invoice_Agent->agent_Name      = $req->agent_Name;
                                $pay_Invoice_Agent->passenger_Name  = $P_Name;
                                $pay_Invoice_Agent->date            = $current_Date;
                                $pay_Invoice_Agent->total_Amount    = $req->total_AmountIA;
                                 $pay_Invoice_Agent->type    = 'request_invoice';
                                $pay_Invoice_Agent->save();
                                
                                DB::commit();  
                                 return response()->json(['status'=>'success','message'=>'Agent_Invoice_Payment is add','pay_Invoice_Agent'=>$pay_Invoice_Agent]); 
                          
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['message'=>'error']);
                        }
    }
 
 }
 
 
}