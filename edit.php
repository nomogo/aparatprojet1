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

$langQuery = $pdo->prepare("SELECT langage FROM user_languages WHERE utilisateur_id = ?");
$langQuery->execute([$id]);
$langages = $langQuery->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier mon profil</title>
</head>
<body>
    <h2>Modifier mes informations</h2>
    <form id="edit-form">
        <input name="nom_complet" value="<?= htmlspecialchars($data["nom_complet"]) ?>" required><br>
        <input name="telephone" value="<?= htmlspecialchars($data["telephone"]) ?>"><br>
        <input name="email" value="<?= htmlspecialchars($data["email"]) ?>" required><br>
        <input name="date_naissance" type="date" value="<?= $data["date_naissance"] ?>"><br>
        <select name="genre">
            <option value="masculin" <?= $data["genre"] == "masculin" ? "selected" : "" ?>>Masculin</option>
            <option value="feminin" <?= $data["genre"] == "feminin" ? "selected" : "" ?>>Féminin</option>
        </select><br>
        <textarea name="biographie"><?= htmlspecialchars($data["biographie"]) ?></textarea><br>
        <label><input type="checkbox" name="accord" <?= $data["accord"] ? "checked" : "" ?>> J'accepte</label><br>
        <label>Langages :
            <select name="langages[]" multiple>
                <?php
                $all = ["PHP", "Python", "JavaScript", "Java", "C++", "Go"];
                foreach ($all as $lang) {
                    $selected = in_array($lang, $langages) ? "selected" : "";
                    echo "<option $selected>$lang</option>";
                }
                ?>
            </select>
        </label><br>
        <button type="submit">Enregistrer</button>
    </form>
    <div id="message"></div>
    <script>
        document.getElementById("edit-form").addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            data["langages"] = formData.getAll("langages[]");

            fetch("api.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            }).then(r => r.json())
              .then(j => document.getElementById("message").innerText = "Profil mis à jour.");
        });
    </script>
</body>
</html>
