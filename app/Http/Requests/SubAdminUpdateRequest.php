<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Admin\Entities\AdminUser;
use Illuminate\Http\Request;

class SubAdminUpdateRequest extends FormRequest
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
        $admin_user = AdminUser::where('id',$request->id)->first();

        if($request->has('password') && $request->password != '')
        {
            $rules = [
                'name'            => 'required',
                'email' => ['required','min:4','email',Rule::unique('admin_users','email')->ignore($admin_user)],
                'password'  => 'required|confirmed|min:4',
                'profile_image'   => 'mimes:jpeg,png,jpg,gif|max:2048',
                'mobile'          => 'required|digits:10 ',
                'status'          => 'required'
            ];
        }else{

            $rules = [
                'name'            => 'required',
                'email' => ['required','min:4','email',Rule::unique('admin_users','email')->ignore($admin_user)],
                'profile_image'   => 'mimes:jpeg,png,jpg,gif|max:2048',
                'mobile'          => 'required|digits:10 ',
                'status'          => 'required'
            ];

        }

        return $rules;
    }
}
