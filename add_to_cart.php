<?php
session_start();
include('db_connect.php'); // db_connect.php tạo biến $conn = mysqli_connect(...);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
        $productId = (int)$_POST['product_id'];

        // Lấy thông tin sản phẩm trong CSDL
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $productId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $product = mysqli_fetch_assoc($result);

            if ($product) {
                // Giá sau khi giảm giá
                $price = $product['original_price'] * (1 - $product['discount_percentage'] / 100);

                // Kiểm tra giỏ hàng đã tồn tại sản phẩm chưa
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += 1;
                } else {
                    $_SESSION['cart'][$productId] = [
                        'name' => $product['product_name'],
                        'price' => $price,
                        'quantity' => 1
                    ];
                }

                // Quay lại trang trước đó
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
                exit();
            } else {
                echo "Sản phẩm không tồn tại!";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Lỗi chuẩn bị truy vấn.";
        }
    } else {
        echo "ID sản phẩm không hợp lệ.";
    }
} else {
    echo "Yêu cầu không hợp lệ.";
}
?>
