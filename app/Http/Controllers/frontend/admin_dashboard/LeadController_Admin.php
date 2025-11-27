<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Agents_detail;
use App\Models\addManageInvoice;
use App\Models\booking_customers;
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
use App\Models\addLead;
use App\Models\addManageQuotationPackage;
use DB;

class LeadController_Admin extends Controller
{
    public function view_Lead_Admin(Request $req){
        $data               = DB::table('addLead')->orderBy('id', 'DESC')->get();
        $all_countries      = country::all();
        $Agents_detail      = DB::table('Agents_detail')->get();
        $customers_data     = DB::table('booking_customers')->get();
        $lead_Quotations    = DB::table('addManageQuotationPackage')->get();
        $all_Users          = DB::table('customer_subcriptions')->get();
        return view('template/frontend/userdashboard/pages/Lead/view_Lead',compact(['all_Users','data','all_countries','Agents_detail','customers_data','lead_Quotations']));
    }
    
    public function view_manage_Lead_Quotation_SingleAll_Admin(Request $req,$lead_id){
        $data               = addManageQuotationPackage::where('lead_id', $lead_id)->get();
        $all_countries      = country::all();
        $all_countries1     = country::all();
        $booking_customers  = DB::table('booking_customers')->get();
        $Agents_detail      = DB::table('Agents_detail')->get();
        $all_Users          = DB::table('customer_subcriptions')->get();
        return view('template/frontend/userdashboard/pages/Lead/view_manage_Lead_Quotation_SingleAll',compact(['all_Users','lead_id','Agents_detail','data','all_countries','all_countries1','booking_customers']));
    }
    
    public function view_manage_Lead_Quotation_Single_Admin(Request $req){
        $data               = addManageQuotationPackage::where('id', $req->id)->first();
        $all_countries      = country::all();
        $booking_customers  = DB::table('booking_customers')->get();
        $Agents_detail      = DB::table('Agents_detail')->get();
        return view('template/frontend/userdashboard/pages/Lead/view_manage_Lead_Quotation_Single',compact(['data','all_countries','booking_customers','Agents_detail']));
    }
    
    public function invoice_Quotations_Admin (Request $req){
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
        
        $data                       = $data1;
        $passengerDetailAdults      = Json_decode($data->passengerDetailAdults);
        $passengerDetailChilds      = $data->passengerDetailChilds;
        $flights_details            = Json_decode($data->flights_details);
        $flights_details_more       = Json_decode($data->flights_details_more);
        $transportation_details     = Json_decode($data->transportation_details);
        $markup_details             = Json_decode($data->markup_details);
        
        return view('template/frontend/userdashboard/pages/quotations/invoice_Quotations',compact(['all_countries','invoice_P_details','total_invoice_Payments','recieved_invoice_Payments','remainig_invoice_Payments','flights_details_more','data','passengerDetailChilds','flights_details','transportation_details','contact_details','customer_data','passengerDetailAdults','markup_details']));
    }
    
    public function view_manage_Lead_Quotation_Admin(Request $req){
        $data               = addManageQuotationPackage::orderBy('id', 'DESC')->get();
        $all_countries      = country::all();
        $booking_customers  = DB::table('booking_customers')->get();
        $Agents_detail      = DB::table('Agents_detail')->get();
        $all_Users          = DB::table('customer_subcriptions')->get();
        return view('template/frontend/userdashboard/pages/Lead/view_manage_Lead_Quotation',compact(['all_Users','data','all_countries','Agents_detail','booking_customers']));
    }
    
    public function view_Lead_Process_Admin(Request $req){
        $all_countries      = country::all();
        $Agents_detail      = DB::table('Agents_detail')->get();
        $customers_data     = DB::table('booking_customers')->get();
        $lead_Quotations    = DB::table('addManageQuotationPackage')->get();
        $all_Users          = DB::table('customer_subcriptions')->get();
        
        $data = [];
        $all_leads = DB::table('addLead')->get();
        if(isset($all_leads) && $all_leads != null && $all_leads != ''){
            foreach($all_leads as $all_leads_value){
                $lead_quotation         = DB::table('addManageQuotationPackage')->where('lead_id',$all_leads_value->id)->get();
                $lead_quotation_count   = count($lead_quotation);
                if(isset($lead_quotation) && $lead_quotation != null && $lead_quotation != ''){
                    if($lead_quotation_count > 0){
                        foreach($lead_quotation as $lead_quotation_value){
                            if($lead_quotation_value->quotation_status != '1'){
                                $single_leads = DB::table('addLead')->where('id',$all_leads_value->id)->first();
                                array_push($data,$single_leads);
                            }
                        }
                    }
                }
            }
        }
        
        return view('template/frontend/userdashboard/pages/Lead/view_Lead',compact(['all_Users','data','all_countries','Agents_detail','customers_data','lead_Quotations']));
    }
    
