<?php
// Check that it is an admin account
session_start();

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == false) {
    header("Location: /Renting/admin/login.php");
}