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

Route::group([
    'prefix' => 'admin',
],function() {
	
    Route::get('/', 'AdminController@index');
    Route::get('/login', 'AdminController@index');
    Route::post('/login','AdminController@login');
   // Route::get('/home', 'AdminController@home');
    Route::get('/logout','AdminController@logout');

    Route::group(['middleware' => 'admin_auth:admin'], function(){
	Route::get('/home', ['middleware' => 'permission:dashboard', 'uses' => 'AdminController@home']);
	Route::get('/event', ['middleware' => 'permission:event', 'uses' => 'AdminController@event']);
	Route::get('/events/list','AdminController@eventlist');
	Route::get('/events/add','AdminController@eventsadd');
	Route::post('/events/addevents','AdminController@addevents');
	Route::get('/events/edit/{id}','AdminController@editevents');
	Route::post('events/updateevents','AdminController@updateevents');
	Route::get('/events/delete/{id}','AdminController@delevents');
    Route::get('/participants','AdminController@participants');
    Route::get('participants/add','AdminController@participantsvideo');
	Route::post('/participants/addvideo','AdminController@addparticipantsvideo');
	Route::get('/participants/list','AdminController@participants_list');
	Route::get('/participants/edit/{id}','AdminController@participants_edit');
	Route::post('/events/updateparticipants','AdminController@updateparticipants');
	Route::get('/participants/delete/{id}','AdminController@deleteparticipants');
	Route::get('/changepassword','AdminController@changePassword');
	Route::post('/password/change','AdminController@passwordStore');

	//settings
	Route::get('/distance/settings', 'AdminController@otherSetting');
	Route::post('/other/settings', 'AdminController@storeSettings');
	Route::get('/settings', 'AdminController@settings');
	Route::post('/settings/appversion', 'AdminController@appversion');
	Route::post('/settings/referal', 'AdminController@referalStore');      

	Route::get('/notificationscount','AdminController@notificationscount');
	Route::get('/notifications','AdminController@notifications');
	Route::get('/updatenotify/{id}','AdminController@updatenotify');
	Route::get('/updatedelivernotify/{id}','AdminController@updatedelivernotify');              

	//Shop Category
	Route::get('/shop/category','AdminController@shopCategory');
	Route::get('/shop/categories/list', 'AdminController@shopCategoryList');
	Route::get('/shop/category/add', 'AdminController@shopCategoryAdd');
	Route::post('/shop/category/store', 'AdminController@shopCategoryStore');
	Route::get('/shop/category/edit/{id}', 'AdminController@shopCategoryEdit');
	Route::post('/shop/category/update', 'AdminController@shopCategoryUpdate');
	Route::get('/shop/category/export', 'AdminController@shopCategoryExport')->name('category.export');


	//Business Category
	Route::get('/category','AdminController@category');
	Route::get('/categories/list', 'AdminController@categorylist');
	Route::get('/category/add', 'AdminController@categoryadd');
	Route::post('/category/addcategory', 'AdminController@addcategory');
	Route::get('/category/getSubCategory', 'AdminController@getSubCategory')->name('category.getSubCategory');
	Route::post('/category/updatecategory', 'AdminController@updatecategory');
	Route::get('/category/edit/{id}', 'AdminController@editcategory');
	Route::get('/category/delete/{id}', 'AdminController@deleteCategory');
	
	Route::get('/category/attributes/{id}', 'AdminController@attributes');
	Route::get('/getAttributes/{id}', 'AdminController@getAttributes');
	Route::get('/category/attribute/add/{id}', 'AdminController@addAttributes');
	Route::post('/category/attribute/store', 'AdminController@storeAttribute');
	Route::get('/category/attribute/edit/{id}', 'AdminController@editAttribute');
	Route::post('/category/updateattribute', 'AdminController@updateAttribute');
	Route::get('/category/attribute/delete/{id}', 'AdminController@deleteAttribute');
	Route::get('/category/export', 'AdminController@categoryExport')->name('category.export');

	//To add services
	Route::get('/category/services/{id}', 'AdminController@services');
	Route::post('/category/service/store', 'AdminController@storeService');

	//Business Shop
	Route::get('/shop', 'ShopController@shop');
	Route::get('/shop/list', 'ShopController@shopList');
	Route::get('/shop/add', 'ShopController@shopAdd');
	Route::get('/shop/city/get-list', 'ShopController@getCityList')->name('admin.get.city.list');
	Route::post('/shop/store', 'ShopController@shopStore');
	Route::get('/shop/edit/{id}/{seller_id?}', 'ShopController@shopEdit');
	Route::post('/shop/update', 'ShopController@shopUpdate');
	Route::get('/shop/services/{id}', 'ShopController@shopServices');
	Route::post('/shop/service/store', 'ShopController@storeServices');
	Route::post('/shop/image/delete', 'ShopController@deleteImage')->name('admin.shop.image.delete');
	Route::post('/shop/getSellerCat', 'ShopController@getSellerCat');
	Route::get('/shop/export', 'ShopController@shopExport')->name('shop.export');

	//Add Products into shops
	Route::get('/shop/products/{id}', 'ShopController@shopProducts');
	Route::get('/shop/getProducts/{id}', 'ShopController@getProducts');
	Route::get('/shop/product/add/{id}', 'ShopController@addProducts');
	Route::get('/shop/product/export/{shop_id}', 'ShopController@shopProductsExport')->name('shopProduct.export');

	//Business Banner
	Route::get('/banner','BannerController@banner');
	Route::get('/banner/list', 'BannerController@bannerList');
	Route::get('/banner/add', 'BannerController@bannerAdd');
	Route::post('/banner/store', 'BannerController@bannerStore');
	Route::get('/banner/edit/{id}', 'BannerController@bannerEdit');
	Route::post('/banner/update', 'BannerController@bannerUpdate');
	Route::get('/banner/delete/{id}', 'BannerController@deleteBanner');
	Route::post('/banner/getproductList', 'BannerController@getProduct');
	Route::get('/banner/searchcode', 'BannerController@searchShopCode')->name('banner.shop.search');
	

	//Business Countrys
	Route::get('/country', ['middleware' => 'permission:country', 'uses' => 'CountryController@country']);
	Route::get('/country/list', 'CountryController@countryList');
	Route::get('/country/add', 'CountryController@countryAdd');
	Route::post('/country/store', 'CountryController@countryStore');
	Route::get('/country/edit/{id}', 'CountryController@countryEdit');
	Route::post('/country/update', 'CountryController@countryUpdate');
	Route::get('/country/delete/{id}', 'CountryController@deleteCountry');

	//Business States
	Route::get('/state','StateController@state');

	Route::get('/state/list', 'StateController@stateList');
	Route::get('/state/add', 'StateController@stateAdd');
	Route::post('/state/store', 'StateController@stateStore');
	Route::get('/state/edit/{id}', 'StateController@stateEdit');
	Route::post('/state/update', 'StateController@stateUpdate');
	// Route::get('/state/delete/{id}', 'StateController@deleteState');

	//Business Cities
	Route::get('/city','CityController@city');
	Route::get('/city/list', 'CityController@cityList');
	Route::get('/city/add', 'CityController@cityAdd');
	Route::post('/city/store', 'CityController@cityStore');
	Route::get('/city/edit/{id}', 'CityController@cityEdit');
	Route::post('/city/update', 'CityController@cityUpdate');
	Route::get('/city/delete/{id}', 'CityController@deleteCity');

	//Product Managment
	Route::get('/product','ProductController@product');
	Route::get('/product/list', 'ProductController@productList');
	Route::get('/product/add/{shop?}', 'ProductController@productAdd');
	Route::post('/product/store', 'ProductController@productStore');
	Route::get('/product/edit/{id}/{shop?}', 'ProductController@productEdit');
	Route::post('/product/update', 'ProductController@productUpdate');
	Route::post('/product/import', 'ProductController@import')->name('product_import');
	Route::post('/product/image/delete', 'ProductController@deleteImage')->name('product.image.delete');
	Route::get('/product/untracked/{shop_id}', 'ProductController@productUntracked')->name('product.untracked');
	Route::get('/product/untracklists/{shop_id}', 'ProductController@untrackProductList');
	Route::get('/product/untrackexport/{shop_id}', 'ProductController@untrackProductExport')->name('product.untracked.export');
	Route::get('/product/deleteUntracked/{prod_id}', 'ProductController@untrackProductDelete');
	Route::post('/product/getVarients', 'ProductController@getVarients');
	Route::get('/product/attribute/edit/{prod_id}', 'ProductController@editProductAttribute');
	Route::post('/product/updateattribute', 'ProductController@updateAttribute');
	Route::post('/product/setRadioSession', 'ProductController@setRadioSession');
	Route::post('/product/changeProductStatus', 'ProductController@changeProductStatus');
	Route::get('/product/editPending/{id}/{shop?}', 'ProductController@productPendingEdit');
	Route::post('/product/changeProductApproveStatus', 'ProductController@changeProductApproveStatus');
	Route::get('/product/status/export/{status}', 'ProductController@productExport')->name('product.status.export');

	Route::get('/product/variation/{id}', 'VariationController@variation');
	Route::get('/variation/list/{id}', 'VariationController@variationList');
	Route::get('/variation/add/{id}', 'VariationController@variationAdd');
	Route::post('/variation/store', 'VariationController@variationStore');
	Route::get('/variation/edit/{id}', 'VariationController@variationEdit');
	Route::post('/variation/update', 'VariationController@variationUpdate');


	//Business Brands
	Route::get('/brand','BrandController@brand');
	Route::get('/brand/list', 'BrandController@brandList');
	Route::get('/brand/add', 'BrandController@brandAdd');
	Route::post('/brand/store', 'BrandController@brandStore');
	Route::get('/brand/edit/{id}', 'BrandController@brandEdit');
	Route::post('/brand/update', 'BrandController@brandUpdate');

	//Measurement Units
	Route::get('/unit','UnitController@unit');
	Route::get('/unit/list', 'UnitController@unitList');
	Route::get('/unit/add', 'UnitController@unitAdd');
	Route::post('/unit/store', 'UnitController@unitStore');
	Route::get('/unit/edit/{id}', 'UnitController@unitEdit');
	Route::post('/unit/update', 'UnitController@unitUpdate');

	//seller management
	Route::get('/seller','UserController@seller');
	Route::get('/seller/list', 'UserController@sellerList');
	Route::get('/seller/add', 'UserController@sellerAdd');
	Route::post('/seller/store', 'UserController@sellerStore');
	Route::get('/seller/edit/{id}', 'UserController@sellerEdit');
	Route::post('/seller/update', 'UserController@sellerUpdate');
	Route::get('/seller/delete/{id}', 'UserController@deleteSeller');


	//customer management
	Route::get('/customer','UserController@customer');
	Route::get('/customer/list', 'UserController@customerList');
	Route::get('/customer/add', 'UserController@customerAdd');
	Route::post('/customer/store', 'UserController@customerStore');
	Route::get('/customer/edit/{id}', 'UserController@customerEdit');
	Route::post('/customer/update', 'UserController@customerUpdate');
	Route::get('/customer/delete/{id}', 'UserController@deleteCustomer');
	

	//export user[customer,seller]
	Route::get('/user/export/{user_type}','UserController@exportUser')->name('user.export');

	//role management
	Route::get('/role','RoleController@role');
	Route::get('/role/list', 'RoleController@roleList');
	Route::get('/role/add', 'RoleController@roleAdd');
	Route::post('/role/store', 'RoleController@roleStore');
	Route::get('/role/edit/{id}', 'RoleController@roleEdit');
	Route::post('/role/update', 'RoleController@roleUpdate');

	//offer management
	Route::get('/offer','OfferController@offer');
	Route::get('/offer/list', 'OfferController@offerList');
	Route::get('/offer/add', 'OfferController@offerAdd');
	Route::post('/offer/store', 'OfferController@offerStore');
	Route::get('/offer/edit/{id}', 'OfferController@offerEdit');
	Route::post('/offer/update', 'OfferController@offerUpdate');
	Route::post('/offer/getproducts', 'OfferController@getProduct');
	Route::get('/offer/searchcode', 'OfferController@searchShopCode')->name('offer.shop.search');
	Route::get('/offer/delete/{id}', 'OfferController@deleteOffer');

	//Offline Voucher
	Route::get('/offline_vouchers','OfferController@offlineVouchers');
	Route::get('/offline/vouchers','OfferController@offlineVoucherList');
	Route::get('/offline/searchvoucher','OfferController@searchVoucher');
	Route::get('/offline/export_voucher','OfferController@exportVoucher');

	//order management
	Route::get('/order','OrderController@order');
	Route::get('/order/list', 'OrderController@orderList');
	Route::get('/order/add', 'OrderController@orderAdd');
	Route::post('/order/store', 'OrderController@orderStore');
	Route::get('/order/edit/{id}', 'OrderController@orderEdit');
	Route::post('/order/update', 'OrderController@orderUpdate');
	Route::get('/order/view/{id}', 'OrderController@orderView');
	Route::get('/order/searchorder','OrderController@searchOrder');
	Route::get('/order/invoice_download/{order_id}','OrderController@invoiceDownload');
	Route::post('/order/getOrderStatus','OrderController@changeOrderStatus');
	Route::get('/order/export/{status}', 'OrderController@orderExport')->name('order.export');
	Route::get('/export_order_report', 'OrderController@orderExport1');
	Route::get('/order/assignlists/', 'OrderController@orderGetDriver');
	Route::get('order/assignDetails/{id}', 'OrderController@assignDetails');
	Route::post('/order/storeDriverOrder', 'OrderController@storeDriverOrder');
	Route::get('/updateordernotify/{id}','AdminController@updateordernotify');


	//Enquiry
	Route::get('/enquiry','AdminController@enquiry');
	Route::get('/enquiry/list', 'AdminController@enquiryList');
	Route::get('/enquiry/searchcode', 'AdminController@searchShopCode')->name('enquiry.shop.search');
	Route::post('/sent/enquiry','AdminController@sentEnquiryPush');
	Route::get('/get/enquiry/{id}','AdminController@getEnquiryPush')->name('enquiry.notified');;

	//quick purcahse buy anything
	Route::get('/buy_anything','AdminController@buyAnything');
	Route::get('/buy/list', 'AdminController@buyAnythingLists');
	Route::get('/buy/assign/{id}', 'AdminController@buyAssign');
	Route::post('/buyanything/changeOrderStatus','AdminController@buychangeOrderStatus');

	//quick purchase deliver anything
	Route::get('/del_anything','AdminController@deliverAnything');
	Route::get('/deliver_anything/list', 'AdminController@deliverAnythingLists');
	Route::get('/deliver/assign/{id}', 'AdminController@deliverAssign');
	Route::post('/deliveranything/changeOrderStatus','AdminController@delchangeOrderStatus');
	

	//admin users
	Route::get('/additional_user/{role_slug}','UserController@additionalUser');
	Route::get('/additonaluser_anything/list/{role_slug}','UserController@additionalUserList');
	Route::get('/add_additional_user/{role_slug}', 'UserController@additionalUserAdd');
	Route::post('/additonaluser/store','UserController@additionalUserStore');
	Route::get('/additonaluser/edit/{id}','UserController@additionalUserEdit');
	Route::post('/additonaluser/update','UserController@additonalUserUpdate');

	//driver users
	Route::get('/driver','UserController@driver');
	Route::get('/driver/list', 'UserController@driverList');
	Route::get('/driver/add', 'UserController@driverAdd');
	Route::post('/driver/store','UserController@driverStore');
	Route::get('/driver/edit/{id}','UserController@driverEdit');
	Route::post('/driver/update','UserController@driverUpdate');

	//Report
	Route::get('/vendor_payment_report','AdminController@vendorPaymentReport');
	Route::get('/vendor_payment_report/list', 'AdminController@vendorPaymentReportList');
	Route::get('/vendor_payment/searchshop_report','AdminController@searchShopReport');
	Route::get('/vendor_payment/view/{id}', 'OrderController@orderView')->name('search.report');
	Route::get('/vendor_payment/view_report/{id}', 'AdminController@viewReport');
	Route::post('/vendor_payment/export', 'AdminController@viewExport');

	//Report
	Route::get('/vendor_commission_report','AdminController@vendorCommissionReport');
	Route::get('/vendor_commission_report/list', 'AdminController@CommissionReportList');
	Route::post('/change_commission_status','AdminController@changeAdminCommissionStatus');
	Route::get('/vendor_commission/searchshop_report','AdminController@searchCommissionShopReport');
	// Route::get('/vendor_payment/view/{id}', 'OrderController@orderView')->name('search.report');
	// Route::get('/vendor_payment/view_report/{id}', 'AdminController@viewReport');
	Route::post('/vendor_commission/export', 'AdminController@viewAdminCommissionExport');


	// Admin notification
    Route::get('/notification','AdminController@notification');
    Route::get('/notification/list','AdminController@notificationlist');
    Route::get('/notification/add','AdminController@addnotification');
    Route::post('/notification/store','AdminController@storenotification');
	
});
});


