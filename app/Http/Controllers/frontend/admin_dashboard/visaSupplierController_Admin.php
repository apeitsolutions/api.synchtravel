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

class visaSupplierController_Admin extends Controller
{
    public function submit_visa_suppliers(Request $request){
        $customer_id            = $request->customer_id;
        $visa_supplier_name     = $request->visa_supplier_name;
        $email                  = $request->email;
        $phone_no               = $request->phone_no;
        $address                = $request->address;
        $contact_person_name    = $request->contact_person_name;
        $country                = $request->country;
        $city                   = $request->city ?? "";
       
        
        $data=DB::table('visa_Sup')->insert([  
            'opening_balance'       => $request->opening_balance,
            'balance'               => $request->opening_balance,
            'opening_payable'       => $request->opening_payable,
            'payable'       => $request->opening_payable,
            'customer_id'           => $customer_id,
            'visa_supplier_name'    => $visa_supplier_name,
            'email'                 => $email,
            'phone_no'              => $phone_no,
            'address'               => $address,
            'contact_person_name'   => $contact_person_name,
            'country'               => $country,
            'city'                  => $city ?? "",
           
        ]);
       return response()->json(['Status'=>'SuccessFull','data'=>$data]);
    }
    
    public function viewvisasupplier_Admin(Request $request){
        $all_Users      = DB::table('customer_subcriptions')->get();
        $visa_supplier  = DB::table('visa_Sup')->get();
        $all_country    = country::all();
        return view('template/frontend/userdashboard/pages/visa/viewvisasupplier',compact(['visa_supplier','all_Users','all_country']));
    }
    
    public function get_visa_suppliers_for_edit(Request $request){
        $visa_sup_id    = $request->visa_sup_id;
        $data           = DB::table('visa_Sup')->where('id',$visa_sup_id)->first();
        return response()->json(['Status'=>'SuccessFull','data'=>$data]);
    }
    
    public function visa_supplier_ledger(Request $request){
        $supplier_detail  = DB::table('visa_Sup')->where('id',$request->supplier_id)->first();
        $supplier_ledger_data  = DB::table('visa_supplier_ledger')->where('supplier_id',$request->supplier_id)->get();
        return response()->json(['status'=>'success','ledger_data'=>$supplier_ledger_data,'supplier_Pers_details'=>$supplier_detail]);
    }
    
    public function submit_visa_suppliers_for_update(Request $request){
        $visa_supllier_id            = $request->visa_sup_id;
       
       
       
        
        $data= DB::table('visa_Sup')->where('id',$visa_supllier_id)->update([  
            'customer_id'           => $request->customer_id,
            'visa_supplier_name'    => $request->visa_supplier_name,
            'email'                 => $request->email,
            'phone_no'              => $request->phone_no,
            'address'               => $request->address,
            'contact_person_name'   => $request->contact_person_name,
            'country'               => $request->country,
            'city'                  => $request->city ?? "",
           
        ]);
       return response()->json(['Status'=>'SuccessFull','data'=>$data]);
    }
    
    public function get_visa_type_for_sup(Request $request){
       
    //   dd($request);
      $customer_id =  $request->customer_id;
       
        
        $data= DB::table('visa_types')->get();
        $visa_supplier = DB::table('visa_Sup')->get();
        $mange_currencies=DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
       return response()->json(['Status'=>'SuccessFull','data'=>$data,'sup'=>$visa_supplier,'mange_currencies'=>$mange_currencies]);
    }
    
