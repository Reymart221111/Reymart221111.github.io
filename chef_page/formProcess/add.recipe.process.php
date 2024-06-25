<?php

use Classes\Recipe;

require_once "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $recipeName = filter_input(INPUT_POST, 'recipe_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $ingredients = $_POST['ingredients'];
    $procedure = $_POST['procedure'];
    $NationalityID = filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_SPECIAL_CHARS);
    $MealTypeID = filter_input(INPUT_POST, 'meal_type', FILTER_SANITIZE_SPECIAL_CHARS);
    $RestrictionID = filter_input(INPUT_POST, 'restriction', FILTER_SANITIZE_SPECIAL_CHARS);
    $cookingTime = filter_input(INPUT_POST, 'cooking_time', FILTER_SANITIZE_SPECIAL_CHARS);
    $servingSize = filter_input(INPUT_POST, 'serving_size', FILTER_SANITIZE_SPECIAL_CHARS);
    $imgFile = $_FILES['recipe_image'];

    $NationalityID = ($NationalityID === '') ? null : $NationalityID;
    $MealTypeID = ($MealTypeID === '') ? null : $MealTypeID;
    $RestrictionID = ($RestrictionID === '') ? null : $RestrictionID;

    if (empty($recipeName)) {
        $message = 'Please input Recipe name.';
        header("Location: ../RecipePageActions/adding_recipe.php?error=" . urlencode($message));
        exit();
    }

    if (empty($ingredients)) {
        $message = 'Please input ingredients.';
        header("Location: ../RecipePageActions/adding_recipe.php?error=" . urlencode($message));
        exit();
    }

    if (empty($procedure)) {
        $message = 'Please input procedure.';
        header("Location: ../RecipePageActions/adding_recipe.php?error=" . urlencode($message));
        exit();
    }

    if (empty($cookingTime)) {
        $message = 'Please input Cooking Time.';
        header("Location: ../RecipePageActions/adding_recipe.php?error=" . urlencode($message));
        exit();
    }

    if (empty($servingSize)) {
        $message = 'Please input Serving Size.';
        header("Location: ../RecipePageActions/adding_recipe.php?error=" . urlencode($message));
        exit();
    }

    // Sanitize ingredients and procedure while preserving newline characters
    $sanitizedIngredients = htmlspecialchars($ingredients, ENT_QUOTES, 'UTF-8');
    $sanitizedProcedure = htmlspecialchars($procedure, ENT_QUOTES, 'UTF-8');

    $SaveRecipe = new Recipe;
    $SaveRecipe->saveRecipe($recipeName, $sanitizedIngredients, $sanitizedProcedure, $cookingTime, $servingSize, $imgFile, $NationalityID, $MealTypeID, $RestrictionID);
}
