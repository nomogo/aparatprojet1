<?php
session_start();
if ($_POST['admin_pass'] ?? '' !== 'admin123') die("Accès refusé");

$pdo = new PDO("mysql:host=localhost;dbname=u68658;charset=utf8", "u68658", "7975806");
$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
$lang_stats = $pdo->query("SELECT langage, COUNT(*) as total FROM user_languages GROUP BY langage")->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Admin</h1>
<table border="1">
<tr><th>ID</th><th>Nom</th><th>Email</th><th>Actions</th></tr>
<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u['id'] ?></td><td><?= $u['nom_complet'] ?></td><td><?= $u['email'] ?></td>
    <td><a href="edit.php?id=<?= $u['id'] ?>">Modifier</a> | <a href="delete.php?id=<?= $u['id'] ?>">Supprimer</a></td>
</tr>
<?php endforeach; ?>
</table>
<h2>Statistiques</h2>
<ul>
<?php foreach ($lang_stats as $row): ?>
<li><?= $row['langage'] ?>: <?= $row['total'] ?> utilisateurs</li>
<?php endforeach; ?>
</ul>