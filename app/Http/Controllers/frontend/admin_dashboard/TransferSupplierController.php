<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\country;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use App\Models\CustomerSubcription\CustomerSubcription;
use Auth;
use DB;
use Carbon\Carbon;

class TransferSupplierController extends Controller
{
    public function view_transfer_suppliers(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                // $data = DB::table('transfer_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
                $customer_Id    = $request->customer_id;
                $SU_id          = $request->SU_id;
                $data           = DB::table('transfer_Invoice_Supplier')
                                    ->where(function ($query) use ($customer_Id) {
                                        $query->where('customer_id', $customer_Id);
                                    })
                                    ->orWhere(function ($query) use ($SU_id) {
                                        $query->where('SU_id', $SU_id);
                                    })
                                    ->orderBy('id','DESC')
                                    ->get();
            }else{
                $data = DB::table('transfer_Invoice_Supplier')->where('customer_id',$request->customer_id)->orderBy('id','DESC')->get();
            }
            return response()->json(['data'=>$data]);
        }else{
            return response()->json(['data'=>'']);
        }
    }
    
    public function transfer_supplier_ledger(Request $request){
        // print_r($request->all());
        // die;
             $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            if($userData){
                
                     $customer_id = $request->customer_id;
                      $suppliers_data = \DB::table('transfer_Invoice_Supplier')->where('id',$request->supplier_id)->first();
                      $suppliers_ledger = \DB::table('transfer_supplier_ledger')->where('supplier_id',$request->supplier_id)->get();
      
          return response()->json(['message'=>'success','supplier_data'=>$suppliers_data,'ledger_data'=>$suppliers_ledger]);
            }else{
                return response()->json(['data'=>'']);
            }
    }
    
    public static function transfer_supplier_statement_Season_Working($all_data,$request){
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
                if (!isset($record->created_at)) {
                    return false;
                }
                
                if($record->created_at != null && $record->created_at != ''){
                    $created_at     = Carbon::parse($record->created_at);
                    $start_Date     = Carbon::parse($start_Date);
                    $end_Date       = Carbon::parse($end_Date);
                    return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function transfer_supplier_statement(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            if(isset($request->start_date)){
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                
                 $supplier_invoices = DB::table('add_manage_invoices')->where('transfer_supplier_id',$request->supplier_id)
                                                 ->whereDate('created_at','>=', $startDate)
                                                 ->whereDate('created_at','<=', $endDate)
                                                 ->select('id as invoice_no','transportation_details','transportation_details_more','created_at')
                                                 ->get();

    
                $supplier_invoices_arr = [];
                if(isset($supplier_invoices)){
                    foreach($supplier_invoices as $supplier_res){
                        $transportation_details = json_decode($supplier_res->transportation_details);
                        $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                        
                        if(isset($transportation_details)){
                            $total_price = 0;
                            $no_of_vehicle = 0;
                            $price_per_vehicle = 0;
                            $vehcile_type = 0;
                            $pick_up_location = '';
                            $drop_off_location = '';
                            $transportation_type = '';

                            foreach($transportation_details as $trans_res){
                                if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                                    $total_price += $trans_res->transportation_vehicle_total_price;
                                    
                                    $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                                    $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                                    $vehcile_type = $trans_res->transportation_vehicle_type;
                                    $pick_up_location = $trans_res->transportation_pick_up_location;
                                    $drop_off_location = $trans_res->transportation_drop_off_location;
                                    $transportation_type = $trans_res->transportation_trip_type;
                                }
                            }
                            
                        if(isset($transportation_details_more)){
                            foreach($transportation_details_more as $trans_res){
                                $drop_off_location = $trans_res->more_transportation_drop_off_location;
                            }
                        }
                            
                            
                            $supplier_invoices_arr[] = (Object)[
                                'supplier_id'=> $request->supplier_id,
                                'booking_type'=> "Invoice",
                                'invoice_id'=> $supplier_res->invoice_no,
                                'pick_up_location'=> $pick_up_location,
                                'drop_off_location'=> $drop_off_location,
                                'transportation_type'=> $transportation_type,
                                'vehcile_type'=> $vehcile_type,
                                'price_per_vehicle'=> $price_per_vehicle,
                                'number_of_vehicles'=> $no_of_vehicle,
                                'total_price'=> $total_price,
                                'created_at' => $supplier_res->created_at
                            ];
                        }
                         
                        // print_r($transportation_details);
                        // print_r($transportation_details_more);
                       
                    }
                }
                $supplier_packages = DB::table('tours_2')->where('transfer_supplier_id',$request->supplier_id)
                                                    ->whereDate('created_at','>=', $startDate)
                                                    ->whereDate('created_at','<=', $endDate)
                                                     ->select('tour_id as package_id','transportation_details','transportation_details_more','created_at')
                                                     ->get();
                
                
                $supplier_packages_arr = [];
                if(isset($supplier_packages)){
                    foreach($supplier_packages as $supplier_res){
                        $transportation_details = json_decode($supplier_res->transportation_details);
                        $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                        
                            if(isset($transportation_details)){
                             
                                $total_price = 0;
                                $no_of_vehicle = 0;
                                $price_per_vehicle = 0;
                                $vehcile_type = 0;
                                $pick_up_location = '';
                                $drop_off_location = '';
                                $transportation_type = '';

                                foreach($transportation_details as $trans_res){
                                    if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                                        $total_price += $trans_res->transportation_vehicle_total_price;
                                        
                                        $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                                        $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                                        $vehcile_type = $trans_res->transportation_vehicle_type;
                                        $pick_up_location = $trans_res->transportation_pick_up_location;
                                        $drop_off_location = $trans_res->transportation_drop_off_location;
                                        $transportation_type = $trans_res->transportation_trip_type;
                                    }
                                }
                                
                            if(isset($transportation_details_more)){
                                foreach($transportation_details_more as $trans_res){
                                    $drop_off_location = $trans_res->more_transportation_drop_off_location;
                                }
                            }
                                
                                 $supplier_packages_arr[] = (Object)[
                                                    'supplier_id'=> $request->supplier_id,
                                                    'booking_type'=> "Package",
                                                    'package_id'=> $supplier_res->package_id,
                                                    'pick_up_location'=> $pick_up_location,
                                                    'drop_off_location'=> $drop_off_location,
                                                    'transportation_type'=> $transportation_type,
                                                    'vehcile_type'=> $vehcile_type,
                                                    'price_per_vehicle'=> $price_per_vehicle,
                                                    'number_of_vehicles'=> $no_of_vehicle,
                                                    'total_price'=> $total_price,
                                                    'created_at' => $supplier_res->created_at
                                                ];
                            }
                        
                    }
                }
                
                $payments_data = DB::table('recevied_payments_details')
                    ->where('Criteria','Transfer Supplier')
                    ->where('Content_Ids',$request->supplier_id)
                    ->whereDate('payment_date','>=', $startDate)
                    ->whereDate('payment_date','<=', $endDate)
                    ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                    ->orderBy('created_at')
                    ->get();
                    
                $make_payments_data = DB::table('make_payments_details')
                    ->where('Criteria','Transfer Supplier')
                    ->where('Content_Ids',$request->supplier_id)
                    ->whereDate('payment_date','>=', $startDate)
                    ->whereDate('payment_date','<=', $endDate)
                    ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                    ->orderBy('created_at')
                    ->get();
                
            }else{
                 $supplier_invoices = DB::table('add_manage_invoices')->where('transfer_supplier_id',$request->supplier_id)
                                                 ->select('id as invoice_no','transportation_details','transportation_details_more','created_at')
                                                 ->get();

    
                $supplier_invoices_arr = [];
                if(isset($supplier_invoices)){
                    foreach($supplier_invoices as $supplier_res){
                        $transportation_details = json_decode($supplier_res->transportation_details);
                        $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                        
                        if(isset($transportation_details)){
                            $total_price = 0;
                            $no_of_vehicle = 0;
                            $price_per_vehicle = 0;
                            $vehcile_type = 0;
                            $pick_up_location = '';
                            $drop_off_location = '';
                            $transportation_type = '';

                            foreach($transportation_details as $trans_res){
                                if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                                    $total_price += $trans_res->transportation_vehicle_total_price;
                                    
                                    $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                                    $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                                    $vehcile_type = $trans_res->transportation_vehicle_type;
                                    $pick_up_location = $trans_res->transportation_pick_up_location;
                                    $drop_off_location = $trans_res->transportation_drop_off_location;
                                    $transportation_type = $trans_res->transportation_trip_type;
                                }
                            }
                            
                        if(isset($transportation_details_more)){
                            foreach($transportation_details_more as $trans_res){
                                $drop_off_location = $trans_res->more_transportation_drop_off_location;
                            }
                        }
                            
                            
                            $supplier_invoices_arr[] = (Object)[
                                'supplier_id'=> $request->supplier_id,
                                'booking_type'=> "Invoice",
                                'invoice_id'=> $supplier_res->invoice_no,
                                'pick_up_location'=> $pick_up_location,
                                'drop_off_location'=> $drop_off_location,
                                'transportation_type'=> $transportation_type,
                                'vehcile_type'=> $vehcile_type,
                                'price_per_vehicle'=> $price_per_vehicle,
                                'number_of_vehicles'=> $no_of_vehicle,
                                'total_price'=> $total_price,
                                'created_at' => $supplier_res->created_at
                            ];
                        }
                         
                        // print_r($transportation_details);
                        // print_r($transportation_details_more);
                       
                    }
                }
                $supplier_packages = DB::table('tours_2')->where('transfer_supplier_id',$request->supplier_id)
                                                     ->select('tour_id as package_id','transportation_details','transportation_details_more','created_at')
                                                     ->get();
                
                
                $supplier_packages_arr = [];
                if(isset($supplier_packages)){
                    foreach($supplier_packages as $supplier_res){
                        $transportation_details = json_decode($supplier_res->transportation_details);
                        $transportation_details_more = json_decode($supplier_res->transportation_details_more);
                        
                            if(isset($transportation_details)){
                             
                                $total_price = 0;
                                $no_of_vehicle = 0;
                                $price_per_vehicle = 0;
                                $vehcile_type = 0;
                                $pick_up_location = '';
                                $drop_off_location = '';
                                $transportation_type = '';

                                foreach($transportation_details as $trans_res){
                                    if(isset($trans_res->transportation_drop_off_location) && !empty($trans_res->transportation_drop_off_location)){
                                        $total_price += $trans_res->transportation_vehicle_total_price;
                                        
                                        $no_of_vehicle = $trans_res->transportation_no_of_vehicle;
                                        $price_per_vehicle = $trans_res->transportation_price_per_vehicle;
                                        $vehcile_type = $trans_res->transportation_vehicle_type;
                                        $pick_up_location = $trans_res->transportation_pick_up_location;
                                        $drop_off_location = $trans_res->transportation_drop_off_location;
                                        $transportation_type = $trans_res->transportation_trip_type;
                                    }
                                }
                                
                            if(isset($transportation_details_more)){
                                foreach($transportation_details_more as $trans_res){
                                    $drop_off_location = $trans_res->more_transportation_drop_off_location;
                                }
                            }
                                
                                 $supplier_packages_arr[] = (Object)[
                                                    'supplier_id'=> $request->supplier_id,
                                                    'booking_type'=> "Package",
                                                    'package_id'=> $supplier_res->package_id,
                                                    'pick_up_location'=> $pick_up_location,
                                                    'drop_off_location'=> $drop_off_location,
                                                    'transportation_type'=> $transportation_type,
                                                    'vehcile_type'=> $vehcile_type,
                                                    'price_per_vehicle'=> $price_per_vehicle,
                                                    'number_of_vehicles'=> $no_of_vehicle,
                                                    'total_price'=> $total_price,
                                                    'created_at' => $supplier_res->created_at
                                                ];
                            }
                        
                    }
                }
                
                $payments_data = DB::table('recevied_payments_details')
                    ->where('Criteria','Transfer Supplier')
                    ->where('Content_Ids',$request->supplier_id)
                    ->select('id as pay_recv_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                    ->orderBy('created_at')
                    ->get();
                    
                $make_payments_data = DB::table('make_payments_details')
                    ->where('Criteria','Transfer Supplier')
                    ->where('Content_Ids',$request->supplier_id)
                    ->select('id as pay_make_id','Content_Ids','Criteria','Amount','remarks','payment_date as created_at','purchase_amount','converion_data',"exchange_rate")
                    ->orderBy('created_at')
                    ->get();
            }
            
            $supplier_invoices_arr  = collect($supplier_invoices_arr);
            $supplier_packages_arr  = collect($supplier_packages_arr);
            $all_data               = $supplier_invoices_arr->concat($supplier_packages_arr)->concat($payments_data)->concat($make_payments_data)->sortBy('created_at');
            $suppliers_data         = DB::table('transfer_Invoice_Supplier')->where('id',$request->supplier_id)->first();
            
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
            if($request->customer_id == 4 || $request->customer_id == 54){
                if($all_data->isEmpty()){
                }else{
                    // dd($all_data);
                    $all_data   = $this->transfer_supplier_statement_Season_Working($all_data,$request);
                    // dd($all_data);
                }
            }
            // Season
            
            return response()->json(['message'=>'success','supplier_data'=>$suppliers_data,'statement_data'=>$all_data,'season_Details'=>$season_Details,'season_Id'=>$season_Id]);
        }else{
            return response()->json(['data'=>'']);
        }
    }
   
    public function transfer_supplier_stats(Request $request){
        // print_r($request->all());
         $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
        
            // Invoices Data
            $invoices_data = DB::table('add_manage_invoices')->where('customer_id',$userData->id)
                                                  ->where('transfer_supplier_id',$request->supplier_id)
                                                  ->select('id','transportation_details','transportation_details_more','transfer_supplier_id')
                                                  ->get();
            $all_invoices_data = [];
            $total_payable = 0;
            foreach($invoices_data as $inv_data_res){
                // print_r($inv_data_res);
                $single_inv_data = [];
                $destination_data = json_decode($inv_data_res->transportation_details);
                foreach($destination_data as $dest_res){
                    // print_r($dest_res);
                    if(!empty($dest_res->transportation_pick_up_location)){
                        
                        if($dest_res->transfer_markup_type_invoice == '%'){
                            $markup_calc = ($dest_res->transportation_vehicle_total_price / 100 ) * $dest_res->transfer_markup_invoice;
                            $with_markup_price = $dest_res->transportation_vehicle_total_price + $markup_calc;
                        }else{
                            $with_markup_price = $dest_res->transportation_vehicle_total_price + $dest_res->transfer_markup_invoice;
                        }
                        
                        $single_inv_data_more = [
                                'Vehicle'=> $dest_res->transportation_vehicle_type,
                                'transportation_trip_type' => $dest_res->transportation_trip_type,
                                'Vehicle_price'=> $dest_res->transportation_price_per_vehicle,
                                'no_of_vehicle'=> $dest_res->transportation_no_of_vehicle,
                                'vehicle_name'=> $dest_res->transportation_vehicle_type,
                                'total_vehicle_price'=> $dest_res->transportation_vehicle_total_price,
                                'markup_type'=> $dest_res->transfer_markup_type_invoice,
                                'markup_value'=> $dest_res->transfer_markup_invoice,
                                'total_vehicle_price'=> $dest_res->transportation_vehicle_total_price,
                                'total_vehicle_price_wi_markup'=> $with_markup_price,
                                'profit'=>$with_markup_price - $dest_res->transportation_vehicle_total_price,
                                'destination_id'=> $dest_res->destination_id,
                                'invoice_id'=> $inv_data_res->id,
                                'exchange_rate'=> $dest_res->transfer_exchange_rate_destination,
                                'converted_price'=> $dest_res->without_markup_price_converted_destination,
                            ];
                            
                            $total_payable += $dest_res->transportation_vehicle_total_price;
                            array_push($single_inv_data,$single_inv_data_more);
                    }
                }
                
                array_push($all_invoices_data,$single_inv_data);
                
                
                // echo "First loop iteration ";
            }
            
            // print_r($all_invoices_data);
            
        }
        
        $supplier_data = DB::table('transfer_Invoice_Supplier')->where('id',$request->supplier_id)->first();
        $pay = $supplier_data->payment_amount;
        
        if($supplier_data->payment_amount == "$total_payable"){
            $remaining_amount = 0;
        }else{
             $remaining_amount = $total_payable - $supplier_data->payment_amount;
        }
       
        $supplier_all_data = [
                'supplier_id'=>$supplier_data->id,
                'supplier_name'=>$supplier_data->room_supplier_name,
                'supplier_wallet_balance'=>$supplier_data->wallat_balance,
                'total_payable'=>$total_payable,
                'payment_amount'=> $supplier_data->payment_amount,
                'remaining_amount'=> $remaining_amount,
                'supplier_inv_data'=>$all_invoices_data,
            ];
        
    //   $customer_id = $request->customer_id;
    //   $data=DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
      

      return response()->json(['status'=>'success','data'=>$supplier_all_data]); 
   }
   
    public function transfer_supplier_payments(Request $request){
    //   print_r($request->all());
    //   die;
       $request_data = json_decode($request->request_data);
         DB::beginTransaction();
                        try {
                            
                             
                                    $supplier_data = DB::table('transfer_Invoice_Supplier')->where('id',$request_data->supplier_id)->first();
                                    
                                    $total_over_paid = $supplier_data->wallat_balance;
                                    $total_paid_amount = $supplier_data->payment_amount + $request_data->amount_paid;
                                    $over_paid = 0;
                                    if($total_paid_amount > $request_data->total_amount){
                                        $over_paid = $total_paid_amount - $request_data->total_amount;
                                        $total_over_paid += $over_paid;
                                        $total_paid_amount = $request_data->total_amount;
                                    }
                                   
                                    // $over_paid_amount = $request_data->over_paid;
                                    
                                  
                                    
                                    DB::table('transfer_Invoice_Supplier')->where('id',$request_data->supplier_id)->update([
                                        'payment_amount' => $total_paid_amount,
                                        'wallat_balance' => $total_over_paid,
                                    ]);
                                    

                                    
                                    if($over_paid > 0){
                                           DB::table('transfer_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid,
                                                                                            'payment_am'=>$supplier_data->payment_amount,
                                                                                            'balance'=>$total_over_paid,
                                                                                            'supplier_id'=>$request_data->supplier_id,
                                                                                            'date'=>$request_data->date,
                                                                                            'pay_method'=>$request_data->payment_method,
                                                                                         ]);
                                    }
                                 
                                    
                                    if($request_data->payment_method == 'Wallet'){
                                        $supplier_data = DB::table('transfer_Invoice_Supplier')->where('id',$request_data->supplier_id)->select('id','wallat_balance')->first();
                                        $supplier_wallet_am = $supplier_data->wallat_balance - $request_data->amount_paid;
                                        DB::table('transfer_Invoice_Supplier')->where('id',$request_data->supplier_id)->update(['wallat_balance'=>$supplier_wallet_am]);
                                        
                                        DB::table('transfer_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid,
                                                                                        'payment_am'=>$request_data->amount_paid,
                                                                                        'balance'=>$supplier_wallet_am,
                                                                                        'supplier_id'=>$request_data->supplier_id,
                                                                                        'date'=>$request_data->date,
                                                                                        'pay_method'=>$request_data->payment_method,
                                                                                     ]);
                                    }
                                    
                                    DB::table('transfer_supplier_payment')->insert(['supplier_id'=>$request_data->supplier_id,
                                                                                    'over_paid_am'=>$over_paid,
                                                                                    'payment_amount'=>$request_data->amount_paid,
                                                                                    'date'=>$request_data->date,
                                                                                     ]);
                                
                                
                               
                                
                    
          
                            DB::commit();
                            
                            return response()->json(['status'=>'success','message'=>'Balance is Updated Successfully']);
                        } catch (\Exception $e) {
                            DB::rollback();
                            echo $e;die;
                            return response()->json(['status'=>'error','message'=>'Something Went Wrong try Again']);
                            // something went wrong
                        }
                        
   }
  
    public function add_transfer_suppliers(Request $request){
        $countries=DB::table('countries')->get();
        return response()->json(['countries'=>$countries]);
    }
  
    public function submit_transfer_suppliers(Request $request){
        $customer_id                = $request->customer_id;
        $room_supplier_name         = $request->room_supplier_name;
        $email                      = $request->email;
        $phone_no                   = $request->phone_no;
        $address                    = $request->address;
        $contact_person_name        = $request->contact_person_name;
        $country                    = $request->country;
        $city                       = $request->city;
        $more_phone_no              = $request->more_phone_no;
        $room_supplier_company_name = $request->room_supplier_company_name;
        $data                       = DB::table('transfer_Invoice_Supplier')->insert([  
            'SU_id'                         => $request->SU_id ?? NULL,
            'customer_id'                   => $customer_id,
            'room_supplier_name'            => $room_supplier_name,
            'email'                         => $email,
            'phone_no'                      => $phone_no,
            'opening_balance'               => $request->opening_bal,
            'balance'                       => $request->opening_bal,
            'address'                       => $address,
            'contact_person_name'           => $contact_person_name,
            'country'                       => $country,
            'city'                          => $city,
            'more_phone_no'                 => $more_phone_no,
            'room_supplier_company_name'    => $room_supplier_company_name,
        ]);
       return response()->json(['Status'=>'SuccessFull','data'=>$data]);
    }
  
    public function edit_transfer_suppliers(Request $request){
        $id=$request->id;
        $data=DB::table('transfer_Invoice_Supplier')->where('id',$id)->first();
      
        $countries=DB::table('countries')->get();
        return response()->json(['data'=>$data,'countries'=>$countries]);
        
        
    }
   
    public function submit_edit_transfer_suppliers(Request $request){
        $id                     = $request->id;
        $customer_id            = $request->customer_id;
        $room_supplier_name     = $request->room_supplier_name;
        $email                  = $request->email;
        $phone_no               = $request->phone_no;
        $address                = $request->address;
        $contact_person_name    = $request->contact_person_name;
        $country                = $request->country;
        $city                   = $request->city;
        $more_phone_no          = $request->more_phone_no;
        $room_supplier_company_name   = $request->room_supplier_company_name;
        $data=DB::table('transfer_Invoice_Supplier')->where('id',$id)->update([
            'customer_id'           => $customer_id,
            'room_supplier_name'    => $room_supplier_name,
            'email'                 => $email,
            'phone_no'              => $phone_no,
            'address'               => $address,
            'contact_person_name'   => $contact_person_name,
            'country'               => $country,
            'more_phone_no'         => $more_phone_no,
            'room_supplier_company_name'    => $room_supplier_company_name,
          
        ]);
      
       return response()->json(['Status'=>'SuccessFull','data'=>$data]);
    }
   
    public function delete_transfer_suppliers(Request $request){
        $id=$request->id;
        $data=DB::table('transfer_Invoice_Supplier')->where('id',$id)->delete();
        return response()->json(['data'=>$data,'Status'=>'Successful']);
    }
}
