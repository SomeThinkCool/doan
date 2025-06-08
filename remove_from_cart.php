<?php
// Khởi tạo session
session_start();

// Kiểm tra nếu có tham số id
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Kiểm tra nếu sản phẩm có trong giỏ hàng
    if (isset($_SESSION['cart'][$productId])) {
        // Xóa sản phẩm khỏi giỏ hàng
        unset($_SESSION['cart'][$productId]);
    }
}

// Chuyển hướng về trang giỏ hàng
header("Location: cart.php");
exit;
