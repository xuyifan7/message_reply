<?php

namespace App\Http\Middleware;

use Closure;
use http\Client\Curl\User;

class AdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->get('user')){
            return $next($request);
        }else{
            return response()->json(['msg'=>'Please login！']);
            //return redirect('user/login')->with('error','please login!');
        }
    }
}