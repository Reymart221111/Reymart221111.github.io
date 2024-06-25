<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'recipeapp');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$selectedCuisine = isset($_GET['cuisine']) ? $_GET['cuisine'] : '';
$selectedMealType = isset($_GET['mealType']) ? $_GET['mealType'] : '';

$itemsPerPage = 12;
$offset = ($page - 1) * $itemsPerPage;

// Build the SQL query for recipes
$sql = "SELECT 
    RecipeDetails.RecipeID,
    RecipeDetails.RecipeName,
    RecipeDetails.ImagePath,
    RecipeDetails.Ingredients,
    RecipeDetails.Procedures,
    RecipeDetails.CookingTime,
    RecipeDetails.ServingSize,
    Users.UserName AS AddedBy,
    CuisineNationality.CuisineOrigin AS Cuisine,
    MealType.MealType AS MealType
    FROM 
    RecipeDetails
    JOIN 
    Users ON RecipeDetails.AddedBy = Users.UserID
    LEFT JOIN 
    CuisineNationality ON RecipeDetails.CuisineID = CuisineNationality.CuisineID
    LEFT JOIN 
    MealType ON RecipeDetails.MealTypeID = MealType.MealTypeID
    WHERE 1=1";

if (!empty($searchQuery)) {
    $sql .= " AND RecipeDetails.RecipeName LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
}

if (!empty($selectedCuisine)) {
    $sql .= " AND CuisineNationality.CuisineOrigin = '" . $conn->real_escape_string($selectedCuisine) . "'";
}

if (!empty($selectedMealType)) {
    $sql .= " AND MealType.MealType = '" . $conn->real_escape_string($selectedMealType) . "'";
}

$sql .= " LIMIT $itemsPerPage OFFSET $offset";

$result = $conn->query($sql);

// Fetch total count for pagination
$countSql = "SELECT COUNT(*) as count FROM RecipeDetails WHERE 1=1";

if (!empty($searchQuery)) {
    $countSql .= " AND RecipeDetails.RecipeName LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
}

if (!empty($selectedCuisine)) {
    $countSql .= " AND RecipeDetails.CuisineID IN (SELECT CuisineID FROM CuisineNationality WHERE CuisineOrigin = '" . $conn->real_escape_string($selectedCuisine) . "')";
}

if (!empty($selectedMealType)) {
    $countSql .= " AND RecipeDetails.MealTypeID IN (SELECT MealTypeID FROM MealType WHERE MealType = '" . $conn->real_escape_string($selectedMealType) . "')";
}

$countResult = $conn->query($countSql);
$totalItems = $countResult->fetch_assoc()['count'];
$numPages = ceil($totalItems / $itemsPerPage);

// Fetch cuisines and meal types for the filters
$cuisineResult = $conn->query("SELECT CuisineOrigin FROM CuisineNationality");
$mealTypeResult = $conn->query("SELECT MealType FROM MealType");

$cuisines = [];
$mealTypes = [];

if ($cuisineResult->num_rows > 0) {
    while ($row = $cuisineResult->fetch_assoc()) {
        $cuisines[] = $row['CuisineOrigin'];
    }
}

if ($mealTypeResult->num_rows > 0) {
    while ($row = $mealTypeResult->fetch_assoc()) {
        $mealTypes[] = $row['MealType'];
    }
}

$conn->close();

?>