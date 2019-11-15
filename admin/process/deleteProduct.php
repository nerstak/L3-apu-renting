<?php
/* Basic includes */
require_once("../../config/config.php");
require_once("adminCheck.php");

/* Specific includes */
require_once(CFG_DB_CONNECTION);
require_once(RES_SQL);
require_once(RES_UTIL);

if(!isset($_GET['id']) || $_GET['id'] < 0)  {
    header("Location: ../products.php");
    die();
}

if($_GET['id'] == $_SESSION['idUser']) {
    $_SESSION['errorMessage'][] = "Cannot delete own account";
    header("Location: ../products.php?content=edit&id=".$_GET['id']);
    die();
}

$occ = verifyProductInRental($dbConnection,$_GET['id']);
if($occ == 0) {
    deleteProduct($dbConnection,$_GET['id']);
    $_SESSION['successMessage'][] = "Product successfully deleted";
    header("Location: ../products.php");
} else {
    $_SESSION['errorMessage'][] = "Account is present in rentals and cannot be deleted";
    header("Location: ../products.php?content=edit&id=".$_GET['id']);
}
