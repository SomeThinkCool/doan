<?php
include('../db_connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$admin_email = 'admin@example.com';


$sql = "
    SELECT cm.*, u.email AS sender_name
    FROM chat_messages cm
    LEFT JOIN users u ON cm.sender_email = u.email
    WHERE cm.receiver_email = ?
    ORDER BY cm.sent_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $sender_display = $row['sender_email'] === 'guest' ? 'Guest' : ($row['sender_name'] ?? $row['sender_email']);
    echo "<p><strong>" . htmlspecialchars($sender_display) . "</strong>: " . htmlspecialchars($row['message']) . "</p>";
}

$stmt->close();
?>
