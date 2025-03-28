<?
session_start();
session_unset();
session_destroy();
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
}
header("Location: login.html");
exit();
?>