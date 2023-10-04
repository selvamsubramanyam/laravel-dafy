<?php

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\AdminUser;
use Modules\Admin\Entities\Settings;
use Modules\Admin\Entities\City;
use Modules\Users\Entities\UserRole;
use Modules\Shop\Entities\Category;
use Modules\Shop\Entities\ShopCategory;
use Modules\Category\Entities\BusinessCategory;
use Modules\Category\Entities\BusinessCategoryField;
use Modules\Category\Entities\Control;
use Modules\Shop\Entities\BusinessShop;
use Modules\Shop\Entities\BusinessCategoryShop;
use Modules\Shop\Entities\ShopImage;
use Modules\Shop\Entities\ShopService;
use Modules\Shop\Entities\ShopProduct;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductImage;
use Modules\Admin\Entities\Brand;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductEditRequest;
use App\Imports\ProductImport;
use App\Exports\ShopExport;
use App\Exports\ShopProductExport;
use Carbon\Carbon;
use Excel;
use DB, Validator, Session;
use Intervention\Image\ImageManagerStatic as Image;
use Mail;
use Auth;
use App\Http\Requests\ShopStoreRequest;
use App\Http\Requests\ShopUpdateRequest;
use Modules\Users\Entities\User;
use Modules\Users\Entities\BusinessSellerCategory;
use SiteHelper;

// use App\Http\Requests\CategoryEditRequest;
// use App\Http\Requests\AttributeStoreRequest;
// use App\Http\Requests\AttributeEditRequest;


