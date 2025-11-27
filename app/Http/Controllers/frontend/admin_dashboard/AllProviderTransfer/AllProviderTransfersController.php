<?php

namespace App\Http\Controllers\frontend\admin_dashboard\AllProviderTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\hotel_manager\Rooms;
use App\Models\admin\RoomsType;
use App\Models\hotel_manager\RoomGallery;
use App\Models\hotel_manager\Hotels;
use App\Models\MetaInfo;
use App\Models\Policies;
use App\Models\country;
use App\Models\city;
use App\Models\rooms_Invoice_Supplier;
use App\Models\allowed_Hotels_Rooms;
use Session;
use concat;
use Auth;
use DB;

class AllProviderTransfersController extends Controller
{
    public function getAllProvidersTransfers(Request $request){
        $today_Date                                 = date('Y-m-d');
        $id                                         = $request->customer_id;
        $clientDetails                              = DB::table('customer_subcriptions')->where('id', '!=', $request->customer_id)->get();
        $allTransferDetails                         = [];
        $tranferDestination                         = DB::table('tranfer_destination')->where('customer_id', $request->customer_id)->orderBy('id', 'desc')->get();
        if (!empty($tranferDestination) && count($tranferDestination) > 0) {
            foreach ($tranferDestination as $value) {
                $vehicleDetailsRaw                  = json_decode($value->vehicle_details);
                $vehicleDetails                     = [];
                $supplierDetails                    = [];
                if (!empty($vehicleDetailsRaw) && is_array($vehicleDetailsRaw)) {
                    foreach ($vehicleDetailsRaw as $val_VD) {
                        if (isset($val_VD->vehicle_id)) {
                            // Fetch vehicle detail
                            $vehicle                = DB::table('tranfer_vehicle')->where('customer_id', $request->customer_id)->where('id', $val_VD->vehicle_id)->first();
                            if ($vehicle) {
                                $vehicleDetails[]   = $vehicle;
                            }
                        }
                        if (isset($val_VD->transfer_supplier_Id)) {
                            // Fetch supplier detail
                            $supplier               = DB::table('transfer_Invoice_Supplier')->where('customer_id', $request->customer_id)->where('id', $val_VD->transfer_supplier_Id)->first();
                            if ($supplier) {
                                $supplierDetails[]  = $supplier;
                            }
                        }
                    }
                }
                
                $singleTransferDetails              = [
                    'destinationDetails'            => $value,
                    'vehicleDetails'                => $vehicleDetails ?: null,
                    'supplierDetails'               => $supplierDetails ?: null,
                ];
                $allTransferDetails[]               = $singleTransferDetails;
            }
        }
        return response()->json([
            'allTransferDetails'                    => $allTransferDetails,
            'clientDetails'                         => $clientDetails,
        ]);
    }
    
    public function checkclientdestinations(Request $request){
        $today_Date                                 = date('Y-m-d');
        $id                                         = $request->customer_id;
        $clientDetails                              = DB::table('customer_subcriptions')->where('id', $request->clientid)->get();
        $allTransferDetails                         = [];
        $tranferDestination                         = DB::table('tranfer_destination')->where('customer_id', $request->customer_id)->where('id', $request->destinationid)->get();
        if (!empty($tranferDestination) && count($tranferDestination) > 0) {
            foreach ($tranferDestination as $value) {
                $vehicleDetailsRaw                  = json_decode($value->vehicle_details);
                $vehicleDetails                     = [];
                $supplierDetails                    = [];
                if (!empty($vehicleDetailsRaw) && is_array($vehicleDetailsRaw)) {
                    foreach ($vehicleDetailsRaw as $val_VD) {
                        if (isset($val_VD->vehicle_id)) {
                            // Fetch vehicle detail
                            $vehicle                = DB::table('tranfer_vehicle')->where('customer_id', $request->customer_id)->where('id', $val_VD->vehicle_id)->first();
                            if ($vehicle) {
                                $vehicleDetails[]   = $vehicle;
                            }
                        }
                        if (isset($val_VD->transfer_supplier_Id)) {
                            // Fetch supplier detail
                            $supplier               = DB::table('transfer_Invoice_Supplier')->where('customer_id', $request->customer_id)->where('id', $val_VD->transfer_supplier_Id)->first();
                            if ($supplier) {
                                $supplierDetails[]  = $supplier;
                            }
                        }
                    }
                }
                
                $singleTransferDetails              = [
                    'destinationDetails'            => $value,
                    'vehicleDetails'                => $vehicleDetails ?: null,
                    'supplierDetails'               => $supplierDetails ?: null,
                ];
                $allTransferDetails[]               = $singleTransferDetails;
            }
        }
        return response()->json([
            'allTransferDetails'                    => $allTransferDetails,
            'clientDetails'                         => $clientDetails,
        ]);
    }
}