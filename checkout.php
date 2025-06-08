<?php
session_start();

// Kiem tra dang nhap
if (!isset($_SESSION['user'])) {
    // Neu chua dang nhap -> Chuyen den trang dang nhap
    header("Location: login.php?redirect=checkout");
    exit;
}
// Tong tien gio hang
$total = 0;
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Giỏ hàng của bạn đang trống.";
    exit;
}
// Tinh tong
foreach ($_SESSION['cart'] as $product) {
    $total += $product['price'] * $product['quantity'];
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="./style/cart.css">
</head>
<body>
    <h1>Thanh toán</h1>
    <h2>Tổng cộng: <?php echo number_format($total, 0, ',', '.'); ?> VND</h2>

    <form action="process_checkout.php" method="POST" class="checkout-form">
        <label for="name">Tên người nhận:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="address">Địa chỉ giao hàng:</label><br>
        <input type="text" id="address" name="address" required><br><br>

        <label for="phone">Số điện thoại:</label><br>
        <input type="text" id="phone" name="phone" required><br><br>

        <input type="submit" value="Thanh toán">
    </form>

    <h2><a href="cart.php" class="return-cart-link">Quay lại giỏ hàng</a></h2>
</body>
</html>
