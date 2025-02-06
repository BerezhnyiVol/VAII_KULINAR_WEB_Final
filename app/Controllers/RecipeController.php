<?php

require_once __DIR__ . '/../Models/Recipe.php';
require_once __DIR__ . '/../Models/Ingredient.php';  // Добавить этот путь
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class RecipeController {
    private $recipeModel;
    private $ingredientModel;
    public function __construct() {
        $this->recipeModel = new Recipe();
        $this->ingredientModel = new Ingredient();
    }


    // Метод для сохранения рецепта
    public function store() {
        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            // Получаем данные из формы
            $name = $_POST['name'] ?? null;
            $description = $_POST['description'] ?? null;
            $steps = $_POST['steps'] ?? null;
            $image = $_POST['image'] ?? null;

            if (!$name || !$description || !$steps) {
                throw new Exception("Некоторые обязательные поля не заполнены.");
            }

            // 1️⃣ Добавляем рецепт
            $stmt = $db->prepare("INSERT INTO recipes (name, description, steps, image) VALUES (:name, :description, :steps, :image)");
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':steps' => $steps,
                ':image' => $image
            ]);
            $recipeId = $db->lastInsertId();

            // 2️⃣ Работа с ингредиентами
            $existingIngredients = $_POST['ingredient_id'] ?? [];
            $amounts = $_POST['ingredient_amount'] ?? [];
            $newIngredientNames = $_POST['new_ingredient_name'] ?? [];
            $newIngredientUnits = $_POST['new_ingredient_unit'] ?? [];
            $newAmounts = $_POST['new_ingredient_amount'] ?? [];

            $newIngredientIds = []; // Список ID новых ингредиентов

            // 3️⃣ Добавляем новые ингредиенты в `ingredients`
            foreach ($newIngredientNames as $index => $newIngredientName) {
                if (!empty($newIngredientName)) {
                    $newUnit = $newIngredientUnits[$index] ?? '';

                    // Вставляем новый ингредиент в `ingredients`
                    $stmt = $db->prepare("INSERT INTO ingredients (name, unit) VALUES (:name, :unit)");
                    $stmt->execute([
                        ':name' => $newIngredientName,
                        ':unit' => $newUnit
                    ]);
                    $newIngredientId = $db->lastInsertId();

                    // Добавляем ID нового ингредиента в массив
                    $newIngredientIds[] = [
                        'id' => $newIngredientId,
                        'amount' => $newAmounts[$index] ?? ''
                    ];
                }
            }

            // 4️⃣ Объединяем ID существующих и новых ингредиентов
            $finalIngredients = [];

            // Сначала добавляем существующие ингредиенты
            foreach ($existingIngredients as $index => $ingredientId) {
                if (!empty($ingredientId) && is_numeric($ingredientId) && !empty($amounts[$index])) {
                    $finalIngredients[] = [
                        'id' => $ingredientId,
                        'amount' => $amounts[$index]
                    ];
                }
            }

            // Добавляем новые ингредиенты
            $finalIngredients = array_merge($finalIngredients, $newIngredientIds);

            // 5️⃣ Записываем все ингредиенты в `recipe_ingredients`
            foreach ($finalIngredients as $ingredient) {
                $stmt = $db->prepare("INSERT INTO recipe_ingredients (recipe_id, ingredient_id, amount) VALUES (:recipe_id, :ingredient_id, :amount)");
                $stmt->execute([
                    ':recipe_id' => $recipeId,
                    ':ingredient_id' => $ingredient['id'],
                    ':amount' => $ingredient['amount']
                ]);
            }

            $db->commit();
            header('Location: /VAII_KULINAR_WEB/public/index.php/recipes');
            exit();

        } catch (Exception $e) {
            $db->rollBack();
            die("Ошибка при добавлении рецепта: " . $e->getMessage());
        }
    }










    public function search() {
        // Получаем строку поиска
        $query = isset($_GET['query']) ? $_GET['query'] : '';

        // Для отладки, проверим, что приходит в query
        error_log("Поиск: " . $query);  // Это запишет в логи XAMPP

        // Получаем все рецепты, которые содержат строку в названии
        $recipes = $this->recipeModel->getAllRecipes($query);

        // Отправляем ответ в формате JSON

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($recipes, JSON_UNESCAPED_UNICODE);

    }




    // Список рецептов
    public function index() {
        $recipes = $this->recipeModel->getAllRecipes();
        require __DIR__ . '/../Views/pages/recipes.view.php';
    }

    // Просмотр рецепта
    public function view($view, $data = []) {
        extract($data); // Разворачиваем массив в переменные
        require_once __DIR__ . '/../Views/' . $view . '.php'; // Подключаем представление
    }

    public function show($id) {
        // Получаем рецепт по ID
        $recipe = $this->recipeModel->getRecipeById($id);

        // Получаем ингредиенты для этого рецепта
        $ingredients = $this->recipeModel->getIngredientsForRecipe($id);

        // Передаем данные в представление
        $this->view('pages/recipe.view', [
            'recipe' => $recipe,
            'ingredients' => $ingredients
        ]);
    }

    // Добавление рецепта
    public function create() {
        $ingredients = $this->ingredientModel->getAllIngredients();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $steps = $_POST['steps'];
            $image = !empty($_POST['image']) ? $_POST['image'] : null; // Сохраняем URL или NULL

            if (empty($name) || empty($description) || empty($steps)) {
                die('Все поля обязательны для заполнения!');
            }

            $this->recipeModel->createRecipe($name, $description, $steps, $image); // Передаем $image
            header('Location: /VAII_KULINAR_WEB/public/index.php/recipes');
            exit;
        }

        require_once __DIR__ . '/../Views/pages/recipe_create.view.php';
    }


    // Показ формы редактирования рецепта
    public function edit($id) {
        $recipe = $this->recipeModel->getRecipeById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $steps = $_POST['steps'];
            $image = isset($_POST['image']) ? $_POST['image'] : null; // Получение URL изображения

            if (empty($name) || empty($description) || empty($steps)) {
                die('Все поля обязательны для заполнения!');
            }

            // Обновляем рецепт, включая URL изображения
            $this->recipeModel->updateRecipe($id, $name, $description, $steps, $image);

            header('Location: /VAII_KULINAR_WEB/public/index.php/recipes');
            exit;
        }

        require_once __DIR__ . '/../Views/pages/recipe_edit.view.php';
    }


// Обновление рецепта
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $steps = $_POST['steps'];

            if (empty($name) || empty($description) || empty($steps)) {
                die('Все поля обязательны для заполнения!');
            }

            $this->recipeModel->updateRecipe($id, $name, $description, $steps);
            header('Location: /VAII_KULINAR_WEB/public/index.php/recipes');
            exit;
        }
    }


    // Удаление рецепта
    public function delete($id) {
        header('Content-Type: application/json'); // ✅ Указываем, что ответ - JSON

        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Nemáte povolenie na vymazanie receptu!']);
            exit;
        }

        $recipeModel = new RecipeModel();
        $deleted = $recipeModel->deleteRecipe($id);

        if ($deleted) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Chyba pri mazaní receptu!']);
        }
        exit;
    }


}
