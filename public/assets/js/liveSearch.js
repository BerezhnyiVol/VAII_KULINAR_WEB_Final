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
                            // ✅ Определяем URL изображения (если нет - ставим placeholder.png)
                            const imageUrl = recipe.image && recipe.image.trim() !== ''
                                ? (recipe.image.startsWith('http') ? recipe.image : `/VAII_KULINAR_WEB/public/uploads/${recipe.image}`)
                                : '/VAII_KULINAR_WEB/public/assets/images/placeholder.png';

                            // ✅ Создание контейнера для каждой карточки
                            const card = document.createElement('div');
                            card.classList.add('recipe-card');

                            // 📦 Внутренний HTML для рецепта
                            card.innerHTML = `
                                <img src="${imageUrl}" alt="${recipe.name}" class="recipe-image"
                                     onerror="this.onerror=null; this.src='/VAII_KULINAR_WEB/public/assets/images/placeholder.png';">
                                <h3 class="recipe-title">
                                    <a href="/VAII_KULINAR_WEB/public/index.php/recipe/${recipe.id}" class="recipe-link">${recipe.name}</a>
                                </h3>
                                <p class="recipe-description">${recipe.description}</p>
                                <div class="recipe-actions" id="admin-actions-${recipe.id}"></div>
                            `;

                            // ✅ Добавление карточки в список
                            recipeList.appendChild(card);

                            // 🔹 Проверяем, админ ли текущий пользователь
                            fetch('/VAII_KULINAR_WEB/public/index.php/user/role')
                                .then(response => response.json())
                                .then(user => {
                                    if (user.role === 'admin') {
                                        const actionsDiv = document.getElementById(`admin-actions-${recipe.id}`);
                                        actionsDiv.innerHTML = `
                                            <button class="button-edit" onclick="location.href='/VAII_KULINAR_WEB/public/index.php/recipe/edit/${recipe.id}'">✏️ Upraviť</button>
                                            <button class="button-delete" data-recipe-id="<?= $recipe['id'] ?>">❌ Vymazať</button>


                                        `;
                                    }
                                })
                                .catch(error => console.error('Chyba pri načítaní roly používateľa:', error));
                        });
                    }
                })
                .catch(error => console.error('Chyba pri načítaní receptov:', error));
        });
    }
});