    public function submit_visa_avalability_for_sup(Request $request){
       
      
      $currency_conv_data = json_decode($request->currency_conversion);
      
      $currency_id = 0;
      if(isset($currency_conv_data)){
        $currency_id = $currency_conv_data->id;
      }
      
      DB::beginTransaction();
      
      try{
        $visa_id =DB::table('visa_Availability')->insertGetId([  
            'customer_id'           => $request->customer_id,
            'country'           => $request->country,
            'visa_supplier'    => $request->visa_supplier,
            'visa_type'                 => $request->visa_type,
            'currency_conversion'                 => $currency_id,
            'conversion_type'                 => $request->conversion_type,
            'visa_price_conversion_rate'                 => $request->visa_price_conversion_rate,
            'visa_converted_price'                 => $request->visa_converted_price,
            
            
            'visa_qty'              => $request->visa_qty,
            'visa_available'              => $request->visa_qty,
            'visa_price'               => $request->visa_price,
            'availability_from'   => $request->availability_from,
            'availability_to'               => $request->availability_to,
            'total_visa_payable'               => $request->total_visa_payable,
           
           
        ]);
        
        $supplier_data = DB::table('visa_Sup')->where('id',$request->visa_supplier)->first();
        
        $supplier_balance = $supplier_data->balance + $request->total_visa_payable;
        
        DB::table('visa_supplier_ledger')->insert([
                'supplier_id' => $request->visa_supplier,
                'payment' => $request->total_visa_payable,
                'balance' => $supplier_balance,
                'payable' => $supplier_data->payable,
                'visa_qty' => $request->visa_qty,
                'visa_type' => $request->visa_type,
                'visa_price' => $request->visa_price,
                'visa_avl_id'=> $visa_id,
                'date'=> date('Y-m-d'),
                'remarks'=> 'New Visa Purchased',
                'customer_id'=> $request->customer_id,
            ]);
        
        $data=DB::table('visa_Sup')->where('id',$request->visa_supplier)->update([  
           'balance'               => $supplier_balance,
        ]);
        
        DB::commit();
        return response()->json(['status'=>'success']);
      }catch(Throwable $e){
           DB::rollback();
           return response()->json(['status'=>'error']);
      }
        
       
    }
    
    public function view_visa_type(Request $request){
       
    //   dd($request);
      
        $data=DB::table('visa_Availability')
        ->join('visa_Sup','visa_Sup.id','visa_Availability.visa_supplier')
        ->join('visa_types','visa_types.id','visa_Availability.visa_type')
        ->join('mange_currencies','mange_currencies.id','visa_Availability.currency_conversion')
        ->select('visa_Availability.id as visa_id','visa_Availability.*','visa_Sup.*','visa_types.*','mange_currencies.purchase_currency','mange_currencies.sale_currency')
        ->get();
       return response()->json(['Status'=>'SuccessFull','data'=>$data,]);
    }
    
    public function get_supplier_visas(Request $request){
        // dd($request->all());
       
        $supplier_visas = DB::table('visa_Availability')
                            ->join('countries','countries.id','visa_Availability.country')
                            ->join('visa_types','visa_types.id','visa_Availability.visa_type')
                            ->where('visa_Availability.visa_supplier',$request->visa_supplier)
                            ->select('visa_Availability.*','countries.name','visa_types.other_visa_type')
                            ->get();
                            
            return response()->json(['Status'=>'SuccessFull','data'=>$supplier_visas]);
        
        // print_r($supplier_visas);
        
    }

    public function delete_visa_suppliers(Request $request){
       
    //   dd($request);
    
        // $data=DB::table('visa_Availability')->where('id',$request->visa_availability_id)->delete();
        $data=DB::table('visa_Sup')->where('id',$request->visa_suplier_id)->delete();
       
       if($data = 1){
     return response()->json(['Status'=>'SuccessFull']);   
    }else{
        return response()->json(['Status'=>'error']);
    }
       
       
       
       
    }
    
    public function delete_visa_avail(Request $request){
       
    //   dd($request);
    
        $data=DB::table('visa_Availability')->where('id',$request->visa_availability_id)->delete();
       
       
       if($data = 1){
     return response()->json(['Status'=>'SuccessFull']);   
    }else{
        return response()->json(['Status'=>'error']);
    }
       
       
       
       
    }

    public function edit_visa_supplier(Request $request){
       
    //   dd($request);
      
        // $data=DB::table('visa_Availability')->where('id',$request->visa_supplier_id)->first();
        $data=DB::table('visa_Availability')
        ->where('visa_Availability.id',$request->visa_supplier_id)
        ->join('visa_Sup','visa_Sup.id','visa_Availability.visa_supplier')
        ->join('visa_types','visa_types.id','visa_Availability.visa_type')
        ->select('visa_Availability.id as visa_id','visa_Availability.*','visa_Sup.*','visa_types.*')->first();
       return response()->json(['Status'=>'SuccessFull','data'=>$data,]);
    }
    
    public function update_visa_type_against_id(Request $request){
       
    //   dd($request);
     $data=DB::table('visa_Availability')->where('id',$request->visa_availability_id)->update([  
            'customer_id'           => $request->customer_id,
            'country'           => $request->country,
            'visa_supplier'    => $request->visa_supplier,
            'visa_type'                 => $request->visa_type,
            'currency_conversion'                 => $request->currency_conversion,
            'conversion_type'                 => $request->conversion_type,
            'visa_price_conversion_rate'                 => $request->visa_price_conversion_rate,
            'visa_converted_price'                 => $request->visa_converted_price,
            
            
            'visa_qty'              => $request->visa_qty,
            'visa_price'               => $request->visa_price,
            'availability_from'   => $request->availability_from,
            'availability_to'               => $request->availability_to,
           
           
        ]);
       return response()->json(['Status'=>'SuccessFull','data'=>$data,]);
    }
    
