<?php
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/Models/Recipe.php';

// Проверка подключения
$db = Database::getInstance();
$conn = $db->getConnection();

if ($conn) {
    echo "✅ Подключение к базе данных прошло успешно!<br>";
}

// Проверка модели рецептов
$recipeModel = new Recipe();
$recipes = $recipeModel->getAllRecipes();

echo "<h3>Список рецептов:</h3>";
foreach ($recipes as $recipe) {
    echo "🍽️ " . htmlspecialchars($recipe['name']) . "<br>";
}
