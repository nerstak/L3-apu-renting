<?php
$forgot = array();
$forgot['flag'] = true;

if (isset($_POST['registerForm'])) {
    if (isset($_POST['mailForgot'])) {
        $forgot['mail'] = $_POST['mailForgot'];
    } else {
        $forgot['flag'] = false;
    }
} else {
    $forgot['flag'] = false;
}