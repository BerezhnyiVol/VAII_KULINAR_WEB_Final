<!DOCTYPE html>
<html lang="ru">


    <meta charset="UTF-8">
    <title>Добавить новый рецепт</title>
</head>
<body>
<h1>➕ Добавить новый рецепт</h1>
<link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/create.css">
<head>

<form action="/VAII_KULINAR_WEB/public/index.php/recipe/create" method="POST">
    <!-- Название рецепта -->
    <label for="name">Название рецепта:</label><br>
    <input type="text" id="name" name="name" required><br><br>

    <!-- Описание рецепта -->
    <label for="description">Описание:</label><br>
    <textarea id="description" name="description" required></textarea><br><br>

    <!-- Шаги приготовления -->
    <label for="steps">Шаги приготовления:</label><br>
    <textarea id="steps" name="steps" required></textarea><br><br>

    <!-- URL изображения -->
    <label for="image">URL изображения (необязательно):</label><br>
    <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg"><br><br>

    <!-- Ингредиенты -->
    <div id="ingredients-container">
        <div class="ingredient-row">
            <select name="ingredient_id[]" class="ingredient-select" onchange="toggleIngredientFields(this)">
                <option value="" disabled selected>Выберите ингредиент или добавьте новый</option>
                <?php foreach ($ingredients as $ingredient): ?>
                    <option value="<?= $ingredient['id'] ?>"><?= htmlspecialchars($ingredient['name']) ?></option>
                <?php endforeach; ?>
                <option value="new">Добавить новый ингредиент</option>
            </select>

            <input type="text" name="new_ingredient_name[]" class="new-ingredient-name" placeholder="Название ингредиента" style="display: none;">
            <input type="text" name="new_ingredient_unit[]" class="new-ingredient-unit" placeholder="Единица измерения" style="display: none;">
            <input type="number" name="ingredient_amount[]" class="ingredient-amount" placeholder="Количество" required style="display: none;">

            <button type="button" class="remove-ingredient" onclick="removeIngredient(this)">Удалить</button>
        </div>
    </div>

    <!-- Кнопка "Добавить ингредиент" ниже, по центру -->
    <div class="centered-button">
        <button type="button" id="add-ingredient">Добавить ингредиент</button>
    </div>

    <!-- Кнопка "Добавить рецепт" еще ниже, по центру -->
    <div class="centered-button">
        <button type="submit">Добавить рецепт</button>
    </div>


</form>

<a href="/VAII_KULINAR_WEB/public/index.php/recipes">← Вернуться к списку рецептов</a>

<script src="/VAII_KULINAR_WEB/public/assets/js/add-ingredient.js"></script>

</body>
</html>
