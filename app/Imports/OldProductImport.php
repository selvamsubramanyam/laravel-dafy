<?php

namespace App\Imports;

use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductImage;
use Modules\Product\Entities\ProductUntracked;
use Modules\Product\Entities\ProductUntrackedImages;
use Modules\Admin\Entities\Brand;
use Modules\Category\Entities\BusinessCategory;
use Modules\Shop\Entities\BusinessCategoryShop;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\Shop\Entities\BusinessShop;
use Modules\Category\Entities\BusinessCategoryField;
use Modules\Users\Entities\BusinessSellerCategory;
use Carbon\Carbon;
use Modules\Admin\Entities\Unit;
use Illuminate\Support\Str;
use DB;
use Session;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Auth;

class ProductImport implements ToModel, WithStartRow 
{

    private $rows = 0;
    private $prod= [];
    private $err = [];
    private $sellerCat = [];
    private $shopCat = [];
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
    
    public function __construct(BusinessShop $shop)
    {
        $this->shop = $shop;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

    $categories = explode('||',$row[7]);

    foreach ($categories as $key => $category) {
        // dump($category);
        $sep_parents = explode('->',$category);

        
        foreach ($sep_parents as $key => $sep_parent) {

            //for parent categorys
            $parent_cat = BusinessCategory::where(['name' => $sep_parents[0], 'parent_id' => 0])->first();

            if($parent_cat) {

                //checking seller category is exist or not
                $seller_cat_exist = BusinessSellerCategory::where(['user_id' => $this->shop->seller_id, 'main_category_id' => $parent_cat->id])->first();
                
                if(!$seller_cat_exist) {
                    // dump($parent_cat);
                    $this->sellerCat[] = $parent_cat->name;
                }
                //end
            }
            //end

            //for sub categorys
            if(isset($sep_parents[1]) && $parent_cat) {
                $sub_cat = BusinessCategory::where(['name' => $sep_parents[1], 'parent_id' => $parent_cat->id])->first();
                // dump($sub_cat);
                if($sub_cat) {
                    //checking seller category is exist or not
                    $shop_cat_exist = BusinessCategoryShop::where(['shop_id' => $this->shop->id, 'category_id' => $sub_cat->id])->first();
                    
                    if(!$shop_cat_exist) {
                        // dump($parent_cat);
                        $this->shopCat[] = $sub_cat->name;
                    }
                    //end
                }
            }
            //end
        }

        
    }

    session()->put('parent_cat', array_unique($this->sellerCat));
    session()->put('sub_cat', array_unique($this->shopCat));

                    $count = 0;
                    $conf=[];
                    $product_type = 'single';
                    $array = [];
                    $images= [];
                    $flag = 0;
                    $parent_avail = 0;
                    $configurable_variations = NULL;

                  
                        if(strtolower($row[11])=='configurable') //have variations parent
                        { 
                            $sku_exist = Product::where('sku',$row[0])->first();
                            
                            // if($sku_exist)
                            // {
                            //     if($sku_exist->seller_id != $this->shop->id)
                            //     {
                            //         $flag = 1; //sku already exists;
                            //         array_push($this->err,'sku already exists');
                            //     }

                            // }
                                $product_type = 'configurable';
                                $type = 2;
                                $variations = explode('|',$row[12]);
                                if(count($variations)>0){

                                    foreach($variations as $variat){
                                    $sub_var = explode(',',$variat);
                                    $sku = substr($sub_var[0], strpos($sub_var[0], "=") + 1);    
                                    $var_name = explode('=',$sub_var[1]);
                                    $data = ['sku' => $sku,$var_name[0] => $var_name[1]];
                                    array_push($conf,$data);
                                    }

                                    $configurable_variations = json_encode($conf);
                                    $parent_id = 0;
                                }else{
                                    $flag =1;//no varients error
                                    array_push($this->err,'configurable variation missing');
                                    
                                }
                            
                      
                        
                        }else if(strtolower($row[11])=='simple'){ //child varients

                            $product_type = 'simple';
                            $type = 1;
                            $sku_exist = Product::where('sku',$row[0])->first();
                            
                            // if($sku_exist)
                            // {
                            //     if($sku_exist->seller_id != $this->shop->id)
                            //     {
                            //         $flag = 1; //sku already exists;
                            //         array_push($this->err,'sku already exists');
                            //     }

                            // }
                                $parent_product = Product::where('parent_id',0)->whereJsonContains('configurable_variations',['sku'=>$row[0]])->latest()->first(); //get parent
                                
                                if($parent_product)
                                    $parent_id = $parent_product->id;

                                if($parent_product){
                                    $parent_avail = 1;
                                
                                }else{
                                    $flag = 1; //no parent found;
                                    array_push($this->err,'parent value not found');
                                    
                                }
                            
                          
                        }else if(strtolower($row[11])=='single'){
                            
                            $product_type = 'single';
                            $type = 0;
                            $parent_id  = NULL;
                            $sku_exist = Product::where('sku',$row[0])->first();
                            
                            // if($sku_exist)
                            // {
                            //     if($sku_exist->seller_id != $this->shop->id)
                            //     {
                            //         $flag = 1; //sku already exists;
                            //         array_push($this->err,'sku already exists');
                            //     }

                            // }

                        }else{
                            $flag =1; //undefined product type
                            array_push($this->err,'undefined product type');
                            
                        }
                        // dd($row[20]);
                    if($flag == 0){

                        if(!empty($row[1]) && !is_null($row[1]))
                        {    
                            $brand = Brand::firstOrCreate(['name' => $row[1]],
                                        ['slug' => $row[1]]);
                        }
        
                        if(!empty($row[9]) && !is_null($row[9]))
                        {    
                            $unit = Unit::firstOrCreate(['name' => $row[9]],
                                    ['slug' => $row[9]]);
                        }
        
            
                        //find if main category exists
                        $categories = $row[7];
        
                        $main_categories=[];
                        $category = [];
                        $cat=[];
                        $sub_cat_ids = [];
        
                        //sepperate pipe
                        $pip_sep = explode('||',$categories);
                        
                        foreach($pip_sep as $key=>$value)
                        {
                           
                            $arr_sep = explode('->',$value);

                            if(isset($arr_sep[1]) && isset($arr_sep[0]))
                            {
                                $main_categories = ['main_cat'=>$arr_sep[0],'sub_cat'=>$arr_sep[1]];
                            }else if(isset($arr_sep[0])){
                                $main_categories = ['main_cat'=>$arr_sep[0]];
        
                            }else{
                              
                                //trash item
                                $flag = 1;
                                array_push($this->err,'category missing or not found1');
                                
                            }
                            array_push($category,$main_categories);
                        }
                        
                        foreach($category as $key=>$cat)
                        { 
                            if($flag==1)
                            {
                                break;
                            }
                            if (array_key_exists("main_cat",$cat))
                            {
                               
                                $category_item = BusinessCategory::where("name",trim($cat['main_cat']))->where('parent_id',0)->first();
                                if($category_item)
                                {
                                    if(isset($cat['sub_cat']))
                                    { 
                                        $sub_category = $category_item->whereHas('childrens', function ($query) use ($cat,$category_item) {
                                            $query->where('name',trim($cat['sub_cat']))->where('parent_id',$category_item->id);
                                        })->orderByDesc('created_at')->first();
                                       
                                        if($sub_category)
                                        { 
                                            $get_subcat = BusinessCategory::where('parent_id',$sub_category->id)->where('name',trim($cat['sub_cat']))->where('is_last_child',1)->first();
                                         
                                            if($get_subcat)
                                            {
                                                if($parent_avail == 1)
                                                {
                                                    $attr = explode('=',$row[13]);
                                                    
                                                    $attr_fieldname = BusinessCategoryField::where('field_name',trim($attr[0]))->where('category_id',$get_subcat->id)->first();
                                                    
                                                    if($attr_fieldname){
                    
                                                        $field_value = explode(',',$attr_fieldname->field_value);
                                                        $field_value = array_map('strtolower', $field_value);
                                                       
                                                        if(in_array(strtolower($attr[1]),$field_value,true))//attr value exist
                                                        {
                                                            $attr_value = $attr[1]; //attr value like x ,s,xl
                                                            $categ_field_id = $attr_fieldname->id; //caregory field attr id
                                                            $parent_id = $parent_product->id; //parent product id
                                                        }else{
                                                           
                                                            if(!is_null($attr_fieldname->field_value))
                                                            {
                                                                    $attr_field_value = $attr_fieldname->field_value.','.$attr[1];
                                                            }else{
                                                                    $attr_field_value = $attr[1] ;
                                                                   
                                                            } 
                                                                BusinessCategoryField::where('field_name',trim(strtolower($attr[0])))->where('category_id',$get_subcat->id)->update([
                                                                        'field_value' => $attr_field_value
                                                                ]);
                                                                $attr_fieldname = BusinessCategoryField::where('field_name',trim(strtolower($attr[0])))->where('category_id',$get_subcat->id)->first();
                                                                $field_value = explode(',',$attr_fieldname->field_value);
                                                                $field_value = array_map('strtolower', $field_value);
                                                                
                                                                if(in_array(strtolower($attr[1]),$field_value,true))//attr value exist
                                                                {
                                                                    $attr_value = $attr[1]; //attr value like x ,s,xl
                                                                    $categ_field_id = $attr_fieldname->id; //caregory field attr id
                                                                    $parent_id = $parent_product->id; //parent product id
                                                                }
                                                            
                                                        }
                    
                                                    }else{
                                                      
                                                        BusinessCategoryField::Create(
                        
                                                                            [
                                                                                'field_name' => $attr[0],
                                                                                'category_id' => $get_subcat->id,
                                                                                'field_value' => $attr[1],
                                                                                'control'     => 2,
                                                                                'is_active'   => 1
                                                                            ]);
                                                        $attr_fieldname = BusinessCategoryField::where('field_name',trim(strtolower($attr[0])))->where('category_id',$get_subcat->id)->first();
                                                       
                                                        if($attr_fieldname){
                    
                                                            $field_value = explode(',',$attr_fieldname->field_value);
                                                            $field_value = array_map('strtolower', $field_value);
                                                            
                                                            if(in_array(strtolower($attr[1]),$field_value,true))//attr value exist
                                                            {
                                                                $attr_value = $attr[1]; //attr value like x ,s,xl
                                                                $categ_field_id = $attr_fieldname->id; //caregory field attr id
                                                                $parent_id = $parent_product->id; //parent product id
                                                            }else{
                                                                if(!is_null($attr_fieldname->field_value))
                                                                    $attr_field_value = $attr_fieldname->field_value .','.$attr[1];
                                                                else
                                                                    $attr_field_value = $attr[1] ;
                                                                BusinessCategoryField::where('field_name',trim($attr[0]))->where('category_id',$get_subcat->id)->update([
                                                                        'field_value' => $attr_field_value
                                                                ]);
                                                                $attr_fieldname = BusinessCategoryField::where('field_name',trim($attr[0]))->where('category_id',$get_subcat->id)->first();
                                                                $field_value = explode(',',$attr_fieldname->field_value);
                                                                $field_value = array_map('strtolower', $field_value);
                                                                
                                                                if(in_array(strtolower($attr[1]),$field_value,true))//attr value exist
                                                                {
                                                                    $attr_value = $attr[1]; //attr value like x ,s,xl
                                                                    $categ_field_id = $attr_fieldname->id; //caregory field attr id
                                                                    $parent_id = $parent_product->id; //parent product id
                                                                }
                                                               
                                                                
                                                            }
                        
                                                        }
                                                        
                                                    }
                                                }
                                            
                                            array_push($sub_cat_ids,$get_subcat->id);
                                            
                                            }else{
                                                
                                                //trash
                                                $sub_cat_id = BusinessCategory::create([
                                                    'name' => $cat['sub_cat'],
                                                    'slug' => str_slug($cat['sub_cat']),
                                                    'parent_id' => $category_item->id,
                                                    'parent_name' => '>>'.$category_item->name,
                                                    'is_last_child' => 1,
                                                    'module_id' => 1,
                                                    'is_active' => 1
                                                ]);
                                                $flag = 0;
                                            //     $flag = 1;
                                            //     array_push($this->err,'child category missing or not found');
                                                array_push($sub_cat_ids,$sub_cat_id->id);
                                            }
                                        }else{
                                            //trash
                                            $sub_cat_id = BusinessCategory::create([
                                                'name' => $cat['sub_cat'],
                                                'slug' => str_slug($cat['sub_cat']),
                                                'parent_id' => $category_item->id,
                                                'parent_name' => '>>'.$category_item->name,
                                                'is_last_child' => 1,
                                                'module_id' => 1,
                                                'is_active' => 1
                                            ]);
                                            $flag = 0;
                                            // array_push($this->err,'category missing or not found2');
                                            array_push($sub_cat_ids,$sub_cat_id->id);
                                            
                                        }
                                    }else{

                                        $category_item = BusinessCategory::where("name",trim($cat['main_cat']))->where('parent_id',0)->first();
                                        
                                        if($category_item)
                                        {
                                            if($category_item->childrens->count()>0){
                                                //skip to trash
                                                $flag = 1;
                                            }else{
                                                BusinessCategory::where('id',$category_item->id)->update([
                                                    
                                                    'parent_id' => $category_item->id,
                                                    'parent_name' => '>>'.$category_item->name,
                                                    'is_last_child' => 1,
                                                    'module_id' => 1,
                                                    'is_active' => 1
                                                ]);
                                            $sub_updated = BusinessCategory::where('id',$category_item->id)->first();
                                                array_push($sub_cat_ids,$sub_updated->id);
                                            }
                                        }else{
        
                                            //skip to trash
                                       
                                            $flag = 1;
                                            array_push($this->err,'category missing or not found3');
                                            
                                        }
                                        
                                    }
                                    
                                }else{
        
                                    $child_cat = BusinessCategory::where("name",trim($cat['main_cat']))->where('is_last_child',1)->first();
                                  
                                    if($child_cat)
                                    {
        
                                        $child = BusinessCategory::where('id',$child_cat->id)->first();
                                        array_push($sub_cat_ids,$child->id);
        
                                    }else{
        
                                         //main category not exist skip to trash
                                  
                                    $flag = 1;
                                    array_push($this->err,'Parent category missing or not found');
                                    
        
                                    }
                                   
                                    
                                }
                                
                            }else{
                                // trash product
                               
                                $flag = 1;
                                array_push($this->err,'Parent category missing or not found');
                                
                            }
                        }
                    }

                    //image validation

                    if($flag == 0)
                    {
                        $imageExtensions = ['jpg', 'jpeg', 'png'];

                        $explodeImage = explode('.', $row[8] );
                        $extension = end($explodeImage);

                        if(in_array($extension, $imageExtensions))
                        {
                            // thump image Is image

                            //check other images extension
                            $co = 1;
                            for($i=15;$i<=19;$i++)
                            {
                                if($row[$i] != null && !empty($row[$i]))
                                {
                                    $explodeImage = explode('.', $row[$i] );
                                    $extension = end($explodeImage);

                                    if(in_array($extension, $imageExtensions))
                                    {
                                        // valid images
                                        $flag = 0;
                                    }else{

                                        // Is not image 
                                         // trash product
                               
                                        $flag = 1;
                                        array_push($this->err, $row[0].' img'.$co.' must be a jpg , jpeg or png .');

                                    }
                                }
                                $co++;
                            }

                        }else
                        {
                            // Is not image 
                             // trash product
                               
                             $flag = 1;
                             array_push($this->err, $row[0].' thump image must be a jpg , jpeg or png .');
                        }
                    }

        
                    if($flag == 0)
                    {

                        $shop = BusinessShop::findorFail($this->shop->id);
                        $shop_name = str_replace(' ', '_',trim($shop->name));

                        if(Auth::guard('admin')->check())
                        {
                            $is_approve = 1;
                            $is_active = 1;
                        }else{
                            $is_approve = 0;
                            $is_active = 0;
                        }

                        
                        $product = Product::updateOrCreate(['sku' => $row[0],'seller_id' => $this->shop->id], 
                        
                        [
                            'type'            => $type,
                            'brand_id'        => $brand->id ?? NULL,
                            'name'            => $row[2],
                            'vendor_price'    => $row[3], 
                            'price'           => $row[4],
                            'description'     => $row[5],
                            'stock'           => $row[6],
                            'thump_image'     => $row[8] ? "products/".$shop_name.'/'.str_replace(' ', '', $row[8]) : NULL,
                            'unit_id'         => $unit->id ?? NULL,
                            'measurement_unit'=> $row[10],
                            'parent_id'       => $parent_id,
                            'configurable_variations' => $configurable_variations,
                            'is_active'  => $is_active,
                            'is_approved'   => $is_approve,
                            'commission'    => $row[14],
                            'offer'         => $row[20]
                        ]);

                        if( $product_type == 'simple'){
                            
                            $product->attributes()->syncWithoutDetaching([$categ_field_id => ['attr_value' => $attr_value]]);
                            // $cat_varient_id = DB::table('product_attribute')
                            // ->where('product_id', $product->id)
                            // ->whereIn('attribute_id', [$categ_field_id])
                            // ->first();
                  
                            
                           
                            // Product::updateOrCreate(['sku' => $row[0],'seller_id' => $this->shop->id], 
                            //         [
                            //             'cat_varient_id' => $cat_varient_id->id
                            //         ]);
                        }

                        
                     $cat_id_array = array();
                     // dump($sub_cat_ids);
                     $category_data = BusinessCategory::find($sub_cat_ids);

                     foreach($category_data as $cat_data){

                        $cat_id_array[$cat_data->id] = ['shop_id' => $this->shop->id];  
                     }
                     
                     // dump($cat_id_array);
                     $product->categories()->syncWithoutDetaching($cat_id_array);
        
                       
                        ProductImage::where('product_id', $product->id)->delete();
                       
                        for($i=15;$i<=19;$i++)
                        {
                            if($row[$i] != null && !empty($row[$i]))
                            {
                                $productImage=['image'=>"products/".$shop_name."/".$row[$i]];
                                array_push($images,$productImage);
                            }
                        }
                        $productImage=[];
                        $prod_images = [];
                      
                        foreach ($images as $image) {
                          
                            $prod_images[] = new ProductImage($image);
                        }
                     
                        $product->productImages()->saveMany($prod_images);
                        
        
                        // if(!empty($images))
                        // {
                        //     foreach($images as $image)
                        //     {
                        //         if(!empty($image) && !is_null($image))
                        //         {
                                   
                        //             $url     = "products/".$image;
                        //             $array[] = [ , 'image' => $url];
                        //         }
                        //     }
        
                        //     ProductImage::insert($array);
                        // }
                        ++$this->rows;
                       
                        return $product;
        
                    }else{
                       
                        //store the product 
                        if($row[0]!=null)
                        {
        
                           $productUntracks = Product::where('sku',$row[0])->where('seller_id',$this->shop->id)->get();
                        
                           if($productUntracks->count()>0){
                              foreach($productUntracks as $productUntrack)
                              {
                                $productUntrack->categories()->detach();
                                ProductImage::where('product_id', $productUntrack->id)->delete();
                                $pro = Product::where('sku',$row[0])->where('seller_id',$this->shop->id)->first();
                                $pro->delete();
                              }
                           }
                           $untrack_prod = ProductUntracked::updateOrCreate(['sku' => $row[0],'seller_id' => $this->shop->id],
                        
                            [
                             
                                
                                'brand'           => $row[1],
                                'name'            => $row[2],
                                'vendor_price'    => $row[3], 
                                'price'           => $row[4],
                                'description'     => $row[5],
                                'stock'           => $row[6],
                                'thump_image'     => $row[8] ? str_replace(' ', '', $row[8]) : NULL,
                                'unit_measurement'=> $row[9],
                                'measurement_value'=> $row[10],
                                'product_type'     => $row[11],
                                'configurable_variations' => $row[12],
                                'additional_attributes' => $row[13],
                                'commission'        => $row[14],
                                'offer'         => $row[20]
                            ]);
        
                            ProductUntrackedImages::where('product_id', $untrack_prod->id)->delete();
        
                            $productImage=[];
                            $prod_images = [];
        
                            for($i=15;$i<=19;$i++)
                            {
                                if($row[$i] != null && !empty($row[$i]))
                                {
                                    $productImage=['image'=>$row[$i]];
                                    array_push($images,$productImage);
                                }
                            }
                            
                          
                            foreach ($images as $image) {
                              
                                $prod_images[] = new ProductUntrackedImages($image);
                            }
        
                            $untrack_prod->untrackImages()->saveMany($prod_images);
        
                            array_push($this->prod,$row[0]);
                            session()->put('row',array_unique($this->prod));
                            session()->put('error_msg',array_unique($this->err));
                        }
                    }
                        
                

        $count++;
    

}

public function getRowCount(): int
{
    return $this->rows;
}
    

}
