<?
include 'includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
} else if ($_SESSION['user']['roleId'] != 1) {
    header("Location: login.php");
    exit;
}

require('classes/User.php');

$user = new User();
$employees = $user->getEmployees();
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
        <!-- Example row of columns -->
        <div class="row">
            <a href="add_employee.php" class="btn btn-default">Add Employee</a>
        </div>
        <div class="row">
            <br>
            <br>
            <table id="dataTable" class="table">
                <thead>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Days Left</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?
                    if ($employees):
                        foreach ($employees as $employee): ?>
                            <tr>
                                <td><?=  $employee['firstName'] ?></td>
                                <td><?=  $employee['lastName'] ?></td>
                                <td><?=  $employee['email'] ?></td>
                                <td><?=  $employee['daysLeft'] ?></td>
                                <td>
                                    <a href="edit_employee.php?id=<?=  $employee['id']?>">Edit</a>
                                    &nbsp;&nbsp;
                                    <a href="delete_employee.php?id=<?=  $employee['id']?>">Delete</a>
                                </td>
                            </tr>
                    <?  endforeach; 
                    else:
                    ?>
                    <tr>
                        <td>No employees yet. Please add one to start</td>
                    </tr>
                <? endif; ?>
                </tbody>
            </table>
        </div>
<?
include 'includes/footer.php';
?>
<script type="application/javascript">
    $(document).ready(function () {
        $('#dataTable').dataTable();
    })
</script>
