<?php
$severname = "39.108.54.64";
$user = "rtce";
$pwd = "090523090615wq";
$dbname = "study";
$username = trim($_POST['username']);
$password = password_hash(trim($_POST['pwd']), PASSWORD_DEFAULT);
$tel = trim($_POST['tel']);
$email = trim($_POST['email']);
$address = trim($_POST['address']);
try {
    $conn = new PDO("mysql:host=$severname;dbname=$dbname;charset=utf8mb4", $user, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        die("已注册");
    }
    $sql = "INSERT INTO users (username,password,phone,email,address,score) VALUE (:username, :password, :tel, :email, :address, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":tel", $tel);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    echo "注册成功！";
    header('Location: login.html');
} catch (PDOException $e) {
    echo "错误" . $e->getMessage();
}
$conn = null;
?>