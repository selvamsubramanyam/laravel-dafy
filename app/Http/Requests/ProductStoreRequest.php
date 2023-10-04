<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductStoreRequest extends FormRequest
{

    protected $product_type, $rules= [] ;
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
        if($request->product_type == 2 || $request->product_type == 0)
        {
            $rules = [
                'name'              =>  'required',
                // 'sku'               =>  'required|min:3|unique:products,sku',
                'sku'               =>  'nullable|min:3',
                'thump_image'       =>  'required|mimes:jpeg,png,jpg,gif|max:2048',
                'price'             =>  ['regex:/^\d+(\.\d{1,2})?$/','required_if:product_type,0','required_if:product_type,2'],
                'status'            =>  'required',
                'stock'             =>  ['required_if:product_type,0','required_if:product_type,2'],
                'categories'        =>  'required',
                'images.*'          =>  'mimes:jpeg,png,jpg,gif|max:2048',
                // 'vendor_price'      =>  ['regex:/^\d+(\.\d{1,2})?$/','required_if:product_type,0','required_if:product_type,2'],
                'product_type'      =>  'required',
                'parent_product'    =>  ['required_if:product_type,2'],
                'offer'             =>  'nullable|numeric'
            ];
        }else{
            $rules = [
                'name'              =>  'required',
                // 'sku'               =>  'required|min:3|unique:products,sku',
                'sku'               =>  'nullable|min:3',
                'thump_image'       =>  'required|mimes:jpeg,png,jpg,gif|max:2048',
                'status'            =>  'required',
                'categories'        =>  'required',
                'images.*'          =>  'mimes:jpeg,png,jpg,gif|max:2048',
                'product_type'      =>  'required',
                'offer'             =>  'nullable|numeric'
            ];

        }

        return $rules;


    }

    public function messages()
    {
        return[
            'parent_product.required_if' => 'Please select the parent product.'
        ];
    }
}
