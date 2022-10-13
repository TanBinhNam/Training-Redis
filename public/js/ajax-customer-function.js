function loadCustomer(page, name, email, status, address) {
    $.ajax({
      url: '/customer-list',
      dataType: 'json',
      data: {
                  page: page ?? 1,
                  name: name ?? '',                  
                  email: email ?? '',                                          
                  status: status ?? '',                  
                  address: address ?? '',                  
            },
      success: function(response) {
          var length = Object.keys(response.data).length;
          
          $("#data-table").empty().html('');
          $(".pagination").empty().html('');
          if(length > 0){                   
            var currentPage = $('.currentPage').val();
            var i = response.numberCustomerPerPage * (currentPage - 1);

          $.each(response.data, function(key, value){ 
      
                var string;
                string += ` <tr>
                                  <td >${i+1}</td>
                                  <td>${value.customer_name}</td>
                                  <td>${value.email}</td>
                                  <td class="address">${value.address}</td>
                                  <td>${value.tel_num}</td>
                                  <td>
                                      <a data-id="${value.customer_id}" class="icon-CRUD edit_user" style="color:#009999" id="edit"><i class="fa fa-pencil" ></i></a>
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
          var offset = response.numberCustomerPerPage * (currentPage -1 );
          var from = offset + 1;
          var to = offset + length;
          if(response.count === 0){
            from = 0;
            to = 0;
          }
          $('.numberOfTotal').text(`Hiển thị từ ${from} ~ ${to} trong tổng số ${response.count} khách hàng`);
  
     
      }
    });
  }
  

  function editCustomer(id, name, email, tel_num, address)
  {
    var token = $("input[name='_token']").val();
      $.ajax(
          {
            type: 'POST',
          
            url: '/customer-edit',
            data: {
            id: id,
            name: name,                  
            email: email,                                          
            tel_num: tel_num,                  
            address: address,      
            _token: token            
            },
          success: function (response){
            hiddenAlert('error');
            hiddenAlert('success');

            $('.alert-success').removeAttr('hidden');
            $('.alert-success').text(response.msg);

            var currentPage = $('.currentPage').val();
            loadCustomer(currentPage);
            
          },
          error: function(response){
            var error ='';
            $('#modalError').modal('show'); 
           
            $.each(response.responseJSON.errors, function(key, value) {
                           error += value + '<br>';
            });
            $('.modal-body-error').html(error);
            }
          }
      )
  }
  
  
  
  function addCustomer()
  {
      $.ajax(
          {
          type: "POST",
          url: '/customer-add',
          data: $('#addform').serialize(),
          success: function (response){
            hiddenAlert('error');
            hiddenAlert('success');

            $('.alert-success').removeAttr('hidden');
            $('.alert-success').text(response.msg);
  
            loadCustomer();
            $('#addmodal').modal('hide');
            $('#add_name').val('');
            $('#add_email').val('');
            $('#add_tel_num').val('');
            $('#add_address').val('');
            
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

  function importCustomer(formData){
        $.ajax({
          url: '/customer-import',
          type: 'POST',
          dataType: 'json',
          data: formData,
          enctype: 'multipart/form-data',
              async: false,
                cache: false,
                contentType: false,
                processData: false,

                success: function (response){
                  hiddenAlert('error');
                  hiddenAlert('success');
                  loadCustomer();
                  var error = Object.keys(response.error_row).length;
                  showAlert('success', 'Import thành công');
                  if(error > 0){
                    $.each(response.error_row, function(key, value) {
              

                        $(`.import-alert`).attr('hidden', false);         
                        $(`.import-alert`).append(
                        ` <li>Dòng ${key}:  ${value}</li>`
                        );
                                      
                    });
                  }
                  
                  
            },
            error: function (response){
           
              $.each(response.responseJSON.errors, function(key, value) {
            
                showAlert('error', value);
                              
            });
       
            },

      })
  }

  function exportCustomer()
  {
      $.ajax(
          {
          type: "POST",
          url: '/customer-export',
          data: $('#search').serialize(),
   
          success: function (response){
            showAlert('success', response.msg)
          },
          error: function(response){
            }
          }
        )
  }

  function removeClassInputError(action)
  {
    $(`#${action}_name`).removeClass('input-error');
    $(`#${action}_email`).removeClass('input-error');
    $(`#${action}_address`).removeClass('input-error');
    $(`#${action}_tel_num`).removeClass('input-error');
  }

  function emptyErrorAlert(action)
  {
    $(`.error_${action}_name`).empty().html();
    $(`.error_${action}_email`).empty().html();
    $(`.error_${action}_address`).empty().html();
    $(`.error_${action}_tel_num`).empty().html();
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