    public function view_atol_certificate_Admin(Request $request,$id){
        $inv_data = DB::table('add_manage_invoices')->where('id',$id)->first();
        return view('template/frontend/userdashboard/emails/lead_mail_attachments/atol',compact('inv_data'));
    }
    
    // Invoices
    public function create_Lead(Request $req){
        DB::beginTransaction();
        try {
            $customer_id            = $req->customer_id;
            $all_countries          = country::all();
            $Agents_detail          = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $customers_data         = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
            $all_Invoice            = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->select('id','services')->get();
            $all_quotations         = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->select('id','services')->get();
            $all_packages           = DB::table('tours')->where('customer_id',$req->customer_id)->select('id','title')->get();
            return response()->json([
                'message'           => 'success',
                'all_countries'     => $all_countries,
                'Agents_detail'     => $Agents_detail,
                'customers_data'    => $customers_data,
                'all_Invoice'       => $all_Invoice,
                'all_quotations'    => $all_quotations,
                'all_packages'      => $all_packages,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function view_Lead(Request $req){
        DB::beginTransaction();
        try {
            $data1              = DB::table('addLead')->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
            $all_countries      = country::all();
            $Agents_detail      = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $customers_data     = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
            $lead_Quotations    = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->get();
            return response()->json([
                'message'           => 'success',
                'data1'             => $data1,
                'all_countries'     => $all_countries,
                'Agents_detail'     => $Agents_detail,
                'customers_data'    => $customers_data,
                'lead_Quotations'   => $lead_Quotations,
            ]);
        } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
            }
    }
    
    public function add_Lead(Request $request){
        DB::beginTransaction();
        try {
            $insert = new addLead();
            $insert->token                      = $request->token;
            $insert->customer_id                = $request->customer_id;
            // Communication Channel
			$insert->communication_channel      = $request->communication_channel;
            $insert->channel_phone              = $request->channel_phone;
            $insert->channel_date               = $request->channel_date;
            $insert->channel_picked_by          = $request->channel_picked_by;
            // Client Type
            $insert->client_type                = $request->client_type;
            $insert->agent_Company_Name         = $request->agent_Company_Name;
            $insert->agent_Id                   = $request->agent_Id;
            $insert->agent_Name                 = $request->agent_Name;
            $insert->customer_name              = $request->customer_name;
            // Passeneger Details
            $insert->booked_by_passenger        = $request->booked_by_passenger;
            $insert->title                      = $request->title;
            $insert->first_Name                 = $request->first_Name;
            $insert->last_Name                  = $request->last_Name;
            $insert->gender                     = $request->gender;
            $insert->date_of_birth              = $request->date_of_birth;
            $insert->email                      = $request->email;
            $insert->phone_No                   = $request->phone_No;
            $insert->mobile_No                  = $request->mobile_No;
            $insert->fax                        = $request->fax;
            $insert->postal_Code                = $request->postal_Code;
            $insert->country                    = $request->country;
            $insert->city                       = $request->city;
            $insert->address                    = $request->address;
            $insert->opening_Balance            = $request->opening_Balance;
            $insert->Agent_Name_new             = $request->Agent_Name_new;
            // Passport Details
            $insert->passport_type              = $request->passport_type;
            $insert->preference_type            = $request->preference_type;
            $insert->first_Name_passport        = $request->first_Name_passport;
            $insert->last_Name_passport         = $request->last_Name_passport;
            $insert->nationality_passport       = $request->nationality_passport;
            $insert->date_of_birth_passport     = $request->date_of_birth_passport;
            $insert->passport_Number            = $request->passport_Number;
            $insert->passport_Expiry            = $request->passport_Expiry;
            // Preference Details
            $insert->location                   = $request->location;
            $insert->more_location_details      = $request->more_location_details;
            // Emergency Number
            $insert->emergency_details          = $request->emergency_details;
            // Refered By
            $insert->refered_by_new             = $request->refered_by_new;
            $insert->refered_agent_Name         = $request->refered_agent_Name;
            $insert->refered_customer_name      = $request->refered_customer_name;
            // Note
            $insert->lead_note                  = $request->lead_note;
            
            $insert->save();
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Lead added Succesfully']); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function edit_Lead(Request $req){
        DB::beginTransaction();
        try {
            $customer_id            = $req->customer_id;
            $all_countries          = country::all();
            $Agents_detail          = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $customers_data         = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
            $all_Invoice            = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->select('id','services')->get();
            $all_quotations         = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->select('id','services')->get();
            $all_packages           = DB::table('tours')->where('customer_id',$req->customer_id)->select('id','title')->get();
            $lead_details           = DB::table('addLead')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
            return response()->json([
                'message'           => 'success',
                'all_countries'     => $all_countries,
                'Agents_detail'     => $Agents_detail,
                'customers_data'    => $customers_data,
                'all_Invoice'       => $all_Invoice,
                'all_quotations'    => $all_quotations,
                'all_packages'      => $all_packages,
                'lead_details'      => $lead_details,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function update_Lead(Request $request){
        // dd('stop');
        DB::beginTransaction();
        try {
            $id                                 = $request->lead_id;
            $insert                             = addLead::find($id);
            
            $agent_Id       = $request->agent_Id;
            $customer_Id    = $request->customer_name;
            
            if($customer_Id == '-1'){
                $Agents_detail = Agents_detail::find($agent_Id);
                // dd($agent_Id);
                if(isset($Agents_detail) && $Agents_detail != null && $Agents_detail != ''){
                    $Agents_detail->agent_Name              = $request->Agent_Name_new;
                    $Agents_detail->agent_Email             = $request->email;
                    $Agents_detail->agent_Address           = $request->address;
                    $Agents_detail->agent_contact_number    = $request->mobile_No;
                    $Agents_detail->company_name            = $request->first_Name;
                    $Agents_detail->company_email           = $request->email;
                    $Agents_detail->company_contact_number  = $request->mobile_No;
                    $Agents_detail->company_address         = $request->address;
                    $Agents_detail->update();
                }
            }
            
            if($customer_Id != '-1'){
                $customer_detail = booking_customers::find($customer_Id);
                // dd($customer_Id);
                if(isset($customer_detail) && $customer_detail != null && $customer_detail != ''){
                    $customer_detail->name              = $request->first_Name;
                    $customer_detail->email             = $request->email;
                    $customer_detail->phone             = $request->mobile_No;
                    $customer_detail->whatsapp          = $request->mobile_No;
                    $customer_detail->gender            = $request->gender;
                    $customer_detail->country           = $request->country;
                    $customer_detail->city              = $request->city;
                    $customer_detail->post_code         = $request->postal_Code;
                    $customer_detail->save();
                }
            }
            
            // dd($request->city);
            
            // Communication Channel
			$insert->communication_channel      = $request->communication_channel;
            $insert->channel_phone              = $request->channel_phone;
            $insert->channel_date               = $request->channel_date;
            $insert->channel_picked_by          = $request->channel_picked_by;
            // Client Type
            $insert->client_type                = $request->client_type;
            $insert->agent_Company_Name         = $request->agent_Company_Name;
            $insert->agent_Id                   = $request->agent_Id;
            $insert->agent_Name                 = $request->agent_Name;
            $insert->customer_name              = $request->customer_name;
            // Passeneger Details
            $insert->booked_by_passenger        = $request->booked_by_passenger;
            $insert->title                      = $request->title;
            $insert->first_Name                 = $request->first_Name;
            $insert->last_Name                  = $request->last_Name;
            $insert->gender                     = $request->gender;
            $insert->date_of_birth              = $request->date_of_birth;
            $insert->email                      = $request->email;
            $insert->phone_No                   = $request->phone_No;
            $insert->mobile_No                  = $request->mobile_No;
            $insert->fax                        = $request->fax;
            $insert->postal_Code                = $request->postal_Code;
            $insert->country                    = $request->country;
            $insert->city                       = $request->city;
            $insert->address                    = $request->address;
            $insert->opening_Balance            = $request->opening_Balance;
            $insert->Agent_Name_new             = $request->Agent_Name_new;
            // Passport Details
            $insert->passport_type              = $request->passport_type;
            $insert->preference_type            = $request->preference_type;
            $insert->first_Name_passport        = $request->first_Name_passport;
            $insert->last_Name_passport         = $request->last_Name_passport;
            $insert->nationality_passport       = $request->nationality_passport;
            $insert->date_of_birth_passport     = $request->date_of_birth_passport;
            $insert->passport_Number            = $request->passport_Number;
            $insert->passport_Expiry            = $request->passport_Expiry;
            // Preference Details
            $insert->location                   = $request->location;
            $insert->more_location_details      = $request->more_location_details;
            // Emergency Number
            $insert->emergency_details          = $request->emergency_details;
            // Refered By
            $insert->refered_by_new             = $request->refered_by_new;
            $insert->refered_agent_Name         = $request->refered_agent_Name;
            $insert->refered_customer_name      = $request->refered_customer_name;
            // Note
            $insert->lead_note                  = $request->lead_note;
            
            $insert->update();
            
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Lead(Agent/Customer) Updated Succesfully']); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function open_close_Lead(Request $req){
        DB::beginTransaction();
        try {
            $id             = $req->id;
            $insert         = addLead::find($id);
            $lead_status    = $req->status;
            if($lead_status == 'OPEN'){
                $insert->lead_status = NULL;
            }else{
                $insert->lead_status = 1;
            }
            $insert->update();
            return response()->json([
                'message' => 'success',
            ]);
        } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
            }
    }

    public function view_Lead_Process(Request $req){
        DB::beginTransaction();
        try {
            // $data1              = DB::table('addLead')->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
            $all_countries      = country::all();
            $Agents_detail      = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $customers_data     = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
            $lead_Quotations    = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->get();
            
            $data1 = [];
            $all_leads = DB::table('addLead')->where('customer_id',$req->customer_id)->get();
            if(isset($all_leads) && $all_leads != null && $all_leads != ''){
                foreach($all_leads as $all_leads_value){
                    $lead_quotation         = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->where('lead_id',$all_leads_value->id)->get();
                    $lead_quotation_count   = count($lead_quotation);
                    if(isset($lead_quotation) && $lead_quotation != null && $lead_quotation != ''){
                        if($lead_quotation_count > 0){
                            foreach($lead_quotation as $lead_quotation_value){
                                if($lead_quotation_value->quotation_status != '1'){
                                    $single_leads = DB::table('addLead')->where('customer_id',$req->customer_id)->where('id',$all_leads_value->id)->first();
                                    array_push($data1,$single_leads);
                                }
                            }
                        }
                    }
                }
            }
            
            return response()->json([
                'message'           => 'success',
                'data1'             => $data1,
                'all_countries'     => $all_countries,
                'Agents_detail'     => $Agents_detail,
                'customers_data'    => $customers_data,
                'lead_Quotations'   => $lead_Quotations,
            ]);
        } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
            }
    }
    
    public function get_customer_ifExist(Request $req){
        $customer_exist = DB::table('booking_customers')->where('customer_id',$req->customer_id)->where('email',$req->email)->first();
        // dd($customer_exist);
        if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
            return response()->json(['status1'=>'error','message1'=>'Customer Already Exist']);
        }else{
            return response()->json(['status1'=>'success','message1'=>'Customer Not Exist']);
        }
      
        // return view('template/frontend/userdashboard/pages/manage_Office/Agents/create_Agents',compact('Agents_detail'));  
    }
    
    public function create_lead_quotation(Request $req){
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
        $all_flight_routes      = DB::table('flight_rute')->where('customer_id',$req->customer_id)->get();
        $flight_suppliers       = DB::table('supplier')->where('customer_id',$req->customer_id)->get();
        
        $lead_deatils           = DB::table('addLead')->where('id',$req->lead_id)->first();
        
        return response()->json(['message'=>'success','lead_deatils'=>$lead_deatils,'flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'all_curr_symboles'=>$all_curr_symboles,'destination_details'=>$destination_details,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function create_lead_quotation_New(Request $req){
        $customer_id                = $req->customer_id;
        $categories                 = DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes                 = DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer                   = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries              = country::all();
        $all_countries_currency     = country::all();
        $bir_airports               = DB::table('bir_airports')->get();
        $payment_gateways           = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes              = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol            = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail              = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $user_hotels                = Hotels::where('owner_id',$customer_id)->get();
        $mange_currencies           = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
        $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        // $destination_details        = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $customers_data             = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
        $all_curr_symboles          = country::all();
        $all_flight_routes          = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
        $flight_suppliers           = DB::table('supplier')->where('customer_id',$customer_id)->get();
        $visa_supplier              = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
        $visa_types                 = DB::table('visa_types')->where('customer_id',$customer_id)->get();
        
        $tranfer_vehicle            = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
        $tranfer_destination        = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $tranfer_company            = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
        $mange_currencies           = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
        
        $lead_deatils               = DB::table('addLead')->where('id',$req->lead_id)->first();
        
        return response()->json(['message'=>'success','lead_deatils'=>$lead_deatils,'tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=> $tranfer_destination,'tranfer_supplier'=> $tranfer_supplier,'tranfer_company'=>$tranfer_company,'mange_currencies'=>$mange_currencies,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function add_manage_Lead_Quotation_Old(Request $req){
        
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
        $insert->lead_id                 = $req->lead_id;
        $insert->quotation_validity      = $req->quotation_validity;
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
    
    public function add_manage_Lead_Quotation(Request $req){
        
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
            
            if(isset($req->accomodation_details)){
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
            
                $accomodation_data      = json_encode($accomodation_data);
                $accomodation_more_data = json_encode($accomodation_more_data);
            }else{
                $accomodation_data = '';
                $accomodation_more_data = '';
            }
            
            // dd($accomodation_data);
            
            $insert = new addManageQuotationPackage();
            //new additon
            //1
            $insert->lead_id                 = $req->lead_id;
            $insert->quotation_validity      = $req->quotation_validity;
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
            
            $insert->accomodation_details           = $accomodation_data;
            $insert->accomodation_details_more      = $accomodation_more_data;
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
            
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            
            $insert->without_acc_cost_price         = $req->without_acc_cost_price;
            $insert->without_acc_sale_price_single  = $req->without_acc_sale_price_single;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            
            $insert->all_costing_details            = $req->all_costing_details;
            $all_services_quotation                 = 1;
            $insert->all_services_quotation         = $all_services_quotation;
            
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
        
            $insert->save();
            
            $agent_data = DB::table('Agents_detail')->where('id',$req->agent_Id)->select('id','balance')->first();
            if(isset($agent_data)){
                // echo "Enter hre ";
                $agent_balance = $agent_data->balance + $insert->total_sale_price_all_Services;
                
                // update Agent Balance
                DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                DB::table('agents_ledgers_new')->insert([
                    'agent_id'=>$agent_data->id,
                    'received'=>$insert->total_sale_price_all_Services,
                    'balance'=>$agent_balance,
                    'invoice_no'=>$insert->id,
                    'customer_id'=>$req->customer_id,
                    'date'=>date('Y-m-d'),
                    ]);
            }
            
            if($req->booking_customer_id != '-1'){
                 $customer_data = DB::table('booking_customers')->where('id',$req->booking_customer_id)->select('id','balance')->first();
                // print_r($agent_data);
                if(isset($customer_data)){
                    // echo "Enter hre ";
                    $customer_balance = $customer_data->balance + $insert->total_sale_price_all_Services;
                    
                    // update Agent Balance
                    
                    DB::table('customer_ledger')->insert([
                        'booking_customer'=>$customer_data->id,
                        'received'=>$insert->total_sale_price_all_Services,
                        'balance'=>$customer_balance,
                        'invoice_no'=>$insert->id,
                        'customer_id'=>$req->customer_id,
                        'date'=>date('Y-m-d'),
                        ]);
                        
                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                }
            }
            
            $invoice_id                 = $insert->id;
            
            $accomodation               = $accomodation_data;
            $more_accomodation_details  = $accomodation_more_data;
            
            $notification_insert = new alhijaz_Notofication();
            $notification_insert->type_of_notification_id   = $insert->id ?? ''; 
            $notification_insert->customer_id               = $insert->customer_id ?? ''; 
            $notification_insert->type_of_notification      = 'create_Invoice' ?? ''; 
            $notification_insert->generate_id               = $insert->generate_id ?? '';
            $notification_insert->notification_creator_name = $req->agent_Name ?? '';
            $notification_insert->total_price               = $insert->total_sale_price_all_Services ?? ''; 
            $notification_insert->remaining_price           = $insert->total_sale_price_all_Services ?? ''; 
            $notification_insert->notification_status       = '1' ?? ''; 
            
            $notification_insert->save();
            
            $flights_Pax_details = json_decode($req->flights_Pax_details);
            if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                foreach($flights_Pax_details as $value){
                    
                    DB::table('flight_seats_occupied')->insert([
                        'token'                         => $req->token,
                        'type'                          => 'Invoice',
                        'booking_id'                    => $invoice_id,
                        'flight_supplier'               => $req->flight_supplier,
                        'flight_route_id'               => $value->flight_route_id_occupied,
                        'flights_adult_seats'           => $value->flights_adult_seats,
                        'flights_child_seats'           => $value->flights_child_seats,
                        'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                    ]);
                    
                    // Update Flight Supplier Balance
                    $supplier_data = DB::table('supplier')->where('id',$req->flight_supplier)->first();
                    
                    //  Calculate Child Price
                    $child_price_wi_adult_price = $value->flights_cost_per_seats_adult * $value->flights_child_seats;
                    $child_price_wi_child_price = $value->flights_cost_per_seats_child * $value->flights_child_seats;
                    
                    $infant_price = $value->flights_cost_per_seats_infant * $value->flights_infant_seats;
                    
                    $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                    
                    if($price_diff != 0 || $infant_price != 0){
                        $supplier_balance = $supplier_data->balance - $price_diff;
                        
                        $supplier_balance = $supplier_balance + $infant_price;
                        $total_differ = $infant_price - $price_diff;
                        
                        DB::table('flight_supplier_ledger')->insert([
                                    'supplier_id'=>$supplier_data->id,
                                    'payment'=>$total_differ,
                                    'balance'=>$supplier_balance,
                                    'route_id'=>$value->flight_route_id_occupied,
                                    'date'=>date('Y-m-d'),
                                    'customer_id'=>$insert->customer_id,
                                    'adult_price'=>$value->flights_cost_per_seats_adult,
                                    'child_price'=>$value->flights_cost_per_seats_child,
                                    'infant_price'=>$value->flights_cost_per_seats_infant,
                                    'adult_seats_booked'=>$value->flights_adult_seats,
                                    'child_seats_booked'=>$value->flights_child_seats,
                                    'infant_seats_booked'=>$value->flights_infant_seats,
                                    'invoice_no'=>$insert->id,
                                    'remarks'=>'Invoice Booked',
                                  ]);
                                  
                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                    }
                }
            }
            
            if(isset($accomodation) && $accomodation != null && $accomodation != ''){
                $accomodation      = json_decode($accomodation_data);
                if(isset($accomodation)){
                    foreach($accomodation as $accomodation_res){
                    if(isset($accomodation_res->hotelRoom_type_idM)){
               
                    
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                                
                                if($room_data){
                                    
                                    
                                    $total_booked = $room_data->booked + $accomodation_res->acc_qty;
                                    
                                   
                                   DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $accomodation_res->hotelRoom_type_id,
                                             'booking_from'=>'Invoices',
                                             'quantity'=>$accomodation_res->acc_qty,
                                             'booking_id'=>$invoice_id,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->acc_check_in,
                                             'check_out'=>$accomodation_res->acc_check_out,
                                             ]);
                                    
                                
                                    DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                    
                                    // Update Hotel Supplier Balance
                            
                                       $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
           
                                     if(isset($supplier_data)){
                                            // echo "Enter hre ";
                                            
                                                 $week_days_total = 0;
                                                 $week_end_days_totals = 0;
                                                 $total_price = 0;
                                                 $accomodation_res->acc_check_in = date('Y-m-d',strtotime($accomodation_res->acc_check_in));
                                                 $accomodation_res->acc_check_out = date('Y-m-d',strtotime($accomodation_res->acc_check_out));
                                                if($room_data->price_week_type == 'for_all_days'){
                                                    $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                    $total_price = $room_data->price_all_days * $avaiable_days;
                                                }else{
                                                     $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                     
                                                     $all_days = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                     
                                                    //  print_r($all_days);
                                                     $week_days = json_decode($room_data->weekdays);
                                                     $week_end_days = json_decode($room_data->weekends);
                                                     
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
                                                  
                                                        // echo "  ".$room_data->weekdays_price;
                                                         if($week_day_found){
                                                             $week_days_total += $room_data->weekdays_price;
                                                         }else{
                                                             $week_end_days_totals += $room_data->weekends_price;
                                                         }
                                                         
                                                         
                                                        //  foreach($week_end_days as $week_day_res){
                                                        //      if($week_day_res == $day){
                                                        //          $week_end_day_found = true;
                                                        //      }
                                                        //  }
                                                        //   if($week_end_day_found){
                                                              
                                                        //  }
                                                     }
                                                     
                                                     
                                                    //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                     
                                                    //  print_r($all_days);
                                                     $total_price = $week_days_total + $week_end_days_totals;
                                                }
                                                
                                                
                                            $all_days_price = $total_price * $accomodation_res->acc_qty;
                                            
                                            // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                            // die;
                                            
                                            // echo "The supplier Balance is ".$supplier_data->balance;
                                            $supplier_balance = $supplier_data->balance;
                                            $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                            
                                            // update Agent Balance
                                            
                                            DB::table('hotel_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$all_days_price,
                                                'balance'=>$supplier_balance,
                                                'payable_balance'=>$supplier_payable_balance,
                                                'room_id'=>$room_data->id,
                                                'customer_id'=>$req->customer_id,
                                                'date'=>date('Y-m-d'),
                                                'invoice_no'=>$invoice_id,
                                                'available_from'=>$accomodation_res->acc_check_in,
                                                'available_to'=>$accomodation_res->acc_check_out,
                                                'room_quantity'=>$accomodation_res->acc_qty,
                                                ]);
                                                
                                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                'balance'=>$supplier_balance,
                                                'payable'=>$supplier_payable_balance
                                                ]);
                                            
                                            
                                              
                                                                        
                                        }
                                }
                     }
                }
                }
            }
            
            if(isset($more_accomodation_details)  && $more_accomodation_details != null && $more_accomodation_details != ''){
                // print_r($more_accomodation_details);
                $more_accomodation_details = json_decode($accomodation_more_data);
                if(isset($more_accomodation_details)){
                foreach($more_accomodation_details as $accomodation_res){
                     if(isset($accomodation_res->more_hotelRoom_type_idM)){
                     
                            
                            $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                                
                                if($room_data){
                                    
                               
                                    $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                    
                                    DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $accomodation_res->more_hotelRoom_type_id,
                                             'booking_from'=>'Invoices',
                                             'quantity'=>$accomodation_res->more_acc_qty,
                                             'booking_id'=>$invoice_id,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->more_acc_check_in,
                                             'check_out'=>$accomodation_res->more_acc_check_out,
                                             ]);
                                             
                                    DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                    
                                    
                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
           
                                     if(isset($supplier_data)){
                                            // echo "Enter hre ";
                                            
                                                 $week_days_total = 0;
                                                 $week_end_days_totals = 0;
                                                 $total_price = 0;
                                                 $accomodation_res->acc_check_in = date('Y-m-d',strtotime($accomodation_res->more_acc_check_in));
                                                 $accomodation_res->acc_check_out = date('Y-m-d',strtotime($accomodation_res->more_acc_check_out));
                                                if($room_data->price_week_type == 'for_all_days'){
                                                    $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                    $total_price = $room_data->price_all_days * $avaiable_days;
                                                }else{
                                                     $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                     
                                                     $all_days = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                     
                                                    //  print_r($all_days);
                                                     $week_days = json_decode($room_data->weekdays);
                                                     $week_end_days = json_decode($room_data->weekends);
                                                     
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
                                                  
                                                        // echo "  ".$room_data->weekdays_price;
                                                         if($week_day_found){
                                                             $week_days_total += $room_data->weekdays_price;
                                                         }else{
                                                             $week_end_days_totals += $room_data->weekends_price;
                                                         }
                                                         
                                                         
                                                        //  foreach($week_end_days as $week_day_res){
                                                        //      if($week_day_res == $day){
                                                        //          $week_end_day_found = true;
                                                        //      }
                                                        //  }
                                                        //   if($week_end_day_found){
                                                              
                                                        //  }
                                                     }
                                                     
                                                    //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                      
                                                     
                                                    //  print_r($all_days);
                                                     $total_price = $week_days_total + $week_end_days_totals;
                                                }
                                                
                                                
                                            $all_days_price = $total_price * $accomodation_res->more_acc_qty;
                                            
                                            // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                            // die;
                                            
                                            // echo "The supplier Balance is ".$supplier_data->balance;
                                            $supplier_balance = $supplier_data->balance;
                                            $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                            
                                            // update Agent Balance
                                            
                                            DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$all_days_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$req->customer_id,
                                                    'date'=>date('Y-m-d'),
                                                    'invoice_no'=>$invoice_id,
                                                    'available_from'=>$accomodation_res->acc_check_in,
                                                    'available_to'=>$accomodation_res->acc_check_out,
                                                    'room_quantity'=>$accomodation_res->more_acc_qty,
                                                ]);
                                                
                                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                'balance'=>$supplier_balance,
                                                'payable'=>$supplier_payable_balance
                                                ]);
                                            
                                            
                                              
                                                                        
                                        }
                                }
                                
                       
                     }
                      
                 }
                }
            
            }
            
