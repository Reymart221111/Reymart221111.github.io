<?php

namespace Classes;

class Message
{
    public static function DisplayMessage()
    {
        $error = isset($_GET['error']) ? $_GET['error'] : '';
        $success = isset($_GET['success']) ? $_GET['success'] : '';

        if (!empty($error)) {
            echo '<span style="color: red;">' . htmlspecialchars($error) . '</span>';
        } elseif (!empty($success)) {
            echo '<span style="color: green;">' . htmlspecialchars($success) . '</span>';
        }
    }
}

