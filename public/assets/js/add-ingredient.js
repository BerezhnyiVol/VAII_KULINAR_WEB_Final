document.getElementById('add-ingredient').addEventListener('click', function () {
    const container = document.getElementById('ingredients-container');

    // Získame zoznam ingrediencií z prvého výberového poľa
    const firstSelect = document.querySelector('.ingredient-select');
    const options = firstSelect ? firstSelect.innerHTML : '';

    const row = document.createElement('div');
    row.classList.add('ingredient-row');

    row.innerHTML = `
        <select name="ingredient_id[]" class="ingredient-select" onchange="toggleIngredientFields(this)">
            ${options}  <!-- Teraz kopírujeme VŠETKY možnosti z prvého select -->
        </select>

        <input type="text" name="new_ingredient_name[]" class="new-ingredient-name" placeholder="Názov ingrediencie" style="display: none;">
        <input type="text" name="new_ingredient_unit[]" class="new-ingredient-unit" placeholder="Jednotka merania" style="display: none;">
        <input type="number" name="ingredient_amount[]" class="ingredient-amount" placeholder="Množstvo" required style="display: none;">

        <button type="button" class="remove-ingredient" onclick="removeIngredient(this)">Odstrániť</button>
    `;

    container.appendChild(row);
});

// Zobrazenie polí podľa výberu
function toggleIngredientFields(selectElement) {
    const row = selectElement.closest('.ingredient-row');
    const isNew = selectElement.value === 'new';

    row.querySelector('.new-ingredient-name').style.display = isNew ? 'inline-block' : 'none';
    row.querySelector('.new-ingredient-unit').style.display = isNew ? 'inline-block' : 'none';
    row.querySelector('.ingredient-amount').style.display = isNew || selectElement.value !== "" ? 'inline-block' : 'none';
}

// Odstránenie ingrediencie
function removeIngredient(button) {
    button.closest('.ingredient-row').remove();
}
