<?php

require_once __DIR__ . '/../Models/Recipe.php';
require_once __DIR__ . '/../Models/Ingredient.php';
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
    private function downloadImageFromUrl($imageUrl) {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        $imageName = time() . '_' . basename(parse_url($imageUrl, PHP_URL_PATH));
        $uploadFile = $uploadDir . $imageName;

        try {
            $imageData = file_get_contents($imageUrl);
            if ($imageData === false) {
                return null; // Не удалось скачать картинку
            }
            file_put_contents($uploadFile, $imageData);
            return "/VAII_KULINAR_WEB/public/uploads/" . $imageName;
        } catch (Exception $e) {
            return null;
        }
    }


    // Metóda na uloženie receptu
    public function store() {
        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            // Získanie údajov z formulára
            $name = $_POST['name'] ?? null;
            $description = $_POST['description'] ?? null;
            $steps = $_POST['steps'] ?? null;
            $image = $_POST['image'] ?? null;

            if (!$name || !$description || !$steps) {
                throw new Exception("Niektoré povinné polia nie sú vyplnené.");
            }

            // 1️⃣ Pridanie receptu
            $stmt = $db->prepare("INSERT INTO recipes (name, description, steps, image) VALUES (:name, :description, :steps, :image)");
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':steps' => $steps,
                ':image' => $image
            ]);
            $recipeId = $db->lastInsertId();

            // 2️⃣ Práca s ingredienciami
            $existingIngredients = $_POST['ingredient_id'] ?? [];
            $amounts = $_POST['ingredient_amount'] ?? [];
            $newIngredientNames = $_POST['new_ingredient_name'] ?? [];
            $newIngredientUnits = $_POST['new_ingredient_unit'] ?? [];
            $newAmounts = $_POST['new_ingredient_amount'] ?? [];

            $newIngredientIds = []; // Zoznam ID nových ingrediencií

            // 3️⃣ Pridanie nových ingrediencií do `ingredients`
            foreach ($newIngredientNames as $index => $newIngredientName) {
                if (!empty($newIngredientName)) {
                    $newUnit = $newIngredientUnits[$index] ?? '';

                    // Vloženie novej ingrediencie do `ingredients`
                    $stmt = $db->prepare("INSERT INTO ingredients (name, unit) VALUES (:name, :unit)");
                    $stmt->execute([
                        ':name' => $newIngredientName,
                        ':unit' => $newUnit
                    ]);
                    $newIngredientId = $db->lastInsertId();

                    // Pridanie ID novej ingrediencie do zoznamu
                    $newIngredientIds[] = [
                        'id' => $newIngredientId,
                        'amount' => $newAmounts[$index] ?? ''
                    ];
                }
            }

            // 4️⃣ Spojenie ID existujúcich a nových ingrediencií
            $finalIngredients = [];

            // Najprv pridáme existujúce ingrediencie
            foreach ($existingIngredients as $index => $ingredientId) {
                if (!empty($ingredientId) && is_numeric($ingredientId) && !empty($amounts[$index])) {
                    $finalIngredients[] = [
                        'id' => $ingredientId,
                        'amount' => $amounts[$index]
                    ];
                }
            }

            // Pridáme nové ingrediencie
            $finalIngredients = array_merge($finalIngredients, $newIngredientIds);

            // 5️⃣ Zapísanie všetkých ingrediencií do `recipe_ingredients`
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
            die("Chyba pri pridávaní receptu: " . $e->getMessage());
        }
    }

    // Vyhľadávanie receptov
    public function search() {
        $query = isset($_GET['query']) ? $_GET['query'] : '';

        error_log("Hľadanie: " . $query);

        $recipes = $this->recipeModel->getAllRecipes($query);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($recipes, JSON_UNESCAPED_UNICODE);
    }

    // Zoznam receptov
    public function index() {
        $recipes = $this->recipeModel->getAllRecipes();
        require __DIR__ . '/../Views/pages/recipes.view.php';
    }

    // Zobrazenie receptu
    public function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../Views/' . $view . '.php';
    }

    public function show($id) {
        $recipe = $this->recipeModel->getRecipeById($id);
        $ingredients = $this->recipeModel->getIngredientsForRecipe($id);

        $this->view('pages/recipe.view', [
            'recipe' => $recipe,
            'ingredients' => $ingredients
        ]);
    }

    // Pridanie receptu
    public function create() {
        $ingredients = $this->ingredientModel->getAllIngredients();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $steps = $_POST['steps'];
            $image = !empty($_POST['image']) ? $_POST['image'] : null;


            if (empty($name) || empty($description) || empty($steps)) {
                die('Všetky polia sú povinné!');
            }

            $this->recipeModel->createRecipe($name, $description, $steps, $image);
            header('Location: /VAII_KULINAR_WEB/public/index.php/recipes');
            exit;
        }

        require_once __DIR__ . '/../Views/pages/recipe_create.view.php';
    }

    // Zobrazenie formulára na úpravu receptu
    public function edit($id) {
        $recipe = $this->recipeModel->getRecipeById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $steps = $_POST['steps'];
            $image = isset($_POST['image']) ? $_POST['image'] : null;

            if (empty($name) || empty($description) || empty($steps)) {
                die('Všetky polia sú povinné!');
            }

            $this->recipeModel->updateRecipe($id, $name, $description, $steps, $image);

            header('Location: /VAII_KULINAR_WEB/public/index.php/recipes');
            exit;
        }

        require_once __DIR__ . '/../Views/pages/recipe_edit.view.php';
    }

    // Aktualizácia receptu
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $steps = $_POST['steps'];

            if (empty($name) || empty($description) || empty($steps)) {
                die('Všetky polia sú povinné!');
            }

            $this->recipeModel->updateRecipe($id, $name, $description, $steps);
            header('Location: /VAII_KULINAR_WEB/public/index.php/recipes');
            exit;
        }
    }

    // Odstránenie receptu
    public function delete($id) {
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID receptu nebolo zadané"]);
            exit;
        }

        $recipeModel = new Recipe();
        $deleted = $recipeModel->deleteRecipe($id);

        if ($deleted) {
            echo json_encode(["success" => "Recept bol odstránený"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Chyba pri odstraňovaní receptu"]);
        }
        exit;
    }
}
