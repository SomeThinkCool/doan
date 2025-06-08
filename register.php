<?php
// register.php

// Kết nối cơ sở dữ liệu (mysqli)
include('db_connect.php');

session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_name = $_POST['reg_name'] ?? '';
    $reg_pass = $_POST['reg_pass'] ?? '';
    $reg_email = $_POST['reg_email'] ?? '';

    if (!empty($reg_name) && !empty($reg_pass) && !empty($reg_email)) {
        // Kiểm tra email đã tồn tại chưa
        $sql_check_email = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt_check_email = $conn->prepare($sql_check_email);
        if (!$stmt_check_email) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }
        $stmt_check_email->bind_param("s", $reg_email);
        $stmt_check_email->execute();
        $result = $stmt_check_email->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email này đã được đăng ký!";
        } else {
            // Mã hóa mật khẩu
            $hashed_pass = password_hash($reg_pass, PASSWORD_DEFAULT);

            // Chèn dữ liệu người dùng mới
            $sql_insert = "INSERT INTO users (name, email, pass) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            if (!$stmt_insert) {
                die("Lỗi chuẩn bị truy vấn: " . $conn->error);
            }
            $stmt_insert->bind_param("sss", $reg_name, $reg_email, $hashed_pass);

            if ($stmt_insert->execute()) {
                $success_message = "Đăng ký thành công!";
                header("Location: login.php");
                exit;
            } else {
                $error_message = "Lỗi khi đăng ký, vui lòng thử lại!";
            }
        }

        $stmt_check_email->close();
        if (isset($stmt_insert)) {
            $stmt_insert->close();
        }
    } else {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="./style/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Đăng ký</h2>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <input type="text" name="reg_name" class="input-field" placeholder="Tên đăng nhập" required>
            <input type="password" name="reg_pass" class="input-field" placeholder="Mật khẩu" required>
            <input type="email" name="reg_email" class="input-field" placeholder="Email" required>
            <button type="submit" class="register-button">Đăng ký</button>
        </form>

        <a href="login.php" class="login-link">Đã có tài khoản? Đăng nhập ngay!</a>
    </div>
</body>
</html>
