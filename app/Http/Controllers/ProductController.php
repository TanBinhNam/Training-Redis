<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatusEnum;
use App\Http\Requests\ProductAddRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; 

class ProductController extends Controller
{
    //
    public function index()
    {  
        return view('product',[
            'status' => ProductStatusEnum::toSelectArray(),
        ]);
    }

    public function show(Request $request)
    {
        $numberProductPerPage = 10;
        $query = Product::query();
      
        $filters= $this->filters($request);
        
        $query->searchProduct($filters);

        $count = $query->get()->count();

       
        $data = $query->orderBy('created_at', 'desc')->paginate($numberProductPerPage);
           
        return response()->json([
                'numberProductPerPage' => $numberProductPerPage,
                'count' =>$count,
                'data' =>$data->getCollection(),
                'paginate' =>(string) $data->links(),
                ], 200);
    }

    public function delete(Request $request)
    {      
        $delete = Product::where('product_id',$request->id)->first();

        if(is_null($delete)){
            return response()->json([
                'error' => "Không tồn tại"
                ], 400);
        }
        File::delete($delete->product_image);

        $delete->delete();

        return response()->json([
            'msg' => 'Xóa thành công sản phẩm '.$delete->product_name,
        ], 200);
    }

    public function addForm()
    {
        return view('productform',[
                        'title' => 'Thêm sản phẩm',
                    ]);
    }

    public function add(ProductAddRequest $request)
    {

        $product = new Product();
        $product->product_id = $this->handleProductID($request->product_name);
        $product->product_name = $request->product_name;
        $product->product_price = $request->product_price;
        $product->description  = $request->description;
        $product->is_sales = $request->is_sales;
        if(!is_null($request->file('product_image'))){
            $scrImage = $this->image($request->file('product_image'));
            $product->product_image = $scrImage;
        }
        $product->save();

        return redirect()->route('products.index')->with('msg', 'Thêm thành công '.$request->product_name);
    }


    public function editForm($id)
    {
        $data = Product::where('product_id', $id)->first();
        return view('productform',[
                        'title' => 'Sửa sản phẩm',
                        'product_id' => $id,
                        'data' => $data,
                    ]);
    }

    public function store(ProductUpdateRequest $request,$id)
    {
        if($id !== $request->product_id){
            return redirect()->back()->with('msg', 'Cập nhật thất bại');;
        }

        $update = Product::where('product_id', $id)->first();
    
        if(is_null($update)){
            return redirect()->back()->with('msg', 'Cập nhật thất bại');;
        }

        $scrImage = $update->product_image;

        if(!is_null($request->file('product_image'))){
            File::delete($scrImage);
            $scrImage = $this->image($request->file('product_image'));
        }
        
        $update->product_name = $request->product_name;
        $update->product_price = $request->product_price;
        $update->description = $request->description;
        $update->is_sales = $request->is_sales;
        $update->product_image = $scrImage;

        $update->save();
 
        return redirect()->route('products.index')->with('msg', 'Cập nhật thành công '.$request->product_name);
    }
    
    private function filters(Request $request){
        $filters = [];

        if($request->has('product_name') && !is_null($request->product_name))
        {
            $filters['product_name'] = $request->product_name;
        } 

        if($request->has('status') && !is_null($request->status))
        {
            $filters['status'] = $request->status;
        } 

        if($request->has('price_from') && $request->price_from !== '0')
        {
            $filters['price_from'] = $request->price_from;
        } 

        if($request->has('price_to') && $request->price_to !== '0')
        {
            $filters['price_to'] = $request->price_to;
        } 
        return $filters;
    }

    public function image($value){
        $file = $value;
        $name = $file->getClientOriginalName();
        $head_name  = current(explode('.', $name));
        $new_name  =  $head_name.Str::random(5).'.'.$file->getClientOriginalExtension();
   
        $value->move('upload/product',  $new_name);

        return 'upload/product'.'/'.$new_name;
    }

    private function handleProductID($product_name){
        $firstCharacter = substr(ucfirst($this->convert_character($product_name)),0, 1);
        $string = '000000000';
        $query = Product::whereRaw('left(product_id, 1) = ?', $firstCharacter);
        $number = $query->count('product_id');
        
        if($number !== 0){
            $last_id = $query->latest()->first('product_id');
            $number = (int)substr($last_id->product_id,1);
        }
        
        $length = strlen($string) - strlen($number);
        return $firstCharacter.''.substr_replace("000000000",$number + 1, $length);
    }

    private function convert_character($str) {
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		$str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
		$str = preg_replace("/( )/", '-', $str);
		return $str;
	}
}
