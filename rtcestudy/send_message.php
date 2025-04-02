<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_name = trim($_POST['receiver_name']);
    $message = trim($_POST['message']);
    if (empty($message) || empty($receiver_name)) {
        echo '收件人和内容不能为空';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
            $stmt->execute([$receiver_name]);
            $receiver = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$receiver) {
                throw new Exception('用户不存在');
            }
            $receiver_id = $receiver['id'];
            $stmt = $pdo->prepare('INSERT INTO messages (sender_id, receiver_id, message) VALUES (?,?,?)');
            $stmt->execute([$sender_id, $receiver_id, $message]);
            $pdo->commit();
            echo '发送成功';
        } catch (Exception $e) {
            $pdo->rollBack();
            echo '发送失败' . $e->getMessage();
        }
    }
}
?>