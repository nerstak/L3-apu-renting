<?php

/* Basic includes */
require_once("../../config/config.php");
require_once (CFG_DB_CONNECTION);
require_once (RES_SQL);
session_start();

//Checking variables needed are present and valid
$set = isset($_POST['idProduct']) && isset($_POST['fromDate']) && isset($_POST['toDate']);
$correct = is_numeric($_POST['idProduct']) && $_POST['idProduct'] > 0 && DateTime::createFromFormat('Y-m-d', $_POST['fromDate']) && DateTime::createFromFormat('Y-m-d', $_POST['toDate']);


var_dump($_POST);
// Declaring variables
$dateFrom = null;
$dateTo = null;
$today = getdate();
$renting = array();

// Checking inputs
if($set && $correct) {
    $dateFrom = DateTime::createFromFormat('Y-m-d', $_POST['fromDate']);
    $dateTo = DateTime::createFromFormat('Y-m-d', $_POST['toDate']);
    $renting['flag'] = true;
    if($dateFrom < $today) {
        $renting['flag'] = false;
        $_SESSION['errorMessage'][] = "Incorrect starting date";
    }
    if($dateTo < $dateFrom) {
        $renting['flag'] = false;
        $_SESSION['errorMessage'][] = "Incorrect ending date";
    }

    $renting['id'] = $_POST['idProduct'];
} else {
    $renting['flag'] = false;
    echo $set .'-'.$correct;
    $_SESSION['errorMessage'][] = "Incorrect inputs";
}

// Processing
if(empty($_SESSION['errorMessage'])) {
    $listRenting = dataSelectionBookingFromEquipment($dbConnection,$renting['id']);
    $product = dataSelectionProduct($dbConnection,$renting['id']);
    $i = 0;

    // Checking if product can be booked
    if(!$product['visible']) {
        $_SESSION['errorMessage'][] = $product['name']. ' unavailable';
    }

    // Checking availability
    foreach ($listRenting as $book) {
        $dateStartCurrent = DateTime::createFromFormat('d-m-y', $book['dateStart']);
        $dateEndCurrent = DateTime::createFromFormat('d-m-y', $book['dateEnd']);

        if(($dateTo < $dateStartCurrent || $dateEndCurrent < $dateFrom) && in_array($book['status'],['Approved','Pending'])) {
            // No overlap of period
            break;
        } else {
            $i += $book['stock'];
        }
    }

    if($i < $product['stock'] && $product['visible']) {
        // Allow transaction
        $_SESSION['booking'][] = [ 'dateStart' => $dateFrom,
            'dateEnd' => $dateTo,
            'idEquipment' => $renting['id'],
            'stock' => 1, // May be changed if we allow more than one book
        ];
        $_SESSION['successMessage'][] = "Product successfully added to basket!";
        header('Location: ../index.php');
    } else {
        $_SESSION['errorMessage'][] = "Unavailable date for product";
    }
}

header("Location: ../product.php?id=".$_POST['idProduct']);