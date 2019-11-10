<?php

/* Basic includes */
require_once("../../config/config.php");
require_once (CFG_DB_CONNECTION);
require_once (RES_SQL);
session_start();

if(isset($_SESSION['booking']) && !empty($_SESSION['booking'])) {
    $prices = array();
    foreach ($_SESSION['booking'] as $current) {
        $listRenting = dataSelectionBookingFromEquipment($dbConnection,$current['idEquipment']);
        $product = dataSelectionProduct($dbConnection,$current['idEquipment']);

        if(!$product['visible']) {
            $_SESSION['errorMessage'][] = $product['name']. ' unavailable';
        }

        // Check availability
        $i = 0;
        foreach ($listRenting as $book) {
            $dateStartCurrent = DateTime::createFromFormat('Y-m-d', $book['dateStart']);
            $dateEndCurrent = DateTime::createFromFormat('Y-m-d', $book['dateEnd']);

            if(($current['dateEnd'] < $dateEndCurrent || $dateStartCurrent < $current['dateStart']) && in_array($book['status'],['Approved','Pending'])) {
                // No overlap of period
                break;
            } else {
                $i += $book['stock'];
            }
        }

        if(!($product['stock'] - $i >= $current['stock']) && $product['visible']) {
            $_SESSION['errorMessage'][] = $product['name'] . ' is not available anymore';
        }
    }

    // Check if full booking can processed
    if(empty($_SESSION['errorMessage'])) {
        foreach ($_SESSION['booking'] as $current) {
            if(!addBooking($dbConnection,$current,$_SESSION['idUser'],$_SESSION['errorMessage'])) {
                break;
            }
        }
        if(empty($_SESSION['errorMessage'])) {
            $_SESSION['booking'] = null;
            $_SESSION['successMessage'][] = "Order placed! Please wait for the shop to validate it";
        }
    }
    header("Location: ../index.php");
}

/** Add an unique booking into database
 * @param mysqli $dbConnection Connection to database
 * @param $book Array containing an unique booking and its information
 * @param $idUser int ID of user booking
 * @param $errors array Return errors by reference
 * @return bool Success of operation
 */
function addBooking(mysqli $dbConnection,$book,$idUser,&$errors) {
    if($stmt = $dbConnection->prepare("INSERT INTO booking(idUser, dateStart, dateEnd, idEquipment, stock, status, price) VALUES (?,?,?,?,?,?,?)")) {
        $status = "Pending";
        $dateStart = $book['dateStart']->format('d-m-y');
        $dateEnd = $book['dateEnd']->format('d-m-y');

        $product = dataSelectionProduct($dbConnection,$book['idEquipment']);


        $period = date_diff($book['dateStart'],$book['dateEnd']);
        $price = $product['priceDay'] * ($period->format('%D' ) +1) * $book['stock'];

        $stmt->bind_param('sssssss', $idUser, $dateStart, $dateEnd, $book['idEquipment'], $book['stock'], $status,$price);

        $stmt->execute();

        $stmt->close();

        return true;
    }
    $errors[] = "Unable to finish booking";
    return false;
}