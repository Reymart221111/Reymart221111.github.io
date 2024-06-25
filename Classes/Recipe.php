<?php

namespace Classes;

use Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Recipe extends DbSetup
{
    private $RecipeName;
    private $Ingredients;
    private $Procedure;
    private $NationalOriginID;
    private $MealTypeID;
    private $RestrictionID;
    private $AddedByID;
    private $CookingTime;
    private $ServingSize;
    private $imgPath;
    private $Id;

    private $imgFile;
    private $imgFolderTarget = "../../ImgUploads/recipe_IMG/"; //Use Relative Path to your File and please dont delete this comment...///////


    public function __construct()
    {
        parent::getInstance();
    }

    public function saveRecipe(
        $RecipeName,
        $Ingredients,
        $Procedure,
        $CookingTime,
        $ServingSize,
        $imgFile,
        $NationalOriginID,
        $MealTypeID,
        $RestrictionID
    ) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        $this->imgFile = $imgFile;
        $ImgUpload = new ImageUpload($this->imgFile, $this->imgFolderTarget);

        $this->RecipeName = $RecipeName;
        $this->Ingredients = $Ingredients;
        $this->Procedure = $Procedure;
        $this->CookingTime = $CookingTime;
        $this->ServingSize = $ServingSize;
        $this->AddedByID = $_SESSION['user_id'];
        $this->NationalOriginID = $NationalOriginID;
        $this->MealTypeID = $MealTypeID;
        $this->RestrictionID = $RestrictionID;

        if ($ImgUpload->isUploadSuccessful()) {
            try {
                $this->imgPath = $ImgUpload->GetImgPath();
                $sql = "INSERT INTO recipedetails(RecipeName, Ingredients, Procedures, CookingTime, ServingSize, ImagePath, AddedBy, CuisineID, MealTypeID, RestrictionID)
                    VALUES(?,?,?,?,?,?,?,?,?,?)";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    'sssiisiiii',
                    $this->RecipeName,
                    $this->Ingredients,
                    $this->Procedure,
                    $this->CookingTime,
                    $this->ServingSize,
                    $this->imgPath,
                    $this->AddedByID,
                    $this->NationalOriginID,
                    $this->MealTypeID,
                    $this->RestrictionID
                );

                if ($stmt->execute()) {
                    $message = "Added Succesfully!";
                    header("Location: ../recipe.page.php?success=" . urlencode($message));
                    exit();
                }
            } catch (Exception $error) {
                echo "Error->" . $error->getMessage();
                exit();
            } finally {
                $stmt->close();
                $conn->close();
            }
        } else {
            $message = $ImgUpload->getErrorMessage();
            header("Location: ../recipe.page.php?error=" . urlencode($message));
            exit();
        }
    }

    public function editRecipe(
        $RecipeName,
        $Ingredients,
        $Procedure,
        $CookingTime,
        $ServingSize,
        $imgFile,
        $NationalOriginID,
        $MealTypeID,
        $RestrictionID
    ) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "No ID found!";
            header("Location: ../recipe.page.php?error=" . urlencode($message));
            exit();
        }
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        $this->imgFile = $imgFile;
        $ImgUpload = new UpdateImage($this->imgFile, $this->imgFolderTarget);

        $this->imgPath = $ImgUpload->GetImgPath();

        $this->RecipeName = $RecipeName;
        $this->Ingredients = $Ingredients;
        $this->Procedure = $Procedure;
        $this->CookingTime = $CookingTime;
        $this->ServingSize = $ServingSize;
        $this->AddedByID = $_SESSION['user_id'];
        $this->NationalOriginID = $NationalOriginID;
        $this->MealTypeID = $MealTypeID;
        $this->RestrictionID = $RestrictionID;
        $this->imgPath = $ImgUpload->GetImgPath();
        if ($ImgUpload->isUploadSuccessful()) {
            try {
                $sql = "UPDATE recipedetails SET RecipeName = ?, Ingredients = ?, Procedures = ?, CookingTime = ?,
                        ServingSize = ?, ImagePath = ?, AddedBy = ?, CuisineID = ?, MealTypeID = ?, RestrictionID = ?
                        WHERE RecipeID = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    'sssiisiiiii',
                    $this->RecipeName,
                    $this->Ingredients,
                    $this->Procedure,
                    $this->CookingTime,
                    $this->ServingSize,
                    $this->imgPath,
                    $this->AddedByID,
                    $this->NationalOriginID,
                    $this->MealTypeID,
                    $this->RestrictionID,
                    $this->Id
                );

                if ($stmt->execute()) {
                    $message = "Edited Succesfully!";
                    header("Location: ../recipe.page.php?success=" . urlencode($message));
                    exit();
                }
            } catch (Exception $error) {
                echo "Error->" . $error->getMessage();
                exit();
            } finally {
                $stmt->close();
                $conn->close();
            }
        } else {
            $message = $ImgUpload->getErrorMessage();
            header("Location: ../recipe.page.php?error=" . urlencode($message));
            exit();
        }
    }

    public function DeleteRecipe()
    {
        $dbSetupInstance = DbSetup::getInstance();
        $conn = $dbSetupInstance->getConnection();

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "No ID found!";
            header("Location: ../recipe.page.php?error=" . urlencode($message));
            exit();
        }

        $sql = "DELETE FROM recipedetails Where RecipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $this->Id);
        if ($stmt->execute()) {
            $message = "Deleted Succesfully!";
            header("Location: ../recipe.page.php?success=" . urlencode($message));
            exit();
        } else {
            echo "error->" . $conn->error;
        }
    }
}
