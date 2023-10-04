<?php

namespace Modules\DeliveryApp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Modules\DeliveryApp\Entities\DriverUser;
use Modules\DeliveryApp\Entities\DriverUserDevice;
use Modules\DeliveryApp\Entities\DriverOtp;
use Modules\DeliveryApp\Entities\DriverOrder;
use Modules\Product\Entities\ProductAttribute;

use Modules\Admin\Entities\BuyAnything;
use Modules\Order\Entities\OrderBuyAnything;
use Modules\Admin\Entities\BuyAddress;

use Modules\Admin\Entities\DeliverAnything;
use Modules\Order\Entities\OrderDeliverAnything;
use Modules\Admin\Entities\DeliverAddress;

use Modules\Admin\Entities\SellerOrderData;
use Modules\Users\Entities\UserAddress;
use Modules\Order\Entities\OrderAddress;
use Modules\Order\Entities\Order;
use Modules\Users\Entities\PointHistory;
use Modules\Users\Entities\User;
use Modules\Order\Entities\Status;
use Modules\Order\Entities\OrderStatus;
use Modules\Admin\Entities\NotificationCategory;
use Modules\Admin\Entities\Notification;
use Modules\Users\Entities\UserDevice;
use Modules\DeliveryApp\Entities\OrderOtp;
use Carbon\Carbon;
use DB;
use DateTime;
use SiteHelper;

