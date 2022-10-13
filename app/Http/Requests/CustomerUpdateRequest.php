<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
    public function rules()
    {   
        $id = $this->request->get('id');
        $uniqueEmail = Rule::unique('mst_customers', 'email')->ignore($id, 'customer_id');
        return [
            'name' => ['required', 'min:5'],
            'email' => ['required', 'email', $uniqueEmail],
            'tel_num' => ['required', 'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'],
            'address' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được trống',
            'name.min' => 'Tên phải hơn 5 ký tự',

            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được đăng ký',
            'email.required' => 'Email không được trống',

            'tel_num.required' => 'Điện thoại không được để trống',
            'tel_num.regex' => 'Nhập không đúng định dạng điện thoại',
       
            'address.required' => 'Địa chỉ không được để trống',
        ];
    }
}
