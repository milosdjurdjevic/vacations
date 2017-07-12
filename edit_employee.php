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
    $employee = $user->getEmployee($_GET['id']);
} else {
    $employee = false;
}

if (isset($_POST['edituserInfo'])) {
    $res = $user->editEmployee($_POST);
    var_dump($res);
} else if (isset($_POST['changepassword'])) {
    $res = $user->changeEmployeePassword($_POST);
}

?>
    <div class="container">
        <!-- Example row of columns -->
        <div class="row">
        <? if ($employee): ?>
            <br>
            <br>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
            <h2>User Info</h2>
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" placeholder="First Name" name="firstName" value="<?= $employee[0]['firstName'] ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" placeholder="Last Name" name="lastName" value="<?= $employee[0]['lastName'] ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?= $employee[0]['email'] ?>">
                </div>
                <input type="hidden" name="employeeId" value="<?= $_GET['id'] ?>" >
                <button type="submit" class="btn btn-success" name="edituserInfo">Submit</button>
            </form>
            <hr>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
            <h2>Change Password</h2>
                <div class="form-group">
                    <label for="newPassword">First Name</label>
                    <input type="password" class="form-control" id="newPassword" placeholder="New password" name="newPassword">
                </div>
                <div class="form-group">
                    <label for="passConfirm">Last Name:</label>
                    <input type="password" class="form-control" id="passConfirm" placeholder="Confirm Password" name="passConfirm">
                </div>
                <input type="hidden" name="employeeId" value="<?= $_GET['id'] ?>" >
                <button type="submit" class="btn btn-success" name="changepassword">Submit</button>
            </form>
        <? else: ?>
            <br>
            <br>
            <p><b>We couldn't find user for you!</b></p>
        <? endif; ?>
        </div>
    </div>
<?
    include 'includes/footer.php';
?>