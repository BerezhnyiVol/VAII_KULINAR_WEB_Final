<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <title>✏️ Upraviť recept</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/edit.css">
</head>
<body>

<h1>✏️ Upraviť recept</h1>

<form action="/VAII_KULINAR_WEB/public/index.php/recipe/edit/<?= $recipe['id'] ?>" method="POST">
    <label for="name">Название рецепта:</label><br>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($recipe['name']) ?>" required><br><br>

    <label for="description">Описание:</label><br>
    <textarea id="description" name="description" required><?= htmlspecialchars($recipe['description']) ?></textarea><br><br>

    <label for="steps">Шаги приготовления:</label><br>
    <textarea id="steps" name="steps" required><?= htmlspecialchars($recipe['steps']) ?></textarea><br><br>

    <!-- Поле для ввода URL изображения -->
    <label for="image">Ссылка на изображение (URL):</label><br>
    <input type="text" id="image" name="image" value="<?= htmlspecialchars($recipe['image']) ?>"><br><br>

    <button type="submit">💾 Сохранить изменения</button>
</form>

<a href="/VAII_KULINAR_WEB/public/index.php/recipes">← Вернуться к списку рецептов</a>

</body>
</html>
