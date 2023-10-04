<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Modules\Users\Entities\User;

class CustomerUpdateRequest extends FormRequest
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
        $user = User::where('id',$request->id)->first();

        return [
            'name'            => 'required',
            'email'           => ['required','email',Rule::unique('users','email')->ignore($user)],
            'profile_image'   => 'mimes:jpeg,png,jpg,gif|max:2048',
            'mobile'          => ['required','digits:10',Rule::unique('users','mobile')->ignore($user)],
            'status'          => 'required',
            
       ];
    }
}
