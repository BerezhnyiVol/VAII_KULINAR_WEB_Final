<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <title>🍽️ Список рецептов</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/recipes.css">
</head>
<body>

<?php include 'header.view.php'; ?>

<main>
    <section class="search-section">
        <input type="text" id="search" placeholder="🔍 Поиск рецепта..." class="search-box">
    </section>

    <section class="recipe-list-section">
        <div class="recipe-container">
            <ul id="recipe-list" class="recipe-list">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="recipe-card">
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
                                     style="max-width: 150px; height: auto; border-radius: 8px;">>
                            <?php endif; ?>

                            <h3 class="recipe-title"><?= htmlspecialchars($recipe['name']) ?></h3>
                        </a>

                        <p class="recipe-description"><?= htmlspecialchars($recipe['description']) ?></p>

                        <div class="recipe-actions">
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
                                <a href="/VAII_KULINAR_WEB/public/index.php/recipe/edit/<?= $recipe['id'] ?>" class="button-edit">✏️ Upraviť</a>
                                <a href="/VAII_KULINAR_WEB/public/index.php/recipe/delete/<?= $recipe['id'] ?>" class="button-delete" ">❌ Vymazať</a>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
</main>

<?php include 'footer.view.php'; ?>
<script src="/VAII_KULINAR_WEB/public/assets/js/delete.js"></script>
<script src="/VAII_KULINAR_WEB/public/assets/js/liveSearch.js"></script>
</body>
</html>
