<?php
/** Update an user
 * @param $edit Array containing informations about the user
 * @param mysqli $dbConnection Mysql connection
 * @param $errorMsg String Return by reference of error
 * @param $idUser Int User to update
 * @param $redirection String page to go if success
 * @return boolean Success of the task
 */
function editProcess($edit, mysqli $dbConnection, $idUser, $redirection)
{
    if(!verifyUniquenessEmail($dbConnection,$edit['email'],$idUser)) {
        $_SESSION['errorMessage'][] = "Mail address already used";
        return false;
    }
    $query = null;
    if ($edit['passwordHash'] != null) {
        // If we change the password
        $query = "UPDATE user  SET firstname = ?, lastnames = ?, email = ?, password = ?, address = ?, role = ? WHERE idUser = ?";
    } else {
        $query = "UPDATE user  SET firstname = ?, lastnames = ?, email = ?, address = ?, role = ? WHERE idUser = ?";
    }
    if ($stmt = $dbConnection->prepare($query)) {
        if ($edit['passwordHash'] != null) {
            // If we change the password
            $stmt->bind_param('ssssssd', $edit['firstName'], $edit['lastName'], $edit['email'], $edit['passwordHash'], $edit['address'], $edit['role'], $idUser);
        } else {
            $stmt->bind_param('sssssd', $edit['firstName'], $edit['lastName'], $edit['email'], $edit['address'], $edit['role'], $idUser);
        }

        $stmt->execute();
        $_SESSION['successMessage'][] = "Account edited";
        header("Location: " . $redirection);
        return true;
    }
    $_SESSION['errorMessage'][] = "Unable to update account (user does not exists)";
    return false;
}

/** Display every booking of user
 * @param mysqli $dbConnection Connection to database
 */
