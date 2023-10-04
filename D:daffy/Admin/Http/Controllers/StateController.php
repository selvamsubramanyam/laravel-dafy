<?php

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\AdminUser;
use Modules\Admin\Entities\Settings;
use Modules\Admin\Entities\City;
use Modules\Admin\Entities\State;
use Modules\Admin\Entities\ShopCountry;
use Modules\Users\Entities\UserRole;
use Modules\Category\Entities\Banner;
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


class StateController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:state-list', ['only' => ['state','stateList']]);
        $this->middleware('permission:state-create', ['only' => ['stateAdd','stateStore']]);
        $this->middleware('permission:state-edit', ['only' => ['stateEdit','stateUpdate']]);
        $this->middleware('permission:state-delete', ['only' => ['deleteState']]);
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function state()
    {
        return view('admin::State.state');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function stateList(Request $request)
    {

        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $state = State::orderBy('id', 'asc');

        if ($search != '') 
        {
            $state->where('name', 'LIKE', '%'.$search.'%');
        }

        $total = $state->count();
        
        $result['data'] = $state->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

   /**
     * Display a listing of the resource.
     * @return Response
     */
    public function stateAdd()
    {
        return view('admin::State.addState');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function stateStore(Request $request)
    {

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        // $slug = Str::slug($request->name);
        // if($request->file('pic'))
        //     $image = $request->file('pic')->store('cities');
        // else
        //     $image = null;

        $country = ShopCountry::where(['slug' => 'india'])->first();
        
        State::create([
                'name' => $request->name,
                'country_id' => $country->id,
                // 'image' => $image,
                // 'latitude' => $request->latitude,
                // 'longitude' => $request->longitude,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'State Added Successfully.');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function stateEdit($id)
    {
        $state = State::where(['id' => $id])->first();
        // dd($categories);
        return view('admin::State.editState', compact('state'));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function stateUpdate(Request $request)
    {
        $state = State::where(['id' => $request->id])->first();

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        // $slug = Str::slug($request->name);        
        // if($request->hasFile('pic')) {
        //     $image = $request->file('pic')->store('cities');

        //     $city->update(['image' => $image]);
        // }
        
        $country = ShopCountry::where(['slug' => 'india'])->first();

        $state->update([
                'name' => $request->name,
                'country_id' => $country->id,
                // 'latitude' => $request->latitude,
                // 'longitude' => $request->longitude,
                'is_active' => $is_active
            ]);
        
        return redirect()->back()->with('message', 'State Updated Successfully.');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    // public function deleteState($id)
    // {
    //     $state = State::where(['id' => $id])->delete();
        
    //     return redirect()->back()->with('message', 'State Deleted Successfully.');
    // }
}
