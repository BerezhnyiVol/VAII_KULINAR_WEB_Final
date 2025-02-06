<?php

class IngredientController
{
    private $ingredientModel;

    public function __construct()
    {
        $this->ingredientModel = new Ingredient();
    }

    // Показать все ингредиенты
    public function index()
    {
        $ingredients = $this->ingredientModel->getAllIngredients();
        require_once 'app/Views/pages/ingredients.view.php';
    }

    // Добавление нового ингредиента
    public function store()
    {
        if (isset($_POST['name'], $_POST['unit'])) {
            $name = $_POST['name'];
            $unit = $_POST['unit'];
            $this->ingredientModel->addNewIngredient($name, $unit);
        }

        header('Location: /index.php/ingredients');
    }
}
