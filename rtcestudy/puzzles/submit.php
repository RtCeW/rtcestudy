<?php
require "../auth_functions.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    submit($_POST['numerator'], $_POST['denominator'], $_POST['puzzle_id'], $pdo);
}
?>