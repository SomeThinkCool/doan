<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit;
}

// Lấy danh sách người dùng (trừ admin)
$sql = "SELECT id, name, email, status FROM users WHERE role != 'admin'";
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>
    <div class="top-links">
    <a href="admin.php">🏠 Quản lý sản phẩm</a>      
    <a href="users.php">👥 Quản lý khách hàng</a>
    <a href="orders.php">📦 Quản lý đơn hàng</a>
    <a href="inventory.php">📦 Quản lý tồn kho</a>
    <a href="../logout.php">🚪 Đăng xuất</a>
</div>
<h2>Danh sách khách hàng</h2>


    <table>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['status'] === 'locked' ? '🔒 Bị khóa' : '✅ Hoạt động' ?></td>
            <td>
                <a href="toggle_user_status.php?id=<?= $user['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái tài khoản này?')">
                    <?= $user['status'] === 'locked' ? '🔓 Mở khoá' : '🔒 Khoá' ?>
                </a>
                |
                <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xoá người dùng này?')">🗑️ Xoá</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
