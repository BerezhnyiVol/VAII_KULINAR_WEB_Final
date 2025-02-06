<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <title>Pridať nový recept</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/create.css">
</head>

<body>
<div class="container">
    <h1>➕ Pridať nový recept</h1>
    <form action="/VAII_KULINAR_WEB/public/index.php/recipe/create" method="POST">
        <!-- Názov receptu -->
        <div class="form-group">
            <label for="name">Názov receptu:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <!-- Popis receptu -->
        <div class="form-group">
            <label for="description">Popis:</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <!-- Kroky prípravy -->
        <div class="form-group">
            <label for="steps">Postup prípravy:</label>
            <textarea id="steps" name="steps" required></textarea>
        </div>

        <!-- URL obrázka -->
        <div class="form-group">
            <label for="image">URL obrázka (voliteľné):</label>
            <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg">
        </div>

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

    <a href="/VAII_KULINAR_WEB/public/index.php/recipes" class="back-link">← Späť na zoznam receptov</a>
</div>

<script src="/VAII_KULINAR_WEB/public/assets/js/add-ingredient.js"></script>
</body>

</html>