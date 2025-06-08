<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập.";
    exit;
}

// Cap nhat so luong ton kho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $product_id = (int)$_POST['product_id'];
    $new_quantity = (int)$_POST['new_quantity'];

    $update_sql = "UPDATE products SET quantity = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, 'ii', $new_quantity, $product_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: inventory.php?success=1");
    exit;
}

// Loc ten theo ma va ten san pham
$where = [];
$params = [];
$types = "";

if (!empty($_GET['product_name'])) {
    $where[] = "product_name LIKE ?";
    $params[] = '%' . $_GET['product_name'] . '%';
    $types .= "s";
}
if (!empty($_GET['product_id'])) {
    $where[] = "id = ?";
    $params[] = (int)$_GET['product_id'];
    $types .= "i";
}

$sql = "SELECT * FROM products";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY quantity ASC"; // sắp xếp theo số lượng tăng dần

$stmt = mysqli_prepare($conn, $sql);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tồn kho</title>
    <link rel="stylesheet" href="../style/admin.css">
    <style>
        .low-stock { background-color: #ffdddd; }
        .stock-form input[type="number"] { width: 60px; }
    </style>
</head>
<body>
<div class="top-links">
    <a href="admin.php">🏠 Quản lý sản phẩm</a>      
    <a href="users.php">👥 Quản lý khách hàng</a>
    <a href="orders.php">📦 Quản lý đơn hàng</a>
    <a href="inventory.php">📦 Quản lý tồn kho</a>
    <a href="../logout.php">🚪 Đăng xuất</a>
</div>

<h2>Quản lý tồn kho</h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">✔️ Cập nhật số lượng thành công!</p>
<?php endif; ?>

<!-- Tim kiem -->
<form method="get" action="inventory.php">
    <input type="text" name="product_name" placeholder="Tìm theo tên sản phẩm" value="<?= htmlspecialchars($_GET['product_name'] ?? '') ?>">
    <input type="number" name="product_id" placeholder="Tìm theo mã sản phẩm" value="<?= htmlspecialchars($_GET['product_id'] ?? '') ?>">
    <button type="submit">🔍 Tìm kiếm</button>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Đã bán</th>
        <th>Số lượng tồn kho</th>
        <th>Chỉnh sửa tồn kho</th>
    </tr>
    <?php foreach ($products as $product): ?>
    <tr class="<?= $product['quantity'] < 5 ? 'low-stock' : '' ?>">
        <td><?= $product['id'] ?></td>
        <td><?= htmlspecialchars($product['product_name']) ?></td>
        <td><?= number_format($product['original_price']) ?> VND</td>
        <td><?= $product['sold_quantity'] ?></td>
        <td><?= $product['quantity'] ?></td>
        <td>
            <form method="post" action="inventory.php" class="stock-form">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="number" name="new_quantity" value="<?= $product['quantity'] ?>" min="0" required>
                <button type="submit" name="update_stock">💾 Lưu</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php if (!$products): ?>
    <p>Không tìm thấy sản phẩm phù hợp.</p>
<?php endif; ?>
</body>
</html>
