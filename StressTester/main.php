<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'Attack') {
        $_SESSION['attacking'] = true;
        $_SESSION['victim'] = $_POST['victim'];
        $_SESSION['amount'] = $_POST['amount'];
        $_SESSION['seconds'] = $_POST['seconds'];
        header("Location: attack_process.php");
        exit;
    } elseif ($action == 'Stop') {
        $_SESSION['attacking'] = false;
        header("Location: index.html");
        exit;
    }
} else {
    echo 'Nothing happens';
}
?>
