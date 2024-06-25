<?php

use Classes\Database;
use Classes\RecipeCard;
use Classes\RecipePage;

require_once "../../vendor/autoload.php";


$db = (new Database())->connect();


$recipeModel = new RecipeCard($db);


$recipePage = new RecipePage($recipeModel);
$recipePage->render();
?>
