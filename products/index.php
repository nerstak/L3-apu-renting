<?php
/* Basic includes */
require_once("../config/config.php");
session_start();
include_once(CFG_DB_CONNECTION);
include_once (RES_SQL);
include_once (RES_UTIL);

/* Display */
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);
include_once (VIEW_SEARCH);

echo '<div id="productsContainer" class="container">';
$type = ['body','lens','tripod'];

// Select correct data to send to function
// May be overkill, but this bloc has been added during debugging
if (isset($_GET['search']) && !empty($_GET['search']) && isset($_GET['type']) && in_array($_GET['type'], $type)) {
    allProductsDisplay($dbConnection,$_GET['search'],$_GET['type']);
} else if (isset($_GET['search']) && !empty($_GET['search'])) {
    allProductsDisplay($dbConnection,$_GET['search']);
} else if (isset($_GET['type']) && in_array($_GET['type'], $type)) {
    allProductsDisplay($dbConnection,NULL,$_GET['type']);
} else {
    allProductsDisplay($dbConnection);
}


echo '</div>';

include_once(VIEW_FOOTER);
include_once(VIEW_END);
