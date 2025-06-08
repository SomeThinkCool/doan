<?php
session_start();
require '../db_connect.php'; 


if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $original_price = $_POST['original_price'];
    $discount_percentage = $_POST['discount_percentage'];
    $sold_quantity = $_POST['sold_quantity'];
    $product_image = $_POST['product_image'];
    $quantity = $_POST['quantity'];

    
    if (!empty($product_name) && !empty($original_price) && !empty($discount_percentage) && !empty($sold_quantity) && !empty($product_image) && isset($quantity)) {
        
        $sql = "INSERT INTO products (product_name, original_price, discount_percentage, sold_quantity, product_image, quantity) 
                VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            
            $stmt->bind_param("sddiss", $product_name, $original_price, $discount_percentage, $sold_quantity, $product_image, $quantity);

            if ($stmt->execute()) {
                echo "Sản phẩm đã được thêm thành công.";
            } else {
                echo "Lỗi truy vấn: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Lỗi chuẩn bị câu truy vấn: " . $conn->error;
        }
    } else {
        echo "Vui lòng điền đầy đủ thông tin.";
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
    <link rel="stylesheet" href="../style/add_product.css"> <!-- Liên kết CSS cho trang add product -->
</head>
<body>
    <div class="top-links">
        <a href="../admin.php">⬅️ Quay lại quản lý sản phẩm</a>
    </div>

    <h3>Thêm sản phẩm mới</h3>

    <form method="POST">
        <label for="product_name">Tên sản phẩm</label>
        <input type="text" name="product_name" id="product_name" required>

        <label for="original_price">Giá gốc</label>
        <input type="number" name="original_price" id="original_price" required>

        <label for="discount_percentage">Giảm giá (%)</label>
        <input type="number" name="discount_percentage" id="discount_percentage" required>

        <label for="sold_quantity">Số lượng đã bán</label>
        <input type="number" name="sold_quantity" id="sold_quantity" required>

        <label for="quantity">Số lượng tồn kho</label> <!-- Thêm trường số lượng -->
        <input type="number" name="quantity" id="quantity" required> <!-- Thêm trường số lượng -->

        <label for="product_image">Ảnh sản phẩm (URL)</label>
        <input type="url" name="product_image" id="product_image" placeholder="Nhập URL ảnh sản phẩm" required>

        <button type="submit">Thêm sản phẩm</button>
    </form>
</body>
</html>
