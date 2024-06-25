<?php

namespace Classes;

use Classes\DbSetup;
use Exception;

class Login extends DbSetup
{
    private $Email;
    private $Password;

    public function __construct()
    {
        parent::getInstance();
    }

    public function LoginUser($Email, $Password)
    {
        $this->Email = $Email;
        $this->Password = $Password;

        try {
            $row = $this->IsEmailExist();

            if (!$row) {
                $message = "Email Does not Exist!";
                header("Location: ../../login.php?error=" . urlencode($message));
                exit();
            } else {

                if (password_verify($this->Password, $row['Password'])) {
                    session_start();

                    $_SESSION['user_id'] = $row['UserID'];
                    $_SESSION['role'] = $row['Role'];

                    if ($_SESSION['role'] === 'admin') {
                        header("Location:../../admin_page/index.php?Admin+PAge");
                        exit();
                    } elseif ($_SESSION['role'] === 'chef') {
                        header("Location:../../chef_page/index.php?chef+page");
                        exit();
                    }
                } else {
                    $message = "Incorrect Password!";
                    header("Location: ../../login.php?error=" . urlencode($message));
                    exit();
                }
            }
        } catch (Exception $error) {
            echo "Error->" . $error->getMessage();
            exit();
        }
    }


    private function IsEmailExist()
    {
        $GetConnection = DbSetup::getInstance();
        $conn = $GetConnection->getConnection();

        try {
            $sql = "SELECT * FROM users WHERE Email=?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $this->Email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return false;
            }
        } catch (Exception $error) {
            echo "Error->" . $error->getMessage();
            exit();
        }
    }
}
