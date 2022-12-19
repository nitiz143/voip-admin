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

    //Client Controller
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
    Route::post('vendor_rate/{id}/vendor_process_download', [ClientController::class, 'vendor_process_download'])->name('vendor_process_download');
    Route::get('history-detail/{id}', [ClientController::class, 'history_detail'])->name('history_detail');
    Route::get('vendor-history-detail/{id}', [ClientController::class, 'vendor_history_detail'])->name('vendor_history_detail');
    Route::get('/customer/{id}/history_export/xlsx', [App\Http\Controllers\ClientController::class, 'history_export_xlsx'])->name('history_export_xlsx');
    Route::get('/customer/{id}/history_export/csv', [App\Http\Controllers\ClientController::class, 'history_export_csv'])->name('history_export_csv');

    Route::get('/vendor/{id}/history_export/xlsx', [App\Http\Controllers\ClientController::class, 'vendor_history_export_xlsx'])->name('Vendor-history_export_xlsx');
    Route::get('/vendor/{id}/history_export/csv', [App\Http\Controllers\ClientController::class, 'vendor_history_export_csv'])->name('Vendor-history_export_csv');

    Route::get('/vendor_blocking/{id}/ajax_datagrid_blockbycountry', [App\Http\Controllers\ClientController::class, 'ajax_datagrid_blockbycountry'])->name('ajax_datagrid_blockbycountry');
    Route::get('/vendor_blocking/{id}/ajax_datagrid_blockbycode', [App\Http\Controllers\ClientController::class, 'ajax_datagrid_blockbycode'])->name('ajax_datagrid_blockbycode');
    Route::post('/block-unblock_country/{id}', [App\Http\Controllers\ClientController::class, 'block_unblock_by_country'])->name('block-unblock-by-country');


    Route::get('/getClient/{id}','App\Http\Controllers\CRMController@ImportClient')->name('getClient');
    Route::resource('/cron','App\Http\Controllers\CronJobController');
    Route::resource('/setting','App\Http\Controllers\SettingController');


    //Trunks
    Route::get('/trunks', [App\Http\Controllers\SettingController::class, 'trunkIndex'])->name('trunks.index');
    Route::post('/trunk/store', [App\Http\Controllers\SettingController::class, 'trunkStore'])->name('trunk.store');
    Route::get('/trunk/edit/{id}', [App\Http\Controllers\SettingController::class, 'trunkEdit'])->name('trunk.edit');
    Route::get('trunks/xlsx', [App\Http\Controllers\SettingController::class, 'trunks_xlsx'])->name('trunks_xlsx');
    Route::get('trunks/csv', [App\Http\Controllers\SettingController::class, 'trunks_csv'])->name('trunks_csv');

   
    Route::resource('/company','App\Http\Controllers\CompanyController');
    Route::get('/rate-upload', [App\Http\Controllers\RateController::class, 'index'])->name('rate-upload');
    Route::post('/rate-upload', [App\Http\Controllers\RateController::class, 'store'])->name('post-rate-upload');
    Route::POST('/rate_upload/getTrunk', [App\Http\Controllers\RateController::class, 'get_trunk'])->name('getTrunk');
    Route::get('/rate-table', [App\Http\Controllers\RateController::class, 'rateIndex'])->name('rate-table');
    Route::get('/rate-table-create', [App\Http\Controllers\RateController::class, 'create'])->name('rate-table.create');
    Route::post('/rate-table-post', [App\Http\Controllers\RateController::class, 'tableStore'])->name('rate-table.store');
    Route::delete('/rate-table-delete', [App\Http\Controllers\RateController::class, 'destroy'])->name('rate-table.delete');
    Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [App\Http\Controllers\HomeController::class, 'profileUpdate'])->name('profile-update');

});
