<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Modules\Admin\Entities\Offer;
use Modules\Product\Entities\OfferProduct;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ClaimedOffer;
use Modules\Product\Entities\ProductRating;
use Modules\Admin\Entities\Settings;
use Modules\Admin\Entities\Enquiry;
use Modules\Admin\Entities\BuyAnything;
use Modules\Admin\Entities\BuyAddress;
use Modules\Admin\Entities\DeliverAnything;
use Modules\Admin\Entities\DeliverAddress;
use Modules\Admin\Entities\Notification;
use Modules\Admin\Entities\NotificationCategory;
use Modules\Users\Entities\User;
use Modules\Users\Entities\Cart;
use Modules\Users\Entities\Wishlist;
use Modules\Users\Entities\UserAddress;
use Modules\Users\Entities\FavouriteShop;
use Modules\Users\Entities\RecentSearch;
use Modules\Users\Entities\TrendKeyword;
use Modules\Users\Entities\PointHistory;
use Modules\Category\Entities\Module;
use Modules\Shop\Entities\Category;
use Modules\Shop\Entities\ShopCategory;
use Modules\Category\Entities\BusinessCategory;
use Modules\Category\Entities\BusinessCategoryField;
use Modules\Shop\Entities\BusinessCategoryShop;
use Modules\Shop\Entities\BusinessShop;
use Modules\Shop\Entities\ShopImage;
use Modules\Category\Entities\Banner;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Order\Entities\Status;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderProduct;
use Modules\Users\Entities\UserDevice;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\OrderAddress;
use Modules\Order\Entities\Transaction;
use Modules\Admin\Entities\SellerOrderData;
use Carbon\Carbon;
use DB;
use DateTime;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use SiteHelper;
use Mail;
use PDF;
use Log;

class BusinessApiController extends Controller
{
    
