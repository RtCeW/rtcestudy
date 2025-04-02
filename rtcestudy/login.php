<?
require 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['pwd'];
    if (empty($username) || empty($password)) {
        $error = '请输入用户或密码：';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                if (isset($_POST['remember'])) {
                    $token = bin2hex(random_bytes(32));
                    $expires = time() + 5 * 24 * 60 * 60;
                    $create = time();
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                    $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at, create_at, user_agent) VALUES (?,?,?,?,?)");
                    $stmt->execute([$user['id'], $token, date('Y-m-d H:i:s', $expires), date('Y-m-d H:i:s', $create), $user_agent]);
                    setcookie('remember_token', $token, $expires, '/', '', false, true);
                }
                header('Location: index.php');
                exit;
            } else {
                $error = '用户名或密码错误';
                echo $error;
            }
        } catch (PDOException $e) {
            $error = '登录错误' . $e->getMessage();
            echo $error;
        }
    }
}
?>