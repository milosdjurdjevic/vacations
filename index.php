<?
    include('pages/includes/header.php');

    if(!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }

    // var_dump($_SESSION['user']);
    var_dump(empty($_SESSION['user'])); die;
    include('pages/includes/footer.php');