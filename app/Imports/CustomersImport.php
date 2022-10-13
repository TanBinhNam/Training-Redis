<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class CustomersImport implements ToArray, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function array(array $array)
    {
        foreach ($array as $array) {
            Customer::create([
                'customer_name' => $array['ten_khach_hang'],
                'email' => $array['email'],
                'tel_num' => $array['telnum'],
                'address' => $array['dia_chi'],
                'is_active' => 1, 
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'ten_khach_hang' => ['required', 'min:5', 'not_regex:/[\'^£$%&*()}{@#~?><>,|=_+¬-]/'],

             'email' => ['required', 'email', 'unique:mst_customers,email'],

             'telnum' => ['required', 'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'],
             
             'dia_chi' =>  ['required'],
        ];
    }

    public function customValidationMessages()
    {
        return [

            'ten_khach_hang.required' => 'Tên không được trống',
            'ten_khach_hang.min' => 'Tên phải hơn 5 ký tự',
            'ten_khach_hang.not_regex' => 'Tên không hợp lệ',

            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được đăng ký',
            'email.required' => 'Email không được trống',

            'telnum.required' => 'Điện thoại không được để trống',
            'telnum.regex' => 'Nhập không đúng định dạng điện thoại',
       
            'dia_chi.required' => 'Địa chỉ không được để trống',
        ];
    }
}
