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

if (isset($_POST['formSubmited'])) {
    $employee = $user->createEmployee($_POST);
}
?>
<div class="container">
        <!-- Example row of columns -->
        <div class="row">
            <br>
            <br>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="addEmployeeForm">
            <h2>Add Employee</h2>
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" placeholder="First Name" name="firstName">
                    <span id="firstNameError"></span>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" placeholder="Last Name" name="lastName">
                    <span id="lastNameError"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                    <span id="emailError"></span>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                    <span id="passwordError"></span>
                </div>
                <div class="form-group">
                    <label for="passConfirm">Confirm Password:</label>
                    <input type="password" class="form-control" id="passConfirm" placeholder="Email" name="passConfirm">
                    <span id="passConfirmError"></span>
                </div>
                <input type="hidden" name="formSubmited">
                <a href="#" class="btn btn-success" id="addEmployee">Submit</a>
            </form>
            <hr>
        </div>
    </div>
<?
    include 'includes/footer.php';
?>
<script type="application/javascript">
    $(document).ready(function () {
        
        $('#addEmployee').click(function (e) {
            e.preventDefault();

            var firstname = $('#firstName').val(),
                lastName = $('#lastName').val(),
                email = $('#email').val(),
                password = $('#password').val(),
                passConfirm = $('#passConfirm').val(),
                errors = 0;

            if (firstname == '') {
                $('#firstNameError').text('First name is required');
                errors++;
            }
            if (lastName == '') {
                $('#lastNameError').text('Last name is required');
                errors++;
            }
            if (email == '') {
                $('#emailError').text('Email is required');
                errors++;
            }
            if (password == '') {
                $('#passwordError').text('Password is required');
                errors++;
            }
            if (passConfirm == '') {
                $('#passConfirmError').text('Password confirmation is required');
                errors++;
            }
            if (password != passConfirm) {
                $('#passwordError').text('Passwords do not match');
                errors++;
            }

            if (errors == 0) {
                $('#addEmployeeForm').submit();
            }
        })
    })
</script>