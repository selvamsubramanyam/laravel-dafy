<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerStoreRequest extends FormRequest
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
             'business_name'   => 'required',
             'email'           => 'required|email',
             'business_email'  => 'required|email|unique:users,business_email',
             'profile_image'   => 'mimes:jpeg,png,jpg,gif|max:2048',
             'business_image'  => 'mimes:jpeg,png,jpg,gif|max:2048',
             'mobile'          => 'required|digits:10|unique:users,mobile',
             'categories'      => 'required',
             'latitude'        => 'required',
             'longitude'       => 'required',
             'street'          => 'required',
             'area'            => 'required',
             'city'            => 'required',
             'pincode'         => 'required|numeric',
             'state_id'        => 'required',
             'status'          => 'required'
        ];
    }
}
