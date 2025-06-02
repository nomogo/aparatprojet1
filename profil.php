<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=site;charset=utf8", "root", "");
$id = $_SESSION["user_id"];

$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$id]);
$data = $user->fetch();

$langs = $pdo->prepare("SELECT langage FROM user_languages WHERE utilisateur_id = ?");
$langs->execute([$id]);
$liste = $langs->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head><title>Mon Profil</title></head>
<body>
    <h2>Bienvenue <?= htmlspecialchars($data["nom_complet"]) ?></h2>
    <p>Email : <?= htmlspecialchars($data["email"]) ?></p>
    <p>Téléphone : <?= htmlspecialchars($data["telephone"]) ?></p>
    <p>Genre : <?= $data["genre"] ?></p>
    <p>Bio : <?= nl2br(htmlspecialchars($data["biographie"])) ?></p>
    <p>Langages préférés : <?= implode(", ", $liste) ?></p>
    <p><a href="edit.php">Modifier mes informations</a></p>
</body>
</html>
