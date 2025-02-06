<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <title>🍽️ Zoznam receptov</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/recipes.css">
</head>
<body>

<?php include 'header.view.php'; ?>

<!-- 🔹 Vyskakovacia správa o úspešnom odstránení -->
<div id="success-message" class="success-message" style="display: none;">
    ✅ Recept bol úspešne odstránený!
</div>

<main>
    <section class="search-section">
        <input type="text" id="search" placeholder="🔍 Hľadať recept..." class="search-box">
    </section>

    <section class="recipe-list-section">
        <div class="recipe-container">
            <ul id="recipe-list" class="recipe-list">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="recipe-card" id="recipe-<?= $recipe['id'] ?>">
                        <a href="/VAII_KULINAR_WEB/public/index.php/recipe/<?= $recipe['id'] ?>" class="recipe-link">

                            <!-- Zobrazenie obrázka iba ak existuje -->
                            <?php if (!empty($recipe['image'])): ?>
                                <img src="<?= htmlspecialchars($recipe['image']) ?>"
                                     alt="<?= htmlspecialchars($recipe['name']) ?>"
                                     class="recipe-image"
                                     style="max-width: 200px; height: auto; border-radius: 8px;">
                            <?php else: ?>
                                <!-- Placeholder pre recepty bez obrázka -->
                                <img src="/VAII_KULINAR_WEB/public/assets/images/placeholder.png"
                                     alt="Žiadny obrázok"
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

<!-- Pripojenie delete.js pre AJAX odstránenie receptov -->
<script src="/VAII_KULINAR_WEB/public/assets/js/delete.js"></script>
<script src="/VAII_KULINAR_WEB/public/assets/js/liveSearch.js"></script>

<!-- 🔹 Štýly pre vyskakovaciu správu -->
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
