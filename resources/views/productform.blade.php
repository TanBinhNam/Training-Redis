@extends('layout')
@push('css')    
<style>
    .title{
        font-weight: bold;
        
    }

    img {
    height: 300px;
    width: 300px;

  }
  .btn{
    color:white !important;
    font:bold;
    padding-left:30px !important;
    padding-right:30px !important;
  }
  .btn-send-form{
    background-color: orangered;
    color:white;
   
  }
  .btn-file{
    margin-left:-5px;
  }
  .ck-editor__editable {min-height: 300px;}


</style>
@endpush

@section('content')
    <div >
        @include('layout.menu')
        <div class='row'>
          <div class='col-md-8' style="float:left">
            <p class="title ml-2 mb-0">Chi tiết sản phẩm</p>
          </div>
          <div class='col-md-3 text-right'>
            <a class=" ml-2 mb-0" href="{{route('products.index')}}">Sản phẩm</a>  > {{$title ?? ''}}
          </div>
        </div>
        
        
     
        <hr class='mt-0' style="height: 10px" color="#99d6ff">
    
        <div class='container-fluid pl-5 pr-5'>
            <form method="POST" enctype="multipart/form-data" class="form-horizontal" id="product_form">
              @csrf
              <input hidden name="product_id" id="product_id" value={{ $product_id ?? ''}}>
                <div class='col-md-6 col-lg-7' style="float:left">
                    <div class="form-group row">
                        <label for="product_name" class="col-md-4 col-form-label col-lg-2">Tên sản phẩm</label>
                        <div class="col-md-8 col-lg-9">
                          <input type="text" class="form-control" name="product_name" id="product_name" value="{{ $data->product_name ?? old('product_name')}}" 
                          placeholder="Nhập tên sản phẩm" required="">
              
                          @error('product_name')
                          
                            <div style="color:red">{{ $message }}</div>
                          
                          @enderror
                          
              
                        </div>
                      </div>
    
                      <div class="form-group row">
                        <label for="product_price" class="col-md-4 col-form-label  col-lg-2">Giá bán</label>
                        <div class="col-md-8  col-lg-9">
                          <input type="number" class="form-control" min="0" name="product_price" id="product_price" value="{{ $data->product_price ?? old('product_price')}}" 
                          placeholder="Nhập giá bán" required="">
                      
                          @error('product_price')
                          
                            <div style="color:red">{{ $message }}</div>
                          
                          @enderror
              
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="description" class="col-md-4 col-form-label col-lg-2">Mô tả</label>
                        <div class="col-md-8 col-lg-9">
                          <textarea class="form-control pt-5" name="description" id="editor" 
                          placeholder="Mô tả sản phẩm" style="text-align:left;" rows="5" cols="50" >{{ $data->description ?? old('description')}}</textarea>
              
                          @error('description')
                          
                            <div style="color:red">{{ $message }}</div>
                         
                          @enderror
              
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="status" class="col-sm-4 col-form-label col-lg-2">Trạng thái</label> 
                        <div class="col-sm-8 col-lg-9">
                        <select class="form-control" id="is_sales" name="is_sales" required="">
                            <option selected hidden value='{{ $data->is_sales ?? old('is_sales')}}' >{{ product_status($data->is_sales ?? '') }} </option>            
                            <option value="0" {{($data->is_sales ?? old('is_sales')) === '0' ? 'selected' : ''}}>Dừng bán</option>
                            <option value="1" {{($data->is_sales ?? old('is_sales')) === '1' ? 'selected' : ''}}>Đang bán</option>
                            <option value="2" {{($data->is_sales ?? old('is_sales')) === '2' ? 'selected' : ''}}>Hết hàng</option>
                        </select>
                        
                          @error('is_sales')
                          
                            <div style="color:red">{{ $message }}</div>
                         
                          @enderror

                        </div>
                      </div>

                </div>
    
                <div class='col-md-6 col-lg-5' style="float:left">
                  <div class="form-group row">
                    <div class="form-group">
                      <label for="product_image" >Hình ảnh</label>
                      <div>
                        <img class="ml-5" src="{{ asset($data->product_image ?? 'upload/product/default-thumbnail.jpg') }}" id="f"/>
                      </div>
                      </div>
                      <div class="col-md-12 pl-0  col-lg-12">
                          <a class='btn btn-primary btn-upload p-1'>Upload</a>
                          <a class='btn btn-danger btn-delete-file p-1 btn-file'>Xóa file</a>
                          <input type="text" id="file_name" placeholder="tên file upload" class="btn-file" readonly>
                      </div>
                      @error('product_image')
                          
                      <div style="color:red">{{ $message }}</div>
                   
                      @enderror

                      <input type="file" class="form-control-file" id="product_image" name="product_image"
                      oninput="f.src=window.URL.createObjectURL(this.files[0])" hidden onchange="getFileData(this)">
                    </div>
                </div>
                <input hidden type="text" id="scr-img" value="{{ $data->product_image ?? 'upload/product/default-thumbnail.jpg' }}">
                <div class="col-md-12 mt-5 text-right col-lg-10" style="float:left">
                  <a href="{{route('products.index')}}" type="button" class="btn btn-secondary mb-2 mr-5 btn-lg btn-cancel">Hủy</a>
                  <button type="button" class="btn mb-2 ml-4 btn-lg btn-send-form" >Lưu</button>
                </div>                
            </form>
       
        </div>
        
    
    
      
        
    
    
         
    
      

    </div>
    
     
@endsection

@push('js')
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>

<script type="text/javascript">

    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .then( editor => {
        editor.ui.view.editable.element.style.height = '300px';
        } )
        .catch( error => {
            console.error( error );
        } );

   $(document).on("click", ".btn-send-form", function(event) {
        var id = $('#product_id').val();
        var route;
        route = `{{route('products.edit', ':id')}}`;
        route = route.replace(':id', id);

        if(id === ''){
          route = `{{route('products.add')}}`;
          $('#product_form').attr('action', route).submit();
        }     

        $('#product_form').attr('action', route).submit();
   });

   $(document).on("click", ".btn-upload", function(event) {
        $('#product_image').click();
   });

   $(document).on("click", ".btn-delete-file", function(event) {
        $('#product_image').val('');
        $('#file_name').val('');
        var src = '/'+$('#scr-img').val();
        $('#f').attr('src' , src);       
   });
   
    function getFileData(myFile){
      var file = myFile.files[0];  
      var filename = file.name;
      $('#file_name').val(filename);
    }
   

</script>
@endpush