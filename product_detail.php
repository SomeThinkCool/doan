<?php
// Kết nối cơ sở dữ liệu
include('db_connect.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID sản phẩm không hợp lệ!";
    exit;
}

$productId = (int)$_GET['id']; // Ép kiểu sang số nguyên

// Lấy thông tin chi tiết sản phẩm với mysqli
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Lỗi chuẩn bị truy vấn: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $productId);
$stmt->execute();

$result = $stmt->get_result();
$product = $result->fetch_assoc();

$stmt->close();

if (!$product) {
    echo "Sản phẩm không tồn tại!";
    exit;
}

$originalPrice = $product['original_price'];
$discount = $product['discount_percentage'];
$newPrice = $originalPrice * (1 - $discount / 100);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="./style/product_detail.css">
    <style>
        .product-detail {
            display: flex;
            gap: 40px;
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-gallery {
            flex: 1;
        }

        .single-image img {
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
        }

        .product-info {
            flex: 1;
        }

        .product-info h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .product-description {
            margin-bottom: 20px;
            white-space: pre-line;
        }

        .price-box {
            margin-bottom: 20px;
        }

        .new-price {
            color: red;
            font-size: 24px;
            font-weight: bold;
            margin-right: 10px;
        }

        .old-price {
            text-decoration: line-through;
            color: gray;
            margin-right: 10px;
        }

        .discount {
            color: green;
            font-weight: bold;
        }

        .add-to-cart {
            background-color: #ff6600;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .add-to-cart:hover {
            background-color: #e65c00;
        }
    </style>
</head>
<body>
<?php include('./handf/header.php'); ?>

<div class="product-detail">
    <div class="product-gallery">
        <div class="single-image">
            <img src="<?= htmlspecialchars($product['product_image']); ?>" alt="<?= htmlspecialchars($product['product_name']); ?>">
        </div>
    </div>

    <div class="product-info">
        <h1><?= htmlspecialchars($product['product_name']); ?></h1>
        <p class="product-description"><?= nl2br(htmlspecialchars($product['description'])); ?></p>
        <div class="price-box">
            <span class="new-price"><?= number_format($newPrice, 0); ?>₫</span>
            <span class="old-price"><?= number_format($originalPrice, 0); ?>₫</span>
            <span class="discount">-<?= $discount; ?>%</span>
        </div>
        <form method="post" action="add_to_cart.php">
            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
            <button type="submit" class="add-to-cart">
                <i class="fa fa-shopping-bag"></i> Thêm vào giỏ
            </button>
        </form>
    </div>
</div>

<?php include('./handf/footer.php'); ?>
</body>
</html>
