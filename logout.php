<?php
require('classes/User.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

$user = new User();
$user->logout();