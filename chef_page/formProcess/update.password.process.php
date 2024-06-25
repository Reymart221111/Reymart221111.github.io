<?php

use Classes\User;

require_once "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $oldpassword = filter_input(INPUT_POST, 'oldPassword', FILTER_SANITIZE_SPECIAL_CHARS);
    $newpassword = filter_input(INPUT_POST, 'newPassword', FILTER_SANITIZE_SPECIAL_CHARS);
    $confirmpassword = filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($oldpassword)) {
        $message = "Please Input your old passwordssss!";
        header("Location: ../profile/change_password.php?error=" . urlencode($message));
        exit();
    }

    if (empty($newpassword)) {
        $message = "Please Input your new password!";
        header("Location: ../profile/change_password.php?error=" . urlencode($message));
        exit();
    }

    if (empty($confirmpassword)) {
        $message = "Confirm Password Cannot be empty!";
        header("Location: ../profile/change_password.php?error=" . urlencode($message));
        exit();
    }

    $UpdatePassword = new User;
    $UpdatePassword->UpdatePassword($oldpassword, $newpassword, $confirmpassword);
}
