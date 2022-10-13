function loadProduct(page, product_name, status, price_from, price_to) {
    
    $.ajax({
      url: '/product-list',
      dataType: 'json',
      data: {
                  page: page ?? 1,
                  product_name: product_name ?? '',                  
                  status: status ?? '',                                          
                  price_from: price_from ?? 0,                  
                  price_to: price_to ?? 0,                  
              },
      success: function(response) {
          var length = Object.keys(response.data).length;
        
          if(length === 0 && page !== 1){
            page = 1;
            loadProduct(page, product_name, status, price_from, price_to);
          }
          

          $("#data-table").empty().html('');
          $(".pagination").empty().html('');
          if(length > 0){                   
            var currentPage = $('.currentPage').val();
            var i = response.numberProductPerPage * (currentPage - 1);

              $.each(response.data, function(key, value){ 
  
                  var status = 'Đang bán';
                  var color = 'green';
                  if(value.is_sales === 0){
                      status = 'Ngừng bán';
                      color = 'red';
                  }
                  if(value.is_sales === 2){
                    status = 'Hết hàng';
                }
           
                  var string;
                  string += ` <tr>
                                  <td >${i+1}</td>
                                  <td>${value.product_name}
                                  <img src="${value.product_image}" alt="">
                                  
                                  </td>
                                  <td>${value.description}</td>
                                  <td>$${parseInt(value.product_price)}</td>
                                  <td style="color: ${color}">${status}</td>
                                  <td>
                                      <a data-id="${value.product_id}" class="icon-CRUD edit_product" style="color:#009999" id="edit"><i class="fa fa-pencil" ></i></a>
  
                                     <a data-name="${value.product_name}"  data-id="${value.product_id}" class="icon-CRUD delete_product" style="color:red" id="delete"><i class="fa  fa-trash"></i></a>
  
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
          var offset = response.numberProductPerPage * (currentPage -1 );
          var from = offset + 1;
          var to = offset + length;
          if(response.count === 0){
            from = 0;
            to = 0;
          }
          $('.numberOfTotal').text(`Hiển thị từ ${from} ~ ${to} trong tổng số ${response.count} sản phẩm`);
          
      }
    });
  }
  
  function deleteProduct(id) {
    hiddenAlert('error');
      $.ajax({
     url: '/product-delete',
     type: 'POST',
     dataType: 'json',
     data: $('#deleteProductForm').serialize(),
     success: function(response) {
       var filter = valueSearch();
            
       loadProduct(filter['page'], filter['product_name'], filter['status'], filter['price_from'], filter['price_to']);
       
       $('.alert-error').removeAttr('hidden');
       $('.alert-error').text(response.msg);
  
       $('#deletedmodal').modal('hide');
       
     },
     error: function(response){        
         $('.alert-error').removeAttr('hidden');
         $('.alert-error').text(response.responseJSON.error);
         $('#deletedmodal').modal('hide');
     }
   });
  }
  
 
  
  function valueSearch(){
        var filter = [];

        filter['product_name'] = $('#product_name').val();
        filter['status'] = $('#status').val();
        filter['price_from'] = $('#price_from').val();
        filter['price_to'] = $('#price_to').val();
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