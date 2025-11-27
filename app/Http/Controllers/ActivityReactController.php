<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\ToursBooking;
use App\Models\Cart_details;
use DB;
use App\Models\country;

class ActivityReactController extends Controller
{
     public function cites_suggestions(Request $request)
    {
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

        $customer_id = $userData->id;
        // echo "id is $customer_id";
        $locations=DB::table('new_activites')
                  ->where('location','LIKE','%'.''.$request->location.'%')
                  ->where('customer_id','=',$customer_id)
        ->select('location')->limit(20)->get();
        // print_r($customer_id);die();
        return response()->json(['status'=>'success',
                                    'locations'=>$locations]);
    }

    public function search_activities(Request $request){

        // Parameters ['location','start_dates','token']

        DB::beginTransaction();
        try {
            $start_date     = date("Y-m-d", strtotime($request->start_date));
            $userData       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            $customer_id    = $userData->id;
            $tours          = DB::table('new_activites')
                                ->where('location','LIKE','%'.''.$request->location.'%')
                                ->where('end_date', '>=', $start_date)
                                ->where('customer_id','=',$customer_id)
                                ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes','start_date')
                                ->orderBy('created_at', 'desc')
                                ->get();
            $tomorrowDate   = date('Y-m-d', strtotime('-1 days'));
            $relatedtours   = DB::table('new_activites')
                                ->where('location','LIKE','%'.''.$request->location.'%')
                                ->where('start_date','>=',$tomorrowDate)
                                ->where('customer_id','=',$customer_id)
                                ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','start_date')->orderBy('created_at', 'desc')->get();

            return response()->json(['status'=>'success','tours'=>$tours,'relatedTours'=>$relatedtours]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            $result = false;
            return response()->json(['status'=>'error']);
        }
    }

    public function activity_details(Request $request){

        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status','webiste_Address')->first();
        if($userData){
            $activity =  DB::table('new_activites')->where('customer_id',$userData->id)->where('id',$request->id)->first();
            if($activity){
                return response()->json(['status'=>'success','data'=>$activity]);
            }else{
                return response()->json(['status'=>'error','message'=>'Invalid Activity Id']);
            }
        }else{
            return response()->json(['status'=>'error','message'=>'Client Validation Error']);
        }

    }

    public function book_activity(Request $request){


        $currency_slc=$request->currency_slc;

        $request_data = json_decode($request->request_data);

        $cart_data = json_decode($request->cart_data);


        $request_data = json_decode($request->request_data);

        $name = $request_data[0]->name." ".$request_data[0]->lname;
        $email = $request_data[0]->email;

        $cart_data_main = json_decode($request->cart_data);

        $cart_data      = $cart_data_main[0];

        $provider_id    = '';
        $userData       = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

        $randomNumber   = random_int(1000000, 9999999);
        $invoiceId      =  "AHT".$randomNumber;

        $tourObj        = new ToursBooking;

        $tourObj->passenger_name = $name;
        $tourObj->email = $email;
        $tourObj->cart_details = '';
        $tourObj->passenger_detail = $request->request_data;
        $tourObj->adults_detail = $request->adults;
        $tourObj->child_detail = $request->childs;
        $tourObj->infant_details = $request->infants;
        $tourObj->customer_id = $userData->id;
        $tourObj->parent_token = $request->token;
        $tourObj->invoice_no = $invoiceId;
        $tourObj->booking_person = $request->booking_person;
        $tourObj->exchange_currency = $currency_slc;

        $tourObj->provider_id = $provider_id;

        DB::beginTransaction();

        try {
            $tourData = $tourObj->save();

            $booking_id = $tourObj->id;

            $customer_id = "";
            $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$email)->first();
            if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                    $customer_id = $customer_exist->id;
            }
            else{


                $password = Hash::make('admin123');

                $customer_detail                    = new booking_customers();
                $customer_detail->name              = $request_data[0]->name." ".$request_data[0]->lname;
                $customer_detail->opening_balance   = 0;
                $customer_detail->balance           = 0;
                $customer_detail->email             = $request_data[0]->email;
                $customer_detail->password             = $password;
                $customer_detail->phone             = $lead_passenger_data[0]->phone;
                $customer_detail->gender            = $lead_passenger_data[0]->gender;
                $customer_detail->country           = $lead_passenger_data[0]->country;

                $customer_detail->customer_id       = $userData->id;
                $result = $customer_detail->save();

                $customer_id = $customer_detail->id;


            }



            // print_r($cart_data);
            // die;
            $cart_data = $cart_data[0];
             foreach($cart_data as $index => $cart_res){
                  $cart_data->customer_id = $customer_id;

             }


             $cart_data = [$cart_data];
                 foreach($cart_data as $cart_res){



                    $addtional_services = '';
                    if(isset($request->additional_services)){
                        $addtional_services = $request->additional_services;
                    }

                    $start_date     = date("Y-m-d", strtotime($cart_res->activity_select_date));

                    $Cart_details   = new Cart_details;
                    $Cart_details->tour_id = $cart_res->activtyId;
                    $Cart_details->generate_id = $cart_res->generate_id;
                    $Cart_details->tour_name = $cart_res->name;
                    $Cart_details->adults = $cart_res->adults;
                    $Cart_details->childs = $cart_res->children;
                    $Cart_details->adult_price = $cart_res->adult_price;
                    $Cart_details->child_price = $cart_res->child_price;
                    $Cart_details->activity_select_date = $start_date;
                    $Cart_details->tour_total_price = $cart_res->activity_total_price;
                    $Cart_details->total_service_price = $cart_res->total_service_price;
                    $Cart_details->price = $cart_res->price;
                    $Cart_details->cart_total_data = json_encode($cart_res);
                    $Cart_details->image = $cart_res->image;
                    $Cart_details->booking_id = $booking_id;
                    $Cart_details->invoice_no = $invoiceId;
                    $Cart_details->currency = $cart_res->currency;
                    $Cart_details->pakage_type = $cart_res->pakage_type;
                    $Cart_details->Additional_services_names = $addtional_services;
                    $Cart_details->client_id = $userData->id;
                    $Cart_details->stripe_payment_response = $request->slc_pyment_method;
                    $Cart_data = $Cart_details->save();

                    if(isset($cart_res->customer_id)){
                        $customer_data = DB::table('booking_customers')->where('id',$cart_res->customer_id)->select('id','balance')->first();
                        if(isset($customer_data)){
                            // echo "Enter hre ";
                            $customer_balance = $customer_data->balance + $cart_res->activity_total_price;

                            // update Agent Balance

                            DB::table('customer_ledger')->insert([
                                'booking_customer'=>$customer_data->id,
                                'received'=>$cart_res->activity_total_price,
                                'balance'=>$customer_balance,
                                'package_invoice_no'=>$invoiceId,
                                'customer_id'=>$userData->id,
                                'date'=>date('Y-m-d'),
                                ]);

                            DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                        }

                    }
                }


            DB::commit();
            return response()->json(['status'=>'success','booking_id'=>$tourObj->id,'invoice_id'=>$invoiceId]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>'error','message'=> 'Something Went Wrong Try Again']);
        }
    }

}
