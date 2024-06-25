<?php

namespace Classes;

use Exception;

require_once __DIR__ . '/../vendor/autoload.php';


class User extends DbSetup
{
    private $Name;
    private $Email;
    private $Password;
    private $Role;
    private $allowedRoles = ['admin', 'chef'];
    private $Id;

    private $CurrentPassword;
    private $NewPassword;
    private $ConfirmPassword;

    private $imgFile;
    private $imgFolderTarget = "../../ImgUploads/user_PROFILE/"; //Use Relative Path to your File and please dont delete this comment pleaseeee...///////
    private $imgPath;

    public function __construct()
    {
        // Ensure the singleton instance is initialized
        parent::getInstance();
    }

    public function AddUser($Name, $Email, $Password, $Role)
    {
        // Get the singleton instance and establish the connection
        $dbSetupInstance = DbSetup::getInstance();
        $conn = $dbSetupInstance->getConnection();

        $this->Name = $Name;
        $this->Email = $Email;
        $this->Password = $Password;
        $this->Role = $Role;

        $hashpass = password_hash($this->Password, PASSWORD_DEFAULT);

        try {

            if (!$this->isRoleValid($this->Role)) {
                $message = "Invalid Role Hahaha nice try editing the console!";
                header("Location: ../Users.php?error=" . urlencode($message));
                exit();
            }

            if ($this->IsDupplicateEmail($this->Email)) {
                $message = "Email Already In Use!";
                header("Location: ../Users.php?error=" . urlencode($message));
                exit();
            }

            $sql = "INSERT INTO users (UserName, Email, Password, Role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param('ssss', $this->Name, $this->Email, $hashpass, $this->Role);

            if ($stmt->execute()) {
                $message = "Registered Successfully";
                header("Location: ../Users.php?success=" . urlencode($message));
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

    public function EditUser($Name, $Email, $Password, $Role)
    {
        // Get the singleton instance and establish the connection
        $dbSetupInstance = DbSetup::getInstance();
        $conn = $dbSetupInstance->getConnection();

        $fetchData = FetchDisplayData::DisplayUserSelectedData();
        $currentEmail = $fetchData['Email'];

        $this->Name = $Name;
        $this->Email = $Email;
        $this->Password = $Password;
        $this->Role = $Role;

        $hashpass = password_hash($this->Password, PASSWORD_DEFAULT);

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "No ID found!";
            header("Location: /Recipe_Sharing_Web/admin_page/Users.php?error=" . urlencode($message));
            exit();
        }
        try {
            if (!$this->isRoleValid($this->Role)) {
                $message = "Invalid Role Hahaha nice try editing the console!";
                header("Location: ../Users.php?error=" . urlencode($message));
                exit();
            }

            if ($this->Email !== $currentEmail && $this->IsDupplicateEmail($this->Email)) {
                $message = "Email Already In Use!";
                header("Location: ../Users.php?error=" . urlencode($message));
                exit();
            }

            $sql = "UPDATE users SET UserName=?, Email=?, Password=?, Role=? WHERE UserID = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param('ssssi', $this->Name, $this->Email, $hashpass, $this->Role, $this->Id);

            if ($stmt->execute()) {
                $message = "Updated Successfully";
                header("Location: ../Users.php?success=" . urlencode($message));
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

    public function DeleteUser()
    {
        $dbSetupInstance = DbSetup::getInstance();
        $conn = $dbSetupInstance->getConnection();

        if (isset($_GET['id'])) {
            $this->Id = $_GET['id'];
        } else {
            $message = "No ID found!";
            header("Location: /Recipe_Sharing_Web/admin_page/Users.php?error=" . urlencode($message));
            exit();
        }

        $sql = "DELETE FROM users Where UserID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $this->Id);
        if ($stmt->execute()) {
            $message = "Deleted Succesfully!";
            header("Location: /Recipe_Sharing_Web/admin_page/Users.php?success=" . urlencode($message));
            exit();
        } else {
            echo "error->" . $conn->error;
        }
    }

    public function UpdateAccountSettings($Name, $Email)
    {
        if (session_status() == PHP_SESSION_NONE) { //Start the Session to get the user_id Without inserting it in the function parameter.
            session_start();
        }

        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        $fetchData = FetchDisplayData::DisplayUserCurrentData();
        $currentEmail = $fetchData['Email'];

        $this->Name = $Name;
        $this->Email = $Email;

        if ($this->Email !== $currentEmail && $this->IsDupplicateEmail($this->Email)) {
            $message = "Email Already In Use!";
            header("Location: /Recipe_Sharing_Web/admin_page/profile/account_settings.php?error=" . urlencode($message));
            exit();
        }

        try {
            $sql = "UPDATE users SET UserName=?, Email=? WHERE UserID=?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $this->Name, $this->Email, $_SESSION['user_id']);

            if ($stmt->execute()) {
                $message = "Updated Succesfully";
                header("Location: /Recipe_Sharing_Web/admin_page/profile/account_settings.php?success=" . urlencode($message));
                exit();
            } else {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
        } catch (Exception $error) {
            echo "error->" . $error->getMessage();
            exit();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }

    public function UserUpdatePhoto($imgFile)
    {
        if (session_status() == PHP_SESSION_NONE) { //Start the Session to get the user_id Without inserting it in the function parameter.
            session_start();
        }

        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        $this->imgFile = $imgFile;

        $UpdateImg = new UpdateUserImg($this->imgFile, $this->imgFolderTarget);

        if ($UpdateImg->isUploadSuccessful()) {
            $this->imgPath = $UpdateImg->GetImgPath();

            try {
                $sql = "UPDATE users SET Profile_path = ? WHERE UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('si', $this->imgPath, $_SESSION['user_id']);

                if ($stmt->execute()) {
                    $message = "Image Updated Succesfully!";
                    header("Location: ../profile/update_photo.php?success=" . urlencode($message));
                    exit();
                }
            } catch (Exception $error) {
                echo "Error->" . $error->getMessage();
                exit();
            }
        } else {
            $message = $UpdateImg->getErrorMessage();
            header("Location: ../profile/update_photo.php?error=" . urlencode($message));
            exit();
        }
    }

    private function IsDupplicateEmail($Email)
    {
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM users WHERE Email = ?");
        $stmt->bind_param('s', $Email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }

    private function isRoleValid($Role)
    {
        return in_array($Role, $this->allowedRoles);
    }

    public function UpdatePassword($CurrentPassword, $NewPassword, $ConfirmPassword)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();
        $this->CurrentPassword = $CurrentPassword;
        $this->NewPassword = $NewPassword;
        $this->ConfirmPassword = $ConfirmPassword;

        if ($this->NewPassword !== $this->ConfirmPassword) {
            $message = "New Password doesn't match from the confirm password!";
            header("Location: ../profile/change_password.php?error=" . urlencode($message));
            exit();
        }

        if ($this->IsPasswordCorrect()) {
            $hashpass = password_hash($this->NewPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET Password = ? WHERE UserID = ?");
            $stmt->bind_param('si', $hashpass, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $message = "Password Updated Succesfully!";
                header("Location: ../profile/change_password.php?success=" . urlencode($message));
                exit();
            }
        } else {
            $message = "Incorrect Old Password";
            header("Location: ../profile/change_password.php?error=" . urlencode($message));
            exit();
        }
    }

    private function IsPasswordCorrect()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $Dbconnection = DbSetup::getInstance();
        $conn = $Dbconnection->getConnection();

        $stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (password_verify($this->CurrentPassword, $row['Password'])) {
            return true;
        } else {
            return false;
        }
    }
}
