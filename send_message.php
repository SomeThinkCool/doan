<?php
session_start();
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_email = 'guest';

    if (isset($_SESSION['user'])) {
        // Lấy email user từ DB dựa vào name đã lưu trong session
        $username = $_SESSION['user'];
        $stmt = $conn->prepare("SELECT email FROM users WHERE name = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($email);
        if ($stmt->fetch()) {
            $sender_email = $email;
        }
        $stmt->close();
    }

    $receiver_email = 'admin@example.com'; // Thay bằng email admin thực tế
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (sender_email, receiver_email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $sender_email, $receiver_email, $message);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: index.php");
    exit();
}
?>
