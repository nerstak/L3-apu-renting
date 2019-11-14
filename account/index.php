<?php
require_once("../config/config.php");
require_once(CFG_DB_CONNECTION);
require_once (RES_SQL);
require_once (RES_UTIL);

session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: portal.php");
}
include_once(VIEW_HEADER);
include_once(VIEW_NAVIGATION);
include_once(VIEW_ACCOUNT_NAV);

echoErrors();
echoSuccess();


if (isset($_GET['content'])) {
    if ($_GET['content'] == 'edit') {
        $dataUser = dataSelectionUser($dbConnection,$_SESSION['idUser']);
        loadEditForm($dataUser);
    } else {
        loadHistory($dbConnection);
    }
} else {
    loadHistory($dbConnection);
}



echo '</main>';

include_once(VIEW_FOOTER);
include_once(VIEW_END);

function loadEditForm($dataUser) {
    echo '<div id="editAccount" class="boxStyle">
            <form class="classicForm" autocomplete="off" action="process/editAccount.php" method="POST">
                <h2>Update your information</h2>';

    echo '<div>
                    <label>Email </label><br><input type="email" name="mailEdit" value="'.$dataUser['email'].'" required>
                </div>';
    echo '<div>
                <label>Fornames </label><br><input type="text" name="fornamesEdit" value="'.$dataUser['firstName'].'" required>
            </div>';
    echo '<div>
                <label>Last names </label><br><input type="text" name="lastnamesEdit" value="'.$dataUser['lastNames'].'" required>
            </div>';
    echo '<div>
                    <label>Address:</label><br><textarea name="addressEdit" value="default">'.$dataUser['address'].'</textarea>
                </div>';
    echo '<div>
                    <label>Password </label><br>
                    <input type="password" name="passwordEdit1" oninput="checkEdit()">
                    <div class="tooltip" style="display:none" id="errorMessageEditP1">
                        <i class="material-icons md-24">error_outline</i>
                        <span class="tooltipText"></span>
                    </div>
                </div>
                <div>
                    <label>Confirm password </label><br>
                    <input type="password" name="passwordEdit2" oninput="checkEdit()">
                    <div class="tooltip" style="display:none" id="errorMessageEditP2">
                        <i class="material-icons md-24">error_outline</i>
                        <span class="tooltipText"></span>
                    </div>
                </div>
                <p style="display:none" id="errorMessageEdit"></p>
                <button id="editButton" type="submit" name="editForm" class="btn-success">Edit account</button>
            </form>
        </div>';
}

function loadHistory(mysqli $dbConnection)
{
    echo '<div id="history" class="boxStyle">
            <table>
                <tr>
                    <th>Transaction ID</th>
                    <th>Equipement</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th></th>
                </tr> ';
    dataSelectionHistory($dbConnection);

    echo '</table></div>';
}