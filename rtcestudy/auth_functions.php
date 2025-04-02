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
function submit($numerator, $denominator, $id, $pdo)
{
    try {
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare('SELECT numerator,denominator FROM answers WHERE id=?');
        $stmt->execute([$id]);
        $answer = $stmt->fetch();
        $ans_numerator = $answer['numerator'];
        $ans_denominator = $answer['denominator'];
        if ($ans_numerator == $numerator && $ans_denominator == $denominator) {
            $stmt = $pdo->prepare('SELECT EXISTS(SELECT 1 FROM user_answer WHERE user_id = ? AND puzzle_id = ?) ');
            $stmt->execute([$user_id, $id]);
            $result = (bool) $stmt->fetchColumn();
            if (!$result) {
                $time = date('Y-m-d H:i:s', time());
                $stmt = $pdo->prepare('INSERT INTO user_answer (user_id,puzzle_id,submit_at) VALUE (?,?,?)');
                $stmt->execute([$user_id, $id, $time]);
                $stmt = $pdo->prepare('UPDATE users SET score = score + 1 WHERE id=?');
                $stmt->execute([$user_id]);
                echo "Correct!";
                return true;
            } else {
                echo "You have submitted correctly!";
                return false;
            }
        } else {
            echo "Incorrect!";
            return false;
        }
    } catch (PDOException $e) {
        error_log('提交错误' . $e->getMessage());
        exit;
    }
}
function getScore($pdo)
{
    try {
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare('SELECT score FROM users WHERE id=?');
        $stmt->execute([$user_id]);
        $score = $stmt->fetch();
        return $score['score'];
    } catch (PDOException $e) {
        echo '获取错误' . $e->getMessage();
    }
}

function getRecords($pdo)
{
    try {
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare('SELECT * FROM user_answer WHERE user_id=?');
        $stmt->execute([$user_id]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $records;
    } catch (PDOException $e) {
        echo '查找记录出错' . $e->getMessage();
        exit;
    }
}
?>