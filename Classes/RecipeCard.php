<?php

namespace Classes;

class RecipeCard
{
    private $conn;
    private $itemsPerPage = 12;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getRecipes($page, $searchQuery, $selectedCuisine, $selectedMealType, $selectedRestriction)
    {
        $offset = ($page - 1) * $this->itemsPerPage;
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
                    MealType.MealType AS MealType,
                    DietRestriction.Restriction AS Restriction
                FROM 
                    RecipeDetails
                JOIN 
                    Users ON RecipeDetails.AddedBy = Users.UserID
                LEFT JOIN 
                    CuisineNationality ON RecipeDetails.CuisineID = CuisineNationality.CuisineID
                LEFT JOIN 
                    MealType ON RecipeDetails.MealTypeID = MealType.MealTypeID
                LEFT JOIN 
                    DietRestriction ON RecipeDetails.RestrictionID =   DietRestriction.RestrictionID
                WHERE 1=1";

        if (!empty($searchQuery)) {
            $sql .= " AND RecipeDetails.RecipeName LIKE '%" . $this->conn->real_escape_string($searchQuery) . "%'";
        }

        if (!empty($selectedCuisine)) {
            $sql .= " AND CuisineNationality.CuisineOrigin = '" . $this->conn->real_escape_string($selectedCuisine) . "'";
        }

        if (!empty($selectedMealType)) {
            $sql .= " AND MealType.MealType = '" . $this->conn->real_escape_string($selectedMealType) . "'";
        }

        if (!empty($selectedRestriction)) {
            $sql .= " AND DietRestriction.Restriction = '" . $this->conn->real_escape_string($selectedRestriction) . "'";
        }

        $sql .= " LIMIT $this->itemsPerPage OFFSET $offset";

        return $this->conn->query($sql);
    }

    public function getTotalItems($searchQuery, $selectedCuisine, $selectedMealType, $selectedRestriction)
    {
        $countSql = "SELECT COUNT(*) as count FROM RecipeDetails WHERE 1=1";

        if (!empty($searchQuery)) {
            $countSql .= " AND RecipeDetails.RecipeName LIKE '%" . $this->conn->real_escape_string($searchQuery) . "%'";
        }

        if (!empty($selectedCuisine)) {
            $countSql .= " AND RecipeDetails.CuisineID IN (SELECT CuisineID FROM CuisineNationality WHERE CuisineOrigin = '" . $this->conn->real_escape_string($selectedCuisine) . "')";
        }

        if (!empty($selectedMealType)) {
            $countSql .= " AND RecipeDetails.MealTypeID IN (SELECT MealTypeID FROM MealType WHERE MealType = '" . $this->conn->real_escape_string($selectedMealType) . "')";
        }

        if (!empty($selectedRestriction)) {
            $countSql .= " AND RecipeDetails.RestrictionID IN (SELECT RestrictionID FROM DietRestriction WHERE Restriction = '" . $this->conn->real_escape_string($selectedRestriction) . "')";
        }

        $countResult = $this->conn->query($countSql);
        return $countResult->fetch_assoc()['count'];
    }

    public function getCuisines()
    {
        $result = $this->conn->query("SELECT CuisineOrigin FROM CuisineNationality");
        $cuisines = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cuisines[] = $row['CuisineOrigin'];
            }
        }

        return $cuisines;
    }

    public function getMealTypes()
    {
        $result = $this->conn->query("SELECT MealType FROM MealType");
        $mealTypes = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $mealTypes[] = $row['MealType'];
            }
        }

        return $mealTypes;
    }

    public function getDietRestrictions()
    {
        $result = $this->conn->query("SELECT Restriction FROM DietRestriction");
        $dietRestrictions = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dietRestrictions[] = $row['Restriction'];
            }
        }

        return $dietRestrictions;
    }
}
