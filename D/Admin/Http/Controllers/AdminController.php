<?php

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\AdminUser;
use Modules\Admin\Entities\Settings;
use Modules\Admin\Entities\City;
use Modules\Users\Entities\UserRole;
use Modules\Category\Entities\BusinessCategory;
use Modules\Shop\Entities\BusinessCategoryShop;
use Modules\Users\Entities\BusinessSellerCategory;
use Modules\Category\Entities\BusinessCategoryField;
use Modules\Category\Entities\Control;
use Modules\Category\Entities\CategoryService;
use Modules\Shop\Entities\BusinessShop;
use Modules\Shop\Entities\Category;
use Modules\Shop\Entities\ShopCategory;
use Modules\Product\Entities\Product;
use Modules\Admin\Entities\Enquiry;
use Modules\Admin\Entities\EnquiryResult;
use Modules\Product\Entities\ProductAttribute;
use Modules\Media\Entities\Media;
use Modules\Admin\Entities\Notification;
use Modules\Admin\Entities\NotificationCategory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Users\Entities\UserDevice;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Exports\CategoryExport;
use App\Exports\ShopCategoryExport;
use Modules\Order\Entities\Order;
use Modules\Admin\Entities\SellerOrderData;
use Modules\Order\Entities\OrderStatus;
use App\Exports\VendorSaleExport;
use Excel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB, Validator, Session;
use Intervention\Image\ImageManagerStatic as Image;
use Mail;
use Auth;
use SiteHelper;
use DateTime;
use Log;

use App\Http\Requests\ShopCategoryStoreRequest;
use App\Http\Requests\ShopCategoryEditRequest;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryEditRequest;
use App\Http\Requests\AttributeStoreRequest;
use App\Http\Requests\AttributeEditRequest;
use Modules\Users\Entities\User;
use Illuminate\Validation\Rule;
use Modules\Admin\Entities\BuyAnything;
use Modules\Admin\Entities\BuyAddress;
use Modules\Admin\Entities\DeliverAddress;
use Modules\Users\Entities\UserAddress;
use Modules\Order\Entities\Status;
use Modules\DeliveryApp\Entities\DriverUser;
use Modules\DeliveryApp\Entities\DriverOrder;
use Modules\Order\Entities\OrderBuyAnything;
use Modules\Admin\Entities\DeliverAnything;
use Modules\Order\Entities\OrderDeliverAnything;