class DeliveryAppController extends Controller
{
    public function login(Request $request)
    {
        $rules = array(
            'country_code' => 'required|numeric',
            'phone_no' => 'required|numeric',
            'device_type' => 'required|integer|in:1,2',
            'device_token' => 'required|string'
            
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            
            $user = DriverUser::where(['country_code' => $request->country_code,'mobile' =>  $request->phone_no, 'is_active' => 1])->first();

            if($user) {
                $data = array();

                $userOtp = rand(1000, 9999);
                    
                    DriverUserDevice::create([
                        'deliveryboy_id' => $user->id,
                        'device_type' => $request->device_type,
                        'device_id' => $request->device_token,
                        'login_time' => date('Y-m-d H:i:s'),
                    ]);

                    $otp = DriverOtp::where(['driver_id' => $user->id])->first();

                    if($otp) {
                        $otp->update(['otp' => $userOtp, 'status' => 0]);
                    } else {
                        $otp = DriverOtp::create([
                            'driver_id' => $user->id,
                            'otp' => $userOtp,
                            'status' => 0
                        ]);
                    }

                    $data=array('otp'=>$userOtp,'user_id'=>$user->id);

                    $apiKey = urlencode('q59xlcpmmhI-09rjL4xmNPQalKFpxthozVj58xCIA0');

                    // $sms_content = $userOtp.' is your login OTP for Dafy +yX4/Oy5bOP';
                    
                    // $url1 = 'thesmsbuddy.com/api/v1/sms/send?key=YEpXB7CZtP3q0nA1lQJOC75kG94jSlWd&type=1&to='.$request->phone_no.'&sender=KLDAFY&message='.urlencode($sms_content).'&flash=0';

                    $sms_content = 'Welcome to DAFY Delivery APP. Your OTP is '.$userOtp.'. Deliver happiness with DAFY';

                    $url1 = 'thesmsbuddy.com/api/v1/sms/send?key=YEpXB7CZtP3q0nA1lQJOC75kG94jSlWd&type=1&to='.$request->phone_no.'&sender=KLDAFY&message='.urlencode($sms_content).'&flash=0&template_id=1307162011793845094';

                    $response = '';
                    $ch = curl_init();
                    curl_setopt_array($ch, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url1
                    ));
                    $response = curl_exec($ch);

                    curl_close ($ch);
        
                   
                    $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );
              
                
            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "User Not Exist!"
                );
            }
        }
        return response()->json($res);
    }

    public function resendOtp(Request $request)
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
            $uid = $request->user_id;

           $user = DriverUser::where('id',$uid)->first();
           
           if($user)
           {
            $otp = DriverOtp::where(['driver_id' => $user->id])->first();
            $userOtp = rand(1000, 9999);
            if($otp) {
                    $otp->update(['otp' => $userOtp, 'status' => 0]);
                } else {
                    $otp = DriverOtp::create([
                        'driver_id' => $user->id,
                        'otp' => $userOtp,
                        'status' => 0
                    ]);
                }
                $data=array('otp'=>$userOtp,'user_id'=>$user->id);

                $apiKey = urlencode('q59xlcpmmhI-09rjL4xmNPQalKFpxthozVj58xCIA0');

                // $sms_content = $userOtp.' is your login OTP for Dafy +yX4/Oy5bOP';
                
                // $url1 = 'thesmsbuddy.com/api/v1/sms/send?key=YEpXB7CZtP3q0nA1lQJOC75kG94jSlWd&type=1&to='.$request->phone_no.'&sender=KLDAFY&message='.urlencode($sms_content).'&flash=0';

                $sms_content = 'Welcome to DAFY Delivery APP. Your OTP is '.$userOtp.'. Deliver happiness with DAFY';

                $url1 = 'thesmsbuddy.com/api/v1/sms/send?key=YEpXB7CZtP3q0nA1lQJOC75kG94jSlWd&type=1&to='.$user->mobile.'&sender=KLDAFY&message='.urlencode($sms_content).'&flash=0&template_id=1307162011793845094';

                $response = '';
                $ch = curl_init();
                curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url1
                ));
                $response = curl_exec($ch);

                curl_close ($ch);
                
                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
          
           }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "User Not Exist!"
                );
           }
        }
        return response()->json($res);
    }

    public function verifyOtp(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'otp' => 'required|integer|digits:4',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $uid    =   $request->user_id;
            $otp    =   $request->otp;
            $data   =   [];
            $user   =   DriverUser::where('id',$uid)->first();

            if($user)
            {

                $otp    =   DriverOtp::where('driver_id',$user->id)->where('otp',$otp)->orderBy('id','desc')->first();
                
                if($otp)
                {
                    if($otp->status==0)
                    {
            
                        $otp->status=1;
                        $otp->save();
            
                        $data = ['user_id' => $user->id ,'userName' =>  $user->name ,'latitude' =>  $user->latitude,'longitude' =>  $user->longitude];
                        $res = array(
                            'errorcode' => 0,
                            'data' => $data,
                            'message' => "Success!"
                        );
            
                    }else{

                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Already verified!"
                        ); 
            
                    }
                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "OTP Missmatch!"
                    );
                }
            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "User Not Exist!"
                );
            }
        }

        return response()->json($res);
    }


    public function getJobList(Request $request)
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
            
            $uid    =   $request->user_id;

            $user   =   DriverUser::with('orders')->where('id',$uid)->where('is_active',1)->first();

          
            if($user)
            {
                $data = [];
                $order_lists = DriverOrder::where('driver_id',$user->id)->whereDate('assigned_date',Carbon::today())->where('status','<>',2)->orderBy('id','desc')->get();
               
                if($order_lists->count()>0)
                {
                    foreach($order_lists as $list)
                    {
                        if($list->status == 0)
                        {
                            $status = 'not started';
                        }else if($list->status == 1)
                        {
                            $status = 'started';
                        }else{
                            $status = 'completed';
                        }

                        $date = new DateTime($list->assigned_date);
                        $assign_date = $date->format('d').' '.$date->format('M').' '.$date->format('Y');
                        
                        $order_addr = OrderAddress::where('order_id',$list->order_id)->first();

                        $order = Order::where('id', $list->order_id)->first();

                       $items[] = array(
                            'orderId' => $list->order_id,
                            'delivery_fee' => $order->delivery_fee,
                            'location' => $order_addr->location,
                            'Job_latitude' => $order_addr->latitude,
                            'job_longitude' => $order_addr->longitude,
                            'status'   => $status,
                            'customer_name' => $order->user->name,
                            'fullAddress' => $order_addr->build_name .' ,'.$order_addr->area.' ,'.$order_addr->pincode,
                            'job_status' => $status,
                            'shopName' => $order->shop->name,
                            'pickupLocation' => $order->shop->location,
                            'pickupLatitude' => $order->shop->latitude,
                            'pickupLongitude' => $order->shop->longitude
                       );
                        $dates = $assign_date;
                    }

                    $data[]= array('list'=>$items,'date'=>$dates);

                    $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );
                }else{

                    $res = array(
                        'errorcode' => 1,
                        'data' => [],
                        'message' => "Order Not Exist!"
                    );
                }

              
            
              // $data['date'] = $dates;
              
            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "User Not Exist!"
                );
            }


        }
        return response()->json($res);
    }

    public function getJobList1(Request $request)
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
            $uid    =   $request->user_id;

            $user   =   DriverUser::with('orders')->where('id',$uid)->where('is_active',1)->first();

            if(!$user) {
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "User Not Exist!"
                );

                return response()->json($res);
            }
            $items = array();
            $date = Carbon::today();
            $assign_date = $date->format('d').' '.$date->format('M').' '.$date->format('Y');

            $order_lists = DriverOrder::where('driver_id',$user->id)->whereDate('assigned_date',Carbon::today())->where('status','<>',2)->orderBy('id','desc')->get();
            
            if(count($order_lists) == 0) 
                $order_items = array();

            foreach($order_lists as $list) {
                    
                if($list->status == 0) {
                    $status = 'not started';
                } else if($list->status == 1) {
                    $status = 'started';
                } else{
                    $status = 'completed';
                }
                
                $order_addr = OrderAddress::where('order_id',$list->order_id)->first();

                $not_status = array('cancelled', 'rejected', 'failed');

                $order = Order::where('id', $list->order_id)->whereHas('orderStatus', function($q) use($not_status){
                    $q->whereNotIn('slug', $not_status);
                })->first();

                if($order) {

                    $items[] = array(
                        'type' => 'order',
                        'orderId' => $list->order_id,
                        'delivery_fee' => $order->delivery_fee,
                        'location' => $order_addr->location,
                        'Job_latitude' => $order_addr->latitude,
                        'job_longitude' => $order_addr->longitude,
                        'status'   => $status,
                        'customer_name' => $order->user->name,
                        'fullAddress' => $order_addr->build_name .' ,'.$order_addr->area.' ,'.$order_addr->pincode,
                        'job_status' => $status,
                        'shopName' => $order->shop->name,
                        'pickupLocation' => $order->shop->location,
                        'pickupLatitude' => $order->shop->latitude,
                        'pickupLongitude' => $order->shop->longitude
                    );
                }
                
            }

            $buy_anything_list = OrderBuyAnything::where('driver_id', $user->id)->whereDate('assign_date', Carbon::today())->where('status_id', 8)->orderBy('id', 'desc')->get();

            if(count($buy_anything_list) == 0) 
                $buy_items = array();

            foreach($buy_anything_list as $list) {
                
                $status = $list->orderStatus->name;

                // if($list->orderData->status == 0) {
                //     $status = 'not started';
                // } else if($list->orderData->status == 1) {
                //     $status = 'started';
                // } else{
                //     $status = 'completed';
                // }
                
                $order_addr = BuyAddress::where('id', $list->orderData->deliver_addressid)->first();

                if(!$order_addr)
                    $order_addr = UserAddress::where('id', $list->orderData->deliver_addressid)->first();

                $not_status = array('item_not_available');

                $order = BuyAnything::where('id', $list->order_id)->whereHas('orderStatus', function($q) use($not_status){
                    $q->whereNotIn('slug', $not_status);
                })->first();

                if($order) {

                    $buy_items[] = array(
                        'type' => 'buy',
                        'orderId' => $list->order_id,
                        'location' => $order_addr->location,
                        'Job_latitude' => $order_addr->latitude,
                        'job_longitude' => $order_addr->longitude,
                        'status'   => $status,
                        'customer_name' => $order->user->name,
                        'fullAddress' => $order_addr->build_name .' ,'.$order_addr->area.' ,'.$order_addr->pincode,
                        // 'job_status' => $status,
                    );
                }
                
            }

            $deliver_anything_list = OrderDeliverAnything::where('driver_id', $user->id)->whereDate('assign_date', Carbon::today())->where('status_id', 8)->orderBy('id', 'desc')->get();

            if(count($deliver_anything_list) == 0) 
                $deliver_items = array();

            foreach($deliver_anything_list as $list) {
                
                $status = $list->orderStatus->name;

                // if($list->orderData->status == 0) {
                //     $status = 'not started';
                // } else if($list->orderData->status == 1) {
                //     $status = 'started';
                // } else{
                //     $status = 'completed';
                // }

                // $date = new DateTime($list->created_at);
                // $assign_date = $date->format('d').' '.$date->format('M').' '.$date->format('Y');

                $pick_order_addr = DeliverAddress::where('id',$list->orderData->pickup_addressid)->first();

                if(!$pick_order_addr)
                    $pick_order_addr = UserAddress::where('id', $list->orderData->pickup_addressid)->first();
                
                $order_addr = DeliverAddress::where('id',$list->orderData->deliver_addressid)->first();

                if(!$order_addr)
                    $order_addr = UserAddress::where('id', $list->orderData->deliver_addressid)->first();

                $not_status = array('item_not_available');

                $order = DeliverAnything::where('id', $list->order_id)->whereHas('orderStatus', function($q) use($not_status){
                    $q->whereNotIn('slug', $not_status);
                })->first();


                if($order) {

                    $deliver_items[] = array(
                        'type' => 'deliver',
                        'orderId' => $list->order_id,
                        'pickup_location' => $pick_order_addr->location,
                        'pickup_latitude' => $pick_order_addr->latitude,
                        'pickup_longitude' => $pick_order_addr->longitude,
                        'status'   => $status,
                        'pickup_address' => $pick_order_addr->build_name .' ,'.$pick_order_addr->area.' ,'.$pick_order_addr->pincode,

                        'deliver_location' => $order_addr->location,
                        'deliver_latitude' => $order_addr->latitude,
                        'deliver_longitude' => $order_addr->longitude,
                        'status'   => $status,
                        'customer_name' => $order->user->name,
                        'deliver_ddress' => $order_addr->build_name .' ,'.$order_addr->area.' ,'.$order_addr->pincode,
                        // 'job_status' => $status,
                    );
                }
                
            }
            
            $data[] = array(
                'date' => $assign_date,
                'list' => $items,
                'buy_items' => $buy_items,
                'deliver_items' => $deliver_items
            );

            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );

        }
        return response()->json($res);
    }


    public function orderDetails(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'orderId' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $uid    =   $request->user_id;
            $order_id  = $request->orderId;

            $user   =   DriverUser::with('orders')->where('id',$uid)->where('is_active',1)->first();

            if($user)
            {
                $order_list = DriverOrder::where(['driver_id'=>$user->id,'order_id' =>$order_id])->first();//->whereDate('assigned_date',Carbon::today())->where('status','<>',2)

                if($order_list)
                {
                    $order = Order::where('id',$order_list->order_id)->with(['shop','user','orderStatus','orderAddresses','orderProducts'])->first();
                    $data['orderId'] = $order->id;
                    $data['customerId'] = $order->user->id;
                    $data['customerName'] = $order->user->name;
                    $data['customerPhone'] = $order->user->mobile;

                    $data['shopName'] = $order->shop->name;
                    $data['pickupLocation'] = $order->shop->location;
                    $data['pickupAddress'] = $order->shop->address;
                    $data['pickupLatitude'] = $order->shop->latitude;
                    $data['pickupLongitude'] = $order->shop->longitude;

                    if($order_list->status == 0) {
                        $driver_status = 'Not Started';
                    } elseif($order_list->status == 1) {
                        $driver_status = 'Started';
                    } else {
                        $driver_status = 'Completed';
                    } 

                    if($order->orderStatus->slug == 'cancelled' || $order->orderStatus->slug == 'rejected' || $order->orderStatus->slug == 'failed')
                        $driver_status = 'Cancelled';

                    $data['driver_status'] = $driver_status;

                    if($order->orderAddresses->type==0)
                    {
                        $type= 'Home';
                    }elseif($order->orderAddresses->type==1)
                    {
                        $type = 'Office';
                    }else{
                        $type = 'Other';
                    }

                    $data['delivery_loc'] = array('address_type' => $type , 'address' =>$order->orderAddresses->build_name.' ,'.$order->orderAddresses->area.' ,'.$order->orderAddresses->location,
                                                    'lat' => $order->orderAddresses->latitude, 'lon' => $order->orderAddresses->longitude
                                            );

                    $date = new DateTime($order_list->assigned_date);
                    $assign_date = $date->format('d').' '.$date->format('M').' '.$date->format('Y');
                    $data['scheduledDate'] = $assign_date;

                    $time =  date('h:i A', strtotime($order_list->assigned_date));
                    $data['scheduledTime'] = $time;

                    foreach($order->orderProducts as $product)
                    {

                        $attr_values = null;

                        $attributes = ProductAttribute::where(['product_id' => $product->product_id])->withTrashed()->get()->pluck('attr_value')->toArray();

                        if(count($attributes) > 0) {
                            $attributes = array_unique($attributes, SORT_REGULAR);
                            $attr_values = implode(', ', $attributes);
                        }
                        
                        if($attr_values == null) 
                            $product_name = optional($product->productData)->name;
                        else
                            $product_name = optional($product->productData)->name.'('.$attr_values.')';

                        $items[]=[
                            'id' => $product->productData->id,
                            'image_url' => $product->productData->thump_image == null ? null : asset('storage/app').'/'.$product->productData->thump_image,
                            'name' => $product_name,
                            'price' => $product->product_price,
                            'qnt' => $product->product_quantity,
                            'total_price' => $product->tot_price,
                            'brand' => optional($product->productData->brand)->name
                        ];
                    }
                    $data ['items']= $items;
                    $data['paymentOption'] = $order->payment_method;
                    $data['delivery_fee'] = $order->delivery_fee;

                    if($order->payment_method=='cod')
                    {
                        $data['amountToReceive'] = $order->amount;
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
                        'message' => "Order Not Found!"
                    );
                }

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "User Not Exist!"
                );
            }

        }

        return response()->json($res);
    }

    public function typeOrderDetails(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'orderId' => 'required|integer',
            'type' => 'required|in:buy,deliver'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $uid    =   $request->user_id;
            $order_id  = $request->orderId;
            $data = array();

            $user   =   DriverUser::where('id',$uid)->where('is_active',1)->first();

            if($user)
            {

                if($request->type == 'buy') {
                    $buy_order = BuyAnything::where(['driver_id' => $user->id, 'id' =>$order_id])->first();

                    if(!$buy_order) {
                        $res = array(
                            'errorcode' => 1,
                            'data' => [],
                            'message' => "Order Not Found!"
                        );

                        return response()->json($res);
                    }

                    $buy_driver_order = OrderBuyAnything::where('order_id', $buy_order->id)->first();

                    if($buy_driver_order) {
                        $buy_order_status = $buy_driver_order->orderStatus->name;
                    } else {
                        $buy_order_status = 'Not Started';
                    }   

                    $products = array();

                    $items = json_decode($buy_order->buy_items);

                    if(count($items) > 0) {
                        
                        foreach($items as $item) {
                            
                            $products[] = array(
                                'name' => $item->name,
                                'qnt' => $item->quantity
                            );
                        }
                    }

                    if($buy_order->buyAddress->type==0)
                    {
                        $type= 'Home';
                    }elseif($buy_order->buyAddress->type==1)
                    {
                        $type = 'Office';
                    }else{
                        $type = 'Other';
                    }

                    $delivery_loc = array(
                        'address_type' => $type , 
                        'address' =>$buy_order->buyAddress->build_name.' ,'.$buy_order->buyAddress->area.' ,'.$buy_order->buyAddress->location, 'lat' => $buy_order->buyAddress->latitude, 'lon' => $buy_order->buyAddress->longitude
                    );

                    $date = new DateTime($buy_order->deliveryData->assign_date);
                    $assign_date = $date->format('d').' '.$date->format('M').' '.$date->format('Y');
                    $time =  date('h:i A', strtotime($buy_order->deliveryData->assign_date));

                    $image = $buy_order->image == null ? null : asset('storage/app').'/'.$buy_order->image;

                    $data = array(
                        'type' => 'buy',
                        'orderId' => $buy_order->id,
                        'customerId' => $buy_order->user_id,
                        'customerName' => $buy_order->user->name,
                        'customerPhone' => $buy_order->user->mobile,
                        'items' => $products,
                        'shopName' => $buy_order->shop_name,
                        'pickupLocation' => $buy_order->shop_location,
                        'pickupLatitude' => $buy_order->shop_latitude,
                        'pickupLongitude' => $buy_order->shop_longitude,
                        'deliveryLocation' => $buy_order->buyAddress->location,
                        'deliveryLatitude' => $buy_order->buyAddress->latitude,
                        'deliveryLongitude' => $buy_order->buyAddress->longitude,
                        'delivery_loc' => $delivery_loc,
                        'scheduledDate' => $assign_date,
                        'scheduledTime' => $time,
                        'image' => $image,
                        'driver_status' => $buy_order_status
                    );

                } else {
                    $deliver_order = DeliverAnything::where(['driver_id' => $user->id, 'id' =>$order_id])->first();

                    if(!$deliver_order) {
                        $res = array(
                            'errorcode' => 1,
                            'data' => [],
                            'message' => "Order Not Found!"
                        );

                        return response()->json($res);
                    }

                    $deliver_driver_order = OrderDeliverAnything::where('order_id', $deliver_order->id)->first();

                    if($deliver_driver_order) {
                        $deliver_order_status = $deliver_driver_order->orderStatus->name;
                    } else {
                        $deliver_order_status = 'Not Started';
                    }

                    $products = array();

                    $items = json_decode($deliver_order->buy_items);

                    if(count($items) > 0) {
                        
                        foreach($items as $item) {
                            
                            $products[] = array(
                                'name' => $item->name,
                                'qnt' => $item->quantity
                            );
                        }
                    }

                    if($deliver_order->deliverAddress->type==0)
                    {
                        $type= 'Home';
                    }elseif($deliver_order->deliverAddress->type==1)
                    {
                        $type = 'Office';
                    }else{
                        $type = 'Other';
                    }

                    $delivery_loc = array(
                        'address_type' => $type , 
                        'address' =>$deliver_order->deliverAddress->build_name.' ,'.$deliver_order->deliverAddress->area.' ,'.$deliver_order->deliverAddress->location, 'lat' => $deliver_order->deliverAddress->latitude, 'lon' => $deliver_order->deliverAddress->longitude
                    );

                    $date = new DateTime($deliver_order->deliveryData->assign_date);
                    $assign_date = $date->format('d').' '.$date->format('M').' '.$date->format('Y');
                    $time =  date('h:i A', strtotime($deliver_order->deliveryData->assign_date));

                    $image = $deliver_order->image == null ? null : asset('storage/app').'/'.$deliver_order->image;

                    $data = array(
                        'type' => 'buy',
                        'orderId' => $deliver_order->id,
                        'customerId' => $deliver_order->user_id,
                        'customerName' => $deliver_order->user->name,
                        'customerPhone' => $deliver_order->user->mobile,
                        'items' => $products,
                        'shopName' => $deliver_order->shop_name,
                        'pickupLocation' => $deliver_order->pickupAddress->location,
                        'pickupLatitude' => $deliver_order->pickupAddress->latitude,
                        'pickupLongitude' => $deliver_order->pickupAddress->longitude,
                        'deliveryLocation' => $deliver_order->deliverAddress->location,
                        'deliveryLatitude' => $deliver_order->deliverAddress->latitude,
                        'deliveryLongitude' => $deliver_order->deliverAddress->longitude,
                        'delivery_loc' => $delivery_loc,
                        'scheduledDate' => $assign_date,
                        'scheduledTime' => $time,
                        'image' => $image,
                        'driver_status' => $deliver_order_status
                    );
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
                    'message' => "User Not Exist!"
                );
            }

        }

        return response()->json($res);
    }


    public function startJob(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'orderId' => 'required|integer',
            // 'key' => 'sometimes|required|in:buy,deliver,order'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $uid    =   $request->user_id;
            $order_id  = $request->orderId;

            $user   =   DriverUser::with('orders')->where('id',$uid)->where('is_active',1)->first();
            
            if($user)
            {   

                $current_date_time = Carbon::now()->toDateTimeString(); 

                // if($request->key == 'buy') {

                //     $buy_order_list = OrderBuyAnything::where(['driver_id' => $user->id,'order_id' => $order_id])->whereDate('assign_date',Carbon::today())->where('status_id','<>',2)->first();

                //     if(!$buy_order_list) {
                //         $res = array(
                //             'errorcode' => 1,
                //             'data' => [],
                //             'message' => "Order Not Found!"
                //         );

                //         return response()->json($res);
                //     }

                //     $buy_order_list->start_time = $current_date_time;
                //     $buy_order_list->orderData->status = 1;
                //     $buy_order_list->save();

                // } elseif($request->key == 'deliver') {

                //     $deliver_order_list = DeliverAnything::where(['driver_id' => $user->id,'order_id' => $order_id])->whereDate('assign_date',Carbon::today())->where('status_id','<>',2)->first();

                //     if(!$deliver_order_list) {
                //         $res = array(
                //             'errorcode' => 1,
                //             'data' => [],
                //             'message' => "Order Not Found!"
                //         );

                //         return response()->json($res);
                //     }
                    
                //     $deliver_order_list->start_time = $current_date_time;
                //     $deliver_order_list->orderData->status = 1;
                //     $deliver_order_list->save();

                // } else {
                    $order_list = DriverOrder::where(['driver_id'=>$user->id,'order_id' =>$order_id])->whereDate('assigned_date',Carbon::today())->where('status','<>',2)->first();

                    if($order_list)
                    {
                        $order_list->start_time = $current_date_time;
                        $order_list->status = 1;
                        $order_list->save();

                        $order = Order::where('id',$order_list->order_id)->with(['shop','user','orderStatus','orderAddresses','orderProducts'])->first();

                        if($order)
                        {
                            $status = Status::where('slug','shipped')->first();

                            $order->status_id = $status->id;
                            $order->save();

                            OrderStatus::updateOrCreate([
                                'order_id'  => $order->id,
                                'status_id' => $status->id
                              ],[
                                'order_product_id' => NULL
                              ]);


                              $notification = NotificationCategory::where('slug','shipped')->first();

                              if($notification)
                              {
                                  $notify = new Notification;
                                  $notify->notification_id =  $notification->id ?? '';
                                  $notify->from_id =  NULL;
                                  $notify->to_id = $order->user_id;
                                  $notify->order_id = $order->id;
                                  $notify->is_view = 0;
                                  $notify->save();
                
                                  $message = array(
                                    'type' => 'order',
                                    'title' => $notification->title,
                                    'order_id' => $order->id ?? '',
                                    'notification_id' => $notify->id ?? '',
                                    'message' => 'Your order no '.$order->order_no.' has been '.$notification->title
                                  );
                            
                                  $device_types=UserDevice::where('user_id',$order->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
                
                                  if (!empty($device_types))
                                  SiteHelper::sendAndroidPush($device_types, $message);
                
                                  //$iosdevice=UserDevice::where('user_id',$order->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                                }
                        }

                    }else{
                        $res = array(
                            'errorcode' => 1,
                            'data' => [],
                            'message' => "Order Not Found!"
                        );

                        return response()->json($res);
                    }
                // }

                $res = array(
                        'errorcode' => 0,
                        'data' => [],
                        'message' => "Success!"
                    ); 

                
            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "User Not Exist!"
                );
            }

        }

        return response()->json($res);
    }


    public function completeJob(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'orderId' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $uid    =   $request->user_id;
            $order_id  = $request->orderId;

            $user   =   DriverUser::with('orders')->where('id',$uid)->where('is_active',1)->first();

            if($user)
            {
                $order_list = DriverOrder::where(['driver_id'=>$user->id,'order_id' =>$order_id])->whereDate('assigned_date',Carbon::today())->where('status','<>',2)->first();

                if($order_list)
                {
                    $current_date_time = Carbon::now()->toDateTimeString(); 

                    $order_list->end_time = $current_date_time;
                    $order_list->status = 2;
                    $order_list->save();

                    $order = Order::where('id',$order_list->order_id)->with(['shop','user','orderStatus','orderAddresses','orderProducts'])->first();

                    if($order)
                    {
                        $status = Status::where('slug','delivered')->first();

                        $order->status_id = $status->id;
                        $order->save();

                        OrderStatus::updateOrCreate([
                            'order_id'  => $order->id,
                            'status_id' => $status->id
                          ],[
                            'order_product_id' => NULL
                          ]);

                        $commission_data = SellerOrderData::where(['order_id' => $order->id])->first();

                        if($commission_data)
                            $commission_data->update(['status' => 1]);

                        $customer = User::where('id', $order->user_id)->whereNotNull('referral_by')->first();

                        if($customer) {

                            $point = PointHistory::where('user_id', $customer->referral_by)->where('from_id', $customer->id)->where('slug', 'credited_by_purchase_referred_customer')->where(['is_credit'=> 1 ,'is_valid' => 0])->first();

                            if($point) {   
                                $point->is_valid  = 1;
                                $point->order_id  = $order_id;
                                $point->save();
                             
                                $customer = User::find($point->user_id);

                                $customer->wallet = $point->points + $customer->wallet;

                                $customer->save();
                            }
                        }
                        

                        if($order->amount >= 100) {

                            $customer = User::find($order->user_id);
                    
                            if($customer)
                            {
                                $point = PointHistory::where('user_id', $order->user_id)->where(['is_credit'=> 1 ,'is_valid' => 0])->where('order_id', $order->id)->where('slug', 'credited_by_purchasing_order')->first();

                                if($point)
                                {
                                    $point->is_valid  = 1;
                                    $point->save();

                                    $customer = User::find($point->user_id);

                                    $customer->wallet = $point->points + $customer->wallet;

                                    $customer->save();
                                }

                            }
                        } 


                          $notification = NotificationCategory::where('slug','delivered')->first();

                          if($notification)
                          {
                              $notify = new Notification;
                              $notify->notification_id =  $notification->id ?? '';
                              $notify->from_id =  NULL;
                              $notify->to_id = $order->user_id;
                              $notify->order_id = $order->id;
                              $notify->is_view = 0;
                              $notify->save();
            
                              $message = array(
                                'type' => 'order',
                                'title' => $notification->title,
                                'order_id' => $order->id ?? '',
                                'notification_id' => $notify->id ?? '',
                                'message' => 'Your order no '.$order->order_no.' has been '.$notification->title
                              );
                        
                              $device_types=UserDevice::where('user_id',$order->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
            
                              if (!empty($device_types))
                              SiteHelper::sendAndroidPush($device_types, $message);
            
                              //$iosdevice=UserDevice::where('user_id',$order->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
                            
                              $res = array(
                                'errorcode' => 0,
                                'data' => [],
                                'message' => "Success!"
                            ); 

                            }
                    }
                   
                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => [],
                        'message' => "Order Not Found!"
                    );
                }
            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "User Not Exist!"
                );
            }
        }

        return response()->json($res);
    }

    public function getCompletedList(Request $request)
    {

        $rules = array(
            'user_id' => 'required|integer',
            'page' => 'required',
            'batchSize' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            
            $uid    =   $request->user_id;

            $user   =   DriverUser::with('orders')->where('id',$uid)->where('is_active',1)->first();

          
            if($user)
            {
                $data = [];
                $order_lists = DriverOrder::where('driver_id',$user->id)->where('status',2)->orderBy('id','desc')->get();
               
                if($order_lists->count()>0)
                {
                    foreach($order_lists as $list)
                    {
                        $order = Order::where('id',$list->order_id)->with(['shop','user','orderStatus','orderAddresses','orderProducts'])->first();

                        $date = new DateTime($list->assigned_date);
                        $assign_date = $date->format('d').' '.$date->format('M').' '.$date->format('Y');
                        
                        $order_addr = OrderAddress::where('order_id',$list->order_id)->first();

                       $data[] = array(
                            'orderId' => $list->order_id,
                            'delivery_fee' => $order->delivery_fee,
                            'location' => $order_addr->location,
                           // 'status'   => $status,
                            'customer_name' => $order->user->name,
                            'fullAddress' => $order_addr->build_name .' ,'.$order_addr->area.' ,'.$order_addr->pincode,
                            'customer_name' => $order->user->name,
                            'shopName' => $order->shop->name,
                            'pickupLocation' => $order->shop->location,
                            'pickupLatitude' => $order->shop->latitude,
                            'pickupLongitude' => $order->shop->longitude,
                            'date' => $assign_date
                       );
                        // $dates = $assign_date;
                    }

                    // $data[]= array('list'=>$items);
            
                    // $data['date'] = $dates;

                    $data = app('Modules\Api\Http\Controllers\CustomerApiController')->paginate($data, $request->batchSize);

                    $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );

                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => [],
                        'message' => "Orders Not Exist!"
                    );
                }

            
            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "User Not Exist!"
                );
            }


        }
        return response()->json($res);
    }

    public function logout(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
           // 'device_token' => 'required|string'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user =  DriverUser::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                DriverUserDevice::where(['deliveryboy_id' => $request->user_id])->update(['logout_time' => date('Y-m-d H:i:s')]);
                
                $res = array(
                    'errorcode' => 0,
                    'data' => [],
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }


    public function sendOtp(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'random_otp' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $driver_id = $request->user_id;
            $user_id   = $request->customer_id;
            $userOtp   = $request->random_otp;

            $otp = OrderOtp::where(['driver_id' => $driver_id,'user_id' => $user_id ,'status'=>0])->first();

            if($otp) {
                $otp->update(['otp' => $userOtp, 'status' => 0]);
            } else {
                $otp = OrderOtp::create([
                    'driver_id' => $driver_id,
                    'user_id' => $user_id,
                    'otp' => $userOtp,
                    'status' => 0
                ]);
            }

            $user = User::where('id',$user_id)->first();

            if($user)
            {
                $apiKey = urlencode('q59xlcpmmhI-09rjL4xmNPQalKFpxthozVj58xCIA0');

                // $sms_content = 'Otp for complete the order is '.$userOtp;
                
                // $url1 = 'thesmsbuddy.com/api/v1/sms/send?key=YEpXB7CZtP3q0nA1lQJOC75kG94jSlWd&type=1&to='.$user->mobile.'&sender=KLDAFY&message='.urlencode($sms_content).'&flash=0';

                $sms_content = 'Dear Customer, Thank you for ordering from DAFY. Your OTP for completing the order is '.$userOtp.'. Keep Shopping.';

                $url1 = 'thesmsbuddy.com/api/v1/sms/send?key=YEpXB7CZtP3q0nA1lQJOC75kG94jSlWd&type=1&to='.$user->mobile.'&sender=KLDAFY&message='.urlencode($sms_content).'&flash=0&template_id=1307162089321997015';
                
                $response = '';
                $ch = curl_init();
                curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url1
                ));
                $response = curl_exec($ch);

                curl_close ($ch);
            }

            $res = array(
                'errorcode' => 0,
                'data' => [],
                'message' => "Success!"
            );

        }

        return response()->json($res);
    }


    public function confirmOtp(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'order_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'random_otp' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $driver_id = $request->user_id;
            $user_id   = $request->customer_id;
            $order_id  = $request->order_id;
            $userOtp   = $request->random_otp;

            $otp = OrderOtp::where(['driver_id' => $driver_id,'user_id' => $user_id,'status'=>0])->first();

            if($otp) 
            {
                if($otp->otp == $userOtp)
                {
                    $otp->status = 1;
                    $otp->save();

                    $res = array(
                        'errorcode' => 0,
                        'data' => [],
                        'message' => "Success!"
                    );
                }else{
                    $res = array(
                        'errorcode' => 1,
                        'data' => [],
                        'message' => "Otp not valid!"
                    );
                }

            }else{
                $res = array(
                    'errorcode' => 1,
                    'data' => [],
                    'message' => "Otp not exist!"
                );
            }

        }

        return response()->json($res);
    }
}
