<?php

/**
 * Display every equipment in a table
 */

echo '<div class="boxStyle">';
echo '<a href="?content=newEquipment&type=body"><button type="button" class="btn-default">New body</button></a>';
echo '<a href="?content=newEquipment&type=lens"><button type="button" class="btn-default">New lens</button></a>';
echo '<a href="?content=newEquipment&type=tripod"><button type="button" class="btn-default">New tripod</button></a>';
echo '</div>';

echo '<div class="boxStyle">
            <table>
                <tr>
                    <th>Equipment ID</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Type</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th></th>
                </tr> ';
    dataSelectionEquipment($dbConnection);
    echo '</table>';
echo '</div>';

/** Display every equipment
 * @param mysqli $dbConnection Connection to database
 */
function dataSelectionEquipment(mysqli $dbConnection) {
    if ($stmt = $dbConnection->prepare("SELECT * FROM equipment ORDER BY idEquipment DESC")) {
        $stmt->execute();
        $data = $stmt->get_result();

        while ($row = $data->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['idEquipment']) . '</td>';
            echo '<td>' . htmlspecialchars($row['name']). '</td>';
            echo '<td>' . htmlspecialchars($row['brand']) . '</td>';
            echo '<td>' . htmlspecialchars($row['type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['stock']) . '</td>';
            echo '<td>' . htmlspecialchars($row['priceDay']). '</td>';
            echo '<td><a href="?content=editEquipment&id='.$row['idEquipment'].'"><button type="button" class="btn-default">Edit</button></a></td>';
            echo '</tr>';
        }
    }
}