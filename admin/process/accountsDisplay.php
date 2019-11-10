<?php

/**
 * Display every account in a table
 */

echo '<div class="boxStyle">
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Role</th>
                    <th></th>
                </tr> ';
dataSelectionAccounts($dbConnection);

echo '</table></div>';

/** Display information about accounts
 * @param mysqli $dbConnection Connection to database
 */
function dataSelectionAccounts(mysqli $dbConnection) {
    if ($stmt = $dbConnection->prepare("SELECT * FROM user ORDER BY idUser DESC")) {
        $stmt->execute();
        $data = $stmt->get_result();

        while ($row = $data->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['idUser']) . '</td>';
            echo '<td>' . htmlspecialchars($row['firstname']) .' ' .htmlspecialchars($row['lastnames']). '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['address']) . '</td>';
            echo '<td>' . htmlspecialchars($row['role']) . '</td>';
            echo '<td><a href="?content=edit&id='.$row['idUser'].'"><button type="button" class="btn-default">Edit</button></a></td>';
            echo '</tr>';
        }
    }
}