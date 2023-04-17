<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ManagerController;

Auth::routes();

Route::prefix('login')->name('login.')->controller(LoginController::class)->group(function () {
    Route::get('/manager', 'showManagerLoginForm')->name('manager');
    Route::get('/customer','showCustomerLoginForm')->name('customer');
    Route::post('/manager', 'managerLogin')->name('manager-post');
    Route::post('/customer', 'customerLogin')->name('customer-post');
});

Route::prefix('register')->name('register.')->controller(RegisterController::class)->group(function () {
    Route::get('/manager', 'showManagerRegisterForm')->name('manager');
    Route::get('/customer', 'showCustomerRegisterForm')->name('customer');
    Route::post('/manager', 'createManager')->name('manager-store');
    Route::post('/customer', 'createCustomer')->name('customer-store');
});

Route::group(['middleware' => 'auth:customer'], function () {
    Route::get('/customer', [CustomerController::class,'index'])->name('customer-dashboard');
    Route::get('/customer/getCompanies', [CustomerController::class,'getCompanies'])->name('customer-companies');
});

Route::group(['middleware' => 'auth:customer'], function () {
    Route::get('/company/{company_id}', [CompanyController::class,'index'])->name('company-dashboard');
    Route::get('/company/getInstances/{company_id}', [CompanyController::class,'getInstances'])->name('company-instances');
});

Route::group(['middleware' => 'auth:manager'], function () {
    Route::get('/manager', [ManagerController::class,'index'])->name('manager-dashboard');
});

Route::prefix('logout')->controller(LoginController::class)->name('logout.')->group(function () {
    Route::post('/customer', 'logoutCustomer')->name('customer');
    Route::post('/manager', 'logoutManager')->name('manager');
});

Route::get('/login', function () {
    return redirect()->route('login.customer');
});

Route::get('/404', function () {
    return view('errors.404');
})->name('404');
