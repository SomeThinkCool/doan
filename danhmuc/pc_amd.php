<?php
include('../db_connect.php'); // chỉnh lại đường dẫn nếu cần

$keyword = 'AMD'; // Tìm kiếm sản phẩm có "AMD" trong tên

// Chuẩn bị câu truy vấn với dấu hỏi ? thay vì :keyword
$sql = "SELECT * FROM products WHERE product_name LIKE ? ORDER BY created_at DESC";

if ($stmt = $conn->prepare($sql)) {
    // Gán tham số cho câu truy vấn, kiểu "s" là string
    $like_keyword = "%$keyword%";
    $stmt->bind_param("s", $like_keyword);

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

// $products bây giờ là mảng kết quả như fetchAll(PDO::FETCH_ASSOC)
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PC AMD | PC Store</title>
    <link rel="stylesheet" href="../style/search.css">
</head>
<body>
<?php include('../handf/header.php'); ?>

<div class="main-body">
    <h2>Danh sách PC AMD</h2>

    <?php if (count($products) > 0): ?>
        <div class="product-grid">
        <?php foreach ($products as $product): 
            $originalPrice = $product['original_price'];
            $discount = $product['discount_percentage'];
            $newPrice = $originalPrice * (1 - $discount / 100);
        ?>
            <div class="promo-card">
                <a href="../product_detail.php?id=<?= $product['id']; ?>">
                    <img src="<?= htmlspecialchars($product['product_image']); ?>" alt="<?= htmlspecialchars($product['product_name']); ?>">
                </a>
                <div class="promo-info">
                    <h4><a href="../product_detail.php?id=<?= $product['id']; ?>"><?= htmlspecialchars($product['product_name']); ?></a></h4>
                    <div class="price-box">
                        <span class="new-price"><?= number_format($newPrice, 0); ?>₫</span>
                        <?php if ($discount > 0): ?>
                            <span class="old-price"><?= number_format($originalPrice, 0); ?>₫</span>
                            <span class="discount">-<?= $discount; ?>%</span>
                        <?php endif; ?>
                    </div>
                    <form method="post" action="../add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                        <button type="submit" class="add-to-cart">Thêm vào giỏ</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Không tìm thấy sản phẩm PC AMD nào.</p>
    <?php endif; ?>
</div>

<?php include('../handf/footer.php'); ?>
</body>
</html>
