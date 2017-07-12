<?
include 'includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
} else if ($_SESSION['user']['roleId'] != 2) {
    header("Location: login.php");
    exit;
}

require('classes/User.php');

$user = new User();
$employee = $user->getEmployee($_SESSION['user']['id']);
?>
<div class="container">
    <br>
    <br>
    <?
        if (isset($_SESSION['flashMessage'])) {
    ?>
        <div class="alert alert-success">
            <strong>Error!</strong> <?= $_SESSION['flashMessage'] ?>.
        </div>
    <?
        unset($_SESSION['flashMessage']);
        }
    ?>
    <div class="row">
        <b>First name:</b> <?= $employee[0]['firstName'] ?><br>
        <b>Last name:</b> <?= $employee[0]['lastName'] ?><br>
        <b>Email:</b> <?= $employee[0]['email'] ?><br>
        <b>Days left:</b> <?= $employee[0]['daysLeft'] ?><br>
    </div>
</div>