<?php

use Classes\Nationality;

require_once "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $Nationality = filter_input(INPUT_POST, "nationality", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($Nationality)) {
        $message = "Please Input the Field!";
        header("Location: ../RecipeCategorization/adding_action_modal/adding_nationality.php?error=" . urlencode($message));
        exit();
    }

    $AddNationality = new Nationality;
    $AddNationality->AddNationality($Nationality);
}
