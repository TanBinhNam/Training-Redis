<?php


function product_status($status){
    if($status === 0){
        return 'Dừng bán';
    }
    if($status === 1){
        return 'Đang bán';
    }
    if($status === 2){
        return 'Hết hàng';
    }

    return 'Chọn trạng thái';
}
