<?php

namespace Classes;

use Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class MealType extends DbSetup
{
    private $MealType;
    private $Id;

    public function __construct()
    {
        parent::getInstance();
    }

    public function AddMealType($MealType)
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();
        $this->MealType = $MealType;


        try {
            // Check if the MealType already exists
            if ($this->isDuplicateMealType($this->MealType)) {
                $message = "Already Existed!";
                header("Location: ../RecipeCategorization/meal_type.php?error=" . urlencode($message));
                exit();
            }

            $sql = "INSERT INTO MealType (MealType) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $this->MealType);

            if ($stmt->execute()) {
                $message = "Added Successfully";
                header("Location: ../RecipeCategorization/meal_type.php?success=" . urlencode($message));
                exit();
            } else {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
        } catch (Exception $error) {
            echo "Error->" . $error->getMessage();
            exit();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public function EditMealType($MealType)
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();
        $this->MealType = $MealType;

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "ID is Missing!";
            header("Location: ../RecipeCategorization/nationality.php?error=" . urlencode($message));
            exit();
        }
        try {
            // Check if the MealType already exists
            if ($this->isDuplicateMealType($this->MealType)) {
                $message = "Already Existed!";
                header("Location: ../RecipeCategorization/meal_type.php?error=" . urlencode($message));
                exit();
            }

            $sql = "UPDATE MealType set MealType = ? where MealTypeID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $this->MealType, $this->Id);

            if ($stmt->execute()) {
                $message = "Edited Successfully";
                header("Location: ../RecipeCategorization/meal_type.php?success=" . urlencode($message));
                exit();
            } else {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
        } catch (Exception $error) {
            echo "Error->" . $error->getMessage();
            exit();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }



    private function isDuplicateMealType($MealType)
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        $sql = "SELECT COUNT(*) FROM MealType WHERE MealType = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $MealType);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function DeleteMealType()
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "No ID found!";
            header("Location: ../RecipeCategorization/meal_type.php?error=" . urlencode($message));
            exit();
        }

        $sql = "DELETE FROM MealType Where MealTypeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $this->Id);
        if ($stmt->execute()) {
            $message = "Deleted Succesfully!";
            header("Location: ../RecipeCategorization/meal_type.php?success=" . urlencode($message));
            exit();
        } else {
            echo "error->" . $conn->error;
        }
    }
}
