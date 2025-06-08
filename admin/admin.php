<?php
session_start();
require '../db_connect.php'; // $conn là kết nối mysqli

// Kiểm tra quyền admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit;
}

// Xây dựng điều kiện lọc nâng cao
$whereClauses = [];
$params = [];
$types = ""; // kiểu dữ liệu để bind_param (s: string, i: int)

// product_name
if (isset($_GET['product_name']) && $_GET['product_name'] !== '') {
    $whereClauses[] = "product_name LIKE ?";
    $params[] = '%' . $_GET['product_name'] . '%';
    $types .= "s";
}

// price_range
if (isset($_GET['price_range'])) {
    $price_range = (int)$_GET['price_range'];
    if ($price_range === 1) {
        $whereClauses[] = "original_price < 10000000";
    } elseif ($price_range === 2) {
        $whereClauses[] = "original_price BETWEEN 10000000 AND 30000000";
    } elseif ($price_range === 3) {
        $whereClauses[] = "original_price > 30000000";
    }
}


// Tạo câu truy vấn
$sql = "SELECT * FROM products";
if (count($whereClauses) > 0) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}

// Chuẩn bị statement
$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    die("Lỗi chuẩn bị câu truy vấn: " . mysqli_error($conn));
}

// Nếu có tham số thì bind
if (count($params) > 0) {
    // Vì bind_param cần tham số theo reference nên dùng hàm dưới
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

// Thực thi
mysqli_stmt_execute($stmt);

// Lấy kết quả
$result = mysqli_stmt_get_result($stmt);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_stmt_close($stmt);
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Quản Trị</title>
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>
<div class="top-links">
    <a href="./add_product.php">➕ Thêm sản phẩm</a>      
    <a href="users.php">👥 Quản lý khách hàng</a>
    <a href="orders.php">📦 Quản lý đơn hàng</a>
    <a href="inventory.php">📦 Quản lý tồn kho</a>
    <a href="admin_messages.php">Tin nhắn</a>
    <a href="../logout.php">🚪 Đăng xuất</a>
</div>

<h3>Danh sách sản phẩm</h3>

<!-- Form lọc sản phẩm -->
<form method="GET" action="admin.php" class="filter-form">
    <input type="text" name="product_name" placeholder="Tìm theo tên sản phẩm" class="filter-input" value="<?= isset($_GET['product_name']) ? $_GET['product_name'] : '' ?>">

    <select name="price_range" class="filter-select">
        <option value="">Chọn khoảng giá</option>
        <option value="1" <?= isset($_GET['price_range']) && $_GET['price_range'] == '1' ? 'selected' : '' ?>>Dưới 10,000,000 VND</option>
        <option value="2" <?= isset($_GET['price_range']) && $_GET['price_range'] == '2' ? 'selected' : '' ?>>10,000,000 - 30,000,000 VND</option>
        <option value="3" <?= isset($_GET['price_range']) && $_GET['price_range'] == '3' ? 'selected' : '' ?>>Trên 30,000,000 VND</option>
    </select>


    <button type="submit" class="filter-button">Lọc</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Ảnh</th>
        <th>Tên sản phẩm</th>
        <th>Giá gốc</th>
        <th>Giảm giá (%)</th>
        <th>Đã bán</th>
        <th>Hành động</th>
    </tr>
    <?php if ($products): ?>
        <?php foreach ($products as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td>
                <?php if (!empty($row['product_image'])): ?>
                    <img src="<?= htmlspecialchars($row['product_image']) ?>" alt="Ảnh sản phẩm" class="product-thumb">
                <?php else: ?>
                    Không có ảnh
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= number_format($row['original_price']) ?> VND</td>
            <td><?= $row['discount_percentage'] ?>%</td>
            <td><?= $row['sold_quantity'] ?></td>
            <td class="action-links">
                <a href="./edit_product.php?id=<?= $row['id'] ?>">✏️</a>
                <a href="./delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Xác nhận xoá?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="8">Không có sản phẩm nào.</td></tr>
    <?php endif; ?>
</table>
</body>
</html>
