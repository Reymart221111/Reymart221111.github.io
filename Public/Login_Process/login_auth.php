<?php

use Classes\Login;

require_once "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $Email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $Password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($Email) && empty($Password)) {
        $message = "Email and Password Cannot be empty!";
        header('Location: ../../login.php?error=' . urlencode($message));
        exit();
    }

    if (empty($Email)) {
        $message = "Email Cannot be empty!";
        header('Location: ../../login.php?error=' . urlencode($message));
        exit();
    } elseif(!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email Adress is Invalid!";
        header('Location: ../../login.php?error=' . urlencode($message));
        exit();
    }

    if (empty($Password)) {
        $message = "Password Cannot be empty!";
        header('Location: ../../login.php?error=' . urlencode($message));
        exit();
    }
    $LoginUser = new Login;
    $LoginUser->LoginUser($Email, $Password);
}
