<?php

/** Verify that the part of the form for the Body part is correct
 * @param $body Array to be filled
 * @param $errors Array of possible errors
 */
function checkBody(&$body, &$errors)
{
    if (isset($_POST['bodyForm'])) {
        $body['flag'] = true;
        if (isset($_POST['sensorBody']) && strlen($_POST['sensorBody']) < 30) {
            $body['sensor'] = $_POST['sensorBody'];
        } else {
            $errors[] = "Incorrect sensor";
            $body['flag'] = false;
        }

        if (isset($_POST['weightBody']) && $_POST['weightBody'] > 0) {
            $body['weight'] = $_POST['weightBody'];
        } else {
            $errors[] = "Incorrect weight";
            $body['flag'] = false;
        }

        if (isset($_POST['dimensionBody']) && strlen($_POST['dimensionBody']) < 30) {
            $body['dimension'] = $_POST['dimensionBody'];
        } else {
            $errors[] = "Incorrect dimension";
            $body['flag'] = false;
        }

        if (isset($_POST['resolutionBody']) && strlen($_POST['resolutionBody']) < 30) {
            $body['resolution'] = $_POST['resolutionBody'];
        } else {
            $errors[] = "Incorrect resolution";
            $body['flag'] = false;
        }

        if(isset($_POST['wirelessBody']) && in_array($_POST['wirelessBody'],[0,1])) {
            $body['wireless'] = $_POST['wirelessBody'];
        } else {
            $errors[] = "Incorrect resolution";
            $body['flag'] = false;
        }

        if(isset($_POST['stabilizerBody']) && in_array($_POST['stabilizerBody'],[0,1])) {
            $body['stabilizer'] = $_POST['stabilizerBody'];
        } else {
            $errors[] = "Incorrect stabilizer";
            $body['flag'] = false;
        }
    } else {
        $body['flag'] = false;
    }
}

/** Verify that the part of the form for the Lens part is correct
 * @param $lens Array to be filled
 * @param $errors Array of possible errors
 */
function checkLens(&$lens, &$errors)
{
    if (isset($_POST['lensForm'])) {
        $lens['flag'] = true;
        if (isset($_POST['mountLens']) && strlen($_POST['mountLens']) < 30) {
            $lens['mount'] = $_POST['mountLens'];
        } else {
            $errors[] = "Incorrect mount";
            $lens['flag'] = false;
        }

        if (isset($_POST['weightLens']) && $_POST['weightLens'] > 0) {
            $lens['weight'] = $_POST['weightLens'];
        } else {
            $errors[] = "Incorrect weight";
            $lens['flag'] = false;
        }

        if (isset($_POST['dimensionLens']) && strlen($_POST['dimensionLens']) < 30) {
            $lens['dimension'] = $_POST['dimensionLens'];
        } else {
            $errors[] = "Incorrect dimension";
            $lens['flag'] = false;
        }

        if (isset($_POST['focalLens']) && strlen($_POST['focalLens']) < 30) {
            $lens['focal'] = $_POST['focalLens'];
        } else {
            $errors[] = "Incorrect focal";
            $lens['flag'] = false;
        }

        if (isset($_POST['apertureLens']) && strlen($_POST['apertureLens']) < 30) {
            $lens['aperture'] = $_POST['apertureLens'];
        } else {
            $errors[] = "Incorrect aperture";
            $lens['flag'] = false;
        }

        if(isset($_POST['stabilizerLens']) && in_array($_POST['stabilizerLens'],[0,1])) {
            $lens['stabilizer'] = $_POST['stabilizerLens'];
        } else {
            $errors[] = "Incorrect stabilizer";
            $lens['flag'] = false;
        }
    } else {
        $lens['flag'] = false;
    }
}

/** Verify that the part of the form for the Tripod part is correct
 * @param $tripod Array to be filled
 * @param $errors Array of possible errors
 */
function checkTripod(&$tripod, &$errors)
{
    if (isset($_POST['tripodForm'])) {
        $tripod['flag'] = true;

        if (isset($_POST['weightTripod']) && $_POST['weightTripod'] > 0) {
            $tripod['weight'] = $_POST['weightTripod'];
        } else {
            $errors[] = "Incorrect weight";
            $tripod['flag'] = false;
        }

        if (isset($_POST['maxWeightTripod']) && $_POST['maxWeightTripod'] > 0) {
            $tripod['maxWeight'] = $_POST['maxWeightTripod'];
        } else {
            $errors[] = "Incorrect maximum weight";
            $tripod['flag'] = false;
        }


        if (isset($_POST['dimensionTripod']) && strlen($_POST['dimensionTripod']) < 30) {
            $tripod['dimension'] = $_POST['dimensionTripod'];
        } else {
            $errors[] = "Incorrect dimension";
            $tripod['flag'] = false;
        }
    } else {
        $tripod['flag'] = false;
    }
}