    //To list all directory list
    public function directoryList(Request $request)
    {
        $rules = array(
            'userid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->userid, 'is_active' => 1])->first();

            if($user) {
                $data = (object)[];
                $directories = array();

                $modules = Module::where('is_active', 1)->get();

                foreach ($modules as $key => $value) {
                    $directories[] = array(
                            'directoryName' => $value->name,
                            'directoryid' => $value->id,
                            'directoryImage' => $value->image == null ? null : asset('storage/app').'/'.$value->image
                        );
                }

                $data = array(
                        'directories' => $directories
                    );
                
                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To list out all banner images
    public function bannerImageList(Request $request)
    {
        $rules = array(
            'userid' => 'required|integer',
            // 'directoryid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->userid, 'is_active' => 1])->first();

            if($user) {
                // $module = Module::where(['is_active' => 1, 'id' => $request->directoryid])->first();

                // if($module) {
                    $banner = array();
                    $data = (object)[];

                    $banners = Banner::whereDate('valid_from', '<=', Carbon::now())->whereDate('valid_to', '>', Carbon::now())->where([/*'module_id' => $request->directoryid, */'is_active' => 1])->get();

                    foreach ($banners as $key => $value) {
                        $banner[] = $value->image == null ? null : asset('storage/app').'/'.$value->image;
                    }

                    $data = array(
                            'bannerImages' => $banner
                        );
                    
                    $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );
                // } else {
                //  $res = array(
                //         'errorcode' => 1,
                //         'data' => (object)[],
                //         'message' => "Directory not exist!"
                //     );
                // }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
            
        }
        return response()->json($res);

    }

    //To list out all business categories
    public function getCategories(Request $request)
    {
        $rules = array(
            // 'userid' => 'required|integer',
            // 'directoryid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $cat = array();

            // $categories = BusinessCategory::where(['parent_id' => 0, 'is_active' => 1])->withTrashed()->orderBy('order', 'ASC')->first();
            
            $categories = Category::where(['is_active' => 1])->orderBy('order', 'ASC')->get();

            foreach ($categories as $key => $value) {
                
                $cat[] = array(
                    'cat_id' => $value->id,
                    'cat_title' => $value->name,
                    'cat_icon' => $value->icon == null ? null : asset('storage/app').'/'.$value->icon
                );
            }

            $res = array(
                'errorcode' => 0,
                'data' => $cat,
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }

    //To list out all business sub-categories of category
    public function getCategoryDetail(Request $request)
    {
        $rules = array(
            'shop_id' => 'required|integer',
            'category_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $cat = array();
            $category = BusinessCategory::where(['id' => $request->category_id, 'is_active' => 1])->first();

            if($category) {
                $shop_categories = BusinessCategoryShop::where(['shop_id' => $request->shop_id, 'main_category_id' => $request->category_id])->get();

                foreach ($shop_categories as $key => $shop_category) {
                    $sub_cat = BusinessCategory::where(['id' => $shop_category->category_id, 'is_active' => 1])->first();

                    if($sub_cat) {
                        $cat[] = array(
                                'subcategory_id' => $sub_cat->id,
                                'subcategory_title' => $sub_cat->name,
                                'subcategory_image' => $sub_cat->image == null ? null : asset('storage/app').'/'.$sub_cat->image,
                                'view_type' => $shop_category->view_type,
                                'order' => $sub_cat->order
                            );
                    }
                }

                $cat = collect($cat)->sortBy('order')->toArray();
                $cat = array_values($cat);

                $res = array(
                    'errorcode' => 0,
                    'data' => $cat,
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Category not found!"
                );
            }
        }
        return response()->json($res);
    }

    //To list out all business sub categories
    public function getSubcategories(Request $request)
    {
        $rules = array(
            'cat_id' => 'required|integer',
            // 'directoryid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $category = BusinessCategory::where(['id' => $request->cat_id, 'parent_id' => 0, 'is_active' => 1])->first();

            if($category) {
                $sub_cat = array();

                $sub_categories = BusinessCategory::where(['parent_id' => $request->cat_id, 'is_active' => 1])->orderBy('order', 'ASC')->get();

                foreach ($sub_categories as $key => $value) {
                    
                    $sub_cat[] = array(
                        'subcat_id' => $value->id,
                        'subcat_title' => $value->name,
                        'subcat_icon' => $value->image == null ? null : asset('storage/app').'/'.$value->image
                    );
                }

                $res = array(
                    'errorcode' => 0,
                    'data' => $sub_cat,
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Category not found!"
                );
            }

        }
        return response()->json($res);
    }

    //To list out all shops from business
    public function shopList(Request $request)
    {
        $rules = array(
            // 'userid' => 'required|integer',
            // 'subcategoryid' => 'required|integer',
            // 'batchSize' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $data = array();
            $image = null;
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            $distance = Settings::where(['slug' => 'min_distance'])->first();

            $shops = BusinessCategoryShop::select('shop_id')->get()->toArray();
            
            $shop_value = BusinessShop::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                    ->having('distance', '<', $distance->value)
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->whereHas('products')
                    // ->where('id', $shop->shop_id)
                    ->where('is_active', 1)
                    ->whereIn('id', $shops)
                    // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                    ->orderBy('distance')
                    ->get();

            foreach ($shop_value as $key => $shop) {
                
                $image = $shop->image == null ? null : asset('storage/app').'/'.$shop->image;

                $data[] = array(
                    'shop_id' => $shop->id,
                    'shop_title' => $shop->name,
                    'shop_image' => $image,
                    'shop_location' => $shop->location,
                    'categories' => $this->getShopCategories($shop->id)
                );
            }

            $data = array_unique($data, SORT_REGULAR);
            $data = app('Modules\Api\Http\Controllers\CustomerApiController')->paginate($data, $request->batchSize);

            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }

    //To list out all shops from business without checking location
    public function getStores(Request $request)
    {   
        // $invoice_data = $this->generateInvoice(393);

        // dd($invoice_data);
        $rules = array(
            // 'userid' => 'required|integer',
            // 'subcategoryid' => 'required|integer',
            // 'batchSize' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $data = array();
            $image = null;

            $shops = BusinessCategoryShop::select('shop_id')->get()->toArray();
            
            $shop_value = BusinessShop::where(['is_active' => 1])->whereHas('products')->orderBy('name')->get();

            foreach ($shop_value as $key => $shop) {
                
                $image = $shop->image == null ? null : asset('storage/app').'/'.$shop->image;

                $data[] = array(
                    'shop_id' => $shop->id,
                    'shop_title' => $shop->name,
                    'shop_image' => $image,
                    'shop_location' => $shop->location,
                    'categories' => $this->getShopCategories($shop->id)
                );
            }

            $data = array_unique($data, SORT_REGULAR);
            $data = app('Modules\Api\Http\Controllers\CustomerApiController')->paginate($data, $request->batchSize);

            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }


    //To list out all shops by category
    public function getShopsByCategory(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'cat_id' => 'required|integer',
            'batchSize' => 'required|integer',
            'latitude' => 'required',
            'longitude' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {

                $data = array();
                $image = null;
                $latitude = $request->latitude;
                $longitude = $request->longitude;

                $distance = Settings::where(['slug' => 'min_distance'])->first();

                // $shops = BusinessCategoryShop::where(['main_category_id' => $request->cat_id])->get()->pluck('shop_id')->toArray();

                $shops = ShopCategory::where(['shop_category_id' => $request->cat_id])->get()->pluck('shop_id')->toArray();
                
                $shop_value = BusinessShop::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                        ->having('distance', '<', $distance->value)
                        ->orWhere('name', 'LIKE', '%' . $request->search . '%')
                        ->whereHas('products')
                        // ->where('id', $shop->shop_id)
                        ->where('is_active', 1)
                        ->whereIn('id', $shops)
                        // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                        ->orderBy('distance')
                        ->get();

                foreach ($shop_value as $key => $shop) {
                    
                    $image = $shop->image == null ? null : asset('storage/app').'/'.$shop->image;

                    $data[] = array(
                        'shop_id' => $shop->id,
                        'shop_title' => $shop->name,
                        'shop_image' => $image,
                        'shop_location' => $shop->location,
                        'categories' => $this->getShopCategories($shop->id)
                    );
                }

                $data = array_unique($data, SORT_REGULAR);
                $data = app('Modules\Api\Http\Controllers\CustomerApiController')->paginate($data, $request->batchSize);

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }


    //To get detail of a shop
    public function shopDetail(Request $request)
    {
        $rules = array(
            // 'user_id' => 'required|integer',
            'shop_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $shop = BusinessShop::where(['id' => $request->shop_id, 'is_active' => 1])->first();

            if($shop) {
                $isWishlist = false;
                $phone_no = array();
                $category = array();
                $email = array();
                $website = array();
                $location = '';
                $available_offers = array();

                // $address = preg_replace("/,+/", ",", $shop->address.','.$city.','.$state.','.$country.','.$pincode);
                // $address = trim($address, ",");

                //New Code

                $shop_icon = $shop->image == null ? null : asset('storage/app').'/'.$shop->image;

                if($shop->phone_no != null)
                    $phone_no = explode(',', $shop->phone_no);

                if($shop->website != null)
                    $website = explode(',', $shop->website);

                $categories = BusinessCategoryShop::where(['shop_id' => $shop->id])->get()->unique('main_category_id');
              
                foreach ($categories as $key => $cat) {

                    if($cat->parentCategoryData) {
                        
                        $exist_category = BusinessCategory::where(['id' => $cat->main_category_id])->first();

                        if($cat->parentCategoryData->is_active == 1 && $exist_category)
                        {
                            $category[] = array(
                                'cat_id' => $cat->main_category_id,
                                'cat_title' => $cat->parentCategoryData->name,
                                'cat_icon' => $cat->parentCategoryData->image == null ? null : asset('storage/app').'/'.$cat->parentCategoryData->image,
                                'order' => $cat->parentCategoryData->order
                            );
                        }
                    }
                }

                $category = collect($category)->sortBy('order')->toArray();
                $category = array_values($category);

               // $category = array_unique($category,SORT_REGULAR);
                $offer_ids = array();

                $offer_shops = OfferProduct::where(['shop_id' => $request->shop_id])->get();

                foreach ($offer_shops as $key => $offer_shop) {

                    $offer = Offer::where('image', '!=', null)->whereDate('valid_from', '<=', Carbon::now())->whereDate('valid_to', '>', Carbon::now())->where(['id' => $offer_shop->offer_id, 'status' => 1])->first();

                    if ($offer && !in_array($offer->id, $offer_ids)) {
                        
                        $available_offers[] = array(
                            'offer_id' => $offer->id,
                            'offer_image' => $offer->image == null ? null : asset('storage/app').'/'.$offer->image
                        );

                        $offer_ids[] = $offer->id;
                    }
                }

                $wishlist = Wishlist::where(['user_id' => $request->user_id, 'shop_id' => $request->shop_id])->first();

                if($wishlist)
                    $isWishlist = true;
                
                $data = array(
                        'shop_id' => $request->shop_id,
                        'shop_name' => $shop->name,
                        'shop_icon' => $shop_icon,
                        'shop_location' => $shop->location,
                        'contact_number' => $shop->phone_no,
                        'website_link' => $shop->website,
                        'aboutus_url' => URL(''),
                        'about_us' => $shop->description,
                        'categories' => $category,
                        'available_offers' => $available_offers,
                        'isWishlist' => $isWishlist
                    );

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Shop not exist!"
                );
            }

        }
        return response()->json($res);
    }


    //To get products from sub caregory
    public function getProductsSubcategory(Request $request)
    {
        $rules = array(
            'subcategory_id' => 'required|integer',
            'shop_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

            return response()->json($res);

        } else {

            $category = BusinessCategory::where(['id' => $request->subcategory_id, 'is_active' => 1])->first();

            if(!$category) {
                
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Category not exist!"
                );

                return response()->json($res);
            }

            $shop = BusinessShop::where(['id' => $request->shop_id, 'is_active' => 1])->first();

            if($shop) {
                $data = array();
                $view_type = 0;
                $new_price = 0;
                $percent_off = null;
                $cart_count = 0;

                $product_categories = ProductCategory::where(['category_id' => $request->subcategory_id, 'shop_id' => $request->shop_id])->get();

                foreach ($product_categories as $key => $product_category) {

                    $offers = Offer::whereDate('valid_from', '<=', Carbon::now())->whereDate('valid_to', '>', Carbon::now())->where(['status' => 1])->get()->pluck('id')->toArray();

                    // foreach ($offers as $key => $offer) {

                        if($product_category->productData) {
                            
                            if($product_category->productData->type != 1 && $product_category->productData->is_active == 1 && $product_category->productData->is_approved == 1) {

                            if($product_category->productData->type == 2) {
                                $product = Product::where(['parent_id' => $product_category->product_id, 'is_active' => 1])/*->where('stock', '!=', 0)*/->first();
                            } else {
                                $product = Product::where(['id' => $product_category->product_id, 'is_active' => 1])/*->where('stock', '!=', 0)*/->first();
                            }
                            
                            if($product) {
                                $product_data = $this->offerPrice($product->id);
                                
                                if($product_data['new_price'] != null) {
                                    $new_price = number_format($product_data['new_price'], 2);
                                    $percent_off = $product_data['percent_off'].'%';
                                } else {
                                    $new_price = null;
                                    $percent_off = null;
                                }

                                $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $product->id])->first();

                                if($wishlist)
                                    $isWishlist = true;
                                else
                                    $isWishlist = false;

                                $shop_category = BusinessCategoryShop::where(['category_id' => $request->subcategory_id, 'shop_id' => $request->shop_id])->first();

                                if($shop_category)
                                    $view_type = $shop_category->view_type;
                                
                                if($product_category->productData->shop)
                                    $seller_info = $product_category->productData->shop->name;
                                else
                                    $seller_info = null;

                                    if($product_category->productData->is_active == 1)
                                    {   
                                        $cart_count = Cart::where(['user_id' => $request->user_id, 'product_id' => $product->id])->sum('quantity');

                                        $data[] = array(
                                            'product_id' => $product->id,
                                            'product_name' => ucwords($product_category->productData->name),
                                            'product_brand' => $product->brand->name ?? '',
                                            'stock' => $product->stock,
                                            'product_shortdescription' => $product_category->productData->description,
                                            'seller_info' => $seller_info,
                                            'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                                            'new_price' =>  $new_price,
                                            'old_price' => number_format($product->price, 2),
                                            'percent_off' => $percent_off,
                                            'isWishlist' => $isWishlist,
                                            'viewtype' => $view_type,
                                            'cart_count' => (int)$cart_count
                                        );
                                    }
                                }
                            }
                        }
                        
                    // }   
                    
                }

                $data = collect($data)->sortBy('product_name')->toArray();
                $data = array_values($data);
                $data = array_unique($data, SORT_REGULAR);
                

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );

                return response()->json($res);
            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Shop not exist!"
                );

                return response()->json($res);
            }

        }
        return response()->json($res);
    }

    //To list out all business categories with subcategories
    public function getCategoriesWithSubcategories(Request $request)
    {
        $rules = array(
            // 'userid' => 'required|integer',
            // 'directoryid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $cat = array();

            $categories = BusinessCategory::where(['parent_id' => 0, 'is_active' => 1])->withTrashed()->orderBy('order', 'ASC')->get();

            foreach ($categories as $key => $value) {
                $subcategories = array();
                $each_sub_categories = $value->childrens;

                foreach ($each_sub_categories as $key => $each_sub_category) {

                    if($each_sub_category->is_active == 1) {
                        $subcategories[] = array(
                            'subcat_id' => $each_sub_category->id,
                            'subcat_title' => $each_sub_category->name,
                            'subcat_icon' => $each_sub_category->image == null ? null : asset('storage/app').'/'.$each_sub_category->image,
                        );
                    }
                }
                
                $cat[] = array(
                    'cat_id' => $value->id,
                    'cat_title' => $value->name,
                    'cat_icon' => $value->image == null ? null : asset('storage/app').'/'.$value->image,
                    'subcategories' => $subcategories
                );
            }

            $res = array(
                'errorcode' => 0,
                'data' => $cat,
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }

    //To get products based on offers
    public function hotDealItems(Request $request)
    {
        $rules = array(
            // 'userid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $offer_products = array();
            $offer_shop_products = array();
            $products = array();
            $data = array();
            $seller_info = null;

            $offer_products = OfferProduct::whereHas('offerData', function($q) {
                $q->whereDate('valid_from', '<=', Carbon::now());
                $q->whereDate('valid_to', '>', Carbon::now());
                $q->where('status', 1);
            })->where('type', 0)->get()->pluck('product_id')->toArray();

            $offer_shop_products = Product::whereHas('offerShops', function($q) {
                $q->where('type', 1);
                $q->whereHas('offerData', function($query) {
                    $query->whereDate('valid_from', '<=', Carbon::now());
                    $query->whereDate('valid_to', '>', Carbon::now());
                    $query->where('status', 1);
                });
            })->get()->pluck('id')->toArray();

            // dump($offer_products);
            // dd($offer_shop_products);

            if(count($offer_products) > 0 || count($offer_shop_products)) {
                $products = array_merge($offer_products, $offer_shop_products);
                $products = array_unique($products, SORT_REGULAR);
            }
            
            
            foreach ($products as $key => $pro) {
                
                $product = Product::where(['id' => $pro, 'is_active' => 1, 'is_approved' => 1])->where('stock', '!=', 0)->first();

                if(optional(optional($product)->shop)->sellerInfo)
                    $seller_info = optional($product->shop)->name;

                $product_data = $this->offerPrice($pro);

                if($product_data['new_price'] != null) {
                    $new_price = number_format($product_data['new_price'], 2);
                    $percent_off = $product_data['percent_off'].'%';
                } else {
                    $new_price = null;
                    $percent_off = null;
                }

                $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $pro])->first();

                if($wishlist)
                    $isWishlist = true;
                else
                    $isWishlist = false;

                if($product) {
                    $data[] = array(
                        'product_id' => $pro,
                        'product_name' => $product->name,
                        'product_brand' => $product->brand->name ?? '',
                        'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                        'seller_info' => $seller_info,
                        'product_description' => $product->description,
                        'new_price' => $new_price,
                        'old_price' => number_format($product->price, 2),
                        'percent_off' => $percent_off,
                        'isWishlist' => $isWishlist
                    ); 
                }
            }

            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }

    //To get todays hot deals based on offers
    public function todaysHotDeal(Request $request)
    {
        $rules = array(
            // 'userid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $shops = array();

            $offer_shops = OfferProduct::with('shopData')->whereHas('shopData', function($q) {
                $q->where('is_active', 1);
            })->whereHas('offerData', function($q) {
                $q->whereDate('valid_from', '<=', Carbon::now());
                $q->whereDate('valid_to', '>=', Carbon::now());
                $q->where('status', 1);
                $q->where('image', '!=', null);
            })->get()->unique('offer_id');//->where('type', 1)
           
            foreach ($offer_shops as $key => $offer_shop) {
                
                $date = new DateTime($offer_shop->offerData->valid_to);
                $offer_date = $date->format('M').' '.$date->format('d').' '.$date->format('Y');
                
                $shop_icon = $offer_shop->offerData->shop->image == null ? null : asset('storage/app').'/'.$offer_shop->offerData->shop->image;
                
                if($offer_shop->offerData->shop->sellerInfo)
                    $seller_info = $offer_shop->offerData->shop->name;

                $percent_off = $offer_shop->offerData->discount_type == 1 ? $offer_shop->offerData->discount_value.' %' : 'Rs '.$offer_shop->offerData->discount_value;

                $shops[] = array(
                    'offer_id' => $offer_shop->offer_id,
                    'offer_date' => $offer_date,
                    'image' => $offer_shop->offerData->image == null ? null : asset('storage/app').'/'.$offer_shop->offerData->image,
                    'shop_icon' => $shop_icon,
                    'shop_name' => $offer_shop->offerData->shop->name,
                    'percent_off' => $percent_off,
                    'offer_title' => $offer_shop->offerData->title,
                    'offer_description' => $offer_shop->offerData->description,
                    'seller_info' => $seller_info
                );
            }
            
            //$shops = array_unique($shops, SORT_REGULAR);

            $res = array(
                'errorcode' => 0,
                'data' => $shops,
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }


    //To get offer detail page
    public function offerDetail(Request $request)
    {
        $rules = array(
            'offer_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            // $data = array();

            $offer = Offer::where(['id' => $request->offer_id, 'status' => 1])->first();

            if ($offer) {

                $product_exist = Product::where(['seller_id' => $offer->seller_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '!=', 0)->where('type', '!=', 2)->first();

                if($product_exist != null)
                    $shop_available = true;
                else
                    $shop_available = false;

                $date = new DateTime($offer->valid_to);
                $offer_valid_date = $date->format('M').' '.$date->format('d').' '.$date->format('Y');

                $shop_icon = null;

                if($offer->shop) {

                    // if(count($offer->shop->images) > 0) {
                        $shop_icon = $offer->shop->image == null ? null : asset('storage/app').'/'.$offer->shop->image;
                    // }
                }

                if($offer->shop->sellerInfo)
                    $seller_info = $offer->shop->name;

                $percent_off = $offer->discount_type == 1 ? ($offer->discount_value).' %' : 'Flat '.$offer->discount_value;

                $data = array(
                    'offer_id' => $offer->id,
                    'offer_valid_date' => $offer_valid_date,
                    'image' => $offer->image == null ? null : asset('storage/app').'/'.$offer->image,
                    'shop_available' => $shop_available,
                    'shop_id' => $offer->seller_id,
                    'shop_icon' => $shop_icon,
                    'shop_name' => $offer->shop->name,
                    'percent_off' => $percent_off,
                    'offer_title' => $offer->title,
                    'offer_description' => $offer->description,
                    'seller_info' => $seller_info,
                    'about_offer' => $offer->about,
                    'shop_latitude' => $offer->shop->latitude,
                    'shop_longitude' => $offer->shop->longitude,
                    'website' => $offer->shop->website,
                    'phone_no' => $offer->shop->phone_no
                );

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );

            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Offer not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To claim offer
    public function claimOffer(Request $request)
    {
        $rules = array(
            'offer_id' => 'required|integer',
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $offer = Offer::where(['id' => $request->offer_id, 'status' => 1])->first();

                if ($offer) {

                    ClaimedOffer::create([
                        'offer_id' => $request->offer_id,
                        'user_id' => $request->user_id,
                        'shop_id' => $offer->seller_id
                    ]);

                    $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );

                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Offer not exist!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //To get products based on arrivals
    public function newArrivalItems(Request $request)
    {
        $rules = array(
            // 'userid' => 'required|integer',
            // 'directoryid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array();

            $products = Product::whereHas('shop', function($q) {
                $q->where('is_active', 1);
            })->where('type', '!=', 2)->where('stock', '!=', 0)->where(['is_active' => 1,'is_approved' => 1])->orderBy('id', 'desc')->take(20)->get();

            foreach ($products as $key => $product) {
                $seller_info = null;
                $new_price = null;
                $percent_off = null;

                if($product->shop->sellerInfo)
                    $seller_info = $product->shop->name;

                if($product->type == 2)
                    $product = Product::where(['parent_id' => $product->id, 'is_active' => 1, 'is_approved' => 1])->where('stock', '!=', 0)->orderBy('id', 'ASC')->first();

                //Offer Calculation
                $product_data = $this->offerPrice($product->id);
                
                if($product_data['new_price'] != null) {
                    $new_price = number_format($product_data['new_price'], 2);
                    $percent_off = $product_data['percent_off'].'%';
                } else {
                    $new_price = null;
                    $percent_off = null;
                }

                //End of Offer calculation

                $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $product->id])->first();

                if($wishlist)
                    $isWishlist = true;
                else
                    $isWishlist = false;

                $exist_parent_category = null;
                $check_category = 0;

                $exist_category = ProductCategory::where(['product_id' => $product->id, 'shop_id' => $product->seller_id])->whereHas('categoryData')->first();

                if($exist_category) {
                    $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                    if($exist_sub_category) {
                        $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                        if($exist_parent_category) {
                            $check_category = 1;
                        }
                    }
                }

                if($check_category == 1 && count($data) <= 10) {

                    $data[] = array(
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_brand' => $product->brand->name ?? '',
                        'stock' => $product->stock,
                        'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                        'seller_info' => $seller_info,
                        'product_description' => $product->description,
                        'new_price' => $new_price,
                        'old_price' => number_format($product->price, 2),
                        'percent_off' => $percent_off,
                        'isWishlist' => $isWishlist
                    );   
                }

                
            }

            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }

    //To view product details
    public function productDetail(Request $request)
    {
        $rules = array(
            'product_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array();
            $images = array();
            $available_attributes = array();
            $product_color_updated_images = array();
            $brand = null;
            $percent_off = null;

            $product = Product::where(['id' => $request->product_id, 'is_active' => 1, 'is_approved' => 1])->first();

            if($product) {

                $sib_products = Product::where(['parent_id' => $product->parent_id])->get()->pluck('id')->toArray();

                $thump_image = $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image;

                $product_images = $product->productImages;

                foreach ($product_images as $key => $product_image) {
                    $images[] = asset('storage/app').'/'.$product_image->image;
                }

                if($product->shop)
                    $seller_info = $product->shop->name;
                else
                    $seller_info = null;

                $product_data = $this->offerPrice($product->id);
               
                if($product_data['new_price'] != null) {
                    $new_price = number_format($product_data['new_price'], 2);
                    $discount = $product_data['discount'];
                    $save_text = 'You save Rs. '.$discount.'('.$product_data["percent_off"].'%)';
                    $percent_off = $product_data["percent_off"].'%';

                } else {
                    $new_price = null;
                    $save_text = null;
                }

                //type = 0 => single, 1 => simple, 2 => configurable
                if($product->type == 0) {
                    $type = 0;
                }elseif ($product->type == 1) {
                    // $sib_products = Product::where(['parent_id' => $product->parent_id])->get()->pluck('id')->toArray();

                    if($request->subcategory_id != null) {
                        $subcategory_id = $request->subcategory_id;
                    } else {
                        $subcategory = ProductCategory::where(['product_id' => $product->id])->whereHas('categoryData', function($q) {
                            $q->where('is_active', 1);
                        })->orderBy('id', 'DESC')->get()->pluck('category_id')->toArray(); 

                        $subcategory_id = $subcategory[0];
                    }

                    $category_attributes = BusinessCategoryField::where(['category_id' => $subcategory_id, 'is_active' => 1])->whereHas('category')->get();
                     
                    $values = array();

                    foreach ($category_attributes as $category_attribute) {
                        $product_attribute_values = array();
                        // dump($category_attribute->toArray());
                        $category_attribute_values = explode(',', $category_attribute->field_value);
                        
                        // dd($category_attribute_values);
                        foreach ($sib_products as $key => $sib_product) {

                            $product_attribute = ProductAttribute::whereIn('attr_value', $category_attribute_values)->where(['product_id' => $sib_product, 'attribute_id' => $category_attribute->id])->first();
                            // dump($category_attribute);
                            if($product_attribute) {

                                if(strtolower($category_attribute->field_name) == strtolower('colour')) {
                                    // dd(count($product_images));
                                    if(count($product_color_updated_images) < 1) {
                                        $color_attribute_products = ProductAttribute::whereIn('product_id', $sib_products)->where(['attribute_id' => $category_attribute->id])->orderBy('id', 'ASC')->get()->pluck('product_id');

                                        $product_color_images = Product::whereIn('id', $color_attribute_products)->where(['is_active' => 1,'is_approved' => 1])->get();

                                        $attribute_product = Product::whereIn('id', $color_attribute_products)->first();

                                        $is_available = true;

                                        if($attribute_product->stock == 0 || $attribute_product->is_active == 0) {
                                            $is_available = false;
                                        }

                                        foreach($product_color_images as $product_color_image) {
                                            $product_color_updated_images[] = array(
                                                'product_id' => $product_color_image->id,
                                                'is_available' => $is_available,
                                                'image' => asset('storage/app').'/'.$product_color_image->thump_image,
                                                'value' => $product_attribute->attr_value
                                            ); 
                                        }
                                        
                                        $product_attribute_values =  $product_color_updated_images;
                                    }
                                } else {
                                    

                                    $attribute_product = Product::where(['id' => $product_attribute->product_id])->first();

                                    $is_available = true;

                                    if($attribute_product->stock == 0 || $attribute_product->is_active == 0) {
                                        $is_available = false;
                                    }

                                    

                                    if(!in_array($product_attribute->attr_value, $values)) {

                                        $values[] = $product_attribute->attr_value;

                                        $product_attribute_values[] = array(
                                                'product_id' => $product_attribute->product_id,
                                                'is_available' => $is_available,
                                                'value' => $product_attribute->attr_value
                                            );
                                    }
                                    
                                }
                            }
                        }

                        if(count($product_attribute_values) > 0) {
                            
                            if($category_attribute->is_detail_filter == 1)
                            {
                                $available_attributes[] = array(
                                        'attribute_id' => $category_attribute->id,
                                        'attribute_title' => $category_attribute->field_name,
                                        'attribute_values' => $product_attribute_values
                                    );
                            }
                        }
                    }

                    $type = 1;

                } else {
                    $type = 2;
                }

                $current_product_attributes = ProductAttribute::where(['product_id' => $product->id])->get();
                $current_attributes = array();

                foreach ($current_product_attributes as $key => $current_product_attribute) {

                    $exist_category_attribute = BusinessCategoryField::where(['id' => $current_product_attribute->attribute_id, 'is_active' => 1])->first();

                    if($exist_category_attribute) {

                        if($exist_category_attribute->is_detail_filter == 1)
                        {
                            $current_attributes[] = array(
                                'product_id' => $current_product_attribute->product_id,
                                'attribute_id' => $current_product_attribute->attribute_id,
                                'attribute_title' => $exist_category_attribute->field_name,
                                'attribute_values' => $current_product_attribute->attr_value
                            ); 
                        }
                    }
                }

                $product_rating = number_format(ProductRating::where(['product_id' => $product->id])->get()->pluck('rating')->avg(), 1);

                $no_reviews = ProductRating::where(['product_id' => $product->id])->where('review', '!=', null)->count();

                if($product->brand_id != null) {
                    $brand = $product->brand->name;
                }

                $cart_count = Cart::where(['user_id' => $request->user_id, 'product_id' => $product->id])->sum('quantity');
                
                $data['type'] = $type;
                $data['product_id'] = $product->id;
                $data['product_name'] = $product->name;
                $data['product_brand'] = $product->brand->name ?? '';
                $data['product_summary'] = $product->description;
                $data['thump_image'] = $thump_image;
                $data['images'] = $images;
                $data['rating'] = $product_rating;
                $data['no_reviews'] = $no_reviews;
                $data['new_price'] = $new_price;
                $data['old_price'] = number_format($product->price, 2);
                $data['percent_off'] = $percent_off;
                $data['stock'] = $product->stock;
                $data['save_text'] = $save_text;
                $data['available_attributes'] = $available_attributes;
                $data['current_attributes'] = $current_attributes;
                $data['product_description'] = $product->description;
                $data['seller_info'] = $seller_info;
                $data['brand'] = $brand;
                $data['cart_count'] = (int)$cart_count;


                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Out of stock!"
                );
            }

        }
        return response()->json($res);
    }

    public function productDetails(Request $request)
    {
        $rules = array(
            'product_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

            return response()->json($res);

        }

        $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

        $data = array();
        $images = array();
        $available_attributes = array();
        $product_color_updated_images = array();
        $brand = null;
        $new_price = 0;
        $save_text = null;
        $percent_off = null;
        $cart_count = 0;
        $product_images = array();
        $current_attributes = array();
        $result_array = array();

        $product = Product::where(['id' => $request->product_id, 'is_active' => 1, 'is_approved' => 1])->first();

        if(!$product) {
            
            $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Product not found!"
                ); 

            return response()->json($res);
        }

        if($product->shop)
            $seller_info = $product->shop->name;
        else
            $seller_info = null;

        if($product->brand_id != null) {
            $brand = $product->brand->name;
        }

        $product_stock = null;
        
        if($product->type == 1) {
            $product_data = $this->offerPrice($product->id);
            $product_name = $product->name;
            $thump_image = $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image;
            $product_stock = $product->stock;
            $parent_cart = 0;

            //Multiple Images
            $product_images = $product->productImages;

            foreach ($product_images as $key => $product_image) {
                $images[] = asset('storage/app').'/'.$product_image->image;
            }
            //End Multiple Images
            
            if($product_data['new_price'] != null) {
                $new_price = number_format($product_data['new_price'], 2);
                $price = number_format($product->price, 2);
                $discount = $product_data['discount'];
                $save_text = 'You save Rs. '.$discount.'('.$product_data["percent_off"].'%)';
                $percent_off = $product_data["percent_off"].'%';

            } else {
                $new_price = null;
                $price = number_format($product->price, 2);
                $save_text = null;
            }

            $product_id = $product->id;
            $parent_product = Product::where(['id' => $product->parent_id, 'is_active' => 1, 'is_approved' => 1])->first();

            if(!$parent_product) {
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Product not found!"
                ); 

                return response()->json($res);
            }

            $type = $parent_product->type;

            $sub_products = Product::where(['parent_id' => $parent_product->id])->get();

            // dd($sub_products->toArray());
            foreach ($sub_products as $key => $sub_product) {
                // dump($sub_product->id);
                $is_cart = false;
                $is_wishlist = false;
                $sub_cart_count = 0;
                $variants = array();
                $option_values = array();

                $product_data = $this->offerPrice($sub_product->id);
               
                if($product_data['new_price'] != null) {
                    $new_price = number_format($product_data['new_price'], 2);
                    $discount = $product_data['discount'];
                    $save_text = 'You save Rs. '.$discount.'('.$product_data["percent_off"].'%)';
                    $percent_off = $product_data["percent_off"].'%';

                } else {
                    $new_price = null;
                    $save_text = null;
                    $percent_off = null;
                    $discount = null;
                }

                //Multiple Images
                $sub_product_images = array();
                $product_images = $sub_product->productImages;

                foreach ($product_images as $key => $product_image) {
                    $sub_product_images[] = asset('storage/app').'/'.$product_image->image;
                }
                //End Multiple Images

                if($user) {
                    $cart = Cart::where(['user_id' => $user->id, 'product_id' => $sub_product->id])->first();

                    if($cart) {
                        $sub_is_cart = true;
                        $sub_cart_count = $cart->quantity;
                            
                        if($parent_cart == 0 && $request->product_id == $sub_product->id) {
                            $is_cart = true;
                            $cart_count = $sub_cart_count;
                            $parent_cart = 1;
                        }
                    
                    }

                    // $wishlist = WishList::where(['user_id' => $user->id, 'product_id' => $sub_product->id])->first();

                    // if($wishlist)
                    //     $wishlist = true;
                }

                $sub_product_attributes = ProductAttribute::where(['product_id' => $sub_product->id])->get();

                foreach ($sub_product_attributes as $key => $sub_product_attribute) {

                    $attribute_data = BusinessCategoryField::where(['id' => $sub_product_attribute->attribute_id])->first();

                    if($attribute_data) {

                        $option_values[] = array(
                            'theme_id' => $attribute_data->id,
                            'theme_name' => $attribute_data->field_name,
                            'theme_value' => $sub_product_attribute->attr_value
                        );
                    }
                }

                $sub_thump_image = $sub_product->thump_image == null ? null : asset('storage/app').'/'.$sub_product->thump_image;
                $product_rating = number_format(ProductRating::where(['product_id' => $sub_product->id])->get()->pluck('rating')->avg(), 1);
                $no_reviews = ProductRating::where(['product_id' => $sub_product->id])->where('review', '!=', null)->count();

                $variants['type'] = $sub_product->type;
                $variants['product_id'] = $sub_product->id;
                $variants['product_name'] = $sub_product->name;
                $variants['product_brand'] = $product->brand->name ?? '';
                $variants['product_summary'] = $sub_product->description;
                $variants['thump_image'] = $sub_thump_image;
                $variants['images'] = $sub_product_images;
                $variants['rating'] = $product_rating;
                $variants['no_reviews'] = $no_reviews;
                $variants['new_price'] = $new_price;
                $variants['old_price'] = number_format($sub_product->price, 2);
                $variants['percent_off'] = $percent_off;
                $variants['stock'] = $sub_product->stock;
                $variants['save_text'] = $save_text;
                $variants['product_description'] = $product->description;
                // $variants['seller_info'] = $seller_info;
                // $variants['brand'] = $brand;
                $variants['cart_count'] = $sub_cart_count;
                $variants['option_values'] = $option_values;

                $result_array[] = $variants;
            }

            $product = $parent_product;


        } elseif($product->type == 0) {
            $product_id = $product->id;
            $type = $product->type;
            $product_name = $product->name;
            $thump_image = $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image;
            $product_stock = $product->stock;

            $product_data = $this->offerPrice($product->id);
            $cart_count = Cart::where(['user_id' => $request->user_id, 'product_id' => $product->id])->sum('quantity');
               
            if($product_data['new_price'] != null) {
                $new_price = number_format($product_data['new_price'], 2);
                $price = number_format($product->price, 2);
                $discount = $product_data['discount'];
                $save_text = 'You save Rs. '.$discount.'('.$product_data["percent_off"].'%)';
                $percent_off = $product_data["percent_off"].'%';

            } else {
                $new_price = null;
                $price = number_format($product->price, 2);
                $save_text = null;
            }

            //Multiple Images
            $product_images = $product->productImages;

            foreach ($product_images as $key => $product_image) {
                $images[] = asset('storage/app').'/'.$product_image->image;
            }
            //End Multiple Images
        } else {
            $product_id = $product->id;
            $type = $product->type;
            $product_name = $product->name;
            $thump_image = $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image;

            $product = Product::where(['id' => $product->id])->first();

            //Multiple Images
            $product_images = $product->productImages;

            foreach ($product_images as $key => $product_image) {
                $images[] = asset('storage/app').'/'.$product_image->image;
            }
            //End Multiple Images

            if($product) {
                
                $new_price = null;
                $price = null;
                $save_text = null;
                $percent_off = null;
                $parent_cart = 0;
                $sub_products = Product::where(['parent_id' => $product->id])->get();

                foreach ($sub_products as $key => $sub_product) {

                    // $current_product_attributes = ProductAttribute::where(['product_id' => $sub_product->id])->get();
                    // $current_attributes = array();

                    // if(count($current_attributes) == 0) {

                    //     foreach ($current_product_attributes as $key => $current_product_attribute) {

                    //         $exist_category_attribute = BusinessCategoryField::where(['id' => $current_product_attribute->attribute_id, 'is_active' => 1])->first();
                            
                    //         if($exist_category_attribute) {

                    //             if($exist_category_attribute->is_detail_filter == 1)
                    //             {
                    //                 $current_attributes[] = array(
                    //                     'product_id' => $current_product_attribute->product_id,
                    //                     'attribute_id' => $current_product_attribute->attribute_id,
                    //                     'attribute_title' => $exist_category_attribute->field_name,
                    //                     'attribute_values' => $current_product_attribute->attr_value
                    //                 ); 
                    //             }
                    //         }
                    //     }
                    // }
                    
                    // dump($sub_product->id);
                    $sub_is_cart = false;
                    $is_wishlist = false;
                    $sub_cart_count = 0;
                    $variants = array();
                    $option_values = array();
                    $images = array();

                    if($product_stock == null)
                        $product_stock = $product->stock;

                    $product_data = $this->offerPrice($sub_product->id);
                   
                    if($product_data['new_price'] != null) {
                        $new_price = number_format($product_data['new_price'], 2);
                        $discount = $product_data['discount'];
                        $save_text = 'You save Rs. '.$discount.'('.$product_data["percent_off"].'%)';
                        $percent_off = $product_data["percent_off"].'%';

                    } else {
                        $new_price = null;
                        $save_text = null;
                        $percent_off = null;
                        $discount = null;
                    }

                    //Multiple Images
                    $product_images = $sub_product->productImages;

                    foreach ($product_images as $key => $product_image) {
                        $images[] = asset('storage/app').'/'.$product_image->image;
                    }
                    //End Multiple Images

                    if($user) {
                        $cart = Cart::where(['user_id' => $user->id, 'product_id' => $sub_product->id])->first();

                        if($cart) {
                            $sub_is_cart = true;
                            $sub_cart_count = $cart->quantity;

                            if($parent_cart == 0) {
                                $is_cart = true;
                                $cart_count = $cart->quantity;
                                $parent_cart = 1;
                            }
                            
                        }

                        // $wishlist = WishList::where(['user_id' => $user->id, 'product_id' => $sub_product->id])->first();

                        // if($wishlist)
                        //     $wishlist = true;
                    }
                    // dump($sub_product);
                    $sub_product_attributes = ProductAttribute::where(['product_id' => $sub_product->id])->get();
                    

                    foreach ($sub_product_attributes as $key => $sub_product_attribute) {

                        $attribute_data = BusinessCategoryField::where(['id' => $sub_product_attribute->attribute_id])->first();

                        if($attribute_data) {

                            $option_values[] = array(
                                'theme_id' => $attribute_data->id,
                                'theme_name' => $attribute_data->field_name,
                                'theme_value' => $sub_product_attribute->attr_value
                            );
                        }
                        
                    }

                    $sub_thump_image = $sub_product->thump_image == null ? null : asset('storage/app').'/'.$sub_product->thump_image;
                    $product_rating = number_format(ProductRating::where(['product_id' => $sub_product->id])->get()->pluck('rating')->avg(), 1);
                    $no_reviews = ProductRating::where(['product_id' => $sub_product->id])->where('review', '!=', null)->count();

                    $variants['type'] = $sub_product->type;
                    $variants['product_id'] = $sub_product->id;
                    $variants['product_name'] = $sub_product->name;
                    $variants['product_brand'] = $product->brand->name ?? '';
                    $variants['product_summary'] = $sub_product->description;
                    $variants['thump_image'] = $sub_thump_image;
                    $variants['images'] = $images;
                    $variants['rating'] = $product_rating;
                    $variants['no_reviews'] = $no_reviews;
                    $variants['new_price'] = $new_price;
                    $variants['old_price'] = number_format($sub_product->price, 2);
                    $variants['percent_off'] = $percent_off;
                    $variants['stock'] = $sub_product->stock;
                    $variants['save_text'] = $save_text;
                    $variants['product_description'] = $product->description;
                    // $variants['seller_info'] = $seller_info;
                    // $variants['brand'] = $brand;
                    $variants['cart_count'] = $sub_cart_count;
                    $variants['option_values'] = $option_values;

                    $result_array[] = $variants;
                }

                // $product = Product::where(['parent_id' => $product->id])->first();
            }

            
        }

        $product_rating = number_format(ProductRating::where(['product_id' => $product->id])->get()->pluck('rating')->avg(), 1);
        $no_reviews = ProductRating::where(['product_id' => $product->id])->where('review', '!=', null)->count();

        $data['type'] = $type;
        $data['product_id'] = $product_id;
        $data['product_name'] = $product_name;
        $data['product_brand'] = $product->brand->name ?? '';
        $data['product_summary'] = $product->description;
        $data['thump_image'] = $thump_image;
        $data['images'] = $images;
        $data['rating'] = $product_rating;
        $data['no_reviews'] = $no_reviews;
        $data['new_price'] = $new_price;
        $data['old_price'] = $price;
        $data['percent_off'] = $percent_off;
        $data['stock'] = $product_stock;
        $data['save_text'] = $save_text;
        $data['product_description'] = $product->description;
        $data['seller_info'] = $seller_info;
        $data['brand'] = $brand;
        $data['cart_count'] = (int)$cart_count;
        $data['variants'] = $result_array;
        // $data['current_attributes'] = $current_attributes;

        $res = array(
            'errorcode' => 0,
            'data' => $data,
            'message' => "Success!"
        );
        
        return response()->json($res);
    }

    //To get order summary
    public function orderSummary(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'is_redeemed' => 'required|integer|in:0,1'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $products = array();
            $price_details = array();
            $address = (object)[];
            $grand_total = 0;
            $delivery_fee = 0;

            $user = User::where(['id' => $request->user_id])->first();

            if($user) {

                if($request->address_id != null) {
                    $user_address = UserAddress::where(['id' => $request->address_id])->first();
                } else {
                    $user_address = UserAddress::where(['user_id' => $request->user_id, 'address_type' => 0, 'default' => 1])->first();

                    $user_address = $user_address == null ? UserAddress::where(['user_id' => $request->user_id, 'address_type' => 0, 'is_save' => 1])->first() : $user_address;
                }

                if($user_address) {
                    // $address = preg_replace("/,+/", ",", $user_address->build_name.','.$user_address->area.','.$user_address->landmark.','.$user_address->location);
                    // $address = trim($address, ",");
                    $address = array(
                        'id' => $user_address->id,
                        'name' => $user_address->name,
                        'build_name' => $user_address->build_name,
                        'area' => $user_address->area,
                        'location' => $user_address->location,
                        'landmark' => $user_address->landmark,
                        'latitude' => $user_address->latitude,
                        'longitude' => $user_address->longitude,
                        'mobile' => $user_address->mobile,
                        'pincode' => $user_address->pincode,
                        'type' => $user_address->type
                    );

                    $address_id = $user_address->id;
                } else {
                    $address = (object)[];
                }

                if($request->product_id != null) {
                    $cart_items = 1;
                    $is_available = true;
                    $is_active = true;

                    $cart = Cart::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                    $product = Product::where(['id' => $request->product_id, 'is_active' => 1,'is_approved' => 1])->first();

                    if($product) {

                        $product_data = $this->offerPrice($product->id);

                        if($product_data['new_price'] != null)
                            $price = $product_data['new_price'];
                        else
                            $price = $product->price;

                        $subtotal = $price * 1;
                        $grand_total = $grand_total + $subtotal;

                        if($product->stock == 0) {
                            $is_available = false;
                        }

                        if($product->is_active == 0) {
                            $is_active = false;
                        }

                       
                        if($product->shop) {
                            $shop_name = $product->shop->name; 
                        } else {
                            $shop_name = null;   
                        }
                        // dd($product_data);
                        $products[] = array(
                            'prod_id' => $product->id,
                            'product_name' => $product->name,
                            'product_brand' => $product->brand->name ?? '',
                            'is_available' => $is_available,
                            'is_active' => $is_active,
                            'seller_info' => $shop_name,
                            'product_new_price' => number_format($product_data['new_price'], 2),
                            'product_price' => number_format($product->price, 2),
                            'product_qty' => 1,
                            'subtotal' => number_format($subtotal, 2),
                            'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                        );

                        $is_instore = false;

                        if($product->shop->instore == 1) {
                            $is_instore = true;
                        }

                        $items[] = array(
                                'shop_id' => $product->seller_id,
                                'seller_name' => $product->shop->name,
                                'is_instore' => $is_instore,
                                'instore_purchase' => $cart->instore,
                                'instore_description' => $product->shop->instore_description,
                                'products' => $products
                            );

                    } else {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Product not available!"
                        );

                        return response()->json($res);
                    }

                } else {
                    $cart = Cart::where(['user_id' => $request->user_id])->whereHas('getProduct')->get();
                    $cart_items = count($cart);
                    $sellers = $cart->pluck('seller_id')->unique();
                    $items = array();
                    // dd($sellers);
                    foreach ($sellers as $key => $seller) {
                        // $items = array();
                        $cart_products = Cart::where(['user_id' => $request->user_id, 'seller_id' => $seller])->get();
                        // dd($cart_products);
                        $seller_data = BusinessShop::where(['id' => $seller])->first();
                        $products = array();

                        foreach ($cart_products as $key => $value) {
                            
                            $is_available = true;
                            $is_active = true;
                            $is_instore = false;

                            $product = Product::where(['id' => $value->product_id, 'is_active' => 1,'is_approved' => 1])->whereHas('shop')->first();

                            if($product) {

                                $product_data = $this->offerPrice($product->id);

                                if($product_data['new_price'] != null)
                                    $price = $product_data['new_price'];
                                else
                                    $price = $product->price;

                                $subtotal = $price * $value->quantity;
                                $grand_total = $grand_total + $subtotal;

                                if($product->stock == 0) {
                                    $is_available = false;
                                }

                                if($product->is_active == 0) {
                                    $is_active = false;
                                }
                               
                                if($product->shop) {
                                    $shop_name = $product->shop->name; 
                                } else {
                                    $shop_name = null;   
                                }
                                // dd($product_data);
                                $products[] = array(
                                    'prod_id' => $product->id,
                                    'product_name' => $product->name,
                                    'product_brand' => $product->brand->name ?? '',
                                    'is_available' => $is_available,
                                    'is_active' => $is_active,
                                    'seller_info' => $shop_name,
                                    'product_new_price' => number_format($product_data['new_price'], 2),
                                    'product_price' => number_format($product->price, 2),
                                    'product_qty' => $value->quantity,
                                    'subtotal' => number_format($subtotal, 2),
                                    'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                                );


                                if($product->shop->instore == 1) {
                                    $is_instore = true;
                                }
                            }
                            
                        }

                        $items[] = array(
                                'shop_id' => $seller,
                                'seller_name' => $seller_data->name,
                                'is_instore' => $is_instore,
                                'instore_purchase' => $value->instore,
                                'instore_description' => $product->shop->instore_description,
                                'products' => $products
                            );
                    }
                }

                $flag = 0;

                $data3 = [];
                
                $current_points = $user->wallet == null ? 0 : $user->wallet;

                if($current_points > 0 && $request->is_redeemed == 1) {
                    $maximum_redeemed_point = ($grand_total * 80) / 100;
                    
                    if($current_points >= $maximum_redeemed_point) {
                        $maximum_redeemed_point = $maximum_redeemed_point;
                    } else {
                        $maximum_redeemed_point = $current_points;
                    }

                    $current_points = $user->wallet - $maximum_redeemed_point;
                    
                    $flag = 1;
                } else {
                    $maximum_redeemed_point = 0;
                }

                $tot = $grand_total;
                $grand_total = $grand_total - $maximum_redeemed_point;

                $delivery_fee_below = Settings::where('slug', 'delivery_charge_below')->first();
                $delivery_fee_between = Settings::where('slug', 'delivery_charge_between')->first();
                $delivery_fee_above = Settings::where('slug', 'delivery_charge_above')->first();

                if($delivery_fee_below->max_order_price > $tot) {

                    $delivery_fee = $delivery_fee_below->price;
                } elseif($delivery_fee_between->min_order_price <= $tot && $delivery_fee_between->max_order_price > $tot) {
                    $delivery_fee = $delivery_fee_between->price;
                } elseif($delivery_fee_above->max_order_price <= $tot) {
                    $delivery_fee = $delivery_fee_above->price;
                }

                // if(count($products) > 0) {
                    $text1 = 'Price('.$cart_items.' items)';
                    $value1 = 'Rs '.number_format($tot, 2);

                    $text2 = 'Delivery Fee';
                    $value2 = $delivery_fee == 0 ? 'Free' : 'Rs '.number_format($delivery_fee, 2);

                    $data1 =  array(
                        'text' => $text1,
                        'value' => $value1
                    );

                    $data2 =  array(
                        'text' => $text2,
                        'value' => $value2
                    );


                    if($flag ==1)
                    {
                        $text3 = 'Redeemed Amount';
                        $value3 = '- Rs '.number_format($maximum_redeemed_point, 2);
                        $data3 =  array(
                            'text' => $text3,
                            'value' => $value3
                        );

                        $price_details = array($data1, $data2 ,$data3);
                    }else{

                        $price_details = array($data1, $data2);
                    }

                    
                // }

                $grand_total = $grand_total + $delivery_fee;

                $data['wallet_point'] = $current_points;
                $data['redeem_point'] = $maximum_redeemed_point;
                $data['address'] = $address;
                $data['items'] = $items;
                $data['price_details'] = $price_details;
                $data['grand_total'] = number_format($grand_total, 2);
                $data['total_amount'] = number_format($tot, 2);

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //to update product to instore
    public function inStore(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'seller_id' => 'required|integer',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

            return response()->json($res);
        }

        $cart = Cart::where(['user_id' => $request->user_id, 'seller_id' => $request->seller_id])->get();

        foreach ($cart as $key => $value) {
            
            if($value->instore == 1) {
                $value->update(['instore' => 0]);
            } else {
                $value->update(['instore' => 1]);
            }
        }
        // if($cart->instore == 1) {
        //     $cart->update(['instore' => 0]);
        // } else {
        //     $cart->update(['instore' => 1]);
        // }
        

        $res = array(
            'errorcode' => 0,
            'data' => (object)[],
            'message' => "Success!"
        );
        return response()->json($res);
    }

    //to get razorpay order id
    public function getRazorPayOrderId(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'total_amount' => 'required',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array();

            if($request->total_amount < 50) {
                DB::rollback();

                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Please purchase with greater than 50 Rs!"
                );

                return response()->json($res);
            } 
  
            $api_key = 'rzp_live_lZPTOytlOJXyl2';
            $api_secret = 'Dn4X1mu2TpMjF3pIDZ638w49';
  
            // $api_key = 'rzp_test_CCbSyEjgrTpxGW';
            // $api_secret = 'BUgj1gTMG7NJ24HF953GaBvs';
            
            // dd($api_secret); 
            $api = new Api($api_key, $api_secret);
            $razorAmount = $request->total_amount * 100;
            
            $order = $api->order->create(array(
                'amount' => $razorAmount,
                'payment_capture' => 1,
                'currency' => 'INR'
                )
              );
             // dd($order);
            $razOrderId = $order['id'];
            $data['razorpay_order_id'] = $razOrderId;

            $status = Status::where(['slug' => 'pending', 'is_active' => 1])->first();

            if($status)
                $status_id = $status->id;

            Order::create([
                'razorpay_order_id' => $razOrderId, 
                'amount' => $request->total_amount,
                'status_id' => $status_id,
                'is_active' => 0
            ]);
 
            $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
        }
        return response()->json($res);
    }

    //check product stock
    public function checkStock(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'product_id' => 'sometimes|required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

            return response()->json($res);
        }

        if($request->product_id != null || $request->product_id != '') {
            $product = Product::where(['id' => $request->product_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '>=', 1)->whereHas('shop', function($q) {
                        $q->where('is_active', 1);
            })->first();

            if(!$product) {
                
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Out of stock!"
                );

                return response()->json($res);
            }

            $exist_parent_category = null;
            $check_category = 0;

            $exist_category = ProductCategory::where(['product_id' => $product->id, 'shop_id' => $product->seller_id])->whereHas('categoryData')->first();

            if($exist_category) {
                $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                if($exist_sub_category) {
                    $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                    if($exist_parent_category) {
                        $check_category = 1;
                    }
                }
            }

            if($check_category == 0) {
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Your cart contains unavailable product(s)!"
                );

                return response()->json($res);
            }
        } else {
            $items = Cart::where(['user_id' => $request->user_id])->whereHas('getProduct')->get();

            foreach ($items as $key => $item) {
                $product = Product::where(['id' => $item->product_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '>=', $item->quantity)->whereHas('shop', function($q) {
                        $q->where('is_active', 1);
                })->first();
        
               if(!$product) {
                    
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Out of stock!"
                    );

                    return response()->json($res);
                }

                $exist_parent_category = null;
                $check_category = 0;

                $exist_category = ProductCategory::where(['product_id' => $product->id, 'shop_id' => $product->seller_id])->whereHas('categoryData')->first();

                if($exist_category) {
                    $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                    if($exist_sub_category) {
                        $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                        if($exist_parent_category) {
                            $check_category = 1;
                        }
                    }
                }

                if($check_category == 0) {
                    
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Your cart contains unavailable product(s)!"
                    );

                    return response()->json($res);
                }
            }
        }

        $res = array(
                'errorcode' => 0,
                'data' => (object)[],
                'message' => "Success!"
            );

        return response()->json($res);
    }


    //place order
    public function placeOrder(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'address_id' => 'required|integer',
            'is_redeemed' => 'required|integer|in:0,1',
            'payment_method' => 'required',
            'total_amount' => 'required',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();
            $data = (object)[];

            if($user) {
                $maximum_redeemed_point = 0;
                $divided_points = 0;
                $order_array = array();
                $commission = 0;
                $current_points = $user->wallet == null ? 0 : $user->wallet;
                
                $address = UserAddress::where(['id' => $request->address_id])->first();

                if($address) {
                    $latitude = $address->latitude;
                    $longitude = $address->longitude;

                    $distance = Settings::where(['slug' => 'max_delivery_distance'])->first();

                    $shops = BusinessCategoryShop::select('shop_id')->get()->toArray();
                    
                    if($latitude != null || $longitude != null) {
                        $shop_value = BusinessShop::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                                ->having('distance', '<', $distance->value)
                                ->where('name', 'LIKE', '%' . $request->search . '%')
                                // ->where('id', $shop->shop_id)
                                ->where('is_active', 1)
                                ->whereIn('id', $shops)
                                // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                                ->orderBy('distance')
                                ->first();
                    } else {
                        
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Please set your location in this address!"
                        );

                        return response()->json($res);
                    }

                    if(!$shop_value) {

                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Delivery not available!"
                        );

                        return response()->json($res);
                    }
                } else {
                    $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Incorrect Address!"
                        );

                    return response()->json($res);
                }

                $api_key = env('RAZ_API_KEY');
                $api_secret = env('RAZ_SECRET_KEY');

                $items = Cart::where(['user_id' => $request->user_id])->whereHas('getProduct')->get()->groupBy('seller_id');

                if(!count($items) > 0) {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Your cart is empty!"
                    );

                    return response()->json($res);
                }

                if($request->product_id != null) {
                    $out_of_stocks = array();
                    $tot_price = 0;
                    $tot_discount = 0;
                    $delivery_fee = 0;
                    $count = 1;
                    $grand_total = 0;

                    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $order_no = '';

                    for ($i = 0; $i < 10; $i++) {
                        $order_no .= $characters[rand(0, $charactersLength - 1)];
                    }

                    $date = new DateTime('now');
                    $date->modify('+1 day');
                    $delivery_date = $date->format('Y').'-'.$date->format('m').'-'.$date->format('d');
                    $delivery_expected_date = $delivery_date;

                    // $accepted_delivery_time = Settings::where(['slug' => 'accepted_delivery_time'])->first();
                    // $cur_time = Carbon::now()->format('H:i:s');
                    
                    // if ($cur_time >= $accepted_delivery_time->value) {
                    //     $date->modify('+1 day');
                    //     $delivery_expected_date = $date->format('Y').'-'.$date->format('m').'-'.$date->format('d');
                    // } else {
                    //     $delivery_expected_date = $delivery_date;
                    // }

                    DB::beginTransaction();

                    $cart = Cart::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                    $product = Product::where(['id' => $request->product_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '>=', 1)->whereHas('shop', function($q) {
                        $q->where('is_active', 1);
                    })->first();

                    if(!$product) {
                        
                        DB::rollback();

                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Out of stock!"
                        );

                        return response()->json($res);
                    }

                    $exist_parent_category = null;
                    $check_category = 0;

                    $exist_category = ProductCategory::where(['product_id' => $product->id, 'shop_id' => $product->seller_id])->whereHas('categoryData')->first();

                    if($exist_category) {
                        $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                        if($exist_sub_category) {
                            $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                            if($exist_parent_category) {
                                $check_category = 1;
                            }
                        }
                    }
                    // dump($product->name);
                    if($check_category == 0) {
                        DB::rollback();

                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Your cart contains unavailable product(s)!"
                        );

                        return response()->json($res);
                    }
                    // dd('ss');
                    $product_data = $this->offerPrice($request->product_id);

                    if($product_data['new_price'] != null)
                        $price = $product_data['new_price'];
                    else
                        $price = $product->price;

                    $subtotal = $price * 1;
                    $grand_total = $grand_total + $subtotal;

                    if($request->payment_method == 'online') {
                        $status = Status::where(['slug' => 'pending', 'is_active' => 1])->first();
                    } else {
                        $status = Status::where(['slug' => 'ordered', 'is_active' => 1])->first();
                    }
                    
                    $order = Order::create([
                        'order_no' => $order_no,
                        'user_id' =>  $request->user_id,
                        'shop_id' => $cart->seller_id,
                        'instore' => $cart->instore,
                        'date_purchased' => Carbon::now(),
                        // 'delivery_date' => $delivery_expected_date,
                        'payment_method' => $request->payment_method,
                        'grand_total' => $grand_total,
                        'status_id' => $status->id,
                        'is_active' => 1
                    ]);

                    $product_tot_discount = null;
                    $product_tot_price = 0;

                    $product = Product::where(['id' => $request->product_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '>=', 1)->first();
                    // dd($product);
                    if($product) {

                        $product->update(['stock' => $product->stock - 1]);
                        
                        $product_data = $this->offerPrice($product->id);

                        if($product_data['new_price'] != null) {
                            $product_price = $product_data['new_price'];
                            $discount = $product_data['discount'];
                        } else {
                            $product_price = $product->price;
                            $discount = null;
                        }

                        if($discount != null)
                            $product_tot_discount = $discount * 1;

                        $product_tot_price = $product_price * 1;

                        OrderProduct::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_price' => $product_price,
                            'product_discount' => $discount,
                            'product_quantity' => 1,
                            'tot_price' => $product_tot_price,
                            'tot_discount' => $product_tot_discount
                        ]);

                        $tot_discount = $tot_discount + $product_tot_discount;
                        $tot_price = $tot_price + $product_tot_price;

                        if($request->payment_method != 'online')
                            Cart::where(['user_id' => $request->user_id, 'product_id' => $product->id])->delete();

                    } else {
                        $out_of_stocks[] = $request->product_id;
                    }

