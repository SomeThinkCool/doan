<?php
include('db_connect.php');
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

$results = [];

if ($keyword) {
    $sql = "SELECT * FROM products WHERE product_name LIKE ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }

    $like_keyword = "%$keyword%";
    $stmt->bind_param("s", $like_keyword);
    if (!$stmt->execute()) {
        die("Lỗi thực thi truy vấn: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result) {
        $results = $result->fetch_all(MYSQLI_ASSOC);
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả tìm kiếm: <?= htmlspecialchars($keyword); ?> | PC Store</title>
    <link rel="stylesheet" href="./style/search.css">
</head>
<body>
<?php include('./handf/header.php'); ?>

<div class="main-body">
    <h2>Kết quả tìm kiếm cho: "<?= htmlspecialchars($keyword); ?>"</h2>

    <?php if (count($results) > 0): ?>
        <div class="product-grid">
        <?php foreach ($results as $product): 
            $originalPrice = $product['original_price'];
            $discount = $product['discount_percentage'];
            $newPrice = $originalPrice * (1 - $discount / 100);
        ?>
            <div class="promo-card">
                <a href="product_detail.php?id=<?= $product['id']; ?>">
                    <img src="<?= htmlspecialchars($product['product_image']); ?>" alt="<?= htmlspecialchars($product['product_name']); ?>">
                </a>
                <div class="promo-info">
                    <h4><a href="product_detail.php?id=<?= $product['id']; ?>"><?= htmlspecialchars($product['product_name']); ?></a></h4>
                    <div class="price-box">
                        <span class="new-price"><?= number_format($newPrice, 0); ?>₫</span>
                        <?php if ($discount > 0): ?>
                            <span class="old-price"><?= number_format($originalPrice, 0); ?>₫</span>
                            <span class="discount">-<?= $discount; ?>%</span>
                        <?php endif; ?>
                    </div>
                    <form method="post" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                        <button type="submit" class="add-to-cart">Thêm vào giỏ</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Không tìm thấy sản phẩm phù hợp.</p>
    <?php endif; ?>
</div>

<?php include('./handf/footer.php'); ?>
</body>
</html>
