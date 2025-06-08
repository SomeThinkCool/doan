<?php
// logout.php

// Khởi tạo session
session_start();

// Xóa tất cả dữ liệu trong session
session_unset();
session_destroy();

// Chuyển hướng đến trang đăng nhập
header("Location: index.php");
exit;
?>
