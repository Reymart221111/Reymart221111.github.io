<?php

use Classes\Recipe;

require_once "../../vendor/autoload.php";

$deleteRecipe = new Recipe;
$deleteRecipe->DeleteRecipe();
