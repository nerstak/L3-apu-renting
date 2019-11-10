<?php
/* Checking parameters */
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !$_GET['id'] > 0) {
    header("Location: index.php");
}
/* Setup */
require_once("../config/config.php");
session_start();
require_once(RES_UTIL);
require_once(CFG_DB_CONNECTION);
require_once(RES_SQL);

/* Display */
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);
echo '<main>';

echoErrors();
echoSuccess();


// Display
if ($equipment = dataSelectionProduct($dbConnection, $_GET['id'])) {
    // We only display the page if the product is visible
    if ($equipment['visible'] == 1) {
        switch ($equipment['type']) {
            case 'body':
                {
                    if ($body = dataSelectionBody($dbConnection, $_GET['id'])) {
                        displayFirstPartProduct($equipment);
                        displayBodyData($body);
                        displayLastPartProduct($equipment);
                    } else {
                        errorID();
                    }
                    break;
                }
            case 'lens':
                {
                    if ($lens = dataSelectionLens($dbConnection, $_GET['id'])) {
                        displayFirstPartProduct($equipment);
                        displayLensData($lens);
                        displayLastPartProduct($equipment);
                    } else {
                        errorID();
                    }
                    break;
                }
            case 'tripod':
                {
                    if ($tripod = dataSelectionTripod($dbConnection, $_GET['id'])) {
                        displayFirstPartProduct($equipment);
                        displayTripodData($tripod);
                        displayLastPartProduct($equipment);
                    } else {
                        errorID();
                    }
                    break;
                }
            default:
                $_SESSION['errorMessage'][] = 'Error during loading';
                errorID();
                break;
        }
    } else {
        $_SESSION['errorMessage'][] = 'Unknown product';
    }
} else {
    $_SESSION['errorMessage'][] = 'Unknown product';
    errorID();
}

echo '</main>';
include_once(VIEW_FOOTER);
include_once(VIEW_END);

/**
 * Redirection in case of error
 */
function errorID()
{
    header("Location: index.php");
}

/** Display the name and brand
 * @param $equipment Array containing information of equipment
 */
function displayEquipmentData($equipment)
{
    echo '<h2>' . $equipment['name'] . ' by ' . $equipment['brand'] . '</h2>';
}

/** Display information of the body part
 * @param $body Array containing body
 */
function displayBodyData($body)
{
    echo '<ul>';
    echo '<li>Sensor: ' . $body['sensor'] . '</li>';
    echo '<li>Weight: ' . $body['weight'] . ' (g)</li>';
    echo '<li>Dimension: ' . $body['dimension'] . '</li>';
    echo '<li>Resolution: ' . $body['resolution'] . '</li>';
    if ($body['wireless']) {
        echo '<li>Wireless control</li>';
    }
    if ($body['stabilizer']) {
        echo '<li>Stabilizer inside</li>';
    }
    echo '</ul>';
}

/** Display information of the lens part
 * @param $lens Array containing lens
 */
function displayLensData($lens)
{
    echo '<ul>';
    echo '<li>Mounting: ' . $lens['mount'] . '</li>';
    echo '<li>Weight: ' . $lens['weight'] . ' (g)</li>';
    echo '<li>Dimension: ' . $lens['dimension'] . '</li>';
    echo '<li>Focal: ' . $lens['focal'] . '</li>';
    echo '<li>Aperture: ' . $lens['aperture'] . '</li>';
    if ($lens['stabilizer']) {
        echo '<li>Stabilizer inside</li>';
    }
    echo '</ul>';
}

/** Display information of the tripod part
 * @param $tripod Array containing tripod
 */
function displayTripodData($tripod)
{
    echo '<ul>';
    echo '<li>Weight: ' . $tripod['weight'] . ' (g)</li>';
    echo '<li>Maximum carried weight: ' . $tripod['maxWeight'] . ' (g)</li>';
    echo '<li>Dimension: ' . $tripod['dimension'] . '</li>';
    echo '</ul>';
}

/** Display the image and attributes of the product
 * @param $equipment Array containing equipment
 */
function displayFirstPartProduct($equipment)
{
    echo '<div class="presentationBox">';
    echo '<div class="presentationImage presentation"><img src="' . $equipment['pathImage'] . '"></div> ';

    echo '<div class="presentationText presentation boxStyle">
                <div>';
    displayEquipmentData($equipment);
}

/** Display price and date form
 * @param $equipment Array containing equipment
 */
function displayLastPartProduct($equipment)
{
    echo '</div>
                <form class="classicForm" method="POST" action="process/productCheck.php">
                    <div>
                        <h2>' . $equipment['priceDay'] . '$ / day</h2>
                        <input type="hidden" name="idProduct" value="' . $equipment['idEquipment'] . '"
                        <label>From </label><input type="date" name="fromDate" oninput="checkPeriod()" required>
                        <label>to </label><input type="date" name="toDate" oninput="checkPeriod()" required>
                    </div>
                    <p style="display:none" id="errorMessage"></p>
                    <button id="submitButton" class="btn-success" type="submit">Submit period of time</button>
                </form>
            </div>';
    echo '</div>';
}
