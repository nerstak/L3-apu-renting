<?php
/* Basic includes */
require_once("../../config/config.php");
require_once("adminCheck.php");

/* Specific includes */
require_once(CFG_DB_CONNECTION);
require_once(RES_SQL);
require_once(RES_UTIL);

if(!isset($_GET['id']) || $_GET['id'] < 0)  {
    header("Location: ../accounts.php");
    die();
}

if($_GET['id'] == $_SESSION['idUser']) {
    $_SESSION['errorMessage'][] = "Cannot delete own account";
    header("Location: ../accounts.php?content=edit&id=".$_GET['id']);
    die();
}

$occ = verifyUserInRental($dbConnection,$_GET['id']);
if($occ == 0) {
    deleteAccount($dbConnection,$_GET['id']);
    $_SESSION['successMessage'][] = "Account successfully deleted";
    header("Location: ../accounts.php");
} else {
    $_SESSION['errorMessage'][] = "Account is present in rentals and cannot be deleted";
    header("Location: ../accounts.php?content=edit&id=".$_GET['id']);
}
