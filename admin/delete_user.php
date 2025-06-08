<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền.";
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$sql = "DELETE FROM users WHERE id = ? AND role != 'admin'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: users.php");
exit;
?>
