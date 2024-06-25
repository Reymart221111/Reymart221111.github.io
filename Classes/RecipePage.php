<?php

namespace Classes;

class RecipePage
{
    private $recipeModel;
    private $itemsPerPage = 12;

    public function __construct($recipeModel)
    {
        $this->recipeModel = $recipeModel;
    }

    public function render()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
        $selectedCuisine = isset($_GET['cuisine']) ? $_GET['cuisine'] : '';
        $selectedMealType = isset($_GET['mealType']) ? $_GET['mealType'] : '';
        $selectedDietRestriction = isset($_GET['dietRestriction']) ? $_GET['dietRestriction'] : '';


        $recipes = $this->recipeModel->getRecipes($page, $searchQuery, $selectedCuisine, $selectedMealType, $selectedDietRestriction);
        $totalItems = $this->recipeModel->getTotalItems($searchQuery, $selectedCuisine, $selectedMealType, $selectedDietRestriction);
        $numPages = ceil($totalItems / $this->itemsPerPage);
        $cuisines = $this->recipeModel->getCuisines();
        $mealTypes = $this->recipeModel->getMealTypes();
        $dietRestrictions = $this->recipeModel->getDietRestrictions();

        include 'template.php';
    }
}
