<?
include 'includes/header.php';

if (!isset($_SESSION['user'])) {
    if ($_SESSION['user']['roleId'] != 1) {
        header("Location: login.php");
        exit;
    }
}
var_dump($_SESSION);
require('classes/Vacation.php');

$vacation = new Vacation();

if (isset($_POST['requestVacation'])) {
    $res = $vacation->vacation($_POST['startDate'], $_POST['endDate']);
}
?>
<div class="container">
    <br>
    <br>
    <?
        if (isset($_SESSION['flashMessage'])) {
    ?>
        <div class="alert alert-success">
            <strong>Success!</strong> <?= $_SESSION['flashMessage'] ?>.
        </div>
    <?
            unset($_SESSION['flashMessage']);
        }
    ?>
    <div class="row">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="editEmployeeForm">
            <h2>Request a Vacation</h2>
                <div class="form-group">
                    <label for="startDate">Start Date:</label>
                    <input type="text" class="form-control" id="startDate" placeholder="Start Date" name="startDate" >
                    <span id="firstNameError"></span>
                </div>
                <div class="form-group">
                    <label for="endDate">End Date:</label>
                    <input type="text" class="form-control" id="endDate" placeholder="End Date" name="endDate">
                    <span id="lastNameError"></span>
                </div>
                <button type="submit" class="btn btn-success" name="requestVacation" id="requestVacation">Request</button>
            </form>
    </div>
</div>

<?
    include 'includes/footer.php';
?>
<script>
  $( function() {
    $( "#startDate" ).datepicker();
    $( "#endDate" ).datepicker();
  } );
</script>