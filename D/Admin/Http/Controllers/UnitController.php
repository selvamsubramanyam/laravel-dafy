<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Unit;
use Illuminate\Routing\Controller;

class UnitController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:unit-list', ['only' => ['unit','unitList']]);
        $this->middleware('permission:unit-create', ['only' => ['unitAdd','unitStore']]);
        $this->middleware('permission:unit-edit', ['only' => ['unitEdit','unitUpdate']]);
        $this->middleware('permission:unit-delete', ['only' => ['deleteUnit']]);
    }
    
    public function unit()
    {
        return view('admin::Unit.unit');
    }
   

    public function unitList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $unit = Unit::orderBy('id', 'desc');

        if ($search != '') 
        {
            $unit->where('name', 'LIKE', '%'.$search.'%');
        }

        $total = $unit->count();
        
        $result['data'] = $unit->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }
  

    public function unitAdd()
    {
        return view('admin::Unit.addUnit');
    }
   

    public function unitStore(Request $request)
    {

        $request->validate([
            'name'=>'required|unique:measurement_units',
           
         ]);

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        Unit::create([
                'name' => $request->name,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'Unit Added Successfully.');
    }
    


    public function unitEdit($id)
    {
        $unit = Unit::where(['id' => $id])->first();
       
        return view('admin::Unit.editUnit', compact('unit'));
    }
  

    public function unitUpdate(Request $request)
    {
        $unit = Unit::where('id',$request->id)->first();
        
        $request->validate([
            'name'=>'required|unique:measurement_units,name,'.$unit->id,
           
         ]);
        
         if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;


        $unit->update([
            'name' => $request->name,
            'is_active' => $is_active
            ]);
        
        return redirect()->back()->with('message', 'Unit Updated Successfully.');
    }


    public function deleteUnit($id)
    {
         Unit::where(['id' => $id])->delete();
        
        return redirect()->back()->with('message', 'Unit Deleted Successfully.');
    }
}
