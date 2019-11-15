<?php
/* Basic includes */
require_once("../config/config.php");
require_once("process/adminCheck.php");

/* Specific includes */
require_once(CFG_DB_CONNECTION);
require_once (RES_UTIL);
require_once(RES_SQL);




/* Display */
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);
include_once(VIEW_ADMIN_NAV);

echoErrors();
echoSuccess();

// Display different contents
if (isset($_GET['content']) && $_GET['content'] == 'editEquipment' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    include_once('process/editProduct.php');
} else if (isset($_GET['content']) && $_GET['content'] == 'newEquipment' && isset($_GET['type']) && in_array($_GET['type'], ['body', 'lens', 'tripod'])) {
    include_once('process/newProduct.php');
} else {
    include_once('process/productsDisplay.php');
}


echo '</main>';

include_once(VIEW_FOOTER);
include_once(VIEW_END);
