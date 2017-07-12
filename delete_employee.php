<?
include 'includes/header.php';

if (!isset($_SESSION['user'])) {
    if ($_SESSION['user']['roleId'] != 1) {
        header("Location: login.php");
        exit;
    }
}

require('classes/User.php');
$user = new User();

if (isset($_GET['id'])) {
    $res = $user->deleteEmployee($_GET['id']);
}