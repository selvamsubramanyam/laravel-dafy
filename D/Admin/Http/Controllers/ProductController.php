<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductUntracked;
use Modules\Product\Entities\ProductUntrackedImages;
use Modules\Product\Entities\ProductImage;
use Modules\Category\Entities\BusinessCategory;
use Modules\Shop\Entities\BusinessCategoryShop;
use Modules\Category\Entities\BusinessCategoryField;
use Modules\Admin\Entities\Brand;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductEditRequest;
use App\Imports\ProductImport;
use Modules\Shop\Entities\BusinessShop;
use Modules\Admin\Entities\Unit;
use Modules\Users\Entities\TrendKeyword;
use App\Exports\UntrackedProductExport;
use App\Exports\ProductStatusExport;
use Excel;
use Carbon\Carbon;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;


class ProductController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:product-list', ['only' => ['product','productList']]);
        $this->middleware('permission:product-create', ['only' => ['productAdd','productStore']]);
        $this->middleware('permission:product-edit', ['only' => ['productEdit','productUpdate']]);
        $this->middleware('permission:product-delete', ['only' => ['deleteProduct']]);
    }
   
    public function product(Request $request)
    {
        $status =   $request->status;
      
        return view('admin::Product.product',compact('status'));
    }

    public function productList(Request $request)
    {
       
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        if($request->status == 'approved')
        {
            $status_val = 1;
        }else{
            $status_val = 0;
        }
        
        $products = Product::with(['categories','brand','shop','parentProduct','attributes'])->where('is_approved',$status_val)->orderBy('id','desc');

        if ($search != '') 
        {
             $products = $products->where('is_approved',$status_val)->where(function($query) use($search,$status_val){
                $query->where('name', 'LIKE', '%'.$search.'%');
                $query->orWhere('sku','LIKE', '%'.$search.'%');
             });
            
             $products = $products->orWhereHas('brand', function($q) use($search){
                $q->where('name', 'LIKE', '%'.$search.'%');
            })->where('is_approved',$status_val);
            
            $products = $products->orWhereHas('categories', function($query) use ($search){//interested in
                $query->where('name', 'LIKE', '%'.$search.'%');
            })->where('is_approved',$status_val);

            $products = $products->orWhereHas('shop', function($q) use($search){
                $q->where('name', 'LIKE', '%'.$search.'%');
            })->where('is_approved',$status_val);
        
        }

        $total = $products->count();

        $result['data'] = $products->take($request->length)->skip($request->start)->get();
        
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function productAdd($shop_id=null)
    {
        // $categories = BusinessCategory::where(['is_active' => 1, 'is_last_child' => 1])->orderBy('name')->get();

        $category_ids = BusinessCategoryShop::where(['shop_id' => $shop_id])->pluck('category_id')->toArray();

        $categories = BusinessCategory::whereIn('id', $category_ids)->orderBy('name')->get();


        $brands = Brand::where('is_active',1)->orderBy('name')->get();

        $shop = BusinessShop::findorFail($shop_id);

        $units = Unit::where('is_active',1)->orderBy('name')->get();

        $parent_products = Product::where('seller_id',$shop->id)->where('parent_id',0)->where('is_active',1)->with('shop')->get();


        return view('admin::Product.addProduct', compact('categories','brands','shop','units','parent_products'));

    }

   public function productStore(ProductStoreRequest $request)
   {


    if($request->has('attrValue') && $request->product_type == 2)
    {
        $business_field_ids =[];
        $type = 1 ;
        
        if($request->attrValue== '')
        {
            return redirect()->back()->with('message', 'Please add attribute value for product.')->withInput();
        }else{

            $attr_value = explode('&_',$request->attrValue);
            if(Product::find($request->parent_product)){
                $parent_product = $request->parent_product;

                foreach($attr_value as $attr)
                {
                    $business_fields = DB::table('business_category_fields')->whereIn('category_id',$request->categories)->whereRaw("find_in_set('".$attr."',business_category_fields.field_value)")->where('is_active',1)->get();
                    if($business_fields->count()>0)
                    {
                        foreach($business_fields as $business_field)
                        {
                            $ids[$business_field->id]= $attr;
                            
                        }
                        
                    }
                   
                }
                
                $business_field_ids = array_unique($ids);
                
            }else{
                return redirect()->back()->with('message', "Parent product couldn't be found.")->withInput();
            }
        }

    }else if($request->product_type == 1){
        $type = 2;
        $parent_product = 0;
     
    }else if($request->product_type == 0){
        $type = 0 ;
        $parent_product = NULL;
    }else{

        return redirect()->back()->with('message', "Some missing fields found product can't be add.")->withInput();
    }
    

    $seller_id = $request->input('shop_id',null);
        
    if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

            if($request->hasfile('thump_image'))
            {
                $thumpimage = $request->thump_image;
                $shop = BusinessShop::findorFail($seller_id);
                $imageName = str_replace(' ', '', time()).trim($thumpimage->getClientOriginalName());
                $shop_name = str_replace(' ', '_',trim($shop->name));
                $thumpimage->move(storage_path('app/products/'.$shop_name), $imageName);  
                $url = "products/".$shop_name."/".$imageName; 
            }

            $product = Product::create([
                'type' => $type,
                'name' => $request->name,
                'sku'  => $request->sku,
                'seller_id' => $seller_id,
                'brand_id' => $request->brand_id,
                'price' => $request->price,
                'vendor_price'=> $request->vendor_price,
                'stock' => $request->stock,
                'is_active' => $is_active,
                'thump_image' => $url,
                'description' => $request->description,
                'unit_id'    => $request->unit_id,
                'measurement_unit' => $request->measurement_unit,
                'parent_id'        => $parent_product,
                'is_approved'      => $request->approve,
                'commission'    => $request->commission,
                'offer' => $request->offer
            ]);
            
            if(!empty($business_field_ids))
            {
                foreach($business_field_ids as $key=>$value){
                    $product->attributes()->syncWithoutDetaching([$key => ['attr_value' => $value]]);
                }
            }
            

            $pics= $request->images;
    

            if($request->hasfile('images'))
            {
   
            
                $array = [];
               foreach($pics as $image)
               {   
                   
                   $imageName = str_replace(' ', '', time()).trim($image->getClientOriginalName());
                   $shop = BusinessShop::findorFail($seller_id);
                   $shop_name = str_replace(' ', '_',trim($shop->name));
                   $image->move(storage_path('app/products/'.$shop_name), $imageName);  
                   $url = "products/".$shop_name.'/'.$imageName; 
   
                   ////Image resize
                   // $filename    = trim($image->getClientOriginalName());
                   // $image_resize = Image::make($image->getRealPath());              
               
                   // $image_resize->resize(300, null, function ($constraint) {
                   //     $constraint->aspectRatio();
                   // });
   
                   // $image_resize->save('storage/app/shops/'.$filename);
               
                   // $url = 'shops/'.$filename;
                    $array[] = ['product_id' => $product->id , 'image' => $url];
               }

               ProductImage::insert($array);
            }
          

            if ($request->has('categories')) {
                $params = $request->categories;
                $categories = BusinessCategory::find($params);
               
                foreach ($categories as $category) {
                    $category_id_array[$category->id] = ['shop_id' => $seller_id];  
                }
               
                $product->categories()->sync($category_id_array);
                
            }

            if($request->search_keyword != null && count($request->search_keyword) > 0) {
                foreach($request->search_keyword as $keyword) {
                    TrendKeyword::create(['product_id' => $product->id, 'term' => $keyword, 'is_active' => 1]);
                }
            }

            return redirect()->back()->with('message', 'Product added Successfully.');



   }


   public function import(Request $request)
   {

    if($request->hasFile('product_file'))
    {

    $validator = Validator::make(
        [
            'file'      => $request->product_file,
            'extension' => strtolower($request->product_file->getClientOriginalExtension()),
        ],
        [
            'file'          => 'required',
            'extension'      => 'required|in:xlsx,xls',
        ]
      
      );

        if ($validator->fails()) {

         return back()->with('message', 'Please select xls or xlsx file');
        }

    }else{
        return back()->with('message', 'Please select xls or xlsx file');

    }

       $shop = BusinessShop::findorFail($request->shop_id);

       $path1 = $request->file('product_file')->store('exceltemp'); 
      
       $path=storage_path('app').'/'.$path1;  

       session()->forget('row');
       session()->forget('error_msg');

       Excel::import($import = new ProductImport($shop),$path);
      
       unlink(storage_path('app').'/'.$path1);
       
       $arr = session()->get('row');
       $err_msg = session()->get('error_msg');
       
       // $parent_cat = array();
       // $sub_cat = array();

       $parent_cat = session()->get('parent_cat');
       $sub_cat = session()->get('sub_cat');
       // dump($parent_cat);
       // dd($sub_cat);
       if($import->getRowCount()>0 && !is_null($arr))
       {

            $parent_message = '';
            $sub_message = '';

            if(count($parent_cat) > 0 && count($sub_cat) > 0) {
              $parent_message = 'Please Note : Missing Parent categories to seller - '.implode(',', $parent_cat);
              $sub_message = 'Missing Sub categories to shop - '.implode(',', $sub_cat);
              
              $message = $import->getRowCount().' product Details Inserted Successfully. But please link these parent categories to seller -  '.implode(',', $parent_cat);
            } elseif(count($parent_cat) > 0) {
              $parent_message = 'Please Note : Missing Parent categories to seller - '.implode(',', $parent_cat);
              $sub_message = '';
            } elseif(count($sub_cat) > 0) {
              $parent_message = '';
              $sub_message = 'Please Note : Missing Sub categories to shop - '.implode(',', $sub_cat);
            }

            return back()->with('message', $import->getRowCount().' product Details Inserted Successfully.Sku '.implode(',', $arr).' are not inserted because of '.implode(',', $err_msg).'.  '.$parent_message.'. '.$sub_message);

            // return back()->with('message', $import->getRowCount().' product Details Inserted Successfully.Sku '.implode(',', $arr).' are not inserted because of '.implode(',', $err_msg));
       }else if($import->getRowCount()>0 && is_null($arr)){

            $parent_message = '';
            $sub_message = '';

            if(count($parent_cat) > 0 && count($sub_cat) > 0) {
              $parent_message = 'Please Note : Missing Parent categories to seller - '.implode(',', $parent_cat);
              $sub_message = 'Missing Sub categories to shop - '.implode(',', $sub_cat);
              
              $message = $import->getRowCount().' product Details Inserted Successfully. But please link these parent categories to seller -  '.implode(',', $parent_cat);
            } elseif(count($parent_cat) > 0) {
              $parent_message = 'Please Note : Missing Parent categories to seller - '.implode(',', $parent_cat);
              $sub_message = '';
            } elseif(count($sub_cat) > 0) {
              $parent_message = '';
              $sub_message = 'Please Note : Missing Sub categories to shop - '.implode(',', $sub_cat);
            }

            return back()->with('message', $import->getRowCount().' product Details Inserted Successfully. '.$parent_message.'. '.$sub_message);

       }else{
            return back()->with('message', implode(',', $arr).' is not inserted because of '.implode(',', $err_msg));
       }
        
   }


   public function productEdit($id,$shop_id=null)
   {

        $categoryIds =[];
       
        $product = Product::findOrFail($id);

        // $categories = BusinessCategory::where(['is_active' => 1, 'is_last_child' => 1])->orderBy('name')->get();

        $category_ids = BusinessCategoryShop::where(['shop_id' => $shop_id])->pluck('category_id')->toArray();

        $categories = BusinessCategory::/*where(['is_active' => 1, 'is_last_child' => 1])*/whereIn('id', $category_ids)->orderBy('name')->get();

        $brands = Brand::where(['is_active' => 1])->orderBy('name')->get();

        foreach($product->categories as $cat)
        {
            $categoryIds[] = $cat->id;
        }        

        $shop = BusinessShop::findorFail($shop_id);

        $units = Unit::where('is_active',1)->orderBy('name')->get();

        $keywords = TrendKeyword::where('product_id', $product->id)->get();

        return view('admin::Product.editProduct', compact('product','categories','brands','categoryIds','shop','units', 'keywords'));
   }


   public function deleteImage(Request $request)
   {
        ProductImage::where('id', $request->img_id)->delete();
   
        return response()->json('Delete successfuly');
   }

  
    public function productUpdate(ProductEditRequest $request)
    {
        $product = Product::where('id' , $request->id)->first();

        $seller_id = $request->input('shop_id',null);
        
        if($product)
        {

            $categories = array();
            $pics = array();
    
            if($request->status == 0)
                $is_active = 0;
            else
                $is_active = 1;

                if($request->hasfile('thump_image'))
                {
                    $thumpimage = $request->thump_image;
                    $shop = BusinessShop::findorFail($seller_id);
                    $shop_name = str_replace(' ', '_',trim($shop->name));
                    $imageName = str_replace(' ', '', time()).trim($thumpimage->getClientOriginalName());
                    $thumpimage->move(storage_path('app/products/'.$shop_name), $imageName);  
                    $url = "products/".$shop_name.'/'.$imageName; 
                }

                if(Auth::guard('admin')->check())
                {
                    $commission = $request->commission;
                }else{
                    $commission = $product->commission;
                }

                Product::whereId($request->id)->update([
                    'name' => $request->name,
                    'sku'  => $request->sku,
                    'seller_id' => $seller_id,
                    'brand_id' => $request->brand_id,
                    'price' => $request->price,
                    'vendor_price'=> $request->vendor_price,
                    'stock' => $request->stock,
                    'is_active' => $is_active,
                    'thump_image' => $url ?? $product->thump_image,
                    'description' => $request->description,
                    'unit_id'    => $request->unit_id,
                    'measurement_unit' => $request->measurement_unit,
                    'is_approved'      => $request->approve,
                    'commission'    => $commission,
                    'offer' => $request->offer
                ]);


                $pics= $request->images;
    

                if($request->hasfile('images'))
                {
       
                
                    $array = [];
                   foreach($pics as $image)
                   {   
                       
                       $imageName = str_replace(' ', '', time()).trim($image->getClientOriginalName());
                       $shop = BusinessShop::findorFail($seller_id);
                       $shop_name = str_replace(' ', '_',trim($shop->name));
                       $image->move(storage_path('app/products/'.$shop_name), $imageName);  
                       $url = "products/".$shop_name.'/'.$imageName; 
       
                       ////Image resize
                       // $filename    = trim($image->getClientOriginalName());
                       // $image_resize = Image::make($image->getRealPath());              
                   
                       // $image_resize->resize(300, null, function ($constraint) {
                       //     $constraint->aspectRatio();
                       // });
       
                       // $image_resize->save('storage/app/shops/'.$filename);
                   
                       // $url = 'shops/'.$filename;
                        $array[] = ['product_id' => $product->id , 'image' => $url];
                   }
    
                   ProductImage::insert($array);
                }

                if ($request->has('categories')) {
                    $params = $request->categories;
                    $categories = BusinessCategory::find($params);

                    foreach ($categories as $category) {
                        $category_id_array[$category->id] = ['shop_id' => $seller_id];  
                    }
                   
                    $product->categories()->sync($category_id_array);
                   
                  
                    
                }

                TrendKeyword::where('product_id', $product->id)->delete();

                if($request->search_keyword != null && count($request->search_keyword) > 0) {
                    foreach($request->search_keyword as $keyword) {
                        TrendKeyword::create(['product_id' => $product->id, 'term' => $keyword, 'is_active' => 1]);
                    }
                }
              

                return back()->with('message', 'Product Updated Successfully.');
    

        }else{
            return back()->with('message', 'Product not found');
        }
        
    }
    
  
    public function productUntracked($id)
    {
       
        
        $products = ProductUntracked::whereHas('trackedProducts', function($q) use($id){
                $q->where('seller_id', $id);
            })->pluck('sku')->toArray();
           
            if($products){
                
               $product_inserted =Product::where('seller_id',$id)->whereIn('sku', $products)->pluck('sku')->toArray();
            
               if( $product_inserted){
                $untrack_id = ProductUntracked::whereIn('sku', $product_inserted)->pluck('id')->toArray();
                
                ProductUntrackedImages::whereIn('id',$untrack_id)->delete();
                ProductUntracked::whereIn('id', $untrack_id)->delete();
                
               }
            }
        return view('admin::Product.productUntracked',compact('id'));
    }

    
    public function untrackProductList(Request $request,$id)
    {
        
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $products = ProductUntracked::where('seller_id',$id)->orderBy('id','desc');

        if ($search != '') 
        {
            $products->where('name', 'LIKE', '%'.$search.'%')->orWhere('sku','LIKE', '%'.$search.'%')->where('seller_id',$id);
        }

        $total = $products->count();

        $result['data'] = $products->take($request->length)->skip($request->start)->get();
        
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function untrackProductExport($shop_id)
    {

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $shop = BusinessShop::findOrFail($shop_id);

        return Excel::download(new UntrackedProductExport($shop->id), $shop->name.'_untracked_product'.Carbon::today()->toDateString().'.xlsx');

    }

    public function untrackProductDelete($product_id)
    {
        ProductUntrackedImages::where('product_id',$product_id)->delete();
        ProductUntracked::find($product_id)->delete();
        return back()->with('message', 'Product Deleted Successfully.');
    }

    public function getVarients(Request $request)
    {
        $view = '';
        if(!is_null($request->category_fields))
        {
            $cat =[];
            $category = explode(',',$request->category_fields);
            
           $category_fields = BusinessCategoryField::whereIn('category_id',$category)->where('is_active',1)->orderBy('category_id')->get();
           if($category_fields->count()>0)
           {
               foreach($category_fields as $category_field)
               {
                    $cat_value = explode(',',$category_field->field_value);
                    $cate[$category_field->field_name] = $cat_value; 
               }

               if(!empty($cate) || !is_null($cate))
               {
                  
                    $view = view("admin::Product.productAttribute",compact('cate'))->render();
               }else{
                   $view = '';
               }
               
           }else{
               $view = '<div><div class="form-group row form-space">
                        <label class="col-xs-12 col-lg-4 col-form-label"></label>
                        <div class="col-xs-12 col-lg-8 "><span style="color: red"><b>No attributes found</b></span>
                        </div></div></div>';
           }
          
        }

        return response()->json(['html'=>$view]);
    }


    public function editProductAttribute($id)
    {
        $product = Product::findOrFail($id);

        $attribute_values = [];
        $attribute_names = [];
        $field_val = [];
        if($product){
            
            $product_attribute =Product::where('id',$product->id)->with(['attributes','categories'])->first();

            foreach($product_attribute->attributes as $attr)
            {
                array_push($attribute_names,$attr->field_name.'->'.$attr->pivot->attr_value);
                array_push($attribute_values,$attr->pivot->attr_value);
            }

            foreach($product_attribute->categories as $cat)
            {
                foreach($cat->categoryAttributes as $attr)
                {
                    $field_value = explode(',',$attr->field_value);
                    $field_val[$attr->field_name] =  $field_value;
                    $field_nam[]=$attr->field_name;

                }
            }
    
            return view('admin::Product.editProductAttribute',compact('product_attribute','attribute_values','field_val','attribute_names'));
        }else{
            return redirect()->back()->with('message', 'Product does not exists.');
        }
    }

    public function updateAttribute(Request $request)
    {

        $ids = array();

        $product = Product::findOrFail($request->id);

        if($product){


        if($request->has('attrValue'))
        {
        $business_field_ids =[];
      
        
        if($request->attrValue== '')
        {
            return redirect()->back()->with('message', 'Please add attribute value for product.')->withInput();
        }else{

            $attr_values = explode('&_',$request->attrValue);
           
             $attr_names = explode('|',$request->attrName);

             $chunks = array_chunk(preg_split('/(->|,)/', $request->attrName), 2);
             
             $results = array_combine(array_column($chunks, 0), array_column($chunks, 1)); //geting each attribute as key value
            
            foreach($results as $key=>$val)   
            {       
                $business_field = DB::table('business_category_fields')->whereIn('category_id',$request->cat_id)->where('field_name',$key)->whereRaw("find_in_set('".$val."',business_category_fields.field_value)")->where('is_active',1)->where('deleted_at', null)->first();
                
                if($business_field)
                {
                    $ids[$business_field->id] = $val;

                }
            }     
                
              // dd($business_field);
                
                $business_field_ids = $ids;
                $syncing_values = [];
              
                if(!empty($business_field_ids))
                {
                    foreach($business_field_ids as $key=>$value){
                        // dump($value);
                       $syncing_values[$key] = ['attr_value' => $value];
                    }
                }
                
                $product->attributes()->sync($syncing_values,true);
                return redirect()->back()->with('message', 'Attrbute edited successfully.');
        
        }

    }



        }else{
            return redirect()->back()->with('message', 'Product does not exists.');
        }

    }

    public function setRadioSession(Request $request)
    {
        // Session::forget('check_radio[]');
        // $checked_values = $request->checked_values;
        // $values =[];
        // foreach($checked_values as $val)
        // {
        //     Session::push('check_radio[]',$val);
        // }
        // return response()->json(['success'=>'Ajax request submitted successfully']);
    }


    public function changeProductStatus(Request $request)
    {
        if($request->ajax()){
            
            if($request->product_id != '' || !is_null($request->product_id))
            {

                $product = Product::where('id',$request->product_id)->first();
                if($product->is_approved == 1)
                {
                    if(Auth::guard('admin')->check())
                    {
                        $product->toggleIsActive()->save();
                        
                        return response()->json(['success'=>'status changed succesfully.']);
                    }else{
                        $product->toggleIsActive()->save();
                        return response()->json(['success'=>'status changed succesfully.']);
                       // return response()->json(['error'=>'permission denied please contact admin']);
                    }
                }else{

                    if(Auth::guard('admin')->check())
                    {
                        $product->toggleIsActive()->save();

                        return response()->json(['success'=>'status changed succesfully.']);
                    }else{

                        $product->toggleIsActive()->save();
                        return response()->json(['success'=>'status changed succesfully.']);
                      //  return response()->json(['error'=>'permission denied please contact admin']);
                    }

                }
               
            }else{
                return response()->json(['error'=>'Status cannot be changed']);
            }
        }
    }

    public function productPendingEdit($id,$shop_id=null)
    {
        
         $product = Product::findOrFail($id);
 
         $categories = BusinessCategory::where(['is_active' => 1, 'is_last_child' => 1])->orderBy('name')->get();
 
         $brands = Brand::where(['is_active' => 1])->orderBy('name')->get();
 
         foreach($product->categories as $cat)
         {
             $categoryIds[] = $cat->id;
         }        
 
         $shop = BusinessShop::findorFail($shop_id);
 
         $units = Unit::where('is_active',1)->orderBy('name')->get();
 
 
         return view('admin::Product.editPendingProduct', compact('product','categories','brands','categoryIds','shop','units'));
    }

    public function changeProductApproveStatus(Request $request)
    {

        if($request->ajax()){
            
            if($request->product_id != '' || !is_null($request->product_id))
            {

                $product = Product::where('id',$request->product_id)->first();
               
                    if(Auth::guard('admin')->check())
                    {
                        $product->is_approved = 1;
                        $product->save();
                        
                        return response()->json(['success'=>'product approved succesfully.']);
                    }else{

                        return response()->json(['error'=>'permission denied please contact admin']);
                    }
           
                
               
            }else{
                return response()->json(['error'=>'Approve cannot be done to this product']);
            }
        }

    }


    public function productExport($status)
    {
        if(Auth::guard('admin')->check())
        {
            $is_approved = ($status === 'approved') ? 1 : 0;

            if(isset($is_approved))
            {

                
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                return Excel::download(new ProductStatusExport($is_approved), $status.'_product_list_'.Carbon::today()->toDateString().'.xlsx');

            }else{
                return redirect()->back();
            }

        }else{
            
            return redirect()->back();

        }
    }
 
    
}
