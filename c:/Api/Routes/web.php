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

Route::prefix('api/user')->group(function() {
	Route::post('login', 'CustomerApiController@login');
	Route::post('logout', 'CustomerApiController@logout');
	Route::post('resendOtp', 'CustomerApiController@resendOtp');
	Route::post('verifyOtp', 'CustomerApiController@verifyOtp');
	Route::post('register', 'CustomerApiController@register');
	Route::post('skipLogin', 'CustomerApiController@skipLogin');

	Route::post('switchAccountVendor', 'CustomerApiController@switchAccountVendor');

	//Seller Related Apis
	Route::post('getBusinessCategories', 'CustomerApiController@getBusinessCategories');
	Route::post('addBusinessDetails', 'CustomerApiController@addBusinessDetails');
	Route::post('getBusinessDetails', 'CustomerApiController@getBusinessDetails');
	Route::post('addBusinessLocation', 'CustomerApiController@addBusinessLocation');
	Route::post('getBusinessLocation', 'CustomerApiController@getBusinessLocation');
	Route::post('addBusinessImage', 'CustomerApiController@addBusinessImage');
	

	Route::post('dashboard', 'CustomerApiController@dashboard');

	// Route::post('mediaDashboard', 'CustomerApiController@mediaDashboard');
	// Route::post('contestDetail', 'CustomerApiController@contestDetail');
	// Route::post('conVideoDetail', 'CustomerApiController@conVideoDetail');
	// Route::post('viewAllVideos', 'CustomerApiController@viewAllVideos');
	
	//Customer Location related Apis
	Route::post('postCurrentLocation', 'CustomerApiController@postCurrentLocation');
	Route::post('postLocation', 'CustomerApiController@postLocation');

	//Address related Api's
	Route::post('checkAddress', 'CustomerApiController@checkAddress');
	Route::post('addAddress', 'CustomerApiController@addAddress');
	Route::post('editAddress', 'CustomerApiController@editAddress');
	Route::post('savedAddresses', 'CustomerApiController@savedAddresses');
	Route::post('deleteAddress', 'CustomerApiController@deleteAddress');

	Route::post('notifications', 'CustomerApiController@notifications');
	// Route::post('upvoteVideo', 'CustomerApiController@upvoteVideo');

	//Profile Related Apis
	Route::post('viewProfile', 'CustomerApiController@viewProfile');
	Route::post('editProfile', 'CustomerApiController@editProfile');
	Route::post('referralCode', 'CustomerApiController@referralCode');
	
	// Route::post('checkUpdate', 'CustomerApiController@checkUpdate');
	Route::post('getCountrys', 'CustomerApiController@getCountrys');
	Route::post('getStates', 'CustomerApiController@getStates');
	Route::post('getCities', 'CustomerApiController@getCities');
	Route::post('setCity', 'CustomerApiController@setCity');

	//Business Module
	// Route::post('directoryList', 'BusinessApiController@directoryList');
	// Route::post('bannerImageList', 'BusinessApiController@bannerImageList');
	Route::post('getCategories', 'BusinessApiController@getCategories');
	Route::post('getCategoryDetail', 'BusinessApiController@getCategoryDetail');
	Route::post('getSubcategories', 'BusinessApiController@getSubcategories');
	Route::post('shopList', 'BusinessApiController@shopList');
	Route::post('getStores', 'BusinessApiController@getStores');
	Route::post('getShopsByCategory', 'BusinessApiController@getShopsByCategory');
	Route::post('shopDetail', 'BusinessApiController@shopDetail');
	Route::post('getProductsSubcategory', 'BusinessApiController@getProductsSubcategory');
	Route::post('getCategoriesWithSubcategories', 'BusinessApiController@getCategoriesWithSubcategories');

	//Wishlist related Api's
	Route::post('wishlistShop', 'CustomerApiController@wishlistShop');
	Route::post('wishlistProduct', 'CustomerApiController@wishlistProduct');
	Route::post('getFavShops', 'BusinessApiController@getFavShops');
	Route::post('getFavProducts', 'BusinessApiController@getFavProducts');

	//Cart related Api's
	Route::post('addToCart', 'CustomerApiController@addToCart');
	Route::post('myCart', 'CustomerApiController@myCart');
	Route::post('getCartShortInfo', 'CustomerApiController@getCartShortInfo');
	Route::post('updateQuantityCart', 'CustomerApiController@updateQuantityCart');
	Route::post('removeItemCart', 'CustomerApiController@removeItemCart');
	Route::post('postRatingandReview', 'CustomerApiController@postRatingandReview');

	//check out of stock
	Route::post('checkStock', 'BusinessApiController@checkStock');

	//checkout Related Apis
	Route::post('orderSummary', 'BusinessApiController@orderSummary');
	Route::post('inStore', 'BusinessApiController@inStore');
	Route::post('getRazorPayOrderId', 'BusinessApiController@getRazorPayOrderId');
	Route::post('placeOrder', 'BusinessApiController@placeOrder');
	Route::post('paymentResponse', 'BusinessApiController@paymentResponse');
	Route::post('webhook', 'BusinessApiController@paymentVerification');
	Route::post('purchaseHistory', 'BusinessApiController@purchaseHistory');
	Route::post('orderDetails', 'BusinessApiController@orderDetails');
	Route::post('cancelOrder', 'BusinessApiController@cancelOrder');
	Route::post('reOrder', 'BusinessApiController@reOrder');

	//Referal and earn
	Route::post('pointHistory', 'BusinessApiController@pointHistory');

	//Dafy Features
	Route::post('buyAnything', 'BusinessApiController@buyAnything');
	Route::post('deliverAnything', 'BusinessApiController@deliverAnything');

	Route::post('search', 'BusinessApiController@search');
	Route::post('storeSearch', 'BusinessApiController@storeSearch');
	Route::post('getRecentSearch', 'BusinessApiController@getRecentSearch');
	Route::post('getTrendingSearch', 'BusinessApiController@getTrendingSearch');

	//Filter Related Apis
	Route::post('getFilterdata', 'BusinessApiController@getFilterdata');
	Route::post('postFilterdata', 'BusinessApiController@postFilterdata');

	//Product Related Apis
	Route::post('newArrivalItems', 'BusinessApiController@newArrivalItems');
	Route::post('hotDealItems', 'BusinessApiController@hotDealItems');
	Route::post('todaysHotDeal', 'BusinessApiController@todaysHotDeal');
	Route::post('offerDetail', 'BusinessApiController@offerDetail');
	Route::post('claimOffer', 'BusinessApiController@claimOffer');
	Route::post('productDetail', 'BusinessApiController@productDetail');
	Route::post('productDetails', 'BusinessApiController@productDetails');

	Route::post('postEnquiry', 'BusinessApiController@postEnquiry');
	Route::post('getExpectedPurchase', 'BusinessApiController@getExpectedPurchase');
	Route::post('getInvitationCode', 'CustomerApiController@getInvitationCode');
	
	//Common pages
	Route::post('/help', 'CustomerApiController@help');	
	Route::get('/aboutus', 'CustomerApiController@aboutUs');
	Route::get('/terms', 'CustomerApiController@terms');

	//invite
	Route::get('/invite', 'CustomerApiController@inviteUrl');

	//buyAnything
	Route::post('/buyanything_list', 'BusinessApiController@buyAnythingList');
	Route::post('/buyanything_detail', 'BusinessApiController@buyAnythingDetail');

	//deliver anything
	Route::post('/deliveranything_list', 'BusinessApiController@deliverAnythingList');
	Route::post('/deliver_anything_detail', 'BusinessApiController@deliverAnythingDetail');
});

Route::get('/apple-app-site-association', 'CustomerApiController@iosShare');
Route::get('/media', 'CustomerApiController@shareUrl');
Route::get('/contestant', 'CustomerApiController@shareUrl');
Route::get('/shop', 'CustomerApiController@shareUrl');