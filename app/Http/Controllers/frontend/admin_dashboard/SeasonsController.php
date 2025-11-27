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
use App\Models\rooms_Invoice_Supplier;
use App\Models\alhijaz_Notofication;
use App\Models\flight_seats_occupied;
use App\Models\addLead;
use App\Models\addAtol;
use App\Models\addAtolFlightPackage;
use App\Models\addManageQuotationPackage;
use DB;
use Carbon\Carbon;

class SeasonsController extends Controller
{
    public function create_Season(Request $req){
        $season_Detail    = DB::table('add_Seasons')->where('customer_id',$req->customer_id)->get();
        return response()->json([
            'message'       => 'success',
            'season_Detail' => $season_Detail,
        ]);
    }
    
    public function add_Season(Request $req){
        DB::beginTransaction();
        try {
            $check_Seasons = DB::table('add_Seasons')->where('customer_id', $req->customer_id)
                                ->where(function($query) use ($req) {
                                    $query->whereBetween('start_Date', [$req->start_Date, $req->end_Date])
                                    ->orWhereBetween('end_Date', [$req->start_Date, $req->end_Date])
                                    ->orWhere(function($query) use ($req) {
                                        $query->where('start_Date', '<=', $req->start_Date)
                                            ->where('end_Date', '>=', $req->end_Date);
                                    });
                                })
                                ->get();
            // dd($check_Seasons);
            if($check_Seasons->isempty()){
                DB::table('add_Seasons')->insert([
                    'token'         => $req->token,
                    'customer_id'   => $req->customer_id,
                    'season_Name'   => $req->season_Name,
                    'start_Date'    => $req->start_Date,
                    'end_Date'      => $req->end_Date,
                ]);
                
                $season_Detail    = DB::table('add_Seasons')->where('customer_id',$req->customer_id)->get();
                
                DB::commit();
                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Season Added Successfully',
                    'season_Detail' => $season_Detail,
                ]);
            }else{
                DB::commit();
                return response()->json([
                    'status'        => 'error',
                    'message'       => 'Season Added Exist',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
    }
    
    public function edit_Season(Request $req){
        $season_Detail    = DB::table('add_Seasons')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
        return response()->json([
            'message'       => 'success',
            'season_Detail' => $season_Detail,
        ]);
    }
    
    public function update_Season(Request $req){
        DB::beginTransaction();
        try {
            $check_Seasons = DB::table('add_Seasons')->where('customer_id', $req->customer_id)->where('id', '!=' ,$req->id)
                                ->where(function($query) use ($req) {
                                    $query->whereBetween('start_Date', [$req->start_Date, $req->end_Date])
                                    ->orWhereBetween('end_Date', [$req->start_Date, $req->end_Date])
                                    ->orWhere(function($query) use ($req) {
                                        $query->where('start_Date', '<=', $req->start_Date)
                                            ->where('end_Date', '>=', $req->end_Date);
                                    });
                                })
                                ->get();
            // dd($check_Seasons);
            if($check_Seasons->isempty()){
                DB::table('add_Seasons')->where('id',$req->id)->update([
                    'season_Name'   => $req->season_Name,
                    'start_Date'    => $req->start_Date,
                    'end_Date'      => $req->end_Date,
                ]);
                
                $season_Detail    = DB::table('add_Seasons')->where('customer_id',$req->customer_id)->get();
                
                DB::commit();
                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Season Updated Successfully',
                    'season_Detail' => $season_Detail,
                ]);
            }else{
                DB::commit();
                return response()->json([
                    'status'        => 'error',
                    'message'       => 'Season Added Exist',
                ]);
            }
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
    }
}