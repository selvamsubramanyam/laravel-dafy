<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderProduct;
use Modules\Product\Entities\Product;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\Status;
use Modules\Users\Entities\PointHistory;
use Modules\Users\Entities\User;
use Modules\Admin\Entities\NotificationCategory;
use Modules\Admin\Entities\Notification;
use Modules\Users\Entities\UserDevice;
use Modules\DeliveryApp\Entities\DriverUser;
use Modules\DeliveryApp\Entities\DriverOrder;
use Modules\Admin\Entities\SellerOrderData;
use App\Exports\OrderExport;
use Excel;
use Carbon\Carbon;
use Auth;
use SiteHelper;
use Mail;
use PDF;
use DateTime;

class OrderController extends Controller
{ 
    protected $BusinessApiController;
    function __construct()
    {
        $this->middleware('permission:order-list', ['only' => ['order','orderList']]);
        $this->middleware('permission:order-view', ['only' => ['orderView']]);
        $this->middleware('permission:order-edit', ['only' => ['changeOrderStatus']]);
        $this->middleware('permission:order-delete', ['only' => ['deleteOrder']]);
    }

    public function order(Request $request)
    {   
        $status = $request->status;
        return view('admin::Order.order',compact('status'));
    }

    public function orderList(Request $request)
    { 
        $search   = $request->search['value'];
        $status   = $request->status;
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $order = Order::with(['shop','user','orderStatus','eachStatus.status','drivers'])->where('is_active',1)->orderBy('created_at', 'desc');

        if(Auth::guard('seller')->check())
        {
            $order = $order->where('shop_id',Auth::guard('seller')->id());
        }

        
        if($status!=null)
        {

          $status_slug = Status::where('slug',$status)->first();

          if($status=='ordered')
          {
           
              $order->whereHas('orderStatus',function($query){
                  $query->where('slug','=','ordered');
              });

          }
          elseif($status=='accepted')
          {

            
            if(Auth::guard('seller')->check())
            {
              
              $order->whereHas('eachStatus.status',function($query){
                $query->where('slug','=','accepted');
              });
           
            }else{
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','accepted');
              });
            }

          }
          elseif($status=='shipped')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','shipped');
              });

          }
          elseif($status=='delivered')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','delivered');
              });
          }
          elseif($status=='cancelled')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','cancelled');
              });
          }
          elseif($status=='rejected')
          {

            $order->whereHas('orderStatus',function($query){
              $query->where('slug','=','rejected');
            });

          }else{
                 //

          }

        }else{
          if(Auth::guard('seller')->check())
          {
    
            $slug = Status::where('slug','=','rejected')->value('id');
         
            $ordered = Status::where('slug','ordered')->first();
            $order->whereHas('eachStatus',function($query)use($slug){
              $query->where('status_id','!=',$slug);
            })->where('status_id','!=',$ordered->id)->where('status_id','!=',$slug);
          }
          
        }

        
        if ($search != '') 
        {
            if(Auth::guard('admin')->check())
            {
              if(!is_null($status))
              {
              $order = $order->where('status_id',$status_slug->id);
                
              $order = $order->where('order_no', 'LIKE', '%'.$search.'%')->orWhereHas('shop',function($query) use ($search){
                    $query->where('name','LIKE', '%'.$search.'%');
                })->where('status_id',$status_slug->id);
              $order = $order->orWhereHas('user',function($query) use ($search){
                    $query->where('name','LIKE', '%'.$search.'%');
                })->where('status_id',$status_slug->id);
                // ->orWhereHas('orderStatus',function($query) use ($search){
                //     $query->where('name','LIKE', '%'.$search.'%');
                // });
              }else{

                $order = $order->where('order_no', 'LIKE', '%'.$search.'%')->orWhereHas('shop',function($query) use ($search){
                      $query->where('name','LIKE', '%'.$search.'%');
                  });
                $order = $order->orWhereHas('user',function($query) use ($search){
                      $query->where('name','LIKE', '%'.$search.'%');
                  });
                $order = $order->orWhereHas('orderStatus',function($query) use ($search){
                      $query->where('name','LIKE', '%'.$search.'%');
                  });

              }
            }

            if(Auth::guard('seller')->check())
            {
              if(!is_null($status))
              {   
                $order = $order->where('shop_id',Auth::guard('seller')->id())->where('status_id',$status_slug->id);
                
                $order = $order->where('order_no', 'LIKE', '%'.$search.'%')->orWhereHas('shop',function($query) use ($search){
                    $query->where('name','LIKE', '%'.$search.'%');
                })->where('shop_id',Auth::guard('seller')->id())->where('status_id',$status_slug->id);
                
                $order = $order->orWhereHas('user',function($query) use ($search){
                    $query->where('name','LIKE', '%'.$search.'%');
                })->where('shop_id',Auth::guard('seller')->id())->where('status_id',$status_slug->id);;
                
                // $order = $order->orWhereHas('orderStatus',function($query) use ($search){
                //     $query->where('name','LIKE', '%'.$search.'%');
                // })->where('shop_id',Auth::guard('seller')->id());;
              }else{

                $slugs = Status::where('slug','rejected')->value('id');
                $ordered = Status::where('slug','ordered')->first();
              
          

                $order = $order->where('shop_id',Auth::guard('seller')->id());
                
                $order = $order->where('order_no', 'LIKE', '%'.$search.'%')->whereHas('eachStatus',function($query)use($slugs){
                  $query->where('status_id','!=',$slugs);
                })->orWhereHas('shop',function($query) use ($search){
                    $query->where('name','LIKE', '%'.$search.'%');
                })->where('shop_id',Auth::guard('seller')->id())->where('status_id','!=',$ordered->id)->where('status_id','!=',$slug);
                
                $order = $order->orWhereHas('user',function($query) use ($search){
                    $query->where('name','LIKE', '%'.$search.'%');
                })->whereHas('eachStatus',function($query)use($slugs){
                  $query->where('status_id','!=',$slugs);
                })->where('shop_id',Auth::guard('seller')->id())->where('status_id','!=',$ordered->id)->where('status_id','!=',$slug);;
                
                
                $order = $order->orWhereHas('orderStatus',function($query) use ($search){
                    $query->where('name','LIKE', '%'.$search.'%');
                })->whereHas('eachStatus',function($query)use($slugs){
                  $query->where('status_id','!=',$slugs);
                })->where('shop_id',Auth::guard('seller')->id())->where('status_id','!=',$ordered->id)->where('status_id','!=',$slug);;
                

              }
            }
        }

        $total = $order->count();
        
        $result['data'] = $order->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function orderView($id)
    {
        $order = Order::where('id',$id)->with(['shop','user','orderStatus','orderAddressesView','orderProducts'])->first();

        $order_status =  OrderStatus::where('order_id',$id)->orderby('id')->get();
        $deliver_status = OrderStatus::whereHas('status', function($q) {
          $q->where(['slug' => 'delivered']);
        })->where('order_id', $id)->first();

        $status_val = Status::where('is_active',1)->whereNotIn('slug', ['pending', 'order_confirmed', 'item_not_available'])->orderby('postion')->get();
       

        return view('admin::Order.orderView',compact('order','order_status','status_val', 'deliver_status'));
    }

    public function searchOrder(Request $request)
    {

        
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        // $date=date('Y-m-d');
        $fromdate=$request->fromdate;
        
        $todate=$request->todate;

        $dateFrom = new DateTime($request->fromdate);
        $dateFrom_date = $dateFrom->format('Y-m-d');

        $dateTo = new DateTime($request->todate);
        $dateTo_date = $dateTo->format('Y-m-d');

        $status=$request->Status;
   
        $order = Order::with(['shop','user','orderStatus'])->where('is_active',1);
 
        if(Auth::guard('seller')->check())
        {
            $order = $order->where('shop_id',Auth::guard('seller')->id());
        }
        

        if($dateFrom_date!=null && $dateTo_date!=null && $status == null)
        { 
          // dd('ss');
          $order->whereBetween('order.created_at', [$dateFrom_date.' 00:00:00', $dateTo_date.' 23:59:59']);
          
        }


        if($status!=null && $fromdate!=null &&  $todate!=null)
        {
         
          $order->whereBetween('created_at', [$fromdate, $todate]);
          
          if($status=='ordered')
          {
           
              $order->whereHas('orderStatus',function($query){
                  $query->where('slug','=','ordered');
              });

          }
          elseif($status=='accepted')
          {
           
           
                $order->whereHas('orderStatus',function($query){
                  $query->where('slug','=','accepted');
                });
            

          }
          elseif($status=='shipped')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','shipped');
              });

          }
          elseif($status=='delivered')
          {
              // dd($status);
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','delivered');
              });
          }
          elseif($status=='cancelled')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','cancelled');
              });
          }
          elseif($status=='rejected')
          {

            $order->whereHas('orderStatus',function($query){
              $query->where('slug','=','rejected');
            });

          }else{

            $order->whereHas('orderStatus',function($query) use($status){
              $query->where('slug','=',$status);
            });

          }

        }

        if($status!=null && $fromdate==null &&  $todate==null)
        {
         
          if($status=='ordered')
          {
           
              $order->whereHas('orderStatus',function($query){
                  $query->where('slug','=','ordered');
              });

          }
          elseif($status=='accepted')
          {

            if(Auth::guard('seller')->check())
            {
             
              $slugs = Status::where('slug','rejected')->value('id');
              $ordered = Status::where('slug','ordered')->first();
              $order->whereHas('eachStatus',function($query)use($slugs){
                $query->where('status_id','!=',$slugs);
              })->where('status_id','!=',$ordered->id)->where('status_id','!=',$slugs);;
            }else{
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','accepted');
              });
            }
          }
          elseif($status=='shipped')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','shipped');
              });

          }
          elseif($status=='delivered')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','delivered');
              });
          }
          elseif($status=='cancelled')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','cancelled');
              });
          }
          elseif($status=='rejected')
          {

            $order->whereHas('orderStatus',function($query){
              $query->where('slug','=','rejected');
            });

          }else{
            if(Auth::guard('seller')->check())
            {
             
              $slugs = Status::where('slug','rejected')->value('id');
              $ordered = Status::where('slug','ordered')->first();
              $order->whereHas('eachStatus',function($query)use($slugs){
                $query->where('status_id','!=',$slugs);
              })->where('status_id','!=',$ordered->id)->where('status_id','!=',$slugs);
            }else{
            $order->whereHas('orderStatus',function($query) use($status){
              $query->where('slug','=',$status);
            });
          }

          }

        }else{

          if(Auth::guard('seller')->check())
          {
           
            $slugs = Status::where('slug','rejected')->value('id');
            $ordered = Status::where('slug','ordered')->first();
            $order->whereHas('eachStatus',function($query)use($slugs){
              $query->where('status_id','!=',$slugs);
            })->where('status_id','!=',$ordered->id)->where('status_id','!=',$slugs);
          }
        }

        $total = $order->orderBy('created_at', 'desc')->count();
        // $total  =  $order->count();
        if($request->length==null)
        {
          $request->length=1;
        }

        $result['data'] = $order->take($request->length)->skip($request->start)->get();
        
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result); 


    }

    //To download invoice
    public function invoiceDownload($order_id)
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

        view()->share(['order' => $order]);

        $pdf = PDF::loadView('order::invoice')->setPaper('a4', '');

        // Mail::send('order::email.invoice', $mail, function ($m) use ($mail,$pdf) { 
        //     $m->from('aljo@webcastle.in', '');
        //     $m->to('aljo@webcastle.in')->subject('Dafy Invoice - #'.$mail['order_id'])->attachData($pdf->output(), "invoice.pdf");
        // });

        return $pdf->download($order->invoice_no.'.pdf');
    }


    public function changeOrderStatus(Request $request)
    {
        if($request->ajax()){

         

          $status_val = $request->status;
          $order_id = $request->order_id;
          $reject  = $request->input('reject',NULL);
          $notification_slug = '';

          if(Auth::guard('admin')->check())
          {
            $from_id = Auth::guard('admin')->id();
            $admin_id = Auth::guard('admin')->id();
          }else{
            $from_id = NULL;
            $admin_id = NULL;
          }

          $status = Status::where('slug',$status_val)->first();
          $order = Order::find($order_id);
          $order->status_id = $status->id;
          $order->reason = $reject;
          $order->admin_id = $admin_id;
          $order->save();

          if($status_val == 'cancelled' && $order->is_reverted == 0) {

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

          } elseif($status_val == 'rejected' && $order->is_reverted == 0) {

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

            
          } elseif($status_val == 'failed' && $order->is_reverted == 0) {

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
            
          }

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
                    'notification_id' => $notify->id ?? '',
                    'message' => 'Your order no '.$order->order_no.' has been '.$notification->title
                  );
            
                  $device_types=UserDevice::where('user_id',$order->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                  if (!empty($device_types))
                  SiteHelper::sendAndroidPush($device_types, $message);

                  $iosdevice=UserDevice::where('user_id',$order->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                  // if (!empty($iosdevice)) 
                  // SiteHelper::sendIosPush($iosdevice, $message);
              }

          }

          if($status->slug == 'delivered')
          {   
            //Delivered status
            $driver_order = DriverOrder::where(['order_id' => $order->id])->first();

            if($driver_order) {
              $current_date_time = Carbon::now()->toDateTimeString();

              $driver_order->update([
                'start_time' => $current_date_time,
                'end_time' => $current_date_time,
                'status' => 2
              ]);
            }
            //End Delivered Status

              $commission_data = SellerOrderData::where(['order_id' => $order->id])->first();

              if($commission_data)
                  $commission_data->update(['status' => 1]);

              $user = User::where('id',$order->user_id)->whereNotNull('referral_by')->first();
              
              if($user)
              {
                $point = PointHistory::where('user_id',$user->referral_by)->where('from_id',$user->id)->where('slug','credited_by_purchase_referred_customer')
                          ->where(['is_credit'=> 1 ,'is_valid' => 0])->first();

                          if($point)
                          {
                            
                            $point->is_valid  = 1;
                            $point->order_id  = $order_id;
                            $point->save();
                             
                            $user = User::find($point->user_id);

                            $user->wallet = $point->points + $user->wallet;

                            $user->save();
                            

                          }
              }

              // if($order->amount >= 100)
              // {
              
                $user = User::find($order->user_id);
                
                if($user)
                {
                    $point = PointHistory::where('user_id',$order->user_id)->where(['is_credit'=> 1 ,'is_valid' => 0])->where('order_id',$order->id)->where('slug','credited_by_purchasing_order')->first();

                    if($point)
                    {
                        $point->is_valid  = 1;
                        $point->save();

                        $user = User::find($point->user_id);

                        $user->wallet = $point->points + $user->wallet;

                        $user->save();
                    }

                // }

              }


           
          }

          return response()->json(['success'=>'Status changed successfully.']);
        }
    }


    // public function orderExport($status)
    // {

    //   if($status)
    //   {

    //       $headers = array(
    //         "Content-type" => "text/csv",
    //         "Content-Disposition" => "attachment; filename=file.csv",
    //         "Pragma" => "no-cache",
    //         "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
    //         "Expires" => "0"
    //       );


    //       return Excel::download(new OrderExport($status), $status.'_order_list_'.Carbon::today()->toDateString().'.xlsx');

    //   }else{
        
    //     return redirect()->back();

    //   }

    // }

    public function orderExport1(Request $request)
    {
        // $date=date('Y-m-d');

        $fromdate=$request->from;
        $todate=$request->to;

        $dateFrom = new DateTime($request->from);
        $dateFrom_date = $dateFrom->format('Y-m-d');

        $dateTo = new DateTime($request->to);
        $dateTo_date = $dateTo->format('Y-m-d');

        $status=$request->status;
        // dd($status);
        $orders = Order::with(['shop','user','orderStatus'])->where('is_active',1);
 
        if(Auth::guard('seller')->check())
        {
            $orders = $orders->where('shop_id',Auth::guard('seller')->id());
        }
        
        if($dateFrom_date!=null && $dateTo_date!=null && $status == null)
        { 
          // dd('ss');
          $orders->whereBetween('order.created_at', [$dateFrom_date.' 00:00:00', $dateTo_date.' 23:59:59']);
          
        }

        if($status!=null && $fromdate!=null &&  $todate!=null)
        {
         
          $orders->whereBetween('order.created_at', [$dateFrom_date.' 00:00:00', $dateTo_date.' 23:59:59']);
          
          if($status=='ordered')
          {
           
              $orders->whereHas('orderStatus',function($query){
                  $query->where('slug','=','ordered');
              });

          }
          elseif($status=='accepted')
          {
           
           
                $orders->whereHas('orderStatus',function($query){
                  $query->where('slug','=','accepted');
                });
            

          }
          elseif($status=='shipped')
          {
              $orders->whereHas('orderStatus',function($query){
                $query->where('slug','=','shipped');
              });

          }
          elseif($status=='delivered')
          {
              // dd($status);
              $orders->whereHas('orderStatus',function($query){
                $query->where('slug','=','delivered');
              });
          }
          elseif($status=='cancelled')
          {
              $orders->whereHas('orderStatus',function($query){
                $query->where('slug','=','cancelled');
              });
          }
          elseif($status=='rejected')
          {

            $orders->whereHas('orderStatus',function($query){
              $query->where('slug','=','rejected');
            });

          }else{

            $orders->whereHas('orderStatus',function($query) use($status){
              $query->where('slug','=',$status);
            });

          }

        }

        if($status!=null && $fromdate==null &&  $todate==null)
        {
         
          if($status=='ordered')
          {
           
              $orders->whereHas('orderStatus',function($query){
                  $query->where('slug','=','ordered');
              });

          }
          elseif($status=='accepted')
          {

            if(Auth::guard('seller')->check())
            {
             
              $slugs = Status::where('slug','rejected')->value('id');
              $ordered = Status::where('slug','ordered')->first();
              $orders->whereHas('eachStatus',function($query)use($slugs){
                $query->where('status_id','!=',$slugs);
              })->where('status_id','!=',$ordered->id)->where('status_id','!=',$slugs);;
            }else{
              $orders->whereHas('orderStatus',function($query){
                $query->where('slug','=','accepted');
              });
            }
          }
          elseif($status=='shipped')
          {
              $orders->whereHas('orderStatus',function($query){
                $query->where('slug','=','shipped');
              });

          }
          elseif($status=='delivered')
          {
              $orders->whereHas('orderStatus',function($query){
                $query->where('slug','=','delivered');
              });
          }
          elseif($status=='cancelled')
          {
              $orders->whereHas('orderStatus',function($query){
                $query->where('slug','=','cancelled');
              });
          }
          elseif($status=='rejected')
          {

            $orders->whereHas('orderStatus',function($query){
              $query->where('slug','=','rejected');
            });

          }else{
            if(Auth::guard('seller')->check())
            {
             
              $slugs = Status::where('slug','rejected')->value('id');
              $ordered = Status::where('slug','ordered')->first();
              $orders->whereHas('eachStatus',function($query)use($slugs){
                $query->where('status_id','!=',$slugs);
              })->where('status_id','!=',$ordered->id)->where('status_id','!=',$slugs);
            }else{
            $orders->whereHas('orderStatus',function($query) use($status){
              $query->where('slug','=',$status);
            });
          }

          }

        }else{

          if(Auth::guard('seller')->check())
          {
           
            $slugs = Status::where('slug','rejected')->value('id');
            $ordered = Status::where('slug','ordered')->first();
            $orders->whereHas('eachStatus',function($query)use($slugs){
              $query->where('status_id','!=',$slugs);
            })->where('status_id','!=',$ordered->id)->where('status_id','!=',$slugs);
          }
        }

        $orders = $orders->orderBy('created_at', 'desc')->get();
        // dd($orders);
        if(count($orders) > 0) {
            
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $fields = array('Order No', 'User Name', 'Shop Name', 'Discount', 'Delivery Fee', 'Amount', 'Points', 'Products', 'Order Date', 'Status', 'Address');

            $order_report = array();

            $i = 0;

            foreach ($orders as $key => $order) {
                $i = $i+1;
                
                $products = '';
                
                if(!empty($order->orderProducts))
                {
                    foreach($order->orderProducts as $order_product)
                    {

                        if(!empty($order_product->productData))
                        {
                            $products.= $order_product->productData->name.' [Qty : '.$order_product->product_quantity.']';
                        }

                    }

                    $products = rtrim($products, ", ");
                }

                $address = '';
                $order_address = $order->orderAddresses;

                if($order_address) {
                  $name = $order_address->name;
                  $build_name = $order_address->build_name;
                  $area = $order_address->area;
                  $pincode = $order_address->pincode;

                  $address = 'Name : '.$name.', Building Name : '.$build_name.', Area : '.$area.', Pincode : '.$pincode;
                }

                $order_report[] = array(
                        'Order No' => $order->order_no ?? '',
                        'User Name' => $order->user->name  ?? '',
                        'Shop Name' => $order->shop->name ?? '',
                        'Discount' => $order->discount ?? '',
                        'Delivery Fee' => $order->delivery_fee ?? '',
                        'Amount' => $order->amount ?? '',
                        'Points' => $order->points ?? '',
                        'Products' => $products,
                        'Order Date' => $order->created_at ? date('d-m-Y H:i:s', strtotime($order->created_at)) : '',
                        'Status' => $order->orderStatus ?  $order->orderStatus->name : '',
                        'Address' => $address,
                    );
            }
            
            $file_name = 'order_list_'.Carbon::today()->toDateString().'.csv';
            
            header('Content-Type: text/csv; charset=utf-8');
            Header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename = '.$file_name.'');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, $fields);
            
            foreach ($order_report as $report) {
                fputcsv($output, $report);
            }
            
            fclose($output);
        } else {
            return redirect()->back()->with('message', 'No orders Found'); 
        }

    }

    public function orderGetDriver(Request $request)
    {
      if($request->ajax()){
        $driver_user = DriverUser::where('is_active',1)->get();

        if($driver_user->count()>0)
        {
          echo json_encode($driver_user);
        }
      }
    }

    public function assignDetails($id)
    {
        $driver_order = DriverOrder::where('order_id',$id)->with('order')->first();
        echo json_encode($driver_order);
    }

    public function storeDriverOrder(Request $request)
    {
      if($request->ajax()){
       
        $order = Order::where('order_no',$request->order_no)->first();
          if($order)
          {
            $driver_order = DriverOrder::where('order_id',$order->id)->with('order')->first();

              if($driver_order)
              {
                $driver_order->driver_id = $request->driver_id;
                $driver_order->order_id = $order->id;
                $driver_order->assigned_date = $request->assign_date;
                $driver_order->save();
              }else{
                DriverOrder::create([
                    'driver_id' => $request->driver_id,
                    'order_id'  => $order->id,
                    'assigned_date' =>  $request->assign_date
                ]);

              }

              $order->update(['delivery_date' => $request->assign_date]);
              
              return ['success' => true, 'message' => 'Driver assigned successfully!!'];
            }
        }
      
    }
}
