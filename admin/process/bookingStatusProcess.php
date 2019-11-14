<?php

require_once("../../config/config.php");
require_once("adminCheck.php");

/* Specific includes */
require_once(CFG_DB_CONNECTION);
require_once(RES_SQL);
require_once(RES_UTIL);

// Checking URL
$set = isset($_GET['status']) && isset($_GET['id']);
$values = in_array($_GET['status'], ['Approved','InUse','Rejected','Finished']) && is_numeric($_GET['id']) && $_GET['id'] >= 0;
if($set && $values) {
    updateBookingStatus($dbConnection,$_GET['id'],$_GET['status'],$_SESSION['errorMessage']);
}
header("Location: ../booking.php");
