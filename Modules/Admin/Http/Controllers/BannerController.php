<?php

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\AdminUser;
use Modules\Admin\Entities\Settings;
use Modules\Users\Entities\UserRole;
use Modules\Shop\Entities\BusinessShop;
use Modules\Category\Entities\Banner;
use Modules\Product\Entities\Product;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB, Validator, Session;
use Intervention\Image\ImageManagerStatic as Image;
use Mail;
use Auth;

// use App\Http\Requests\ShopStoreRequest;
// use App\Http\Requests\CategoryEditRequest;
// use App\Http\Requests\AttributeStoreRequest;
// use App\Http\Requests\AttributeEditRequest;


class BannerController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:banner-list', ['only' => ['banner','bannerList']]);
        $this->middleware('permission:banner-create', ['only' => ['bannerAdd','bannerStore']]);
        $this->middleware('permission:banner-edit', ['only' => ['bannerEdit','bannerUpdate']]);
        $this->middleware('permission:banner-delete', ['only' => ['deleteBanner']]);
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function banner()
    {
        return view('admin::Banner.banner');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function bannerList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        if(Auth::guard('admin')->check())
        {
            $banner = Banner::orderBy('id', 'desc');
            
            if ($search != '') 
            {
              $banner = $banner->where('title', 'LIKE', '%'.$search.'%')->orWhere('description','LIKE', '%'.$search.'%');
            }
        }
        
        if(Auth::guard('seller')->check())
        {
            $shop_id = Auth::guard('seller')->id();
            $banner = Banner::where('shop_id',$shop_id)->orderBy('id', 'desc');

            if ($search != '') 
            {
                $banner->where(function($query) use($search){
                $query->orWhere('title', 'LIKE', '%'.$search.'%')->orWhere('description','LIKE', '%'.$search.'%');
              });
              
            }
        }

        $total = $banner->count();
        
        $result['data'] = $banner->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

   /**
     * Display a listing of the resource.
     * @return Response
     */
    public function bannerAdd()
    {
        return view('admin::Banner.addBanner');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function bannerStore(Request $request)
    {

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        if($request->file('pic'))
            $image = $request->file('pic')->store('banners');
        else
            $image = null;

        Banner::create([
                // 'module_id' => 1,
                'shop_id' => $request->shops,
                'product_id' => $request->product,
                'title' => $request->title,
                'image' => $image,
                'description' => $request->description,
                'valid_from' => $request->validfrom,
                'valid_to' => $request->validto,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'Banner Added Successfully.');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function bannerEdit($id)
    {
        $banner = Banner::where(['id' => $id])->first();
        $shops = BusinessShop::where([/*'is_active' => 1,*/'id'=>$banner->shop_id])->orderBy('id','desc')->get();
        
        
        if(Auth::guard('seller')->check())
        {
            $shop_id = Auth::guard('seller')->id();
            $shops = BusinessShop::where([/*'is_active' => 1,*/'id' => $shop_id])->orderBy('id','desc')->get();
        }

        $products = Product::where('is_active' , 1)->where('seller_id',$banner->shop_id)->whereIn('type',[0,1])->orderBy('name')->groupBy('sku')->get();

        return view('admin::Banner.editBanner', compact('banner','shops','products'));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function bannerUpdate(Request $request)
    {
        $banner = Banner::where(['id' => $request->id])->first();

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        if($request->hasFile('pic')) {
            $image = $request->file('pic')->store('banners');

            $banner->update(['image' => $image]);
        }

        $banner->update([
                'shop_id' => $request->shops,
                'product_id' => $request->product,
                'title' => $request->title,
                'description' => $request->description,
                'valid_from' => $request->validfrom,
                'valid_to' => $request->validto,
                'is_active' => $is_active
            ]);
        
        return redirect()->back()->with('message', 'Banner Updated Successfully.');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteBanner($id)
    {
        $banner = Banner::where(['id' => $id])->delete();
        
        return redirect()->back()->with('message', 'Banner Deleted Successfully.');
    }

    public function searchShopCode(Request $request)
    {
       
    	$shops = [];
        if(Auth::guard('admin')->check())
        {
            if($request->has('q')){
                $search = $request->q;
                $shops =BusinessShop::select("id", "name")
                        ->where('name', 'LIKE', "%$search%")
                        ->get();
            }else{
                $shops = BusinessShop::select("id", "name")->where(['is_active' => 1])->orderBy('name','asc')->limit(3000)->get();
            }
        }
        if(Auth::guard('seller')->check())
        {
            $shop_id = Auth::guard('seller')->id();
            $shops = BusinessShop::where(['is_active' => 1,'id' => $shop_id])->get();
        }

        return response()->json($shops);
        
    }

    public function getproduct(Request $request)
    {
        if($request->ajax()){

            $ids = [];
            if($request->shop_id != '')
            {
                $shop_id = $request->shop_id;

                $products['data'] = Product::where('is_active' , 1)->where('seller_id',$shop_id)->whereIn('type',[0,1])->orderBy('name')->groupBy('sku')->get();
              
                return response()->json($products);
            }else{
                return response()->json('');
            }
        }
        
    }
}
