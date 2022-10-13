@extends('layout')
@push('css')    
<style>
    .title{
        font-weight: bold;
        
    }
    td:nth-child(4) {
                width: 25%;
            }
    .address{
      word-break: break-word;
    }
</style>
@endpush

@section('content')
    @include('layout.menu')
    <div class='row'>
      <div class='col-md-9' style="float:left">
        <p class="title ml-2 mb-0">Danh sách khách hàng </p>
      </div>
      <div class='col-md-2 text-right'>
        <a class=" ml-2 mb-0" href="{{route('customers.index')}}">Khách hàng </a>
      </div>
    </div>
    
    
 
    <hr class='mt-0' style="height: 10px" color="#99d6ff">
    <div class="row ml-5">
    
     
        <div class="alert alert-danger col-md-6 ml-5 text-center import-alert" role="alert" style="width:40%;" hidden>
          Các dòng bị lỗi khi import
         
        </div>

      <div class="alert alert-danger alert-error col-md-6 ml-5 text-center" role="alert" style="width:40%;" hidden></div>
      <div class="alert alert-success alert-success col-md-6  ml-5 text-center" role="alert" style="width:40%;" hidden></div>
    </div>
    
    
    <form id="search" method="post">
      @csrf
        <div class="form-row">
            <div class="col-md-2 mb-3 ml-2 col-lg-2">
              <label for="name">Tên</label>
              <input type="text" class="form-control" name="name" id="name" placeholder="Nhập họ tên" value="{{old('name')}}">
            </div>

            <div class="col-md-2 mb-3 ml-6 col-lg-2">
              <label for="email">Email</label>
              <input type="text" class="form-control" name="email" id="email" placeholder="Nhập email" value="{{old('email')}}">     
            </div>

            <div class="col-md-2 mb-3 ml-6 col-lg-2">
              <label for="status">Trạng thái</label> 
              <select class="custom-select" id="status" name="status">
                <option selected hidden value=''> Chọn trạng thái</option>
                  <option value="1">Đang hoạt động</option>
                  <option value="0">Tạm khóa</option>
              </select>
            </div>

            <div class="col-md-2 mb-2 ml-6 col-lg-2">
              <label for="name">Địa chỉ</label>
              <input type="text" class="form-control" name="address" id="address" placeholder="Nhập địa chỉ" value="{{old('name')}}">
            </div>
            
          </div>

        </form>

    <div class="form-row">
     
        <form  id="import" method="POST" enctype="multipart/form-data" class="form-horizontal">
        @csrf
          <input id="import_excel" name="import_excel" type='file' style="display: none;"/>
          <input type="submit" hidden>
      </form>
        <div class="col-md-7 ml-2 col-lg-7 float-left">
            <button class="btn btn-primary btn-open-add-modal" data-toggle="modal" data-target="#addmodal"><i class="fa fa-user-plus mr-2"></i>Thêm mới</button>
            <button class="btn btn-success ml-3 btn-import" for="#import_excel"><i class="fa fa-download mr-2"></i>Import CSV</button>
            
            <button class="btn btn-success ml-3 btn-export" for="#search"><i class="fa fa-upload mr-2"></i>Export CSV</button>
        </div>

 
        <div class='col-md-12 text-right col-lg-4'>
            <button class="btn btn-primary ml-auto mr-5 btn-search" form="search"><i class="fa fa-search  mr-2"></i>Tìm kiếm</button>
            <button class="btn btn-success ml-auto btn-reset-search"><i class="fa fa-times  mr-2"></i>Xóa tìm</button>
        </div>
    </div>

    <form id="export" action="{{route('customers.export')}}" method="post">
      @csrf  
              <input type="text" class="form-control" name="name" id="value_search_name" hidden>       
              <input type="text" class="form-control" name="email" id="value_search_email" hidden>               
              <input type="text" class="form-control" name="status" id="value_search_status" hidden>                  
              <input type="text" class="form-control" name="address" id="value_search_address" hidden>
    </form>

    <div class='mt-3 row'>
      <div class="col-md-12 pagination justify-content-center col-lg-9" id="pagination">
      
      </div>
      <p   class="numberOfTotal col-md-12 text-right col-lg-3" ></p>

    </div>
    
    <table class="table table-striped">
        <thead style="background-color: orangered; color:white">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Họ tên</th>
            <th scope="col">Email</th>
            <th scope="col">Địa chỉ</th>
            <th scope="col">Điện thoại</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody id="data-table">
   
        </tbody>
      </table>

      <div class='mt-3 row'>
        <div class="col-md-12 pagination justify-content-center col-lg-9  ">
           
          </div>
                  
    </div>
    {{-- <input type="hidden" class="pageOfPagination" value='1'> --}}
    <!--Add Modal-->
    <div class="modal fade" id="addmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color:#b3b3cc">
              <h5 class="modal-title" id="exampleModalLabel" >Thêm khách hàng</h5>
            </div>
             <form id="addform">
            <div class="modal-body">
              @csrf


              <div class="form-group row">
                <label for="add_name" class="col-sm-3 col-form-label">Tên</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="add_name" id="add_name" value="{{old('add_name')}}" 
                  placeholder="Nhập họ tên" required="">
     
                  <div class="error_add_name" >
                  </div>

                </div>
              </div>
              <div class="form-group row">
                <label for="add_email" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9" required>
                  <input required type="text" class="form-control" name="add_email" id="add_email" value="{{old('add_email')}}" placeholder="Nhập email" required>
            
                  <div class="error_add_email" >            
                  </div>

                </div>
              </div>

              <div class="form-group row">
                <label for="add_password" class="col-sm-3 col-form-label">Điện thoại</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="add_tel_num" id="add_tel_num" value="{{old('add_tel_num')}}" 
                  placeholder="Điện thoại" required>

                  <div class="error_add_tel_num" >
                  </div>
                  
                 
                </div>
              </div>
              <div class="form-group row">
                <label for="confirmpassword" class="col-sm-3 col-form-label">Địa chỉ</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" required name="add_address" id="add_address" placeholder="Địa chỉ" required>
                  <div class="error_add_address" >
                  </div>
                </div>
              </div>

      
              <div class="form-group row">
                <div class="col-sm-3">Trạng thái</div>
                <div class="col-sm-9">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="add_status" id="add_status" checked>
                   
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary btn-add-customer" >Lưu</button>
            </div>
                      </form>
          </div>
        </div>
      </div>
      <!---->
      <div class="modal fade" id="modalError">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header justify-content-center" style="background-color:red">
              <h5 class="modal-title" id="exampleModalLabel">Lỗi</h5>
            </div>
            
            <div class="modal-body-error text-center">
             

            </div>
    
                 
          </div>
        </div>
      </div>
      <input type="hidden" class="currentPage" value='1'>
      <input type="hidden" id="MAX_FILE_SIZE" value="5242880" />
