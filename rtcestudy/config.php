<?php
define("dbhost", "39.108.54.64");
define("dbname", "study");
define("dbuser", "rtce");
define("dbpass", "090523090615wq");
try {
    $pdo = new PDO(
        "mysql:host=" . dbhost . ';dbname=' . dbname . ';charset=utf8',
        dbuser,
        dbpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die('数据库连接失败' . $e->getMessage());
}
session_start();
?>