<?php
/* Basic includes and check */
session_start();
if(isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
    header("Location: ".DIR_BASE."/admin/index.php");
}
require_once("../config/config.php");
require_once(CFG_DB_CONNECTION);
require_once (RES_UTIL);

/* Display */
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);

/* Check login */
// Login input and login processing is made on the same page
if(isset($_POST['mailLogin']) && isset($_POST['passwordLogin'])) {
    if($stmt = $dbConnection->prepare("SELECT password,idUser FROM user WHERE email=? AND role='admin'")) {
        $stmt->bind_param('s', $_POST['mailLogin']);
        $stmt->execute();
        $stmt->bind_result($passwordHash, $id);
        $stmt->fetch();

        var_dump($passwordHash);
        if ($passwordHash) {
            if (password_verify($_POST['passwordLogin'], $passwordHash)) {
                $_SESSION['admin'] = true;
                $_SESSION['email'] = $_POST['mailLogin'];
                $_SESSION['idUser'] = $id;
                $_SESSION['logged'] = true;
                header("Location: index.php");
            } else {
                $_SESSION['errorMessage'][] = 'Incorrect log';
            }
        } else {
            $_SESSION['errorMessage'][] = 'Incorrect log';
        }
    }
}

echo '<main>';

echoErrors();

echo '<form class="classicForm boxStyle" action="login.php" method="POST">
            <h2>Login - Admin only</h2>
            <div><label>Email </label><br><input type="email" name="mailLogin" required></div>
            <div><label>Password </label><br><input type="password" name="passwordLogin" required>
            </div>
            <p style="display:none" id="errorMessageLogin"></p>
            <a href="../account">Regular account?</a></a>
            <button id="loginButton" type="submit" class="btn-success" name="loginForm">Login</button>
        </form>';


echo '</main>';
include_once(VIEW_FOOTER);
include_once(VIEW_END);