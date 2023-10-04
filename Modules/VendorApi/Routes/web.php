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

Route::prefix('api/vendor')->group(function() {
    Route::post('/neworders', 'VendorApiController@newOrders');
    Route::post('/accept_order', 'VendorApiController@acceptOrder');
    Route::post('/reject_order', 'VendorApiController@rejectOrder');
    Route::post('/myproducts', 'VendorApiController@myProducts');
    Route::post('/disabledProducts', 'VendorApiController@disabledProducts');
    Route::post('/search', 'VendorApiController@searchProduct');
    Route::post('/activate', 'VendorApiController@activateProduct');
    Route::post('/notifications', 'VendorApiController@notification');
    Route::post('/completedOrders', 'VendorApiController@completedOrders');
    Route::post('/acceptedOrders', 'VendorApiController@acceptedOrders');
    Route::post('/reviews', 'VendorApiController@reviews');
    Route::post('/getprofile', 'VendorApiController@getProfile');
    Route::post('/editProductPrice', 'VendorApiController@editProductPrice');
});
