<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientMiddleware
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
       $token=$request->token;
       $token1='5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
       
       if($token == $token1)
       {
          if(Auth::guard('customer')->check() && $request->user()->type != 'customer')
        {
            return $next($request);
        }
        else
        {
            return redirect('login1');
        } 
       }
       print_r($token);die();
        
//        return $next($request);
    }
}
