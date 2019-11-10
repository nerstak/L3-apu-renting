<?php
    require_once (CFG_DB_CONNECTION);
    require_once ("login.php");
    require_once ("register.php");
    require_once ("forgot.php");

    require_once(RES_SQL);

    if($login['flag'] && !$register['flag'] && !$forgot['flag'] && !$edit['flag']) {
        if(!loginProcess($login,$dbConnection,$errorMsg)) {
            $_SESSION['errorMessage'] = $errorMsg;
            header("Location: portal.php");
        }
    } else if(!$login['flag'] && $register['flag'] && !$forgot['flag'] && !$edit['flag']) {
        if(!registerProcess($register,$dbConnection,$errorMsg)) {
            $_SESSION['errorMessage'] = $errorMsg;
            header("Location: portal.php");
        }
    } else if(!$login['flag'] && !$register['flag'] && $forgot['flag'] && !$edit['flag']) {

    }