<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
            'import_excel' => ['required', 'mimes:xlsx', 'file'],

        ];
    }

    public function messages()
    {
        return [
            'import_excel.required' => 'Chọn file',
            'import_excel.mimes' => 'File không hợp lệ',
            'import_excel.file' => 'Không hợp lệ',
        ];
    }
}
