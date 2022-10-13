<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
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
        $id = $this->request->get('edit_id');
      
        $rulePassword = Password::min(5)->numbers()->mixedCase();
   
        $uniqueEmail = Rule::unique('mst_users', 'email')->ignore($id);

        return [
            'edit_name' => ['required', 'min:5'],
            'edit_email' => ['required', 'email', $uniqueEmail],
            'edit_password' => ['required' , $rulePassword, 'same:edit_password_confirmation'],
            'edit_group_role' => [Rule::in(['Admin', 'Reviewer', 'Editor'])],
        ];
    }

    public function messages()
    {
        return [
            'edit_name.required' => 'Tên không được trống',
            'edit_name.min' => 'Tên phải hơn 5 ký tự',

            'edit_email.email' => 'Email không đúng định dạng',
            'edit_email.unique' => 'Email đã được đăng ký',
            'edit_email.required' => 'Email không được trống',

            'edit_password.required' => 'Mật khẩu không được trống',
            'edit_password.min' => 'Mật khẩu phải hơn 5 ký tự',
            'edit_password.same' => 'Mật khẩu và xác nhận mật khẩu không chính xác.',
            'edit_password.min.mixedCase' => 'Mật khẩu không bảo mật.',
            
            'edit_group_role.in' => 'Nhóm không hợp lệ',
        ];
    }
}
