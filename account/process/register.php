<?php
$register = array();
$register['flag'] = true;

if (isset($_POST['registerForm'])) {
    if (isset($_POST['mailRegister']) && strlen($_POST['mailRegister']) < 255) {
        $register['email'] = $_POST['mailRegister'];
    } else {
        $register['flag'] = false;
    }

    $boolEval = $_POST['passwordRegister1'] == $_POST['passwordRegister2'] && strlen($_POST['passwordRegister1'] <= 10);
    if (isset($_POST['passwordRegister1']) && isset($_POST['passwordRegister2']) && $register['flag'] && $boolEval) {
        $register['passwordHash'] = password_hash($_POST['passwordRegister1'], PASSWORD_DEFAULT);

    } else {
        $register['flag'] = false;
    }

    if (isset($_POST['fornamesRegister']) && strlen($_POST['fornamesRegister']) < 255 && $register['flag']) {
        $register['firstName'] = $_POST['fornamesRegister'];
    } else {
        $register['flag'] = false;
    }

    if (isset($_POST['lastnamesRegister']) && strlen($_POST['lastnamesRegister']) < 255 && $register['flag']) {
        $register['lastName'] = $_POST['lastnamesRegister'];
    } else {
        $register['flag'] = false;
    }

    if (isset($_POST['addressRegister']) && strlen($_POST['addressRegister']) < 255 && $register['flag']) {
        $register['address'] = $_POST['addressRegister'];
    } else {
        $register['flag'] = false;
    }
} else {
    $register['flag'] = false;
}

function registerProcess($register, mysqli $dbConnection,&$errorMsg)
{
    if($stmt = $dbConnection->prepare("INSERT INTO user(firstname, lastnames, email, password, address, role) VALUES (?,?,?,?,?,?)")) {
        $role = "user";
        $stmt->bind_param('ssssss', $register['firstName'], $register['lastName'], $register['email'], $register['passwordHash'], $register['address'], $role);

        $stmt->execute();

        header("Location: index.php");

        return true;
    }
    $errorMsg = "Unable to create account";
    return false;
}