<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền thực hiện thao tác này.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = trim($_POST['new_status']);

    // Cập nhật trạng thái đơn hàng
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "si", $new_status, $order_id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: orders.php");
        exit;
    } else {
        echo "Cập nhật trạng thái thất bại.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Dữ liệu không hợp lệ.";
}
