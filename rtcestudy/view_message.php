<?php
require "config.php";
if (isset($_POST["user_id"])) {
    header("Location: login.html");
    exit;
}
try {
    $stmt = $pdo->prepare("SELECT m.id, m.message, m.sent_at, m.is_read, u.username as sender_name 
                        FROM messages m 
                        JOIN users u ON m.sender_id = u.id 
                        WHERE m.receiver_id = ? 
                        ORDER BY m.sent_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $update_stmt = $pdo->prepare('UPDATE messages SET is_read = TRUE WHERE receiver_id = ? AND is_read = FALSE');
    $update_stmt->execute([$_SESSION['user_id']]);
} catch (PDOException $e) {
    die('获取消息失败' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View messages</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .unread {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Received messages</h1>
    <a href="send_message.html">Send</a>
    <?php if (count($messages) > 0): ?>
        <table>
            <tr>
                <th>Sender:</th>
                <th>Message:</th>
                <th>Time:</th>
                <th>Status:</th>
            </tr>
            <?php foreach ($messages as $message): ?>
                <tr class="<?php echo $message['is_read'] ? '' : 'unread'; ?>">
                    <td><?php echo htmlspecialchars($message['sender_name']); ?></td>
                    <td><?php echo htmlspecialchars($message['message']); ?></td>
                    <td><?php echo htmlspecialchars($message['sent_at']); ?></td>
                    <td><?php echo $message['is_read'] ? '已读' : '未读'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No messages</p>
    <?php endif; ?>
</body>

</html>