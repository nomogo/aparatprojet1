<?php
include "bdd.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

function clean($x) {
    return htmlspecialchars(trim($x));
}

if (!isset($_SESSION['user_id'])) {
    // INSCRIPTION
    if (empty($data['login']) || empty($data['mot_de_passe'])) {
        http_response_code(400);
        echo json_encode(["error" => "Login ou mot de passe manquant."]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->execute([$data['login']]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(["error" => "Login déjà utilisé."]);
        exit;
    }

    $hash = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (nom_complet, telephone, email, date_naissance, genre, biographie, accord, login, mot_de_passe_hash)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        clean($data["nom_complet"]),
        clean($data["telephone"]),
        clean($data["email"]),
        $data["date_naissance"],
        clean($data["genre"]),
        clean($data["biographie"]),
        isset($data["accord"]) ? 1 : 0,
        clean($data["login"]),
        $hash
    ]);
    $id = $pdo->lastInsertId();

    if (!empty($data["langages"])) {
        foreach ($data["langages"] as $lang) {
            $pdo->prepare("INSERT INTO user_languages (utilisateur_id, langage) VALUES (?, ?)")
                ->execute([$id, clean($lang)]);
        }
    }

    echo json_encode([
        "login" => $data["login"],
        "mot_de_passe" => $data["mot_de_passe"],
        "profil" => "profil.php?id=$id"
    ]);
} else {
    // MODIFICATION
    $id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("UPDATE users SET nom_complet = ?, telephone = ?, email = ?, date_naissance = ?, genre = ?, biographie = ?, accord = ? WHERE id = ?");
    $stmt->execute([
        clean($data["nom_complet"]),
        clean($data["telephone"]),
        clean($data["email"]),
        $data["date_naissance"],
        clean($data["genre"]),
        clean($data["biographie"]),
        isset($data["accord"]) ? 1 : 0,
        $id
    ]);

    $pdo->prepare("DELETE FROM user_languages WHERE utilisateur_id = ?")->execute([$id]);
    if (!empty($data["langages"])) {
        foreach ($data["langages"] as $lang) {
            $pdo->prepare("INSERT INTO user_languages (utilisateur_id, langage) VALUES (?, ?)")
                ->execute([$id, clean($lang)]);
        }
    }

    echo json_encode(["success" => true, "id" => $id, "message" => "Profil mis à jour"]);
}
?>
