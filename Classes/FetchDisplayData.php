<?php

namespace Classes;

use Exception;

class FetchDisplayData extends DbSetup
{
    private static $defualtImg = "/Recipe_Sharing_Web/ImgUploads/user_PROFILE/no-Img.jpg";
    public function __construct()
    {
        parent::getInstance();
    }

    public static function FetchUserProfile()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT * FROM users WHERE UserID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function FetchNationality()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT * FROM cuisinenationality";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $Nrow = $result->fetch_assoc();
                return $Nrow;
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function FetchMealType()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT * FROM mealtype";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $MealRow = $result->fetch_assoc();
                return $MealRow;
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function FetchRestrictions()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT * FROM dietrestriction";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $RestrictionRow = $result->fetch_assoc();
                return $RestrictionRow;
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function DisplayNationalitySelectedOption()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $message = "Id is missing!";
            header('Location: ../recipe.page.php?error=' . urlencode($message));
            exit();
        }

        try {
            $sql = "SELECT CuisineID FROM recipedetails WHERE RecipeID = ?";
            $sql2 = "SELECT CuisineID, CuisineOrigin FROM cuisinenationality";

            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Invalid statement: ' . $conn->error);
            }
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt2 = $conn->prepare($sql2);
            if ($stmt2 === false) {
                throw new Exception('Invalid statement: ' . $conn->error);
            }
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result->num_rows > 0) {
                $selectedRow = $result->fetch_assoc();
                $restrictionID = $selectedRow['CuisineID'];

                while ($row2 = $result2->fetch_assoc()) {
                    $selected = ($row2['CuisineID'] == $restrictionID) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($row2['CuisineID'], ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($row2['CuisineOrigin'], ENT_QUOTES, 'UTF-8') . '</option>';
                }
            } else {
                echo "<option value='None'>None</option>";
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            if ($result) $result->close();
            if ($result2) $result2->close();
            if ($stmt) $stmt->close();
            if ($stmt2) $stmt2->close();
            $conn->close();
        }
    }

    public static function DisplayNationalityOption()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT * FROM cuisinenationality";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value=\"" . $row['CuisineID'] . "\">" . $row['CuisineOrigin'] . "</option>";
                }
            } else {
                echo "<option value='None'>None</option>";
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function DisplayMealTypeOption()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT * FROM MealType";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value=\"" . $row['MealTypeID'] . "\">" . $row['MealType'] . "</option>";
                }
            } else {
                echo "<option value='None'>None</option>";
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }



    public static function DisplayMealTypeSelectedOption()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $message = "Id is missing!";
            header('Location: ../recipe.page.php?error=' . urlencode($message));
            exit();
        }

        try {
            $sql = "SELECT MealTypeID FROM recipedetails WHERE RecipeID = ?";
            $sql2 = "SELECT MealTypeID, MealType FROM mealtype";

            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Invalid statement: ' . $conn->error);
            }
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt2 = $conn->prepare($sql2);
            if ($stmt2 === false) {
                throw new Exception('Invalid statement: ' . $conn->error);
            }
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result->num_rows > 0) {
                $selectedRow = $result->fetch_assoc();
                $restrictionID = $selectedRow['MealTypeID'];

                while ($row2 = $result2->fetch_assoc()) {
                    $selected = ($row2['MealTypeID'] == $restrictionID) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($row2['MealTypeID'], ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($row2['MealType'], ENT_QUOTES, 'UTF-8') . '</option>';
                }
            } else {
                echo "<option value='None'>None</option>";
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            if ($result) $result->close();
            if ($result2) $result2->close();
            if ($stmt) $stmt->close();
            if ($stmt2) $stmt2->close();
            $conn->close();
        }
    }

    public static function DisplayRestrictionOption()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT * FROM dietrestriction";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value=\"" . $row['RestrictionID'] . "\">" . $row['Restriction'] . "</option>";
                }
            } else {
                echo "<option value='None'>None</option>";
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function DisplayRestrictionSelectedOption()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $message = "Id is missing!";
            header('Location: ../recipe.page.php?error=' . urlencode($message));
            exit();
        }

        try {
            $sql = "SELECT RestrictionID FROM recipedetails WHERE RecipeID = ?";
            $sql2 = "SELECT RestrictionID, Restriction FROM dietrestriction";

            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Invalid statement: ' . $conn->error);
            }
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt2 = $conn->prepare($sql2);
            if ($stmt2 === false) {
                throw new Exception('Invalid statement: ' . $conn->error);
            }
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result->num_rows > 0) {
                $selectedRow = $result->fetch_assoc();
                $restrictionID = $selectedRow['RestrictionID'];

                while ($row2 = $result2->fetch_assoc()) {
                    $selected = ($row2['RestrictionID'] == $restrictionID) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($row2['RestrictionID'], ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($row2['Restriction'], ENT_QUOTES, 'UTF-8') . '</option>';
                }
            } else {
                echo "<option value='None'>None</option>";
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            if ($result) $result->close();
            if ($result2) $result2->close();
            if ($stmt) $stmt->close();
            if ($stmt2) $stmt2->close();
            $conn->close();
        }
    }

    public static function DisplayRecipeTable()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT 
                    RecipeDetails.RecipeID,
                    RecipeDetails.RecipeName,
                    RecipeDetails.Ingredients,
                    RecipeDetails.Procedures,
                    RecipeDetails.CookingTime,
                    RecipeDetails.ServingSize,
                    RecipeDetails.ImagePath,
                    RecipeDetails.AddedDate,
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
                    DietRestriction ON RecipeDetails.RestrictionID = DietRestriction.RestrictionID;
                    ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['RecipeID'] . "</td>";
                    echo "<td><img src='" . $row['ImagePath'] . "' alt='Recipe Image' class='img-thumbnail' style='max-width: 100px;'></td>";
                    echo "<td>" . $row['RecipeName'] . "</td>";
                    echo "<td>" . $row['MealType'] . "</td>";
                    echo "<td>" . $row['AddedBy'] . "</td>";
                    echo "<td>" . $row['AddedDate'] . "</td>";
                    echo "<td>";
                    echo '<a href="RecipePageActions/editting_recipe.php?id=' . urlencode($row['RecipeID']) . '" class="btn btn-primary btn-sm">Update</a> ';
                    echo '<a href="formProcess/delete.recipe.process.php?id=' . urlencode($row['RecipeID']) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete</a>';
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='7'>No Records Found</td>";
                echo "</tr>";
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function DisplayUserImg()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT Profile_path from users WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();

                if ($row['Profile_path'] === null) {
                    return self::$defualtImg;
                }

                return $row['Profile_path'];
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public static function DisplayNationalitySelectedData()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $message = "Id is missing!";
            header('Location: ../RecipeCategorization/nationality.php?error=' . urlencode($message));
            exit();
        }
        try {
            $sql = "SELECT * FROM cuisinenationality where CuisineID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function DisplayMealTypeSelectedData()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $message = "Id is missing!";
            header('Location: ../RecipeCategorization/nationality.php?error=' . urlencode($message));
            exit();
        }

        try {
            $sql = "SELECT * FROM mealtype where MEalTypeID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function DisplayRestrictionData()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $message = "Id is missing!";
            header('Location: ../RecipeCategorization/nationality.php?error=' . urlencode($message));
            exit();
        }

        try {
            $sql = "SELECT * FROM dietRestriction where RestrictionID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function DisplayRecipeData()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $message = "Id is missing!";
            header('Location: ../recipe.page.php?error=' . urlencode($message));
            exit();
        }

        try {
            $sql = "SELECT 
                    RecipeDetails.RecipeID,
                    RecipeDetails.RecipeName,
                    RecipeDetails.Ingredients,
                    RecipeDetails.Procedures,
                    RecipeDetails.CookingTime,
                    RecipeDetails.ServingSize,
                    RecipeDetails.ImagePath,
                    RecipeDetails.AddedDate,
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
                    DietRestriction ON RecipeDetails.RestrictionID = DietRestriction.RestrictionID
                    WHERE RecipeDetails.RecipeID = ?;
                    ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function DisplayUserSelectedData()
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $message = "Id is missing!";
            header('Location: ../Users.php?error=' . urlencode($message));
            exit();
        }

        $stmt = $conn->prepare("SELECT * FROM users where UserID=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    public static function DisplayUserCurrentData()
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $stmt = $conn->prepare("SELECT * FROM users where UserID=?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    public static function DisplayCurrentUser()
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $stmt = $conn->prepare("SELECT * FROM users where UserID=?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    public static function DisplayRecipeDataUpdate()
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $message = "Id is missing!";
            header('Location: ../recipe.page.php?error=' . urlencode($message));
            exit();
        }

        $stmt = $conn->prepare("SELECT * FROM recipedetails where RecipeID=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    public static function DisplayRecipeTableUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT 
                    RecipeDetails.RecipeID,
                    RecipeDetails.RecipeName,
                    RecipeDetails.Ingredients,
                    RecipeDetails.Procedures,
                    RecipeDetails.CookingTime,
                    RecipeDetails.ServingSize,
                    RecipeDetails.ImagePath,
                    RecipeDetails.AddedDate,
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
                    DietRestriction ON RecipeDetails.RestrictionID = DietRestriction.RestrictionID
                    WHERE RecipeDetails.AddedBy = ?
                    ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='" . $row['ImagePath'] . "' alt='Recipe Image' class='img-thumbnail' style='max-width: 100px;'></td>";
                    echo "<td>" . $row['RecipeName'] . "</td>";
                    echo "<td>" . $row['MealType'] . "</td>";
                    echo "<td>" . $row['AddedDate'] . "</td>";
                    echo "<td>";
                    echo '<a href="RecipePageActions/editting_recipe.php?id=' . urlencode($row['RecipeID']) . '" class="btn btn-primary btn-sm">Update</a> ';
                    echo '<a href="formProcess/delete.recipe.process.php?id=' . urlencode($row['RecipeID']) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete</a>';
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='7'>No Records Found</td>";
                echo "</tr>";
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public static function FetchRecipeCountUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        $sql = "SELECT COUNT(*) as Count FROM RecipeDetails where Addedby = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Count'];
    }

    public static function FetchRecipeCount()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        $sql = "SELECT COUNT(*) as Count FROM RecipeDetails";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Count'];
    }

    public static function FetchRecipeCategoryCount()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        $sql = "SELECT COUNT(*) as Count FROM cuisinenationality";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Count'];
    }

    public static function FetchUserChefCount()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        $sql = "SELECT COUNT(*) as Count FROM users Where Role = 'chef'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Count'];
    }

    public static function FetchUserAdminCount()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        $sql = "SELECT COUNT(*) as Count FROM users Where Role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Count'];
    }
}
