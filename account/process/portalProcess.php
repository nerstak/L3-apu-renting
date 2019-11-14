<?php
    require_once (CFG_DB_CONNECTION);
    require_once ("login.php");
    require_once ("register.php");
    require_once ("forgot.php");

    require_once(RES_SQL);


    if($login['flag'] && !$register['flag'] && !$forgot['flag']) {
        loginProcess($login,$dbConnection);
    } else if(!$login['flag'] && $register['flag'] && !$forgot['flag']) {
        registerProcess($register,$dbConnection);
    }
