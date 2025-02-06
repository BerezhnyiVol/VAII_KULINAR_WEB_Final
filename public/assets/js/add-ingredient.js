document.getElementById('add-ingredient').addEventListener('click', function () {
    const container = document.getElementById('ingredients-container');

    // Получаем список ингредиентов из первого селекта
    const firstSelect = document.querySelector('.ingredient-select');
    const options = firstSelect ? firstSelect.innerHTML : '';

    const row = document.createElement('div');
    row.classList.add('ingredient-row');

    row.innerHTML = `
        <select name="ingredient_id[]" class="ingredient-select" onchange="toggleIngredientFields(this)">
            ${options}  <!-- Теперь копируем ВСЕ опции из первого select -->
        </select>

        <input type="text" name="new_ingredient_name[]" class="new-ingredient-name" placeholder="Название ингредиента" style="display: none;">
        <input type="text" name="new_ingredient_unit[]" class="new-ingredient-unit" placeholder="Единица измерения" style="display: none;">
        <input type="number" name="ingredient_amount[]" class="ingredient-amount" placeholder="Количество" required style="display: none;">

        <button type="button" class="remove-ingredient" onclick="removeIngredient(this)">Удалить</button>
    `;

    container.appendChild(row);
});

// Показываем нужные поля в зависимости от выбора
function toggleIngredientFields(selectElement) {
    const row = selectElement.closest('.ingredient-row');
    const isNew = selectElement.value === 'new';

    row.querySelector('.new-ingredient-name').style.display = isNew ? 'inline-block' : 'none';
    row.querySelector('.new-ingredient-unit').style.display = isNew ? 'inline-block' : 'none';
    row.querySelector('.ingredient-amount').style.display = isNew || selectElement.value !== "" ? 'inline-block' : 'none';
}

// Удаление строки ингредиента
function removeIngredient(button) {
    button.closest('.ingredient-row').remove();
}
