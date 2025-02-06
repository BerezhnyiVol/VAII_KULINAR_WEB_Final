<h3>Ingrediencie</h3>

<form action="/VAII_KULINAR_WEB/public/index.php/ingredients/store" method="POST" id="ingredient-form">
    <div id="ingredients-container">
        <div class="ingredient-row">
            <select name="ingredient_id[]" class="ingredient-select" onchange="toggleNewIngredientFields(this)">
                <option value="">Vyberte ingredienciu alebo pridajte novú</option>
                <?php foreach ($ingredients as $ingredient): ?>
                    <option value="<?= $ingredient['id'] ?>"><?= htmlspecialchars($ingredient['name']) ?></option>
                <?php endforeach; ?>
                <option value="new">Pridať novú ingredienciu</option>
            </select>

            <input type="text" name="name[]" class="new-ingredient-name" placeholder="Názov novej ingrediencie" style="display: none;">
            <input type="text" name="unit[]" class="new-ingredient-unit" placeholder="Jednotka" style="display: none;">
            <input type="number" name="amount[]" class="ingredient-amount" placeholder="Množstvo">
            <button type="button" class="remove-ingredient" onclick="removeIngredient(this)">Odstrániť</button>
        </div>
    </div>

    <button type="button" id="add-ingredient-btn" onclick="addIngredientRow()">Pridať ingredienciu</button>
    <button type="submit">Uložiť ingrediencie</button>
</form>
