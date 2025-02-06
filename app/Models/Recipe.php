<?php

require_once __DIR__ . '/../core/Database.php';

class Recipe {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }


    // Получение всех рецептов
    public function getAllRecipes($search = '') {
        if ($search) {
            // Поиск по имени, без учета регистра
            $stmt = $this->db->prepare("SELECT * FROM recipes WHERE LOWER(name) LIKE LOWER(:search)");
            $stmt->execute(['search' => '%' . $search . '%']);
        } else {
            // Если поиск не был задан, возвращаем все рецепты
            $stmt = $this->db->query("SELECT * FROM recipes");
        }

        // Возвращаем все рецепты в виде массива
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getIngredientsForRecipe($recipeId) {
        // Сначала извлекаем все ingredient_id для данного recipe_id из таблицы связей
        $stmt = $this->db->prepare("
        SELECT ingredient_id, amount 
        FROM recipe_ingredients 
        WHERE recipe_id = :recipe_id");
        $stmt->execute(['recipe_id' => $recipeId]);

        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Теперь получаем сами ингредиенты по ingredient_id
        $ingredientDetails = [];
        foreach ($ingredients as $ingredient) {
            // Для каждого ingredient_id извлекаем название ингредиента
            $stmt = $this->db->prepare("SELECT name FROM ingredients WHERE id = :id");
            $stmt->execute(['id' => $ingredient['ingredient_id']]);
            $ingredientData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Добавляем в массив с количеством
            $ingredientDetails[] = [
                'name' => $ingredientData['name'],
                'amount' => $ingredient['amount']
            ];
        }

        return $ingredientDetails;
    }





    // Получение рецепта по ID
    public function getRecipeById($id) {
        $stmt = $this->db->prepare('SELECT * FROM recipes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Добавление нового рецепта (исправлено)
    public function createRecipe($name, $description, $steps, $image = null) {
        $stmt = $this->db->prepare('INSERT INTO recipes (name, description, steps, image) VALUES (:name, :description, :steps, :image)');
        $stmt->execute([
            ':name' => htmlspecialchars($name),
            ':description' => htmlspecialchars($description),
            ':steps' => htmlspecialchars($steps),
            ':image' => $image
        ]);
    }




    public function updateRecipe($id, $name, $description, $steps, $image) {
        $stmt = $this->db->prepare('UPDATE recipes SET name = :name, description = :description, steps = :steps, image = :image WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => htmlspecialchars($name),
            'description' => htmlspecialchars($description),
            'steps' => htmlspecialchars($steps),
            'image' => $image
        ]);
    }



    // Удаление рецепта
    public function deleteRecipe($id) {
        $stmt = $this->db->prepare('DELETE FROM recipes WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
