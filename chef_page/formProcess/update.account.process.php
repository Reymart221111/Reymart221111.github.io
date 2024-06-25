<?php

use Classes\User;

require_once "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $Name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $Email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (empty($Name)) {
        $message = 'Please input a name.';
        header("Location: /Recipe_Sharing_Web/chef_page/profile/account_settings.php?error=" . urlencode($message));
        exit();
    }

    if (empty($Email)) {
        $message = 'Please input an Email Address.';
        header("Location: /Recipe_Sharing_Web/chef_page/profile/account_settings.php?error=" . urlencode($message));
        exit();
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please input a Valid Email Address';
        header("Location: /Recipe_Sharing_Web/chef_page/profile/account_settings.php?error=" . urlencode($message));
        exit();
    }

    $UpdateAccount = new User;
    $UpdateAccount->UpdateAccountSettings($Name, $Email);
}
