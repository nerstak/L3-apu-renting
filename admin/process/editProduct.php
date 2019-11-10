<?php

/**
 * Display form to edit a product
 */

// Checking URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['errorMessage'][] = 'Incorrect ID of product';
    header("Location: ../products/php");
} else if (!$product = dataSelectionProduct($dbConnection, $_GET['id'])) {
    $_SESSION['errorMessage'][] = 'No product existing with this ID';
    header("Location: ../products.php");
}

/* Display */
echo '<div class="boxStyle">';
echo '<form enctype="multipart/form-data" action="process/productEditProcess.php" method="POST">';

// Select correct form
switch ($product['type']) {
    case 'body':
        {
            if ($body = dataSelectionBody($dbConnection, $product['idEquipment'])) {
                productForm($product);
                bodyForm($body);
            }
            break;
        }
    case 'lens':
        {
            if ($lens = dataSelectionLens($dbConnection, $product['idEquipment'])) {
                productForm($product);
                lensForm($lens);
            }
            break;
        }
    case 'tripod':
        {
            if ($tripod = dataSelectionTripod($dbConnection, $product['idEquipment'])) {
                productForm($product);
                tripodForm($tripod);
            }
            break;
        }
    default:
        {
            $_SESSION['errorMessage'][] = 'No product existing with this ID';
            header("Location: ../products.php");
        }
}


echo '<button type="submit" class="btn-success">Update</button>';
echo '</form></div>';

/** Display information in the form
 * @param $product Array containing information of equipment
 */
function productForm($product)
{
    echo '<input type="hidden" name="productForm">';
    echo '<input type="hidden" name="idEquipment" value="'.$_GET['id'].'">';
    echo '<div><label>Name: </label><br><input type="text" name="nameEquipment" required value="' . $product['name'] . '"></div>';
    echo '<div><label>Brand: </label><br><input type="text" name="brandEquipment" required value="' . $product['brand'] . '"></div>';
    echo '<div><label>Stock: </label><br><input type="number" name="stockEquipment" required value="' . $product['stock'] . '"></div>';
    echo '<div><label>Price: </label><br><input type="number" name="priceEquipment" required value="' . $product['priceDay'] . '"></div>';
    echo '<div class="productBox"><img src="' . $product['pathImage'] . '"></div>';
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />';
    echo '<div><label>Image: </label><br><input type="file" name="imageEquipment"></div>';
    $select = $product['visible'] == true ? "selected" : "";
    echo '<div><label>Visible: </label><br><select name="visibleEquipment"><option value="0">False</option><option value="1" ' . $select . '>True</option></select></div>';

}

/** Display information in the form
 * @param $body Array containing information of body
 */
function bodyForm($body)
{
    echo '<div><label>Sensor: </label><br><input type="text" name="sensorBody" required value="' . $body['sensor'] . '"></div>';
    echo '<div><label>Weight: </label><br><input type="number" name="weightBody" required value="' . $body['weight'] . '"></div>';
    echo '<div><label>Dimension: </label><br><input type="text" name="dimensionBody" required value="' . $body['dimension'] . '"></div>';
    echo '<div><label>Resolution: </label><br><input type="text" name="resolutionBody" required value="' . $body['resolution'] . '"></div>';
    $select = $body['wireless'] == true ? "selected" : "";
    echo '<div><label>Wireless: </label><br><select name="wirelessBody"><option value="0">False</option><option value="1" ' . $select . '>True</option></select></div>';
    $select = $body['stabilizer'] == true ? "selected" : "";
    echo '<div><label>Stabilizer: </label><br><select name="stabilizerBody"><option value="0">False</option><option value="1" ' . $select . '>True</option></select></div>';
    echo '<input type="hidden" name="bodyForm">';
}

/** Display information in the form
 * @param $lens Array containing information of lens
 */
function lensForm($lens)
{
    echo '<div><label>Mount: </label><br><input type="text" name="mountLens" required value="' . $lens['mount'] . '"></div>';
    echo '<div><label>Weight: </label><br><input type="number" name="weightLens" required value="' . $lens['weight'] . '"></div>';
    echo '<div><label>Dimension: </label><br><input type="text" name="dimensionLens" required value="' . $lens['dimension'] . '"></div>';
    echo '<div><label>Focal: </label><br><input type="text" name="focalLens" required value="' . $lens['focal'] . '"></div>';
    echo '<div><label>Aperture: </label><br><input type="text" name="apertureLens" required value="' . $lens['aperture'] . '"></div>';
    $select = $lens['stabilizer'] == true ? "selected" : "";
    echo '<div><label>Stabilizer: </label><br><select name="stabilizerLens"><option value="0">False</option><option value="1" '. $select . '>True</option></select></div>';
    echo '<input type="hidden" name="lensForm">';
}

/** Display information in the form
 * @param $tripod Array containing information of tripod
 */
function tripodForm($tripod) {
    echo '<div><label>Weight: </label><br><input type="number" name="weightTripod" required value="' . $tripod['weight'] . '"></div>';
    echo '<div><label>Maximum weight: </label><br><input type="number" name="maxWeightTripod" required value="' . $tripod['maxWeight'] . '"></div>';
    echo '<div><label>Dimension: </label><br><input type="text" name="dimensionTripod" required value="' . $tripod['dimension'] . '"></div>';
    echo '<input type="hidden" name="tripodForm">';
}