<?php

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Admin\Entities\AdminUser;
use Modules\Admin\Entities\Settings;
use Modules\Admin\Entities\City;
use Modules\Admin\Entities\State;
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


class CityController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:city-list', ['only' => ['city','cityList']]);
        $this->middleware('permission:city-create', ['only' => ['cityAdd','cityStore']]);
        $this->middleware('permission:city-edit', ['only' => ['cityEdit','cityUpdate']]);
        $this->middleware('permission:city-delete', ['only' => ['deleteCity']]);
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function city()
    {
        return view('admin::City.city');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function cityList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $city = City::with('getState')->orderBy('id', 'asc');
        
        if ($search != '') 
        {
            $city->where('name', 'LIKE', '%'.$search.'%');
        }

        $total = $city->count();
        
        $result['data'] = $city->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

   /**
     * Display a listing of the resource.
     * @return Response
     */
    public function cityAdd()
    {
        $states = State::where(['is_active' => 1])->get();

        return view('admin::City.addCity', compact('states'));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function cityStore(Request $request)
    {

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        if($request->file('pic'))
            $image = $request->file('pic')->store('cities');
        else
            $image = null;

        City::create([
                'name' => $request->name,
                'state_id' => $request->state,
                'image' => $image,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_active' => $is_active
            ]);

        return redirect()->back()->with('message', 'City Added Successfully.');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function cityEdit($id)
    {
        $city = City::where(['id' => $id])->first();
        $states = State::where(['is_active' => 1])->get();

        return view('admin::City.editCity', compact('city', 'states'));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function cityUpdate(Request $request)
    {
        $city = City::where(['id' => $request->id])->first();

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;

        if($request->hasFile('pic')) {
            $image = $request->file('pic')->store('cities');

            $city->update(['image' => $image]);
        }

        $city->update([
                'name' => $request->name,
                'state_id' => $request->state,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_active' => $is_active
            ]);
        
        return redirect()->back()->with('message', 'City Updated Successfully.');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function deleteCity($id)
    {
        $city = City::where(['id' => $id])->delete();
        
        return redirect()->back()->with('message', 'City Deleted Successfully.');
    }
}
