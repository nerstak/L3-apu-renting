<?php
require_once("../../config/config.php");
require_once(CFG_DB_CONNECTION);
require_once (RES_SQL);
require_once (RES_UTIL);

session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: ../portal.php");
}

// Checking URL
$set = isset($_GET['status']) && isset($_GET['id']);
$values = $_GET['status'] == "Canceled" && is_numeric($_GET['id']) && $_GET['id'] >= 0;
if($set && $values) {
    updateBookingStatus($dbConnection,$_GET['id'],$_GET['status'],$_SESSION['errorMessage']);
}
header("Location: ../index.php");