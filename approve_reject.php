<?
include 'includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
} else if ($_SESSION['user']['roleId'] != 1) {
    header("Location: login.php");
    exit;
}

require('classes/Vacation.php');

$vacation = new Vacation();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'approve') {
        if ($vacation->approveVacation($_GET['id'])) {
            $_SESSION['flashSuccess'] = 'Vacation approved';
            header("Location: vacation_requests.php");
        }
    } else if ($_GET['action'] == 'reject') {
        if ($vacation->rejectVacation($_GET['id'])) {
            $_SESSION['flashSuccess'] = 'Vacation rejected';
            header("Location: vacation_requests.php");
        }
    }
}