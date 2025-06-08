<?php
session_start();
include('db_connect.php'); // $conn = new mysqli(...);

if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: login.php");
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

if (empty($name) || empty($address) || empty($phone)) {
    die("Vui lòng điền đầy đủ thông tin giao hàng.");
}

$cart = $_SESSION['cart'];
if (!is_array($cart) || count($cart) === 0) {
    header("Location: cart.php");
    exit;
}

// Tính tổng tiền
$total = 0;
foreach ($cart as $product) {
    $price = (float)$product['price'];
    $quantity = (int)$product['quantity'];
    $total += $price * $quantity;
}

try {
    // Bắt đầu transaction
    $conn->begin_transaction();

    // Lấy user_id
    $username = $_SESSION['user'];
    $stmtUser = $conn->prepare("SELECT id FROM users WHERE name = ? LIMIT 1");
    $stmtUser->bind_param("s", $username);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $user = $resultUser->fetch_assoc();

    if (!$user) {
        throw new Exception('Người dùng không tồn tại.');
    }
    $user_id = $user['id'];
    $stmtUser->close();

    // Thêm đơn hàng
    $stmtOrder = $conn->prepare("INSERT INTO orders (user_id, name, address, phone, total, status, created_at) VALUES (?, ?, ?, ?, ?, 'Đang xử lý', NOW())");
    $stmtOrder->bind_param("isssd", $user_id, $name, $address, $phone, $total);
    $stmtOrder->execute();

    $order_id = $conn->insert_id;
    if (empty($order_id)) {
        throw new Exception('Lỗi: Không thể lấy order_id.');
    }
    $stmtOrder->close();

    // Thêm chi tiết đơn hàng và cập nhật số lượng
    $stmtDetail = $conn->prepare("INSERT INTO order_details (order_id, product_id, product_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtUpdateStock = $conn->prepare("UPDATE products SET sold_quantity = sold_quantity + ?, quantity = quantity - ? WHERE id = ?");

    foreach ($cart as $productId => $product) {
        $quantity = (int)$product['quantity'];
        $price = (float)$product['price'];
        $subtotal = $price * $quantity;
        $product_name = $product['name'];

        $stmtDetail->bind_param("iisisd", $order_id, $productId, $product_name, $quantity, $price, $subtotal);
        $stmtDetail->execute();

        $stmtUpdateStock->bind_param("iii", $quantity, $quantity, $productId);
        $stmtUpdateStock->execute();
    }

    $stmtDetail->close();
    $stmtUpdateStock->close();

    $conn->commit();

    unset($_SESSION['cart']);

    header("Location: dashboard.php?success=1");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo "Lỗi khi xử lý đơn hàng: " . $e->getMessage();
}
?>
