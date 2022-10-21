<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Auth;
class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        if ($request->is('manager') || $request->is('manager/*')) {
            return redirect()->guest('/login/manager');
        }
        if ($request->is('customer') || $request->is('customer/*')) {
            return redirect()->guest('/login/customer');
        }
        return redirect()->guest(route('login'));
    }
}
