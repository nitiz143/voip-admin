<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SettingController;
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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::group(['middleware' => ['auth','activity']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('/call','App\Http\Controllers\CallController');
    Route::get('/getCallhistory','App\Http\Controllers\CallController@getCallhistory')->name('getCallhistory');
    Route::resource('/users','App\Http\Controllers\UserController');
    Route::post('getUsers','App\Http\Controllers\UserController@getUsers')->name('getUsers');
    // Route::get('autocomplete','App\Http\Controllers\UserController@autocomplete')->name('autocomplete');
    Route::resource('/crm','App\Http\Controllers\CRMController');
    Route::resource('/client','App\Http\Controllers\ClientController');
    Route::get('client-customer/{id}','App\Http\Controllers\ClientController@customer')->name('client.customer');
    Route::get('client-vendor/{id}','App\Http\Controllers\ClientController@vendor')->name('client.vendor');
    Route::get('customers','App\Http\Controllers\ClientController@customers')->name('customers');
    Route::get('vendors','App\Http\Controllers\ClientController@vendors')->name('Vendors');
    Route::post('vendorTrunk-Update/{id}','App\Http\Controllers\ClientController@vendortrunk')->name('Vendor.trunk');
    Route::post('customerTrunk-Update/{id}','App\Http\Controllers\ClientController@customertrunk')->name('Customer.trunk');
    Route::post('customer/Codedeckid', [ClientController::class, 'fetchRateTable'])->name('customerCodedeckid.update');
    Route::post('vendor/Codedeckid', [ClientController::class, 'updatecodeckid'])->name('vendorCodedeckid.update');
    Route::get('owners-customer/{id}', [ClientController::class, 'owners_customer'])->name('owners_customer');
    Route::post('customer_rate/{id}/process_download', [ClientController::class, 'process_download'])->name('process_download');
    Route::get('history-detail/{id}', [ClientController::class, 'history_detail'])->name('history_detail');
    Route::get('/getClient/{id}','App\Http\Controllers\CRMController@ImportClient')->name('getClient');
    Route::resource('/cron','App\Http\Controllers\CronJobController');
    Route::resource('/setting','App\Http\Controllers\SettingController');
    Route::resource('/company','App\Http\Controllers\CompanyController');
    Route::get('/rate-upload', [App\Http\Controllers\RateController::class, 'index'])->name('rate-upload');
    Route::post('/rate-upload', [App\Http\Controllers\RateController::class, 'store'])->name('post-rate-upload');
    Route::get('/rate-table', [App\Http\Controllers\RateController::class, 'rateIndex'])->name('rate-table');
    Route::get('/rate-table-create', [App\Http\Controllers\RateController::class, 'create'])->name('rate-table.create');
    Route::post('/rate-table-post', [App\Http\Controllers\RateController::class, 'tableStore'])->name('rate-table.store');
    Route::delete('/rate-table-delete', [App\Http\Controllers\RateController::class, 'destroy'])->name('rate-table.delete');
    Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [App\Http\Controllers\HomeController::class, 'profileUpdate'])->name('profile-update');

});
