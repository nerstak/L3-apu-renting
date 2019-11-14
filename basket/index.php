<?php

/* Basic includes */
require_once("../config/config.php");
session_start();
require_once(CFG_DB_CONNECTION);
require_once(RES_UTIL);
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);
echo '<main>';

echoErrors();

// Display
if (isset($_SESSION['booking']) && !empty($_SESSION['booking'])) {
    echo '<div class="boxStyle">
            <h3>Basket</h3>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Brand</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Price</th>
                    <th></th>
                </tr> ';
    dataBookDisplay($dbConnection, $_SESSION['booking']);
    echo '</table>';

    if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
        echo '<a href="process/checkBooking.php"><button type="button" class="btn-success" style="width: 100%;">Validate</button></a>';
    } else {
        echo '<div class="errorMessage"><p>Please log in before hand</p></div>';
        echo '<button type="button" disabled style="width: 100%;">Validate</button>';
    }

    echo '</div>';
} else if (isset($_SESSION['successMessage']) && !empty($_SESSION['successMessage'])) {
    echoSuccess();
} else {
    echo '<div class="errorMessage">Please add products to your basket</div>';
}

echo '</main>';


include_once(VIEW_FOOTER);
include_once(VIEW_END);



/** Display bookings in basket
 * @param mysqli $dbConnection Connection to database
 * @param $listBook Array of booking to display
 */
function dataBookDisplay(mysqli $dbConnection, $listBook)
{
    $total = 0;
    $i = 0;
    foreach ($listBook as $singleBook) {
        if ($stmt = $dbConnection->prepare("SELECT * FROM equipment where idEquipment=?")) {
            $stmt->bind_param('s', $singleBook['idEquipment']);
            $stmt->execute();
            $data = $stmt->get_result();

            while ($row = $data->fetch_assoc()) {
                $time = date_diff($singleBook['dateStart'], $singleBook['dateEnd']);
                $price = htmlspecialchars($row['priceDay']) * ($time->format('%D')+1);
                $total += $price;
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['brand']) . '</td>';
                echo '<td>' . htmlspecialchars($singleBook['dateStart']->format('d-m-y')) . '</td>';
                echo '<td>' . htmlspecialchars($singleBook['dateEnd']->format('d-m-y')) . '</td>';
                echo '<td>' . htmlspecialchars($price) . '</td>';
                echo '<td><a href="process/deleteBook.php?id=' . $i++ . '"><button type="button" class="btn-danger">Delete</button></a></td>';
                echo '</tr>';
            }
        }
    }

    echo '<tr>';
    echo '<td>Total</td>';
    for ($i = 0; $i < 3; $i++) {
        echo '<td></td>';
    }
    echo '<td>' . $total . '</td>';
    echo '</tr>';

}