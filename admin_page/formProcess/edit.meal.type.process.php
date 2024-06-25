<?php

use Classes\MealType;

require_once "../../vendor/autoload.php";

if (isset($_GET['id'])) {
    $Id = $_GET['id'];
} else {
    $message = "ID is Missing!";
    header("Location: ../RecipeCategorization/nationality.php?error=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $MealType = filter_input(INPUT_POST, "meal_type", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($MealType)) {
        $message = "Please Input the Field!";
        header("Location: ../RecipeCategorization/edit_action_modal/editting_meal_type.php?id=" . $Id . "&error=" . urlencode($message));
        exit();
    }

    $EditMealType = new MealType;
    $EditMealType->EditMealType($MealType);
}
