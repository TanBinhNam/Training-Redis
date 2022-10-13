@extends('layout')
@push('css')    
<style>
    .title{
        font-weight: bold;        
    }

    img {
    height: auto;
    width: 300px;
    display: none
  }
  td:hover img {
    display: block;
  position: absolute;
    z-index: 2
  }
  .input_price{
    margin-right: -25px;
    margin-left: -25px;
  }
</style>
@endpush

@section('content')
<div class='container-fluid p-0'>


    @include('layout.menu')
    <div class=' row'>
      <div class='col-md-9' style="float:left">
        <p class="title ml-2 mb-0">Danh sách sản phẩm </p>
      </div>
      <div class='col-md-2 text-right'>
        <a class=" ml-2 mb-0" href="{{route('products.index')}}">Sản phẩm </a>
      </div>
    </div>
    
    
 
    <hr class='mt-0' style="height: 10px" color="#99d6ff">
    <div class="row ml-5">
      @if (session('msg'))         
                <div class="alert alert-success col-md-6  ml-5 text-center" role="alert" style="width:40%;">          
                  {{ session('msg') }}    
                </div>                 
      @endif
      <div class="alert alert-danger alert-error col-md-6 ml-5 text-center" role="alert" style="width:40%;" hidden></div>
    </div>
    
    
    <form id="search" method="post">
      @csrf
        <div class="form-row">
            <div class="col-md-2 mb-3 ml-2 col-lg-2">
              <label for="product_name">Tên sản phẩm</label>
              <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Nhập tên sản phẩm" value="{{old('product_name')}}">
            </div>

            <div class="col-md-2 mb-3 ml-3 col-lg-2">
              <label for="status">Trạng thái</label> 
              <select class="form-control" id="status" name="status">
                <option selected hidden value=''> Chọn trạng thái</option>
                  {{-- <option value="0">Dừng bán</option>
                  <option value="1">Đang bán</option>
                  <option value="2">Hết hàng</option> --}}
                  @foreach($status as $value  => $description)
                     <option value="{{$value }}">{{$description}}</option>
                  @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-2 ml-5 col-lg-6">

            <div class="col-md-4 mb-2 input_price col-lg-3" style="float: left">
              <label for="price_from">Giá bán từ</label>
              <input type="text" class="form-control" size="4" name="price_from" id="price_from" value="{{old('price_from')}}">
            
             
            </div>
     
            <div class="col-md-2 text-center col-lg-1" style="float: left;margin-top:32px;padding-left:15px">
              ~ 
            </div>

            <div class="col-md-4 mb-2 input_price col-lg-3" style="float: left">
              <label for="price_to">Giá bán đến</label>
              <input type="text" class="form-control" size="4" name="price_to" id="price_to" value="{{old('price_to')}}">
             
            </div>

            </div>
            
            
          </div>

        </form>

    <div class="form-row">

        <div  class="col-md-6 ml-2 col-lg-7 float-left">
            <a class="btn btn-primary" href="{{route('products.add')}}" style="color:white"> <i class="fa fa-user-plus mr-2"></i>Thêm mới</a>
            
        </div>

 
        <div class='col-md-5 text-right col-lg-4'>
            <button class="btn btn-primary ml-auto mr-5 btn-search" form="search"><i class="fa fa-search  mr-2"></i>Tìm kiếm</button>
            <button class="btn btn-success ml-auto btn-reset-search"><i class="fa fa-times  mr-2"></i>Xóa tìm</button>
        </div>
    </div>

    <div class='mt-3 row'>
      <div class="col-md-6 pagination justify-content-center col-lg-9" id="pagination">
      
      </div>
      <p   class="numberOfTotal col-md-6 text-right float-right col-lg-3" ></p>

    </div>
    
    <table class="table table-striped">
        <thead style="background-color: orangered; color:white">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Tên sản phẩm</th>
            <th scope="col">Mô tả</th>
            <th scope="col">Giá</th>
            <th scope="col">Tình trạng</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody id="data-table">
   
        </tbody>
      </table>

      <div class='mt-3 row'>
        <div class="col-md-9 pagination justify-content-center">
           
          </div>
                  
    </div>

    <!--Deleted Modal-->
    <div class="modal fade" id="deletedmodal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Nhắc nhở</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="deleteProductForm">
              @csrf
              <input type="hidden" id="deleted_product_id" name="id"> 
            </form>
              
              <p class='modal-body-deleted'></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy bỏ</button>
            <button type="button" class="btn btn-primary btn-deleted-product">Xác nhận</button>
          </div>
        </div>
      </div>
    </div>

    <input type="hidden" class="currentPage" value='1'>
  </div>
@endsection

@push('js')
<script src="{{asset('js/ajax-product-function.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
  loadProduct()
  $(document).on("click", ".pagination > li > a", function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];  
                //var url = new URLSearchParams(window.location.search);           
               // url.set('page', page);      
                if(typeof  page !== 'undefined'){

                  $('.currentPage').val(page);
                  var filter = valueSearch();

                  loadProduct(page, filter['product_name'], filter['status'], filter['price_from'], filter['price_to']);
                }
            });

            $(document).on("click", ".btn-reset-search", function() {
                $('#product_name').val('');
                $('#status').val('');
                $('#price_from').val('');
                $('#price_to').val('');
                $('.alert-success').attr('hidden', true);
                hiddenAlert('error');
                loadProduct();
              });

              $(document).on("click", ".btn-search", function(e) {
                e.preventDefault();
                hiddenAlert('error');
                $('.alert-success').attr('hidden', true);

                var filter = valueSearch();
         
                loadProduct(filter['page'], filter['product_name'], filter['status'], filter['price_from'], filter['price_to']);
              });

              $(document).on("click", ".delete_product", function(e) {
                e.preventDefault();
                hiddenAlert('error');
                var id = $(this).data("id");
                var name = $(this).data("name");
                $('.modal-body-deleted').text(`Bạn có muốn xóa sản phẩm ${name} không?`);
                $('#deleted_product_id').val(id);
                $('#deletedmodal').modal('show'); 
                $('.alert-success').attr('hidden', true);
              });      

              $(document).on("click", ".btn-deleted-product", function(e) {
                e.preventDefault();
                hiddenAlert('error');
                var id = $('#deleted_product_id').val();
                deleteProduct(id);
              });

              $(document).on("click", ".edit_product", function(e) {
                var id = $(this).data("id");

                window.location.href = "/product-edit/"+id;
              });

})
</script>
@endpush