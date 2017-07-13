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

$requests = $vacation->getAllRequests();
$requestHistory = $vacation->getRequestsHistory();
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
         <h3>Employee requested vacations</h3>
         <table class="table">
            <thead>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </thead>
            <tbody>
            <? 
                if ($requests):
                    foreach ($requests as $request): ?>
                    <tr>
                        <td><?= $request['firstName'] ?></td>
                        <td><?= $request['lastName'] ?></td>
                        <td><?= $request['startDate'] ?></td>
                        <td><?= $request['endDate'] ?></td>
                        <td>
                            <a href="approve_reject.php?action=approve&id=<?= $request['id'] ?>">Approve</a>
                            &nbsp;&nbsp;
                            <a href="approve_reject.php?action=reject&id=<?= $request['id'] ?>">Reject</a>
                        </td>
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

        <h3>History</h3>
        <table class="table">
            <thead>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </thead>
            <tbody>
            <? 
                if ($requestHistory):
                    foreach ($requestHistory as $request): ?>
                    <tr>
                        <td><?= $request['firstName'] ?></td>
                        <td><?= $request['lastName'] ?></td>
                        <td><?= $request['startDate'] ?></td>
                        <td><?= $request['endDate'] ?></td>
                        <td>
                            <?= $request['status'] == 'a' ? 'Approved' : 'Rejected' ?>
                        </td>
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
</div>
<? include 'includes/footer.php'; ?>