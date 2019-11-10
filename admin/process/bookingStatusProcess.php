<?php

require_once("../../config/config.php");
require_once("adminCheck.php");

/* Specific includes */
require_once(CFG_DB_CONNECTION);
require_once(RES_SQL);
require_once(RES_UTIL);

// Checking URL
$set = isset($_GET['status']) && isset($_GET['id']);
$values = in_array($_GET['status'], ['Approved','InUse','Finished']) && is_numeric($_GET['id']) && $_GET['id'] >= 0;
if($set && $values) {
    updateBookingStatus($dbConnection,$_GET['id'],$_GET['status'],$_SESSION['errorMessage']);
}
header("Location: ../booking.php");

/** Update the status of a booking
 * @param mysqli $dbConnection Connection to database
 * @param $id int ID of the book
 * @param $newStatus string New value for the status
 * @param $errors Array return by reference
 * @return bool Success of operation
 */
function updateBookingStatus(mysqli $dbConnection,$id,$newStatus,&$errors) {
    if($stmt = $dbConnection->prepare("SELECT status FROM booking WHERE idBooking=?")) {
        $stmt->bind_param("s",$id);
        $stmt->bind_result($status);
        $stmt->execute();

        $stmt->fetch();
        $stmt->close();

        if(isset($status) && in_array($status,['Pending','Approved','InUse'])) {
            if($stmt = $dbConnection->prepare("UPDATE booking SET status=? WHERE idBooking=?")) {
                $stmt->bind_param("ss",$newStatus,$id);
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