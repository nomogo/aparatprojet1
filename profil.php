<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la BDD
try {
    $pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$id = $_SESSION['user_id'];

// Charger les données
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

// Traitement de la mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_complet = $_POST["nom_complet"];
    $telephone = $_POST["telephone"];
    $email = $_POST["email"];
    $biographie = $_POST["biographie"];

    $stmt = $pdo->prepare("UPDATE users SET nom_complet = ?, telephone = ?, email = ?, biographie = ? WHERE id = ?");
    $stmt->execute([$nom_complet, $telephone, $email, $biographie, $id]);

    echo "<p style='color:green;'>Données mises à jour !</p>";
}
?>

<!-- Formulaire de modification -->
<form method="POST">
    <label>Nom complet : <input type="text" name="nom_complet" value="<?= htmlspecialchars($user['nom_complet']) ?>"></label><br>
    <label>Téléphone : <input type="text" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>"></label><br>
    <label>Email : <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"></label><br>
    <label>Biographie : <textarea name="biographie"><?= htmlspecialchars($user['biographie']) ?></textarea></label><br>
    <button type="submit">Mettre à jour</button>
</form>
