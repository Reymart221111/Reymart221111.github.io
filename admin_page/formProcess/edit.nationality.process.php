<?php

use Classes\Nationality;

require_once "../../vendor/autoload.php";

if (isset($_GET['id'])) {
    $Id = $_GET['id'];
} else {
    $message = "ID is Missing!";
    header("Location: ../RecipeCategorization/nationality.php?error=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $Nationality = filter_input(INPUT_POST, "nationality", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($Nationality)) {
        $message = "Please Input the Field!";
        header("Location: ../RecipeCategorization/edit_action_modal/editting_nationality.php?id=" . $Id . "&error=" . urlencode($message));
        exit();
    }

    $EditNationality = new Nationality;
    $EditNationality->EditNationality($Nationality);
}
