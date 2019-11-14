<?php
session_start();

/* Basic includes */
require_once("../config/config.php");
include_once ("process/portalProcess.php");
include_once (RES_UTIL);

/* Display */
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);
echo'<main>';

// Redirection to main page if user is already logged
if (isset($_SESSION['logged']) && $_SESSION['logged']==true) {
    header("Location: index.php");
}

echoErrors();


if (isset($_GET['content'])) {
    if ($_GET['content'] == 'register') {
        loadRegister();
    } elseif ($_GET['content'] == 'forgot') {
        loadForgot();
    } else {
        loadLogin();
    }
} else {
    loadLogin();
}


echo '</main>';
include_once (VIEW_FOOTER);
include_once (VIEW_END);


/**
 * Display form of login
 */
function loadLogin()
{
    echo '<form class="classicForm boxStyle" action="portal.php" method="POST">
            <h2>Login</h2>
            <div><label>Email </label><br><input type="email" name="mailLogin" required></div>
            <div><label>Password </label><br><input type="password" name="passwordLogin" required>
            </div>
            <p style="display:none" id="errorMessageLogin"></p>
            <a href="?content=forgot">Forgot Password?</a>
            <a href="?content=register">New? Create an account</a>
            <button id="loginButton" type="submit" class="btn-success" name="loginForm">Login</button>
        </form>';
}

/**
 * Display form of register
 */
function loadRegister() {
    echo '<form class="classicForm boxStyle" autocomplete="off" action="portal.php" method="POST">
            <h2>Register a new account</h2>
            <div>
                <label>Email </label><br><input type="email" name="mailRegister" required>
            </div>
            <div>
                <label>Fornames </label><br><input type="text" name="fornamesRegister" required>
            </div>
            <div>
                <label>Last names </label><br><input type="text" name="lastnamesRegister" required>
            </div>
            <div>
                    <label>Address:</label><br><textarea name="addressRegister" value="default"></textarea>
                </div>
            <div>
                <label>Password </label><br>
                <input type="password" name="passwordRegister1" oninput="checkRegister()" required>
                <div class="tooltip" style="display:none" id="errorMessageRegisterP1">
                    <i class="material-icons md-24">error_outline</i>
                    <span class="tooltipText"></span>
                </div>
            </div>
            <div>
                <label>Confirm password </label><br>
                <input type="password" name="passwordRegister2" oninput="checkRegister()" required>
                <div class="tooltip" style="display:none" id="errorMessageRegisterP2">
                    <i class="material-icons md-24">error_outline</i>
                    <span class="tooltipText"></span>
                </div>
            </div>
            <p style="display:none" id="errorMessageRegister"></p>
            <button id="registerButton" type="submit" name="registerForm" class="btn-success">Register</button>
            <a href="?content=login">Already have an account?</a>
        </form>';
}

/**
 * Load form of forgot password
 */
function loadForgot() {
    echo '<form class="classicForm boxStyle" action="account.html" method="POST"><h2>Login</h2>
            <div><label>Email </label><br><input type="email" name="mailForgot" required></div><p style="display:none" id="errorMessageLogin"></p>
            <button id="forgotButton" type="submit" class="btn-success" name="forgotForm">Login</button></form>';
}