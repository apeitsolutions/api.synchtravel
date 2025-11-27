<?php

namespace App\Http\Controllers\frontend\admin_dashboard\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use concat;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Models\companies;
use App\Models\companyreviews;
use Illuminate\Validation\Rule;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\CustomerSubcription\RoleManager;

class CompanyController extends Controller
{
    public function loginCompany(Request $request){
        $token          = $request->token;
        $credentials    = $request->validate([
            'token'     => ['required'],
        ]);
        
        $userData       = CustomerSubcription::where('Auth_key',$token)->first();
        if($userData){
            if($userData->status == 1){
                return response()->json([
                    'status'    => 'Success',
                    'data'      => $userData,
                ]);
            }
        }
        return response()->json([
            'status'    => 'Failed',
            'message'   => 'Invalid Token',
        ]);
    }
    
    public function dashboardCompany(Request $request){
        try {
            $companies      = DB::table('companies')->where('token', $request->token)->orderBy('created_at', 'desc')->get();
            $reviews        = DB::table('companyreviews')->where('token', $request->token)->orderBy('created_at', 'desc')->get();
            return response()->json([
                'success'   => true,
                'companies' => $companies,
                'reviews'   => $reviews,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blog posts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function addCompany(Request $request){
        $request->validate([
            'token'             => 'required|string',
            'customer_id'       => 'required|string',
            'email'             => 'required|string|max:255',
            'password'          => 'required|string|max:255',
            'name'              => 'required|string|max:255',
            'description'       => 'required|string|max:255',
            'packagecode'       => 'required|string|max:255',
        ]);
        
        try {
            $postId             = DB::table('companies')->insertGetId([
                'token'         => $request->token,
                'SU_id'         => $request->SU_id ?? NULL,
                'customer_id'   => $request->customer_id,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'name'          => $request->name,
                'description'   => $request->description,
                'packagecode'   => $request->packagecode,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
            
            return response()->json([
                'message' => 'Company created successfully',
                'post_id' => $postId,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong while saving the blog post.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    public function viewCompany(Request $request){
        try {
            $companies = DB::table('companies')->where('token', $request->token)->orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $companies
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blog posts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function viewReviews(Request $request){
        try {
            $reviews        = DB::table('companyreviews')->where('token', $request->token)->orderBy('created_at', 'desc')->get();
            return response()->json([
                'success'   => true,
                'data'      => $reviews
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => 'Failed to retrieve blog posts.',
                'error'     => $e->getMessage()
            ], 500);
        }
    }
}