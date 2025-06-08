<?php
session_start();
require '../db_connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit;
}

// Lấy danh sách đơn hàng
$sql = "SELECT o.id, o.user_id, o.name, o.address, o.phone, o.total, o.status, o.created_at, u.name AS username
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
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

<h2>Danh sách đơn hàng</h2>

<?php if ($orders): ?>
<table>
    <tr>
        <th>ID</th>
        <th>Khách hàng</th>
        <th>Người nhận</th>
        <th>Địa chỉ</th>
        <th>Điện thoại</th>
        <th>Tổng tiền (VND)</th>
        <th>Trạng thái</th>
        <th>Ngày đặt</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= htmlspecialchars($order['id']) ?></td>
        <td><?= htmlspecialchars($order['username'] ?? 'Khách vãng lai') ?></td>
        <td><?= htmlspecialchars($order['name']) ?></td>
        <td><?= htmlspecialchars($order['address']) ?></td>
        <td><?= htmlspecialchars($order['phone']) ?></td>
        <td><?= number_format($order['total'], 0, ',', '.') ?></td>
        <td><?= htmlspecialchars($order['status']) ?></td>
        <td><?= htmlspecialchars($order['created_at']) ?></td>
        <td class="action-links">
            <form action="update_order_status.php" method="POST">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <select name="new_status" onchange="this.form.submit()">
                    <option disabled selected><?= htmlspecialchars($order['status']) ?></option>
                    <option value="Đang xử lý">Đang xử lý</option>
                    <option value="Đang giao hàng">Đang giao hàng</option>
                    <option value="Hoàn tất">Hoàn tất</option>
                    <option value="Đã hủy">Đã hủy</option>
                </select>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p>Chưa có đơn hàng nào.</p>
<?php endif; ?>

</body>
</html>
