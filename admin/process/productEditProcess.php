<?php

/* Basic includes */
require_once("../../config/config.php");
require_once("adminCheck.php");

/* Specific includes */
require_once(CFG_DB_CONNECTION);
require_once(RES_SQL);
require_once(RES_UTIL);
require_once('checkParts.php');

/* Processing forms */
checkProduct($product, $_SESSION['errorMessage']);
checkBody($body, $_SESSION['errorMessage']);
checkLens($lens, $_SESSION['errorMessage']);
checkTripod($tripod, $_SESSION['errorMessage']);

/* Selecting correct task */
if ($product['flag']) {
    if ($body['flag'] && !$lens['flag'] && !$tripod['flag']) {
        updateBody($product, $body, $_SESSION['errorMessage'], $dbConnection);
    } else if (!$body['flag'] && $lens['flag'] && !$tripod['flag']) {
        updateLens($product, $lens, $_SESSION['errorMessage'], $dbConnection);
    } else if (!$body['flag'] && !$lens['flag'] && $tripod['flag']) {
        updateTripod($product, $tripod, $_SESSION['errorMessage'], $dbConnection);
    }
}

header('Location: ../products.php');

/** Verify that the part of the form for the Equipment part is correct
 * @param $product Array to be filled
 * @param $errors Array of possible errors
 * @throws Exception
 */
function checkProduct(&$product, &$errors)
{
    if (isset($_POST['productForm'])) {
        $product['flag'] = true;
        if (isset($_POST['nameEquipment']) && strlen($_POST['nameEquipment']) < 50) {
            $product['name'] = $_POST['nameEquipment'];
        } else {
            $errors[] = "Incorrect name";
            $product['flag'] = false;
        }

        if (isset($_POST['brandEquipment']) && strlen($_POST['brandEquipment']) < 50) {
            $product['brand'] = $_POST['brandEquipment'];
        } else {
            $errors[] = "Incorrect brand";
            $product['flag'] = false;
        }

        if (isset($_POST['stockEquipment']) && is_numeric($_POST['stockEquipment']) && $_POST['stockEquipment'] > 0) {
            $product['stock'] = $_POST['stockEquipment'];
        } else {
            $errors[] = "Incorrect stock";
            $product['flag'] = false;
        }

        if (isset($_POST['priceEquipment']) && is_numeric($_POST['priceEquipment']) && $_POST['priceEquipment'] > 0) {
            $product['price'] = $_POST['priceEquipment'];
        } else {
            $errors[] = "Incorrect price";
            $product['flag'] = false;
        }

        if ($product['flag']) {
            $name = $product['name'] . '-' . $product['brand'] . random_int(0, 999) . '.png';
        }
        if ($product['flag'] && isset($_FILES['imageEquipment']) && !$_FILES['imageEquipment']['size'] == 0 && pictureUpload($_FILES, 'imageEquipment', $name, DIR_IMG_PROD, 30000000, $errors)) {
            $product['path'] = DIR_IMG_PROD . $name;
            $tmp = explode("\\", $product['path']);
            $product['path'] = '/' . end($tmp);
        } else {
            $product['path'] = null;
        }

        if (isset($_POST['visibleEquipment']) && in_array($_POST['visibleEquipment'], [0, 1])) {
            $product['visible'] = $_POST['visibleEquipment'];
        } else {
            $errors[] = "Incorrect visibility";
            $product['flag'] = false;
        }

        if (isset($_POST['idEquipment']) && is_numeric($_POST['idEquipment']) && $_POST['idEquipment'] >= 0) {
            $product['id'] = $_POST['idEquipment'];
        } else {
            $errors[] = "Incorrect ID";
            $product['flag'] = false;
        }
    } else {
        $product['flag'] = false;
    }
}

/** Update values of equipment table
 * @param $product Array of values
 * @param $errors Array of possible errors
 * @param mysqli $dbConnection Connection to DB
 * @return bool Done
 */
function updateEquipment($product, &$errors, mysqli $dbConnection)
{
    if ($product['path']) {
        $query = 'UPDATE equipment SET name = ?, brand = ?, stock = ?, pathImage=?,priceDay=?,visible=? WHERE idEquipment=?';
    } else {
        $query = 'UPDATE equipment SET name = ?, brand = ?, stock = ?,priceDay=?,visible=? WHERE idEquipment=?';
    }
    if ($stmt = $dbConnection->prepare($query)) {
        if ($product['path']) {
            $stmt->bind_param('ssisiii', $product['name'], $product['brand'], $product['stock'], $product['path'], $product['price'], $product['visible'], $product['id']);
        } else {
            $stmt->bind_param('ssiiii', $product['name'], $product['brand'], $product['stock'], $product['price'], $product['visible'], $product['id']);
        }
        $stmt->execute();
        $stmt->close();
        return $product['id'];
    }
    unlink($product['path']);
    return false;
}

/** Update values to body table
 * @param $product Array of values
 * @param $body Array of values
 * @param $errors Array of possible errors
 * @param mysqli $dbConnection Connection to DB
 * @return bool Done
 */
function updateBody($product, $body, &$errors, mysqli $dbConnection)
{
    if(!$id = updateEquipment($product,$errors,$dbConnection)) {
        return false;
    }
    if($stmt = $dbConnection->prepare("UPDATE body SET sensor=?,weight=?,dimension=?,resolution=?,wireless= ?,stabilizer = ? WHERE idEquipment=?")) {
        $stmt->bind_param('sissiii', $body['sensor'], $body['weight'], $body['dimension'], $body['resolution'], $body['wireless'], $body['stabilizer'],$id);
        $stmt->execute();
        $stmt->close();

        return true;
    }
    $errors[] = "Unable to add product";
    return false;
}

/** Update values to lens table
 * @param $product Array of values
 * @param $lens Array of values
 * @param $errors Array of possible errors
 * @param mysqli $dbConnection Connection to DB
 * @return bool Done
 */
function updateLens($product, $lens, &$errors, mysqli $dbConnection)
{
    if(!$id = updateEquipment($product,$errors,$dbConnection)) {
        return false;
    }
    if($stmt = $dbConnection->prepare("UPDATE lens SET mount=?,weight=?,dimension=?,focal=?,aperture=?,stabilizer=? WHERE idEquipment=?")) {
        $stmt->bind_param('sisssii', $lens['mount'], $lens['weight'], $lens['dimension'], $lens['focal'], $lens['aperture'], $lens['stabilizer'],$id);
        $stmt->execute();
        $stmt->close();

        return true;
    }
    $errors[] = "Unable to add product";
    return false;
}

/** Update values to tripod table
 * @param $product Array of values
 * @param $tripod Array of values
 * @param $errors Array of possible errors
 * @param mysqli $dbConnection Connection to DB
 * @return bool Done
 */
function updateTripod($product, $tripod, &$errors, mysqli $dbConnection)
{
    if(!$id = updateEquipment($product,$errors,$dbConnection)) {
        return false;
    }
    if($stmt = $dbConnection->prepare("UPDATE tripod SET weight=?,dimension=?,maxWeight=? WHERE idEquipment=?")) {
        $stmt->bind_param('dsd', $tripod['weight'], $tripod['dimension'], $tripod['maxWeight'],$id);
        $stmt->execute();
        $stmt->close();

        return true;
    }
    $errors[] = "Unable to add product";
    return false;
}