<?php

namespace Modules\VendorApi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Modules\Shop\Entities\BusinessShop;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\Status;
use Modules\Users\Entities\PointHistory;
use Modules\Users\Entities\User;
use Modules\Admin\Entities\NotificationCategory;
use Modules\Admin\Entities\Notification;
use Modules\Admin\Entities\Enquiry;
use Modules\Users\Entities\UserDevice;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductRating;
use Modules\Users\Entities\Role;
use Modules\Users\Entities\UserAddress;
use Modules\Api\Http\Controllers\BusinessApiController;
use DateTime;
use SiteHelper;
use Carbon;

class VendorApiController extends Controller
{
    protected $BusinessApiController;
    public function __construct(BusinessApiController $BusinessApiController)
    {
        $this->BusinessApiController = $BusinessApiController;
    }
  
    public function newOrders(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $order_details = [];
           
            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();
            
            if($shop)
            {
                $orders = Order::with(['shop','user','orderStatus','eachStatus.status'])->where(['shop_id' => $shop->id,'is_active' => 1])->whereHas('orderStatus',function($query){
                    $query->where('slug','=','ordered');
                })->orderBy('created_at', 'desc')->get();

                if(count($orders)>0)
                {
                    foreach($orders as $order)
                    {
                        $product = [];
                        $count = 1;

                        foreach($order->orderProducts as $order_product)
                        {   
                            // $product_data = $this->BusinessApiController->offerPrice($order_product->productData->id);

                            // if($product_data['new_price'] != null) {
                            //     $new_price = 'Rs '.number_format($product_data['new_price'], 2);
                            //     $percent_off = $product_data['percent_off'].'%';
                            // } else {
                            //     $new_price = null;
                            //     $percent_off = null;
                            // }

                            $product_data = Product::where(['id' => $order_product->product_id])->withTrashed()->first();
                            

                            if($count==1)
                            {
                                $image = optional($order_product->product)->thump_image == null ? null : asset('storage/app').'/'.optional($order_product->product)->thump_image;
                            }

                            $attr_values = null;

                            $attributes = ProductAttribute::where(['product_id' => $order_product->product_id])->withTrashed()->get()->pluck('attr_value')->toArray();

                            if(count($attributes) > 0) {
                                $attributes = array_unique($attributes, SORT_REGULAR);
                                $attr_values = implode(', ', $attributes);
                            }
                            
                            if($attr_values == null) 
                                $product_name = optional($product_data)->name;
                            else
                                $product_name = optional($product_data)->name.'('.$attr_values.')';

                            $product []= [
                                'product_id' => $order_product->product_id,
                                'quantity' => $order_product->product_quantity,
                                'product_name' => $product_name,
                                'brand' => optional(optional($order_product->productData)->brand)->name,
                                'price' => 'Rs '.$order_product->product_price
                                // 'new_price' => $new_price
                            ];
                            $count++;
                        }

                        $date = new DateTime($order->created_at);
                        $order_date = $date->format('M').' '.$date->format('d').' '.$date->format('Y');

                        $delivery_date = new DateTime($order->delivery_date);
                        $delivery_expected_date = $delivery_date->format('M').' '.$delivery_date->format('d').' '.$delivery_date->format('Y');
                        
                        $delivery_fee = $order->delivery_fee == null ? 0 : $order->delivery_fee;
                        $grand_total = $order->grand_total - $delivery_fee;

                        $order_details []= [
                            'id' => $order->id,
                            'order_id' => $order->order_no,
                            'instore' => $order->instore,
                            'order_description' => $order->comments,
                            'items' => $product,
                            'ordered_date' => $order_date,
                            'scheduled_date' => $delivery_expected_date,
                            'grand_total' => 'Rs '.$grand_total,
                            'product_image' =>  $image,
                            "order_status" => 'ordered'
                        ];
                    }


                    $res = array(
                        'errorcode' => 0,
                        'data' =>  $order_details,
                        'message' => "Success!"
                    );
                
                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "You have no new orders!"
                    );

                }

            }else{

                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Shop not exist!"
                );
            }
        }

        return response()->json($res);
    }



    public function acceptOrder(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();
            if($shop)
            {

            $status_val = 'accepted';
            $order_id = $request->order_id;
            $reject  = $request->input('reject',NULL);
            $notification_slug = '';
            $from_id = NULL;
            $admin_id = NULL;
            
  
  
  
            $status = Status::where('slug',$status_val)->first();
            $order = Order::where('id',$order_id)->first();

            if($order)
            {

            
            $order->status_id = $status->id;
            $order->reason = $reject;
            $order->admin_id = $admin_id;
            $order->save();
  
            OrderStatus::updateOrCreate([
              'order_id'  => $order->id,
              'status_id' => $status->id
            ],[
              'order_product_id' => NULL
            ]);
  
            if($status)
            {
                if($status->slug == 'ordered')
                {
                  $notification_slug = 'placed';
                }else{
                  $notification_slug = $status->slug;
                }
                
                $notification = NotificationCategory::where('slug',$notification_slug)->first();
  
                if($notification)
                {
                    $notify = new Notification;
                    $notify->notification_id =  $notification->id ?? '';
                    $notify->from_id = $from_id ?? NULL;
                    $notify->to_id = $order->user_id;
                    $notify->order_id = $order->id;
                    $notify->is_view = 0;
                    $notify->save();
  
                    $message = array(
                      'type' => 'order',
                      'title' => $notification->title,
                      'order_id' => $order->id ?? '',
                      'instore' => $order->instore,
                      'notification_id' => $notify->id ?? '',
                      'message' => 'Your order no '.$order->order_no.' has been '.$notification->title
                    );
              
                    $device_types=UserDevice::where('user_id',$order->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
  
                    if (!empty($device_types))
                    SiteHelper::sendAndroidPush($device_types, $message);
  
                    $iosdevice=UserDevice::where('user_id',$order->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
  
                    // if (!empty($iosdevice)) 
                    // SiteHelper::sendIosPush($iosdevice, $message);

                    $res = array(
                        'errorcode' => 0,
                        'data' =>  (object)[],
                        'message' => "Success!"
                    );
                }
            
  
            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Order status not exist!"
                );
            }

  
        }else{
            $res = array(
                'errorcode' => 1,
                'data' => (object)[],
                'message' => "Order not exist!"
            );

        }
        }else{
            $res = array(
                'errorcode' => 1,
                'data' => (object)[],
                'message' => "Shop not exist!"
            );
        }
    }

        return response()->json($res);
    }


    public function rejectOrder(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();
            if($shop)
            {

            $status_val = 'rejected';
            $order_id = $request->order_id;
            $reject  = $request->input('reject',NULL);
            $notification_slug = '';
            $from_id = NULL;
            $admin_id = NULL;
            
  
  
  
            $status = Status::where('slug',$status_val)->first();
            $order = Order::where('id',$order_id)->first();

            if($order)
            {

            
            $order->status_id = $status->id;
            $order->reason = $reject;
            $order->admin_id = $admin_id;
            $order->save();
  
            OrderStatus::updateOrCreate([
              'order_id'  => $order->id,
              'status_id' => $status->id
            ],[
              'order_product_id' => NULL
            ]);
  
            if($status)
            {
                if($status->slug == 'ordered')
                {
                  $notification_slug = 'placed';
                }else{
                  $notification_slug = $status->slug;
                }

                //Point Reverted
                $point = PointHistory::where(['order_id' => $order->id, 'slug' => 'debited_by_purchasing_order', 'is_valid' => 1])->first();
                
                if($point) {
                    $user = User::where(['id' => $order->user_id])->first();

                    $user->update(['wallet' => $user->wallet + $point->points]);

                    $point->delete();
                }
                //End Point Reverted
                
                $notification = NotificationCategory::where('slug',$notification_slug)->first();
  
                if($notification)
                {
                    $notify = new Notification;
                    $notify->notification_id =  $notification->id ?? '';
                    $notify->from_id = $from_id ?? NULL;
                    $notify->to_id = $order->user_id;
                    $notify->order_id = $order->id;
                    $notify->is_view = 0;
                    $notify->save();
  
                    $message = array(
                      'type' => 'order',
                      'title' => $notification->title,
                      'order_id' => $order->id ?? '',
                      'instore' => $order->instore,
                      'notification_id' => $notify->id ?? '',
                      'message' => 'Your order no '.$order->order_no.' has been '.$notification->title
                    );
              
                    $device_types=UserDevice::where('user_id',$order->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
  
                    if (!empty($device_types))
                    SiteHelper::sendAndroidPush($device_types, $message);
  
                    $iosdevice=UserDevice::where('user_id',$order->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
  
                    // if (!empty($iosdevice)) 
                    // SiteHelper::sendIosPush($iosdevice, $message);

                    $res = array(
                        'errorcode' => 0,
                        'data' =>  (object)[],
                        'message' => "Success!"
                    );
                }
            
  
            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Order status not exist!"
                );
            }

  
        }else{
            $res = array(
                'errorcode' => 1,
                'data' => (object)[],
                'message' => "Order not exist!"
            );

        }
        }else{
            $res = array(
                'errorcode' => 1,
                'data' => (object)[],
                'message' => "Shop not exist!"
            );
        }
    }

        return response()->json($res);
    }


    public function myProducts(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $product_list =[];

            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();
            if($shop)
            {
                $products = Product::where('seller_id',$shop->id)->where('type','<>',2)->orderBy('id','desc')->get();

                if(count($products)>0)
                {

                    foreach($products as $product)
                    {
                        $product_data = $this->BusinessApiController->offerPrice($product->id);

                        if($product_data['new_price'] != null) {
                            $new_price = 'Rs '.number_format($product_data['new_price'], 2);
                            $percent_off = $product_data['percent_off'].'%';
                        } else {
                            $new_price = null;
                            $percent_off = null;
                        }

                        $product_list [] = [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'brand' => optional($product->brand)->name,
                            'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                            'price' => 'Rs '.$product->price,
                            'new_price' => $new_price,
                            'percent_off' => $percent_off,
                            'isActivated' => ($product->is_approved==1 && $product->is_active==1) ? true : false
                        ];
                    }

                    $res = array(
                        'errorcode' => 0,
                        'data' => $product_list,
                        'message' => "Success!"
                    );

                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Product doesnt not exist!"
                    );
                }

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Shop not exist!"
                );
            }
        }
        return response()->json($res);
    }

    public function disabledProducts(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $product_list =[];

            $shop =  BusinessShop::where('seller_id',$user_id)->first();
            if($shop)
            {
                $products = Product::where(['seller_id' => $shop->id])->where('type','<>',2)->where(['is_active' => 0])->orderBy('id','desc')->get();

                if(count($products)>0)
                {

                    foreach($products as $product)
                    {
                        $product_data = $this->BusinessApiController->offerPrice($product->id);

                        if($product_data['new_price'] != null) {
                            $new_price = 'Rs '.number_format($product_data['new_price'], 2);
                            $percent_off = $product_data['percent_off'].'%';
                        } else {
                            $new_price = null;
                            $percent_off = null;
                        }

                        $product_list [] = [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'brand' => optional($product->brand)->name,
                            'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                            'price' => 'Rs '.$product->price,
                            'new_price' => $new_price,
                            'percent_off' => $percent_off,
                            'isActivated' => false
                        ];
                    }

                    $res = array(
                        'errorcode' => 0,
                        'data' => $product_list,
                        'message' => "Success!"
                    );

                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => [],
                        'message' => "No Disabled Products!"
                    );
                }

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Shop not exist!"
                );
            }
        }
        return response()->json($res);
    }


    public function searchProduct(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $product_list =[];

            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();
            if($shop)
            {
                if($request->keyword!= '')
                {

                    $search_value = $request->keyword;

                    $products = Product::where('seller_id',$shop->id)->where('type','<>',2)->where('name', 'like', '%' . $search_value . '%')->orderBy('id','desc')->get();

                    if(count($products)>0)
                    {

                        foreach($products as $product)
                        {
                            $product_data = $this->BusinessApiController->offerPrice($product->id);

                            if($product_data['new_price'] != null) {
                                $new_price = 'Rs '.number_format($product_data['new_price'], 2);
                                $percent_off = $product_data['percent_off'].'%';
                            } else {
                                $new_price = null;
                                $percent_off = null;
                            }

                            $product_list [] = [
                                'product_id' => $product->id,
                                'product_name' => $product->name,
                                'brand' => optional($product->brand)->name,
                                'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                                'price' => 'Rs '.$product->price,
                                'new_price' => $new_price,
                                'percent_off' => $percent_off,
                                'isActivated' => ($product->is_approved==1 && $product->is_active==1) ? true : false
                            ];
                        }

                        $res = array(
                            'errorcode' => 0,
                            'data' => $product_list,
                            'message' => "Success!"
                        );

                    }else{
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Product doesnt not exist!"
                        );
                    }
                }else{

                    $products = Product::where('seller_id',$shop->id)->where('type','<>',2)->orderBy('id','desc')->get();
                    if(count($products)>0)
                    {

                        foreach($products as $product)
                        {
                            $product_list [] = [
                                'product_id' => $product->id,
                                'product_name' => $product->name,
                                'brand' => optional($product->brand)->name,
                                'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                                'price' => 'Rs '.$product->price,
                                'isActivated' => ($product->is_approved==1 && $product->is_active==1) ? true : false
                            ];
                        }

                        $res = array(
                            'errorcode' => 0,
                            'data' => $product_list,
                            'message' => "Success!"
                        );

                    }else{
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Product doesnt not exist!"
                        );
                    }
                }
            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Shop not exist!"
                );
            }
        }

        return response()->json($res);

    }


    public function activateProduct(Request $request)
    {


        $rules = array(
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user_id = $request->user_id;

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $product_list =[];

            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();
            if($shop)
            {

                $product = Product::where(['id'=>$request->product_id,'seller_id'=> $shop->id])->first();
                if($product)
                {
                    if($product->is_active == 0)
                    {
                        $product->is_active = 1;
                        $product->save();
                    }else{
                        $product->is_active = 0;
                        $product->save();
                    }

                    $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );


                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Product not exist!"
                    );
                }

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Shop not exist!"
                );
            }
        }

        return response()->json($res);
    }


    public function notification(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $description = '';

            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();
           
            if($shop)
            {
                $data = array();

                $notifications = Notification::where(['to_id' => $shop->seller_id])->whereHas('notificationCategory', function($q) {
                    $q->where('for', 1);
                })->orderBy('id', 'desc')->get();

                foreach ($notifications as $key => $notification) {

                    if($notification->notificationCategory->slug == 'seller_order_placed') {
                        $description = 'A new Order ID #'.$notification->orderData->order_no.' has been placed.';
                    } elseif($notification->notificationCategory->slug == 'seller_enquiry_notification') {

                        $enquiry = Enquiry::where(['id' => $notification->enquiry_id])->first();
                        $description = 'An Enquiry has been received for the product '.$enquiry->product_name;
                    }

                    $date = $notification->created_at;
                    $notif_date =  Carbon\Carbon::parse($date)->diffForHumans();
                    
                    $data[] = array(
                        'notification_id' => $notification->id,
                        'notification_title' => $notification->notificationCategory->title,
                        'notification_content' => $description,
                        'notification_time' => $notif_date
                    );

                    $notification->update(['is_view' => 1]);
                }

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Shop not exist!"
                );
            }

        }
        return response()->json($res);
    }

    public function completedOrders(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $order_details = [];
           
            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();
            
            if($shop)
            {
                $orders = Order::with(['shop','user','orderStatus','eachStatus.status'])->where(['shop_id' => $shop->id,'is_active' => 1])->whereHas('orderStatus',function($query){
                    $query->where('slug','=','delivered');
                })->orderBy('created_at', 'desc')->get();

                if(count($orders)>0)
                {
                    foreach($orders as $order)
                    {
                        $product = [];
                        $count = 1;

                        foreach($order->orderProducts as $order_product)
                        {   
                            // $product_data = $this->BusinessApiController->offerPrice($order_product->productData->id);

                            // if($product_data['new_price'] != null) {
                            //     $new_price = 'Rs '.'Rs '.number_format($product_data['new_price'], 2);
                            //     $percent_off = $product_data['percent_off'].'%';
                            // } else {
                            //     $new_price = null;
                            //     $percent_off = null;
                            // }

                            if($count==1)
                            {
                                $image = optional($order_product->productData)->thump_image == null ? null : asset('storage/app').'/'.optional($order_product->productData)->thump_image;
                            }

                            $attr_values = null;

                            $attributes = ProductAttribute::where(['product_id' => $order_product->product_id])->withTrashed()->get()->pluck('attr_value')->toArray();

                            if(count($attributes) > 0) {
                                $attributes = array_unique($attributes, SORT_REGULAR);
                                $attr_values = implode(', ', $attributes);
                            }
                            
                            if($attr_values == null) 
                                $product_name = optional($order_product->productData)->name;
                            else
                                $product_name = optional($order_product->productData)->name.'('.$attr_values.')';
                                
                            $product []= [
                                'quantity' => $order_product->product_quantity,
                                'product_name' => $product_name,
                                'brand' => optional(optional($order_product->productData)->brand)->name,
                                'price' => 'Rs '.$order_product->product_price,
                                // 'new_price' => $new_price
                            ];
                            $count++;
                        }

                        $date = new DateTime($order->created_at);
                        $order_date = $date->format('M').' '.$date->format('d').' '.$date->format('Y');

                        $delivery_date = new DateTime($order->delivery_date);
                        $delivery_expected_date = $delivery_date->format('M').' '.$delivery_date->format('d').' '.$delivery_date->format('Y');
                        
                        $delivery_fee = $order->delivery_fee == null ? 0 : $order->delivery_fee;
                        $grand_total = $order->grand_total - $delivery_fee;

                        $order_details []= [
                            'id' => $order->id,
                            'order_id' => $order->order_no,
                            'instore' => $order->instore,
                            'order_description' => $order->comments,
                            'items' => $product,
                            'ordered_date' => $order_date,
                            'scheduled_date' => $delivery_expected_date,
                            'grand_total' => 'Rs '.$grand_total,
                            'product_image' =>  $image,
                            "order_status" => 'completed'
                        ];
                    }


                    $res = array(
                        'errorcode' => 0,
                        'data' =>  $order_details,
                        'message' => "Success!"
                    );
                
                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "You have no completed orders!"
                    );

                }

            }else{

                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Shop not exist!"
                );
            }
        }

        return response()->json($res);
    }


    public function acceptedOrders(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $order_details = [];
            $image = null;
            
            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();
            
            if($shop)
            {
                $orders = Order::with(['shop','user','orderStatus','eachStatus.status'])->where(['shop_id' => $shop->id,'is_active' => 1])->whereHas('orderStatus',function($query){
                    $query->where('slug','=','accepted')->orWhere('slug', '=', 'shipped');
                })->orderBy('created_at', 'desc')->get();

                if(count($orders)>0)
                {
                    foreach($orders as $order)
                    {
                        $product = [];
                        $count = 1;

                        foreach($order->orderProducts as $order_product)
                        {   
                            // $product_data = $this->BusinessApiController->offerPrice($order_product->productData->id);

                            // if($product_data['new_price'] != null) {
                            //     $new_price = 'Rs '.number_format($product_data['new_price'], 2);
                            //     $percent_off = $product_data['percent_off'].'%';
                            // } else {
                            //     $new_price = null;
                            //     $percent_off = null;
                            // }

                            if($order_product->productData) {

                                if($count==1)
                                {
                                    $image = optional($order_product->productData)->thump_image == null ? null : asset('storage/app').'/'.optional($order_product->productData)->thump_image;
                                }

                                $attr_values = null;

                                $attributes = ProductAttribute::where(['product_id' => $order_product->product_id])->withTrashed()->get()->pluck('attr_value')->toArray();

                                if(count($attributes) > 0) {
                                    $attributes = array_unique($attributes, SORT_REGULAR);
                                    $attr_values = implode(', ', $attributes);
                                }
                                
                                if($attr_values == null) 
                                    $product_name = optional($order_product->productData)->name;
                                else
                                    $product_name = optional($order_product->productData)->name.'('.$attr_values.')';

                                $product []= [
                                    'quantity' => $order_product->product_quantity,
                                    'product_name' => $product_name,
                                    'brand' => optional(optional($order_product->productData)->brand)->name,
                                    'price' => 'Rs '.$order_product->product_price,
                                    // 'new_price' => $new_price
                                ];
                                $count++;
                            }
                            
                        }

                        $date = new DateTime($order->created_at);
                        $order_date = $date->format('M').' '.$date->format('d').' '.$date->format('Y');

                        $delivery_date = new DateTime($order->delivery_date);
                        $delivery_expected_date = $delivery_date->format('M').' '.$delivery_date->format('d').' '.$delivery_date->format('Y');
                        
                        $delivery_fee = $order->delivery_fee == null ? 0 : $order->delivery_fee;
                        $grand_total = $order->grand_total - $delivery_fee;

                        $order_details []= [
                            'id' => $order->id,
                            'order_id' => $order->order_no,
                            'instore' => $order->instore,
                            'order_description' => $order->comments,
                            'items' => $product,
                            'ordered_date' => $order_date,
                            'scheduled_date' => $delivery_expected_date,
                            'grand_total' => 'Rs '.$grand_total,
                            'product_image' =>  $image,
                            "order_status" => $order->orderStatus->name
                        ];
                    }


                    $res = array(
                        'errorcode' => 0,
                        'data' =>  $order_details,
                        'message' => "Success!"
                    );
                
                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "You have no orders!"
                    );

                }

            }else{

                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Shop not exist!"
                );
            }
        }

        return response()->json($res);
    }


    public function reviews(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $data = [];
            $prod = [];
            $shop =  BusinessShop::where('seller_id',$user_id)->latest('updated_at')->first();

            if($shop)
            {
                $products = Product::where('seller_id', $shop->id)->where([/*'is_active' => 1,'is_approved' => 1*/])->pluck('id');

                if(!is_null($products) && !empty($products))
                {

                    $avg_rating = (int)ProductRating::whereIn('product_id',$products)->avg('rating');

                    $total_count = ProductRating::whereIn('product_id',$products)->count('review');

                    $product_lists =ProductRating::with(['product','user'])->whereIn('product_id',$products)->get();

                    if(count($product_lists))
                    {
                        foreach($product_lists as $product_list)
                        {
                            $date = $product_list->created_at;
                            $date =  Carbon\Carbon::parse($date)->diffForHumans();

                            $prod [] =[
                                'product_name' => $product_list->product->name,
                                'brand' => optional(optional($product_list->product)->brand)->name,
                                'product_image' =>$product_list->product->thump_image == null ? null : asset('storage/app').'/'.$product_list->product->thump_image,
                                'customer_name' => $product_list->user->name,
                                'customer_rating' => number_format($product_list->rating,1),
                                'time' => $date,
                                'review_comment' => $product_list->review
                            ];
                        }

                        $data['overall_rating'] = $avg_rating;
                        $data['no_reviews'] = $total_count;
                        $data['customer_reviews'] = $prod;
                        
                        $res = array(
                            'errorcode' => 0,
                            'data' =>  $data,
                            'message' => "Success!"
                        );

                    }else{
                        $res = array(
                            'errorcode' => 0,
                            'data' => (object)[],
                            'message' => "Success!"
                        );

                    }
                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Product not exist!"
                    );
                }

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Shop not exist!"
                );

            }
        }

        return response()->json($res);

    }


    public function getProfile(Request $request)
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

            $user = User::where(['id' => $user_id,  'is_active' => 1])->first();

            if(!$user) {

                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );

                return response()->json($res);
            }

            $role = Role::where('name','seller')->first();
        
            $user = User::where('id',$user_id)->whereNotNull('mobile')->with('categories','userAddresses.state','roles');
            
           
                $user->whereHas('roles', function ($query) use($role) {
                    $query->where("user_role.role_id", "=", $role->id); //seller
                    
                });

                $user = $user->first();

                if($user)
                {
                    $data['Name'] = $user->name;
                    
                    $data['businessname'] = $user->business_name;
                    $addr =[];
                    if($user->userAddresses)
                    {
                        foreach($user->userAddresses as $address)
                        {
                            if($address->address_type == 1)
                            {
                                array_push($addr,$address->area.','.$address->city);
                            }
                        }
                    }

                    $addr = implode(',', $addr);
                    $data['location'] = $addr;

                    $data['business_image'] = $user->business_image == null ? null : asset('storage/app').'/'.$user->business_image;
                    if($user->shop)
                    {
                    $status = Status::where('slug','ordered')->first();
                    $data['new_orders'] = $user->shop->orders()->where('status_id',$status->id)->count();
                    $status = Status::where('slug','delivered')->first();
                    $data['completed_orders'] = $user->shop->orders()->where('status_id',$status->id)->count();
                    $status = Status::where('slug','accepted')->first();
                    $data['accepted_orders'] = $user->shop->orders()->where('status_id',$status->id)->count();
                    $data['my_products'] = $user->shop->products()->where('type','<>',2)->count();
                    }else{
                        $data['new_orders'] = 0;
                        $data['completed_orders'] = 0;
                        $data['accepted_orders'] = 0;
                        $data['my_products'] = 0;

                    }
                    $notification_category = NotificationCategory::where(['slug' => 'seller_order_placed'])->first();
                    if($notification_category)
                    {
                    $data['notification'] =  Notification::where('to_id',$user->id)->where('notification_id',$notification_category->id)->count();
                    }else{
                        $data['notification'] = 0;
                    }

                    $counter = 0;
                    if($user->shop)
                    {
                        $products = $user->shop->products()->with('productRatings')->get();

                       
                        if($products->count()>0)
                        {
                            foreach($products as $product)
                            {
                            $counter += $product->productRatings()->where('review','<>',NULL)->count();
                            }
                        }
                    }
                    $data['reviews'] = $counter;
                    
                    $res = array(
                        'errorcode' => 0,
                        'data' =>  $data,
                        'message' => "Success!"
                    );

                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Seller not exist!"
                    );
                }
        }

        return response()->json($res);

    }

    public function editProductPrice(Request $request)
    {
        
        $rules = array(
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'price' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            Product::where(['id' => $request->product_id])->update(['price' => $request->price]);

            $res = array(
                'errorcode' => 0,
                'data' => (object)[],
                'message' => "Success!"
            );
        }
        return response()->json($res);
    }

}
