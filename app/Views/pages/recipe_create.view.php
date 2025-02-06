<!DOCTYPE html>
<html lang="sk">

<meta charset="UTF-8">
<title>Pridať nový recept</title>
</head>
<body>
<h1>➕ Pridať nový recept</h1>
<link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/create.css">
<head>

    <form action="/VAII_KULINAR_WEB/public/index.php/recipe/create" method="POST">
        <!-- Názov receptu -->
        <label for="name">Názov receptu:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <!-- Popis receptu -->
        <label for="description">Popis:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <!-- Kroky prípravy -->
        <label for="steps">Postup prípravy:</label><br>
        <textarea id="steps" name="steps" required></textarea><br><br>

        <!-- URL obrázka -->
        <label for="image">URL obrázka (voliteľné):</label><br>
        <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg"><br><br>

        <!-- Ingrediencie -->
        <div id="ingredients-container">
            <div class="ingredient-row">
                <select name="ingredient_id[]" class="ingredient-select" onchange="toggleIngredientFields(this)">
                    <option value="" disabled selected>Vyberte ingredienciu alebo pridajte novú</option>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <option value="<?= $ingredient['id'] ?>"><?= htmlspecialchars($ingredient['name']) ?></option>
                    <?php endforeach; ?>
                    <option value="new">Pridať novú ingredienciu</option>
                </select>

                <input type="text" name="new_ingredient_name[]" class="new-ingredient-name" placeholder="Názov ingrediencie" style="display: none;">
                <input type="text" name="new_ingredient_unit[]" class="new-ingredient-unit" placeholder="Jednotka" style="display: none;">
                <input type="number" name="ingredient_amount[]" class="ingredient-amount" placeholder="Množstvo" required style="display: none;">

                <button type="button" class="remove-ingredient" onclick="removeIngredient(this)">Odstrániť</button>
            </div>
        </div>

        <!-- Tlačidlo "Pridať ingredienciu" nižšie, v strede -->
        <div class="centered-button">
            <button type="button" id="add-ingredient">Pridať ingredienciu</button>
        </div>

        <!-- Tlačidlo "Pridať recept" ešte nižšie, v strede -->
        <div class="centered-button">
            <button type="submit">Pridať recept</button>
        </div>

    </form>

    <a href="/VAII_KULINAR_WEB/public/index.php/recipes">← Späť na zoznam receptov</a>

    <script src="/VAII_KULINAR_WEB/public/assets/js/add-ingredient.js"></script>

</body>
</html>
