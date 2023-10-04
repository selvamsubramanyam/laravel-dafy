<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubAdminStoreRequest extends FormRequest
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
           
            'email'           => 'required|email|unique:admin_users,email',
          
            'profile_image'   => 'mimes:jpeg,png,jpg,gif|max:2048',
          
            'mobile'          => 'required|digits:10 ',
        
            'password'        => 'required|confirmed|min:4',

            'status'          => 'required',

            'role_id'         => 'required|integer'
           
       ];
    }
}
