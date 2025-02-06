document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.trim();

            const url = query.length === 0
                ? '/VAII_KULINAR_WEB/public/index.php/recipes'
                : `/VAII_KULINAR_WEB/public/index.php/recipes/search?query=${encodeURIComponent(query)}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const recipeList = document.getElementById('recipe-list');
                    recipeList.innerHTML = ''; // Очистка списка

                    if (data.length === 0) {
                        recipeList.innerHTML = '<p>Нič sa nenašlo.</p>';
                    } else {
                        data.forEach(recipe => {
                            // ✅ Создание контейнера для каждой карточки
                            const card = document.createElement('div');
                            card.classList.add('recipe-card');

                            // 📦 Внутренний HTML для рецепта
                            card.innerHTML = `
                                <h3 class="recipe-title">
                                    <a href="/VAII_KULINAR_WEB/public/index.php/recipe/${recipe.id}" class="recipe-link">${recipe.name}</a>
                                </h3>
                                <p class="recipe-description">${recipe.description}</p>
                                <div class="recipe-actions">
                                    <button class="button-edit" onclick="location.href='/VAII_KULINAR_WEB/public/index.php/recipe/edit/${recipe.id}'">✏️ Upraviť</button>
                                    <button class="button-delete" onclick="if(confirm('Naozaj chcete vymazať tento recept?')) location.href='/VAII_KULINAR_WEB/public/index.php/recipe/delete/${recipe.id}'">❌ Vymazať</button>
                                </div>
                            `;

                            // ✅ Добавление карточки в список
                            recipeList.appendChild(card);
                        });
                    }
                })
                .catch(error => console.error('Chyba pri načítaní receptov:', error));
        });
    }
});
