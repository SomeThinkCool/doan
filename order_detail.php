<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db_connect.php');
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Kiểm tra tham số order_id
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Mã đơn hàng không hợp lệ.");
}

$order_id = (int)$_GET['order_id'];
$username = $_SESSION['user'];

// Lấy thông tin người dùng
$sql_user = "SELECT * FROM users WHERE name = ? LIMIT 1";
if ($stmt = mysqli_prepare($conn, $sql_user)) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        $user_id = $user['id'];
    } else {
        die("Không tìm thấy người dùng.");
    }

    mysqli_stmt_close($stmt);
}

// Lấy thông tin đơn hàng thuộc về user đang đăng nhập
$sql_order = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
if ($stmt = mysqli_prepare($conn, $sql_order)) {
    mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($order = mysqli_fetch_assoc($result)) {
        // Đơn hàng hợp lệ
    } else {
        die("Không tìm thấy đơn hàng.");
    }

    mysqli_stmt_close($stmt);
}

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_details = "SELECT od.*, p.product_name AS product_name
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = ?";
$details = [];
if ($stmt = mysqli_prepare($conn, $sql_details)) {
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $details = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="./style/dashboard.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .actions {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Chi tiết đơn hàng #<?php echo $order_id; ?></h1>
        <p><strong>Ngày đặt:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
        <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
        <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total'], 0); ?> VND</p>

        <h2>Sản phẩm trong đơn</h2>
        <?php if (count($details) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price'], 0); ?> VND</td>
                            <td><?php echo number_format($item['price'] * $item['quantity'], 0); ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không có sản phẩm nào trong đơn này.</p>
        <?php endif; ?>

        <div class="actions">
            <a href="dashboard.php">← Quay lại lịch sử đơn hàng</a>
        </div>
    </div>
</body>
</html>
