<?php
session_start();
$total = 0;

// Cap nhat so luong
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['quantity'] as $productId => $quantity) {
        // Cap nhat so luong sp trong gio
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = max(1, (int)$quantity); // Đảm bảo số lượng không nhỏ hơn 1
        }
    }
}

// Tinh tong tien gio hang
foreach ($_SESSION['cart'] as $product) {
    $total += $product['price'] * $product['quantity'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="./style/cart.css">
    <script>
        // Tu dong gui form 
        function updateCart() {
            document.getElementById("cart-form").submit();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Giỏ hàng của bạn</h1>
        <form id="cart-form" method="POST" action="cart.php">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $productId => $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</td>
                        <td>
                            <!-- Thay doi so luong -> Form tu dong thay doi -->
                            <input type="number" name="quantity[<?php echo $productId; ?>]" value="<?php echo $product['quantity']; ?>" min="1" style="width: 50px;" oninput="updateCart()">
                        </td>
                        <td><?php echo number_format($product['price'] * $product['quantity'], 0, ',', '.'); ?> VND</td>
                        <td>
                            <a href="remove_from_cart.php?id=<?php echo $productId; ?>" class="remove-item">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <h3>Tổng cộng: <?php echo number_format($total, 0, ',', '.'); ?> VND</h3>
            </div>

            <div class="cart-actions">
                <a href="index.php" class="view-details">Tiếp tục mua sắm</a>
                <a href="checkout.php" class="view-details">Thanh toán</a>
            </div>
        </form>
    </div>
</body>
</html>
