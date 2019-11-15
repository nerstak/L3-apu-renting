<?php
// Check that it is an admin account
session_start();

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == false) {
    $_SESSION['errorMessage'][] = "You must be a logged admin";
    header("Location: /Renting/account/index.php");
}