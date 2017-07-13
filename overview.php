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
require('classes/Vacation.php');

$user = new User();
$vacation = new Vacation();

$employee = $user->getEmployee($_SESSION['user']['id']);
$onReview = $vacation->getVacationsOnReview();
$approved = $vacation->getApprovedVacations();
$rejected = $vacation->getRejectedVacations();
?>
<div class="container">
    <br>
    <br>
    <?
        if (isset($_SESSION['flashSuccess'])) {
    ?>
        <div class="alert alert-success">
            <strong>Success!</strong> <?= $_SESSION['flashSuccess'] ?>.
        </div>
    <?
        unset($_SESSION['flashSuccess']);
        } else if (isset($_SESSION['flashError'])) {
    ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> <?= $_SESSION['flashError'] ?>.
        </div>
    <?
        unset($_SESSION['flashError']);
        }
    ?>
    <div class="row">
        <b>First name:</b> <?= $employee[0]['firstName'] ?><br>
        <b>Last name:</b> <?= $employee[0]['lastName'] ?><br>
        <b>Email:</b> <?= $employee[0]['email'] ?><br>
        <b>Days left:</b> <?= $employee[0]['daysLeft'] ?><br>
    </div>
    <hr>
    <div class="row">
         <h3>Vacation/s on review</h3>
         <table class="table">
            <thead>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Cacnel request</th>
            </thead>
            <tbody>
            <? 
                if ($onReview):
                    foreach ($onReview as $item): ?>
                    <tr>
                        <td><?= $item['startDate'] ?></td>
                        <td><?= $item['endDate'] ?></td>
                        <td><a href="cancel_vacation.php?id=<?= $item['id'] ?>">Cancel</a></td>
                    </tr>
            <? 
                    endforeach; 
                else:
            ?>
                <tr>
                    <td>No requested vacations yet.</td>
                </tr>
            <? endif; ?>
            </tbody>
         </table>
    </div>
    <hr>
    <div class="row">
         <h3>Approved vacation/s</h3>
         <table class="table">
            <thead>
                <th>Start Date</th>
                <th>End Date</th>
            </thead>
            <tbody>
            <? 
                if ($approved):
                    foreach ($approved as $a): ?>
                    <tr>
                        <td><?= $a['startDate'] ?></td>
                        <td><?= $a['endDate'] ?></td>
                    </tr>
            <? 
                    endforeach; 
                else:
            ?>
                <tr>
                    <td>No approved vacations yet.</td>
                </tr>
            <? endif; ?>
            </tbody>
         </table>
    </div>
    <hr>
    <div class="row">
         <h3>Rejected vacation/s</h3>
         <table class="table">
            <thead>
                <th>Start Date</th>
                <th>End Date</th>
            </thead>
            <tbody>
            <? 
                if ($rejected):
                    foreach ($rejected as $r): ?>
                    <tr>
                        <td><?= $r['startDate'] ?></td>
                        <td><?= $r['endDate'] ?></td>
                    </tr>
            <? 
                    endforeach; 
                else:
            ?>
                <tr>
                    <td>No rejected vacations yet.</td>
                </tr>
            <? endif; ?>
            </tbody>
         </table>
    </div>
</div>
<? include 'includes/footer.php'; ?>