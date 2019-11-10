<?php
$login = array();
$login['flag'] = true;

if(isset($_POST['loginForm'])) {
    if(isset($_POST['mailLogin'])) {
        $login['email'] = $_POST['mailLogin'];
    } else {
        $login['flag'] = false;
    }

    if(isset($_POST['passwordLogin']) && $login['flag']) {
        $login['password'] = $_POST['passwordLogin'];
    } else {
        $login['flag'] = false;
    }
} else {
    $login['flag'] = false;
}

function loginProcess($login, mysqli $dbConnection, &$errorMessage) {
    if($stmt = $dbConnection->prepare("SELECT password,idUser FROM user WHERE email=?")) {
        $stmt->bind_param('s', $login['email']);
        $stmt->execute();
        $stmt->bind_result($passwordHash,$id);
        $stmt->fetch();

        if($passwordHash) {
            if(password_verify($login['password'],$passwordHash)) {
                echo 'Connected';
                session_start();
                $_SESSION['logged'] = true;
                $_SESSION['email'] = $login['email'];
                $_SESSION['idUser'] = $id;
                header("Location: index.php");
                return true;
            } else {
                $errorMessage = "Incorrect password";
            }
        } else {
            $errorMessage = "Unknown email address";
        }
    }
    if (!$stmt) {
        $errorMessage = "Error of connection";
    }
    return false;
}