<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    $sql = "DELETE FROM products WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            header("Location: ../admin.php");
            exit;
        } else {
            echo "Xóa sản phẩm không thành công: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi chuẩn bị câu truy vấn: " . $conn->error;
    }
} else {
    echo "Không có ID sản phẩm hợp lệ!";
}
?>
