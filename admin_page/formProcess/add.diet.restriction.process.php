<?php

use Classes\Restriction;

require_once "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $DietRestriction = filter_input(INPUT_POST, "diet_restriction", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($DietRestriction)) {
        $message = "Please Input the Field!";
        header("Location: ../RecipeCategorization/adding_action_modal/adding_nationality.php?error=" . urlencode($message));
        exit();
    }

   $AddRestriction = new Restriction;
   $AddRestriction->AddRestriction($DietRestriction);

}
