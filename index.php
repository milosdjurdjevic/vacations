<?
    include 'pages/includes/header.php';

    if(!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
?>

<?
    include 'pages/includes/footer.php';