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
$employee = $user->getEmployee($_SESSION['user']['id']);
?>
<div class="container">
    <br>
    <br>
    <div class="row">
        <b>First name:</b> <?= $employee[0]['firstName'] ?><br>
        <b>Last name:</b> <?= $employee[0]['lastName'] ?><br>
        <b>Email:</b> <?= $employee[0]['email'] ?><br>
        <b>Days left:</b> <?= $employee[0]['daysLeft'] ?><br>
    </div>
</div>