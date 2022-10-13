@extends('layout')
@push('css')    
<style>
    .title{
        font-weight: bold;
        
    }
    .icon{
        border: 1px solid white;
        
    }

</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    @include('layout.menu')
    <div class="title ml-2">
        Users
    </div>
    
    <hr class='mt-0' style="height: 10px" color="#99d6ff">
    <div class="row ml-5">
      <div class="alert alert-danger alert-error col-md-6 ml-5 text-center" role="alert" style="width:40%;" hidden></div>
      <div class="alert alert-success alert-success col-md-6  ml-5 text-center" role="alert" style="width:40%;" hidden></div>
    </div>
    
    
    <form id="search" method="post">
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
                <label for="group_role">Nhóm</label>
                <select class="form-control" id="group_role" name="group_role">
                  <option selected hidden value=''>Chọn nhóm</option>
                  @foreach($group_role as $each)
                    <option value="{{$each}}">{{$each}}</option>
                  @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2 ml-6 col-lg-2">
                <label for="status">Trạng thái</label> 
                <select class="form-control" id="status" name="status">
                  <option selected hidden value=''> Chọn trạng thái</option>
                  @foreach($active as $each)
                    <option value="{{$each}}">@if($each === 1) {{'Đang hoạt động'}} @else {{'Tạm khóa'}} @endif</option>
                  @endforeach
                </select>
              </div>
          </div>

        </form>

    <div class="form-row">
        <div class="col-md-6 ml-2 col-lg-7 float-left">
            <button class="btn btn-primary btn-open-add-modal" data-toggle="modal" data-target="#addmodal"><i class="fa fa-user-plus mr-2"></i>Thêm mới</button>
        </div>
        <div class='col-md-5 text-right col-lg-4'>
            <button class="btn btn-primary ml-auto mr-3 btn-search" form="search"><i class="fa fa-search  mr-2"></i>Tìm kiếm</button>
            <button class="btn btn-success ml-auto btn-reset-search"><i class="fa fa-times  mr-2"></i>Xóa tìm</button>
        </div>
    </div>

    <div class='mt-3 row'>
          <div class="col-md-12 pagination justify-content-center col-lg-9 " id="pagination">
          
          </div>
          <p  class="numberOfTotal col-md-12 text-right  col-lg-3" ></p>
    
    </div>
    <div class="container-fluid p-0">
    <table class="table table-striped">
        <thead style="background-color: orangered; color:white">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Họ tên</th>
            <th scope="col">Email</th>
            <th scope="col">Nhóm</th>
            <th scope="col">Trạng thái</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody id="data-table">
   
        </tbody>
      </table>
    </div>
      <div class='mt-3 row'>
        <div class="col-md-12 pagination justify-content-center col-lg-9">
           
          </div>
                  
    </div>
    <input type="hidden" class="currentPage" value='1'>
    <!--Add Modal-->
    <div class="modal fade" id="addmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color:#b3b3cc">
              <h5 class="modal-title" id="exampleModalLabel" >Thêm User</h5>
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
                <label for="add_password" class="col-sm-3 col-form-label">Mật khẩu</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" name="add_password" id="add_password" value="{{old('add_password')}}" 
                  placeholder="Mật khẩu" required>

                  <div class="error_add_password" >
                  </div>
                  
                 
                </div>
              </div>
              <div class="form-group row">
                <label for="confirmpassword" class="col-sm-3 col-form-label">Xác nhận</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" required name="add_password_confirmation" id="add_password_confirmation" placeholder="Xác nhận mật khẩu" required>
                </div>
              </div>

              <div class="form-group row">
                <label for="group_role"  class="col-sm-3 col-form-label">Nhóm</label>
                <div class="col-sm-9">
                  <select class="form-control" id="add_group_role" name="add_group_role" required>
                    <option selected hidden value=''>Chọn nhóm</option>
                    @foreach($group_role as $each)
                      <option value="{{$each}}">{{$each}}</option>
                    @endforeach
                  </select>
                  <div class="error_add_group_role m-auto" >
                 
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
              <button type="button" class="btn btn-secondary mr-5 btn-lg " data-dismiss="modal">Hủy</button>
              <button type="button" class="btn btn-primary btn-add-user btn-lg" style="background-color: orangered" >Lưu</button>
            </div>
                      </form>
          </div>
        </div>
      </div>
      <!---->

      <!--Edit Modal-->
 <div class="modal fade" id="editmodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#b3b3cc">
        <h5 class="modal-title" id="exampleModalLabel" >Chỉnh sửa User</h5>
      </div>
       <form id="editform">
      <div class="modal-body">
        @csrf
        <input type="hidden" name="edit_id" id="edit_user_id"> 
        <div class="form-group row">
          <label for="add_name" class="col-sm-3 col-form-label">Tên</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="edit_name" id="edit_name" value="{{old('edit_name')}}" 
            placeholder="Nhập họ tên" required="">

            <div class="error_edit_name" >
            </div>

          </div>
        </div>
        <div class="form-group row">
          <label for="add_email" class="col-sm-3 col-form-label">Email</label>
          <div class="col-sm-9" required>
            <input required type="text" class="form-control" name="edit_email" id="edit_email" value="{{old('edit_email')}}" placeholder="Nhập email" required>
      
            <div class="error_edit_email" >            
            </div>

          </div>
        </div>

        <div class="form-group row">
          <label for="add_password" class="col-sm-3 col-form-label">Mật khẩu</label>
          <div class="col-sm-9">
            <input type="password" class="form-control" name="edit_password" id="edit_password" value="{{old('edit_password')}}" 
            placeholder="Mật khẩu" required>

            <div class="error_edit_password" >
            </div>
                      
          </div>
        </div>

        <div class="form-group row">
          <label for="edit_password_confirmation" class="col-sm-3 col-form-label">Xác nhận</label>
          <div class="col-sm-9">
            <input type="password" class="form-control" required name="edit_password_confirmation" id="edit_password_confirmation" placeholder="Xác nhận mật khẩu" required>
          </div>
        </div>


        <div class="form-group row">
          <label for="group_role"  class="col-sm-3 col-form-label mr-3">Nhóm</label>
          <select class="form-control col-sm-8" id="edit_group_role" name="edit_group_role" required>
            <option class="option-hidden" selected hidden value=''>Chọn nhóm</option>
            @foreach($group_role as $each)
              <option value="{{$each}}">{{$each}}</option>
            @endforeach
          </select>
          <div class="error_edit_group_role" >
         
          </div>

        </div>
        <div class="form-group row">
          <div class="col-sm-3">Trạng thái</div>
          <div class="col-sm-9">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="edit_status" id="edit_status" checked>
             
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-lg mr-5" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-edit-user btn-lg " style="background-color: orangered" >Lưu</button>
      </div>
                </form>
    </div>
  </div>