                    if(count($out_of_stocks) == $count) {
                        DB::rollback();

                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Your cart contains unavailable product(s)!"
                        );

                        return response()->json($res);
                    }

                    // $status = Status::where(['slug' => 'pending', 'is_active' => 1])->first();

                    // if($status)
                    //     $status_id = $status->id;
                    // else
                    //     $status_id = null;

                    $current_tot_price = $tot_price;
                    $current_points = $user->wallet == null ? 0 : $user->wallet;

                    if($request->is_redeemed == 1) {

                        if($current_points > 0) {
                            $maximum_redeemed_point = round(($tot_price * 80) / 100);
                            
                            if($current_points >= $maximum_redeemed_point) {
                                $maximum_redeemed_point = $maximum_redeemed_point;
                            } else {
                                $maximum_redeemed_point = $current_points;
                            }

                            if($request->payment_method != 'online') {
                                $current_points = $user->wallet - $maximum_redeemed_point;
                                $user->update(['wallet' => $current_points]);
                            }

                            $tot_price = $tot_price - $maximum_redeemed_point;
                        } else {
                            DB::rollback();

                            $res = array(
                                'errorcode' => 1,
                                'data' => (object)[],
                                'message' => "Not much points in your cart!"
                            );

                            return response()->json($res);
                        }
                    }

                    $loyality_points = ($tot_price * 1) / 100;

                    if($order->instore != 1) {
                        $delivery_fee_below = Settings::where('slug', 'delivery_charge_below')->first();
                        $delivery_fee_between = Settings::where('slug', 'delivery_charge_between')->first();
                        $delivery_fee_above = Settings::where('slug', 'delivery_charge_above')->first();

                        if($delivery_fee_below->max_order_price > $product_tot_price) {

                            $delivery_fee = $delivery_fee_below->price;
                        } elseif($delivery_fee_between->min_order_price <= $product_tot_price && $delivery_fee_between->max_order_price > $product_tot_price) {
                            $delivery_fee = $delivery_fee_between->price;
                        } elseif($delivery_fee_above->max_order_price <= $product_tot_price) {
                            $delivery_fee = $delivery_fee_above->price;
                        }
                    }
                    // dd($delivery_fee);
                    $tot_price = $tot_price + $delivery_fee;

                    // if($grand_total < 50) {

                    //     DB::rollback();

                    //     $res = array(
                    //         'errorcode' => 1,
                    //         'data' => (object)[],
                    //         'message' => "Please purchase with greater than 50 Rs!"
                    //     );

                    //     return response()->json($res);
                    // }

                    if($request->payment_method != 'online') {

                        PointHistory::create([
                            'user_id' => $request->user_id,
                            'is_credit' => 0,
                            'order_id' => $order->id,
                            'points' => $maximum_redeemed_point,
                            'slug' => 'debited_by_purchasing_order',
                            'is_valid' => 1
                        ]);

                    } else {

                        PointHistory::create([
                            'user_id' => $request->user_id,
                            'is_credit' => 0,
                            'order_id' => $order->id,
                            'points' => $maximum_redeemed_point,
                            'slug' => 'debited_by_purchasing_order',
                            'is_valid' => 0
                        ]);

                        PointHistory::create([
                            'user_id' => $request->user_id,
                            'is_credit' => 1,
                            'order_id' => $order->id,
                            'points' => round($loyality_points),
                            'slug' => 'credited_by_purchasing_order',
                            'is_valid' => 0
                        ]);
                    }
                    


                    if($request->payment_method != 'online') {
                        $message = array(
                            'type' => 'refer',
                            'message' => round($loyality_points).' points earned by purchasing order.'
                        );

                        $device_types=UserDevice::where('user_id',$request->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                        if (!empty($device_types))
                            SiteHelper::sendAndroidPush($device_types, $message);
                    }
    
                    //   $iosdevice=UserDevice::where('user_id',$request->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
    
                      // if (!empty($iosdevice)) 
                      // SiteHelper::sendIosPush($iosdevice, $message);

                    if($product->commission != null) {
                        $commission = ($product_price * $product->commission) / 100;
                    }

                    SellerOrderData::create([
                        'seller_id' => $cart->seller_id,
                        'total' => $order->grand_total,
                        'order_id' => $order->id,
                        'commission' => $commission,
                        'amount' => $tot_price
                    ]);

                    OrderStatus::create([
                        'order_id' => $order->id,
                        'status_id' => $status->id
                    ]);

                    OrderAddress::create([
                        'order_id' => $order->id,
                        'name' => $address->name,
                        'build_name' => $address->build_name,
                        'area' => $address->area,
                        'location' => $address->location,
                        'landmark' => $address->landmark,
                        'latitude' => $address->latitude,
                        'longitude' => $address->longitude,
                        'mobile' => $address->mobile,
                        'pincode' => $address->pincode,
                        'type' => $address->type
                    ]);

                    $razOrderId = null;
 
                    if($request->payment_method == 'online') {
                        $data = array();
                        // $api_key = 'rzp_test_CCbSyEjgrTpxGW'; //test
                        // $api_secret = 'BUgj1gTMG7NJ24HF953GaBvs'; //test

                        $api_key = 'rzp_live_lZPTOytlOJXyl2'; //live
                        $api_secret = 'Dn4X1mu2TpMjF3pIDZ638w49'; //live

                        // dd($api_secret);
                        $api = new Api($api_key, $api_secret);
                        $razorAmount = $request->total_amount * 100;
                        
                        $raz_order = $api->order->create(array(
                            'amount' => $razorAmount,
                            'payment_capture' => 1,
                            'currency' => 'INR'
                            )
                          );
                         // dd($order);
                        $razOrderId = $raz_order['id'];
                        $data['razorpay_order_id'] = $razOrderId;
                        $data['amount'] = $tot_price;
                        $data['order_id'] = $order->id;

                        $order->update(['razorpay_order_id' => $razOrderId]);

                        Transaction::create([
                            'raz_order_id' => $razOrderId,
                            'order_id' => $order->id,
                            'amount' => $tot_price,
                            'status' => 'pending'
                        ]);
                    }

                    $order->update([
                        'razorpay_order_id' => $razOrderId,
                        'discount' => $tot_discount,
                        'status_id' => $status->id,
                        'points' => $maximum_redeemed_point,
                        'delivery_fee' => $delivery_fee,
                        'amount' => $tot_price,
                        'grand_total' => $order->grand_total + $delivery_fee
                    ]);

                    if($request->payment_method != 'online') {

                        $notification_category = NotificationCategory::where(['slug' => 'placed'])->first();

                        if($notification_category) {
                            
                            Notification::create([
                                'notification_id' => $notification_category->id,
                                'to_id' => $request->user_id,
                                'order_id' => $order->id
                            ]);
                        }

                        $notification_category = NotificationCategory::where(['slug' => 'seller_order_placed'])->first();

                        $shop = BusinessShop::where('id',$order->shop_id)->first();


                        if($notification_category && $shop) {
                            
                            Notification::create([
                                'notification_id' => $notification_category->id,
                                'from_id' => $request->user_id,
                                'to_id' => $shop->seller_id,
                                'order_id' => $order->id
                            ]);

                            $message = array(
                                'title' => 'New Order',
                                'type' => 'new order',
                                'order_id' => $order->id,
                                'message' => 'You have a new order '.$order->order_no
                              );
            
                              $device_types=UserDevice::where('user_id',$shop->seller_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
            
                              if (!empty($device_types))
                              SiteHelper::sendAndroidPush($device_types, $message);
                        }
                    }

                    DB::commit();

                } else {
                    $shops_array = array();
                    $total_points = 0;
                    $seller_id = null;
                    $tot_order_price = 0;
                    $add_delivery_fee = 0;

                    DB::beginTransaction();
                    
                    foreach ($items as $key => $item_list) {

                        $seller_id = $key;
                        $shops_array[] = $key;

                        $out_of_stocks = array();
                        $tot_price = 0;
                        $tot_discount = 0;
                        $delivery_fee = 0;
                        $grand_total = 0;
                        

                        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $charactersLength = strlen($characters);
                        $order_no = '';

                        for ($i = 0; $i < 10; $i++) {
                            $order_no .= $characters[rand(0, $charactersLength - 1)];
                        }

                        $date = new DateTime('now');
                        $date->modify('+1 day');
                        $delivery_date = $date->format('Y').'-'.$date->format('m').'-'.$date->format('d');
                        $delivery_expected_date = $delivery_date;

                        // $accepted_delivery_time = Settings::where(['slug' => 'accepted_delivery_time'])->first();
                        // $cur_time = Carbon::now()->format('H:i:s');
                        
                        // if ($cur_time >= $accepted_delivery_time->value) {
                        //     $date->modify('+1 day');
                        //     $delivery_expected_date = $date->format('Y').'-'.$date->format('m').'-'.$date->format('d');
                        // } else {
                        //     $delivery_expected_date = $delivery_date;
                        // }

                        foreach ($item_list as $item) {
                            $product = Product::where(['id' => $item->product_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '>=', $item->quantity)->first();
            
                            if(!$product) {

                                DB::rollback();

                                $res = array(
                                    'errorcode' => 1,
                                    'data' => (object)[],
                                    'message' => "Out of stock!"
                                );

                                return response()->json($res);
                            }

                            $exist_parent_category = null;
                            $check_category = 0;

                            $exist_category = ProductCategory::where(['product_id' => $product->id, 'shop_id' => $product->seller_id])->whereHas('categoryData')->first();

                            if($exist_category) {
                                $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                                if($exist_sub_category) {
                                    $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                                    if($exist_parent_category) {
                                        $check_category = 1;
                                    }
                                }
                            }
                            // dump($user->name);
                            // dump($product->name);
                            if($check_category == 0) {

                                DB::rollback();

                                $res = array(
                                    'errorcode' => 1,
                                    'data' => (object)[],
                                    'message' => "Your cart contains unavailable product(s)!"
                                );

                                return response()->json($res);
                            }
                            // dd('ss');
                            if($product)
                            {
                                $product_data = $this->offerPrice($product->id);

                                if($product_data['new_price'] != null)
                                    $price = $product_data['new_price'];
                                else
                                    $price = $product->price;
    
                                $subtotal = $price * $item->quantity;
                                $grand_total = $grand_total + $subtotal;
                            }
                        }

                        

                        if($request->payment_method == 'online') {
                            $status = Status::where(['slug' => 'pending', 'is_active' => 1])->first();
                        } else {
                            $status = Status::where(['slug' => 'ordered', 'is_active' => 1])->first();
                        }

                        $shop = BusinessShop::where(['id' => $key, 'is_active' => 1])->first();

                        if($shop == null) {
                            DB::rollback();

                            $res = array(
                                'errorcode' => 1,
                                'data' => (object)[],
                                'message' => "Your cart contains unavailable product(s), Please review the cart items!"
                            );

                            return response()->json($res);
                        }

                        $order = Order::create([
                            'order_no' => $order_no,
                            'user_id' =>  $request->user_id,
                            'shop_id' => $key,
                            'instore' => $item->instore,
                            'date_purchased' => Carbon::now(),
                            // 'delivery_date' => $delivery_expected_date,
                            'payment_method' => $request->payment_method,
                            'status_id' => $status->id,
                            'is_active' => 1,
                            'grand_total' => $grand_total
                        ]);

                        $order_array[] = $order->id;

                        $count = count($item_list);
                        $commission = 0;

                        foreach ($item_list as $item) {
                            
                            $product_tot_discount = null;
                            $product_tot_price = 0;

                            $product = Product::where(['id' => $item->product_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '>=', $item->quantity)->first();
                            // dd($product);
                            if($product) {

                                $product->update(['stock' => $product->stock - $item->quantity]);
                                
                                $product_data = $this->offerPrice($product->id);

                                if($product_data['new_price'] != null) {
                                    $product_price = $product_data['new_price'];
                                    $discount = $product_data['discount'];
                                } else {
                                    $product_price = $product->price;
                                    $discount = null;
                                }

                                if($discount != null)
                                    $product_tot_discount = $discount * $item->quantity;

                                $product_tot_price = $product_price * $item->quantity;

                                if($product->commission != null) {
                                    $commission += ($product_tot_price * $product->commission) / 100;
                                }

                                OrderProduct::create([
                                    'order_id' => $order->id,
                                    'product_id' => $product->id,
                                    'product_price' => $product_price,
                                    'product_discount' => $discount,
                                    'product_quantity' => $item->quantity,
                                    'tot_price' => $product_tot_price,
                                    'tot_discount' => $product_tot_discount
                                ]);

                                $tot_discount = $tot_discount + $product_tot_discount;
                                $tot_price = $tot_price + $product_tot_price;

                                if($request->payment_method != 'online')
                                    $item->delete();
                            } else {
                                $out_of_stocks[] = $item->product_id;
                            }
                        }
                        

                        if(count($out_of_stocks) == $count) {
                            DB::rollback();

                            $res = array(
                                'errorcode' => 1,
                                'data' => (object)[],
                                'message' => "Your cart contains unavailable product(s), Please review the cart items!"
                            );

                            return response()->json($res);
                        }

                        if($request->payment_method != 'online')
                            $current_points = $user->wallet == null ? 0 : $user->wallet;
                        else
                            $current_points = $current_points;

                        if($request->is_redeemed == 1) {

                            if($current_points > 0) {
                                $maximum_redeemed_point = round(($tot_price * 80) / 100);
                                // dd($maximum_redeemed_point);
                                if($current_points >= $maximum_redeemed_point) {
                                    $maximum_redeemed_point = $maximum_redeemed_point;
                                } else {
                                    $maximum_redeemed_point = $current_points;
                                }
                                
                                if($request->payment_method != 'online') {
                                    $current_points = $user->wallet - $maximum_redeemed_point;
                                    $user->update(['wallet' => $current_points]);
                                } else {
                                    $current_points = $user->wallet - $maximum_redeemed_point;
                                }
                                $tot_price = $tot_price - $maximum_redeemed_point;
                            } else {
                                $maximum_redeemed_point = 0;
                            }

                        }

                        $loyality_points = ($tot_price * 1) / 100;

                        $tot_price = $tot_price + $delivery_fee;

                        $tot_order_price += $tot_price;
                        // DB::commit();

                        if($request->payment_method != 'online') {

                            PointHistory::create([
                                'user_id' => $request->user_id,
                                'is_credit' => 0,
                                'order_id' => $order->id,
                                'points' => $maximum_redeemed_point,
                                'slug' => 'debited_by_purchasing_order',
                                'is_valid' => 1
                            ]);

                        } else {

                            PointHistory::create([
                                'user_id' => $request->user_id,
                                'is_credit' => 0,
                                'order_id' => $order->id,
                                'points' => $maximum_redeemed_point,
                                'slug' => 'debited_by_purchasing_order',
                                'is_valid' => 0
                            ]);

                            PointHistory::create([
                                'user_id' => $request->user_id,
                                'is_credit' => 1,
                                'order_id' => $order->id,
                                'points' => round($loyality_points),
                                'slug' => 'credited_by_purchasing_order',
                                'is_valid' => 0
                            ]);
                        }
                        

                        $order->update([
                            'discount' => $tot_discount,
                            'points' => $maximum_redeemed_point,
                            'delivery_fee' => $delivery_fee,
                            'amount' => $tot_price
                        ]);

                        $total_points += $maximum_redeemed_point;

                        OrderStatus::create([
                            'order_id' => $order->id,
                            'status_id' => $status->id
                        ]);

                        OrderAddress::create([
                            'order_id' => $order->id,
                            'name' => $address->name,
                            'build_name' => $address->build_name,
                            'area' => $address->area,
                            'location' => $address->location,
                            'landmark' => $address->landmark,
                            'latitude' => $address->latitude,
                            'longitude' => $address->longitude,
                            'mobile' => $address->mobile,
                            'pincode' => $address->pincode,
                            'type' => $address->type
                        ]);

                        

                        SellerOrderData::create([
                            'seller_id' => $seller_id,
                            'order_id' => $order->id,
                            'total' => $order->grand_total,
                            'commission' => $commission,
                            'amount' => $order->amount
                        ]);

                        //Invoice

                        // $invoice_data = $this->generateInvoice($order->id);

                        //

                        if($request->payment_method != 'online') {
                            
                            $message = array(
                                'type' => 'refer',
                                'message' => round($loyality_points).' points earned by purchasing order.'
                              );
            
                              
                            $device_types=UserDevice::where('user_id',$request->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
            
                            if (!empty($device_types))
                                SiteHelper::sendAndroidPush($device_types, $message);
            
                            //   $iosdevice=UserDevice::where('user_id',$request->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
            
                              // if (!empty($iosdevice)) 
                              // SiteHelper::sendIosPush($iosdevice, $message);
                            

                            $notification_category = NotificationCategory::where(['slug' => 'placed'])->first();

                            if($notification_category) {
                                
                                Notification::create([
                                    'notification_id' => $notification_category->id,
                                    'to_id' => $request->user_id,
                                    'order_id' => $order->id
                                ]);
                            }

                            $notification_category = NotificationCategory::where(['slug' => 'seller_order_placed'])->first();

                            $shop = BusinessShop::where('id',$order->shop_id)->first();


                            if($notification_category && $shop) {
                                
                                Notification::create([
                                    'notification_id' => $notification_category->id,
                                    'from_id' => $request->user_id,
                                    'to_id' => $shop->seller_id,
                                    'order_id' => $order->id
                                ]);

                                $message = array(
                                    'title' => 'New Order',
                                    'type' => 'new order',
                                    'order_id' => $order->id,
                                    'message' => 'You have a new order '.$order->order_no
                                  );
                
                                  $device_types=UserDevice::where('user_id',$shop->seller_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
                                
                                  if (!empty($device_types))
                                    SiteHelper::sendAndroidPush($device_types, $message);

                                
                            }
                        }
                        
                    }
                    
                    // if($grand_total < 50) {

                    //     DB::rollback();

                    //     $res = array(
                    //         'errorcode' => 1,
                    //         'data' => (object)[],
                    //         'message' => "Please purchase with greater than 50 Rs!"
                    //     );

                    //     return response()->json($res);
                    // }

                    

                    DB::commit(); 
                

                    $razOrderId = null;

                    if($request->payment_method == 'online') {
                        $data = array();
                        // $api_key = 'rzp_test_CCbSyEjgrTpxGW'; //test
                        // $api_secret = 'BUgj1gTMG7NJ24HF953GaBvs'; //test
 
                        $api_key = 'rzp_live_lZPTOytlOJXyl2'; //live
                        $api_secret = 'Dn4X1mu2TpMjF3pIDZ638w49'; //live

                        // dd($api_secret);
                        $api = new Api($api_key, $api_secret);
                        $razorAmount = $request->total_amount * 100;
                        
                        $raz_order = $api->order->create(array(
                            'amount' => $razorAmount,
                            'payment_capture' => 1,
                            'currency' => 'INR'
                            )
                          );
                         // dd($order);
                        $razOrderId = $raz_order['id'];
                        $data['razorpay_order_id'] = $razOrderId;
                        $data['amount'] = $tot_order_price;
                        $data['order_id'] = $order->id;
     
                        // $order->update(['razorpay_order_id' => $razOrderId]);

                        Transaction::create([
                            'raz_order_id' => $razOrderId,
                            'order_id' => $order->id,
                            'amount' => $tot_order_price,
                            'status' => 'pending'
                        ]);
                    }
                }

                $order_grand_total = 0;

                foreach ($order_array as $key => $order_array_id) {
                    $order = Order::where(['id' => $order_array_id])->first();

                    $order_sum = OrderProduct::where(['order_id' => $order_array_id])->sum('tot_price');

                    $order_discount = OrderProduct::where(['order_id' => $order_array_id])->sum('tot_discount');

                    $order_grand_total += $order_sum;

                    Order::where(['id' => $order_array_id])->update(['razorpay_order_id' => $razOrderId, 'discount' => $order_discount, 'amount' => $order_sum - $order->points, 'grand_total' => $order_sum]);
                }

                if($request->product_id == null) {
                    $delivery_fee_below = Settings::where('slug', 'delivery_charge_below')->first();
                    $delivery_fee_between = Settings::where('slug', 'delivery_charge_between')->first();
                    $delivery_fee_above = Settings::where('slug', 'delivery_charge_above')->first();

                    if($delivery_fee_below->max_order_price > $order_grand_total) {

                        $delivery_fee = $delivery_fee_below->price;
                    } elseif($delivery_fee_between->min_order_price <= $order_grand_total && $delivery_fee_between->max_order_price > $order_grand_total) {
                        $delivery_fee = $delivery_fee_between->price;
                    } elseif($delivery_fee_above->max_order_price <= $order_grand_total) {
                        $delivery_fee = $delivery_fee_above->price;
                    }

                    $order->update(['delivery_fee' => $delivery_fee, 'amount' => $order->amount + $delivery_fee, 'grand_total' => $order->grand_total + $delivery_fee]);
                }

                if($request->payment_method != 'online') {

                    $message = array(
                        'title' => 'Order Placed',
                        'type' => 'order',
                        'order_id' => $order->id,
                        'message' => 'Your order has been placed.'
                      );

                      $device_types=UserDevice::where('user_id',$order->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                      if (!empty($device_types))
                        SiteHelper::sendAndroidPush($device_types, $message);

                    //   $iosdevice=UserDevice::where('user_id',$order->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                      // if (!empty($iosdevice)) 
                      // SiteHelper::sendIosPush($iosdevice, $message);

                }

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }

        return response()->json($res);
    }

    /**
     * Payment Response
     * @return Renderable
     */
    public function paymentResponse(Request $request)
    {
        $rules = array(
            'user_id' => 'required',
            'product_id' => 'integer|sometimes|required',
            'razorpay_order_id' => 'required',
            'order_id' => 'required',
            'raz_transaction_id' => 'required',
            'total_amount' => 'required',
            'status' => 'required|in:0,1'
        );

        $validator = Validator::make($request->all() , $rules);

        if ($validator->fails()) {
            $res = array(
                'errorcode' => 3,
                'message' => $validator->messages()
            );

            return response()->json($res);

        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://rzp_live_lZPTOytlOJXyl2:Dn4X1mu2TpMjF3pIDZ638w49@api.razorpay.com/v1/payments/".$request->raz_transaction_id);

        // curl_setopt($ch, CURLOPT_URL, "https://rzp_test_CCbSyEjgrTpxGW:BUgj1gTMG7NJ24HF953GaBvs@api.razorpay.com/v1/payments/".$request->raz_transaction_id);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
   
        $output = curl_exec($ch); 
        
        $raz_result = json_decode($output);
        
        if(isset($raz_result->error)) {
            $res = array(
                'errorcode' => 2,
                'data' => (object)[],
                'message' => "Invalid Transaction!"
            );

            return response()->json($res);
        }

        curl_close($ch);

        $payment_exist = Transaction::where(['raz_order_id' => $request->razorpay_order_id, 'amount' => $request->total_amount, 'status' => 'pending'])->first();
        
        if(!$payment_exist) {
            $res = array(
                'errorcode' => 1,
                'data' => (object)[],
                'message' => "Invalid payment!"
            );

            return response()->json($res);
        } 

        if($request->status == 1) {
            $status = Status::where(['slug' => 'ordered', 'is_active' => 1])->first();
            $payment_status = 'success';

            if($request->product_id != null) {
                Cart::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->delete();
            } else {
                Cart::where(['user_id' => $request->user_id])->delete();
            }

        } else {
            $status = Status::where(['slug' => 'failed', 'is_active' => 1])->first();
            $payment_status = 'failed';
        }

        $payment_exist->update([
                'transaction_id' => $request->raz_transaction_id, 'status' => $payment_status
            ]);


        if($request->product_id != null) {
            $order = Order::where(['id' => $request->order_id])->first();
            $user = User::where(['id' => $order->user_id])->first();

            $order->update(['status_id' => $status->id]);

            //point return
            $point_history = PointHistory::where(['user_id' => $order->user_id, 'is_credit' => 0, 'order_id' => $order->id, 'slug' => 'debited_by_purchasing_order', 'is_valid' => 0])->first();

            if($point_history) {
                $current_points = $user->wallet - $point_history->points;

                $user->update(['wallet' => $current_points]);

                $point_history->update(['is_valid' => 1]);
            }
            
            //end 

            $point = PointHistory::where(['user_id' => $request->user_id, 'is_credit' => 1, 'order_id' => $order->id, 'slug' => 'credited_by_purchasing_order', 'is_valid' => 0])->first();

            $message = array(
                'type' => 'refer',
                'message' => $point->points.' points earned by purchasing order.'
            );

            $device_types=UserDevice::where('user_id',$request->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

            if (!empty($device_types))
                SiteHelper::sendAndroidPush($device_types, $message);


            $notification_category = NotificationCategory::where(['slug' => 'placed'])->first();

            if($notification_category) {
                
                Notification::create([
                    'notification_id' => $notification_category->id,
                    'to_id' => $request->user_id,
                    'order_id' => $order->id
                ]);
            }

            $notification_category = NotificationCategory::where(['slug' => 'seller_order_placed'])->first();

            $shop = BusinessShop::where('id', $order->shop_id)->first();


            if($notification_category && $shop) {
                
                Notification::create([
                    'notification_id' => $notification_category->id,
                    'from_id' => $request->user_id,
                    'to_id' => $shop->seller_id,
                    'order_id' => $order->id
                ]);

                $message = array(
                    'title' => 'New Order',
                    'type' => 'new order',
                    'order_id' => $order->id,
                    'message' => 'You have a new order '.$order->order_no
                  );

                $device_types=UserDevice::where('user_id',$shop->seller_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                if (!empty($device_types))
                  SiteHelper::sendAndroidPush($device_types, $message);

                // $iosdevice=UserDevice::where('user_id',$order->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                // if (!empty($iosdevice)) 
                //     SiteHelper::sendIosPush($iosdevice, $message);
            }

        } else {

            $orders = Order::where(['razorpay_order_id' => $request->razorpay_order_id])->get();

            foreach ($orders as $key => $order) {
                
                $order = Order::where(['id' => $order->id])->first();
                $user = User::where(['id' => $order->user_id])->first();

                $order->update(['status_id' => $status->id]);

                //point return
                $point_history = PointHistory::where(['user_id' => $order->user_id, 'is_credit' => 0, 'order_id' => $order->id, 'slug' => 'debited_by_purchasing_order', 'is_valid' => 0])->first();

                if($point_history) {
                    $current_points = $user->wallet - $point_history->points;

                    $user->update(['wallet' => $current_points]);

                    $point_history->update(['is_valid' => 1]);
                }
                //end 

                $point = PointHistory::where(['user_id' => $request->user_id, 'is_credit' => 1, 'order_id' => $order->id, 'slug' => 'credited_by_purchasing_order', 'is_valid' => 0])->first();

                $message = array(
                    'type' => 'refer',
                    'message' => $point->points.' points earned by purchasing order.'
                  );

                  
                $device_types=UserDevice::where('user_id', $request->user_id)->where('device_type', 1)->where('logout_time', '=', NULL)->pluck('device_id')->toArray();

                if (!empty($device_types))
                    SiteHelper::sendAndroidPush($device_types, $message);

                //   $iosdevice=UserDevice::where('user_id',$request->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                  // if (!empty($iosdevice)) 
                  // SiteHelper::sendIosPush($iosdevice, $message);
                

                $notification_category = NotificationCategory::where(['slug' => 'placed'])->first();

                if($notification_category) {
                    
                    Notification::create([
                        'notification_id' => $notification_category->id,
                        'to_id' => $request->user_id,
                        'order_id' => $order->id
                    ]);
                }

                $notification_category = NotificationCategory::where(['slug' => 'seller_order_placed'])->first();

                $shop = BusinessShop::where('id', $order->shop_id)->first();


                if($notification_category && $shop) {
                    
                    Notification::create([
                        'notification_id' => $notification_category->id,
                        'from_id' => $request->user_id,
                        'to_id' => $shop->seller_id,
                        'order_id' => $order->id
                    ]);

                    $message = array(
                        'title' => 'New Order',
                        'type' => 'new order',
                        'order_id' => $order->id,
                        'message' => 'You have a new order '.$order->order_no
                      );

                      $device_types=UserDevice::where('user_id',$shop->seller_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
                    
                      if (!empty($device_types))
                        SiteHelper::sendAndroidPush($device_types, $message);

                    
                }


                $message = array(
                    'title' => 'Order Placed',
                    'type' => 'order',
                    'order_id' => $order->id,
                    'message' => 'Your order has been placed.'
                  );

                $device_types=UserDevice::where('user_id', $order->user_id)->where('device_type', 1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                if (!empty($device_types))
                SiteHelper::sendAndroidPush($device_types, $message);

                //   $iosdevice=UserDevice::where('user_id',$order->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                // if (!empty($iosdevice)) 
                // SiteHelper::sendIosPush($iosdevice, $message);
            }
        }
        

        $res = array(
            'errorcode' => 0,
            'data' => (object)[],
            'message' => "Success!"
        );

        return response()->json($res);
    }

    //to get razorpay response
    public function paymentVerification(Request $request)
    {
        Log::debug('Webhook');
        Log::debug('response array '.json_encode($request->all()));
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);
        
        if ($data['event'] == 'payment.captured' || $data['event'] == 'payment.failed') {
            $status=$data['payload']['payment']['entity']['status'];
            $raz_order_id=$data['payload']['payment']['entity']['order_id'];
            $order_status = Status::where(['slug' => 'ordered', 'is_active' => 1])->first();
            $orders = Order::where(['razorpay_order_id' => $raz_order_id])->get();

            if($status=='captured') {

                foreach ($orders as $key => $order) {
                    $user = User::where(['id' => $order->user_id])->first();

                    $order = Order::where(['id' => $order->id])->first();

                    if($order->orderStatus->slug == 'pending')
                        $order->update(['status_id' => $order_status->id]);

                    $point_history = PointHistory::where(['user_id' => $order->user_id, 'is_credit' => 0, 'order_id' => $order->id, 'slug' => 'debited_by_purchasing_order', 'is_valid' => 0])->first();

                    $current_points = $user->wallet - $point_history->points;

                    $user->update(['wallet' => $current_points]);

                    $point_history->update(['is_valid' => 1]);

                    $products = $order->orderProducts;

                    if(count($products) > 0) {

                        foreach ($products as $key => $product) {
                            $cart = Cart::where(['user_id' => $order->user_id, 'product_id' => $product->product_id])->first();

                            if($cart) {
                                $cart->delete();
                            }
                        }
                    }
                }

            } else {

                foreach ($orders as $key => $order) {
                    $order_products = OrderProduct::where(['order_id' => $order->id])->get();

                    foreach ($order_products as $key => $order_product) {
                        $product = Product::where(['id' => $order_product->product_id])->first();

                        $product->update(['stock' => $product->stock + $order_product->product_quantity]);
                    }
                }
            }

            Log::debug('success : '.$raz_order_id);

            echo 200;
        } else {
            Log::debug('failed : '.json_encode($request->all()));
        }
        
    }

    //To get purchase history
    public function purchaseHistory(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array();
            
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $orders = Order::with('orderProducts')->whereHas('orderProducts')->where(['user_id' => $request->user_id, 'is_active' => 1])->where('status_id', '!=', 1)->orderBy('created_at', 'DESC')->get();
                
                foreach ($orders as $key => $order) {
                    $seller_info = null;
                    $product_image = null;
                    $items = array();

                    $date = new DateTime($order->created_at);
                    $order_date = $date->format('M').' '.$date->format('d').' '.$date->format('Y');

                    if($order->delivery_date != null) {
                        $delivery_date = new DateTime($order->delivery_date);
                        $delivery_expected_date = $delivery_date->format('M').' '.$delivery_date->format('d').' '.$delivery_date->format('Y');
                    } else {
                        $delivery_expected_date = null;
                    }
                    

                    foreach ($order->orderProducts as $key => $order_product) {
                        
                        $item = Product::where(['id' => $order_product->product_id])->withTrashed()->first();
                        // dd($item->thump_image);
                        if($seller_info == null)
                            $seller_info = $order->shop->name == null ? null : $order->shop->name;

                        if($product_image == null) {

                            if($item) {
                                $product_image = $item->thump_image == null ? null : asset('storage/app').'/'.$item->thump_image;
                            }
                        }

                       
                        if($item) {

                            $attr_values = null;
                            $not_array = array('color', 'Color', 'COLOR', 'colour', 'Colour', 'COLOUR');

                            $attributes = ProductAttribute::whereHas('attributeData', function($q) use($not_array){
                                $q->whereNotIn('field_name', $not_array);
                            })->where(['product_id' => $order_product->product_id])->get()->pluck('attr_value')->toArray();

                            if(count($attributes) > 0) {
                                $attributes = array_unique($attributes, SORT_REGULAR);
                                $attr_values = implode(', ', $attributes);
                            }
                            
                            if($attr_values == null) 
                                $product_name = optional($item)->name;
                            else
                                $product_name = optional($item)->name.'('.$attr_values.')';

                            $items[] = array(
                                'product_id' => $order_product->product_id,
                                'quantity' => $order_product->product_quantity,
                                'product_name' => $product_name,
                                'brand' => optional($item->brand)->name,
                                'product_image' => $product_image
                            );
                        }
                    }

                    $data[] = array(
                        'id' => $order->id,
                        'order_id' => $order->order_no,
                        'payment_method' => $order->payment_method,
                        'product_image' => $product_image,
                        'seller_info' => $seller_info,
                        'instore' => $order->instore,
                        'items' => $items,
                        'order_date' => $order_date,
                        'delivery_expected_date' => $delivery_expected_date,
                        'price' => 'Rs '.$order->amount,
                        'points' => $order->points,
                        'order_status_slug' => $order->orderStatus->slug,
                        'is_rejected' => $order->orderStatus->slug =='rejected' ? true : false,
                        'reject_reason' => $order->reason,
                        'order_status' => $order->orderStatus->name
                    );
                }

                $data = array_unique($data, SORT_REGULAR);
                $data = app('Modules\Api\Http\Controllers\CustomerApiController')->paginate($data, $request->batchSize);

                $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To get order details
    public function orderDetails(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'order_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array();
            $items = array();
            $sub_total = 0;
            
            $payment_details = array();

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $order = Order::with('orderProducts')->whereHas('orderProducts')->where(['id' => $request->order_id, 'is_active' => 1])->first();

                if($order) {
                    $seller_info = optional($order->shop)->name == null ? null : optional($order->shop)->name;

                    $date = new DateTime($order->created_at);
                    $order_date = $date->format('M').' '.$date->format('d').' '.$date->format('Y');

                    if($order->delivery_date != null) {
                        $delivery_date = new DateTime($order->delivery_date);
                        $delivery_expected_date = $delivery_date->format('M').' '.$delivery_date->format('d').' '.$delivery_date->format('Y');
                    } else {
                        $delivery_expected_date = null;
                    }
                    
                    
                    $data['order_id'] = $order->order_no;
                    $data['seller_info'] = $seller_info;
                    $data['instore'] = $order->instore;
                    $data['scheduled_date'] = $delivery_expected_date;
                    $data['order_status'] = $order->orderStatus->name;

                    foreach ($order->orderProducts as $key => $order_product) {
                        $item = Product::where(['id' => $order_product->product_id])->withTrashed()->first();

                        if($item) {
                            $attr_values = null;

                            $not_array = array('color', 'Color', 'COLOR', 'colour', 'Colour', 'COLOUR');

                            $attributes = ProductAttribute::whereHas('attributeData', function($q) use($not_array){
                                $q->whereNotIn('field_name', $not_array);
                            })->where(['product_id' => $order_product->product_id])->withTrashed()->get()->pluck('attr_value')->toArray();

                            if(count($attributes) > 0) {
                                $attributes = array_unique($attributes, SORT_REGULAR);
                                $attr_values = implode(', ', $attributes);
                            }
                            
                            if($attr_values == null) 
                                $product_name = optional($item)->name;
                            else
                                $product_name = optional($item)->name.'('.$attr_values.')';

                            $product_image = $item->thump_image == null ? null : asset('storage/app').'/'.$item->thump_image;

                            $items[] = array(
                                'product_id' => $order_product->product_id,
                                'quantity' => $order_product->product_quantity,
                                'product_name' => $product_name,
                                'brand' => optional($item->brand)->name,
                                'product_image' => $product_image,
                                'product_price' => $order_product->product_price
                            );

                            $sub_total += $order_product->tot_price;
                        }
                        
                    }
                    $data['items'] = $items;

                    $payment_details[] = array(
                        'text' => 'Sub Total',
                        'value' => 'Rs '.$sub_total
                    );

                    $payment_details[] = array(
                        'text' => 'Delivery Fee',
                        'value' => 'Rs '.$order->delivery_fee
                    );

                    $payment_details[] = array(
                        'text' => 'Points',
                        'value' => $order->points
                    );

                    $data['payment_details'] = $payment_details;
                    $data['payment_method'] = $order->payment_method;
                    $data['grand_total'] = $order->amount;

                    $address = $order->orderAddresses;

                    // dd($address);

                    $delivery_address = array(
                        'order_address_id' => $address->id,
                        'name' => $address->name,
                        'build_name' => $address->build_name,
                        'area' => $address->area,
                        'location' => $address->location,
                        'landmark' => $address->landmark,
                        'latitude' => $address->latitude,
                        'longitude' => $address->longitude,
                        'mobile' => $address->mobile,
                        'pincode' => $address->pincode,
                        'type' => $address->type
                    );

                    $data['delivery_address'] = $delivery_address;

                    $data['is_rejected'] = $order->orderStatus->slug =='rejected' ? true : false;
                    $data['reject_reason'] = $order->reason;
                }

                    // $data[] = array(
                    //     'order_id' => $order->id,
                    //     'product_image' => $product_image,
                    //     'seller_info' => $seller_info,
                    //     'items' => $items,
                    //     'order_date' => $order_date,
                    //     'delivery_expected_date' => $delivery_expected_date,
                    //     'price' => 'Rs '.$order->amount,
                    //     'order_status_slug' => $order->orderStatus->slug,
                    //     'order_status' => $order->orderStatus->name
                    // );

                // $data = array_unique($data, SORT_REGULAR);
                // $data = app('Modules\Api\Http\Controllers\CustomerApiController')->paginate($data, $request->batchSize);

                $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To get order details
    public function cancelOrder(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'order_id' => 'required|integer',
            'reason' => 'nullable'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $order = Order::where(['id' => $request->order_id])->first();

                if($order) {

                    $cancelled_status = Status::where(['slug' => 'cancelled', 'is_active' => 1])->first();

                    if($order->status_id == $cancelled_status->id) {
                        $res = array(
                            'errorcode' => 0,
                            'data' => (object)[],
                            'message' => "Already cancelled!"
                        );

                        return response()->json($res);
                    }

                    if($cancelled_status) {
                        $order->update([
                            'status_id' => $cancelled_status->id,
                            'reason' => $request->reason
                        ]);

                        OrderStatus::create([
                            'order_id' => $order->id,
                            'status_id' => $cancelled_status->id
                        ]);
                    }

                    //Stock revert
                    $order_products = OrderProduct::where(['order_id' => $order->id])->get();

                    foreach ($order_products as $key => $order_product) {
                        $product = Product::where(['id' => $order_product->product_id])->first();

                        $product->update(['stock' => $product->stock + $order_product->product_quantity]);
                    }

                    $order->update(['is_reverted' => 1]);
                    //Stock revert

                    //Point Reverted
                    $point = PointHistory::where(['order_id' => $order->id, 'slug' => 'debited_by_purchasing_order', 'is_valid' => 1])->first();
                    
                    if($point) {
                        $user = User::where(['id' => $order->user_id])->first();

                        $user->update(['wallet' => $user->wallet + $point->points]);

                        $point->delete();
                    }
                    //End Point Reverted

                    $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Order not exist!"
                    );
                }

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //re order
    public function reOrder(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'order_id' => 'required|integer',
            'address_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                
                $address = UserAddress::where(['id' => $request->address_id])->first();

                if($address) {
                    $latitude = $address->latitude;
                    $longitude = $address->longitude;

                    $distance = Settings::where(['slug' => 'max_delivery_distance'])->first();

                    $shops = BusinessCategoryShop::select('shop_id')->get()->toArray();
                    
                    if($latitude != null || $longitude != null) {
                        $shop_value = BusinessShop::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                                ->having('distance', '<', $distance->value)
                                ->where('name', 'LIKE', '%' . $request->search . '%')
                                // ->where('id', $shop->shop_id)
                                ->where('is_active', 1)
                                ->whereIn('id', $shops)
                                // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                                ->orderBy('distance')
                                ->first();
                    } else {
                        
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Please set your location in this address!"
                        );

                        return response()->json($res);
                    }

                    if(!$shop_value) {

                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Delivery not available!"
                        );

                        return response()->json($res);
                    }
                } else {
                    $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Incorrect Address!"
                        );

                    return response()->json($res);
                }

                // foreach ($items as $key => $item_list) { 
                    $out_of_stocks = array();
                    $tot_price = 0;
                    $tot_discount = 0;
                    $delivery_fee = 0;

                    DB::beginTransaction();
                    $exist_order = Order::where(['id' => $request->order_id])->first();

                    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $order_no = '';

                    for ($i = 0; $i < 10; $i++) {
                        $order_no .= $characters[rand(0, $charactersLength - 1)];
                    }

                    $date = new DateTime('now');
                    $date->modify('+1 day');
                    $delivery_date = $date->format('Y').'-'.$date->format('m').'-'.$date->format('d');
                    $delivery_expected_date = $delivery_date;

                    // $accepted_delivery_time = Settings::where(['slug' => 'accepted_delivery_time'])->first();
                    // $cur_time = Carbon::now()->format('H:i:s');
                    
                    // if ($cur_time >= $accepted_delivery_time->value) {
                    //     $date->modify('+1 day');
                    //     $delivery_expected_date = $date->format('Y').'-'.$date->format('m').'-'.$date->format('d');
                    // } else {
                    //     $delivery_expected_date = $delivery_date;
                    // }

                    $order = Order::create([
                        'order_no' => $order_no,
                        'user_id' =>  $request->user_id,
                        'shop_id' => $exist_order->shop_id,
                        'instore' => $exist_order->instore,
                        'date_purchased' => Carbon::now(),
                        'delivery_date' => $delivery_expected_date
                    ]);

                    $exist_order_products = OrderProduct::where(['order_id' => $request->order_id])->get();

                    foreach ($exist_order_products as $item) {
                        
                        $product_tot_discount = null;
                        $product_tot_price = 0;

                        $product = Product::where(['id' => $item->product_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '>=', $item->product_quantity)->first();
                        // dd($product);
                        if($product) {

                            $product->update(['stock' => $product->stock - $item->product_quantity]);
                            
                            $product_data = $this->offerPrice($product->id);

                            if($product_data['new_price'] != null) {
                                $product_price = $product_data['new_price'];
                                $discount = $product_data['discount'];
                            } else {
                                $product_price = $product->price;
                                $discount = null;
                            }

                            if($discount != null)
                                $product_tot_discount = $discount * $item->product_quantity;

                            $product_tot_price = $product_price * $item->product_quantity;

                            OrderProduct::create([
                                'order_id' => $order->id,
                                'product_id' => $product->id,
                                'product_price' => $product_price,
                                'product_discount' => $discount,
                                'product_quantity' => $item->product_quantity,
                                'tot_price' => $product_tot_price,
                                'tot_discount' => $product_tot_discount
                            ]);

                            $tot_discount = $tot_discount + $product_tot_discount;
                            $tot_price = $tot_price + $product_tot_price;

                        } else {
                            $out_of_stocks[] = $item->product_id;
                        }
                    }

                    if(count($out_of_stocks) == count($exist_order_products)) {
                        DB::rollback();

                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Your cart contains unavailable product(s). Please review the cart items!"
                        );

                        return response()->json($res);
                    }

                    $status = Status::where(['slug' => 'ordered', 'is_active' => 1])->first();

                    if($status)
                        $status_id = $status->id;
                    else
                        $status_id = null;

                    $tot_price = $tot_price + $delivery_fee;

                    $order->update([
                        'discount' => $tot_discount,
                        'status_id' => $status_id,
                        'delivery_fee' => $delivery_fee,
                        'amount' => $tot_price
                    ]);

                    OrderStatus::create([
                        'order_id' => $order->id,
                        'status_id' => $status_id
                    ]);

                    OrderAddress::create([
                        'order_id' => $order->id,
                        'name' => $address->name,
                        'build_name' => $address->build_name,
                        'area' => $address->area,
                        'location' => $address->location,
                        'landmark' => $address->landmark,
                        'latitude' => $address->latitude,
                        'longitude' => $address->longitude,
                        'mobile' => $address->mobile,
                        'pincode' => $address->pincode,
                        'type' => $address->type
                    ]);

                    Transaction::create([
                        'transaction_id' => 'Test123',
                        'order_id' => $order->id,
                        'amount' => $tot_price,
                        'status' => 'success'
                    ]);

                    DB::commit();

                // }

                $res = array(
                    'errorcode' => 0,
                    'data' => (object)[],
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }

        return response()->json($res);
    }

    //Buy anything
    public function buyAnything(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'deliver_addressid' => 'required|integer',
            'name' => 'string',
            'mobile' => 'required|string',
            'buy_items' => 'required',
            'image' => 'mimes:jpeg,jpg,png|max:10000',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $image = null;
            
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {

                $address = UserAddress::where(['id' => $request->deliver_addressid])->first();

                if($address) {

                    $latitude = $address->latitude;
                    $longitude = $address->longitude;

                    $distance = Settings::where(['slug' => 'max_delivery_distance'])->first();

                    $shops = BusinessCategoryShop::select('shop_id')->get()->toArray();
                    
                    if($latitude && $longitude) {
                        $shop_value = BusinessShop::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                            ->having('distance', '<', $distance->value)
                            ->orWhere('name', 'LIKE', '%' . $request->search . '%')
                            ->whereHas('products')
                            // ->where('id', $shop->shop_id)
                            ->where('is_active', 1)
                            ->whereIn('id', $shops)
                            // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                            ->orderBy('distance')
                            ->first();

                        if($shop_value) {

                            if($request->file('image')){
                                $imageName = time().trim($request->image->getClientOriginalName());
                                $imageName = str_replace(' ', '', $imageName);
                                $request->image->move(storage_path('app/buy_anything'), $imageName);  
                                $image = 'buy_anything/'.$imageName;
                            }

                            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $charactersLength = strlen($characters);
                            $order_no = '';

                            for ($i = 0; $i < 10; $i++) {
                                $order_no .= $characters[rand(0, $charactersLength - 1)];
                            }

                            $status = Status::where(['slug' => 'pending', 'is_active' => 1])->first();

                            $buy_address = BuyAddress::create([
                                            'name' => $address->name,
                                            'build_name' => $address->build_name,
                                            'street' => $address->name,
                                            'area' => $address->area,
                                            'location' => $address->location,
                                            'landmark' => $address->landmark,
                                            'pincode' => $address->pincode,
                                            'latitude' => $address->latitude,
                                            'longitude' => $address->longitude,
                                            'mobile' => $address->mobile,
                                            'type' => $address->type,
                                        ]);
                            
                            $order = BuyAnything::create([
                                    'user_id' => $request->user_id,
                                    'order_no'  => 'BUY_'.$order_no,
                                    'deliver_addressid' => $buy_address->id,
                                    'name' => $request->name,
                                    'mobile' => $request->mobile,
                                    'shop_name' => $request->shop_name,
                                    'shop_location' => $request->shop_location,
                                    'shop_latitude' => $request->shop_latitude,
                                    'shop_longitude' => $request->shop_longitude,
                                    'buy_items' => $request->buy_items,
                                    'image' => $image,
                                    'order_status' => $status->id,
                                    'status' => 0
                                ]);

                            $notification_category = NotificationCategory::where(['slug' => 'buy_anything'])->first();

                            if($notification_category) {
                                
                                Notification::create([
                                    'notification_id' => $notification_category->id,
                                    'to_id' => $request->user_id,
                                    'order_id' => $order->id
                                ]);
                            }

                            $res = array(
                                    'errorcode' => 0,
                                    'data' => (object)[],
                                    'message' => "Success!"
                                );

                        } else {
                            $res = array(
                                'errorcode' => 1,
                                'data' => (object)[],
                                'message' => "Delivery not available!"
                            );
                        }

                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Please add your location!"
                    );
                }
                
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Address not exist!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    public function buyAnythingList(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user_id = $request->user_id;
            $buys = BuyAnything::with('orderStatus')->where('user_id',$user_id)->orderBy('id','desc')->get();

            $data = [];
            $addr ='';
            $flag = 0;

        

                if($buys->count()>0)
                {

                    foreach($buys as $buy)
                    {
                        $addr ='';
                        $date = new DateTime($buy->created_at);
                        $order_date = $date->format('M').' '.$date->format('d') .','. date('h:i A', strtotime($buy->created_at));

                        $address = BuyAddress::where('id', $buy->deliver_addressid)->first();

                        if(!$address)
                            $address = UserAddress::where('id', $buy->deliver_addressid)->first();
                    
                                if($address)
                                {
                                    $addr = $address->build_name .','. $address->area;
                                    
                                }
                            

                         $data [] = [
                            'id' => $buy->id,
                            'order_id' => $buy->order_no,
                            'order_date_time' =>  $order_date,
                            'deliver_address' => $addr,
                            
                            'status' => $buy->orderStatus->name
                        ];
                    }

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Orders not exist!"
                );
            }
        }
        return response()->json($res);
    }


    public function buyAnythingDetail(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'order_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user_id = $request->user_id;
            $order_id = $request->order_id;
            $buy = BuyAnything::with('orderStatus')->where(['id' => $order_id ,'user_id' => $user_id])->first();

            if($buy)
            {
                $data = [];
                $addr ='';
                $data['delivery Address'] ='';
                $data['ordered_items'] = '';

              
                $address = BuyAddress::where('id',$buy->deliver_addressid)->first();
                
                if(!$address)
                    $address = UserAddress::where('id', $buy->deliver_addressid)->first();

                if($address)
                {
                    if($address->type ==0)
                    {
                        $type = 'Home';
                    }else if($address->type ==1)
                    {
                        $type = 'Office';
                    }else{
                        $type = 'Other';
                    }
                        $data['delivery Address'] = [
                            'address_id' => $buy->deliver_addressid,
                            'build_name' => $address->build_name,
                            'area' => $address->area,
                            'address_type' =>  $type,
                            'location' => $address->location,
                            'latitude' => $address->latitude,
                            'longitude' => $address->longitude,
                        ];
                    
                }

                     
                    

                        if(!is_null($buy->buy_items))
                        {
                
                        $items =json_decode($buy->buy_items);

                            if(count($items)>0)
                            {
                                foreach($items as $key=>$val)
                                {
                                    if($val != '')
                                    {
                                        $del_items[] =[
                                            'item_name' => $val->name,
                                            'quantity' => $val->quantity
                                        ];
                                    }
                                }

                                $data['ordered_items'] = $del_items;
                            }
                        }

                    $data['order_id'] = $buy->order_no;
                    $data['name'] = $buy->name;
                    $data['contact_number'] = $buy->mobile;
    
                    $date = new DateTime($buy->created_at);
                    $order_date = $date->format('M').' '.$date->format('d') .','. date('h:i A', strtotime($buy->created_at));
    
                    $data['ordered_date'] = $order_date;
    
                    $data['image'] = $buy->image == null ? null : asset('storage/app').'/'.$buy->image;

                    $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );
    
              

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Orders not exist!"
                );
            }

        }

        return response()->json($res);
    }

    //Deliver anything
    public function deliverAnything(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'deliver_addressid' => 'required|integer',
            'pickup_addressid' => 'required|integer',
            'name' => 'string',
            'mobile' => 'required|string',
            'buy_items' => 'required',
            'note' => 'sometimes|string',
            'image' => 'mimes:jpeg,jpg,png|max:10000',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $image = null;
            
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {

                $address = UserAddress::where(['id' => $request->deliver_addressid])->first();
                $pic_address = UserAddress::where(['id' => $request->pickup_addressid])->first();

                if($address && $pic_address) {

                    $latitude = $address->latitude;
                    $longitude = $address->longitude;

                    $distance = Settings::where(['slug' => 'max_delivery_distance'])->first();

                    $shops = BusinessCategoryShop::select('shop_id')->get()->toArray();
                    
                    if($latitude && $longitude) {
                        $shop_value1 = BusinessShop::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                            ->having('distance', '<', $distance->value)
                            ->orWhere('name', 'LIKE', '%' . $request->search . '%')
                            ->whereHas('products')
                            // ->where('id', $shop->shop_id)
                            ->where('is_active', 1)
                            ->whereIn('id', $shops)
                            // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                            ->orderBy('distance')
                            ->first();

                      
                    } else {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Please add your deliver location!"
                        );
                        return response()->json($res);
                    }

                    $latitude = $pic_address->latitude;
                    $longitude = $pic_address->longitude;

                    $distance = Settings::where(['slug' => 'max_delivery_distance'])->first();

                    $shops = BusinessCategoryShop::select('shop_id')->get()->toArray();
                    
                    if($latitude && $longitude) {
                        $shop_value2 = BusinessShop::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                            ->having('distance', '<', $distance->value)
                            ->orWhere('name', 'LIKE', '%' . $request->search . '%')
                            ->whereHas('products')
                            // ->where('id', $shop->shop_id)
                            ->where('is_active', 1)
                            ->whereIn('id', $shops)
                            // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                            ->orderBy('distance')
                            ->first();

                      
                    } else {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Please add your pickup location!"
                        );
                        return response()->json($res);
                    }
                

                if($shop_value1 && $shop_value2)
                {
                if($request->file('image')){
                    $imageName = time().trim($request->image->getClientOriginalName());
                    $imageName = str_replace(' ', '', $imageName);
                    $request->image->move(storage_path('app/deliver_anything'), $imageName);  
                    $image = 'deliver_anything/'.$imageName;
                }

                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $order_no = '';

                for ($i = 0; $i < 10; $i++) {
                    $order_no .= $characters[rand(0, $charactersLength - 1)];
                }

                $status = Status::where(['slug' => 'pending', 'is_active' => 1])->first();

                $deliver_address = DeliverAddress::create([
                                    'name' => $address->name,
                                    'build_name' => $address->build_name,
                                    'street' => $address->name,
                                    'area' => $address->area,
                                    'location' => $address->location,
                                    'landmark' => $address->landmark,
                                    'pincode' => $address->pincode,
                                    'latitude' => $address->latitude,
                                    'longitude' => $address->longitude,
                                    'mobile' => $address->mobile,
                                    'type' => $address->type,
                                ]);

                $deliver_pick_address = DeliverAddress::create([
                                        'name' => $pic_address->name,
                                        'build_name' => $pic_address->build_name,
                                        'street' => $pic_address->name,
                                        'area' => $pic_address->area,
                                        'location' => $pic_address->location,
                                        'landmark' => $pic_address->landmark,
                                        'pincode' => $pic_address->pincode,
                                        'latitude' => $pic_address->latitude,
                                        'longitude' => $pic_address->longitude,
                                        'mobile' => $pic_address->mobile,
                                        'type' => $pic_address->type,
                                    ]);

                $order = DeliverAnything::create([
                        'user_id' => $request->user_id,
                        'order_no' => 'DELIVER_'.$order_no,
                        'deliver_addressid' => $deliver_address->id,
                        'pickup_addressid' => $deliver_pick_address->id,
                        'name' => $request->name,
                        'mobile' => $request->mobile,
                        'buy_items' => $request->buy_items,
                        'shop_name' => $request->shop_name,
                        'shop_location' => $request->shop_location,
                        'shop_latitude' => $request->shop_latitude,
                        'shop_longitude' => $request->shop_longitude,
                        'note' => $request->note,
                        'image' => $image,
                        'order_status' => $status->id,
                        'status' => 0
                    ]);

                $notification_category = NotificationCategory::where(['slug' => 'deliver_anything'])->first();

                if($notification_category) {
                    
                    Notification::create([
                        'notification_id' => $notification_category->id,
                        'to_id' => $request->user_id,
                        'order_id' => $order->id
                    ]);
                }

                $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );
                }else{

                    if(!$shop_value1 && !$shop_value2)
                    {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Delivery and Pickup not available!"
                        );
                    }else if(!$shop_value1){
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Delivery not available!"
                        );
                    }else{
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Pickup not available!"
                        );
                    }
                }
                }else{
                    if(!$address && !$pic_address)
                    {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Delivery and Pickup address not exist"
                        );
                    }else if(!$address){
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Delivery address not exist!"
                        );
                    }else{
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Pickup address not exist!"
                        );
                    }
                  
                }

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }


    public function deliverAnythingList(Request $request)
    {

        $rules = array(
            'user_id' => 'required|integer',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user_id = $request->user_id;
            $buys = DeliverAnything::with('orderStatus')->where('user_id',$user_id)->orderBy('id','desc')->get();

            $data = [];
            $addr ='';
            $flag = 0;

            if($buys->count()>0)
            {

                foreach($buys as $buy)
                {
                    $addr ='';
                    $pic_addr ='';
                    $date = new DateTime($buy->created_at);
                    $order_date = $date->format('M').' '.$date->format('d') .','. date('h:i A', strtotime($buy->created_at));

                    $address = DeliverAddress::where('id', $buy->deliver_addressid)->first();

                    if(!$address)
                        $address = UserAddress::where('id', $buy->deliver_addressid)->first();
                
                    if($address)
                    {
                        $addr = $address->build_name .','. $address->area;
                        
                    }

                    $pic_address = DeliverAddress::where('id',$buy->pickup_addressid)->first();

                    if(!$pic_address)
                        $pic_address = UserAddress::where('id', $buy->pickup_addressid)->first();
                
                            if($pic_address)
                            {
                                $pic_addr = $pic_address->build_name .','. $pic_address->area;
                                
                            }
                        

                    $data [] = [
                        'id' => $buy->id,
                        'order_id' => $buy->order_no,
                        'order_date_time' =>  $order_date,
                        'deliver_address' => $addr,
                        'pickup_address' =>  $pic_addr,
                        'status' => $buy->orderStatus->name
                    ];
                }

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Orders not exist!"
                );
            }
        }
        return response()->json($res);
    }

    public function deliverAnythingDetail(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'order_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user_id = $request->user_id;
            $order_id = $request->order_id;
            $buy = DeliverAnything::with('orderStatus')->where(['id' => $order_id ,'user_id' => $user_id])->first();

            if($buy)
            {
                $data = [];
                $addr ='';
                $data['delivery Address'] ='';
                $data['ordered_items'] = '';
                $data['pickup_address'] = '';

              
                $address = DeliverAddress::where('id',$buy->deliver_addressid)->first();

                if(!$address)
                    $address = UserAddress::where('id', $buy->deliver_addressid)->first();
                  
                        if($address)
                        {
                            if($address->type ==0)
                            {
                                $type = 'Home';
                            }else if($address->type ==1)
                            {
                                $type = 'Office';
                            }else{
                                $type = 'Other';
                            }
                                $data['delivery Address'] = [
                                    'address_id' => $buy->deliver_addressid,
                                    'build_name' => $address->build_name,
                                    'area' => $address->area,
                                    'address_type' => $type,
                                    'location' => $address->location,
                                    'latitude' => $address->latitude,
                                    'longitude' => $address->longitude,
                                ];
                            
                        }

                    $pic_address = DeliverAddress::where('id',$buy->pickup_addressid)->first();

                    if(!$pic_address)
                        $pic_address = UserAddress::where('id', $buy->pickup_addressid)->first();
                  
                        if($pic_address)
                        {
                            if($pic_address->type ==0)
                            {
                                $type = 'Home';
                            }else if($pic_address->type ==1)
                            {
                                $type = 'Office';
                            }else{
                                $type = 'Other';
                            }
                                $data['pickup_address'] = [
                                    'address_id' => $buy->pickup_addressid,
                                    'build_name' => $pic_address->build_name,
                                    'area' => $pic_address->area,
                                    'address_type' => $type,
                                    'location' => $pic_address->location,
                                    'latitude' => $pic_address->latitude,
                                    'longitude' => $pic_address->longitude,
                                ];
                            
                        }

                     
                    

                        if(!is_null($buy->buy_items))
                        {
                
                        $items =json_decode($buy->buy_items);

                            if(count($items)>0)
                            {
                                foreach($items as $key=>$val)
                                {
                                    if($val != '')
                                    {
                                        $del_items[] =[
                                            'item_name' => $val->name,
                                            'quantity' => $val->quantity
                                        ];
                                    }
                                }

                                $data['ordered_items'] = $del_items;
                            }
                        }

                    $data['order_id'] = $buy->order_no;
                    $data['name'] = $buy->name;
                    $data['contact_number'] = $buy->mobile;
    
                    $date = new DateTime($buy->created_at);
                    $order_date = $date->format('M').' '.$date->format('d') .','. date('h:i A', strtotime($buy->created_at));
    
                    $data['ordered_date'] = $order_date;
    
                    $data['image'] = $buy->image == null ? null : asset('storage/app').'/'.$buy->image;

                    $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );
    
              

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Orders not exist!"
                );
            }

        }

        return response()->json($res);
    }


    //To get favourite shops
    public function getFavShops(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            // 'shopid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $shops = array();
                $fav_shops = Wishlist::where(['user_id' => $request->user_id])->get();

                foreach ($fav_shops as $key => $fav_shop) {
                    
                    if($fav_shop->shopData) {
                    
                        if($fav_shop->shopData->is_active == 1) {
                            $shops[] = array(
                                'shop_id' => $fav_shop->shopData->id,
                                'shop_title' => $fav_shop->shopData->name,
                                'shop_image' => $fav_shop->shopData->image == null ? null : asset('storage/app').'/'.$fav_shop->shopData->image,
                                'shop_location' => $fav_shop->shopData->location
                            );
                        }
                    }
                }

                $res = array(
                        'errorcode' => 0,
                        'data' => $shops,
                        'message' => "Success!"
                    );

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To get favourite products
    public function getFavProducts(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            // 'shopid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $products = array();
                $fav_products = Wishlist::where(['user_id' => $request->user_id])->get();

                foreach ($fav_products as $key => $fav_product) {
                    $is_available = true;
                    $new_price = 0;
                    $percent_off = null;
                    
                    if($fav_product->productData) {
                    
                        if($fav_product->productData->is_active == 1) {

                            //Offer Calculation

                            $product_data = $this->offerPrice($fav_product->product_id);
                
                            if($product_data['new_price'] != null) {
                                $new_price = number_format($product_data['new_price'], 2);
                                $percent_off = $product_data['percent_off'].'%';
                            } else {
                                $new_price = null;
                                $percent_off = null;
                            }

                            //End of Offer calculation

                            if($fav_product->productData->shop)
                                $seller_info = $fav_product->productData->shop->name;
                            else
                                $seller_info = null;

                            $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $fav_product->product_id])->first();

                            if($wishlist)
                                $isWishlist = true;
                            else
                                $isWishlist = false;

                            $exist_parent_category = null;
                            $check_category = 0;

                            $exist_category = ProductCategory::where(['product_id' => $fav_product->product_id, 'shop_id' => $fav_product->productData->seller_id])->whereHas('categoryData')->first();

                            if($exist_category) {
                                $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                                if($exist_sub_category) {
                                    $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                                    if($exist_parent_category) {
                                        $check_category = 1;
                                    }
                                }
                            }

                            if($check_category == 0) {
                                $is_available = false;
                            }

                            $products[] = array(
                                'product_id' => $fav_product->product_id,
                                'product_name' => $fav_product->productData->name,
                                'product_brand' => $fav_product->productData->brand->name ?? '',
                                'stock' => $fav_product->productData->stock,
                                'product_image' => $fav_product->productData->thump_image == null ? null : asset('storage/app').'/'.$fav_product->productData->thump_image,
                                'seller_info' => $seller_info,
                                'product_description' => $fav_product->productData->description,
                                'new_price' => $new_price,
                                'old_price' => number_format($fav_product->productData->price, 2),
                                'percent_off' => $percent_off,
                                'isWishlist' => $isWishlist,
                                'is_available' => $is_available
                            );
                        }
                    }
                }

                $res = array(
                        'errorcode' => 0,
                        'data' => $products,
                        'message' => "Success!"
                    );

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //enquiry form
    public function postEnquiry(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'shop_id' => 'sometimes|integer',
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'location' => 'required',
            'mobile' => 'required',
            'product_detail' => 'required',
            'product_name' => 'required',
            'expected_purchase' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            
            Enquiry::create([
                'user_id' => $request->user_id,
                'shop_id' => $request->shop_id,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'location' => $request->location,
                'mobile' => $request->mobile,
                'product_detail' => $request->product_detail,
                'product_name' => $request->product_name,
                'expected_purchase' => $request->expected_purchase
            ]);

            $res = array(
                'errorcode' => 0,
                'data' => (object)[],
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }

    //Static data for enquiry related form
    public function getExpectedPurchase(Request $request)
    {
        $rules = array(
            // 'shop_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array('within 1 week', 'within 6 months', 'over an year');

            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }

    //Search result
    public function search(Request $request)
    {
        $rules = array(
            'user_id' => 'sometimes|integer',
            'shop_id' => 'sometimes|integer',
            'term' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $delivery_fee = 0;
            
            // $setting = Settings::where('slug', 'delivery_charge')->first();

            // if($setting){

            //     if($setting->price != null || $setting->price != 0) {
            //         $delivery_fee = $setting->price;
            //     }
            // }

            $latitude1 = null;
            $longitude1 = null;
            $distance = null;
            $time_to_reach = null;
            $products = array();
            $categories = array();
            $cart_count = 0;

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $latitude1 = $user->latitude;
                $longitude1 = $user->longitude;
            }

            $shop_list = array();
            $items = array();
            $data = array();
            $term = $request->term;

            if($request->term) {

                $exist_search = RecentSearch::where(['term' => $term])->first();

                if($exist_search) {
                    $exist_search->update(['count' => $exist_search->count + 1]);
                } else {
                    RecentSearch::create([
                        'user_id' => $user->id,
                        'term' => $term,
                        'count' => 1
                    ]);
                }
            }

            // $shops = BusinessShop::whereHas('products', function($q) use($term){
            //     $q->where('name', 'LIKE', '%' . $term . '%');
            // })->where(['is_active' => 1])->take(20)->get();

            if($request->shop_id != null) {
                $sellers[] = $request->shop_id;
            } else {

                $categories = BusinessCategory::where(['is_active' => 1])->where('parent_id', '!=', 0)->where('name', 'LIKE', $term . '%')->withTrashed()->get()->pluck('id');
                
                $sellers = Product::whereHas('shop', function($q) {
                    $q->where('is_active', 1);
                })->orWhereHas('categories', function($q) use($categories){
                    $q->whereIn('category_id', $categories);
                })->orWhereHas('brand', function($q) use($term) {
                    $q->where('name', 'LIKE', '%' . $term . '%');
                })->where(['is_active' => 1,'is_approved' => 1])->orWhere('name', 'LIKE', '%' . $term . '%')->where('type', '!=', 2)->get()->pluck('seller_id')->toArray();
            }
            
            // $sellers = Product::where(['is_active' => 1,'is_approved' => 1])->where('type', '!=', 2)->get()->pluck('seller_id')->toArray();

            $sellers = array_unique($sellers, SORT_REGULAR);
            // dd($sellers);

            foreach ($sellers as $key => $seller) {
                $shop_products = array();
                $shop_brand_products = array();
                $shop_name_products = array();
                $category_name_products = array();
                $products = array();

                $shop = BusinessShop::where(['id' => $seller, 'is_active' => 1])->first();
                // dump($seller);

                if($shop) {
                    $latitude2 =  $shop->latitude == null ? null : $shop->latitude;
                    $longitude2 = $shop->longitude == null ? null : $shop->longitude;

                    if($latitude1 != null && $longitude1 != null && $latitude2 != null && $longitude2 != null) {
                        $distance = $this->distance($latitude1, $longitude1, $latitude2, $longitude2, "K");
                        $time_to_reach = round($distance / 3).' mins';
                    }

                    // $shop_products = Product::orWhereHas('brand', function($q) use($term) {
                    //     $q->orWhere('name', 'LIKE', '%' . $term . '%');
                    // })->where(['seller_id' => $shop->id, 'is_active' => 1,'is_approved' => 1])->orWhere('name', 'LIKE', '%' . $term . '%')->where('type', '!=', 2)->get();

                    $shop_brand_products = Product::whereHas('shop', function($q) {
                        $q->where('is_active', 1);
                    })->whereHas('brand', function($q) use($term) {
                        $q->where('name', 'LIKE', '%' . $term . '%');
                    })->where(['seller_id' => $shop->id, 'is_active' => 1,'is_approved' => 1])->where('type', '!=', 2)->get()->pluck('id')->toArray();

                    // $shop_name_first_products = Product::where('name', 'LIKE', $term . '%')->where(['seller_id' => $shop->id, 'is_active' => 1,'is_approved' => 1])->where('type', '!=', 2)->get()->pluck('id')->toArray();

                    $shop_name_products = Product::whereHas('shop', function($q) {
                        $q->where('is_active', 1);
                    })->where('name', 'LIKE', $term . '%')->where(['seller_id' => $shop->id, 'is_active' => 1,'is_approved' => 1])->where('type', '!=', 2)->get()->pluck('id')->toArray();

                    $category_name_products = Product::whereHas('shop', function($q) {
                        $q->where('is_active', 1);
                    })->where(['seller_id' => $shop->id, 'is_active' => 1,'is_approved' => 1])->whereHas('categories', function($q) use($categories){
                        $q->whereIn('category_id', $categories);
                    })->where('type', '!=', 2)->get()->pluck('id')->toArray();

                    $keywords_name_products = Product::whereHas('shop', function($q) {
                        $q->where('is_active', 1);
                    })->where(['seller_id' => $shop->id, 'is_active' => 1,'is_approved' => 1])->whereHas('keywords', function($q) use($term) {
                        $q->where('term', 'LIKE', '%' . $term . '%');
                    })->where('type', '!=', 2)->get()->pluck('id')->toArray();

                    $shop_products = array_merge($shop_brand_products, $category_name_products, $shop_name_products, $keywords_name_products);

                    $shop_products = array_unique($shop_products, SORT_REGULAR);

                    if(count($shop_products) > 0) {
                        
                        foreach($shop_products as $shop_product_data) {

                            $shop_product = Product::where(['id' => $shop_product_data])->first();

                            $product_data = $this->offerPrice($shop_product->id);

                            if($product_data['new_price'] != null) {
                                $new_price = number_format($product_data['new_price'], 2);
                                $discount = $product_data['discount'];
                            } else {
                                $new_price = null;
                            }

                            $category_type = null;

                            if(count($shop_product->categories) > 0) {
                                $category_type = BusinessCategoryShop::where(['category_id' => $shop_product->categories[0]->id, 'shop_id' => $shop_product->seller_id])->first();
                            }
                            

                            if($category_type != null)
                                $view_type = $category_type->view_type;
                            else
                                $view_type = 0;

                            $cart_count = Cart::where(['user_id' => $request->user_id, 'product_id' => $shop_product->id])->sum('quantity');

                            $exist_parent_category = null;
                            $check_category = 0;

                            $exist_category = ProductCategory::where(['product_id' => $shop_product->id, 'shop_id' => $shop_product->seller_id])->whereHas('categoryData')->first();

                            if($exist_category) {
                                $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                                if($exist_sub_category) {
                                    $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                                    if($exist_parent_category) {
                                        $check_category = 1;
                                    }

                                    $exist_category_shop = BusinessCategoryShop::where(['shop_id' => $shop_product->seller_id, 'category_id' => $exist_sub_category->id])->first();

                                    if(!$exist_category_shop && $check_category == 1) {
                                        $check_category = 0;
                                    }

                                }
                            }
                            
                            if(count($products) < 30 && $check_category == 1) {

                                $products[] = array(
                                    'product_id' => $shop_product->id,
                                    'product_name' => $shop_product->name,
                                    'product_quantity' => $shop_product->measurement_unit.' '.optional($shop_product->unit)->name,
                                    'product_stock' => $shop_product->stock,
                                    'new_price' => $new_price,
                                    'old_price' => number_format($shop_product->price, 2),
                                    'product_image' => $shop_product->thump_image == null ? null : asset('storage/app').'/'.$shop_product->thump_image,
                                    'view_type' => $view_type,
                                    'product_brand' => $shop_product->brand->name ?? '',
                                    'cart_count' => (int)$cart_count,
                                    'product_description' => $shop_product->description,
                                );
                            }
                            
                        }

                        if(count($items) < 30 && count($products) > 0) {
                            
                            $count_items = count($products);

                            $items[] = array(
                                'shop_id' => $shop->id,
                                'shop_name' => $shop->name,
                                'time_to_reach' => $time_to_reach,
                                'delivery_fee' => $delivery_fee == 0 ? 'Free' : null,
                                'shop_image' => $shop->image == null ? null : asset('storage/app').'/'.$shop->image,
                                'products' => $products,
                                'count_items' => $count_items
                            );
                        }

                    }
                }

                // $items['products'] = $products;
            }

            $items = collect($items)->sortBy('count_items')->reverse()->toArray();
            $items = array_values($items);
            // foreach ($products as $key => $product) {

            //         $items[] = array(
            //             'shop_id' => $product['id'],
            //             'shop_name' => $product['name'],
            //             'delivery_fee' => 'Free',
            //             'shop_image' => $product['image'] == null ? null : asset('storage/app').'/'.$product['image'],
            //             'product_list' => $product['products']
            //         );
            // }
            

            $shops = BusinessShop::where('name', 'LIKE', '%' . $term . '%')->whereHas('products')->where(['is_active' => 1])->take(30)->get();

            foreach ($shops as $key => $shop) {

                $latitude2 =  $shop->latitude == null ? null : $shop->latitude;
                $longitude2 = $shop->longitude == null ? null : $shop->longitude;
                
                if($latitude1 != null && $longitude1 != null && $latitude2 != null && $longitude2 != null) {
                    $distance = $this->distance($latitude1, $longitude1, $latitude2, $longitude2, "K");
                    $time_to_reach = round($distance / 3).' mins';
                }
                
                $shop_list[] = array(
                        'id' => $shop->id,
                        'shop_name' => $shop->name,
                        'shop_icon' => $shop->image == null ? null : asset('storage/app').'/'.$shop->image,
                        'shop_location' => $shop->location,
                        'shop_distance' => number_format($distance, 1).' kms',
                        'time_to_reach' => $time_to_reach
                    );
            }

            // $items = app('Modules\Api\Http\Controllers\CustomerApiController')->paginate($items, 30);

            // $shop_list = app('Modules\Api\Http\Controllers\CustomerApiController')->paginate($shop_list, 30);

            $data['items'] = $items;
            $data['shops'] = $shop_list;
            
            // $data = app('Modules\Api\Http\Controllers\CustomerApiController')->paginate($data, 30);

            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );
        }

        return response()->json($res);
    }

    //Search result for stores only
    public function storeSearch(Request $request)
    {
        $rules = array(
            'user_id' => 'sometimes|integer',
            'term' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $latitude1 = null;
            $longitude1 = null;
            $distance = null;
            $time_to_reach = null;
            $products = array();
            $cart_count = 0;

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $latitude1 = $user->latitude;
                $longitude1 = $user->longitude;
            }

            $data = array();
            $term = $request->term;

            if($request->term) {

                $exist_search = RecentSearch::where(['term' => $term])->first();

                if($exist_search) {
                    $exist_search->update(['count' => $exist_search->count + 1]);
                } else {
                    RecentSearch::create([
                        'user_id' => $user->id,
                        'term' => $term,
                        'count' => 1
                    ]);
                }
            }

            $shops = BusinessShop::where('name', 'LIKE', '%' . $term . '%')->whereHas('products')->where(['is_active' => 1])->take(30)->get();

            foreach ($shops as $key => $shop) {

                // $latitude2 =  $shop->latitude == null ? null : $shop->latitude;
                // $longitude2 = $shop->longitude == null ? null : $shop->longitude;
                
                // if($latitude1 != null && $longitude1 != null && $latitude2 != null && $longitude2 != null) {
                //     $distance = $this->distance($latitude1, $longitude1, $latitude2, $longitude2, "K");
                //     $time_to_reach = round($distance / 3).' mins';
                // }
                
                $data[] = array(
                        'shop_id' => $shop->id,
                        'shop_title' => $shop->name,
                        'shop_image' => $shop->image == null ? null : asset('storage/app').'/'.$shop->image,
                        'shop_location' => $shop->location,
                        'categories' => $this->getShopCategories($shop->id)
                        // 'shop_distance' => number_format($distance, 1).' kms',
                        // 'time_to_reach' => $time_to_reach
                    );
            }
             
            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );
        }

        return response()->json($res);
    }

    public function getShopCategories($shop_id)
    {
        $category = [];
        $categories = BusinessCategoryShop::where(['shop_id' => $shop_id])->get()->unique('main_category_id');
              
        foreach ($categories as $key => $cat) {

            if($cat->parentCategoryData) {
                
                $exist_category = BusinessCategory::where(['id' => $cat->main_category_id])->first();

                if($cat->parentCategoryData->is_active == 1 && $exist_category)
                {
                    $category[] = array(
                        'cat_id' => $cat->main_category_id,
                        'cat_title' => $cat->parentCategoryData->name,
                        'cat_icon' => $cat->parentCategoryData->image == null ? null : asset('storage/app').'/'.$cat->parentCategoryData->image,
                        'order' => $cat->parentCategoryData->order
                    );
                }
            }
        }

        $category = collect($category)->sortBy('order')->toArray();
        $category = array_values($category);

        return $category;
    }

    //Search result
    public function getRecentSearch(Request $request)
    {
        $rules = array(
            'user_id' => 'sometimes|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array();

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            // if($user) {
                $recent = RecentSearch::where(['user_id' => $request->user_id])->orderBy('id', 'desc')->get();
                
                $recent = $recent->unique('term')->values()->take(10); 

                foreach ($recent as $key => $value) {
                    $data[] = $value->term;
                }

             
                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            // } else {
            //     $res = array(
            //         'errorcode' => 9,
            //         'data' => [],
            //         'message' => "User not exist!"
            //     );
            // }
        }

        return response()->json($res);
    }

    //To get trending search result
    public function getTrendingSearch(Request $request)
    {
        $rules = array(
            'user_id' => 'sometimes|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array();
                
            $trend = TrendKeyword::where(['is_active' => 1])->orderBy('id', 'desc')->take(10)->get(); 

            foreach ($trend as $key => $value) {
                $data[] = $value->term;
            }

         
            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );
        }

        return response()->json($res);
    }

    //To get filter data
    public function getFilterdata(Request $request)
    {
        $rules = array(
            'subcategory_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $attributes = array();
                
            $category_attributes = BusinessCategoryField::where(['category_id' => $request->subcategory_id, 'is_active' => 1, 'is_filter' => 1])->get(); 

            foreach ($category_attributes as $key => $category_attribute) {
                $attribute_data = array();
                
                if($category_attribute->control == 1)
                    $view_type = 0;
                else
                    $view_type = 1;

                $attribute_data = explode(',', $category_attribute->field_value);
                $attribute_data = array_unique($attribute_data, SORT_REGULAR);
              
                    $attributes[] = array(
                        'attribute_id' => $category_attribute->id,
                        'attribute_title' => $category_attribute->field_name,
                        'attribute_viewtype' => $view_type,
                        'attribute_data' => $attribute_data,
                        
                    );
                
            }

         
            $res = array(
                'errorcode' => 0,
                'data' => $attributes,
                'message' => "Success!"
            );
        }

        return response()->json($res);
    }

    //To get filter data
    public function postFilterdata(Request $request)
    {
        $rules = array(
            //'request_data' => 'required',
            'shop_id' => 'required|integer',
           // 'min' => 'required',
           // 'max' => 'required'
           'subcategory_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $get_datas = json_decode($request->request_data, true);
            $products = array();
            $product_ids = array();
            $subcategory_id = $request->subcategory_id;
            $view_type = 0;
            $exist_ids = array();

            $shop_category = BusinessCategoryShop::where(['category_id' => $request->subcategory_id, 'shop_id' => $request->shop_id])->first();

            if($shop_category)
                $view_type = $shop_category->view_type;


        if($request->request_data != '' && $request->min !='' && $request->max != '') {
            foreach ($get_datas as $key => $get_data) {
                
                    
                $product_ids = ProductAttribute::where(['attribute_id' => $get_data['attribute_id'], 'attr_value' => $get_data['subattribute_value']])->get()->pluck('product_id');
                
                foreach ($product_ids as $key => $product_id) {

                    $product = Product::where(['id' => $product_id, 'seller_id' => $request->shop_id, 'is_active' => 1,'is_approved' => 1])->whereNotIn('id', $exist_ids)->whereBetween(DB::raw("`price` - IFNULL(`offer`, 0)"), [$request->min, $request->max])
                    ->whereHas('categories', function($query) use ($subcategory_id){
                        $query->where('product_categories.category_id',$subcategory_id);
                    })->first();
                    
                    if($product)    {

                        $product_data = $this->offerPrice($product_id);
                    
                        if($product_data['new_price'] != null) {
                            $new_price = number_format($product_data['new_price'], 2);
                            $percent_off = $product_data['percent_off'].'%';
                        } else {
                            $new_price = null;
                            $percent_off = null;
                        }

                        $cart = Cart::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->sum('quantity');

                        $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                        if($wishlist)
                            $isWishlist = true;
                        else
                            $wishlist = false;

                        $exist_ids[] = $product_id;

                        $products[] = array(
                            'product_id' => $product_id,
                            'product_name' => $product->name,
                            'product_brand' => $product->brand->name ?? '',
                            'seller_info' => $product->shop->name,
                            'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                            'new_price' => $new_price,
                            'old_price' => number_format($product->price, 2),
                            'percent_off' => $percent_off,
                            'isWishlist' => $wishlist,
                            'cart_count' => $cart,
                            'product_shortdescription' => $product->description,
                            'view_type' => $view_type,
                            'stock' => $product->stock
                        );

                    }
                }

            }
        }else if($request->request_data != ''){
            
            foreach ($get_datas as $key => $get_data) {
                
                    
                $product_ids = ProductAttribute::where(['attribute_id' => $get_data['attribute_id'], 'attr_value' => $get_data['subattribute_value']])->get()->pluck('product_id');
                
                foreach ($product_ids as $key => $product_id) {
                    $product = Product::where(['id' => $product_id, 'seller_id' => $request->shop_id, 'is_active' => 1,'is_approved' => 1])->whereNotIn('id', $exist_ids)->whereHas('categories', function($query) use ($subcategory_id){
                        $query->where('product_categories.category_id',$subcategory_id);
                    })->first();
                    
                    if($product)    {

                        $product_data = $this->offerPrice($product_id);
                    
                        if($product_data['new_price'] != null) {
                            $new_price = number_format($product_data['new_price'], 2);
                            $percent_off = $product_data['percent_off'].'%';
                        } else {
                            $new_price = null;
                            $percent_off = null;
                        }

                        $cart = Cart::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->sum('quantity');

                        $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                        if($wishlist)
                            $isWishlist = true;
                        else
                            $wishlist = false;

                        $exist_ids[] = $product_id;

                        $products[] = array(
                            'product_id' => $product_id,
                            'product_name' => $product->name,
                            'product_brand' => $product->brand->name ?? '',
                            'seller_info' => $product->shop->name,
                            'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                            'new_price' => $new_price,
                            'old_price' => number_format($product->price, 2),
                            'percent_off' => $percent_off,
                            'isWishlist' => $wishlist,
                            'cart_count' => $cart,
                            'product_shortdescription' => $product->description,
                            'view_type' => $view_type,
                            'stock' => $product->stock
                        );


                    }
                }
            }
        }else if($request->min != '' && $request->max != ''){
       
                   
            $product_ids = ProductCategory::get()->pluck('product_id');
            
            foreach ($product_ids as $key => $product_id) {
                $product = Product::where(['id' => $product_id, 'seller_id' => $request->shop_id, 'is_active' => 1,'is_approved' => 1])->whereNotIn('id', $exist_ids)->whereBetween(DB::raw("`price` - IFNULL(`offer`, 0)"), [$request->min, $request->max])->whereHas('categories', function($query) use ($subcategory_id){
                    $query->where('product_categories.category_id',$subcategory_id);
                })->first();
                
                if($product){

                    $product_data = $this->offerPrice($product_id);
                
                    if($product_data['new_price'] != null) {
                        $new_price = number_format($product_data['new_price'], 2);
                        $percent_off = $product_data['percent_off'].'%';
                    } else {
                        $new_price = null;
                        $percent_off = null;
                    }

                    $cart = Cart::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->sum('quantity');

                    $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                    if($wishlist)
                        $isWishlist = true;
                    else
                        $wishlist = false;

                    $exist_ids[] = $product_id;

                    $products[] = array(
                        'product_id' => $product_id,
                        'product_name' => $product->name,
                        'product_brand' => $product->brand->name ?? '',
                        'seller_info' => $product->shop->name,
                        'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                        'new_price' => $new_price,
                        'old_price' => number_format($product->price, 2),
                        'percent_off' => $percent_off,
                        'isWishlist' => $wishlist,
                        'cart_count' => $cart,
                        'product_shortdescription' => $product->description,
                        'view_type' => $view_type,
                        'stock' => $product->stock
                    );


                }
            }
    
        }
            // $products = array_unique($products, SORT_REGULAR);
         
            $res = array(
                'errorcode' => 0,
                'data' => $products,
                'message' => "Success!"
            );
        }

        return response()->json($res);
    }

    //Point history
    public function pointHistory(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array();
            $history = array();

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $point_transactions = PointHistory::where(['user_id' => $request->user_id, 'is_valid' => 1])->orderBy('created_at', 'DESC')->get();
                
                foreach ($point_transactions as $key => $point_transaction) {

                    $date = new DateTime($point_transaction->created_at);
                    $transaction_date = $date->format('M').' '.$date->format('d').' '.$date->format('Y');

                    if($point_transaction->slug == 'credited_by_referring_customer') {
                        $description = 'Referral reward for installing from '.$point_transaction->ReferralUser->name;
                    } elseif($point_transaction->slug == 'credited_by_purchase_referred_customer') {
                        $description = 'Points credited for purchasing a order by '.$point_transaction->ReferralUser->name;
                    } elseif($point_transaction->slug == 'debited_by_purchasing_order') {
                        $description = 'Points debited for order '.$point_transaction->orderData->order_no;
                    } elseif($point_transaction->slug == 'credited_by_purchasing_order') {
                        $description = 'Points credited for order '.$point_transaction->orderData->order_no;
                    }

                    if($point_transaction->points > 0 )
                    {
                        $history[] = array(
                            'description' => $description,
                            'date' => $transaction_date,
                            'type' => $point_transaction->is_credit,
                            'points' => $point_transaction->points
                        );
                    }
                }

                $points = Settings::where(['slug' => 'referral_earning_when_registration'])->first();

                $sharelink = 'Join me on Dafy App. Enter my code '.$user->user_code.' to earn '.$points->value.' points back on your wallet! '.$request->root().'/api/user/invite?referal_code='.$user->user_code;
                
                $data['user_code'] = $user->user_code;
                $data['history'] = $history;
                $data['sharelink'] = $sharelink;

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }
        }

        return response()->json($res);
    }

    //To get product latest price
    public function offerPrice($id)
    {
        $current_discount = 0;
        $current_discount1 = 0;
        $type = null;
        $product_data = array();
        $new_price = null;
        $percent_off = null;
        $discount = null;

        $product = Product::where(['id' => $id])->first();

        $product_price = $product->price - $product->offer;

        $offer_product = OfferProduct::where(['product_id' => $product->id, 'type' => 0])->first();
        
        if($offer_product) {

            $type = 0;

            if($offer_product->offerData) {
                $offer = $offer_product->offerData;

                if($offer->valid_from <= Carbon::now() && $offer->valid_to > Carbon::now() && $offer->status == 1) {
                
                    if($offer_product->offerData->discount_type == 1) {
                        $new_price = ($product_price - ($product_price * $offer_product->offerData->discount_value) / 100);

                        if($offer_product->offerData->max_discount_value != null) {
                            $max_dicount = $offer_product->offerData->max_discount_value;
                            $current_discount = $product_price - $new_price;
                            
                            if($current_discount > $max_dicount) {
                                $current_discount = $max_dicount;
                            }
                        } else {
                            $current_discount = $product_price - $new_price;
                        }
                    } else {
                        $current_discount = $offer_product->offerData->discount_value;
                    }

                    if($current_discount >= ($product->price + $product->offer)) {
                        $current_discount = 0;
                    }

                }

            }
        }

        $offer_shop = OfferProduct::where(['shop_id' => $product->seller_id, 'type' => 1, 'product_id' => null])->first();

        if($offer_shop) {

            $type = 1;

            if($offer_shop->offerData) {
                $offer_shop = $offer_shop->offerData;

                if($offer_shop->valid_from <= Carbon::now() && $offer_shop->valid_to > Carbon::now() && $offer_shop->status == 1) {
                    
                    if($offer_shop->discount_type == 1) {
                        $new_price_shop = $product_price;
                        
                        $new_price1 = ($new_price_shop - ($new_price_shop * $offer_shop->discount_value) / 100);
                        
                        if($offer_shop->max_discount_value != null) {
                            $max_dicount = $offer_shop->max_discount_value;
                            
                            $current_discount1 = $new_price_shop - $new_price1;
                            
                            if($current_discount1 > $max_dicount) {
                                $current_discount1 = $max_dicount;
                            }
                        } else {
                            $current_discount1 = $new_price_shop - $new_price1;
                        }
                    } else {
                        $current_discount1 = $offer_shop->discount_value;
                    }

                    if($current_discount1 >= ($product->price + $product->offer)) {
                        $current_discount1 = 0;
                    }
                }
            }
        }
        $tot_discount = $current_discount + $current_discount1 + $product->offer;

        if($tot_discount != 0) {
            $discount = $tot_discount;
            $new_price = $product->price - $discount;
            
            if($product->price != 0)
                $percent_off =  (($product->price - $new_price) / $product->price) * 100;
        } else {

            if($product->offer != null || $product->offer != 0) {
                $discount = $product->offer;
                $new_price = $product->price - $discount;
                $percent_off =  (($product->price - $new_price) / $product->price) * 100;
            }
        }


        return $product_data = array(
            'new_price' => $new_price,
            'percent_off' => round($percent_off),
            'discount' => $discount,
            'type' => $type
        );
    }

    // To get distance
    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
      if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
      }
      else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
          return $miles;
        }
      }
    }

    // generate invoice
    public function generateInvoice($order_id) 
    {
        $order = Order::where(['id' => $order_id])->first();
        $user = User::where(['id' => $order->user_id])->first();

        if($order->invoice_no == null) {

            //get last record
            $record = Order::max('invoice_no');
            $expNum = explode('-', $record);

            //check first day in a year
            if($expNum[0] == '') {
                if ( date('l',strtotime(date('Y-01-01'))) ){
                    $nextInvoiceNumber = date('Y').'-000001';
                }
            } else {
                //increase 1 with last invoice number
                $expNum1 = $expNum[1] + 1; 
                $expNum1 = str_pad($expNum1,6,"0",STR_PAD_LEFT);

                $nextInvoiceNumber = $expNum[0].'-'.$expNum1;
            }

            $order->update([
                'invoice_no' => $nextInvoiceNumber,
                'invoice_date' => Carbon::now()
            ]);
        }
        
        $mail['order_id'] = $order->order_no;
        $mail['invoice_no'] = $order->invoice_no;
        $mail['name'] = $user->name;
        $mail['shop_name'] = $order->shop->name;
        $mail['ship_from_address'] = 


        view()->share(['order' => $order]);

        $pdf = PDF::loadView('order::invoice')->setPaper('a4', '');

        Mail::send('order::email.invoice', $mail, function ($m) use ($mail,$pdf) { 
            $m->from('aljo@webcastle.in', '');
            $m->to('aljo@webcastle.in')->subject('Dafy Invoice - #'.$mail['order_id'])->attachData($pdf->output(), "invoice.pdf");
        });

        return $invoice_data = array(
            'response' => 'success'
        );
    }



}
