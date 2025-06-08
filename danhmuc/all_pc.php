<?php
// Kết nối cơ sở dữ liệu
include('../db_connect.php');

// Gọi file sắp xếp
$products = include('../sort_products.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm PC</title>
    <link rel="stylesheet" href="../style/allpc.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include('../handf/header.php'); ?>

<main class="main-container">
    <form method="get" class="sort-form">
        <label for="sort">Sắp xếp theo:</label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="newest" <?= ($_GET['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
            <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
            <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
            <option value="discount_desc" <?= ($_GET['sort'] ?? '') === 'discount_desc' ? 'selected' : '' ?>>Giảm giá nhiều nhất</option>
            <option value="name_asc" <?= ($_GET['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>Tên A-Z</option>
            <option value="name_desc" <?= ($_GET['sort'] ?? '') === 'name_desc' ? 'selected' : '' ?>>Tên Z-A</option>
            <option value="sold_desc" <?= ($_GET['sort'] ?? '') === 'sold_desc' ? 'selected' : '' ?>>Bán chạy nhất</option>
        </select>
    </form>

    <div class="product-grid-container">
        <?php foreach ($products as $product): 
            $originalPrice = $product['original_price'];
            $discount = $product['discount_percentage'];
            $newPrice = $originalPrice * (1 - $discount / 100);
        ?>
        <div class="product-card">
            <!-- Link đến trang chi tiết sản phẩm -->
            <a href="../product_detail.php?id=<?= $product['id']; ?>">
                <img src="<?= htmlspecialchars($product['product_image']); ?>" alt="<?= htmlspecialchars($product['product_name']); ?>">
                <h4><?= htmlspecialchars($product['product_name']); ?></h4>
            </a>
            <div class="price-box">
                <span class="new-price"><?= number_format($newPrice, 0); ?>₫</span>
                <?php if ($discount > 0): ?>
                    <span class="old-price"><?= number_format($originalPrice, 0); ?>₫</span>
                    <span class="discount">-<?= $discount; ?>%</span>
                <?php endif; ?>
            </div>
            <form method="post" action="../add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                <button type="submit" class="add-to-cart">
                <i class="fa fa-cart-plus"></i> Thêm vào giỏ
            </button>
</form>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include('../handf/footer.php'); ?>
</body>
</html>
