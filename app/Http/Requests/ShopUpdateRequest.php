<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Modules\Shop\Entities\BusinessShop;

class ShopUpdateRequest extends FormRequest
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

        $shop = BusinessShop::where('id',$request->id)->first();

        if($request->has('password') && $request->password != '')
        {
            $rules = [
                'email' => ['required','min:4','email',Rule::unique('business_shops','email')->ignore($shop)],
                'password'  => 'required|confirmed|min:4'
            ];
        }else{

            $rules = [
                'email' => ['required','min:4','email',Rule::unique('business_shops','email')->ignore($shop)],
            ];

        }

        return $rules;
    }
}