    public function save_visa_payment_recieved_and_remaining(Request $req){
       
    //   dd($request);
   DB::beginTransaction();
                        try {
                            
                                  $flightid= $req->visaId;
                                //   dd($flightid);
                                  $payment_routine_array = [];
                                  
                                  $flight_data = \DB::table('visa_Availability')->where('id',$flightid)->first();
                    //   dd($flight_data);
                                $total_amount = $flight_data->total_visa_payable;
                                $paid_amount = $flight_data->visa_paid_amount;
                                $over_paid_amount = $flight_data->over_paid_amount;
                                
                                $total_paid_amount = $paid_amount + $req->amount_paid;
                                $total_over_paid = 0;
                                $over_paid_am = 0;
                                if($total_paid_amount > $total_amount){
                                    $over_paid_am = $total_paid_amount - $total_amount;
                                    $total_over_paid = $over_paid_amount + $over_paid_am;
                                    
                                    $total_paid_amount = $total_paid_amount - $over_paid_am;
                                }
                                
                                DB::table('visa_Availability')->where('id',$flightid)->update([
                                    'visa_paid_amount' => $total_paid_amount,
                                    'over_paid_amount' => $total_over_paid,
                                ]);
                                
                                $supplier_data = DB::table('visa_Sup')->where('id',$flight_data->visa_supplier)->select('id','wallet_amount')->first();
                                $supplier_wallet_am = $supplier_data->wallet_amount + $over_paid_am;
                                DB::table('supplier')->where('id',$flight_data->supplier)->update(['wallet_amount'=>$supplier_wallet_am]);
                                
                                
                                if($over_paid_am != 0){
                                       DB::table('flight_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                    'balance'=>$supplier_wallet_am,
                                                                                    'flight_id'=>$req->flightId,
                                                                                    'supplier_id'=>$flight_data->supplier,
                                                                                    'date'=>$req->date,
                                                                                     ]);
                                }
                             
                                
                                if($req->payment_method == 'Wallet'){
                                    $supplier_data = DB::table('supplier')->where('id',$flight_data->supplier)->select('id','wallet_amount')->first();
                                    $supplier_wallet_am = $supplier_data->wallet_amount - $req->amount_paid;
                                    DB::table('supplier')->where('id',$flight_data->supplier)->update(['wallet_amount'=>$supplier_wallet_am]);
                                    
                                    DB::table('flight_supplier_wallet_trans')->insert(['payment_am'=>$req->amount_paid,
                                                                                'balance'=>$supplier_wallet_am,
                                                                                'flight_id'=>$req->flightId,
                                                                                'supplier_id'=>$flight_data->supplier,
                                                                                'date'=>$req->date,
                                                                                 ]);
                                }
                                
                               
                                
                    //   dd($flight_data);
                      if($flight_data != null){
                          if($flight_data->amount_paidandremaining_of_flight){
                              $payment_routine = $flight_data->amount_paidandremaining_of_flight;
                              $payment_data = json_decode($payment_routine);
                            //   dd($payment_data);
                              
                              array_push($payment_data,$req->all());
                              $payment_routine_array = $payment_data;
                          }else{
                              array_push($payment_routine_array,$req->all());
                            //   $payment_routine_array = [$req->all()];
                          }
                      
                      }
                      
                      
                    //   dd($payment_routine_array);
                      
                      
                      
                      
                      $suppliers=\DB::table('Flight_sup_seats')->where('id',$flightid)->update([
                          
                    'amount_paidandremaining_of_flight'=>json_encode($payment_routine_array),
                    
                          ]);
          
                            DB::commit();
                            
                            return response()->json(['status'=>'success','message'=>'Balance is Updated Successfully']);
                        } catch (\Exception $e) {
                            DB::rollback();
                            echo $e;die;
                            return response()->json(['status'=>'error','message'=>'Something Went Wrong try Again']);
                            // something went wrong
                        }
                        
                        
    if($suppliers = 1){
     return response()->json(['message'=>'success']);   
    }else{
        return response()->json(['message'=>'error']);
    }
    }
}