class ShopController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:shop-list', ['only' => ['shop','shopList']]);
        $this->middleware('permission:product-list', ['only' => ['shopProducts','getProducts']]);
        $this->middleware('permission:shop-create', ['only' => ['shopAdd','shopStore']]);
        $this->middleware('permission:shop-edit', ['only' => ['shopEdit','shopUpdate']]);
        $this->middleware('permission:shop-delete', ['only' => ['deleteShop']]);
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function shop()
    {
      
        return view('admin::Shop.shop');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function shopList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        if(Auth::guard('admin')->check())
        {
            $shop = BusinessShop::with('sellerInfo')->orderBy('id','desc');

            if ($search != '') 
            {
                $shop->where('name', 'LIKE', '%'.$search.'%')->orWhere('code','LIKE', '%'.$search.'%')->orWhere('location','LIKE', '%'.$search.'%');
            }
        }

        if(Auth::guard('seller')->check())
        {
            $shop_id = Auth::guard('seller')->id();
            $shop_list = BusinessShop::find($shop_id);
            $shop = BusinessShop::where('id',$shop_id)->with('sellerInfo')->orderBy('id','desc');

            if ($search != '') 
            {
                
                $shop = $shop->where('id',$shop_id)->where(function($query) use($search){
                $query->orWhere('name', 'LIKE', '%'.$search.'%')->orWhere('code','LIKE', '%'.$search.'%')->orWhere('location','LIKE', '%'.$search.'%');
            });
            }
        }
        

     

        $total = $shop->count();
        
        $result['data'] = $shop->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function shopAdd()
    {
        $categories = BusinessCategory::where(['is_last_child' => 1])->orderBy('name')->get();

        $shops = BusinessShop::where(['parent_id' => 0, 'is_active' => 1])->get();

        $cities = City::where(['is_active' => 1])->get();
       
        if(Auth::guard('admin')->check())
        {
        
         
        $sellers = User::where('business_name', '!=', null)->with('roles')->whereDoesntHave('shop')->where('is_active',1)->whereHas('roles', function ($query){
                        $query->where("user_role.role_id", "=", 2); //seller
                    })->orderBy('business_name','desc')->get();
        }

        if(Auth::guard('seller')->check())
        {
            $shop_id = Auth::guard('seller')->id();
            $shop_list = BusinessShop::find($shop_id);

            $sellers = User::where('id',$shop_list->seller_id)->get();

        }

        $category_shops = Category::get();

        return view('admin::Shop.addShop', compact('categories', 'shops', 'cities' ,'sellers', 'category_shops'));
    }

    // //To get city list
    // public function getCityList(Request $request)
    // {
    //     if($request->pro){
    //         $cities = City::select(['id', 'name'])->where([['name', 'like', '%'.$request->pro.'%'], 'is_active' => 1])->get();
    //     } else {
    //         $cities = '';
    //     }
    //     return response()->json(['results' => $cities], 200);
    // }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function shopStore(ShopStoreRequest $request)
    {
        $categories = array();
        $pics = array();
        $latitude = null;
        $longitude = null;

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        // if($request->file('pic'))
        //     $image = $request->file('pic')->store('categories');
        // else
        //     $image = null;

        if($request->main == 0)
            $parent = $request->main_shop;
        else
            $parent = 0;

        if($request->type == 0)
            $type = 'Gen';
        else
            $type = 'Pre';

            if($request->hasfile('thump_image'))
            {
                $image = $request->thump_image;
                $shopimage = str_replace(' ','', time()).trim($image->getClientOriginalName());
                $shopimage = str_replace(' ', '_',trim($shopimage));
                $image->move(storage_path('app/shops'), $shopimage);  
                $imgurl = "shops/".$shopimage; 
            }else{
                $imgurl = NULL;
            }

        $seller = User::where('id', $request->seller_id)->first();

        if($seller) {
            $latitude = $seller->latitude;
            $longitude = $seller->longitude;
        }

        $shop = BusinessShop::create([
                // 'code' => $request->code,
                'seller_id' => $request->seller_id,
                'name' => $request->name,
                'type' => $type,
                'instore' => $request->instore,
                'instore_description' => $request->instore_description,
                'phone_no' => $request->contact,
                'image' => $imgurl,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'website' => $request->website,
                'email' => $request->email,
                'location' => $request->location,
                'address' => $request->address,
                'pincode' => $request->pincode,
                'city_id' => $request->city,
                'services' => $request->services,
                'description' => $request->description,
                'parent_id' => $parent,
                'is_active' => $is_active,
                'password'  => Hash::make($request->password)
            ]);

        $pics = $request->pic;

        if($request->hasfile('pic'))
         {
            foreach($pics as $image)
            {
                $imageName = str_replace(' ','', time()).trim($image->getClientOriginalName());
                $imageName = str_replace(' ', '_',trim($imageName));
                $image->move(storage_path('app/shops'), $imageName);  
                $url = "shops/".$imageName; 

                $img = $request->file('pic');

                /////////Image resize
                // $filename    = trim($image->getClientOriginalName());
                // $image_resize = Image::make($image->getRealPath());              
            
                // $image_resize->resize(300, null, function ($constraint) {
                //     $constraint->aspectRatio();
                // });

                // $image_resize->save('storage/app/shops/'.$filename);
                
                // $url = 'shops/'.$filename;

                ShopImage::create([
                    'shop_id' => $shop->id,
                    'image' => $url
                ]);
            }
         }

        
        $categories = $request->shop_category;

        foreach ($categories as $key => $value) {
            
            ShopCategory::create([
                    'shop_category_id' => $value,
                    'shop_id' => $shop->id
                ]);
        }


        if(count($request->category) > 0) {
            // $categories = $request->category;
            
            foreach ($request->category as $key => $cat) {
                $view_type =$request->$cat;

                if($view_type == null) 
                    $view_type = 0;
                
                $child_category = BusinessCategory::find($cat);
                BusinessCategoryShop::create([
                        'main_category_id' => $child_category->parent_id ?? null,
                        'category_id' => $cat,
                        'shop_id' => $shop->id,
                        'view_type' => $view_type
                    ]);
            }
        }

      //  $shareUrl = "https://pipli.in/shop?shop_id=".$shop->id;

    //    $shop->update(['share_url' => $shareUrl]);

        return redirect()->back()->with('message', 'Shop Added Successfully.');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function shopEdit($id,$seller_id=null)
    {
        $shops = BusinessShop::where(['parent_id' => 0, 'is_active' => 1])->get();
        $shop = BusinessShop::where(['id' => $id])->first();
        $shopCategories = BusinessCategoryShop::where(['shop_id' => $id])->with('categoryData')->get();
        if(!is_null($seller_id)){
            
            $ids = [];
            $bus_cats = BusinessSellerCategory::where('user_id',$seller_id)->with('getCategory')->get();

            if($bus_cats->count()>0)
            {
                foreach($bus_cats as $bus_cat)
                {
                    array_push($ids,$bus_cat->getCategory->id);
                }
            }
            
            $categories = BusinessCategory::whereIn('parent_id',$ids)->where('is_last_child',1)->get();
            
        }else{
            $categories = BusinessCategory::where(['is_last_child' => 1])->orderBy('name')->get();
        }
      
        $cities = City::where(['is_active' => 1])->get();
        $images = ShopImage::where('shop_id', $id)->get();

 
        $sellers = User::with('roles')->whereDoesntHave('shop', function($query) use($shop){
            $query->where('seller_id','!=', $shop->seller_id);
            })->where('is_active',1)->whereHas('roles', function ($query){
            $query->where("user_role.role_id", "=", 2); //seller
        })->orderBy('business_name','desc')->get();

        $category_shops = Category::get();
        $category_shops_selected = ShopCategory::where(['shop_id' => $id])->get()->pluck('shop_category_id')->toArray();
        // dd($category_shops_selected);
        return view('admin::Shop.editShop', compact('shops', 'categories', 'shop', 'shopCategories', 'cities', 'images','sellers', 'category_shops', 'category_shops_selected'));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function shopUpdate(ShopUpdateRequest $request)
    {   
        // dd($request->shop_category);
        $shop = BusinessShop::where(['id' => $request->id])->first();
        $categories = array();
        $pics = array();

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        // if($request->hasFile('pic')) {
        //     $image = $request->file('pic')->store('categories');

        //     $shop->update(['image' => $image]);
        // }

        if($request->main == 0)
            $parent = $request->main_shop;
        else
            $parent = 0;

        if($request->type == 0)
            $type = 'Gen';
        else
            $type = 'Pre';

        if($request->location != null) {
            $shop->update([
                    'area_id' => null
                ]);
        }

        if($request->password != null || $request->password != '')
        {
            $password = Hash::make($request->password);
        }else{
            $password = $shop->password;
        }

        if($request->hasfile('thump_image'))
            {
                $image = $request->thump_image;
                $shopimage = str_replace(' ','', time()).trim($image->getClientOriginalName());
                $shopimage = str_replace(' ', '_',trim($shopimage));
                $image->move(storage_path('app/shops'), $shopimage);  
                $imgurl = "shops/".$shopimage; 
            }else{
                $imgurl = $shop->image;
            }

        $shop = $shop->update([
                // 'code' => $request->code,
               
                'name' => $request->name,
                'type' => $type,
                'instore' => $request->instore,
                'instore_description' => $request->instore_description,
                'phone_no' => $request->contact,
                'image' => $imgurl,
                // 'latitude' => $request->latitude,
                // 'longitude' => $request->longitude,
                'website' => $request->website,
                'email' => $request->email,
                'services' => $request->services,
                'location' => $request->location,
                'address' => $request->address,
                'pincode' => $request->pincode,
                'city_id' => $request->city,
                'description' => $request->description,
                'parent_id' => $parent,
                'is_active' => $is_active,
                'password' => $password
            ]);

        $pics = $request->pic;

        if($request->hasfile('pic'))
         {

            // ShopImage::where('shop_id', $request->id)->delete();
            
            foreach($pics as $image)
            {   
                $imageName = str_replace(' ','', time()).trim($image->getClientOriginalName());
                $imageName = str_replace(' ', '_',trim($imageName));
                $image->move(storage_path('app/shops'), $imageName);  
                $url = "shops/".$imageName; 

                ////Image resize
                // $filename    = trim($image->getClientOriginalName());
                // $image_resize = Image::make($image->getRealPath());              
            
                // $image_resize->resize(300, null, function ($constraint) {
                //     $constraint->aspectRatio();
                // });

                // $image_resize->save('storage/app/shops/'.$filename);
            
                // $url = 'shops/'.$filename;

                ShopImage::create([
                    'shop_id' => $request->id,
                    'image' => $url
                ]);
            }
         }

         if($request->shop_category == null) {

            ShopCategory::where(['shop_id' => $request->id])->delete();

        }elseif(count($request->shop_category) > 0) {
        
            $shop_categories = $request->shop_category;

            ShopCategory::where(['shop_id' => $request->id])->delete();

            foreach ($shop_categories as $key => $value) {
                
                ShopCategory::create([
                        'shop_category_id' => $value,
                        'shop_id' => $request->id
                    ]);
            }
        }

        if($request->category == null) {

            BusinessCategoryShop::where(['shop_id' => $request->id])->delete();

        }elseif(count($request->category) > 0) {
        
            $categories = $request->category;

            BusinessCategoryShop::where(['shop_id' => $request->id])->delete();

            foreach ($categories as $key => $value) {
                $view_type = $request->$value;

                if($view_type == null) 
                    $view_type = 0;

                $child_category = BusinessCategory::find($value);
                BusinessCategoryShop::create([
                        'main_category_id' => $child_category->parent_id ?? null,
                        'category_id' => $value,
                        'shop_id' => $request->id,
                        'view_type' => $view_type
                    ]);
            }
        }
        
        return redirect()->back()->with('message', 'Shop Updated Successfully.');
    }

    public function deleteImage(Request $request)
    {
        ShopImage::where('id', $request->img_id)->delete();
        // print_r($request->img_id);exit();
        return response()->json('Delete successfuly');
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function shopServices(Request $request, $id)
    {
        $services = ShopService::where('shop_id', $id)->first();
        return view('admin::Shop.addServices', compact('id', 'services'));
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function storeServices(Request $request)
    {
        
        ShopService::create([
                'shop_id' => $request->id,
                'service_names' => $request->values
            ]);

        return redirect()->back()->with('message', 'Services Added Successfully.');
    }

    public function shopProducts($id)
    {
        $shop = BusinessShop::where('id', $id)->first();
       
        return view('admin::Shop.shopProducts', compact('id', 'shop'));
    }

    public function getProducts(Request $request,$id)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $products = Product::with(['categories','brand','shop','parentProduct','attributes','unit'])->where('seller_id',$id)->orderBy('id','desc');

        if ($search != '') 
        {
            $products->where('name', 'LIKE', '%'.$search.'%')->orWhere('sku','LIKE', '%'.$search.'%')->where('seller_id',$id);
            $products->orWhereHas('brand', function($q) use($search,$id){
                $q->where('name', 'LIKE', '%'.$search.'%')->where('products.seller_id',$id);
            })->orWhereHas('categories', function($query) use ($search,$id){//interested in
                $query->where('name', 'LIKE', '%'.$search.'%')->where('products.seller_id',$id);
            })->orWhereHas('attributes', function($que) use ($search,$id){//interested in
                    $que->where('attr_value', 'LIKE', '%'.$search.'%')->where('products.seller_id',$id);
            });
        }
        // $query = $products;
        // $query_result = SiteHelper::getEloquentSqlWithBindings($query);
        // dd($query_result);
        $total = $products->count();

        $result['data'] = $products->take($request->length)->skip($request->start)->get();
    
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function getSellerCat(Request $request)
    {
        if($request->ajax()){

            $ids = [];
            if($request->seller_id != '')
            {
                $seller_id = $request->seller_id;

                $bus_cats = BusinessSellerCategory::where('user_id',$seller_id)->with('getCategory')->get();

                if($bus_cats->count()>0)
                {
                    foreach($bus_cats as $bus_cat)
                    {
                        array_push($ids,$bus_cat->getCategory->id);
                    }
                }
                
                $categories['data'] = BusinessCategory::whereIn('parent_id',$ids)->where('is_last_child',1)->where('is_active',1)->get();
                
                return response()->json($categories);
            }else{
                return response()->json('');
            }
        }
    }


    public function shopExport()
    {

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        return Excel::download(new ShopExport(), 'shop_list_'.Carbon::today()->toDateString().'.xlsx');
        
    }

    public function shopProductsExport($shop_id)
    {

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        
        $shop = BusinessShop::find($shop_id);
        
        if($shop)
        {
            return Excel::download(new ShopProductExport($shop->id), $shop->name.'_product_list_'.Carbon::today()->toDateString().'.xlsx');
        }else{
            return redirect()->back();
        }
        
    }
   
}
