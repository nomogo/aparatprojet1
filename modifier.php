<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$pdo = new PDO("mysql:host=localhost;dbname=site;charset=utf8", "root", "");
$id = $_SESSION["user_id"];
$user = $pdo->query("SELECT * FROM users WHERE id = $id")->fetch();
$langs = $pdo->query("SELECT langage FROM user_languages WHERE utilisateur_id = $id")->fetchAll(PDO::FETCH_COLUMN);
?>

<h2>Modifier vos informations</h2>
<pre><?= print_r($user, true) ?></pre>
<pre>Langages : <?= implode(", ", $langs) ?></pre>
