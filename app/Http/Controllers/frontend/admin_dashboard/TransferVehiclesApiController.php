<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\addManageQuotation;
use App\Models\AccountDetailsApi;
use App\Models\viewBooking;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\country;
use App\Models\visa_Pay;
use App\Models\Transportation_Pay;
use App\Models\flight_Pay;
use App\Models\accomodation_Pay;
use App\Models\tranfer_vehicle;
use App\Models\tranfer_destination;
use App\Models\vehicle_category;
use App\Models\pickupPoint;
use App\Models\mazaraatDetails;
use DateTime;
use Carbon\Carbon;
use DB;

class TransferVehiclesApiController extends Controller
{
    // View Pickup Points
    public function getPickupPoints(Request $request){
        try {
            $userData               = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            if($userData){
                $pickupPoint        = DB::table('pickupPoint')->where('customer_id',$userData->id)->where('pickupPoint',$request->pickupPoint)->first();
                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Pickup Points Available',
                    'pickupPoint'   => $pickupPoint,
                ]);
            }else{
                return response()->json([
                    'status'        => 'error',
                    'message'       => 'Invalid Token!',
                    'pickupPoint'   => [],
                ]);
            }
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    // View Pickup Points
    public function viewPickupPoint(Request $request){
        try {
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                $pickupPoint  = DB::table('pickupPoint')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->orderBy('created_at','desc')->get();
            }else{
                $pickupPoint  = DB::table('pickupPoint')->where('customer_id',$request->customer_id)->orderBy('created_at','desc')->get();
            }
            return response()->json([
                'pickupPoint'  => $pickupPoint,
            ]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    // Add Pickup Points
    public function addPickupPoint(Request $request){
        DB::beginTransaction();
        try {
            $pickupPoint               = new pickupPoint;
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                $pickupPoint->SU_id    = $request->SU_id;
            }
            $pickupPoint->customer_id  = $request->customer_id;
            $pickupPoint->pickupPoint  = $request->pickupPoint;
            $pickupPoint->price        = $request->price;
            $pickupPoint->save();
            
            DB::commit();
            return response()->json([
                'message' => 'Success',
            ]);
        } catch (Throwable $e) {
             DB::rollback();
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    // Edit Pickup Points
    public function editPickupPoint(Request $request){
        try {
            $pickupPoint  = DB::table('pickupPoint')->where('id',$request->id)->first();
            return response()->json([
                'pickupPoint'  => $pickupPoint,
            ]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['message'=>'error','booking_id'=> '']);
        }
    }
    
    // Update Pickup Points
    public function updatePickupPoint(Request $request){
        DB::beginTransaction();
        try {
            $pickupPoint               = pickupPoint::find($request->id);
            $pickupPoint->pickupPoint  = $request->pickupPoint;
            $pickupPoint->price        = $request->price;
            $pickupPoint->update();
            
            DB::commit();
            return response()->json([
                'message' => 'Success',
            ]);
        } catch (Throwable $e) {
             DB::rollback();
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    // Delete Pickup Points
    public function deletePickupPoint(Request $request){
        DB::beginTransaction();
        try {
            $pickupPoint = DB::table('pickupPoint')->where('id',$request->id)->get();
            if($pickupPoint->isNotEmpty()){
                $departurePickupPoint = DB::table('transfers_new_booking')->where('customer_id', $request->customer_id)->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(transfer_data, '$.pickupPointNewId')) = ?", [$request->id])->get();
                if($departurePickupPoint->isNotEmpty()){
                    // dd('Departure Point Exist');
                    return response()->json([
                        'message' => 'error',
                    ]);
                }else{
                    $returnPickupPoint = DB::table('transfers_new_booking')->where('customer_id', $request->customer_id)->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(transfer_data, '$.retpickupPointNewId')) = ?", [$request->id])->get();
                    if($returnPickupPoint->isNotEmpty()){
                        // dd('Return Point Exist');
                        return response()->json([
                            'message' => 'error',
                        ]);
                    }
                    else{
                        // dd('Booking Not Exist');
                        pickupPoint::find($request->id)->delete();
                        DB::commit();
                        return response()->json([
                            'message' => 'Success',
                        ]);
                    }
                }
            }else{
                return response()->json([
                    'message' => 'Success',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    // get_tbo_hotel_details
    public function get_tbo_hotel_details(Request $req){
        $HotelCode = $req->HotelCode;
        $data = DB::table('synchron_mata.tbo_hotel_details1')->where('hotel_code',$HotelCode)->first();
        return response()->json([
            'data' => $data,
        ]);
    }
    
    // Add Vehicles
    public function add_Vehicles(Request $request){
        $vehicle_category   = DB::table('vehicle_category')->where('customer_id',$request->customer_id)->get();
        return response()->json(['message'=>'success','vehicle_category' => $vehicle_category]);
        // return view('template/frontend/userdashboard/pages/Transfer_Details/add_Vehicles');
    }
    
    // Search Vehicles
    public function search_Vehicles(Request $req){
      print_r($req->all());
              $tours = DB::table('tranfer_destination')
                    ->where('pickup_City','LIKE','%'.''.$request->location.'%')
                    ->where('dropof_City','LIKE','%'.''.$request->location.'%')
                    
                    ->Where(function($query) use($request_data) {
                        
                        $query->orwhere('pickup_City','LIKE','%'.''.$request_data->location.'%')
                        ->orwhere('tours_2.double_grand_total_amount', [$request_data->min,$request_data->max]);
                      
                     })
                    ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration','tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours.tour_attributes','tours_2.flights_details','tours_2.flights_details_more')->orderBy('tours.created_at', 'desc')->get();
                    return response()->json(['message'=>'success','data'=>$tours]);
    }
    
    // Add New Vehicle
    public function add_new_vehicle(Request $request){
        // Data
        $vehicle = new tranfer_vehicle;
        $vehicle->customer_id           =  $request->customer_id;
        
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $vehicle->SU_id               = $request->SU_id;
        }
        
        $vehicle->currency_symbol       =  $request->currency_symbol;
        $vehicle->vehicle_category_id   =  $request->vehicle_category_id;
        $vehicle->vehicle_Name          =  $request->vehicle_Name;
        $vehicle->vehicle_Description   =  $request->vehicle_Description;
        $vehicle->vehicle_image         =  $request->vehicle_image;
        $vehicle->vehicle_Status        =  $request->vehicle_Status;
        $vehicle->vehicle_Type          =  $request->vehicle_Type;
        $vehicle->vehicle_Passenger     =  $request->vehicle_Passenger;
        $vehicle->vehicle_Door          =  $request->vehicle_Door;
        $vehicle->vehicle_Transmission  =  $request->vehicle_Transmission;
        $vehicle->vehicle_Baggage       =  $request->vehicle_Baggage;
        $vehicle->vehicle_Picking       =  $request->vehicle_Picking;
        $vehicle->meta_title            =  $request->meta_title;
        $vehicle->meta_keywords         =  $request->meta_keywords; 
        $vehicle->meta_desc             =  $request->meta_desc;
        $vehicle->payment_option        =  $request->payment_option;
        $vehicle->policy_and_terms      =  $request->policy_and_terms;
        $vehicle->gallery      =  $request->vehicle_gallery;
        
        $vehicle->save();
        // End Data
        
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $tranfer_vehicle = DB::table('tranfer_vehicle')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
        }else{
            $tranfer_vehicle = DB::table('tranfer_vehicle')->where('customer_id',$request->customer_id)->get();
        }
        
        return response()->json([
            'message'           => 'Success',
            'tranfer_vehicle'   => $tranfer_vehicle,
        ]);
    }
    
    // View Vehicles
    public function view_Vehicles1(Request $request){
        $data = DB::table('tranfer_vehicle')->where('customer_id',$request->customer_id)->where('vehicle_Name',$request->vehicle_Name)->first();
        return response()->json([
            'data' => $data,
        ]);
    }
    
    // View Vehicles
    public function view_Vehicles(Request $request){
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $data               = DB::table('tranfer_vehicle')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
        }else{
            $data               = DB::table('tranfer_vehicle')->where('customer_id',$request->customer_id)->get();
        }
        return response()->json([
            'data' => $data,
        ]);
       
    }
    
    // Edit Vehicles
    public function edit_vehicle_details(Request $request){
        $data               = DB::table('tranfer_vehicle')->where('id',$request->id)->first();
        $vehicle_category   = DB::table('vehicle_category')->where('customer_id',$request->customer_id)->get();
        return response()->json([
            'data'              => $data,
            'vehicle_category'  => $vehicle_category
        ]);
    }
    
    public function delete_vehicle_details(Request $request){
        DB::beginTransaction();
        try {
            $vehicleExist = DB::table('tranfer_vehicle')->where('id',$request->id)->first();
            if($vehicleExist == null){
                return response()->json(['status' => 'error', 'message' => 'Vehicle Does Not Exist']);
            }
            $transferBookings  = DB::table('transfers_new_booking')->where('customer_id', $request->customer_id)->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(transfer_data, '$.vehicle_Id')) = ?", [$request->id])->get();
            if ($transferBookings->isNotEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'Booking Exist']);
            }else{
                DB::table('tranfer_vehicle')->where('id',$request->id)->delete();
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Vehicle Deleted Successfully']);
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error', 'message' => $e->message ?? 'Something Went Wrong']);
        }
    }
    
    // Update New Vehicle
    public function update_vehicle(Request $request){

        // Data
        $id      = $request->id;
        $vehicle = tranfer_vehicle::find($id);
        $vehicle->customer_id           =  $request->customer_id;
        $vehicle->vehicle_category_id   =  $request->vehicle_category_id;
        $vehicle->vehicle_Name          =  $request->vehicle_Name;
        $vehicle->vehicle_Description   =  $request->vehicle_Description;
        $vehicle->vehicle_image         =  $request->vehicle_image;
        $vehicle->vehicle_Status        =  $request->vehicle_Status;
        $vehicle->vehicle_Type          =  $request->vehicle_Type;
        $vehicle->vehicle_Passenger     =  $request->vehicle_Passenger;
        $vehicle->vehicle_Door          =  $request->vehicle_Door;
        $vehicle->vehicle_Transmission  =  $request->vehicle_Transmission;
        $vehicle->vehicle_Baggage       =  $request->vehicle_Baggage;
        $vehicle->vehicle_Picking       =  $request->vehicle_Picking;
        $vehicle->meta_title            =  $request->meta_title;
        $vehicle->meta_keywords         =  $request->meta_keywords; 
        $vehicle->meta_desc             =  $request->meta_desc;
        $vehicle->payment_option        =  $request->payment_option;
        $vehicle->policy_and_terms      =  $request->policy_and_terms;
        
        $vehicle->update();
        // End Data
        
        return response()->json([
            'message' => 'Success',
        ]);
    }
    
    // Mazaraat
    public function viewMazaraat(Request $request){
        try {
            $vehicleDetails         = DB::table('tranfer_vehicle')->where('customer_id',$request->customer_id)->get();
            $mazaraatDetails        = DB::table('mazaraatDetails')->where('customer_id',$request->customer_id)->get();
            $country                = country::get();
            $currencies             = DB::table('mange_currencies')->where('customer_id',$request->customer_id)->get();
            $transferSupplier       = DB::table('transfer_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
            
            DB::commit();
            return response()->json([
                'vehicleDetails'    => $vehicleDetails,
                'mazaratDetails'    => $mazaraatDetails,
                'country'           => $country,
                'currencies'        => $currencies,
                'transferSupplier'  => $transferSupplier,
            ]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function addMazaraat(Request $request){
        DB::beginTransaction();
        try {
            DB::table('mazaraatDetails')->insert([
                'token'                 => $request->token,
                'customer_id'           => $request->customer_id,
                'conversion_type_Id'    => $request->currencyConversion,
                'country'               => $request->country,
                'city'                  => $request->city,
                'availableFrom'         => $request->availableFrom,
                'availableTo'           => $request->availableTo,
                'ziyarat_City_details'  => $request->mazaraatDetails,
                'vehicle_details'       => $request->vehicleDetails,
            ]);
            
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Mazaraat Added Successfully']);
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error', 'message' => $e->message ?? 'Something Went Wrong']);
        }
       
    }
    
    public function editMazaraat(Request $request){
        try {
            $vehicleDetails         = DB::table('tranfer_vehicle')->where('customer_id',$request->customer_id)->get();
            $mazaraatDetails        = DB::table('mazaraatDetails')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            $country                = country::get();
            $currencies             = DB::table('mange_currencies')->where('customer_id',$request->customer_id)->get();
            $transferSupplier       = DB::table('transfer_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
            
            DB::commit();
            return response()->json([
                'vehicleDetails'    => $vehicleDetails,
                'mazaratDetails'    => $mazaraatDetails,
                'country'           => $country,
                'currencies'        => $currencies,
                'transferSupplier'  => $transferSupplier,
            ]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function updateMazaraat(Request $request){
        DB::beginTransaction();
        try {
            DB::table('mazaraatDetails')->where('customer_id',$request->customer_id)->where('id',$request->id)->update([
                'conversion_type_Id'    => $request->currencyConversion,
                'country'               => $request->country,
                'city'                  => $request->city,
                'availableFrom'         => $request->availableFrom,
                'availableTo'           => $request->availableTo,
                'ziyarat_City_details'  => $request->mazaraatDetails,
                'vehicle_details'       => $request->vehicleDetails,
            ]);
            
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Mazaraat Updated Successfully']);
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error', 'message' => $e->message ?? 'Something Went Wrong']);
        }
       
    }
    
    // Destination
    public function view_Destination(Request $request){
        // if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
        //     $data                = DB::table('tranfer_vehicle')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
        //     $tranfer_destination = DB::table('tranfer_destination')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
        //     $tranfer_supplier    = DB::table('transfer_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
        //     $tranfer_company     = DB::table('transfer_Invoice_Company')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
        //     $mange_currencies    = DB::table('mange_currencies')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
        // }else{
            $data                = DB::table('tranfer_vehicle')->where('customer_id',$request->customer_id)->get();
            $tranfer_destination = DB::table('tranfer_destination')->where('customer_id',$request->customer_id)->orderBy('id', 'desc')->get();
            $tranfer_supplier    = DB::table('transfer_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
            $tranfer_company     = DB::table('transfer_Invoice_Company')->where('customer_id',$request->customer_id)->get();
            $mange_currencies    = DB::table('mange_currencies')->where('customer_id',$request->customer_id)->get();
        // }
        return response()->json([
            'data'                  => $data,
            'tranfer_destination'   => $tranfer_destination,
            'tranfer_supplier'      => $tranfer_supplier,
            'tranfer_company'       => $tranfer_company,
            'mange_currencies'      => $mange_currencies,
        ]);
       
    }
    
    public function delete_destination(Request $request){
        $tranfer_destination = DB::table('tranfer_destination')->where('id',$request->destination_id)->delete();
        if($tranfer_destination){
            return response()->json([
                'status'                => 'success',
                'tranfer_destination'   => $tranfer_destination,
            ]);   
        }
        else{
           return response()->json(['status'=>'error']);
        }
    }
    
    public function delete_Destination_Details(Request $request){
        try {
            $destination_Exist  = false;
            $id                 = $request->id;
            
            // INVOICE
            // $id = 237;
            // $id = 178;
            $invoice_Transfers = DB::table('add_manage_invoices')
                                    ->where('customer_id', $request->customer_id)
                                    ->whereNotNull('transportation_details')
                                    ->where('transportation_details', '!=', '')
                                    ->where(function ($query) use ($id) {
                                        $query->whereJsonContains('transportation_details', ['destination_id' => $id])
                                            ->orWhereJsonContains('transportation_details', ['destination_id' => strval($id)]);
                                    })
                                ->get();
            if(count($invoice_Transfers) > 0){
                $destination_Exist = true;
            }
            // INVOICE
            
            // TOUR
            // $id = 1;
            $package_Transfers = DB::table('tours')
                                    ->where('customer_id', $request->customer_id)
                                    ->join('tours_2','tours.id','tours_2.tour_id')
                                    ->whereNotNull('transportation_details')
                                    ->where('transportation_details', '!=', '')
                                    ->where(function ($query) use ($id) {
                                        $query->whereJsonContains('transportation_details', ['destination_id' => $id])
                                            ->orWhereJsonContains('transportation_details', ['destination_id' => strval($id)]);
                                    })
                                    ->select('tours.id','transportation_details')
                                    ->get();
            if(count($package_Transfers) > 0){
                for($a=0; $a<count($package_Transfers); $a++){
                    $package_booking_Transfers  = DB::table('cart_details')
                                                    ->where('pakage_type','tour')
                                                    ->where('confirm','!=',NULL)
                                                    ->where('tour_id',$package_Transfers[$a]->id)
                                                    ->get();
                    if(count($package_booking_Transfers) > 0){
                        $destination_Exist = true;
                    }
                }
            }
            // TOUR
            
            // WEBSITE
            // $id = 236;
            $website_Transfers = DB::table('transfers_new_booking')
                                    ->where('customer_id', $request->customer_id)
                                    ->where('transfer_destination_id',$id)
                                    ->get();
            if(count($website_Transfers) > 0){
                $destination_Exist = true;
            }
            // WEBSITE
            
            if($destination_Exist != true){
                DB::table('tranfer_destination')->where('customer_id',$request->customer_id)->where('id',$request->id)->delete();
                return response()->json(['status'=>'Success']);   
            }
            else{
               return response()->json(['status'=>'Exist']);
            }
        } catch (Throwable $e) {
            echo $e;
            DB::rollback();
            return response()->json(['status'=>'Error']);
        }
    }
    
    // Add New Destination
    public function add_new_destination(Request $request){
        // Data
        $destination = new tranfer_destination;
        $destination->customer_id           =  $request->customer_id;
        
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $destination->SU_id           =  $request->SU_id;
        }
        
        $destination->pickup_City           =  $request->pickup_City;
        $destination->pickup_CountryCode    =  $request->pickup_CountryCode;
        $destination->dropof_City           =  $request->dropof_City;
        $destination->pickup_api_City       =  $request->pickup_api_City;
        $destination->dropof_api_City       =  $request->dropof_api_City;
        $destination->return_pickup_City    =  $request->return_pickup_City;
        $destination->return_dropof_City    =  $request->return_dropof_City;
        $destination->pickup_sub_loc        =  $request->pickup_sub_loc;
        $destination->drop_sub_loc          =  $request->drop_sub_loc;
        $destination->transfer_type         =  $request->transfer_type;
        $destination->transfer_company      =  $request->transfer_company;
        $destination->available_from        =  $request->available_from;
        $destination->available_to          =  $request->available_to;
        $destination->currency_conversion   =  $request->currency_conversion;
        $destination->conversion_type_Id    =  $request->conversion_type_Id;
        $destination->select_exchange_type  =  $request->select_exchange_type;
        $destination->vehicle_details       =  $request->vehicle_details;
        $destination->more_destination_details  =  $request->more_destination_details;
        $destination->ziyarat_City_details      =  $request->ziyarat_City_details;
        
        $destination->save();
        // End Data
        return response()->json([
            'message' => 'Success',
        ]);
    }
    
    // Edit Destination Details
    public function edit_destination_details(Request $request){
        $data                = DB::table('tranfer_vehicle')->where('customer_id',$request->customer_id)->get();
        $tranfer_destination = DB::table('tranfer_destination')->where('id',$request->id)->first();
        $tranfer_supplier    = DB::table('transfer_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
        $tranfer_company     = DB::table('transfer_Invoice_Company')->where('customer_id',$request->customer_id)->get();
        $mange_currencies    = DB::table('mange_currencies')->where('customer_id',$request->customer_id)->get();
        return response()->json([
            'data'                  => $data,
            'tranfer_destination'   => $tranfer_destination,
            'tranfer_supplier'      => $tranfer_supplier,
            'tranfer_company'       => $tranfer_company,
            'mange_currencies'      => $mange_currencies,
        ]);
       
    }
    
    // Update Destination
    public function update_destination(Request $request){

        $id      = $request->id;
        $destination = tranfer_destination::find($id);
        $destination->customer_id           =  $request->customer_id;
        // Data
        $destination->pickup_City           =  $request->pickup_City;
        $destination->pickup_CountryCode    =  $request->pickup_CountryCode;
        $destination->dropof_City           =  $request->dropof_City;
        $destination->return_pickup_City    =  $request->return_pickup_City;
        $destination->return_dropof_City    =  $request->return_dropof_City;
        $destination->pickup_sub_loc        =  $request->pickup_sub_loc;
        $destination->drop_sub_loc          =  $request->drop_sub_loc;
        $destination->transfer_type         =  $request->transfer_type;
        $destination->transfer_company      =  $request->transfer_company;
        $destination->available_from        =  $request->available_from;
        $destination->available_to          =  $request->available_to;
        $destination->currency_conversion   =  $request->currency_conversion;
        $destination->conversion_type_Id    =  $request->conversion_type_Id;
        $destination->select_exchange_type  =  $request->select_exchange_type;
        $destination->vehicle_details       =  $request->vehicle_details;
        $destination->more_destination_details  =  $request->more_destination_details;
        $destination->ziyarat_City_details      =  $request->ziyarat_City_details;
        
        $destination->update();
        // End Data
        
        return response()->json([
            'message' => 'Success',
        ]);
    }
    
    // View Vehicle Category
    public function view_Vehicle_Category(Request $request){
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $vehicle_Category  = DB::table('vehicle_category')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
        }else{
            $vehicle_Category  = DB::table('vehicle_category')->where('customer_id',$request->customer_id)->get();
        }
        return response()->json([
            'vehicle_Category'  => $vehicle_Category,
        ]);
       
    }
    
    // Add New Vehicle Category
    public function add_new_vehicle_category(Request $request){
        $vehcile_Category                       = new vehicle_category;
        
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $vehcile_Category->SU_id               = $request->SU_id;
        }
        
        $vehcile_Category->customer_id          = $request->customer_id;
        $vehcile_Category->vehicle_category     = $request->vehicle_category;
        $vehcile_Category->max_passeneger       = $request->max_passeneger;
        $vehcile_Category->max_bagage           = $request->max_bagage;
        $vehcile_Category->save();
        
        return response()->json([
            'message' => 'Success',
        ]);
    }
    
    // Add New Vehicle Category Ajax
    public function add_new_vehicle_category_Ajax(Request $request){

        $vehcile_Category                       = new vehicle_category;
        $vehcile_Category->customer_id          = $request->customer_id;
        $vehcile_Category->vehicle_category     = $request->vehicle_category;
        $vehcile_Category->max_passeneger       = $request->max_passeneger;
        $vehcile_Category->max_bagage           = $request->max_bagage;
        $vehcile_Category->save();
        
        $vehicle_Category = DB::table('vehicle_category')->where('customer_id',$request->customer_id)->get();
        
        return response()->json([
            'message'           => 'Success',
            'vehicle_Category'  => $vehicle_Category,
        ]);
    }
    
    // Edit Vehicle Category Details
    public function edit_vehicle_category(Request $request){
        $vehicle_Category  = DB::table('vehicle_category')->where('id',$request->id)->get();
        return response()->json([
            'vehicle_Category' => $vehicle_Category,
        ]);
       
    }
    
    // Update Vehicle Category
    public function update_vehicle_category(Request $request){
        $id                                     = $request->id;
        $vehcile_Category                       = vehicle_category::find($id);
        $vehcile_Category->customer_id          = $request->customer_id;
        $vehcile_Category->vehicle_category     = $request->vehicle_category;
        $vehcile_Category->max_passeneger       = $request->max_passeneger;
        $vehcile_Category->max_bagage           = $request->max_bagage;
        $vehcile_Category->update();

        return response()->json([
            'message' => 'Success',
        ]);
    }
    
    // Delete Vehicle Category
    public function delete_vehicle_category(Request $request){
        $id                                     = $request->id;
        $vehcile_Category                       = vehicle_category::find($id);
        $vehcile_Category->delete();
        
        return response()->json([
            'message' => 'Success',
        ]);
    }
    
    public function transfer_Bookings_Season_Working($all_data,$request){
        // dd($request->start_date);
        $today_Date     = date('Y-m-d');
        
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
            $start_Date = $season_Details->start_Date;
            $end_Date   = $season_Details->end_Date;
            
            $filtered_data = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                // dd($record);
                if (!isset($record->created_at)) {
                    return false;
                }
                
                if($record->created_at != null && $record->created_at != ''){
                    $checkIn    = Carbon::parse($record->created_at);
                    return $checkIn->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkIn->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    // Transfer Bookings
    public function transfer_Bookings(Request $request){
        $system_url     = DB::table('customer_subcriptions')->where('id',$request->customer_id)->select('webiste_Address')->first();
        $data           = DB::table('transfers_new_booking')->where('customer_id',$request->customer_id)->orderBy('id', 'desc')->get();
        
        $season_Id      = '';
        $today_Date     = date('Y-m-d');
        if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
            $season_Id  = 'all_Seasons';
        }elseif(isset($request->season_Id) && $request->season_Id > 0){
            $season_SD  = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
            $season_Id  = $season_SD->id ?? '';
        }else{
            $season_SD  = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            $season_Id  = $season_SD->id ?? '';
        }
        $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        // Season
        if($request->customer_id == 4){
            // dd($data);
            $data       = $this->transfer_Bookings_Season_Working($data,$request);
            // dd($data);
        }
        // Season
        
        return response()->json([
            'data'              => $data,
            'system_url'        => $system_url->webiste_Address,
            'season_Details'    => $season_Details,
            'season_Id'         => $season_Id,
        ]);
       
    }
}