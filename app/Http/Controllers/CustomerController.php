<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Http\Requests\CustomerAddRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Requests\ImportRequest;
use App\Imports\CustomersImport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;

class CustomerController extends Controller
{   
    const NUMBER_CUSTOMER_PER_PAGE = 10;

    public function index()
    {
        return view('customer');
    }

    public function show(Request $request)
    {
        
        $query = Customer::query();

        $filters= $this->filters($request);
        
        $query->searchCustomer($filters);

            $count = $query->get()->count();
            $data = $query->orderBy('created_at', 'desc')->paginate(self::NUMBER_CUSTOMER_PER_PAGE);
  
            return response()->json([
                'numberCustomerPerPage' => self::NUMBER_CUSTOMER_PER_PAGE,
                'count' =>$count,
                'data' =>$data->getCollection(),
                'paginate' =>(string) $data->links(),
                //'paginate' =>$data->linkCollection(),
                ], 200);
    }

    public function add(CustomerAddRequest $request)
    {
        $customer = new Customer();
        $customer->customer_name = $request->add_name;
        $customer->email = $request->add_email;
        $customer->tel_num = $request->add_tel_num;
        $customer->address = $request->add_address;
        $customer->is_active = 0;
        if($request->add_status === 'on')
        {
            $customer->is_active = 1;
        }
        $customer->save();

        return response()->json([
            'msg' => 'Thêm thành công khách hàng '.$request->add_name,
        ], 200);

    }

    public function store(CustomerUpdateRequest $request)
    {
        $customer = Customer::where('customer_id',$request->id)->first();
        if(is_null($customer)){
            return response()->json([
                'error' => "Không tồn tại"
                ], 400);
        }
        $customer->customer_name = $request->name;
        $customer->email = $request->email;
        $customer->tel_num = $request->tel_num;
        $customer->address = $request->address;
     
        $customer->save();
         return response()->json([
            'msg' => 'Cập nhật thành công thành viên '.$request->name,
        ], 200);
    }

    public function import(ImportRequest $request){
         
            $file = $request->file('import_excel');
            //$import = Excel::import(new CustomersImport, $file);
   
            $import = new CustomersImport();
            $import->import($file);
            $error_row = [];
            foreach ($import->failures() as $failure) {
                $error_row[$failure->row()] =  $failure->errors(); 
            }
            ksort($error_row);
       
            return response()->json([
                'error_row' => $error_row,
            ], 200); 
    }

    public function export(Request $request){
            
        $filters= $this->filters($request);

        return Excel::download(new CustomersExport($filters ,self::NUMBER_CUSTOMER_PER_PAGE), 'Export-'.Str::random(2).'.xlsx');

        return response()->json([
            'msg' => 'Export thành công',
        ], 200);
}

    private function filters(Request $request){
        $filters = [];

        if($request->has('name') && !is_null($request->name))
        {
            $filters['name'] = $request->name;
        } 

        if($request->has('status') && !is_null($request->status))
        {
            $filters['status'] = $request->status;
        } 

        if($request->has('email') && !is_null($request->email))
        {
            $filters['email'] = $request->email;
        } 

        if($request->has('address') && !is_null($request->address))
        {
            $filters['address'] = $request->address;
        } 
        return $filters;
    }

}
