<?php

/* Basic includes */
require_once("../../config/config.php");
session_start();

/* Specifig includes */
require_once (CFG_DB_CONNECTION);
require_once(RES_SQL);



$edit = array();
$edit['flag'] = true;

if (isset($_POST['editForm'])) {
    if (isset($_POST['mailEdit']) && strlen($_POST['mailEdit']) < 255) {
        $edit['email'] = $_POST['mailEdit'];
    } else {
        $_SESSION['errorMessage'] = "Error with mail address";
        $edit['flag'] = false;
    }

    $boolEval = $_POST['passwordEdit1'] == $_POST['passwordEdit2'] && strlen($_POST['passwordEdit1'] <= 12);
    if (isset($_POST['passwordEdit1']) && isset($_POST['passwordEdit2']) && $edit['flag'] && !empty($_POST['passwordEdit1'])) {
        if($boolEval) {
            $edit['passwordHash'] = password_hash($_POST['passwordEdit1'], PASSWORD_DEFAULT);
        }else {
            $_SESSION['errorMessage'] = "Error with password";
            $edit['flag'] = false;
        }
    } else {
        $edit['passwordHash'] = null;
    }

    if (isset($_POST['fornamesEdit']) && strlen($_POST['fornamesEdit']) < 255 && $edit['flag']) {
        $edit['firstName'] = $_POST['fornamesEdit'];
    } else {
        $edit['flag'] = false;
        $_SESSION['errorMessage'] = "Error with first name";
    }

    if (isset($_POST['lastnamesEdit']) && strlen($_POST['lastnamesEdit']) < 255 && $edit['flag']) {
        $edit['lastName'] = $_POST['lastnamesEdit'];
    } else {
        $_SESSION['errorMessage'] = "Error with last names";
        $edit['flag'] = false;
    }

    if (isset($_POST['addressEdit']) && strlen($_POST['addressEdit']) < 255 && $edit['flag']) {
        $edit['address'] = $_POST['addressEdit'];
    } else {
        $_SESSION['errorMessage'] = "Error with address";
        $edit['flag'] = false;
    }

    $edit['role'] = 'user';
    if(isset($_POST['roleEdit']) && $_POST['roleEdit'] == 'admin' && $_SESSION['admin'] == true ) {
        $edit['role'] = 'admin';
    }
} else {
    $edit['flag'] = false;
    $_SESSION['errorMessage'] = "Error during process";
}

editProcess($edit,$dbConnection,$_SESSION['idUser'],"../index.php?content=edit");
header("Location: ../index.php?content=edit");
