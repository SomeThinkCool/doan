<?php
session_start();
require '../db_connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit;
}

// Lấy order_id từ GET và kiểm tra hợp lệ
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "ID đơn hàng không hợp lệ.";
    exit;
}

$order_id = (int)$_GET['order_id'];

$sql = "
    SELECT od.*, o.name, o.address, o.phone, o.total, o.status, o.created_at
    FROM order_details od
    JOIN `order` o ON od.order_id = o.id
    WHERE od.order_id = ?
";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$order_details = [];
$order_info = null;

while ($row = mysqli_fetch_assoc($result)) {
    if (!$order_info) {
        // Lấy thông tin đơn hàng từ bản ghi đầu tiên
        $order_info = [
            'name' => $row['name'],
            'address' => $row['address'],
            'phone' => $row['phone'],
            'total' => $row['total'],
            'status' => $row['status'],
            'created_at' => $row['created_at']
        ];
    }
    // Lấy chi tiết từng sản phẩm
    $order_details[] = [
        'id' => $row['id'],
        'product_name' => $row['product_name'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'subtotal' => $row['subtotal']
    ];
}

mysqli_stmt_close($stmt);

if (!$order_info) {
    echo "Đơn hàng không tồn tại hoặc không có chi tiết.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?= $order_id ?></title>
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>
<div class="top-links">
    <a href="orders.php">← Quay lại danh sách đơn hàng</a>
</div>

<h3>Chi tiết đơn hàng #<?= $order_id ?></h3>

<p><strong>Khách hàng:</strong> <?= htmlspecialchars($order_info['name']) ?></p>
<p><strong>Địa chỉ:</strong> <?= nl2br(htmlspecialchars($order_info['address'])) ?></p>
<p><strong>Điện thoại:</strong> <?= htmlspecialchars($order_info['phone']) ?></p>
<p><strong>Tổng tiền:</strong> <?= number_format($order_info['total'], 2) ?> VND</p>
<p><strong>Trạng thái:</strong> <?= htmlspecialchars($order_info['status']) ?></p>
<p><strong>Ngày đặt:</strong> <?= htmlspecialchars($order_info['created_at']) ?></p>

<?php if ($order_details): ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($order_details as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td><?= number_format($item['price'], 2) ?> VND</td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($item['subtotal'], 2) ?> VND</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>Không có chi tiết nào cho đơn hàng này.</p>
<?php endif; ?>

</body>
</html>
