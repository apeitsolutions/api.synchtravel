<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\CustomerSubcription\CustomerSubcription;
use DB;
use App\Models\country;

class TokenCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd('aa');
        $userData = CustomerSubcription::where('Auth_key',$request->token)->where('status','1')->select('id','status')->first();
        if(isset($userData) && $userData != null && $userData != ''){
            return $next($request);
        }else{
            // return redirect('Error_Authenticate');
            return response()->json(['message'=>'error']);
        }
    }
}
