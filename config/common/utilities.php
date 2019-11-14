<?php
/** Upload picture
 * @param $files Array containing files ($_FILES)
 * @param $nameField string of the file inside array
 * @param $newName string name to give once uploaded
 * @param $directoryToMove string path to place image
 * @param $maxSize int maximum size
 * @param $errors Array of errors
 * @return bool Success of operation
 */
function pictureUpload($files, $nameField, $newName, $directoryToMove, $maxSize, &$errors) {
    $fileExtensionsAllowed = ['jpeg','jpg','png'];
    $fileName = $files[$nameField]['name'];
    $fileSize = $files[$nameField]['size'];
    $fileTmpName  = $files[$nameField]['tmp_name'];
    $fileType = $files[$nameField]['type'];
    $tmp = explode('.',$fileName);
    $fileExtension = strtolower(end($tmp));

    if(!in_array($fileExtension,$fileExtensionsAllowed)) {
        $errors[] = "File extension not allowed";
    }

    if($fileSize > $maxSize) {
        $errors[] = "File greater than max size ("+ $maxSize +"b)";
    }

    if(empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $directoryToMove.$newName);
        if(!$didUpload) {
            $errors[] = "Error during upload, please contact administrator: ".$files[$nameField]['error'];
        } else {
            return true;
        }
    }
    return false;
}

/**
 * Display every error messages
 */
function echoErrors() {
    if(isset($_SESSION['errorMessage']) && !empty($_SESSION['errorMessage'])) {
        echo '<div class="errorMessage">';
        foreach ($_SESSION['errorMessage'] as $e) {
            echo "<p>".$e.'</p>';
            if($e !== end($_SESSION['errorMessage'])) {
                echo '<br/>';
            }
        }
        echo '</div>';
        $_SESSION['errorMessage'] = null;
    }
}

/**
 * Display every success messages
 */
function echoSuccess() {
    if(isset($_SESSION['successMessage']) && !empty($_SESSION['successMessage'])) {
        echo '<div class="successMessage">';
        foreach ($_SESSION['successMessage'] as $e) {
            echo "<p>".$e.'</p>';
            if($e !== end($_SESSION['successMessage'])) {
                echo '<br/>';
            }
        }
        echo '</div>';
        $_SESSION['successMessage'] = null;
    }
}
