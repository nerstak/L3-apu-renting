<?php
/* Basic includes */
require_once("config/config.php");
session_start();

/* Specific includes */
include_once(CFG_DB_CONNECTION);

/* Display */
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);


echo '<main><h1>New products!</h1><div id="productsContainer" class="container">';
    lastProductsDisplay($dbConnection,3);

echo '</div>';

include_once(VIEW_FOOTER);
include_once(VIEW_END);



/** Display n last items
 * @param mysqli $dbConnection Connection to database
 * @param $nb int Number of item to display
 */
function lastProductsDisplay(mysqli $dbConnection, $nb)
{
    $stmt = $dbConnection->query("SELECT * FROM equipment WHERE visible=1 ORDER BY idEquipment DESC LIMIT ".$nb);

    while ($row = $stmt->fetch_assoc()) {
        echo '<div class="productBox">';
        echo '<a href="products/product.php?id=' . $row['idEquipment'] . '">';
        echo '<img src="' . $row['pathImage'] . '"></a>';
        echo '<div class="centered-text"><a href="products/product.php?id=' . $row['idEquipment'] . '">' . $row['name'] . '</a></div>
            </div>';
    }
}