@endsection

@push('js')
<script src="{{asset('js/ajax-customer-function.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {

  loadCustomer()
  $(document).on("click", ".pagination > li > a", function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];  
   
                if(typeof  page !== 'undefined'){
                  var name = $('#name').val();
                  var email = $('#email').val();
                  var address = $('#address').val();
                  var status = $('#status').val();
                  $('.currentPage').val(page);
                  loadCustomer(page, name, email, status, address);
                }
            });

            $(document).on("click", ".btn-reset-search", function() {
                $('#name').val('');
                $('#email').val('');
                $('#address').val('');
                $('#status').val('');
                $('.alert-success').attr('hidden', true);
                $('#value_search_name').val('');
                $('#value_search_email').val('');
                $('#value_search_address').val('');
                $('#value_search_status').val('');
                $('.currentPage').val(1);
                loadCustomer();
              });

              $(document).on("click", ".btn-search", function(e) {
                e.preventDefault();
                $('.alert-success').attr('hidden', true);
                var name = $('#name').val();
                var email = $('#email').val();
                var address = $('#address').val();
                var status = $('#status').val();

                $('#value_search_name').val(name);
                $('#value_search_email').val(email);
                $('#value_search_address').val(address);
                $('#value_search_status').val(status);
         
                loadCustomer(1, name, email, status, address);
              });

              $(document).on("click", ".btn-open-add-modal", function(e) {
                emptyErrorAlert('add');

                removeClassInputError('add');
              }); 

              $(document).on("click", '.btn-add-customer', function(e) {
                e.preventDefault();
                addCustomer();
              })

              $('.btn-import').click(function() {
                   $('#import_excel').click();
              });

              // $('#import_excel').change(function(e)
              // {
              //   $('#import').submit();
              // })      
              
              $('.btn-export').click(function(e)
              {
                  $('#export').submit();
              })     
              $(document).on('click', '.edit_user', function(e) {
                $("#data-table").find("tr").removeAttr('contenteditable');
                $("#data-table").find("tr").removeAttr('style');
                $("#data-table").find("a").removeClass('Update');
                
                var currentRow = $(this).closest("tr");
                currentRow.attr('style','border: 3px solid black;');
                currentRow.attr('contenteditable', true); // get current row 1st table cell TD value
                $(this).addClass('Update');
              
              })

              $(document).on('click', '.Update', function(e) {
                var id = $(this).data('id');

                var currentRow = $(this).closest("tr");
                
                var name = currentRow.find("td:eq(1)").html();
                var email = currentRow.find("td:eq(2)").html(); 
                var address = currentRow.find("td:eq(3)").html(); 
                var tel_num = currentRow.find("td:eq(4)").html();

                editCustomer(id, name, email, tel_num, address)              
              })
             
              $('#import_excel').change(function(e)
              {
                var formData = new FormData();           
                formData.append("import_excel", $(this)[0].files[0]);  
                formData.append("_token", $("input[name='_token']").val()); 
               
                if($(this)[0].files[0].size < $('#MAX_FILE_SIZE').val()){
                  importCustomer(formData);
                }else{
                  hiddenAlert('error');
                  hiddenAlert('success');
                  showAlert('error', 'File có dung lượng quá lớn');
                }
              
              })

})
</script>
@endpush