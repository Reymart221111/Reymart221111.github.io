<?php

namespace Classes;

class Session
{
    private static $Sessions = [];

    public static function CheckSession()
    {
        session_start();
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['role'])) {
            $message = "login session not found!";
            header("Location: /Recipe_Sharing_Web/login.php?error=" . urldecode($message));
            exit();
        }
        self::$Sessions = $_SESSION;
    }

    public static function LogoutDestroySession()
    {
        session_start();
        $_SESSION = [];
        self::$Sessions = $_SESSION;
        session_unset();
        session_destroy();
    }

    public static function CheckCurrentSession()
    {
        session_start();
        if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'admin') {
                header("Location: /Recipe_Sharing_Web/admin_page/index.php");
                exit();
            } elseif ($_SESSION['role'] === 'chef') {
                header("Location: /Recipe_Sharing_Web/chef_page/index.php");
                exit();
            }
        } else {
            $message = "login session not found!";
            header("Location: /Recipe_Sharing_Web/login.php?error=" . urldecode($message));
            exit();
        }
    }

    public static function getSessionData()
    {
        return self::$Sessions;
    }

    public static function setSessionData($key, $value)
    {
        session_start();
        $_SESSION[$key] = $value;
        self::$Sessions = $_SESSION;
    }
}
