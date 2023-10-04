<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Modules\Product\Entities\Product;

class ProductEditRequest extends FormRequest
{


    protected $product_type, $rules= [] ;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       // $id = $this->route('id');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {

        $product=Product::where('id',$request->id)->first();
     
        if($product->parent_id != 0)
        {
            
            $rules = [
                'name'              =>  'required',
                // 'sku'               =>  'required|min:3|unique:products,sku,'.$request->id.'NULL,id,deleted_at,NULL',
                'sku'               =>  'nullable|min:3',
                'thump_image'       =>  'mimes:jpeg,png,jpg,gif|max:2048',
                'price'             =>  ['required','regex:/^\d+(\.\d{1,2})?$/','required_if:product_type,2'],
                'status'            =>  'required',
                'stock'             =>  'required',
                'categories'        =>  'required',
                'images.*'          =>  'mimes:jpeg,png,jpg,gif|max:2048',
                // 'vendor_price'      =>  'required|regex:/^\d+(\.\d{1,2})?$/',
                'offer'             =>  'nullable|numeric'
            ];
        }else{

            $rules = [
                'name'              =>  'required',
                // 'sku'               =>  'required|min:3|unique:products,sku,'.$request->id.'NULL,id,deleted_at,NULL',
                'sku'               =>  'nullable|min:3',
                'thump_image'       =>  'mimes:jpeg,png,jpg,gif|max:2048',
                'status'            =>  'required',
                'categories'        =>  'required',
                'images.*'          =>  'mimes:jpeg,png,jpg,gif|max:2048',
                'offer'             =>  'nullable|numeric'
            ];

        }

        return $rules;
    }
}
