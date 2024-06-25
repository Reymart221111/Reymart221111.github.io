<?php

use Classes\MealType;

require_once "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $MealType = filter_input(INPUT_POST, "meal_type", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($MealType)) {
        $message = "Please Input the Field!";
        header("Location: ../RecipeCategorization/adding_action_modal/adding_nationality.php?error=" . urlencode($message));
        exit();
    }

    $AddMealType = new MealType;
    $AddMealType->AddMealType($MealType);
}
