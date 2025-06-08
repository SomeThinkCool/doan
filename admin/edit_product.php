<?php
session_start();
require '../db_connect.php';  // Đảm bảo đường dẫn chính xác đến file kết nối CSDL

// Kiểm tra quyền admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit;
}

// Kiểm tra nếu có tham số 'id' trong URL và hợp lệ
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    // Lấy thông tin sản phẩm từ CSDL
    $sql = "SELECT * FROM products WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        if (!$product) {
            echo "Sản phẩm không tồn tại.";
            exit;
        }
    } else {
        echo "Lỗi chuẩn bị truy vấn: " . $conn->error;
        exit;
    }

    // Xử lý form cập nhật sản phẩm
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_name = $_POST['product_name'] ?? '';
        $original_price = $_POST['original_price'] ?? '';
        $discount_percentage = $_POST['discount_percentage'] ?? '';
        $product_image = $_POST['product_image'] ?? '';

        // Cập nhật thông tin sản phẩm
        $update_sql = "UPDATE products SET 
                        product_name = ?, 
                        original_price = ?, 
                        discount_percentage = ?, 
                        product_image = ?
                       WHERE id = ?";
        if ($update_stmt = $conn->prepare($update_sql)) {
            $update_stmt->bind_param(
                "sddisi", 
                $product_name, 
                $original_price, 
                $discount_percentage, 
                $product_image, 
                $product_id
            );
            if ($update_stmt->execute()) {
                // Chuyển hướng về trang admin sau khi cập nhật thành công
                header('Location: ../admin.php');
                exit;
            } else {
                echo "Lỗi khi cập nhật sản phẩm: " . $update_stmt->error;
            }
            $update_stmt->close();
        } else {
            echo "Lỗi chuẩn bị câu truy vấn cập nhật: " . $conn->error;
        }
    }
} else {
    echo "Không có ID sản phẩm để sửa.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin sản phẩm</title>
    <link rel="stylesheet" href="../style/add_product.css">  <!-- Đảm bảo đường dẫn CSS chính xác -->
</head>
<body>

<div class="top-links">
    <a href="../admin.php">👥 Quản lý sản phẩm</a>      
    <a href="../logout.php">🚪 Đăng xuất</a>
</div>

<h3>Sửa thông tin sản phẩm</h3>

<!-- Form để sửa sản phẩm -->
<form method="POST" action="edit_product.php?id=<?= $product['id'] ?>" enctype="multipart/form-data">
    <label for="product_name">Tên sản phẩm:</label>
    <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

    <label for="original_price">Giá gốc:</label>
    <input type="number" id="original_price" name="original_price" value="<?= htmlspecialchars($product['original_price']) ?>" required>

    <label for="discount_percentage">Giảm giá (%):</label>
    <input type="number" id="discount_percentage" name="discount_percentage" value="<?= htmlspecialchars($product['discount_percentage']) ?>" required>

    <label for="product_image">Ảnh sản phẩm (đường dẫn):</label>
    <input type="text" id="product_image" name="product_image" value="<?= htmlspecialchars($product['product_image']) ?>" required>

    <button type="submit">Cập nhật sản phẩm</button>
</form>

</body>
</html>
