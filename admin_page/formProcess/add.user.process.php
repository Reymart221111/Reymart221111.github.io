<?php

use Classes\User;

require_once "../../vendor/autoload.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $Name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $Email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $Password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $Role = filter_input(INPUT_POST, 'user_role', FILTER_SANITIZE_SPECIAL_CHARS);

    // Name Validation
    if (empty($Name)) {
        $message = 'Please input a name.';
        header("Location: ../UserAction/AddUser.php?error=" . urlencode($message));
        exit();
    }

    // Email Validation
    if (empty($Email)) {
        $message = 'Please input an email.';
        header("Location: ../UserAction/AddUser.php?error=" . urlencode($message));
        exit();
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email address.';
        header("Location: ../UserAction/AddUser.php?error=" . urlencode($message));
        exit();
    }

    // Password Validation
    if (empty($Password)) {
        $message = 'Please input a password.';
        header("Location: ../UserAction/AddUser.php?error=" . urlencode($message));
        exit();
    } elseif (strlen($Password) < 8) {
        $message = 'Password must be at least 8 characters long.';
        header("Location: ../UserAction/AddUser.php?error=" . urlencode($message));
        exit();
    }

    // Role Validation
    if (empty($Role)) {
        $message = 'Please select a role.';
        header("Location: ../UserAction/AddUser.php?error=" . urlencode($message));
        exit();
    }

    // Proceed to add the user if no errors
    $AddUser = new User;
    $AddUser->AddUser($Name, $Email, $Password, $Role);
}
