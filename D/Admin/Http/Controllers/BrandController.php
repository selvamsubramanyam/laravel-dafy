<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Brand;

class BrandController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:brand-list', ['only' => ['brand','brandList']]);
        $this->middleware('permission:brand-create', ['only' => ['brandAdd','brandStore']]);
        $this->middleware('permission:brand-edit', ['only' => ['brandEdit','brandUpdate']]);
        $this->middleware('permission:brand-delete', ['only' => ['deleteBrand']]);
    }
   
    public function brand()
    {
        return view('admin::Brand.brand');
    }
   

    public function brandList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $brand = Brand::orderBy('id', 'desc');

        if ($search != '') 
        {
            $brand->where('name', 'LIKE', '%'.$search.'%');
        }

        $total = $brand->count();
        
        $result['data'] = $brand->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }
  

    public function brandAdd()
    {
        return view('admin::Brand.addBrand');
    }
   

    public function brandStore(Request $request)
    {

        $request->validate([
            'name'=>'required|unique:brands',
            'logo'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
         ]);

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        if($request->file('logo'))
            $image = $request->file('logo')->store('brands');
        else
            $image = null;

        Brand::create([
                'name' => $request->name,
                'slug' => str_slug($request->name),
                'logo' => $image,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'Brand Added Successfully.');
    }
    


    public function brandEdit($id)
    {
        $brand = Brand::where(['id' => $id])->first();
       
        return view('admin::Brand.editBrand', compact('brand'));
    }
  

    public function brandUpdate(Request $request)
    {
        $brand = Brand::where('id',$request->id)->first();
        
        $request->validate([
            'name'=>'required|unique:brands,name,'.$brand->id,
            'logo'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
         ]);
        
         if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        if($request->hasFile('logo')) {
            $image = $request->file('logo')->store('brands');

            $brand->update(['logo' => $image]);
        }

        $brand->update([
            'name' => $request->name,
            'slug' => str_slug($request->name),
            'is_active' => $is_active
            ]);
        
        return redirect()->back()->with('message', 'Brand Updated Successfully.');
    }


    public function deleteBrand($id)
    {
         Brand::where(['id' => $id])->delete();
        
        return redirect()->back()->with('message', 'Brand Deleted Successfully.');
    }
}
