<?php
/* Basic includes */
require_once("../config/config.php");
require_once ("process/adminCheck.php");

require_once(CFG_DB_CONNECTION);

/* Display */
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);
include_once(VIEW_ADMIN_NAV);

/* Display different content according to url */
/*
if(isset($_GET['content'])) {
    switch ($_GET['content']) {
        case 'newEquipment': include_once ('process/newProduct.php');break;
        case 'products': include_once('process/productsDisplay.php');break;
        case 'edit': include_once('process/editAccount.php');break;
        default:
        case 'accounts': include_once('process/accountsDisplay.php');
    }
} else {
    include_once('process/accountsDisplay.php');
}*/
header("Location: accounts.php");


echo '</main>';

include_once(VIEW_FOOTER);
include_once(VIEW_END);