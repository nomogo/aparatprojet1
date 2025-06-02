<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function get_json_input() {
    $input = file_get_contents("php://input");
    return json_decode($input, true);
}

function sanitize($str) {
    return htmlspecialchars(strip_tags(trim($str)));
}

$data = get_json_input();
if (!$data) die(json_encode(["error" => "Invalid input"]));

if (!isset($_SESSION['user_id'])) {
    // inscription
    $login = sanitize($data['login'] ?? '');
    $pass = $data['mot_de_passe'] ?? '';
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    // ... validation + insert en base ...
    $stmt = $pdo->prepare("INSERT INTO users (...) VALUES (...)");
    // ...
    $id = $pdo->lastInsertId();
    echo json_encode(["login" => $login, "mot_de_passe" => $pass, "profile_url" => "profil.php?user=$id"]);
} else {
    // modification
    $id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("UPDATE users SET nom_complet=?, email=? WHERE id=?");
    $stmt->execute([$data['nom_complet'], $data['email'], $id]);
    echo json_encode(["success" => true]);
}