            $lead_in_process = 0;
            $all_leads = DB::table('addLead')->where('customer_id',$req->customer_id)->get();
            if(isset($all_leads) && $all_leads != null && $all_leads != ''){
                foreach($all_leads as $all_leads_value){
                    $lead_quotation         = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->where('lead_id',$all_leads_value->id)->get();
                    $lead_quotation_count   = count($lead_quotation);
                    if(isset($lead_quotation) && $lead_quotation != null && $lead_quotation != ''){
                        if($lead_quotation_count > 0){
                            foreach($lead_quotation as $lead_quotation_value){
                                if($lead_quotation_value->quotation_status != '1'){
                                    $lead_in_process = $lead_in_process + 1;
                                }
                            }
                        }
                    }
                }
            }
            
            DB::commit();
            return response()->json(['status'=>'success','invoice_id'=>$invoice_id,'lead_in_process'=>$lead_in_process,'message'=>'Agent Invoice added Succesfully']); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function view_manage_Lead_Quotation(Request $req){
        $data1              = addManageQuotationPackage::where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
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
    
    public function view_manage_Lead_Quotation_SingleAll(Request $req){
        $data1              = addManageQuotationPackage::where('customer_id',$req->customer_id)->where('lead_id', $req->lead_id)->get();
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
    
    public function view_manage_Lead_Quotation_Single(Request $req){
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
    
    public function edit_manage_Lead_Quotation(Request $req){
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
        $get_invoice                    = addManageQuotationPackage::where('id',$req->id)->first();
        
        $visa_type                      = DB::table('visa_types')->where('customer_id',$customer_id)->get();
        
        $supplier_detail                = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        
        $tranfer_supplier               = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $customers_data                 = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
        
        $all_curr_symboles              = country::all();
        
        $all_flight_routes              = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
        $flight_suppliers               = DB::table('supplier')->where('customer_id',$customer_id)->get();
        
        $visa_supplier                  = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
        $visa_types                     = DB::table('visa_types')->where('customer_id',$customer_id)->get();
        
        $tranfer_vehicle                = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
        $tranfer_destination            = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $tranfer_company                = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
        
        $lead_deatils                   = DB::table('addLead')->where('id',$req->lead_id)->first();
        
        $destination_details            = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        
        return response()->json(['message'=>'success','lead_deatils'=>$lead_deatils,'destination_details'=>$destination_details,'tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=>$tranfer_destination,'tranfer_company'=>$tranfer_company,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'all_flight_routes'=>$all_flight_routes,'flight_suppliers'=>$flight_suppliers,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'visa_type'=>$visa_type,'get_invoice'=>$get_invoice,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function update_manage_Lead_Quotation(Request $req){
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
            //1
            $insert->lead_id                 = $req->lead_id;
            $insert->quotation_validity      = $req->quotation_validity;
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
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
            
            DB::beginTransaction();
            try {
                $insert->update();
                DB::commit();
            } catch (Throwable $e) {
                echo $e;
                DB::rollback();
                return response()->json(['message'=>'error']);
            }
            return response()->json(['status'=>'success','message'=>'Package Quotation Updated Succesfully']); 
        }else{
            return response()->json(['status'=>'error','message'=>$id]); 
        }
    }
    
    public function confirm_manage_Lead_Quotation(Request $req){
        $id     = $req->id;
        $q_S    = DB::table('addManageQuotationPackage')->where('id',$id)->update(['quotation_Invoice' => '1']);
        $i_S    = DB::table('add_manage_invoices')->where('quotation_id',$id)->update(['quotation_Invoice' => '0']);
        return response()->json(['message'=>'Both Status Update']);
    }
    
    public function confirm_Lead_Quotation(Request $req){
        
        DB::beginTransaction();
        try {
            // $lead_id                            = $req->lead_id;
            // $insert                             = addLead::find($lead_id);
            // // Passport Details
            // $insert->preference_type            = $req->preference_type;
            // $insert->first_Name_passport        = $req->first_Name_passport;
            // $insert->last_Name_passport         = $req->last_Name_passport;
            // $insert->nationality_passport       = $req->nationality_passport;
            // $insert->date_of_birth_passport     = $req->date_of_birth_passport;
            // $insert->passport_Number            = $req->passport_Number;
            // $insert->passport_Expiry            = $req->passport_Expiry;
            // $insert->update();
            
            $quotation_Status = DB::table('addManageQuotationPackage')->where('id',$req->id)->update(['quotation_status' => '1']);
            
            DB::commit();
            
            return response()->json(['message'=>'Quotation Status Update','quotation_Status'=>$quotation_Status]);
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function view_atol_Lead(Request $request){
        $inv_details =  DB::table('add_manage_invoices')->where('id',$request->id)->first();
        if($inv_details){
            return response()->json(['message'=>'success','inv_details'=>$inv_details]);
        }else{
            return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
        }
       
    }
    
    public function view_atol_certificate(Request $request){
        $inv_details =  DB::table('add_manage_invoices')->where('id',$request->id)->first();
        if($inv_details){
            return response()->json(['message'=>'success','inv_details'=>$inv_details]);
        }else{
            return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
        }
       
    }
    
    public function view_idemnity_form(Request $request){
        $inv_details =  DB::table('add_manage_invoices')->where('id',$request->id)->first();
        if($inv_details){
            return response()->json(['message'=>'success','inv_details'=>$inv_details]);
        }else{
            return response()->json(['message'=>'failed','inv_details'=>'','cart_data'=>'']);
        }
       
    }
    // End Invoice
}