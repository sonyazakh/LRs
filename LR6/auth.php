<?php
session_start();

function checkAuth() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
}
?>
