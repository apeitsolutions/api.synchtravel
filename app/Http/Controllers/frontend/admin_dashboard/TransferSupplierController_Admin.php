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

class TransferSupplierController_Admin extends Controller
{
    public function view_transfer_suppliers_Admin(Request $request){
        $all_Users  = DB::table('customer_subcriptions')->get();
        $data       = DB::table('transfer_Invoice_Supplier')->get();
        return view('template/frontend/userdashboard/pages/transfer_supplier/view_supplier',compact(['data','all_Users']));  
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
        
        $data=DB::table('transfer_Invoice_Supplier')->insert([  
            'customer_id'           => $customer_id,
            'room_supplier_name'    => $room_supplier_name,
            'email'                 => $email,
            'opening_balance'                 => $request->opening_bal,
            'balance'                 => $request->opening_bal,
            'phone_no'              => $phone_no,
            'address'               => $address,
            'contact_person_name'   => $contact_person_name,
            'country'               => $country,
            'city'                  => $city,
            'more_phone_no'         => $more_phone_no,
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
