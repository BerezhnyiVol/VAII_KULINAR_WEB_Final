<?php

class Ingredient
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Získanie všetkých ingrediencií
    public function getAllIngredients()
    {
        $query = 'SELECT * FROM ingredients';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pridanie ingrediencií k receptu
    public function addIngredientsToRecipe($recipeId, $ingredients)
    {
        $stmt = $this->db->prepare("
        INSERT INTO recipe_ingredients (recipe_id, ingredient_id, amount) 
        VALUES (:recipe_id, :ingredient_id, :amount)
    ");

        foreach ($ingredients as $ingredient) {
            $stmt->execute([
                'recipe_id' => $recipeId,
                'ingredient_id' => $ingredient['id'],
                'amount' => $ingredient['amount']
            ]);
        }
    }

    // Pridanie novej ingrediencie
    public function addNewIngredient($name, $unit)
    {
        $query = 'INSERT INTO ingredients (name, unit) VALUES (:name, :unit)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':unit', $unit);
        return $stmt->execute();
    }
}
