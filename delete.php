<?php
session_start();
if (!$_SESSION["is_admin"]) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $pdo = new PDO("mysql:host=localhost;dbname=site;charset=utf8", "root", "");

    $pdo->prepare("DELETE FROM user_languages WHERE utilisateur_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);

    header("Location: admin.php");
}
?>
