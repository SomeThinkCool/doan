<?php
session_start();
include('db_connect.php');

if (isset($_SESSION['user'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? "admin/admin.php" : "dashboard.php"));
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } else {
        $sql = "SELECT * FROM users WHERE name = ? LIMIT 1";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($user = mysqli_fetch_assoc($result)) {
                if (trim($user['status']) === 'locked') {
                    $error_message = "Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.";
                } elseif (password_verify($password, trim($user['pass']))) {
                    session_regenerate_id(true); // Tăng bảo mật
                    $_SESSION['user'] = $user['name'];
                    $_SESSION['role'] = trim($user['role']);

                    header("Location: " . ($_SESSION['role'] === 'admin' ? "admin/admin.php" : "dashboard.php"));
                    exit;
                } else {
                    $error_message = "Mật khẩu sai!";
                }
            } else {
                $error_message = "Tên đăng nhập không tồn tại!";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Lỗi truy vấn cơ sở dữ liệu.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="./style/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="text" name="username" class="input-field" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" class="input-field" placeholder="Mật khẩu" required>
            <button type="submit" class="login-button">Đăng nhập</button>
        </form>

        <a href="register.php" class="register-link">Chưa có tài khoản? Đăng ký ngay!</a>
    </div>
</body>
</html>
