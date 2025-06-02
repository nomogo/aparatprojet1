<?php
session_start();
$erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    // Connexion à la BDD
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    // Vérifier les identifiants
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: modifier.php");
        exit();
    } else {
        $erreur = "Login ou mot de passe incorrect.";
    }
}
?>

<!-- Formulaire de connexion -->
<form method="POST">
    <label>Login : <input type="text" name="login" required></label><br>
    <label>Mot de passe : <input type="password" name="mot_de_passe" required></label><br>
    <button type="submit">Se connecter</button>
</form>

<?php if ($erreur) echo "<p style='color:red;'>$erreur</p>"; ?>
