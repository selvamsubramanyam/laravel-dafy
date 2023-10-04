<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Users\Entities\Role;
use Modules\Users\Entities\Permission;
use Session;
use Illuminate\Support\Str;

class RoleController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:role-list', ['only' => ['role','roleList']]);
        $this->middleware('permission:role-create', ['only' => ['roleAdd','roleStore']]);
        $this->middleware('permission:role-edit', ['only' => ['roleEdit','roleUpdate']]);
        $this->middleware('permission:role-delete', ['only' => ['deleterole']]);
    }
 
    public function role()
    {
        return view('admin::Role.role');
    }

  
    public function roleList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $role = Role::orderBy('id', 'asc');
        
        if ($search != '') 
        {
            $role->where('name', 'LIKE', '%'.$search.'%');
        }

        $total = $role->count();
        
        $result['data'] = $role->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function roleAdd()
    {
        $permissions = Permission::all();

        return view('admin::Role.addRole',compact('permissions'));
    }
    

    public function roleStore(Request $request)
    {
       
        $permissions = [];
        $perms = [];
        $slug = Str::slug($request->name);

        $role = Role::where('slug', '=',$slug)->first();
        if ($role) {
            return redirect()->back()->with('danger', 'Role already exists');
        }

        $dash = Permission::where('slug', '=','dashboard')->first();
       
        $perms = $request->perms;

        if($perms != null)
        {
            foreach($perms as $perm)
            {
               array_push($permissions,$perm);
            }
           
            array_push($permissions,(string)$dash->id);
        }else{
            array_push($permissions,(string)$dash->id);  //default dashboard perm
        }
        
       
     
       $role = Role::updateOrCreate(['slug'=>$slug],
         [
            'name' => $request->name,
            'description' => $request->description
        ]);
      

        if(count($permissions) > 0)
            $role->permissions()->sync($permissions);

        Session::flash('message', 'Entry added successfully' ); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect()->back();

    }

    public function roleEdit($id)
    {
        $permissions = Permission::all();
        $role = Role::find($id);
        $permission = $role->permissions()->pluck('permission_id')->toArray();
        return view('admin::Role.editRole',compact('permissions','role','permission'));
    }

    public function roleUpdate(Request $request)
    {

        $permissions = [];
        $perms = [];
        // if(!isset($request->perms) || count($request->perms) == 0)
        // {
        //     return redirect()->back()->with('message', 'Select permissions');
        // }
        
        $role = Role::find($request->id);
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();

        $dash = Permission::where('slug', '=','dashboard')->first();
        $perms = $request->perms;

        if($perms != null)
        {
            foreach($perms as $perm)
            {
               array_push($permissions,$perm);
            }
           
            array_push($permissions,(string)$dash->id);
        }else{
            array_push($permissions,(string)$dash->id);  //default dashboard perm
        }

        if(count($permissions) > 0)
            $role->permissions()->sync($permissions);

        Session::flash('message', 'Entry updated successfully' ); 
        Session::flash('alert-class', 'alert-success'); 

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function deleterRole($id)
    {
        //
    }
}
