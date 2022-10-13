<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddRequest extends FormRequest
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
            'add_name' => ['required', 'min:5'],
            'add_email' => ['required', 'email', 'unique:mst_customers,email'],
            'add_tel_num' => ['required', 'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'],
            'add_address' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'add_name.required' => 'Tên không được trống',
            'add_name.min' => 'Tên phải hơn 5 ký tự',

            'add_email.email' => 'Email không đúng định dạng',
            'add_email.unique' => 'Email đã được đăng ký',
            'add_email.required' => 'Email không được trống',

            'add_tel_num.required' => 'Điện thoại không được để trống',
            'add_tel_num.regex' => 'Nhập không đúng định dạng điện thoại',
       
            'add_address.required' => 'Địa chỉ không được để trống',
        ];
    }
}
