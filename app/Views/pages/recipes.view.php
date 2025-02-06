<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <title>🍽️ Список рецептов</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/recipes.css">
</head>
<body>

<?php include 'header.view.php'; ?>

<!-- 🔹 Всплывающее сообщение об успешном удалении -->
<div id="success-message" class="success-message" style="display: none;">
    ✅ Рецепт успешно удалён!
</div>

<main>
    <section class="search-section">
        <input type="text" id="search" placeholder="🔍 Поиск рецепта..." class="search-box">
    </section>

    <section class="recipe-list-section">
        <div class="recipe-container">
            <ul id="recipe-list" class="recipe-list">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="recipe-card" id="recipe-<?= $recipe['id'] ?>">
                        <a href="/VAII_KULINAR_WEB/public/index.php/recipe/<?= $recipe['id'] ?>" class="recipe-link">

                            <!-- Отображение изображения только если оно есть -->
                            <?php if (!empty($recipe['image'])): ?>
                                <img src="<?= htmlspecialchars($recipe['image']) ?>"
                                     alt="<?= htmlspecialchars($recipe['name']) ?>"
                                     class="recipe-image"
                                     style="max-width: 200px; height: auto; border-radius: 8px;">
                            <?php else: ?>
                                <!-- Placeholder для рецептов без изображения -->
                                <img src="/VAII_KULINAR_WEB/public/assets/images/placeholder.png"
                                     alt="Нет изображения"
                                     class="recipe-image"
                                     style="max-width: 150px; height: auto; border-radius: 8px;">
                            <?php endif; ?>

                            <h3 class="recipe-title"><?= htmlspecialchars($recipe['name']) ?></h3>
                        </a>

                        <p class="recipe-description"><?= htmlspecialchars($recipe['description']) ?></p>

                        <div class="recipe-actions">
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
                                <a href="/VAII_KULINAR_WEB/public/index.php/recipe/edit/<?= $recipe['id'] ?>" class="button-edit">✏️ Upraviť</a>
                                <button class="button-delete" data-id="<?= $recipe['id'] ?>">❌ Vymazať</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
</main>

<?php include 'footer.view.php'; ?>

<!-- Подключаем delete.js для AJAX-удаления рецептов -->
<script src="/VAII_KULINAR_WEB/public/assets/js/delete.js"></script>
<script src="/VAII_KULINAR_WEB/public/assets/js/liveSearch.js"></script>

<!-- 🔹 Стили для всплывающего сообщения -->
<style>
    .success-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
        z-index: 1000;
    }
</style>

</body>
</html>