</div>
<!---->

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
              <form id="deleteUserForm">
                @csrf
                <input type="hidden" id="deleted_user_id" name="id"> 
              </form>
                
                <p class='modal-body-deleted'></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary btn-deleted-user">Xác nhận</button>
            </div>
          </div>
        </div>
      </div>

      <!--ChangeStatus Modal-->
      <div class="modal fade" id="changestatusmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Nhắc nhở</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="changeStatusForm">
                @csrf
                  <input type="hidden" id="status_user_id" name="id"> 
              </form>
                
                <p class='modal-body-change-status'></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary btn-change-status-user">Xác nhận</button>
            </div>
          </div>
        </div>
      </div>

    </div>
@endsection

@push('js')
<script src="{{asset('js/ajax-user-function.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
        loadUser(); 
    
    
        $(document).on("click", ".pagination > li > a", function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];        
              
                if(typeof  page !== 'undefined'){
                  $('.currentPage').val(page);
                  var filter = valueSearch();           
                  loadUser(page, filter['name'], filter['email'], filter['group_role'], filter['status']);
                }          
            });
   

              $(document).on("click", ".btn-open-add-modal", function(e) {
                emptyErrorAlert('add');
                clearDataOfModal('add');
                removeClassInputError('add');
              }); 

            $(document).on("click", ".btn-add-user", function(e) {
                e.preventDefault();       
                addUser();
              }); 
     
    
     
            $(document).on("click", ".delete_user", function(e) {
                e.preventDefault();
                var id = $(this).data("id");
                var name = $(this).data("name");
                $('.modal-body-deleted').text(`Bạn có muốn xóa thành viên ${name} không?`);
                $('#deleted_user_id').val(id);
                $('#deletedmodal').modal('show'); 
                $('.alert-success').attr('hidden', true);
              });         
            $(document).on("click", ".btn-deleted-user", function(e) {
                e.preventDefault();
                deleteUser();
              });

                    

            $(document).on("click", ".edit_user", function(e) {
              e.preventDefault();
              clearDataOfModal('edit');
              emptyErrorAlert('edit');
              removeClassInputError('edit');

              var id = $(this).data("id");             
              $('#edit_user_id').val(id);

              $('#editmodal').modal('show'); 
              var id = $('#edit_user_id').val();
              
              getValueInEditUser(id);
            });
            
            $(document).on("click", ".btn-edit-user", function(e) {
                e.preventDefault();
                editUser();
              }); 

            $(document).on("click", ".change_status_user", function(e) {
              e.preventDefault();
                var id = $(this).data("id");
                var name = $(this).data("name");
                $('.modal-body-change-status').text(`Bạn có muốn khóa/mở khóa thành viên ${name} không`);
                $('#status_user_id').val(id);
                $('#changestatusmodal').modal('show'); 
                $('.alert-success').attr('hidden', true);
            });

            $(document).on("click", ".btn-change-status-user", function(e) {
                e.preventDefault();   
                changeStatus(); 
            });    

            $(document).on("click", ".btn-reset-search", function() {
                $('#name').val('');
                $('#email').val('');
                $('#group_role').val('');
                $('#status').val('');
                $('.alert-success').attr('hidden', true);
                loadUser();
            });

            $(document).on("click", ".btn-search", function(e) {
                e.preventDefault();
                $('.alert-success').attr('hidden', true);
                var filter = valueSearch();
                loadUser(1, filter['name'], filter['email'], filter['group_role'], filter['status']);
            });
           
})
   
</script>
@endpush