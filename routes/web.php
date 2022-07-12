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
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('/call','App\Http\Controllers\CallController');
    Route::resource('/users','App\Http\Controllers\UserController');
    Route::post('getUsers','App\Http\Controllers\UserController@getUsers')->name('getUsers');
    // Route::get('autocomplete','App\Http\Controllers\UserController@autocomplete')->name('autocomplete');
    Route::resource('/crm','App\Http\Controllers\CRMController');
    Route::resource('/client','App\Http\Controllers\ClientController');
    Route::get('/getClient/{id}','App\Http\Controllers\CRMController@ImportClient')->name('getClient');
    Route::resource('/cron','App\Http\Controllers\CronJobController');
    Route::resource('/setting','App\Http\Controllers\SettingController');
    Route::resource('/company','App\Http\Controllers\CompanyController');
});
