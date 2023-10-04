<?php

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

Route::prefix('api/deliveryapp')->group(function() {
    Route::post('/login', 'DeliveryAppController@login');
    Route::post('/resendOtp', 'DeliveryAppController@resendOtp');
    Route::post('/verifyOtp', 'DeliveryAppController@verifyOtp');
    Route::post('/getJobList', 'DeliveryAppController@getJobList');
    Route::post('/getJobList1', 'DeliveryAppController@getJobList1');
    Route::post('/orderDetails', 'DeliveryAppController@orderDetails');
    Route::post('/typeOrderDetails', 'DeliveryAppController@typeOrderDetails');
    Route::post('/startJob', 'DeliveryAppController@startJob');
    Route::post('/completeJob', 'DeliveryAppController@completeJob');
    Route::post('/getCompletedList', 'DeliveryAppController@getCompletedList');
    Route::post('/sendOtp', 'DeliveryAppController@sendOtp');
    Route::post('/confirmotp', 'DeliveryAppController@confirmOtp');
    Route::post('/logout', 'DeliveryAppController@logout');
});
