<?php
require "auth_functions.php";
session_start();
$islogined = isset($_SESSION['user_id']);
if (!$islogined) {
    $user_id = validateRememberToken($pdo);
    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
    } else {
        header('Location: login.html');
        exit;
    }
} else {
    $score = getScore($pdo);
}
?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <title>Study</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2 class="title">Welcome <?php if ($islogined) {
        echo $_SESSION['username'];
    } ?></h2>
    <div class="auth-links">
        <?php if (!$islogined): ?>
            <a href="login.html">Login</a>
            <a href="register.html">Register</a>
        <?php else: ?>
            <a href="score.php">Score:<?php echo $score; ?></a>
            <a href="everyday_puzzle.php">Everyday puzzle</a>
            <a href="send_message.html">Send</a>
            <a href="view_message.php">View</a>
            <a href="settings.php"><?php echo htmlspecialchars($_SESSION['username']) ?></a>
        <?php endif; ?>
        <form action="logout.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>
    <div name="answers"><a href="answers.html">Answers</a></div>
    <div name="puzzles"><a href="puzzles.html">Puzzles</a></div>
</body>
<script>

</script>

</html>