<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <title>✏️ Upraviť recept</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/edit.css">
</head>
<body>

<h1>✏️ Upraviť recept</h1>
<?php if (!empty($recipe['image'])): ?>
    <img src="<?= htmlspecialchars($recipe['image']) ?>" alt="Obrázok receptu" class="recipe-image">
<?php endif; ?>

<form action="/VAII_KULINAR_WEB/public/index.php/recipe/edit/<?= $recipe['id'] ?>" method="POST">
    <label for="name">Názov receptu:</label><br>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($recipe['name']) ?>" required><br><br>

    <label for="description">Popis:</label><br>
    <textarea id="description" name="description" required><?= htmlspecialchars($recipe['description']) ?></textarea><br><br>

    <label for="steps">Postup prípravy:</label><br>
    <textarea id="steps" name="steps" required><?= htmlspecialchars($recipe['steps']) ?></textarea><br><br>

    <!-- Pole na vloženie URL obrázka -->
    <label for="image">Odkaz na obrázok (URL):</label><br>
    <input type="text" id="image" name="image" value="<?= htmlspecialchars($recipe['image']) ?>"><br><br>

    <button type="submit">💾 Uložiť zmeny</button>
</form>

<a href="/VAII_KULINAR_WEB/public/index.php/recipes">← Späť na zoznam receptov</a>

</body>
</html>
