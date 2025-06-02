<?php
session_start();
$user_data = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script src="form-handler.js" defer></script>
</head>
<body>
<h1>Formulaire d'inscription</h1>
<form id="registerForm" method="POST" action="api.php">
    <label>Nom complet: <input type="text" name="nom_complet" value="<?= htmlspecialchars($user_data['nom_complet'] ?? '') ?>" required></label><br>
    <label>Téléphone: <input type="text" name="telephone" value="<?= htmlspecialchars($user_data['telephone'] ?? '') ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($user_data['email'] ?? '') ?>" required></label><br>
    <label>Date de naissance: <input type="date" name="date_naissance" value="<?= htmlspecialchars($user_data['date_naissance'] ?? '') ?>" required></label><br>
    <label>Genre:
        <select name="genre">
            <option value="masculin" <?= (isset($user_data['genre']) && $user_data['genre'] === 'masculin') ? 'selected' : '' ?>>Masculin</option>
            <option value="feminin" <?= (isset($user_data['genre']) && $user_data['genre'] === 'feminin') ? 'selected' : '' ?>>Féminin</option>
        </select>
    </label><br>
    <label>Biographie:<br><textarea name="biographie"><?= htmlspecialchars($user_data['biographie'] ?? '') ?></textarea></label><br>
    <label>Langages de programmation:<br>
        <input type="checkbox" name="langages[]" value="PHP"> PHP
        <input type="checkbox" name="langages[]" value="Python"> Python
        <input type="checkbox" name="langages[]" value="Java"> Java
        <input type="checkbox" name="langages[]" value="JavaScript"> JavaScript
    </label><br>
    <label><input type="checkbox" name="accord" value="1" checked> J'accepte les conditions</label><br>
    <?php if (!$user_data): ?>
        <label>Login: <input type="text" name="login" required></label><br>
        <label>Mot de passe: <input type="password" name="mot_de_passe" required></label><br>
    <?php endif; ?>
    <button type="submit">Envoyer</button>
</form>
<?php if ($user_data): ?>
    <p><a href="logout.php">Se déconnecter</a></p>
<?php endif; ?>
</body>
</html>

