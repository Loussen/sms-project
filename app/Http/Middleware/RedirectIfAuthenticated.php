<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
//        echo $guard; exit;
        if ($guard == "manager" && Auth::guard($guard)->check()) {
            return redirect('/manager');
        }
        if ($guard == "customer" && Auth::guard($guard)->check()) {
            return redirect('/customer');
        }
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }

        return $next($request);
    }
}
