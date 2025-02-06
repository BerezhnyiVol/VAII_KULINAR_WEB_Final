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
                    recipeList.innerHTML = '';

                    if (data.length === 0) {
                        recipeList.innerHTML = '<p>Нič sa nenašlo.</p>';
                    } else {
                        data.forEach(recipe => {
                            const imageUrl = recipe.image && recipe.image.trim() !== ''
                                ? (recipe.image.startsWith('http') ? recipe.image : `/VAII_KULINAR_WEB/public/uploads/${recipe.image}`)
                                : '/VAII_KULINAR_WEB/public/assets/images/placeholder.png';

                            const card = document.createElement('div');
                            card.classList.add('recipe-card');
                            card.setAttribute('id', `recipe-${recipe.id}`);

                            card.innerHTML = `
                                <img src="${imageUrl}" alt="${recipe.name}" class="recipe-image">
                                <h3 class="recipe-title">
                                    <a href="/VAII_KULINAR_WEB/public/index.php/recipe/${recipe.id}" class="recipe-link">${recipe.name}</a>
                                </h3>
                                <p class="recipe-description">${recipe.description}</p>
                                <div class="recipe-actions" id="admin-actions-${recipe.id}"></div>
                            `;

                            recipeList.appendChild(card);
                        });

                        // 🔹 Вызываем функцию для добавления кнопок (если админ)
                        addAdminButtons();
                    }
                })
                .catch(error => console.error('Chyba pri načítaní receptov:', error));
        });
    }
});

// ✅ Функция добавления кнопок администратора
function addAdminButtons() {
    fetch('/VAII_KULINAR_WEB/public/index.php/user/role')
        .then(response => response.json())
        .then(user => {
            if (user.role === 'admin') {
                document.querySelectorAll('.recipe-card').forEach(card => {
                    const recipeId = card.getAttribute('id').replace('recipe-', '');
                    const actionsDiv = card.querySelector('.recipe-actions');
                    if (actionsDiv) {
                        actionsDiv.innerHTML = `
                            <button class="button-edit" onclick="location.href='/VAII_KULINAR_WEB/public/index.php/recipe/edit/${recipeId}'">✏️ Upraviť</button>
                            <button class="button-delete" data-id="${recipeId}">❌ Vymazať</button>
                        `;
                    }
                });
            }
        })
        .catch(error => console.error("Ошибка при загрузке роли пользователя:", error));
}
