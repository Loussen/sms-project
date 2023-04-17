<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    private $module_name;


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        if(!App::runningInConsole()) {
            if (Route::getCurrentRoute()->getAction('as') == 'login.customer') {
                $this->middleware('guest:customer')->except('logoutCustomer');
            } elseif (Route::getCurrentRoute()->getAction('as') == 'login.manager') {
                $this->middleware('guest:manager')->except('logoutManager');
            } else {
                $this->middleware('guest')->except('logout');
            }
        }

    }

    public function showManagerLoginForm()
    {
        return view('auth.login', ['url' => 'manager']);
    }

    public function managerLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return redirect()->intended(route('manager-dashboard'));
        }
        return back()->withInput($request->only('email', 'remember'));
    }

    public function showCustomerLoginForm()
    {
        return view('auth.login', ['url' => 'customer']);
    }

    public function customerLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return redirect()->intended(route('customer-dashboard'));
        }
        return back()->withInput($request->only('email', 'remember'));
    }

    public function logoutCustomer(Request $request)
    {
        if(Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
            return redirect()->route('login.customer');
        }

        return redirect()->intended(route('customer-dashboard'));
    }

    public function logoutManager(Request $request)
    {
        if(Auth::guard('manager')->check()) {
            Auth::guard('manager')->logout();
            return redirect()->route('login.manager');
        }

        return redirect()->intended(route('customer-dashboard'));
    }

//    public function logout(Request $request)
//    {
//        Auth::logout();
//
//        $request->session()->invalidate();
//
//        $request->session()->regenerateToken();
//
//        return redirect()->route('login');
//    }
}
