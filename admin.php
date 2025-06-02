<?php
// === HTTP AUTH ===
$admin_login = 'admin';
$admin_password = 'secret';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $admin_login || $_SERVER['PHP_AUTH_PW'] !== $admin_password) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Accès refusé';
    exit;
}

// === Connexion à la base de données ===
$pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// === Supprimer un utilisateur si demandé ===
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM user_languages WHERE utilisateur_id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
    header("Location: admin.php");
    exit;
}

// === Lire tous les utilisateurs ===
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

// === Lire les préférences de langages ===
$stats = $pdo->query("SELECT langage, COUNT(*) as total FROM user_languages GROUP BY langage")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>
    <h1>Zone administrateur</h1>

    <h2>Utilisateurs enregistrés</h2>
    <table>
        <tr>
            <th>ID</th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Naissance</th>
            <th>Genre</th><th>Biographie</th><th>Login</th><th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['nom_complet']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['telephone']) ?></td>
            <td><?= htmlspecialchars($user['date_naissance']) ?></td>
            <td><?= htmlspecialchars($user['genre']) ?></td>
            <td><?= htmlspecialchars($user['biographie']) ?></td>
            <td><?= htmlspecialchars($user['login']) ?></td>
            <td>
                <a href="edit.php?id=<?= $user['id'] ?>">Modifier</a> |
                <a href="admin.php?delete=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Statistiques des langages de programmation</h2>
    <table>
        <tr><th>Langage</th><th>Nombre d'utilisateurs</th></tr>
        <?php foreach ($stats as $stat): ?>
        <tr>
            <td><?= htmlspecialchars($stat['langage']) ?></td>
            <td><?= $stat['total'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
