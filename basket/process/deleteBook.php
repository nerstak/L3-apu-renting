<?php
require_once("../../config/config.php");
session_start();

if(isset($_GET['id']) && isset($_SESSION['booking']) && isset($_SESSION['booking'][$_GET['id']])) {
    unset($_SESSION['booking'][$_GET['id']]);
}

header("Location: ../index.php");