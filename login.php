<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["mot_de_passe"];

    $pdo = new PDO("mysql:host=localhost;dbname=site;charset=utf8", "root", "");
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["mot_de_passe_hash"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["is_admin"] = ($user["login"] === "admin");
        header("Location: " . ($user["login"] === "admin" ? "admin.php" : "modifier.php"));
        exit();
    } else {
        $erreur = "Identifiants invalides.";
    }
}
?>

<form method="post">
    <input name="login" required placeholder="Login">
    <input name="mot_de_passe" type="password" required placeholder="Mot de passe">
    <button type="submit">Se connecter</button>
    <?php if (!empty($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
</form>
