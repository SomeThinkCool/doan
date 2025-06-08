<?php
// Kết nối cơ sở dữ liệu
include('../db_connect.php');

// Lấy kiểu sắp xếp từ URL (GET)
$sort = $_GET['sort'] ?? 'newest';

// Đặt điều kiện sắp xếp dựa trên kiểu sắp xếp
switch ($sort) {
    case 'price_asc':
        $orderBy = "original_price ASC";
        break;
    case 'price_desc':
        $orderBy = "original_price DESC";
        break;
    case 'discount_desc':
        $orderBy = "discount_percentage DESC";
        break;
    case 'name_asc':
        $orderBy = "product_name ASC";
        break;
    case 'name_desc':
        $orderBy = "product_name DESC";
        break;
    case 'sold_desc':
        $orderBy = "sold_quantity DESC";
        break;
    case 'newest':
    default:
        $orderBy = "created_at DESC";
        break;
}

// Truy vấn cơ sở dữ liệu để lấy sản phẩm theo kiểu sắp xếp
$sql = "SELECT * FROM products ORDER BY $orderBy";

if ($stmt = $conn->prepare($sql)) {
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        die("Lỗi thực thi truy vấn: " . $stmt->error);
    }
} else {
    die("Lỗi chuẩn bị truy vấn: " . $conn->error);
}

// Trả về danh sách sản phẩm
return $products;
?>
