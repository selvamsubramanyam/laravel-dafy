<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Modules\Admin\Entities\Offer;

class OfferUpdateRequest extends FormRequest
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

        $offer = Offer::where('id',$request->id)->first();
        
        return [

            'shops' =>  'required',
            'title' =>  ['required','min:3',Rule::unique('offers','title')->ignore($offer)],
            'pic'   =>  'mimes:jpeg,png,jpg,gif|max:2048',
            'discount_type' => 'required',
            'discount_value' => ['regex:/^\d+(\.\d{1,2})?$/','required'],
            'validfrom' => 'required|date',
            'validto'   => 'required|date|after_or_equal:validfrom',
            'status'    => 'required'
        ];
    }
}
