<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverStoreRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'            => 'required',
           
            'email'           => 'required|email|unique:driver_users,email',
          
            'profile_image'   => 'mimes:jpeg,png,jpg,gif|max:2048',
          
            'mobile'          => 'required|digits:10|unique:driver_users,mobile',
        
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
