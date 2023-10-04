<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\DeliveryApp\Entities\DriverUser;
use Illuminate\Http\Request;

class DriverUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {

        $driver_user = DriverUser::where('id',$request->id)->first();

        return [
            'name'            => 'required',
           
            'email'           => ['required','min:4','email',Rule::unique('driver_users','email')->ignore($driver_user)],
            
            'profile_image'   => 'mimes:jpeg,png,jpg,gif|max:2048',
          
            'mobile'          => ['required','digits:10',Rule::unique('driver_users','mobile')->ignore($driver_user)],
        
            'alt_mobile'      => 'required',

            'location'        => 'required',

            'area'            => 'required',

            'build_name'      => 'required',

            'latitude'        =>  'required|numeric',

            'longitude'       =>  'required|numeric',


            'city'            =>  'required',

            'status'          => 'required'
        ];
    }
}
