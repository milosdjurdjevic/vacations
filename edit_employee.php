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
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="editEmployeeForm">
            <h2>User Info</h2>
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" placeholder="First Name" name="firstName" value="<?= $employee[0]['firstName'] ?>">
                    <span id="firstNameError"></span>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" placeholder="Last Name" name="lastName" value="<?= $employee[0]['lastName'] ?>">
                    <span id="lastNameError"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?= $employee[0]['email'] ?>">
                    <span id="emailError"></span>
                </div>
                <input type="hidden" name="employeeId" value="<?= $_GET['id'] ?>" >
                <input type="hidden" name="edituserInfo" >
                <button type="submit" class="btn btn-success" name="edituserInfo" id="editEmployeeBtn">Submit</button>
            </form>
            <hr>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="changePasswordForm">
            <h2>Change Password</h2>
                <div class="form-group">
                    <label for="newPassword">First Name</label>
                    <input type="password" class="form-control" id="newPassword" placeholder="New password" name="newPassword">
                    <span id="passwordError"></span>
                    <span id="passwordMismatch"></span>
                </div>
                <div class="form-group">
                    <label for="passConfirm">Last Name:</label>
                    <input type="password" class="form-control" id="passConfirm" placeholder="Confirm Password" name="passConfirm">
                    <span id="passConfirmError"></span>
                </div>
                <input type="hidden" name="employeeId" value="<?= $_GET['id'] ?>" >
                <input type="hidden" name="changepassword" >
                <button type="submit" class="btn btn-success" name="changepassword" id="changePassBtn">Submit</button>
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
<script type="application/javascript">
    $(document).ready(function () {
        
        // Validate password
        $('#changePassBtn').click(function (e) {
            e.preventDefault();

            var password = $('#newPassword').val(),
                passConfirm = $('#passConfirm').val(),
                errors = 0;

            if (password == '') {
                $('#passwordError').text('Password is required');
                errors++;
            } else {
                $('#passwordError').text('');
            }
            if (passConfirm == '') {
                $('#passConfirmError').text('Password confirmation is required');
                errors++;
            } else {
                $('#passConfirmError').text('');
            }
            if (password != passConfirm) {
                $('#passwordMismatch').text('Passwords do not match');
                errors++;
            } else {
                $('#passwordMismatch').text('');
            }
            console.log(errors);
            if (errors == 0) {
                $('#changePasswordForm').submit();
            } else {

            }
        })

        // Validate employee
        $('#editEmployeeBtn').click(function (e) {
            e.preventDefault();

            var firstName = $('#firstName').val(),
                lastName = $('#lastName').val(),
                email = $('#email').val(),
                errors = 0;

            if (password == '') {
                $('#passwordError').text('Password is required');
                errors++;
            } else {
                $('#passwordError').text('');
            }
            if (passConfirm == '') {
                $('#passConfirmError').text('Password confirmation is required');
                errors++;
            } else {
                $('#passConfirmError').text('');
            }
            if (password != passConfirm) {
                $('#passwordMismatch').text('Passwords do not match');
                errors++;
            } else {
                $('#passwordMismatch').text('');
            }
            console.log(errors);
            if (errors == 0) {
                $('#editEmployeeForm').submit();
            } else {

            }
        })
    })
</script>