<?php

namespace Classes;

use Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Restriction extends DbSetup
{
    private $Restriction;
    private $Id;

    public function __construct()
    {
        // Ensure the singleton instance is initialized
        parent::getInstance();
    }

    public function AddRestriction($Restriction)
    {
        // Get the singleton instance and establish the connection
        $dbSetupInstance = DbSetup::getInstance();
        $conn = $dbSetupInstance->getConnection();
        $this->Restriction = $Restriction;

        try {
            if ($this->IsDupplicateRestriction($this->Restriction)) {
                $message = "Already Existed!";
                header("Location: ../RecipeCategorization/restrictions.php?error=" . urlencode($message));
                exit();
            }

            $sql = "INSERT INTO DietRestriction (Restriction) VALUES (?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param('s', $this->Restriction);

            if ($stmt->execute()) {
                $message = "Added Successfully";
                header("Location: ../RecipeCategorization/restrictions.php?success=" . urlencode($message));
                exit();
            } else {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
        } catch (Exception $error) {
            echo "Error->" . $error->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $conn->close();
        }
    }

    public function EditRestriction($Restriction)
    {
        // Get the singleton instance and establish the connection
        $dbSetupInstance = DbSetup::getInstance();
        $conn = $dbSetupInstance->getConnection();
        $this->Restriction = $Restriction;

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "ID is Missing!";
            header("Location: ../RecipeCategorization/nationality.php?error=" . urlencode($message));
            exit();
        }

        try {
            if ($this->IsDupplicateRestriction($this->Restriction)) {
                $message = "Already Existed!";
                header("Location: ../RecipeCategorization/restrictions.php?error=" . urlencode($message));
                exit();
            }

            $sql = "UPDATE DietRestriction SET Restriction = ? WHERE RestrictionID = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param('si', $this->Restriction, $this->Id);

            if ($stmt->execute()) {
                $message = "Edited Successfully";
                header("Location: ../RecipeCategorization/restrictions.php?success=" . urlencode($message));
                exit();
            } else {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
        } catch (Exception $error) {
            echo "Error->" . $error->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $conn->close();
        }
    }

    private function IsDupplicateRestriction($Restriction)
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM dietrestriction WHERE Restriction = ?");
        $stmt->bind_param('s', $Restriction);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }

    public function DeleteRestriction()
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "No ID found!";
            header("Location: ../RecipeCategorization/restrictions.php?error=" . urlencode($message));
            exit();
        }

        $sql = "DELETE FROM dietrestriction Where RestrictionID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $this->Id);
        if ($stmt->execute()) {
            $message = "Deleted Succesfully!";
            header("Location: ../RecipeCategorization/restrictions.php?success=" . urlencode($message));
            exit();
        } else {
            echo "error->" . $conn->error;
        }
    }
}
