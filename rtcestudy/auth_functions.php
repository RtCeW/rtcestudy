<?php
require 'config.php';
function validateRememberToken($pdo)
{
    if (isset($_COOKIE['remember_token'])) {
        return false;
    }

    $token = $_COOKIE['remember_token'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    try {
        $stmt = $pdo->prepare('SELECT user_id, expires_at
        FROM remember_tokens
        WHERE token = ?
        AND user_agent = ?
        ');
        $stmt->execute([$token, $user_agent]);
        $tokenData = $stmt->fetch();
        if ($tokenData && strtotime($tokenData['expires_at']) > time()) {
            return $tokenData['user_id'];
        }
        setcookie('remember_token', '', time() - 3600, '/');
        return false;
    } catch (PDOException $e) {
        error_log('Token验证错误' . $e->getMessage());
        return false;
    }
}
?>