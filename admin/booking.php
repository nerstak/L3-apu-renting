<?php
/* Basic includes */
require_once("../config/config.php");
require_once("process/adminCheck.php");
require_once (CFG_DB_CONNECTION);
require_once (RES_SQL);
require_once (RES_UTIL);


/* Display */
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);
include_once(VIEW_ADMIN_NAV);

echo '<main>';

echoErrors();

echo '<div class="boxStyle">
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>Name User</th>
                    <th>Product</th>
                    <th>Period</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th></th>
                </tr> ';
dataSelectionBooking($dbConnection);
echo '</table>';
echo '</div>';
echo '</main>';

/** Display every booking
 * @param mysqli $dbConnection Connection to db
 */
function dataSelectionBooking(mysqli $dbConnection) {
    if ($stmt = $dbConnection->prepare("SELECT * FROM booking ORDER BY idBooking DESC")) {
        $stmt->execute();
        $data = $stmt->get_result();

        while ($row = $data->fetch_assoc()) {
            $product = dataSelectionProduct($dbConnection,$row['idEquipment']);
            $user = dataSelectionUser($dbConnection,$row['idUser']);
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['idBooking']) . '</td>';
            echo '<td>' . htmlspecialchars($user['firstName'].' '.$user['lastNames']). '</td>';
            echo '<td>' . htmlspecialchars($product['name'].' '.$product['brand']) . '</td>';
            echo '<td>' . $row['dateStart'] . ' - ' . $row['dateEnd'] . '</td>';
            echo '<td>' . htmlspecialchars($row['price']) . '</td>';
            echo '<td>' . htmlspecialchars($row['status']) . '</td>';
            echo '<td>';
            if($row['status'] == 'Pending') {
                echo '<a href="process/bookingStatusProcess.php?status=Approved&id='.$row['idBooking'].'"><button type="button" class="btn-success">Approve</button></a>';
                echo '<a href="process/bookingStatusProcess.php?status=Rejected&id='.$row['idBooking'].'"><button type="button" class="btn-danger">Reject</button></a>';
            }else if($row['status'] == 'Approved') {
                echo '<a href="process/bookingStatusProcess.php?status=InUse&id='.$row['idBooking'].'"><button type="button" class="btn-primary">In Use</button></a>';
            }else if($row['status'] == 'InUse') {
                echo '<a href="process/bookingStatusProcess.php?status=Finished&id='.$row['idBooking'].'"><button type="button" class="btn-primary">Finish</button></a>';
            }
            echo '</td>';
            echo '</tr>';
        }
    }
}