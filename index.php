<?
    include 'includes/header.php';

    if(!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
?>

<?
    include 'includes/footer.php';