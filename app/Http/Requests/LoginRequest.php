<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'email', "not_regex:/=|!|<|>|'|\*|\"/i"],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'Email sai định dạng',
            'email.required' => 'Email không được trống',
            'email.not_regex' => 'Email không hợp lệ',

            'password.required' => 'Mật khẩu không được trống',

        ];
    }
}