function dataSelectionHistory(mysqli $dbConnection)
{
    if ($stmt = $dbConnection->prepare("SELECT * FROM booking WHERE idUser=? ORDER BY idBooking DESC")) {
        $stmt->bind_param('s', $_SESSION['idUser']);
        $stmt->execute();
        $data = $stmt->get_result();

        while ($row = $data->fetch_assoc()) {
            $name = mysqli_query($dbConnection, "SELECT name FROM equipment WHERE idEquipment=" . $row['idEquipment']);
            echo '<tr>';
            echo '<td>' . $row['idBooking'] . '</td>';
            echo '<td>' . $name->fetch_assoc()['name'] . '</td>';
            echo '<td>' . $row['dateStart'] . '</td>';
            echo '<td>' . $row['dateEnd'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            echo '<td>' . $row['price'] . '</td>';
            echo '<td>';
            if (in_array($row['status'], ["Pending", "Approved"])) {
                echo '<a href="process/statusProcess.php?status=Canceled&id=' . $row['idBooking'] . '"><button type="button" class="btn-danger">Cancel</button></a>';
            }
            echo '</td>';
            echo '</tr>';
        }
    }
}

/** Search information of an user
 * @param mysqli $dbConnection Connection to database
 * @param $idUser int ID of the user
 * @return array|null Array of data of user
 */
function dataSelectionUser(mysqli $dbConnection, $idUser)
{
    if ($stmt = $dbConnection->prepare("SELECT firstname,lastnames,email,address,role FROM user WHERE idUser=?")) {
        $data = array();
        $stmt->bind_param('s', $idUser);
        $stmt->bind_result($data['firstName'], $data['lastNames'], $data['email'], $data['address'], $data['role']);
        $stmt->execute();
        $stmt->fetch();
        return $data;
    }
    return null;
}

/** Display every products corresponding to research
 * @param mysqli $dbConnection Connection to database
 * @param null $search String of search (in name or brand)
 * @param null $type String of type of product to search
 */
function allProductsDisplay(mysqli $dbConnection, $search = NULL, $type = NULL)
{
    // Correct query
    if (isset($search) && !empty($search) && isset($type) && !empty($type)) {
        $query = "SELECT * FROM equipment WHERE visible=1 AND (name LIKE ? OR brand LIKE ?) AND type=? ORDER BY idEquipment DESC";
    } else if (isset($search) && !empty($search)) {
        $query = "SELECT * FROM equipment WHERE visible=1 AND (name LIKE ? OR brand LIKE ?) ORDER BY idEquipment DESC";
    } else if (isset($type) && !empty($type)) {
        $query = "SELECT * FROM equipment WHERE visible=1 AND type=? ORDER BY idEquipment DESC";
    } else {
        $query = "SELECT * FROM equipment WHERE visible=1 ORDER BY idEquipment DESC";
    }

    // Prepare query
    $stmt = $dbConnection->prepare($query);

    // Correct parameters
    if (isset($search) && !empty($search) && isset($type) && !empty($type)) {
        $tmp = "%" . $search . "%";
        $stmt->bind_param('sss', $tmp, $tmp, $type);
    } else if (isset($search) && !empty($search)) {
        $tmp = "%" . $search . "%";
        $stmt->bind_param('ss', $tmp, $tmp);
    } else if (isset($type) && !empty($type)) {
        $stmt->bind_param('s', $type);
    }

    $stmt->execute();
    $data = $stmt->get_result();

    // Display query
    while ($row = $data->fetch_assoc()) {
        echo '<div class="productBox">';
        echo '<a href="product.php?id=' . $row['idEquipment'] . '">';
        echo '<img src="' . $row['pathImage'] . '"></a>';
        echo '<div class="centered-text"><a href="product.php?id=' . $row['idEquipment'] . '">' . $row['name'] . '</a></div>
            </div>';
    }
}

/** Find values of an equipment (and not its part)
 * @param mysqli $dbConnection Connection to database
 * @param $idEquipment int ID of equipment
 * @return array|null Array of information
 */
function dataSelectionProduct(mysqli $dbConnection, $idEquipment)
{
    if ($stmt = $dbConnection->prepare("SELECT idEquipment, name, brand, type, pathImage, priceDay, stock, visible FROM equipment WHERE idEquipment=?")) {
        $data = array();
        $stmt->bind_param('s', $idEquipment);
        $stmt->bind_result($data['idEquipment'], $data['name'], $data['brand'], $data['type'], $data['pathImage'], $data['priceDay'], $data['stock'], $data['visible']);
        $stmt->execute();
        $stmt->fetch();
        return $data;
    }
    return null;
}

/** Find values of body
 * @param mysqli $dbConnection Connection to database
 * @param $idEquipment int ID of equipment
 * @return array|null Array of information
 */
function dataSelectionBody(mysqli $dbConnection, $idEquipment)
{
    if ($stmt = $dbConnection->prepare("SELECT sensor, weight, dimension, resolution, wireless, stabilizer FROM body WHERE idEquipment=?")) {
        $data = array();
        $stmt->bind_param('s', $idEquipment);
        $stmt->bind_result($data['sensor'], $data['weight'], $data['dimension'], $data['resolution'], $data['wireless'], $data['stabilizer']);
        $stmt->execute();
        $stmt->fetch();
        return $data;
    }
    return null;
}

/** Find values of lens
 * @param mysqli $dbConnection Connection to database
 * @param $idEquipment int ID of equipment
 * @return array|null Array of information
 */
function dataSelectionLens(mysqli $dbConnection, $idEquipment)
{
    if ($stmt = $dbConnection->prepare("SELECT mount, weight, dimension, focal, aperture, stabilizer FROM lens WHERE idEquipment=?")) {
        $data = array();
        $stmt->bind_param('s', $idEquipment);
        $stmt->bind_result($data['mount'], $data['weight'], $data['dimension'], $data['focal'], $data['aperture'], $data['stabilizer']);
        $stmt->execute();
        $stmt->fetch();
        return $data;
    }
    return null;
}

/** Find values of tripod
 * @param mysqli $dbConnection Connection to database
 * @param $idEquipment int ID of equipment
 * @return array|null Array of information
 */
function dataSelectionTripod(mysqli $dbConnection, $idEquipment)
{
    if ($stmt = $dbConnection->prepare("SELECT maxWeight, weight, dimension FROM tripod WHERE idEquipment=?")) {
        $data = array();
        $stmt->bind_param('s', $idEquipment);
        $stmt->bind_result($data['maxWeight'], $data['weight'], $data['dimension']);
        $stmt->execute();
        $stmt->fetch();
        return $data;
    }
    return null;
}

/** Search values of booking
 * @param mysqli $dbConnection Connection to database
 * @param $idEquipment int ID of booking
 * @return array|null Array of values
 */
function dataSelectionBookingFromEquipment(mysqli $dbConnection, $idEquipment)
{
    if ($stmt = $dbConnection->prepare("SELECT idBooking, dateStart, dateEnd, idEquipment, stock, idUser, status FROM booking WHERE idEquipment=?")) {
        $stmt->bind_param('s', $idEquipment);
        $stmt->execute();
        $tmp = $stmt->get_result();

        $data = array();
        while ($row = $tmp->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
    return null;
}


/** Update the status of a booking
 * @param mysqli $dbConnection Connection to database
 * @param $id int ID of the book
 * @param $newStatus string New value for the status
 * @param $errors Array return by reference
 * @return bool Success of operation
 */
function updateBookingStatus(mysqli $dbConnection, $id, $newStatus, &$errors)
{
    if ($stmt = $dbConnection->prepare("SELECT status FROM booking WHERE idBooking=?")) {
        $stmt->bind_param("s", $id);
        $stmt->bind_result($status);
        $stmt->execute();

        $stmt->fetch();
        $stmt->close();

        if (isset($status) && in_array($status, ['Pending', 'Approved', 'InUse', 'Canceled'])) {
            if ($stmt = $dbConnection->prepare("UPDATE booking SET status=? WHERE idBooking=?")) {
                $stmt->bind_param("ss", $newStatus, $id);
                $stmt->execute();
                return true;
            }
            $errors[] = "Error of connection to database (incorrect ID for update)";
            return false;
        }
        $errors[] = "Impossible to alter the actual status";
        return false;
    }
    $errors[] = "Error of connection to database (incorrect ID)";
    return false;
}

/** Verify if an email is already in database
 * @param mysqli $dbConnection Connection to database
 * @param $email String to check
 * @param int $id of user we want to exclude
 * @return bool Uniqueness of Email
 */
function verifyUniquenessEmail(mysqli $dbConnection, $email, $id = -1)
{
    if ($stmt = $dbConnection->prepare("SELECT idUser FROM user WHERE email=?")) {
        $stmt->bind_param("s", $email);
        $stmt->bind_result($status);
        $stmt->execute();

        $tmp = $stmt->get_result();
        $data = array();
        while($row = $tmp->fetch_assoc()) {
            $data[] = $row;
        }

        if (count($data) > 1) {
            return false;
        } else if (count($data) == 0) {
            return true;
        } else {
            if ($data[0]['idUser'] == $id) {
                return true;
            } else {
                return false;
            }
        }
    }
    return false;
}

function verifyUserInRental(mysqli $dbConnection, $id) {

}