<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard('employee')->check() && $request->user()->type != 'employee')
        {
            return $next($request);
        }
        else
        {
            return redirect('super_admin/employee_login');
        }
//        return $next($request);
    }
}
