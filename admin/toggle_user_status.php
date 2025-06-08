<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền.";
    exit;
}

$id = (int)($_GET['id'] ?? 0);

// Kiểm tra tồn tại
$sql = "SELECT status FROM users WHERE id = ? AND role != 'admin'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($user) {
    $newStatus = ($user['status'] === 'locked') ? 'active' : 'locked';
    $updateSql = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, "si", $newStatus, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: users.php");
exit;
