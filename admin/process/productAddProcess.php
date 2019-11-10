<?php
/* Basic includes */
require_once("../../config/config.php");
require_once("adminCheck.php");

/* Specific includes */
require_once(CFG_DB_CONNECTION);
require_once(RES_SQL);
require_once(RES_UTIL);
require_once ('checkParts.php');

/* Processing forms */
checkProduct($product, $_SESSION['errorMessage']);
checkBody($body, $_SESSION['errorMessage']);
checkLens($lens, $_SESSION['errorMessage']);
checkTripod($tripod, $_SESSION['errorMessage']);

/* Selecting correct task */
if ($product['flag']) {
    if($body['flag'] && !$lens['flag'] && !$tripod['flag']) {
        addBody($product,$body,$_SESSION['errorMessage'],$dbConnection);
    } else if(!$body['flag'] && $lens['flag'] && !$tripod['flag']) {
        addLens($product,$lens,$_SESSION['errorMessage'],$dbConnection);
    } else if(!$body['flag'] && !$lens['flag'] && $tripod['flag']) {
        addTripod($product,$tripod,$_SESSION['errorMessage'],$dbConnection);
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
            $name = $product['name'] . '-' . $product['brand'] . random_int(0,999).'.png';
        }
        if ($product['flag'] && pictureUpload($_FILES, 'imageEquipment', $name, DIR_IMG_PROD, 30000000, $errors)) {
            $product['path'] = DIR_IMG_PROD.$name;
            $tmp = explode("\\",$product['path']);
            $product['path'] = '/' . end($tmp);
        } else {
            $product['flag'] = false;
        }

        if(isset($_POST['visibleEquipment']) && in_array($_POST['visibleEquipment'],[0,1])) {
            $product['visible'] = $_POST['visibleEquipment'];
        } else {
            $errors[] = "Incorrect visibility";
            $product['flag'] = false;
        }
    } else {
        $product['flag'] = false;
    }
}

/** Add values to equipment table
 * @param $product Array of values
 * @param $errors Array of possible errors
 * @param mysqli $dbConnection Connection to DB
 * @return bool Done
 */
function addEquipment($product, &$errors, mysqli $dbConnection) {
    if($stmt = $dbConnection->prepare("INSERT INTO equipment(name, brand, stock, pathImage, type,priceDay,visible) VALUES (?,?,?,?,?,?,?)")) {
        $stmt->bind_param('ssissii', $product['name'], $product['brand'], $product['stock'], $product['path'], $product['type'],$product['price'],$product['visible']);
        $stmt->execute();

        return $dbConnection->insert_id;
    }
    unlink($product['path']);
    return false;
}

/** Add values to body table
 * @param $product Array of values
 * @param $body Array of values
 * @param $errors Array of possible errors
 * @param mysqli $dbConnection Connection to DB
 * @return bool Done
 */
function addBody($product, $body, &$errors, mysqli $dbConnection)
{
    $product['type'] = 'body';
    if(!$id = addEquipment($product,$errors,$dbConnection)) {
        return false;
    }
    if($stmt = $dbConnection->prepare("INSERT INTO body(idEquipment,sensor,weight,dimension,resolution,wireless,stabilizer) VALUES (LAST_INSERT_ID(),?,?,?,?,?,?)")) {
        $stmt->bind_param('sdssdd', $body['sensor'], $body['weight'], $body['dimension'], $body['resolution'], $body['wireless'], $body['stabilizer']);
        $stmt->execute();

        return true;
    } else {
        unlink($product['path']);
        $dbConnection->query("DELETE * FROM equipment where idEquipment=".$id);
    }
    $errors[] = "Unable to add product";
    return false;
}

/** Add values to lens table
 * @param $product Array of values
 * @param $lens Array of values
 * @param $errors Array of possible errors
 * @param mysqli $dbConnection Connection to DB
 * @return bool Done
 */
function addLens($product, $lens, &$errors, mysqli $dbConnection)
{
    $product['type'] = 'lens';
    if(!$id = addEquipment($product,$errors,$dbConnection)) {
        return false;
    }
    if($stmt = $dbConnection->prepare("INSERT INTO lens(idEquipment,mount,weight,dimension,focal,aperture,stabilizer) VALUES (LAST_INSERT_ID(),?,?,?,?,?,?)")) {
        $stmt->bind_param('sdsssd', $lens['mount'], $lens['weight'], $lens['dimension'], $lens['focal'], $lens['aperture'], $lens['stabilizer']);
        $stmt->execute();

        return true;
    } else {
        unlink($product['path']);
        $dbConnection->query("DELETE * FROM equipment where idEquipment=".$id);
    }
    $errors[] = "Unable to add product";
    return false;
}

/** Add values to tripod table
 * @param $product Array of values
 * @param $tripod Array of values
 * @param $errors Array of possible errors
 * @param mysqli $dbConnection Connection to DB
 * @return bool Done
 */
function addTripod($product, $tripod, &$errors, mysqli $dbConnection)
{
    $product['type'] = 'tripod';
    if(!$id = addEquipment($product,$errors,$dbConnection)) {
        return false;
    }
    if($stmt = $dbConnection->prepare("INSERT INTO tripod(idEquipment,weight,dimension,maxWeight) VALUES (LAST_INSERT_ID(),?,?,?)")) {
        $stmt->bind_param('dsd', $tripod['weight'], $tripod['dimension'], $tripod['maxWeight']);
        $stmt->execute();

        echo mysqli_error($dbConnection);
        return true;
    } else {
        unlink($product['path']);
        $dbConnection->query("DELETE * FROM equipment where idEquipment=".$id);
    }
    $errors[] = "Unable to add product";
    return false;
}