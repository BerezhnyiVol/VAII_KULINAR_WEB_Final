<?php

require_once __DIR__ . '/../core/Database.php';

class Recipe {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Získanie všetkých receptov
    public function getAllRecipes($search = '') {
        if ($search) {
            // Hľadanie podľa názvu, bez ohľadu na veľkosť písmen
            $stmt = $this->db->prepare("SELECT * FROM recipes WHERE LOWER(name) LIKE LOWER(:search)");
            $stmt->execute(['search' => '%' . $search . '%']);
        } else {
            // Ak nebolo zadané hľadanie, vrátime všetky recepty
            $stmt = $this->db->query("SELECT * FROM recipes");
        }

        // Vrátime všetky recepty vo forme poľa
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Získanie ingrediencií pre konkrétny recept
    public function getIngredientsForRecipe($recipeId) {
        // Najprv získame všetky ingredient_id pre daný recipe_id z prepojenej tabuľky
        $stmt = $this->db->prepare("
        SELECT ingredient_id, amount 
        FROM recipe_ingredients 
        WHERE recipe_id = :recipe_id");
        $stmt->execute(['recipe_id' => $recipeId]);

        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Teraz získame samotné ingrediencie podľa ingredient_id
        $ingredientDetails = [];
        foreach ($ingredients as $ingredient) {
            // Pre každý ingredient_id získame názov ingrediencie
            $stmt = $this->db->prepare("SELECT name FROM ingredients WHERE id = :id");
            $stmt->execute(['id' => $ingredient['ingredient_id']]);
            $ingredientData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Pridáme do poľa s množstvom
            $ingredientDetails[] = [
                'name' => $ingredientData['name'],
                'amount' => $ingredient['amount']
            ];
        }

        return $ingredientDetails;
    }

    // Získanie receptu podľa ID
    public function getRecipeById($id) {
        $stmt = $this->db->prepare('SELECT * FROM recipes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Pridanie nového receptu
    public function createRecipe($name, $description, $steps, $image = null) {
        $stmt = $this->db->prepare('INSERT INTO recipes (name, description, steps, image) VALUES (:name, :description, :steps, :image)');
        $stmt->execute([
            ':name' => htmlspecialchars($name),
            ':description' => htmlspecialchars($description),
            ':steps' => htmlspecialchars($steps),
            ':image' => $image
        ]);
    }

    // Aktualizácia receptu
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

    // Odstránenie receptu
    public function deleteRecipe($id) {
        $stmt = $this->db->prepare("DELETE FROM recipes WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
