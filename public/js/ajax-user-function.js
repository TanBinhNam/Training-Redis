function loadUser(page, name, email, group_role, status) {
  $.ajax({
    url: '/user-list',
    dataType: 'json',
    data: {
                page: page ?? 1,
                name: name ?? '',                  
                email: email ?? '',                                          
                group_role: group_role ?? '',                  
                status: status ?? '',                  
            },
    success: function(response) {
        var length = Object.keys(response.data).length;

        if(length === 0 && page !== 1){
          page = 1;
          loadUser(page, name, email, group_role, status);
        }
        
        $("#data-table").empty().html('');
        $(".pagination").empty().html('');
        if(length > 0){         
            var currentPage = $('.currentPage').val();
            var i = response.numberUserPerPage * (currentPage - 1);
               
            $.each(response.data, function(key, value){ 
                
                var status = 'Đang hoạt động';
                var color = 'green';
                if(value.is_active === 0){
                    status = 'Tạm khóa';
                    color = 'red';
                }
                
                var string;
                string += ` <tr>
                                <td >${i+1}</td>
                                <td>${value.name}</td>
                                <td>${value.email}</td>
                                <td>${value.group_role}</td>
                                <td style="color: ${color}">${status}</td>
                                <td>
                                    <a data-id="${value.id}" class="icon-CRUD edit_user" style="color:#009999" id="edit"><i class="fa fa-pencil" ></i></a>

<a data-name="${value.name}"  data-id="${value.id}" class="icon-CRUD delete_user" style="color:red" id="delete"><i class="fa  fa-trash"></i></a>

<a data-name="${value.name}" data-id="${value.id}" class="icon-CRUD change_status_user" style="color:black" id="lock_unlock"><i class="fa fa-user-times"></i></a>
                                </td>
                            </tr>
                                    `;             
                $("#data-table").append(string);
                i++;
            });

        }else{
            $("#data-table").append(`<tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>`)
        }

        $(".pagination").html(
                   `${(response.paginate)}`
        );    
        var offset = response.numberUserPerPage * (currentPage -1 );
        var from = offset + 1;
        var to = offset + length;
        if(response.count === 0){
          from = 0;
          to = 0;
        }
        
        $('.numberOfTotal').text(`Hiển thị từ ${from} ~ ${to} trong tổng số ${response.count} thành viên`);
        
    }
  });
}

function deleteUser() {
    $.ajax({
   url: '/user-delete',
   dataType: 'json',
   type: 'POST',
   data: $('#deleteUserForm').serialize(),
   success: function(response) {
    hiddenAlert('error');
    hiddenAlert('success');

     $('.alert-error').removeAttr('hidden');
     $('.alert-error').text(response.msg);

     $('#deletedmodal').modal('hide');
     var filter = valueSearch();
          
     loadUser(filter['page'], filter['name'], filter['email'], filter['group_role'], filter['status']);
   },
   error: function(response){        
       $('.alert-error').removeAttr('hidden');
       $('.alert-error').text(response.responseJSON.error);
       $('#deletedmodal').modal('hide');
   }
 });
}

function changeStatus() {
    $.ajax({
   url: '/user-status',
   type: 'POST',
   dataType: 'json',
   data: $('#changeStatusForm').serialize(),
   success: function(response) {         
    hiddenAlert('error');
    hiddenAlert('success');

       $('.alert-error').removeAttr('hidden');
       $('.alert-error').text(response.msg);
       $('#changestatusmodal').modal('hide');

       var filter = valueSearch();
          
       loadUser(filter['page'], filter['name'], filter['email'], filter['group_role'], filter['status']);
   },
   error: function(response){        
       $('.alert-error').removeAttr('hidden');
       $('.alert-error').text(response.responseJSON.error);
       $('#changestatusmodal').modal('hide');
   }
 });
}

function editUser()
{
    $.ajax(
        {
        type: "POST",
        url: '/user-edit',
        data: $('#editform').serialize(),
        success: function (response){
          $('.alert-success').removeAttr('hidden');
          $('.alert-success').text(response.msg);
         
          clearDataOfModal('edit');
          
          var filter = valueSearch();
          
          loadUser(filter['page'], filter['name'], filter['email'], filter['group_role'], filter['status']);
          $('#editmodal').modal('hide');
        },
        error: function(response){
          emptyErrorAlert('edit');

          removeClassInputError('edit');

          $.each(response.responseJSON.errors, function(key, value) {
            var name = key;
            $.each(value,  function(key, value){
              $(`#${name}`).addClass('input-error');         
              $(`.error_${name}`).append(
              `<div style="color:red">${value}</div>`
              );
            });                    
          });
          }
        }
)
}

function getValueInEditUser(id)
{
    $.ajax(
        {
          dataType: 'json',
          url: '/user-edit',
          data: {
                  id: id,                        
                },
          success: function (response){       
              value = response.data;
 
              $('#edit_name').val(value.name);
              //$('#edit_password').val(value.password);
              $('#edit_email').val(value.email);
              $('#edit_status').attr('checked', true);
              $('#edit_group_role').val(value.group_role);
              if(value.is_active === 0){
                  $('#edit_status').removeAttr('checked');
              }
          },
          error: function(response){
          $('.alert-error').removeAttr('hidden');
          $('.alert-error').text(response.responseJSON.error);       
          },
        })
}

function addUser()
{
    $.ajax(
        {
        type: "POST",
        url: '/user-add',
        data: $('#addform').serialize(),
        success: function (response){
          $('.alert-success').removeAttr('hidden');
          $('.alert-success').text(response.msg);
          clearDataOfModal('add');
          $('.currentPage').val(1);
          
          loadUser();
          $('#addmodal').modal('hide');
        },
        error: function(response){
          emptyErrorAlert('add');

          removeClassInputError('add');
          
          $.each(response.responseJSON.errors, function(key, value) {
            var name = key;
            $.each(value,  function(key, value){

              $(`#${name}`).addClass('input-error');
              
              $(`.error_${name}`).append(
              `<div style="color:red">${value}</div>`
              );

            });                    
          });
          }
        }
)
}

function valueSearch(){
          var filter = [];

          filter['name'] = $('#name').val();
          filter['email'] = $('#email').val();
          filter['group_role'] = $('#group_role').val();
          filter['status'] = $('#status').val();
          filter['page'] = $('.currentPage').val();

          return filter;
}

function removeClassInputError(action)
{
  $(`#${action}_name`).removeClass('input-error');
  $(`#${action}_email`).removeClass('input-error');
  $(`#${action}_password`).removeClass('input-error');
  $(`#${action}_group_role`).removeClass('input-error');
}

function emptyErrorAlert(action)
{
  $(`.error_${action}_name`).empty().html();
  $(`.error_${action}_email`).empty().html();
  $(`.error_${action}_password`).empty().html();
  $(`.error_${action}_group_role`).empty().html();
}

function clearDataOfModal(action)
{

  $(`#${action}_name`).val('');
  $(`#${action}_email`).val('');
  $(`#${action}_password`).val('');
  $(`#${action}_password_confirmation`).val('');
  $(`#${action}_password`).val('');
}

function hiddenAlert(type)
{
  $(`.alert-${type}`).text('');
  $(`.alert-${type}`).attr('hidden', true);
}

function showAlert(type, text)
{
  $(`.alert-${type}`).text(text);
  $(`.alert-${type}`).attr('hidden', false);
}