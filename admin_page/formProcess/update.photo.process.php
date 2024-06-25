<?php

use Classes\User;

require_once '../../vendor/autoload.php';

$ProfilePic = $_FILES['Profile_Image'];

$UpdateImg = new User;
$UpdateImg->UserUpdatePhoto($ProfilePic);
