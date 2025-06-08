<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin.php");
    exit;
}

$username = $_SESSION['user'];

// Xử lý cập nhật thông tin cá nhân
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);

    // Cập nhật thông tin
    $sql_update = "UPDATE users SET name = ?, email = ? WHERE name = ?";
    if ($stmt_update = mysqli_prepare($conn, $sql_update)) {
        mysqli_stmt_bind_param($stmt_update, "sss", $new_name, $new_email, $username);
        mysqli_stmt_execute($stmt_update);
        mysqli_stmt_close($stmt_update);

        // Cập nhật session
        $_SESSION['user'] = $new_name;
        $username = $new_name;
    }
}

// Lấy thông tin user sau khi cập nhật
$sql = "SELECT * FROM users WHERE name = ? LIMIT 1";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        header("Location: login.php");
        exit;
    }

    mysqli_stmt_close($stmt);
} else {
    die("Lỗi truy vấn cơ sở dữ liệu.");
}

// Lấy đơn hàng
$sql_orders = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
if ($stmt_orders = mysqli_prepare($conn, $sql_orders)) {
    mysqli_stmt_bind_param($stmt_orders, "i", $user['id']);
    mysqli_stmt_execute($stmt_orders);
    $result_orders = mysqli_stmt_get_result($stmt_orders);
    $orders = mysqli_fetch_all($result_orders, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt_orders);
} else {
    die("Lỗi truy vấn đơn hàng.");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Trang Dashboard</title>
    <link rel="stylesheet" href="./style/dashboard.css" />
    <style>
        form.update-form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            max-width: 400px;
        }
        form.update-form label {
            display: block;
            margin-top: 10px;
        }
        form.update-form input {
            width: 100%;
            padding: 5px;
        }
        form.update-form button {
            margin-top: 10px;
            padding: 5px 10px;
        }
        #profile-display {
            margin-top: 20px;
            max-width: 400px;
            border: 1px solid #eee;
            padding: 10px;
        }
        #profile-display p {
            margin: 8px 0;
        }
        .actions {
            margin-top: 20px;
        }
    </style>
    <script>
        function toggleEditProfile(showForm) {
            const display = document.getElementById('profile-display');
            const form = document.getElementById('profile-form');
            if (showForm) {
                display.style.display = 'none';
                form.style.display = 'block';
            } else {
                display.style.display = 'block';
                form.style.display = 'none';
            }
        }
        window.onload = function() {
            toggleEditProfile(false); // ẩn form lúc đầu
        };
    </script>
</head>
<body>
    <div class="dashboard-container">
        <h1>Chào mừng, <?php echo htmlspecialchars($user['name']); ?>!</h1>

        <!-- Hiển thị thông tin cá nhân -->
        <div id="profile-display">
            <p><strong>Tên người dùng:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <button onclick="toggleEditProfile(true)">Chỉnh sửa thông tin</button>
        </div>

        <!-- Form cập nhật thông tin -->
        <form method="post" class="update-form" id="profile-form" style="display:none;">
            <input type="hidden" name="update_profile" value="1" />
            <label for="name">Tên người dùng:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />

            <button type="submit">Lưu thay đổi</button>
            <button type="button" onclick="toggleEditProfile(false)">Hủy</button>
        </form>

        <h2>Lịch sử đơn hàng</h2>
        <?php if (count($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày tạo</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                            <td><?php echo number_format($order['total'], 2); ?> VND</td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><a href="order_detail.php?order_id=<?php echo $order['id']; ?>">Xem chi tiết</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Chưa có đơn hàng nào.</p>
        <?php endif; ?>

        <div class="actions">
            <a href="index.php">← Về trang chủ</a>
            <a href="logout.php">Đăng xuất</a>
        </div>
    </div>
</body>
</html>
