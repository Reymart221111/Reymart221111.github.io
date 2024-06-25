<?php

namespace Classes;

use Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Nationality extends DbSetup
{
    private $Nationality;
    private $Id;

    public function __construct()
    {
        parent::getInstance();
    }

    public function AddNationality($Nationality)
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();
        $this->Nationality = $Nationality;

        try {
            // Check for duplicates
            if ($this->IsDuplicateNationality($this->Nationality)) {
                $message = "Already Existed!";
                header("Location: ../RecipeCategorization/nationality.php?error=" . urlencode($message));
                exit();
            }

            // Insert new nationality
            $sql = "INSERT INTO CuisineNationality (CuisineOrigin) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $this->Nationality);

            if ($stmt->execute()) {
                $message = "Added Successfully";
                header("Location: ../RecipeCategorization/nationality.php?success=" . urlencode($message));
                exit();
            } else {
                echo "Error in adding nationality";
            }
        } catch (Exception $error) {
            echo "Error->" . $error->getMessage();
            exit();
        }
    }

    public function EditNationality($Nationality)
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();
        $this->Nationality = $Nationality;

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "ID is Missing!";
            header("Location: ../RecipeCategorization/nationality.php?error=" . urlencode($message));
            exit();
        }

        try {
            // Check for duplicates
            if ($this->IsDuplicateNationality($this->Nationality)) {
                $message = "Already Existed!";
                header("Location: ../RecipeCategorization/nationality.php?error=" . urlencode($message));
                exit();
            }

            // Insert new nationality
            $sql = "UPDATE CuisineNationality set CuisineOrigin = ? where CuisineID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $this->Nationality, $this->Id);

            if ($stmt->execute()) {
                $message = "Edited Successfully";
                header("Location: ../RecipeCategorization/nationality.php?success=" . urlencode($message));
                exit();
            } else {
                echo "Error in editting nationality";
            }
        } catch (Exception $error) {
            echo "Error->" . $error->getMessage();
            exit();
        }
    }

    private function IsDuplicateNationality($Nationality)
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM CuisineNationality WHERE CuisineOrigin = ?");
        $stmt->bind_param('s', $Nationality);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function DeleteNationality()
    {
        $dbSetupInstance = DbSetup::getInstance();
        $conn = $dbSetupInstance->getConnection();

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "No ID found!";
            header("Location: ../RecipeCategorization/nationality.php?error=" . urlencode($message));
            exit();
        }

        $sql = "DELETE FROM cuisinenationality Where CuisineID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $this->Id);
        if ($stmt->execute()) {
            $message = "Deleted Succesfully!";
            header("Location: ../RecipeCategorization/nationality.php?success=" . urlencode($message));
            exit();
        } else {
            echo "error->" . $conn->error;
        }
    }
}