class AdminController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:category-list', ['only' => ['category','categorylist']]);
        $this->middleware('permission:category-create', ['only' => ['categoryadd','addcategory']]);
        $this->middleware('permission:category-edit', ['only' => ['editcategory','updatecategory']]);
        $this->middleware('permission:category-delete', ['only' => ['deletecategory']]);
        
        $this->middleware('permission:attribute-list', ['only' => ['attributes','getAttributes']]);
        $this->middleware('permission:attribute-create', ['only' => ['addAttributes','storeAttribute']]);
        $this->middleware('permission:attribute-edit', ['only' => ['editAttribute','updateAttribute']]);
        $this->middleware('permission:attribute-delete', ['only' => ['deleteAttribute']]);

        $this->middleware('permission:settings', ['only' => ['otherSetting','storeSettings']]);
        $this->middleware('permission:app-settings', ['only' => ['settings','appversion']]);
     
    }

    public function notificationscount() {
        $orders = Order::where(['status_id' => 2, 'notify' => 1, 'is_active' => 1])->count();
        $buy_orders = BuyAnything::where(['order_status' => 1, 'notify' => 1])->count();
        $deliver_orders = DeliverAnything::where(['order_status' => 1, 'notify' => 1])->count();
        $notify = $orders + $buy_orders + $deliver_orders;
        // dd($notify);
        return ($notify);
    }

    public function notifications() {
        $orderid_list = array();

        $orders = Order::where(['status_id' => 2, 'notify' => 1, 'is_active' => 1])->orderBy('id', 'desc')->get();

        $buy_orders = BuyAnything::where(['order_status' => 1, 'notify' => 1])->orderBy('id', 'desc')->get();

        $deliver_orders = DeliverAnything::where(['order_status' => 1, 'notify' => 1])->orderBy('id', 'desc')->get();

        $order_orderid_list = array();
        
        foreach($orders as $order) {
            $order_orderid_list[] = array(
                'Id' => $order->id,
                'type' => 'order',
                'number' => $order->order_no
            );
        }

        
        $buy_orderid_list = array();
        
        foreach($buy_orders as $buy_order) {
            $buy_orderid_list[] = array(
                'Id' => $buy_order->id,
                'type' => 'buy',
                'number' => $buy_order->order_no
            );
        }

        $deliver_orderid_list = array();
        
        foreach($deliver_orders as $deliver_order) {
            $deliver_orderid_list[] = array(
                'Id' => $deliver_order->id,
                'type' => 'deliver',
                'number' => $deliver_order->order_no
            );
        }

        $orderid_list = array_merge($order_orderid_list, $buy_orderid_list, $deliver_orderid_list);
        
        echo json_encode($orderid_list);
    }

    public function updatenotify($id)
    {
        $notify = BuyAnything::where(['id' => $id])->update(['notify' => 0]);

        return redirect()->back();

    }

    public function updatedelivernotify($id)
    {
        $notify = DeliverAnything::where(['id' => $id])->update(['notify' => 0]);
        
        return redirect()->back();

    }

    public function updateordernotify($id)
    {
        $notify = Order::where(['id' => $id])->update(['notify' => 0]);
        
        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::index');
    }

    public function home()
    {
        $events = Media::where('is_active',1)->count();
        $cities = City::where('is_active',1)->count();
      
        $shops = BusinessShop::where('is_active',1)->count();
        if(Auth::guard('admin')->check())
        {
            $categories = BusinessCategory::where(['is_active' => 1, 'parent_id' => 0])->count();
            $products = Product::where('is_active',1)->count();
        }else{
            $seller_id = Auth::guard('seller')->id();
            $products = Product::where(['seller_id'=>$seller_id,'is_active'=>1])->count();
            $shop = BusinessShop::find($seller_id);
            $count = User::where('id',$shop->seller_id)->withCount('categories')->first();
            $categories = $count->categories_count ?? 0;
        }
        
        return view('admin::dashboard',compact('events', 'cities', 'categories', 'shops', 'products'));
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 
            'password' => 'required',
        ]);

        if($request->route()->getPrefix()== '/seller')
        {
           $shop = BusinessShop::where('email',$request->email)->first();
          
           if($shop){
            $credentials = $request->only('email', 'password');
            if (Auth::guard('seller')->attempt($credentials)) {
                $user = User::find($shop->seller_id);
                $user = Auth::guard('seller')->user($user);
                
                return redirect('seller/home');
            }
            else {
                return redirect('seller/login')->withErrors(['Enter valid credentials and try again']);
            }

           }else{
            
            return redirect('seller/login')->withErrors(['No such user exist.']);
           }
        }

        else {
            
            if($request->route()->getPrefix() == '/admin')
            {
                $credentials = $request->only('email', 'password');

                if (Auth::guard('admin')->attempt($credentials)) {
                    $user = Auth::guard('admin')->user();
                
                    return redirect('admin/home');
                }
                else {
                    return redirect('admin/login')->withErrors(['Enter valid credentials and try again']);
                }
            }

        }
    }

    public function changePassword()
    {
        return view('admin::Password.changepassword');
    }


    public function passwordStore(Request $request)
    {
        if(Auth::guard('admin')->check() || Auth::guard('seller')->check())
        {
            $requestData = $request->all();
            $validator = $this->validatePasswords($requestData);
            if($validator->fails())
            {
                return back()->withErrors($validator->getMessageBag());
            }
            else
            {
                if(Auth::guard('seller')->check())
                {
                    $shop_id = Auth::guard('seller')->id();
                    $currentPassword=BusinessShop::find($shop_id);
                

                    if(Hash::check($requestData['password'], $currentPassword->password))
                    {
                        $userId = Auth::guard('seller')->id();
                        $user = BusinessShop::find($userId);
                        $user->password = Hash::make($requestData['new-password']);;
                        $user->save();
                        return back()->with('message', 'Your password has been updated successfully.');
                    }
                    else
                    {
                        return back()->withErrors(['Sorry, your current password was not recognised. Please try again.']);
                    }
                }else{

                    $user_id = Auth::guard('admin')->id();
                    
                    $currentPassword=AdminUser::find($user_id);
                

                    if(Hash::check($requestData['password'], $currentPassword->password))
                    {
                        $userId = Auth::guard('admin')->id();
                        $user = AdminUser::find($userId);
                        $user->password = Hash::make($requestData['new-password']);;
                        $user->save();
                        return back()->with('message', 'Your password has been updated successfully.');
                    }
                    else
                    {
                        return back()->withErrors(['Sorry, your current password was not recognised. Please try again.']);
                    }
                }
            }
        }
        else
        {
            // Auth check failed - redirect to domain root
            return redirect()->to('/');
        }
    }

    public function validatePasswords(array $data)
    {
        $messages = [
            'password.required' => 'Please enter your current password',
            'new-password.required' => 'Please enter a new password',
            'new-password-confirmation.not_in' => 'Sorry, common passwords are not allowed. Please try a different new password.'
        ];

        $validator = Validator::make($data, [
            'password' => 'required',
            'new-password' => ['required', 'same:new-password', 'min:4', Rule::notIn($this->bannedPasswords())],
            'new-password-confirmation' => 'required|same:new-password',
        ], $messages);

        return $validator;
    }

    public function bannedPasswords(){
        return [
            'password', '12345678', '123456789', 'baseball', 'football', 'jennifer', 'iloveyou', '11111111', '222222222', '33333333', 'qwerty123'
        ];
    }


    public function logout()
    {
       
        if(Auth::guard('seller')->check())
        {
            Auth::guard('seller')->logout();
            return redirect('/seller/login');
        }else{
            Auth::guard('admin')->logout();
            return redirect('/admin/login');
        }
       
    }

    //shop categories
    public function shopCategory()
    {
        return view('admin::Category.shop_category');
    }

    public function shopCategoryList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $category = Category::orderBy('id','desc');

        if ($search != '') 
        {
            $category->where('name', 'LIKE', '%'.$search.'%');
        }

        $total = $category->count();

        $result['data'] = $category->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function shopCategoryAdd()
    {
        // $categories = Category::where(['is_active' => 0])->get();
        
        return view('admin::Category.add_shop_category'/*, compact('categories')*/);
    }

    public function shopCategoryStore(ShopCategoryStoreRequest $request)
    {
        $validated = $request->validated();

        $slug = Str::slug($request->name);
        
        if($request->file('thump_image')){
            // $image = $request->file('pic')->store('categories');
            $image = $request->file('thump_image');
            $filename    = time().str_replace(' ', '',$image->getClientOriginalName());
            $image_resize = Image::make($image->getRealPath());              
        
            $image_resize->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save('storage/app/categories/'.$filename);
        
            $image = 'categories/'.$filename;

        }
        else {
            $image = null;
        }

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        Category::create([
                'name' => $request->name,
                'slug' => $slug,
                'icon' => $image,
                'order' => $request->order,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'Category Added Successfully.');
    }

    public function shopCategoryEdit($id)
    {
         $category = Category::where('id', $id)->first();

         return view('admin::Category.edit_shop_category', compact('category'));
    }

    public function shopCategoryUpdate(ShopCategoryEditRequest $request)
    {
        // dd($request->all());
        // $slug = Str::slug($request->title);
        $validated = $request->validated();
        $category = Category::where(['id' => $request->categoryid])->first();

        if($request->hasFile('thump_image')) {
            // $image = $request->file('pic')->store('categories');
            $image = $request->file('thump_image');
            $filename    = time().str_replace(' ', '',$image->getClientOriginalName());
            $image_resize = Image::make($image->getRealPath());              
            
            $image_resize->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            // str_replace(' ', '', $string);
            $image_resize->save('storage/app/categories/'.$filename);
        
            $image = 'categories/'.$filename;
        }
        else {
            $image = $category->icon;
        }

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        $category->update([
                'name' => $request->name,
                'icon' => $image,
                'order' => $request->order,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'Category Added Successfully.');
    }

    public function shopCategoryExport()
    {

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );


       // $categories = BusinessCategory::all();

        return Excel::download(new ShopCategoryExport(), 'shop_category_list_'.Carbon::today()->toDateString().'.xlsx');

    }

    public function category()
    {
        return view('admin::Category.category');
    }

    public function categorylist(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $category = BusinessCategory::orderBy('id','desc');

        if ($search != '') 
        {
            $category->where('name', 'LIKE', '%'.$search.'%')->orWhere('description','LIKE', '%'.$search.'%');
        }

        $total = $category->count();

        $result['data'] = $category->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function categoryadd()
    {
        $categories = BusinessCategory::where(['parent_id' => 0])->orderBy('name','asc')->get();
        
        return view('admin::Category.addcategory', compact('categories'));
    }

    public function addcategory(CategoryStoreRequest $request)
    {
        $validated = $request->validated();

        $slug = Str::slug($request->name);
        
        if($request->file('pic')){
            // $image = $request->file('pic')->store('categories');
            $image = $request->file('pic');
            $filename    = trim($image->getClientOriginalName());
            $image_resize = Image::make($image->getRealPath());              
        
            $image_resize->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save('storage/app/categories/'.$filename);
        
            $image = 'categories/'.$filename;

        }
        else {
            $image = null;
        }



        if (isset($request->last_child))
            $last_child = 1;
        else
            $last_child = 0;

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        if($request->parent_id == 0 || $request->parent_id == null) { 
            $exist_parent_category = BusinessCategory::where('name',trim($request->name))->where('parent_id', 0)->first();

            if($exist_parent_category) {
                return redirect()->back()->with('error_message', 'Parent Category name is already exist');
            }
        } else {
            $exist_category = BusinessCategory::where('name',trim($request->name))->where('parent_id', $request->parent_id)->first();

            if($exist_category) {
                return redirect()->back()->with('error_message', 'Category name is already exist');
            }
        }

        BusinessCategory::create([
                'name' => $request->name,
                'slug' => $slug,
                'image' => $image,
                'parent_id' => $request->parent_id,
                'parent_name' => $request->parent_name,
                'is_last_child' => $last_child,
                'module_id' => 1,
                'order' => $request->order,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'Category Added Successfully.');
    }

    public function updatecategory(CategoryEditRequest $request)
    {
        // dd($request->all());
        // $slug = Str::slug($request->title);
        $validated = $request->validated();
        $category = BusinessCategory::where(['id' => $request->categoryid])->first();

        if($request->hasFile('pic')) {
            // $image = $request->file('pic')->store('categories');
            $image = $request->file('pic');
            $filename    = trim($image->getClientOriginalName());
            $image_resize = Image::make($image->getRealPath());              
        
            $image_resize->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save('storage/app/categories/'.$filename);
        
            $image = 'categories/'.$filename;
        }
        else {
            $image = $category->image;
        }

        if (isset($request->last_child))
            $last_child = 1;
        else
            $last_child = 0;

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        if($category->parent_id == 0 || $category->parent_id == null) { 
            $exist_parent_category = BusinessCategory::where('name',trim($request->name))->where('parent_id', 0)->where('id', '!=', $category->id)->first();

            if($exist_parent_category) {
                return redirect()->back()->with('error_message', 'Parent Category name is already exist');
            }
        } else {
            $exist_category = BusinessCategory::where('name',trim($request->name))->where('parent_id', $category->parent_id)->where('id', '!=', $category->id)->first();

            if($exist_category) {
                return redirect()->back()->with('error_message', 'Category name is already exist');
            }
        }

        $category->update([
                'name' => $request->name,
                'image' => $image,
                'parent_name' => $request->parent_name,
                'order' => $request->order,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'Category Updated Successfully.');
    }

    public function getSubCategory(Request $request)
    {
        
        $parent_name = BusinessCategory::where(['id' => $request->category, 'is_active' => 1])->get();

        $sub_categories = BusinessCategory::where(['parent_id' => $request->category, 'is_active' => 1, 'is_last_child' => 0])->orderBy('name','asc')->get();

        return response()->json([
            'sub_categories' => $sub_categories,
            'parent_name' => $parent_name
        ]);
    }

    public function editcategory($id)
    {
         $category = BusinessCategory::where('id', $id)->first();

         return view('admin::Category.editcategory', compact('category'));
    }

    public function attributes($id)
    {
        $category = BusinessCategory::where('id', $id)->first();

        return view('admin::Category.attributes', compact('id', 'category'));
    }

    public function getAttributes(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        
        $id=$request->id;
        $field = BusinessCategoryField::where('category_id', $id)->orderBy('id','desc');

        if ($search != '') 
        {
            $field->where('field_name', 'LIKE', '%'.$search.'%')->orWhere('field_value','LIKE', '%'.$search.'%');
        }

        $total = $field->count();

        $result['data'] = $field->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function addAttributes(Request $request, $id)
    {
        // $attribute = BusinessCategoryField::where(['id' => $id])->first();
        $category = BusinessCategory::where('id', $id)->first();
        $controls = Control::all();

        return view('admin::Category.addAttribute', compact('id', 'category', 'controls'));
    }

    public function editAttribute($id)
    {   
        $attribute = BusinessCategoryField::where('id', $id)->first();
        $category = BusinessCategory::where('id', $attribute->category_id)->first();
        $controls = Control::all();

        return view('admin::Category.editAttribute',compact('attribute', 'category', 'controls'));
    }

    public function storeAttribute(AttributeStoreRequest $request)
    {
        $validated = $request->validated();
        $is_filter = 0;
        $is_detail_filter = 0;

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        if($request->is_filter == 1)
            $is_filter = 1;

        if($request->is_detail_filter == 1)
            $is_detail_filter = 1;

        BusinessCategoryField::create([
                'category_id' => $request->id,
                'field_name' => $request->field_name,
                'field_value' => $request->values,
                'control' => $request->control,
                'is_filter' => $is_filter,
                'is_detail_filter' => $is_detail_filter,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'Category Added Successfully.');
    }

    public function updateAttribute(AttributeEditRequest $request)
    {
        // dd($request->all());
        // $validated = $request->validated();
        $is_filter = 0;
        $is_detail_filter = 0;

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        $old_attribute = BusinessCategoryField::where('id', $request->id)->first();
        // dd($request->values);
        if($request->values != '' || $request->values == null) {
            $values = $old_attribute->field_value.','.$request->values;
        } else {
            $values = $request->values;
        }
        // dd($values);
        if($request->is_filter == 1)
            $is_filter = 1;

        if($request->is_detail_filter == 1)
            $is_detail_filter = 1;

        BusinessCategoryField::where('id', $request->id)->update([
                // 'category_id' => $request->id,
                'field_name' => $request->field_name,
                'field_value' => $values,
                'control' => $request->control,
                'is_filter' => $is_filter,
                'is_detail_filter' => $is_detail_filter,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'Category Added Successfully.');
    }

    public function deleteCategory(Request $request,$id)
    { 
        
        if($request->ajax())
        {
            $category = BusinessCategory::where(['id' => $id])->first();

            if($category->parent_id == 0) {
                BusinessCategoryShop::where(['main_category_id' => $id])->delete();
                BusinessSellerCategory::where(['main_category_id' => $id])->delete();
            } else {
                BusinessCategoryShop::where(['category_id' => $id])->delete();

                $fields = BusinessCategoryField::where(['category_id' => $id])->get();

                foreach ($fields as $key => $field) {
                    ProductAttribute::where(['attribute_id' => $field->id])->delete();
                    $field->delete();
                }
            }

            $category->delete();
            
        }
    }

    
    public function services($id)
    {
        $service = CategoryService::where(['category_id' => $id])->first();

        if($service)
            $flag = 1;
        else
            $flag = 0;

        return view('admin::Category.addService', compact('id', 'flag', 'service'));
    }

    public function storeService(Request $request)
    {
        if(isset($request->edit_values)) {
            
            // CategoryService::where(['id' => $request->id])->update(['']);
        } else {
            CategoryService::create([
                    'category_id' => $request->id,
                    'service_names' => $request->values
                ]);
        }

        return redirect()->back()->with('message', 'Services Added Successfully.');
    }

    public function deleteAttribute($id)
    {
        BusinessCategoryField::where('id', $id)->delete();

        return redirect()->back()->with('message', 'Attribute Deleted Successfully.');
    }

    public function event()
    {

    return view('admin::event');

    }
    public function eventlist(Request $request)
    {

        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
      

      $offer=Media::orderBy('id','desc');

        if ($search != '') 
        {
            $offer->where('title', 'LIKE', '%'.$search.'%')->orWhere('description','LIKE', '%'.$search.'%');
                
            

            
        }

     
        $total  =$offer->count();

        $result['data'] = $offer->take($request->length)->skip($request->start)->get();
        
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result); 

    }

    public function eventsadd()
    {

    return view('admin::addevents');

    }
    public function addevents(Request $request)
    {
        
        

        $events=new Media;
        $events->title=$request->title;
        $events->description=$request->description;
        //$events->video_url=$request->videourl;
        $url=$request->videourl;
       if(strlen($url) > 11)
        {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
            {
               
                $events->video_url=$match[1];
                //return $match[1];

            }
            else
                return false;
        }

       

          $events->from_date=$request->validfrom;
          $events->to_date=$request->validto;
          $events->interactive_date=$request->interactive;
          $events->is_active=$request->status;
         if($request->file('pic'))
            {
             $image = $request->file('pic')->store('events');

             $img=$request->file('pic');
             $filename    = $img->getClientOriginalName();
             $image_resize = Image::make($img->getRealPath());              
             $image_resize->resize(300, 300);
             $image_resize->save('storage/app/events/' .$filename);
          
            }
            else
            {
            $image="";
            }

            $events->thumbnail_url='events/'.$filename;
            $events->poster=$image;
            $events->save();
            $eventId=$events->id;
            $shareevent=Media::where('id',$eventId)->first();
            $shareevent->share_url="https://pipli.in/media?postId=".$eventId;
            $shareevent->save();

            return redirect()->back()->with('message', 'Events Added Successfully.');

   
    }


    public function editevents($id)
    {
         $events=Media::where('id',$id)->first();
         return view('admin::editevents',compact('events'));


    }

    public function updateevents(Request $request)
    {
        
        $id=$request->eventid;
        $events=Media::where('id',$id)->first();
        $events->title=$request->title;
        $events->description=$request->description;
        //$events->video_url=$request->videourl;
        $url=$request->videourl;
        $events->is_active=$request->status;
       if(strlen($url) > 11)
        {
           // dd($url);
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
            {
              // dd($match[1]);
                $events->video_url=$match[1];
                //return $match[1];

            }
            else
                return false;
        }

       
        
        //$events->video_url = $url;
        $events->from_date=$request->validfrom;
        $events->to_date=$request->validto;
        $events->interactive_date=$request->interactive;
        
         if($request->file('pic'))
            {
            $image = $request->file('pic')->store('events');
            
            $img=$request->file('pic');
             $filename    = $img->getClientOriginalName();
             $image_resize = Image::make($img->getRealPath());              
             $image_resize->resize(300, 300);
             $image_resize->save('storage/app/events/' .$filename);
             $events->thumbnail_url='events/'.$filename;
             $events->poster=$image;
            }
            

            
            $events->save();
           

            return redirect()->back()->with('message', 'Events Updated Successfully.');



    }

    public function delevents($id)
    {
        $events=Media::where('id',$id)->delete();
        return redirect()->back()->with('message', 'Events Deleted Successfully.');


    }

    public function participants()
    {
        
        return view('admin::participants');

    }
    public function participantsvideo()
    {
        $events=Media::all();
        return view('admin::participantsvideo',compact('events'));

    }

    public function addparticipantsvideo(Request $request)
    {

        $events=new ContestVideo;
        $events->media_id=$request->events;
        $events->title=$request->title;
        $events->description=$request->description;
        //$events->video_url=$request->videourl;
        $url=$request->videourl;
           if(strlen($url) > 11)
            {
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
                {
                   
                    $events->video_url=$match[1];
                    //return $match[1];

                }
                else
                    return false;
            }

       

            if($request->file('pic'))
            {
            $image = $request->file('pic')->store('userevents');

             $img=$request->file('pic');
             $filename    = $img->getClientOriginalName();
             $image_resize = Image::make($img->getRealPath());              
             $image_resize->resize(300, 300);
             $image_resize->save('storage/app/userevents/' .$filename);
             $events->poster=$image;

            }
            else
            {
            $image="";
            }

            $events->thumbnail_url='userevents/'.$filename;
            
            $events->save();
            $eventId=$events->id;
            $shareevent=ContestVideo::where('id',$eventId)->first();
            $shareevent->share_url="https://pipli.in/contestant?postId=".$eventId;

            $shareevent->save();
            return redirect()->back()->with('message', 'Events Added Successfully.');

    }

    public function participants_list(Request $request)
    {

        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
      

      $uservideo=ContestVideo::with('events')->orderBy('id','desc');

        if ($search != '') 
        {
            $uservideo->where('title', 'LIKE', '%'.$search.'%')->orWhere('description','LIKE', '%'.$search.'%');
                
            

            
        }

     
        $total  =$uservideo->count();

        $result['data'] = $uservideo->take($request->length)->skip($request->start)->get();
        
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result); 

    }
    public function participants_edit($id)
    {
         $event_detail=Media::all();
         $events=ContestVideo::with('events')->where('id',$id)->first();
         return view('admin::editparticipants',compact('events','event_detail'));

    }
    public function updateparticipants(Request $request)
    {
        $id=$request->eventid;
        $events=ContestVideo::with('events')->where('id',$id)->first();
         $events->media_id=$request->events;
        $events->title=$request->title;
        $events->description=$request->description;
        //$events->video_url=$request->videourl;
        $url=$request->videourl;
           if(strlen($url) > 11)
            {
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
                {
                   
                    $events->video_url=$match[1];
                    //return $match[1];

                }
                else
                    return false;
            }

       

            if($request->file('pic'))
            {
            $image = $request->file('pic')->store('userevents');
           

             $img=$request->file('pic');
             $filename    = $img->getClientOriginalName();
             $image_resize = Image::make($img->getRealPath());              
             $image_resize->resize(300, 300);
             $image_resize->save('storage/app/userevents/' .$filename);
             $events->poster=$image;
             $events->thumbnail_url='userevents/'.$filename;
            }
           

            
           // $events->video_url = $url;
            $events->save();
            return redirect()->back()->with('message', 'Events Updated Successfully.');
    }
    public function deleteparticipants($id)
    {

        $events=ContestVideo::where('id',$id)->delete();
        return redirect()->back()->with('message', 'Events Deleted Successfully.');


    }

    
    public function otherSetting()
    {
      $min_distance = Settings::where('slug', 'min_distance')->first();
      $max_delivery_distance = Settings::where('slug', 'max_delivery_distance')->first();
      $delivery_charge_below = Settings::where('slug', 'delivery_charge_below')->first();
      $delivery_charge_between = Settings::where('slug', 'delivery_charge_between')->first();
      $delivery_charge_above = Settings::where('slug', 'delivery_charge_above')->first();
      $accepted_delivery_time = Settings::where('slug', 'accepted_delivery_time')->first();
      $android = Settings::where('slug', 'android')->first();
      $ios =  Settings::where('slug', 'ios')->first();
      $referal_reg = Settings::where('slug', 'referral_earning_when_registration')->first();
      $referal_purchase = Settings::where('slug', 'referral_earning_when_purchase')->first();
      $tab = '';
      
      return view('admin::otherSetting',compact('min_distance','max_delivery_distance','delivery_charge_below','delivery_charge_between','delivery_charge_above','accepted_delivery_time','android','ios','referal_reg','referal_purchase','tab'));  

    }

    public function storeSettings(Request $request)
    {
        $request->validate([
            'min_distance' => 'required|integer',
            'max_delivery_distance' => 'required|integer',
            'delivery_charge' => 'nullable|numeric',
            // 'accepted_delivery_time' => 'required',
            'tab' => 'required'
        ]);
        Settings::where('slug','min_distance')->update(['value' => $request->min_distance]);
        Settings::where('slug','max_delivery_distance')->update(['value' => $request->max_delivery_distance]);
        Settings::where('slug','delivery_charge_below')->update(['price' => $request->delivery_charge_below]);
        Settings::where('slug','delivery_charge_between')->update(['price' => $request->delivery_charge_between]);
        Settings::where('slug','delivery_charge_above')->update(['price' => $request->delivery_charge_above]);
        // Settings::where('slug','accepted_delivery_time')->update(['value' => $request->accepted_delivery_time]);
       
        return redirect()->back()->with('message', 'Settings added Successfully.')->withInput();
    }

    public function settings()
    {
      $settings=Settings::all();

      return view('admin::settings',compact('settings'));  

    }
    public function appversion(Request $request)
    {
        $request->validate([
            'android' => 'required|numeric',
            'androidmin' => 'required|numeric',
            'ios' => 'required|numeric',
            'iosmin' => 'required|numeric',
            'tab' => 'required'
        ]);

        $android=Settings::where('slug','android')->first();
        
        $androidversion=$request->android;
        $android->value=$androidversion;
        $android->min_value=$request->androidmin;
        $android->save();
        


        $ios=Settings::where('slug','ios')->first();

        $iosversion=$request->ios;
        $ios->value=$iosversion;
        $ios->min_value=$request->iosmin;
        $ios->save();
         return redirect()->back()->with('message', 'Version added Successfully.')->withInput();


    }

    public function referalStore(Request $request)
    {
        $request->validate([
            'ref_reg' => 'required|integer',
            'ref_purchase' => 'required|integer',
            'tab' => 'required'
        ]);

        Settings::where('slug','referral_earning_when_registration')->update(['value' => $request->ref_reg]);
        Settings::where('slug','referral_earning_when_purchase')->update(['value' => $request->ref_purchase]);

        return redirect()->back()->with('message', 'Referal points added Successfully.')->withInput();
    }
   

    public function categoryExport()
    {

        
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );


       // $categories = BusinessCategory::all();

        return Excel::download(new CategoryExport(), 'category_list_'.Carbon::today()->toDateString().'.xlsx');

    }


    public function enquiry()
    {
        if(Auth::guard('admin')->check())
        {
            return view('admin::Enquiry.enquiry');
        }
    }

    public function enquiryList(Request $request)
    {
        if(Auth::guard('admin')->check())
        {
                $search   = $request->search['value'];
                $sort     = $request->order;
                $column   = $sort[0]['column'];
                $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
                
                $enquiry = Enquiry::with(['shop','user','category','subCategory'])->orderBy('id', 'desc');

                if ($search != '') 
                {
                    $enquiry->where('mobile', 'LIKE', '%'.$search.'%')->orWhere(function($query) use($search){
                    $query->orWhereHas('shop', function($q) use($search){
                        $q->where('name', 'LIKE', '%'.$search.'%');
                    });
                    $query->orWhereHas('user', function($q) use($search){
                        $q->where('name', 'LIKE', '%'.$search.'%');
                    });
                });
                }

                $total = $enquiry->count();
                
                $result['data'] = $enquiry->take($request->length)->skip($request->start)->get();
                $result['recordsTotal'] = $total;
                $result['recordsFiltered'] =  $total;

                echo json_encode($result);
        }
    }

    public function searchShopCode(Request $request)
    {
       
    	$shops = [];

        if($request->has('q')){
            $search = $request->q;
            $shops =BusinessShop::select("id", "name")
            		->where('name', 'LIKE', "%$search%")
            		->get();
        }else{
            $shops = BusinessShop::select("id", "name")->where(['is_active' => 1])->orderBy('name','asc')->limit(3000)->get();
        }
        return response()->json($shops);
        
    }

    public function sentEnquiryPush(Request $request)
    {
        if($request->ajax()){
            if($request->enquiry_id != '')
            {

                $enquiry = Enquiry::find($request->enquiry_id);
                $device_types=UserDevice::where('user_id',$enquiry->user_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
                
                $shop = BusinessShop::where('id',$request->shop_id)->first();
                if($shop)
                {
                    $message = array(
                        'type' => 'product_enquiry',
                        'title' => 'Product Enquiry',
                        'shop_name' => $shop->name ?? '',
                        'shop_id' => $shop->id ?? '',
                        'message' => $request->message
                    );
                }

                if (!empty($device_types))
                    SiteHelper::sendAndroidPush($device_types, $message);

                // $iosdevice=UserDevice::where('user_id',$enquiry->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                // if (!empty($iosdevice)) 
                // SiteHelper::sendIosPush($iosdevice, $message);
                // Log::debug('shop '.$shop);

                if($shop) {
                    $notification_category = Notificationcategory::where(['slug' => 'seller_enquiry_notification'])->first();

                    if($notification_category) {
                        Notification::create([
                            'notification_id' => $notification_category->id,
                            'from_id' => 1,
                            'to_id' => $shop->seller_id,
                            'enquiry_id' => $request->enquiry_id
                        ]);
                    }

                    $device_types=UserDevice::where('user_id',$shop->seller_id)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

                    $message = array(
                        'type' => 'product_enquiry',
                        'title' => 'Enquiry Notification',
                        'shop_name' => $shop->name ?? '',
                        'product_name' => $enquiry->product_name,
                        'product_detail' => $enquiry->product_detail,
                        'shop_id' => $shop->id,
                        'message' => 'An Enquiry has been received for the product '.$enquiry->product_name
                    );

                    if (!empty($device_types))
                        SiteHelper::sendAndroidPush($device_types, $message);
                }

                Enquiry::whereId($request->enquiry_id)->update([

                    'shop_id' => $request->shop_id,
                    'status'  => 1
                ]);

                EnquiryResult::updateOrCreate([
                    'enquiry_id'   => $enquiry->id,
                ],[
                    'message'   => $request->message
                ]);

                return ['success' => true, 'message' => 'Push Notification Sent!!'];
            }
        }

        

    }

    
    public function getEnquiryPush(Request $request,$id)
    {

        if($request->ajax()){

            $enquiry = Enquiry::where('id',$id)->with(['enquiryResult','shop'])->first();

            if($enquiry)
            {
                return response()->json($enquiry);
            }else{
                return ['success' => false, 'message' => 'not found!!'];
            }
           
        }
    }


    public function buyAnything()
    {

        if(Auth::guard('admin')->check())
        {
            return view('admin::BuyAnything.buy');
        }
    }


    public function buyAnythingLists(Request $request)
    {

        if(Auth::guard('admin')->check())
        {
                $search   = $request->search['value'];
                $sort     = $request->order;
                $column   = $sort[0]['column'];
                $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
                
                $buy_anything = BuyAnything::with('orderStatus')->orderBy('id', 'desc');

                if ($search != '') 
                {
                    $buy_anything->where('name', 'LIKE', '%'.$search.'%')->orWhere('mobile', 'LIKE', '%'.$search.'%');
                
                }

                $total = $buy_anything->count();
                
                $result['data'] = $buy_anything->take($request->length)->skip($request->start)->get();
                $result['recordsTotal'] = $total;
                $result['recordsFiltered'] =  $total;

                echo json_encode($result);
        }
    }


    public function buyAssign($id)
    {


        $buy_anything = BuyAnything::findOrFail($id);

        $address = BuyAddress::where('id', $buy_anything->deliver_addressid)->first();

        if(!$address)
            $address = UserAddress::where('id', $buy_anything->deliver_addressid)->first();
        $status = Status::whereIn('slug', ['pending', 'order_confirmed', 'item_not_available', 'delivered'])->get();

        $drivers = DriverUser::where(['is_busy' => 0 ,'is_active'=>1])->get();

        return view('admin::BuyAnything.buy_assign',compact('buy_anything','address','status','drivers'));
    }

    public function buychangeOrderStatus(Request $request)
    {
      
      
            $status_val = $request->status;
            $order_id = $request->order_id;
            $driver_id = $request->driver;

            $status = Status::where('slug',$status_val)->first();

        

            $order = BuyAnything::find($order_id);

            if($order)
            {

                $order_buy = OrderBuyAnything::where('order_id',$order->id)->first();

                $order->order_status = $status->id;
                $order->driver_id = $driver_id;
                $order->status = 1;
                $order->save(); 

                $order_buy = OrderBuyAnything::where(['order_id' => $order->id])->first();
               
                if($order_buy) {

                    $order_buy->update([
                        'driver_id' => $driver_id,
                        'status_id' => $status->id,
                        'assign_date' => $request->assign_date
                    ]);
                } else {
                    OrderBuyAnything::create([
                        'order_id' => $order->id,
                        'driver_id' => $driver_id,
                        'status_id' => $status->id,
                        'assign_date' => $request->assign_date
                    ]);
                }
                

                return redirect()->back()->with(['message'=>'Status changed successfully.']);
            }

        
    }


    public function deliverAnything()
    {

        if(Auth::guard('admin')->check())
        {
            return view('admin::DeliverAnything.deliver_anything');
        }
    }

    public function deliverAnythingLists(Request $request)
    {

        if(Auth::guard('admin')->check())
        {
                $search   = $request->search['value'];
                $sort     = $request->order;
                $column   = $sort[0]['column'];
                $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
                
                $deliver_anything = DeliverAnything::with('orderStatus')->orderBy('id', 'desc');

                if ($search != '') 
                {
                    $deliver_anything->where('name', 'LIKE', '%'.$search.'%')->orWhere('mobile', 'LIKE', '%'.$search.'%');
                
                }

                $total = $deliver_anything->count();
                
                $result['data'] = $deliver_anything->take($request->length)->skip($request->start)->get();
                $result['recordsTotal'] = $total;
                $result['recordsFiltered'] =  $total;

                echo json_encode($result);
        }
    }

    public function deliverAssign($id)
    {


        $deliver_anything = DeliverAnything::findOrFail($id);

        $del_address = DeliverAddress::where('id',$deliver_anything->deliver_addressid)->first();

        $pic_address = DeliverAddress::where('id',$deliver_anything->pickup_addressid)->first();

        if(!$del_address)
            $del_address = UserAddress::where('id', $deliver_anything->deliver_addressid)->first();

        if(!$pic_address)
            $pic_address = UserAddress::where('id', $deliver_anything->pickup_addressid)->first();

        $status = Status::whereIn('slug', ['pending', 'order_confirmed', 'item_not_available', 'delivered'])->get();

        $drivers = DriverUser::where(['is_busy' => 0 ,'is_active'=>1])->get();

        return view('admin::DeliverAnything.deliver_assign',compact('deliver_anything','del_address','status','drivers','pic_address'));
    }


    public function delchangeOrderStatus(Request $request)
    {
      
      
            $status_val = $request->status;
            $order_id = $request->order_id;
            $driver_id = $request->driver;

            $status = Status::where('slug',$status_val)->first();

        

            $order = DeliverAnything::find($order_id);

            if($order)
            {

                $order_buy = OrderDeliverAnything::where('order_id',$order->id)->first();

                $order->order_status = $status->id;
                $order->driver_id = $driver_id;
                $order->status = 1;
                $order->save(); 
                // dd($request->assign_date);

                $order_delivery = OrderDeliverAnything::where(['order_id' => $order->id])->first();
               
                if($order_delivery) {

                    $order_delivery->update([
                        'driver_id' => $driver_id,
                        'status_id' => $status->id,
                        'assign_date' => $request->assign_date
                    ]);
                } else {
                    OrderDeliverAnything::create([
                        'order_id' => $order->id,
                        'driver_id' => $driver_id,
                        'status_id' => $status->id,
                        'assign_date' => $request->assign_date
                    ]);
                }

                

                return redirect()->back()->with(['message'=>'Status changed successfully.']);
            }

        
    }


    public function vendorPaymentReport()
    {
        if(Auth::guard('admin')->check())
        {
            $shops = BusinessShop::whereIsActive(1)->get();
            
        }else{
            $shop_id = Auth::guard('seller')->id();
            $shops = BusinessShop::whereId($shop_id)->first();
        }
        return view('admin::Report.SaleReportVendor',compact('shops'));
    }

    public function vendorPaymentReportList(Request $request)
    {
        if(Auth::guard('admin')->check())
        {
          
                    $search   = $request->search['value'];
                    $sort     = $request->order;
                    $column   = $sort[0]['column'];
                    $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
                    
                    $order = Order::orderBy('id', 'desc');
    
                  
                        $order->where('id', 'LIKE', '%xx%');
                    
                    
    
                    $total = $order->count();
                    
                    $result['data'] = $order->take($request->length)->skip($request->start)->get();
                    $result['recordsTotal'] = $total;
                    $result['recordsFiltered'] =  $total;
    
                    echo json_encode($result);
            
         
          

        }else{
            $search   = $request->search['value'];
            $sort     = $request->order;
            $column   = $sort[0]['column'];
            $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
            
            $shop_id = Auth::guard('seller')->id();

            $status = Status::where(['slug' => 'delivered','is_active' =>1])->first();

            $startDate = Carbon::now(); //returns current day
            $firstDay = $startDate->firstOfMonth();  
            $yesterday = Carbon::yesterday();

   
            $order = Order::with(['shop','user','orderStatus','orderProducts.product'])->where(['shop_id' => $shop_id ,'is_active' =>1])->where('status_id',$status->id)->whereBetween('delivery_date',[$firstDay,$yesterday])
                    ->whereHas('orderProducts.product', function($query){
                        $query->orWhere('commission','<>',NULL);
                    })->orderBy('created_at', 'desc');
          

            
            $total = $order->count();
            
            $result['data'] = $order->take($request->length)->skip($request->start)->get();
            $result['recordsTotal'] = $total;
            $result['recordsFiltered'] =  $total;

            echo json_encode($result);
    
 
        }
    }


    public function searchShopReport(Request $request)
    {

  
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        $date=date('Y-m-d');
        $shop_id=$request->shop;

        $status = Status::where(['slug' => 'delivered','is_active' =>1])->first();


        if($request->fromdate != null  && $request->todate != null)
        {   
            $fromdate=$request->fromdate.' 00:00:00';
            $todate=$request->todate.' 23:59:59';
        }else{
            $startDate = Carbon::now(); //returns current day
            $fromdate = $startDate->firstOfMonth();  
            $todate = Carbon::yesterday();
        }

   
        $order = Order::with(['shop','user','orderStatus','orderProducts.product'])->where(['shop_id' => $shop_id ,'is_active' =>1])->whereBetween('created_at',[$fromdate,$todate])->where('status_id',$status->id)
                ->whereHas('orderProducts.product', function($query){
                    $query->orWhere('commission','<>',NULL);
                })->orderBy('created_at', 'desc');
       
       
        $total  =  $order->count();
        if($request->length==null)
        {
          $request->length=1;
        }

        $result['data'] = $order->take($request->length)->skip($request->start)->get();

        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result); 


    }

    public function viewReport($id)
    { 
        $order = Order::where('id',$id)->with(['shop','user','orderStatus','orderAddresses','orderProducts'])->first();
        
        $order_status =  OrderStatus::where('order_id',$id)->orderby('id')->get();

        $status_val = Status::where('is_active',1)->orderby('postion')->get();
       
        $view=\View::make('admin::Report.SaleOrder',compact('order','order_status','status_val'));
        return ['html'=>$view->render()];
    }

    public function viewExport(Request $request)
    { 
     
        $shop_id = $request->shop_id;
        $from_id = $request->from_date;
        $to_id = $request->to_date;
        
        $shop = BusinessShop::find($shop_id);
        if($shop)
        { 
              $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
              );
    
           
              return Excel::download(new VendorSaleExport($shop_id,$from_id,$to_id), $shop->name.'_sale_list_'.Carbon::today()->toDateString().'.xlsx');
           

        }else{
            return redirect()->back()->with(['message'=>'Please add shop and dates']);
        }

    }

    //vendor report
    public function vendorCommissionReport()
    {
        $shops = BusinessShop::with('sellerReport')->whereHas('sellerReport', function($q) {
            $q->where(['type' => 0, 'status' => 1]);
        })->get();
        
        return view('admin::Report.CommissionReportAdmin', compact('shops'));
    }

    public function CommissionReportList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC";

        // $status = Status::where(['slug' => 'delivered','is_active' =>1])->first();

        $startDate = Carbon::now(); //returns current day
        $firstDay = $startDate->firstOfMonth();  
        $yesterday = Carbon::now();


        $order = SellerOrderData::with('orderData', 'sellerData')->where(['type' => 0, 'status' => 1])->whereHas('orderData', function($q) use ($firstDay, $yesterday) {
            $q->whereBetween('delivery_date',[$firstDay,$yesterday]);
        })->orderBy('created_at', 'desc');
        
        // dd($order->get());
        $total = $order->count();
        
        $result['data'] = $order->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    //change report status
    public function changeAdminCommissionStatus(Request $request)
    {
        // dd($request->all());
        if($request->ajax()) {
            
            if($request->data_id != '' || !is_null($request->data_id))
            {

                $data = SellerOrderData::where('id', $request->data_id)->first();
                // dd($data);
                if($data->is_paid == 0) {
                    $data->update(['is_paid' => 1]);
                } else {
                    $data->update(['is_paid' => 0]);
                }

                return response()->json(['success'=>'status changed succesfully.']);
                

            } else{
                    return response()->json(['error'=>'Status cannot be changed']);
            }
        }
    }

    public function searchCommissionShopReport(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        $date=date('Y-m-d');

        // $fromdate=$request->fromdate;
        // $todate=$request->todate;
        $shop_id=$request->shop;

        // $status = Status::where(['slug' => 'delivered','is_active' =>1])->first();

        

        if($request->fromdate != '' && $request->todate != '') {
            $fromdate = $request->fromdate.' 00:00:00';
            $todate = $request->todate.' 23:59:59';
        }else{
            $startDate = Carbon::now(); //returns current day
            $fromdate = $startDate->firstOfMonth();  
            $todate = Carbon::now();
        }

        $order = SellerOrderData::with('orderData', 'sellerData')->where(['seller_id' => $shop_id, 'type' => 0, 'status' => 1])->whereHas('orderData', function($q) use ($fromdate, $todate) {
            $q->whereBetween('delivery_date',[$fromdate,$todate]);
        })->orderBy('created_at', 'desc');
        
        $total = $order->count();
        
        $result['data'] = $order->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);

    }

    public function viewAdminCommissionExport(Request $request)
    {   
        $shop_id = $request->shop_id;

        /*if($request->from_date != null || $request->from_date != '') {
            $dateFrom = new DateTime($request->from_date);
            $dateFrom_date = $dateFrom->format('Y-m-d');
        } else{
            $dateFrom_date = $startDate->firstOfMonth(); 
        }

        if($request->to_date != null || $request->to_date != '') {
            $dateTo = new DateTime($request->to_date);
            $dateTo_date = $dateTo->format('Y-m-d');
        } else {
            $dateTo_date = Carbon::now();
        }

        $datas = SellerOrderData::with('orderData')->where(['seller_id' => $shop_id, 'type' => 0])->whereBetween('created_at',[$dateFrom_date, $dateTo_date])->orderBy('created_at', 'desc')->get();*/
        ///
        if($request->from_date != '' && $request->to_date != '') {
            $fromdate = $request->from_date.' 00:00:00';
            $todate = $request->to_date.' 23:59:59';
        }else{
            $startDate = Carbon::now(); //returns current day
            $fromdate = $startDate->firstOfMonth();  
            $todate = Carbon::now();
        }

        $datas = SellerOrderData::with('orderData', 'sellerData')->where(['seller_id' => $shop_id, 'type' => 0, 'status' => 1])->whereHas('orderData', function($q) use ($fromdate, $todate) {
            $q->whereBetween('delivery_date',[$fromdate,$todate]);
        })->orderBy('created_at', 'desc')->get();

        
        if(count($datas) > 0) {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $fields = array('Order Date', 'Total', 'Commission', 'Amount', 'Status');
            $commisiion_list = array();

            foreach ($datas as $key => $data) {
                $order_date = new DateTime($data->created_at);
                $date = $order_date->format('m/d/Y');

                $commisiion_list[] = array(
                        'Order Date' => $date,
                        'Total' => $data->total,
                        'Commission' => $data->commission,
                        'Amount' => $data->amount,
                        'Status' => $data->is_paid == 1 ? 'Paid' : 'Unpaid',
                    );
            }
            // dd($admin_list);
            $file_name = 'commisiion_list.csv';
            
            header('Content-Type: text/csv; charset=utf-8');
            Header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename = '.$file_name.'');
            
            $output = fopen('php://output', 'w');
            // fputcsv($output, $fields);
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, $fields);
            // dd('ss');
            foreach ($commisiion_list as $list) {
                fputcsv($output, $list);
            }
            
            fclose($output);
        } else {
            return redirect()->back();
        }
    }


    //vendor report
    public function vendorReport()
    {
        $shop_id = Auth::guard('seller')->id();
        $datas = SellerOrderData::where(['seller_id' => $shop_id, 'type' => 0, 'status' => 1])->get();
        
        return view('admin::Report.CommissionReportVendor', compact('datas', 'shop_id'));
    }

    public function vendorCommissionReportList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $shop_id = Auth::guard('seller')->id();

        // $status = Status::where(['slug' => 'delivered','is_active' =>1])->first();

        $startDate = Carbon::now(); //returns current day
        $firstDay = $startDate->firstOfMonth();  
        $yesterday = Carbon::yesterday();


        // $order = Order::with(['shop','user','orderStatus','orderProducts.product'])->where(['shop_id' => $shop_id ,'is_active' =>1])->where('status_id',$status->id)->whereBetween('delivery_date',[$firstDay,$yesterday])
        //         ->whereHas('orderProducts.product', function($query){
        //             $query->orWhere('commission','<>',NULL);
        //         })->orderBy('created_at', 'desc');

        $order = SellerOrderData::with('orderData')->where(['seller_id' => $shop_id, 'type' => 0, 'status' => 1])->whereHas('orderData', function($q) use ($firstDay, $yesterday) {
            $q->whereBetween('delivery_date',[$firstDay,$yesterday]);
        })->orderBy('created_at', 'desc');
      

        // dd($order->get());
        $total = $order->count();
        
        $result['data'] = $order->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    //change report status
    public function changeCommissionStatus(Request $request)
    {
        
        if($request->ajax()) {
            
            if($request->data_id != '' || !is_null($request->data_id))
            {

                $data = SellerOrderData::where('id', $request->data_id)->first();
                
                if($data->is_paid == 1) {
                    
                    $data->update(['is_paid' => 0]);
                    return response()->json(['success'=>'status changed succesfully.']);
                   // return response()->json(['error'=>'permission denied please contact admin']);

                }else{

                    $data->update(['is_paid' => 1]);
                    return response()->json(['success'=>'status changed succesfully.']);
                }

            } else{
                    return response()->json(['error'=>'Status cannot be changed']);
            }
        }
    }

    public function searchShopCommissionReport(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $date=date('Y-m-d');
        
        $shop_id=$request->shop;

        // $status = Status::where(['slug' => 'delivered','is_active' =>1])->first();

        if($request->fromdate != '' && $request->todate != '') {
            $fromdate = $request->fromdate.' 00:00:00';
            $todate = $request->todate.' 23:59:59';
        }else{
            $startDate = Carbon::now(); //returns current day
            $fromdate = $startDate->firstOfMonth();  
            $todate = Carbon::yesterday();
        }

        $order = SellerOrderData::with('orderData')->where(['seller_id' => $shop_id, 'type' => 0, 'status' => 1])->whereHas('orderData', function($q) use ($fromdate, $todate) {
            $q->whereBetween('delivery_date',[$fromdate,$todate]);
        })->orderBy('created_at', 'desc');
        

        // dd($order->get());
        $total = $order->count();
        
        $result['data'] = $order->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);


    }

    public function vendorCommissionExport(Request $request)
    { 
        $shop_id = $request->shop_id;
        $startDate = Carbon::now(); //returns current day
        // dd($request->all());
        // if($request->from_date != '' && $request->to_date != '') {
        //     $fromdate = $request->from_date.' 00:00:00';
        //     $todate = $request->to_date.' 23:59:59';
        // } else {
        //     $fromdate = $startDate->firstOfMonth(); 
        //     $todate = Carbon::yesterday();
        // }

        if($request->from_date != null || $request->from_date != '') {
            $dateFrom = new DateTime($request->from_date);
            $dateFrom_date = $dateFrom->format('Y-m-d');
        } else{
            $dateFrom_date = $startDate->firstOfMonth(); 
        }

        if($request->to_date != null || $request->to_date != '') {
            $dateTo = new DateTime($request->to_date);
            $dateTo_date = $dateTo->format('Y-m-d');
        } else {
            $dateTo_date = Carbon::yesterday();
        }

        $datas = SellerOrderData::with('orderData')->where(['seller_id' => $shop_id, 'type' => 0])->whereBetween('created_at',[$dateFrom_date, $dateTo_date])->orderBy('created_at', 'desc')->get();

        
        if(count($datas) > 0) {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $fields = array('Order Date', 'Total', 'Commission', 'Amount', 'Status');
            $commisiion_list = array();

            foreach ($datas as $key => $data) {
                $order_date = new DateTime($data->created_at);
                $date = $order_date->format('m/d/Y');

                $commisiion_list[] = array(
                        'Order Date' => $date,
                        'Total' => $data->total,
                        'Commission' => $data->commission,
                        'Amount' => $data->amount,
                        'Status' => $data->is_paid == 1 ? 'Paid' : 'Unpaid',
                    );
            }
            // dd($admin_list);
            $file_name = 'commisiion_list.csv';
            
            header('Content-Type: text/csv; charset=utf-8; encoding=UTF-8');
            Header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename = '.$file_name.'');
            
            $output = fopen('php://output', 'w');
            // fputcsv($output, $fields);
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, $fields);
            // dd('ss');
            foreach ($commisiion_list as $list) {
                fputcsv($output, $list);
            }
            
            fclose($output);
        } else {
            return redirect()->back();
        }

    }

    //Terms and conditions
    public function home_page()
    {   
        return view('home_page');
    }

    //privacy policy
    public function privacyPolicy()
    {
        return view('admin::terms');
    }

    //Terms and conditions
    public function termsConditions()
    {
        return view('admin::terms_conditions');
    }

    //Bulk notifications
    
    public function notification()
    {
        return view('admin::notification');
    }

    public function notificationlist(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
      

        $users=Notification::with('notificationCategory', 'getShop', 'getProduct')->where(['to_id' => 0, 'order_id' => null])->orderBy('id','desc');

        if ($search != '') 
        {
            
            $users->whereHas('notificationCategory', function($q) use ($search) {
                            $q->where('title', 'LIKE', '%'.$search.'%')->orWhere('description','LIKE', '%'.$search.'%');
                        });
        }
     
        $total  = $users->count();

        $result['data'] = $users->take($request->length)->skip($request->start)->get();
       
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result); 
    }
    
    public function addnotification()
    {
        $shops = BusinessShop::get();

        return view('admin::addnotification', compact('shops'));
    }

    public function storenotification(Request $request)
    {
        $notification_category = new Notificationcategory;
        $notification_category->title = $request->title;
        $notification_category->slug = /*\Str::slug($request->title, '-');*/ 'common';
        $notification_category->description = $request->description;
        if($request->file('image'))
        {
            $image = $request->file('image')->store('notification');
            $notification_category->image=$image;
        }
        $notification_category->save();

        $notification = new Notification;
        $notification->notification_id = $notification_category->id;
        $notification->from_id = 0;
        $notification->to_id = 0;
        $notification->shop_id = $request->shop_id;
        $notification->product_id = $request->product_id;
        // $notification->is_sent = 0;
        $notification->save();

        return redirect()->back()->with('message', 'Notification added successfully.');
    }

    public function sendBulkNotification()
    {
        $notifications = Notification::with('notificationCategory')->where(['to_id' => 0, 'order_id' => null, 'is_sent' => 0])->get();
        // dd($notifications);
        foreach ($notifications as $notify) 
        {
            // $notify->is_sent = 2;
            // $notify->save();

            $device_types=UserDevice::where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

            $mobdevice=UserDevice::where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();

            $image = $notify->notificationCategory->image == null ? null : asset('storage/app').'/'.$notify->notificationCategory->image;
            
            $message = array(
                'type' => 'common',
                'shop_id' => $notify->shop_id,
                'product_id' => $notify->product_id,
                'order_id' => $notify->order_id,
                'title' => $notify->notificationCategory->title,
                'message' => $notify->notificationCategory->description,
                'image' => $image
            );
            
            if (!empty($device_types)) SiteHelper::sendAndroidBulkPush($device_types, $message);

            // if (!empty($mobdevice)) SiteHelper::sendIosBulkPush($mobdevice, $message);

            $notify->is_sent = 1;
            $notify->save();    
        }
        echo("success");
    }
}
