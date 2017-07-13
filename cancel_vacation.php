<?
include 'includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
} else if ($_SESSION['user']['roleId'] != 2) {
    header("Location: login.php");
    exit;
}

require('classes/Vacation.php');

$vacation = new Vacation();

if (isset($_GET['id'])) {
    if ($vacation->cancelRequest($_GET['id'])) {
        $_SESSION['flashSuccess'] = 'Vacation canceled';
        header("Location: overview.php");
        exit;
    } else {
        $_SESSION['flashError'] = 'Something went wrong while trying to cancel vacation!';
        header("Location: overview.php");
        exit;
    }
}