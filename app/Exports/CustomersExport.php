<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $filters;
    protected $numberCustomerPerPage;

    function __construct($filters = array(), $numberCustomerPerPage) {
            $this->filters = $filters;
            $this->numberCustomerPerPage = $numberCustomerPerPage;
     }
    
 
    public function headings(): array
    {
        return [
            [
                'Tên khách hàng',
                'Email',
                'TelNum',
                'Địa chỉ',
            ],

        ];
    }

    public function collection()
    {
        if(empty($this->filters)){
            return Customer::query()
            ->select('customer_name','email','tel_num','address')
            ->orderBy('created_at', 'desc')
            ->limit($this->numberCustomerPerPage)
            ->get();
        }

        return Customer::query()
        ->select('customer_name','email','tel_num','address')
        ->searchCustomer($this->filters)       
        ->get();
    }
}
