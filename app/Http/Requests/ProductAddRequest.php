<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductAddRequest extends FormRequest
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
            'product_name' => ['required', 'min:5'],
            'product_price' => ['required', 'min:0', 'max:99999', 'numeric'],
            'description' => ['required'],
            'is_sales' => ['required', Rule::in(['0', '1', '2'])],
            'product_image' => ['required', 'mimes:jpg,png,jpeg', 'max:2048', 'dimensions:max_width=1024,max__height=1024'],
            
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Tên sản phẩm không được trống',
            'product_name.min' => 'Tên sản phẩm phải hơn 5 ký tự',

            'product_price.numeric' => 'Giá bán chỉ được nhập số',
            'product_price.min' => 'Giá bán không được nhỏ hơn 0',
            'product_price.max' => 'Giá bán không được lớn hơn 99999',
            'product_price.required' => 'Giá bán không được trống',

            'description.required' => 'Nhập mô tả',
       
            'is_sales.required' => 'Trạng thái không được để trống',
            'is_sales.in' => 'Trạng thái không hợp lệ',

            'product_image.required' => 'Chọn ảnh',
           
            'product_image.mimes' => 'Chỉ cho upload các file hình png, jpg, jpeg',
            'product_image.max' => 'Dung lượng hình không quá 2Mb',
            'product_image.dimensions' => 'Kích thước không quá 1024px',
        ];
    }
}