Route::group([
	'prefix' => 'seller',
],function() {

	Route::get('/', 'AdminController@index');
    Route::get('/login', 'AdminController@index');
    Route::post('/login','AdminController@login');
   // Route::get('/home', 'AdminController@home');
    Route::get('/logout','AdminController@logout');


	Route::group(['middleware' => 'admin_auth:seller'], function(){
		Route::get('/home', ['middleware' => 'permission:dashboard', 'uses' => 'AdminController@home']);

		//Product
		Route::get('/shop/products/{id}', 'ShopController@shopProducts');

		//Order
		Route::get('/order','OrderController@order');
		Route::get('/order/list', 'OrderController@orderList');

		//password
		Route::get('/changepassword','AdminController@changePassword');
		Route::post('/password/change','AdminController@passwordStore');

		//report
		Route::get('/vendor_payment_report','AdminController@vendorPaymentReport');
		Route::get('/vendor_payment_report/list', 'AdminController@vendorPaymentReportList');
		Route::get('/vendor_payment/searchshop_report','AdminController@searchShopReport');
		Route::get('/vendor_payment/view/{id}', 'OrderController@orderView')->name('search.report');
		Route::get('/vendor_payment/view_report/{id}', 'AdminController@viewReport');
		Route::post('/vendor_payment/export', 'AdminController@viewExport');

		//report
		Route::get('/vendor_report','AdminController@vendorReport');
		Route::get('/vendor_commission_report/list', 'AdminController@vendorCommissionReportList');
		// Route::post('/change_commission_status','AdminController@changeCommissionStatus');
		Route::get('/search_shop_commission_report','AdminController@searchShopCommissionReport');
		// Route::get('/vendor_payment/view/{id}', 'OrderController@orderView')->name('search.report');
		// Route::get('/vendor_payment/view_report/{id}', 'AdminController@viewReport');
		Route::post('/vendor_commission/export', 'AdminController@vendorCommissionExport');

});

});
Route::get('/', 'AdminController@home_page');
Route::get('/return_policy', 'AdminController@privacyPolicy');
Route::get('/terms', 'AdminController@termsConditions');
Route::get('/sendBulkNotification','AdminController@sendBulkNotification');
