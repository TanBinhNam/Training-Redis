<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserAddRequest extends FormRequest
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
        $rulePassword = Password::min(5)->numbers()->mixedCase();
        return [
            'add_name' => ['required', 'min:5', 'not_regex:/[\'^£$%&*()}{@#~?><>,|=_+¬-]/'],
            'add_email' => ['required', 'email', 'unique:mst_users,email'],
            'add_password' => ['required' , $rulePassword, 'same:add_password_confirmation'],
            'add_group_role' => [Rule::in(['Admin', 'Reviewer', 'Editor'])],
        ];
    }

    public function messages()
    {
        return [
            'add_name.required' => 'Tên không được trống',
            'add_name.min' => 'Tên phải hơn 5 ký tự',
            'add_name.not_regex' => 'Tên không hợp lệ',

            'add_email.email' => 'Email không đúng định dạng',
            'add_email.unique' => 'Email đã được đăng ký',
            'add_email.required' => 'Email không được trống',

            'add_password.required' => 'Mật khẩu không được trống',
            'add_password.min' => 'Mật khẩu phải hơn 5 ký tự',
            'add_password.same' => 'Mật khẩu và xác nhận mật khẩu không chính xác.',
            'add_password.min.mixedCase' => 'Mật khẩu không bảo mật.',
            

            'add_group_role.in' => 'Nhóm không hợp lệ',
        ];
    }
}
